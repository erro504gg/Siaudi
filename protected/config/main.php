<?php
/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo é parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  é um software livre; você pode redistribui-lo e/ou                   *
*  modificá-lo sob os termos da Licença Pública Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a versão 2 da                 *
*  Licença, como (a seu critério) qualquer versão posterior.                    *
*                                                                               *
*  SIAUDI é distribuído na expectativa de que seja útil,                        *
*  porém, SEM NENHUMA GARANTIA; nem mesmo a garantia implícita                  *
*  de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.                *
*  Consulte a Licença Pública Geral do GNU para mais detalhes em português:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Você deve ter recebido uma cópia da Licença Pública Geral do GNU             *
*  junto com este programa; se não, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
?>
<?php



require_once 'Zend/Loader/Autoloader.php';
spl_autoload_unregister(array('YiiBase', 'autoload'));
spl_autoload_register(array('Zend_Loader_Autoloader', 'autoload'));
spl_autoload_register(array('YiiBase', 'autoload'));

//date_default_timezone_set('America/Sao_Paulo');
//"localhost", 11211
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
//print(dirname(__FILE__) . DIRECTORY_SEPARATOR );exit;

// definindo url completa  ex: http://localhost/siaudi
//$url_completa_protocolo = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false) ? 'http' : 'https';

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'extensionPath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '../common/extensions',
    'name' => 'SIAUDI - Sistema de Auditoria Interna',
    'sourceLanguage' => 'pt_br',
    'language' => 'pt',
    'timeZone' => 'America/Sao_Paulo',
    'charset' => 'utf-8',
    // preloading 'log' component
    //'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.models.table.*',
        'application.models.view.*',
        'application.models.webservice.*',
        'application.components.*',
        'application.common.util.*',
        'application.modules.auditTrail.models.AuditTrail',
        'application.common.extensions.giix-components.*', // giix components
        'application.common.extensions.*',
    ),
    'modules' => array(
//Desabilitar módulo gii
//        'gii' => array(
//            'class' => 'system.gii.GiiModule',
//            'password' => 'admin',
//            'ipFilters' => array('127.0.0.1', '::1'),
//            'generatorPaths' => array(
//                'ext.giix-core', // giix generators
//            ),
//        ),
        'auditTrail'=>array(
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            'class' => 'WebUser',
            //'loginUrl' => array('site/logout'),
            'loginUrl'=>array('site/login'),
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'showScriptName' =>false,
            'urlFormat' => 'path',
            'caseSensitive' =>'false',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        
        
        
        // mpdf
        'ePdf' => array(
            
                   'class'        => 'application.common.extensions.EYiiPdf',
                   'params'       => array(
                       'mpdf'     => array(
                           'librarySourcePath' => 'application.common.extensions.mpdf.*',
                           'constants'         => array(
                               '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                           ),
                           'class'=>'mpdf', // the literal class filename to be loaded from the vendors folder.
                           /*'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
                               'mode'              => '', //  This parameter specifies the mode of the new document.
                               'format'            => 'A4', // format A4, A5, ...
                               'default_font_size' => 0, // Sets the default document font size in points (pt)
                               'default_font'      => '', // Sets the default font-family for the new document.
                               'mgl'               => 15, // margin_left. Sets the page margins for the new document.
                               'mgr'               => 15, // margin_right
                               'mgt'               => 16, // margin_top
                               'mgb'               => 16, // margin_bottom
                               'mgh'               => 9, // margin_header
                               'mgf'               => 9, // margin_footer
                               'orientation'       => 'P', // landscape or portrait orientation
                           )*/
                       ),
                       'HTML2PDF' => array(
                           'librarySourcePath' => 'application.common.extensions.html2pdf.*',
                           'classFile'         => 'html2pdf.class.php', // For adding to Yii::$classMap
                           /*'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
                               'orientation' => 'P', // landscape or portrait orientation
                               'format'      => 'A4', // format A4, A5, ...
                               'language'    => 'en', // language: fr, en, it ...
                               'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
                               'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
                               'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
                           )*/
                       )
                   ),
               ),        
        
        
        
        
        //REGRA_ESPECIFICA
        // banco de desenvolvimento
         'db' => array(
            'class' => 'application.components.MyDbConnection',
            'connectionString' => 'pgsql:host=localhost;port=5432;dbname=bd_siaudi',
            'emulatePrepare' => false,
            'username' => 'usrsiaudi',
            'password' => '!@#-usr-siaudi',
            'charset' => 'utf8',
            'tablePrefix' => 'tb_',
            'enableProfiling' => YII_DEBUG,
            'enableParamLogging' => YII_DEBUG,   
        ),
        
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                 //uncomment the following to show log messages on web pages
                /*array(
                    'class' => 'CWebLogRoute',
                    'levels' => 'trace',
                    'categories' => 'system.db.*',
                ),*/
                array(
                    'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                ),                
            ),
        ),
        'widgetFactory' => array(
            'widgets' => array( 
                'CDetailView' => array(
                    'nullDisplay' => ' ',
                ),
                'CJuiDatePicker' => array(
                    'language' => 'pt-BR',
                    'htmlOptions' => array('size' => 10),
                    'options' => array(
                        'showButtonPanel' => true,
                        'changeYear' => true,
                        'dateFormat' => 'dd/mm/yy',
                        'changeMonth' => true,
                        'changeYear' => true,
                        'buttonImage' => '/siaudi2/images/calendar.gif',
                        'buttonImageOnly' => true,
                        'showOn' => 'button',
                    ),
                ),
                'CJuiAutoComplete' => array(
                    'options' => array(
                        'showAnim' => 'fold',
                        'minLength' => '2',
                    ),
                    'htmlOptions' => array(
                        'class' => 'autocomplete',
                        'size' => '40'
                    ),
                ),
            ),
        ),
        'format' => array(
            'class' => 'application.components.MyFormatter',
            'timeFormat' => 'H:i:s',
            'numberFormat' => array(
                'decimals' => '2',
                'decimalSeparator' => ',',
                'thousandSeparator' => '.',
            ),
            'booleanFormat' => array(Yii::t('app', 'N\E3o'), Yii::t('app', 'Sim')),
        ),
        'clientScript' => array(
            'class' => 'ext.NLSClientScript',
            //'excludePattern' => '/\.tpl/i', //js regexp, files with matching paths won't be filtered is set to other than 'null'
        ),
        /* 'cache' => array(
          'class' => 'CMemCache',
          'servers' => array(
          array(
          'host' => 'localhost',
          'port' => 11211,
          ),
          ),
          ), */
        'request' => array(
           'class' => 'application.components.HttpRequest',
           'enableCsrfValidation' => false,
           'enableCookieValidation' => false,
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'email.de.contato@dominio.com.br',
        'emailGrupoAuditoria' => array('email@dominio.gov.br'),
        //'dominioEmail' => '@dominio.com.br',
        'id_aplicacao' => 'SIAUDI2',
        'tema' => 'brownTheme',
        'schema' => 'siaudi',
        'schema_corporativo' => 'public',
    	'limite_inferior_exercicio' => 1950,
        /* Valor em segundos. 
         * Para evitar conflito com o Garbage Collector o parâmetro session.gc_maxlifetime
         * no php.ini deve ser, pelo menos, igual ao valor de session_time
         */
        'session_time' => 1800 
    ),
);


function debug($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
    exit;
}      

function converte_acentos($string){
  $minusculo1 = array('ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ');
  $minusculo2 = array('&aacute;','&acirc;','&agrave;','&atilde;','&ccedil;','&eacute;','&ecirc;','&iacute;','&oacute;','&ocirc;','&otilde;','&uacute;','&uuml;');  
  
  $maiusculo1 = array('ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ','ï¿œ');
  $maiusculo2 = array('&Aacute;','&Acirc;','&Agrave;','&Atilde;','&Ccedil;','&Eacute;','&Ecirc;','&Iacute;','&Oacute;','&Ocirc;','&Otilde;','&Uacute;','&Uuml;');    
  
  $string = str_replace($minusculo1,$minusculo2,$string);
  $string = str_replace($maiusculo1,$maiusculo2,$string); 
  return ($string);
}
