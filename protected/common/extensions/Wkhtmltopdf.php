<?php

///
// * @manual http://madalgo.au.dk/~jakobt/wkhtmltoxdoc/wkhtmltopdf_0.10.0_rc2-doc.html
// * @author aur1mas <aur1mas@devnet.lt>
// * @author Charles SANQUER <charles.sanquer@spyrit.net>
// * @author Clement Herreman <clement.herreman@pictime.com>
// * @copyright aur1mas <aur1mas@devnet.lt>
// * @license http://framework.zend.com/license/new-bsd New BSD License
// * @see Repository: https://github.com/aur1mas/Wkhtmltopdf
// * @version 1.10
// /
class Wkhtmltopdf {

    /**
     * Setters / Getters properties.
     */
    protected $_html = null;
    protected $_url = null;
    protected $_orientation = null;
    protected $_pageSize = null;
    protected $_toc = false;
    protected $_copies = 1;
    protected $_grayscale = false;
    protected $_title = null;
    protected $_xvfb = false;
    protected $_path; // path to directory where to place files
    protected $_zoom = 1;
    protected $_footerHtml;
    protected $_headerHtml;
    protected $_username;
    protected $_password;
    protected $_windowStatus;
    protected $_margins = array('top' => null, 'bottom' => null, 'left' => null, 'right' => null);
    protected $_options = array();

    /**
     * Path to executable.
     */
    protected $_bin = '/usr/local/bin/wkhtmltopdf'; //'/usr/bin/wkhtmltopdf';
    protected $_filename = null; // filename in $path directory
    protected $_prefixoArquivo = null; // prefixo dos nomes dos arquivos de Cabeçalho e Rodapé
    protected $_createdHeader = false; // prefixo dos nomes dos arquivos de Cabeçalho e Rodapé
    protected $_createdFooter = false; // prefixo dos nomes dos arquivos de Cabeçalho e Rodapé    
    

    /**
     * Available page orientations.
     */

    const ORIENTATION_PORTRAIT = 'Portrait'; // vertical
    const ORIENTATION_LANDSCAPE = 'Landscape'; // horizontal
    /**
     * Page sizes.
     */
    const SIZE_A4 = 'A4';
    const SIZE_LETTER = 'letter';

    /**
     * File get modes.
     */
    const MODE_DOWNLOAD = 0;
    const MODE_STRING = 1;
    const MODE_EMBEDDED = 2;
    const MODE_SAVE = 3;

    /**
     * @param array $options
     */
    public function __construct(array $options = array()) {
        if (array_key_exists('html', $options)) {
            $this->setHtml($options['html']);
        }
        if (array_key_exists('orientation', $options)) {
            $this->setOrientation($options['orientation']);
        } else {
            $this->setOrientation(self::ORIENTATION_PORTRAIT);
        }
        if (array_key_exists('page_size', $options)) {
            $this->setPageSize($options['page_size']);
        } else {
            $this->setPageSize(self::SIZE_A4);
        }
        if (array_key_exists('toc', $options)) {
            $this->setTOC($options['toc']);
        }
        if (array_key_exists('margins', $options)) {
            $this->setMargins($options['margins']);
        }
        if (array_key_exists('binpath', $options)) {
            $this->setBinPath($options['binpath']);
        }
        if (array_key_exists('window-status', $options)) {
            $this->setWindowStatus($options['window-status']);
        }
        if (array_key_exists('grayscale', $options)) {
            $this->setGrayscale($options['grayscale']);
        }
        if (array_key_exists('title', $options)) {
            $this->setTitle($options['title']);
        }
        if (array_key_exists('footer_html', $options)) {
            $this->setFooterHtml($options['footer_html']);
        }
        if (array_key_exists('header_html', $options)) {
            $this->setHeaderHtml($options['header_html']);
        }
        if (array_key_exists('xvfb', $options)) {
            $this->setRunInVirtualX($options['xvfb']);
        }
        if (!array_key_exists('path', $options)) {
            throw new Exception("Path to directory where to store files is not set");
        }
        if (!is_writable($options['path'])) {
            throw new Exception("Path to directory where to store files is not writable");
        }
        $this->setPath($options['path']);
        $this->_createFile(null,'body',$this->getHtml());
    }

    /**
     * Creates file to which will be writen HTML content.
     *
     * @return string
     */
    protected function _createFile($prefixo = null, $complemento = '', $conteudo = '') {
        do {
            if($prefixo){
                $this->_prefixoArquivo = (string) $prefixo;
                $prefixo = null;
            }else{
                $this->_prefixoArquivo = mt_rand();
            }
            $filePath = $this->getPath() . $this->_prefixoArquivo . $complemento . '.html';
            if($complemento == 'body'){
                $this->_filename = $filePath;
            }
        } while (file_exists($filePath));

        file_put_contents($filePath, $conteudo);
        chmod($filePath, 0777);
        return $filePath;
    }

    /**
     * Returns file path where HTML content is saved.
     *
     * @return string
     */
    public function getFilePath() {
        return $this->_filename;
    }

    /**
     * Retorna o prefixo do arquivo HTML
     * 
     * @return string
     */
    public function getPrefixo() {
        return $this->_prefixoArquivo;
    }
    
    /**
     * Executes command.
     *
     * @param string $cmd command to execute
     * @param string $input other input (not arguments)
     * @return array
     */
    protected function _exec($cmd, $input = "") {
        $result = array('stdout' => '', 'stderr' => '', 'return' => '');
        $proc = proc_open($cmd, array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
        fwrite($pipes[0], $input);
        fclose($pipes[0]);
        $result['stdout'] = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $result['stderr'] = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $result['return'] = proc_close($proc);
        return $result;
    }

    /**
     * Returns help info.
     *
     * @return string
     */
    public function getHelp() {
        $r = $this->_exec($this->_bin . " --extended-help");
        return $r['stdout'];
    }

    /**
     * Sets the PDF margins.
     *
     * @param $margins array<position => value> The margins.
     * * Possible <position> :
     * * top : sets the margin on the top of the PDF
     * * bottom : sets the margin on the bottom of the PDF
     * * left : sets the margin on the left of the PDF
     * * right : sets the margin on the right of the PDF
     * * Value : size of the margin (positive integer). Null to leave the default one.
     * @return Wkhtmltopdf $this
     */
    public function setMargins($margins) {
        $this->_margins = array_merge($this->_margins, $margins);
        return $this;
    }

    /**
     * Gets the PDF margins.
     *
     * @return array See $this->setMargins()
     * @see $this->setMargins()
     */
    public function getMargins() {
        return $this->_margins;
    }

    /**
     * Sets additional command line options.
     *
     * @param $options array<option => value> The additional options to set.
     * For command line options with no value, set $options value to NULL.
     * @return Wkhtmltopdf $this
     */
    public function setOptions($options) {
        $this->_options = array_merge($this->_options, $options);
        return $this;
    }

    /**
     * Gets the custom command line options.
     *
     * @return array See $this->setOptions()
     * @see $this->setOptions()
     */
    public function getOptions() {
        return $this->_options;
    }

    /**
     * Set wkhtmltopdf to wait when `window.status` on selected page changes to setted status, and after that render PDF.
     *
     * @param string $windowStatus
     * we add a `--window-status {$windowStatus}` for execution to `$this->_bin`
     * @return Wkthmltopdf
     */
    public function setWindowStatus($windowStatus) {
        $this->_windowStatus = (string) $windowStatus;
        return $this;
    }

    /**
     * Get the window status.
     *
     * @return string See $this->setWindowStatus()
     * @see $this->setWindowStatus()
     */
    public function getWindowStatus() {
        return $this->_windowStatus;
    }

    /**
     * Set HTML content to render.
     *
     * @param string $html
     * @return Wkthmltopdf
     */
    public function setHtml($html) {
        $this->_html = (string) $html;
        return $this;
    }

    /**
     * Returns HTML content.
     *
     * @return string
     */
    public function getHtml() {
        return $this->_html;
    }

    /**
     * Set URL to render.
     *
     * @param string $html
     * @return Wkthmltopdf
     */
    public function setUrl($url) {
        $this->_url = (string) $url;
        return $this;
    }

    /**
     * Returns URL.
     *
     * @return string
     */
    public function getUrl() {
        return $this->_url;
    }

    /**
     * Absolute path where to store files.
     *
     * @throws Exception
     * @param string $path
     * @return Wkthmltopdf
     */
    public function setPath($path) {
        if (realpath($path) === false) {
            throw new Exception("Path must be absolute");
        }
        $this->_path = realpath($path) . DIRECTORY_SEPARATOR;
        return $this;
    }

    /**
     * Returns path where to store saved files.
     *
     * @return string
     */
    public function getPath() {
        return $this->_path;
    }

    /**
     * Set page orientation.
     *
     * @param string $orientation
     * @return Wkthmltopdf
     */
    public function setOrientation($orientation) {
        $this->_orientation = (string) $orientation;
        return $this;
    }

    /**
     * Returns page orientation.
     *
     * @return string
     */
    public function getOrientation() {
        return $this->_orientation;
    }

    /**
     * Sets the page size.
     *
     * @param string $size
     * @return Wkthmltopdf
     */
    public function setPageSize($size) {
        $this->_pageSize = (string) $size;
        return $this;
    }

    /**
     * Returns page size.
     *
     * @return int
     */
    public function getPageSize() {
        return $this->_pageSize;
    }

    /**
     * Set the zoom level.
     *
     * @return string
     */
    public function setZoom($zoom) {
        $this->_zoom = $zoom;
        return $this;
    }

    /**
     * Returns zoom level.
     *
     * @return int
     */
    public function getZoom() {
        return $this->_zoom;
    }

    /**
     * Enable / disable generation Table Of Contents.
     *
     * @param boolean $toc
     * @return Wkhtmltopdf
     */
    public function setTOC($toc = true) {
        $this->_toc = (boolean) $toc;
        return $this;
    }

    /**
     * Returns value is enabled Table Of Contents generation or not.
     *
     * @return boolean
     */
    public function getTOC() {
        return $this->_toc;
    }

    /**
     * Returns bin path.
     *
     * @return string
     */
    public function getBinPath() {
        return $this->_bin;
    }

    /**
     * Returns bin path.
     *
     * @return string
     */
    public function setBinPath($path) {
        if (file_exists($path)) {
            $this->_bin = (string) $path;
        }
        return $this;
    }

    /**
     * Set number of copies.
     *
     * @param int $copies
     * @return Wkthmltopdf
     */
    public function setCopies($copies) {
        $this->_copies = (int) $copies;
        return $this;
    }

    /**
     * Returns number of copies to make.
     *
     * @return int
     */
    public function getCopies() {
        return $this->_copies;
    }

    /**
     * Whether to print in grayscale or not.
     *
     * @param boolean $mode
     * @return Wkthmltopdf
     */
    public function setGrayscale($mode) {
        $this->_grayscale = (boolean) $mode;
        return $this;
    }

    /**
     * Returns is page will be printed in grayscale format.
     *
     * @return boolean
     */
    public function getGrayscale() {
        return $this->_grayscale;
    }

    /**
     * If TRUE, runs wkhtmltopdf in a virtual X session.
     *
     * @param bool $xvfb
     * @return Wkthmltopdf
     */
    public function setRunInVirtualX($xvfb) {
        $this->_xvfb = (bool) $xvfb;
        return $this;
    }

    /**
     * If TRUE, runs wkhtmltopdf in a virtual X session.
     *
     * @return bool
     */
    public function getRunInVirtualX() {
        if ($this->_xvfb) {
            return $this->_xvfb;
        }
    }

    /**
     * Set the PDF title.
     *
     * @param string $title
     * @return Wkthmltopdf
     */
    public function setTitle($title) {
        $this->_title = (string) $title;
        return $this;
    }

    /**
     * Returns PDF document title.
     *
     * @throws Exception
     * @return string
     */
    public function getTitle() {
        if ($this->_title) {
            return $this->_title;
        }
    }

    /**
     * Set footer html.     *
     * @param string $footer
     * @return Wkthmltopdf
     */
    public function setFooterHtml($footer) {
        $this->_footerHtml = (string) $footer;
        return $this;
    }

    /**
     * Get footer html.
     *
     * @return string
     */
    public function getFooterHtml() {
        return $this->_footerHtml;
    }

    /**
     * Set header html.
     *
     * @param string $header
     * @return Wkthmltopdf
     */
    public function setHeaderHtml($header) {
        $this->_headerHtml = (string) $header;
        return $this;
    }

    /**
     * Get header html.
     *
     * @return string
     */
    public function getHeaderHtml() {
        return $this->_headerHtml;
    }
    
    /**
     * Set HTTP username.
     *
     * @param string $username
     * @return Wkthmltopdf
     */
    public function setUsername($username) {
        $this->_username = (string) $username;
        return $this;
    }

    /**
     * Get HTTP username.
     *
     * @return string
     */
    public function getUsername() {
        return $this->_username;
    }

    /**
     * Set http password.
     *
     * @param string $password
     * @return Wkthmltopdf
     */
    public function setPassword($password) {
        $this->_password = (string) $password;
        return $this;
    }

    /**
     * Get http password.
     *
     * @return string
     */
    public function getPassword() {
        return $this->_password;
    }

    public function getCommand() {
        return $this->_getCommand();
    }

    /**
     * Indica que o cabeçalho foi criado com base em um conteúdo passado
     * 
     * @param type $createdHeader
     * @return \Wkhtmltopdf
     */
    public function setCreatedHeader($createdHeader) {
        $this->_createdHeader = $createdHeader;
        return $this;
    }

    /**
     * Retorna se o arquivo físico do cabeçalho foi criado pela aplicação
     */
    public function isCreatedHeader() {
        return $this->_createdHeader;
    }
    
    /**
     * Indica que o rodapé foi criado com base em um conteúdo passado
     * 
     * @param type $createdFooter
     * @return \Wkhtmltopdf
     */
    public function setCreatedFooter($createdFooter) {
        $this->_createdFooter = $createdFooter;
        return $this;
    }

    /**
     * Retorna se o arquivo físico do rodapé foi criado pela aplicação
     */
    public function isCreatedFooter() {
        return $this->_createdFooter;
    }

    

    
    /**
     * Cria os arquivos de Cabeçalho e Rodapé e os preenche com o respectivo conteúdo
     * 
     * @param type $header Conteúdo do Cabeçalho
     * @param type $footer Conteúdo do Rodapé
     */
    public function setHeaderFooterContent($header = null, $footer = null) {
        if($header){
            $arquivoHeader = $this->_createFile($this->getPrefixo(), $complemento = 'header', $header);
            $this->setHeaderHtml($arquivoHeader);
            $this->setCreatedHeader(true);
        }

        if($footer){
            $arquivoFooter = $this->_createFile($this->getPrefixo(), $complemento = 'footer', $footer);
            $this->setFooterHtml($arquivoFooter);
            $this->setCreatedFooter(true);
        }
    }
    
    
    /**
     * Returns command to execute.
     *
     * @return string
     */
    protected function _getCommand() {
        $command = $this->_bin;
        $command .= ($this->getCopies() > 1) ? " --copies " . $this->getCopies() : "";
        $command .= " --orientation " . $this->getOrientation();
        $command .= " --page-size " . $this->getPageSize();
        $command .= " --zoom " . $this->getZoom();
        foreach ($this->getMargins() as $position => $margin) {
            $command .= (!is_null($margin)) ? sprintf(' --margin-%s %s', $position, $margin) : '';
        }
        foreach ($this->getOptions() as $key => $value) {
            $command .= " --$key $value";
        }
        $command .= ($this->getWindowStatus()) ? " --window-status " . $this->getWindowStatus() . "" : "";
        $command .= ($this->getTOC()) ? " --toc" : "";
        $command .= ($this->getGrayscale()) ? " --grayscale" : "";
        $command .= (mb_strlen($this->getPassword()) > 0) ? " --password " . $this->getPassword() . "" : "";
        $command .= (mb_strlen($this->getUsername()) > 0) ? " --username " . $this->getUsername() . "" : "";
        $command .= (mb_strlen($this->getFooterHtml()) > 0) ? " --margin-bottom 20 --footer-line --footer-html \"" . $this->getFooterHtml() . "\"" : "";
        $command .= (mb_strlen($this->getHeaderHtml()) > 0) ? " --margin-top 45 --header-font-size 10 --header-spacing 10 --header-html \"" . $this->getHeaderHtml() . "\"" : "";
        $command .= ($this->getTitle()) ? ' --title "' . $this->getTitle() . '"' : '';
        $command .= ' "%input%"';
        $command .= " -";
        if ($this->getRunInVirtualX()) {
            $command = 'xvfb-run ' . $command;
        }
        return $command;
    }

    /**
     * @todo use file cache
     *
     * @throws Exception
     * @return string
     */
    protected function _render() {
        if (mb_strlen($this->_html, 'utf-8') === 0 && empty($this->_url)) {
            throw new Exception("HTML content or source URL not set");
        }
        if ($this->getUrl()) {
            $input = $this->getUrl();
        } else {
            //getFilePath() retorna o nome do arquivo completo
            file_put_contents($this->getFilePath(), $this->getHtml());
            $input = $this->getFilePath();
        }
        $content = $this->_exec(str_replace('%input%', $input, $this->_getCommand()));
        if (strpos(mb_strtolower($content['stderr']), 'error')) {
            throw new Exception("System error <pre>" . $content['stderr'] . "</pre>");
        }
        if (mb_strlen($content['stdout'], 'utf-8') === 0) {
            throw new Exception("WKHTMLTOPDF didn't return any data");
        }
        if ((int) $content['return'] > 1) {
            throw new Exception("Shell error, return code: " . (int) $content['return']);
        }
        return $content['stdout'];
    }

    /**
     * Create the PDF file.
     *
     * @param int $mode
     * @param string $filename
     */
    public function output($mode, $filename) {
        switch ($mode) {
            case self::MODE_DOWNLOAD:
                if (!headers_sent()) {
                    $result = $this->_render();
                    header("Content-Description: File Transfer");
                    header("Cache-Control: public; must-revalidate, max-age=0");
                    header("Pragma: public");
                    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
                    header("Last-Modified: " . gmdate('D, d m Y H:i:s') . " GMT");
                    header("Content-Type: application/force-download");
                    header("Content-Type: application/octec-stream", false);
                    header("Content-Type: application/download", false);
                    header("Content-Type: application/pdf", false);
                    header('Content-Disposition: attachment; filename="' . basename($filename) . '";');
                    header("Content-Transfer-Encoding: binary");
                    header("Content-Length: " . strlen($result));
                    echo $result;
                    $this->eraseTempFiles();
                    exit();
                } else {
                    throw new Exception("Headers already sent");
                }
                break;
            case self::MODE_STRING:
                return $this->_render();
                break;
            case self::MODE_EMBEDDED:
                if (!headers_sent()) {
                    $result = $this->_render();
                    header("Content-type: application/pdf");
                    header("Cache-control: public, must-revalidate, max-age=0");
                    header("Pragme: public");
                    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
                    header("Last-Modified: " . gmdate('D, d m Y H:i:s') . " GMT");
                    header("Content-Length: " . strlen($result));
                    header('Content-Disposition: inline; filename="' . basename($filename) . '";');
                    echo $result;
                    $this->eraseTempFiles();
                    exit();
                } else {
                    throw new Exception("Headers already sent");
                }
                break;
            case self::MODE_SAVE:
                file_put_contents($this->getPath() . $filename, $this->_render());
                $this->eraseTempFiles();
                break;
            default:
                throw new Exception("Mode: " . $mode . " is not supported");
        }
    }
    
    /**
     * Apaga os arquivos HTML temporários criados para a geração do PDF
     * 
     */
    public function eraseTempFiles() {

       $filepath = $this->getFilePath();
       if (!empty($filepath)){
           unlink($filepath);
       }
       $filepath = $this->getHeaderHtml();
       $condicao = !empty($filepath) && ($this->isCreatedHeader()===true);
       if ($condicao){
           unlink($filepath);
       }
       $filepath = $this->getFooterHtml();
       $condicao = !empty($filepath) && ($this->isCreatedFooter()===true);
       if ($condicao){
           unlink($filepath);
       }
    }   
}
