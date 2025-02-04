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
/**
 * CMask
 * 
 * Esta classe possuia muitos métodos com diferentes finalidades, tornando-a 
 * dificil de entender e manter.
 * 
 * Além disso, existem métodos duplicados ou com substitutos diretos por funções
 * nativas do php, como as funções de data e a classe DateTime da SPL/PHP.
 * 
 * Por isso, foi criado um conjunto de novas classes separando as funções por 
 * finalidade, que devem ser utilizadas em detrimento as aqui descritas.
 * 
 * **Evite** o uso desta classe.
 * 
 * @link CMask2
 * @link CTransform
 * @link CValidate
 * @deprecated Substituida pelas classes CMask2, CTransform e CValidate
  */
class CMask {

    /**
     * REMOVE MASCARAS COM . / - ( )
     * @deprecated 
     * @see CMask2::removeMascara() Veja substituta em CMask2
     * */
    public function removeMascara($param) {
        $masc = array('.', '-', '/', '(', ')');
        return str_replace($masc, '', $param);
    }

    /**
     * ADICIONA MASCARAS
     * @deprecated 
     * @see CMask2::cnpj() Veja substituta em CMask2
     * */
    public function getFormataCNPJ($strCNPJ) {
        if (!empty($strCNPJ))
            return substr($strCNPJ, 0, 2) . '.' . substr($strCNPJ, 2, 3) . '.' . substr($strCNPJ, 5, 3) . '/' . substr($strCNPJ, 8, 4) . '-' . substr($strCNPJ, 12, 2);
        else
            return null;
    }

    /**
     * @deprecated 
     * @see CMask2::cep() Veja substituta em CMask2
     */
    public function getFormataCEP($strCEP) {
        if (!empty($strCEP))
            return substr($strCEP, 0, 2) . '.' . substr($strCEP, 2, 3) . '-' . substr($strCEP, 5, 3);
        else
            return null;
    }

    /**
     * @deprecated 
     * @see CMask2::cda() Veja substituta em CMask2
     */
    public function getFormataCDA($strCDA) {
        if (!empty($strCDA))
            return substr($strCDA, 0, 2) . '.' . substr($strCDA, 2, 4) . '.' . substr($strCDA, 6, 4) . '-' . substr($strCDA, 10, 1);
        else
            return null;
    }

    /**
     * @deprecated 
     * @see CMask2::cpr() Veja substituta em CMask2
     */
    public function getFormataCPR($strCPR) {
        if (!empty($strCPR))
            return substr($strCPR, 0, 4) . '.' . substr($strCPR, 4, 2) . '.' . substr($strCPR, 6, 4);
        else
            return null;
    }

    /**
     * @deprecated 
     * @see CMask2::cpf() Veja substituta em CMask2
     */
    public function getFormataCPF($strCPF) {
        if (!empty($strCPF))
            return substr($strCPF, 0, 3) . '.' . substr($strCPF, 3, 3) . '.' . substr($strCPF, 6, 3) . '-' . substr($strCPF, 9, 2);
        else
            return null;
    }

    /**
     * @deprecated 
     * @see CMask2::telefone() Veja substituta em CMask2
     */
    public function getFormataTelefone($strTelefone) {
        if (!empty($strTelefone))
            return '(' . substr($strTelefone, 0, 2) . ') ' . substr($strTelefone, 2, 4) . '-' . substr($strTelefone, 6, 4);
        else
            return null;
    }

    /**
     * @deprecated 
     * @see CMask2::safra() Veja substituta em CMask2
     */
    public function getFormataSafra($strSafra) {
        if (!empty($strSafra))
            return substr($strSafra, 0, 4) . '/' . substr($strSafra, 4, 4);
        else
            return null;
    }

    /**
     * @deprecated 
     * @see CMask2::chaveAcessoNfe() Veja substituta em CMask2
     */
    public function getFormataChaveAcesso($cNfe) {
        return substr($cNfe, 0, 2) . '-' . substr($cNfe, 2, 4) . '-' . substr($cNfe, 6, 2) . '.' . substr($cNfe, 8, 3) . '.' . substr($cNfe, 11, 3) . '/' . substr($cNfe, 14, 4) . '-' . substr($cNfe, 18, 2) . '-' . substr($cNfe, 20, 2) . '-' . substr($cNfe, 22, 3) . '-' . substr($cNfe, 25, 3) . '.' . substr($cNfe, 28, 3) . '.' . substr($cNfe, 31, 3) . '-' . substr($cNfe, 34, 3) . '.' . substr($cNfe, 37, 3) . '.' . substr($cNfe, 40, 3) . '-' . substr($cNfe, 43, 1);
    }

    /**
     * @deprecated 
     * @see CMask2::codigoEntregaCpr() Veja substituta em CMask2
     */
    public function getFormataCodigoEntregaCPR($codigo_entrega_cpr) {
        return substr($codigo_entrega_cpr, 0, 3) . '.' . substr($codigo_entrega_cpr, 3, 3) . '.' . substr($codigo_entrega_cpr, 6, 3) . '.' . substr($codigo_entrega_cpr, 9, 2) . '-' . substr($codigo_entrega_cpr, 11, 1);
    }

    /**
     * @deprecated 
     * @see CMask2::processoConab() Veja substituta em CMask2
     */
    public function getFmtProcesso($strProc) {
        return substr($strProc, 0, 5) . '.' . substr($strProc, 5, 6) . '/' . substr($strProc, 11, 4) . '-' . substr($strProc, 15, 2);
    }

    /**
     * @deprecated 
     * @see CMask2::cpfOuCnpj() Veja substituta em CMask2
     */
    public function getFormataCpfCnpj($numeroDocumento) {
        if (strlen($numeroDocumento) == 11) {
            return CMask::getFormataCPF($numeroDocumento);
        } else if (strlen($numeroDocumento) == 14) {
            return CMask::getFormataCNPJ($numeroDocumento);
        } 
    }

    /**
     * Adiciona mascara para o usuario
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     * @author junio.santos
     * @param data
     * @param time (retorna com hora ou nao)
     * @return retorna a data formatada para o usuario
     * */
    public function addMaskUserDate($data, $time = true) {

        if (strlen($data) >= 19 && strpos($data, '-') !== false) {
            $ano = substr($data, 0, 4);
            $mes = substr($data, 5, 2);
            $dia = substr($data, 8, 2);
            $hora = substr($data, 11, 2);
            $minuto = substr($data, 14, 2);
            $segundo = substr($data, 17, 2);
            if ($time)
                return $dia . '/' . $mes . '/' . $ano . ' ' . $hora . ':' . $minuto . ':' . $segundo;
            else
                return $dia . '/' . $mes . '/' . $ano;
        }
        if (strpos($data, '-') === false) {
            $ano = substr($data, 0, 4);
            $mes = substr($data, 4, 2);
            $dia = substr($data, 6, 2);

            return $dia . '/' . $mes . '/' . $ano;
        } else if (strpos($data, '-') !== false) {
            $array = explode('-', $data);
            return $array[2] . '/' . $array[1] . '/' . $array[0];
        }
    }

    /**
     * Remove a mascara para o banco
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     * @author junio.santos
     * @param data
     * @return retorna a data formatada para o banco sem traco -
     * */
    public function addMaskBdDate($data) {
        if (strlen($data) > 8) {
            $array = explode('/', $data);
            if (count($array) >= 3)
                return $array[2] . $array[1] . $array[0];
        }
    }

    /**
     * Remove a mascara para o banco
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     * @author junio.santos
     * @param data
     * @param time (retorna com hora ou nao)
     * @return retorna a data formatada para o banco com traco -
     * */
    public function addMaskBdDatePG($data, $time = false) {

        if (strlen($data) > 8 && strpos($data, '/') !== false) {
            $array = explode('/', $data);
            $ano_hora = explode(' ', $array[2]);
            if ($time)
                return $ano_hora[0] . '-' . $array[1] . '-' . $array[0] . ' ' . $ano_hora[1];
            if (count($array) >= 3)
                return $array[2] . '-' . $array[1] . '-' . $array[0];
        }
    }

    /**
     * Valida o cpf
     * @deprecated
     * @see CValidate::cpf() Veja substituta em CValidate
     * @param CModel the model to be validated
     * @return bool
     */
    public function validaCPF($cpf) { // Verifiva se o numero digitado contem todos os digitos
        $cpf = str_replace('.', '', str_replace('-', '', $cpf));

        for ($i = 0; $i < 10; $i++) {
            if ($cpf == str_repeat($i, 11) or !preg_match("@^[0-9]{11}$@", $cpf) or $cpf == "12345678909"
            )
                return false;
            if ($i < 9)
                $soma[] = $cpf{$i} * ( 10 - $i );
            $soma2[] = $cpf{$i} * ( 11 - $i );
        }
        if (((array_sum($soma) % 11) < 2 ? 0 : 11 - ( array_sum($soma) % 11 )) != $cpf{9}
        )
            return false;
        return ((( array_sum($soma2) % 11 ) < 2 ? 0 : 11 - ( array_sum($soma2) % 11 )) != $cpf{10}) ? false : true;
    }

    /**
     * @deprecated
     * @see CValidate::cnpj() Veja substituta em CValidate
     * @param $str string
     * @return bool
     */
    public function validaCnpj($str) {
        if (!preg_match('|^(\d{2,3})\.?(\d{3})\.?(\d{3})\/?(\d{4})\-?(\d{2})$|', $str, $matches))
            return false;

        array_shift($matches);
        $str = implode('', $matches);

        if (strlen($str) <> 14) {
            return false;
        }
        $calcular = 0;
        $calcularDois = 0;
        for ($i = 0, $x = 5; $i <= 11; $i++, $x--) {
            $x = ($x < 2) ? 9 : $x;
            $number = substr($str, $i, 1);
            $calcular += $number * $x;
        }

        for ($i = 0, $x = 6; $i <= 12; $i++, $x--) {
            $x = ($x < 2) ? 9 : $x;
            $numberDois = substr($str, $i, 1);
            $calcularDois += $numberDois * $x;
        }

        $digitoUm = (($calcular % 11) < 2) ? 0 : 11 - ($calcular % 11);
        $digitoDois = (($calcularDois % 11) < 2) ? 0 : 11 - ($calcularDois % 11);

        if ($digitoUm <> substr($str, 12, 1) || $digitoDois <> substr($str, 13, 1)) {
            return false;
        }
        return true;
    }

    /**
     * Realiza a validacao de Documento, sendo ele CPF *OU* CNPJ
     * @deprecated
     * @see CValidate::cpfOuCnpj() Veja substituta em CValidate
     * @param string $str CPF ou CNPJ a ser validado
     * @return boolean Resultado da verificacao
     */
    public function validaCpfCnpj($str) {
        $numeroDocumento = preg_replace('/[^0-9]/', '', $str);

        if (strlen($numeroDocumento) == 11) {
            return CMask::validaCPF($str);
        } else if (strlen($numeroDocumento) == 14) {
            return CMask::validaCnpj($str);
        } 

        return false;
    }

    /**
     * Funcao para retirar acentos, caracteres especiais de uma string
     * @deprecated Use a função URLENCODE() nativa do PHP.
     * @param $string
     * @return $string
     */
    function formattourl($string) {
        $palavra = strtr($string, "???????¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
        $palavranova = str_replace(" ", "_", $palavra);
        return $palavranova;
    }

    /**
     * Função para remover os acentos e caracteres especiais, no entanto sem remover os espaços.
     * @deprecated
     * @see CTransform::removeEspeciais() Veja substituta em CTransform
     * @param type $string
     * @return type
     */
    function retiraAcentos($string) {
        $palavra = strtr($string, "???????¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
        return $palavra;
    }

    /**
     * Funcaoo para trocar virgula por ponto
     * @deprecated
     * @see CTransform::stringParaValor() Veja substituta em CTransform
     * @param $string
     * @return $string
     */
    function formataValorParaBanco($string) {
        if (!empty($string)) {
            if (strpos($string, ',')) {
                $string = str_replace(".", "", $string);
                $string = (double) str_replace(",", ".", $string);
            }
        } else {
            $string = (double) 0;
        }
        return $string;
    }

    /**
     * Funcao para trocar virgula por ponto
     * @deprecated
     * @see CTransform::stringParaValor() Veja substituta em CTransform
     * @param $string
     * @return $string
     */
    function formataValorParaBancoInteiro($string) {
        if (!empty($string)) {
            if (strpos($string, ',')) {
                $string = str_replace(".", "", $string);
                $string = intval(str_replace(",", ".", $string));
            } else if (strpos($string, '.')) {
                $string = intval(str_replace(".", "", $string));
            }
        } else {
            $string = (integer) 0;
        }
        return $string;
    }

    /**
     * Funcao para preencher zeros a esquerda
     * Prenche uma string com zeros a esquerda ex: 9 -> 0009
     * @deprecated Use a função STR_PAD() nativa do PHP
     * @param $valorSemZeros
     * @param $qtdDigitos
     * @return $strValor
     */
    function addZerosEsquerda($valorSemZeros, $qtdDigitos = 2) {
        $valorSemZeros = $valorSemZeros . "";
        $strValor = $valorSemZeros;
        $tamanho = strlen($valorSemZeros);
        if ($tamanho < $qtdDigitos) {
            for ($i = 0; $i < ($qtdDigitos - $tamanho); $i++) {
                $strValor = "0" . $strValor;
            }
        }
        return $strValor;
    }

    /**
     * Mascara para datas no estilo MM/YYYY
     * Exemplo, Transforma 201109 => 09/2011
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     * @param $data
     * @return $mes . '/' . $ano
     */
    public function maskMesAno($data) {
        if (strlen($data) == 6) {
            $ano = substr($data, 0, 4);
            $mes = substr($data, 4, 2);

            return $mes . '/' . $ano;
        }
    }

    /**
     * Mascara para datas no estilo MM/YYYY
     * Exemplo, Transforma 09/2011 ==> 201109
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     * @param $string
     * @return $ano . $mes
     */
    public function removeMaskMesAno($string) {
        $string = str_replace("/", "", $string);
        if (strlen($string) == 6) {
            $ano = substr($string, 2, 4);
            $mes = substr($string, 0, 2);

            return $ano . $mes;
        }
    }

    /**
     * Retorna mes ano de uma determinada data no estilo MM/YYYY
     * Exemplo, Transforma 01/03/2011 ==> 03/2011
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     * @param $string
     * @return $ano/$mes
     */
    public function mesAno($string) {
        $string = str_replace("/", "", $string);
        if (strlen($string) == 8) {
            $mes = substr($string, 2, 2);
            $ano = substr($string, 4, 4);

            return $mes . "/" . $ano;
        }
    }

    /**
     * Nome:  extenso
     * Autor:  Chrystian Toigo
     * Data:  06/12/2011
     * Nota:  Esta funcao fornece o valor por extenso.
     * Exemplo:  extenso( 12345678.90, "real", "reais", "centavo", "centavos" ) ;
     * @deprecated 
     * @see CTransform::numeroParaExtenso() Veja substituta em CTransform
     */
    function extenso($valor, $moedaSing, $moedaPlur, $centSing, $centPlur) {

        $centenas = array(0,
            array(0, "cento", "cem"),
            array(0, "duzentos", "duzentos"),
            array(0, "trezentos", "trezentos"),
            array(0, "quatrocentos", "quatrocentos"),
            array(0, "quinhentos", "quinhentos"),
            array(0, "seiscentos", "seiscentos"),
            array(0, "setecentos", "setecentos"),
            array(0, "oitocentos", "oitocentos"),
            array(0, "novecentos", "novecentos"));

        $dezenas = array(0,
            "dez",
            "vinte",
            "trinta",
            "quarenta",
            "cinquenta",
            "sessenta",
            "setenta",
            "oitenta",
            "noventa");

        $unidades = array(0,
            "um",
            "dois",
            "três",
            "quatro",
            "cinco",
            "seis",
            "sete",
            "oito",
            "nove");

        $excecoes = array(0,
            "onze",
            "doze",
            "treze",
            "quatorze",
            "quinze",
            "dezeseis",
            "dezesete",
            "dezoito",
            "dezenove");

        $extensoes = array(0,
            array(0, "", ""),
            array(0, "mil", "mil"),
            array(0, "milhão", "milhões"),
            array(0, "bilhão", "bilhões"),
            array(0, "trilhão", "trilhões"));

        $valorForm = trim(number_format($valor, 2, ".", ","));

        $inicio = 0;

        if ($valor <= 0) {
            return ( $valorExt );
        }

        for ($conta = 0; $conta <= strlen($valorForm) - 1; $conta++) {
            if (strstr(",.", substr($valorForm, $conta, 1))) {
                $partes[] = str_pad(substr($valorForm, $inicio, $conta - $inicio), 3, " ", STR_PAD_LEFT);
                if (substr($valorForm, $conta, 1) == ".") {
                    break;
                }
                $inicio = $conta + 1;
            }
        }

        $centavos = substr($valorForm, strlen($valorForm) - 2, 2);

        if (!( count($partes) == 1 and intval($partes[0]) == 0 )) {
            for ($conta = 0; $conta <= count($partes) - 1; $conta++) {

                $centena = intval(substr($partes[$conta], 0, 1));
                $dezena = intval(substr($partes[$conta], 1, 1));
                $unidade = intval(substr($partes[$conta], 2, 1));

                if ($centena > 0) {

                    $valorExt .= $centenas[$centena][($dezena + $unidade > 0 ? 1 : 2)] . ( $dezena + $unidade > 0 ? " e " : "" );
                }

                if ($dezena > 0) {
                    if ($dezena > 1) {
                        $valorExt .= $dezenas[$dezena] . ( $unidade > 0 ? " e " : "" );
                    } elseif ($dezena == 1 and $unidade == 0) {
                        $valorExt .= $dezenas[$dezena];
                    } else {
                        $valorExt .= $excecoes[$unidade];
                    }
                }

                if ($unidade > 0 and $dezena != 1) {
                    $valorExt .= $unidades[$unidade];
                }

                if (intval($partes[$conta]) > 0) {
                    $valorExt .= " " . $extensoes[(count($partes) - 1) - $conta + 1][(intval($partes[$conta]) > 1 ? 2 : 1)];
                }

                if ((count($partes) - 1) > $conta and intval($partes[$conta]) > 0) {
                    $conta3 = 0;
                    for ($conta2 = $conta + 1; $conta2 <= count($partes) - 1; $conta2++) {
                        $conta3 += (intval($partes[$conta2]) > 0 ? 1 : 0);
                    }

                    if ($conta3 == 1 and intval($centavos) == 0) {
                        $valorExt .= " e ";
                    } elseif ($conta3 >= 1) {
                        $valorExt .= ", ";
                    }
                }
            }

            if (count($partes) == 1 and intval($partes[0]) == 1) {
                $valorExt .= $moedaSing;
            } elseif (count($partes) >= 3 and ((intval($partes[count($partes) - 1]) + intval($partes[count($partes) - 2])) == 0)) {
                $valorExt .= " de " + $moedaPlur;
            } else {
                $valorExt = trim($valorExt) . " " . $moedaPlur;
            }
        }

        if (intval($centavos) > 0) {

            $valorExt .= (!empty($valorExt) ? " e " : "");

            $dezena = intval(substr($centavos, 0, 1));
            $unidade = intval(substr($centavos, 1, 1));

            if ($dezena > 0) {
                if ($dezena > 1) {
                    $valorExt .= $dezenas[$dezena] . ( $unidade > 0 ? " e " : "" );
                } elseif ($dezena == 1 and $unidade == 0) {
                    $valorExt .= $dezenas[$dezena];
                } else {
                    $valorExt .= $excecoes[$unidade];
                }
            }

            if ($unidade > 0 and $dezena != 1) {
                $valorExt .= $unidades[$unidade];
            }

            $valorExt .= " " . ( intval($centavos) > 1 ? $centPlur : $centSing );
        }

        return ( $valorExt );
    }

    /**
     * Nome:  mesExtenso
     * Autor:  Chrystian Toigo
     * Data:  06/12/2011
     * Nota:  Esta funcao fornece o mes por extenso.
     * Exemplo:  mesExtenso("12") ;
     * @deprecated 
     * @see CTransform::mesParaExtenso() Veja substituta em CTransform
     */
    function mesExtenso($mes) {

        switch ($mes) {

            case 1: $mes = "JANEIRO";
                break;
            case 2: $mes = "FEVEREIRO";
                break;
            case 3: $mes = "MARÇO";
                break;
            case 4: $mes = "ABRIL";
                break;
            case 5: $mes = "MAIO";
                break;
            case 6: $mes = "JUNHO";
                break;
            case 7: $mes = "JULHO";
                break;
            case 8: $mes = "AGOSTO";
                break;
            case 9: $mes = "SETEMBRO";
                break;
            case 10: $mes = "OUTUBRO";
                break;
            case 11: $mes = "NOVEMBRO";
                break;
            case 12: $mes = "DEZEMBRO";
                break;
        }

        $mes = strtolower($mes);
        return $mes;
    }

    /**
     * Nome:  dataExtenso
     * Autor:  Chrystian Toigo
     * Data:  06/12/2011
     * Nota:  Esta funcao fornece a data por extenso.
     * Exemplo:  dataExtenso("27/12/2011") ;
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     */
    function dataExtenso($data) {

        $data = explode("/", $data);

        $dia = $data[0];
        $mes = $data[1];
        $ano = $data[2];

        switch ($mes) {

            case 1: $mes = "JANEIRO";
                break;
            case 2: $mes = "FEVEREIRO";
                break;
            case 3: $mes = "MARÇO";
                break;
            case 4: $mes = "ABRIL";
                break;
            case 5: $mes = "MAIO";
                break;
            case 6: $mes = "JUNHO";
                break;
            case 7: $mes = "JULHO";
                break;
            case 8: $mes = "AGOSTO";
                break;
            case 9: $mes = "SETEMBRO";
                break;
            case 10: $mes = "OUTUBRO";
                break;
            case 11: $mes = "NOVEMBRO";
                break;
            case 12: $mes = "DEZEMBRO";
                break;
        }

        $mes = strtolower($mes);
        return ("$dia de $mes de $ano");
    }

    /**
     * Nome:  retiraVirgula
     * Autor:  Diego Macedo
     * Data:  05/06/2012
     * Nota:  Esta funcao retira virgula no inicio e no final da string caso exista
     * Exemplo:  retiraVirgula(",1,3,2,6,") ;
     * @deprecated Use a função nativa: trim(",1,3,2,6,", ',');
     */
    function retiraVirgula($dado) {
        $virgula = ",";
        if (substr($dado, 0, 1) == $virgula)
            $dado = substr($dado, 1);
        if (substr($dado, -1) == $virgula)
            $dado = substr($dado, 0, -1);

        return $dado;
    }

    /**
     * Nome:  ultimoDiaMes
     * Autor: Diego Macedo
     * Data:  23/08/2012
     * Nota:  Esta Funcao recebe uma data mmm/YYYY e retorna o ultimo dia do Mes
     *        util para evitar problemas com ano Bisexto
     *        A Logica e pegar o primeiro dia do mes, somar com 1 Mes e subtrair 1 dia
     * Exemplo:  ultimoDiaMes(02/2012) ;
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     */
    function ultimoDiaMes($mesAno) {
        /* Desmembrando a Data */
        list($newMes, $newAno) = explode("/", $mesAno);
        return date("d/m/Y", mktime(0, 0, 0, $newMes + 1, 0, $newAno));
    }

    /**
     * Nome:  getArrayAno
     * Autor:  Chrystian Toigo
     * Data:  24/08/2012
     * Nota:  Esta funcao retorna um array de ANO subtraindo a quantidade passada por paramentro
     * Exemplo:  getArrayAno(5)
     */
    function getArrayAno($quantidade) {
        $array_ano = array();
        for ($index = 0; $index < $quantidade; $index++) {
            $array_ano[date('Y') - $index] = date('Y') - $index;
        }
        return $array_ano;
    }

    /**
     * @deprecated Validações de Models deveriam estar aqui?
     */
    function getValidateModel($model) {
        if ($model !== null) {
            $campos = $model->getAttributes();
            $return = false;
            foreach ($campos as $key => $value) {
                if (!empty($value)) {
                    $return = true;
                }
            }
        } else {
            $return = false;
        }
        return $return;
    }

    /**
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     */
    function tratarAnoMesNota($data) {
        $dataNota = explode("/", $data);
        $anoMes = substr($dataNota[2], 2, 2) . $dataNota[1];
        return $anoMes;
    }

    /**
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     */
    function toDbDate($data) {
        if (preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $data, $match)) {
            return($match[3] . "-" . $match[2] . "-" . $match[1]);
        } else {
            return false;
        }
    }

    /**
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     */
    function fromDbDate($data) {
        if (preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/", $data, $match)) {
            return($match[3] . "/" . $match[2] . "/" . $match[1] . " " . $match[4] . ":" . $match[5] . ":" . $match[6]);
        } elseif (preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $data, $match)) {
            return($match[3] . "/" . $match[2] . "/" . $match[1]);
        } else {
            return false;
        }
    }

    /**
     * Funcao para retirar caracteres especiais de um texto
     */
    function retiraCaracteresComVazio($str) {
        $str = str_replace("[^a-zA-Z0-9 .]", "", strtr($str, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇºª²³>< ", "aaaaeeiooouucAAAAEEIOOOUUC..23   "));
        return $str;
    }

    /**
     * Metodo para tratamento de caracteres para Nota Fiscal
     * Especifica para os campos: nome, fantasia, endereco e bairro
     * @param String $str String a ser formatada
     * @return String String formatada
     */
    function tratarDadoNotaFiscal($str, $sub = true) {
        $formatado = '';
        if ($sub) {
            $formatado = strtoupper(self::retiraCaracteresComVazio(substr(trim(strtolower($str)), 0, 60)));
        } else {
            $formatado = strtoupper(self::retiraCaracteresComVazio(trim(strtolower($str))));
        }
        return $formatado;
    }

    /**
     * REMOVE ponto e virgula
     * @deprecated
     * @see CMask2::removeMascara() Veja possível substituta em CMask2
     * @see CMask2::valor() Veja possível substituta em CMask2
     * @see CTransform::stringParaValor() Veja possível substituta em CTransform
     */
    public function removePontoVirgula($param) {
        return str_replace('.', '', str_replace(',', '', $param));
    }

    /**
     * TROCA Virgula por ponto
     * EX 9.999,99 -> 9999.99
     * @deprecated
     * @see CMask2::valor() Veja possível substituta em CMask2
     * @see CTransform::stringParaValor() Veja possível substituta em CTransform
     */
    public function trocaPontoVirgula($param) {
        return str_replace(',', '.', str_replace('.', '', $param));
    }

    /**
     * @deprecated
     * @see CMask2::removeMascara() Veja substituta em CMask2
     */
    public function noFormat($campo) {
        $campo = str_replace('-', '', $campo);
        $campo = str_replace('.', '', $campo);
        $campo = str_replace(';', '', $campo);
        $campo = str_replace(',', '', $campo);
        $campo = str_replace('/', '', $campo);
        $campo = str_replace('\\', '', $campo);
        $campo = str_replace('{', '', $campo);
        $campo = str_replace('}', '', $campo);
        $campo = str_replace('[', '', $campo);
        $campo = str_replace(']', '', $campo);
        $campo = str_replace('?', '', $campo);
        $campo = str_replace('(', '', $campo);
        $campo = str_replace(')', '', $campo);
        return $campo;
    }

    /**
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     */
    public function somarData($data, $dias = '', $meses = '', $ano = '') {
        $data = explode('/', $data);
        $newData = date('d/m/Y', mktime(0, 0, 0, $data[1] + $meses, $data[0] + $dias, $data[2] + $ano));
        return $newData;
    }

    /**
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     */
    public function subtrairData($data, $dias = '', $meses = '', $ano = '') {
        $data = explode('/', $data);
        $newData = date('d/m/Y', mktime(0, 0, 0, $data[1] - $meses, $data[0] - $dias, $data[2] - $ano));
        return $newData;
    }

    public function formatarMoeda($param) {
        $retorno = null;
        if ($param !== null) {
            $retorno = number_format($param, 2, ",", ".");
        }
        return $retorno;
    }

    /**
     * @deprecated Se houver necessidade, crie uma nova máscara em CMask2
     */
    public function mascaraGenerica($val, $mask) {
        //na máscara informar # como o caractere a ser substituído
        //Exemplo: Para data ##/##/####; para CPF ###.###.###-##
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    /**
     * //entrada do tipo 20131211 e saída 2013-12-11
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     */
    public function addMaskIntDate($data) {
        if (!$data || !is_numeric($data) || strlen($data) != 8)
            return '';

        $ano = substr($data, 0, 4);
        $mes = substr($data, 4, 2);
        $dia = substr($data, 6, 2);

        return $ano . '-' . $mes . '-' . $dia;
    }

    /**
     * // valida de uma data formada apelas pelo mês e o ano (ex.: 08/2013) é válida
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     */
    public function checkDataAnoMes($date) {
        list($mm, $yy) = explode('/', $date);

        return !empty($mm) && !empty($yy) && checkdate($mm, '01', $yy);
    }

    /**
     * Retorna uma enumeração (do português) com os ítens de um determinado array.
     * Ex.: um array ("Arroz", "Feijão", "Ovo") vai retornar uma string: "Arroz, Feijão e Ovo".
     * @deprecated
     * @see CTransform::arrayParaEnumeracao() Veja substituta em CTransform
     * @author igor.costa
     * @param array O array desejado.
     * @return a string com a enumeração em português.
     * */
    public function geraStringEnumeracaoArray($array) {
        return join(' e ', array_filter(array_merge(array(join(', ', array_slice($array, 0, -1))), array_slice($array, -1))));
    }

    /**
     * Função para remover os acentos e caracteres especiais e transformar para caixa alta
     * @deprecated
     * @see CTransform::removeEspeciais() Veja substituta em CTransform
     * @param type $string
     * @return type
     */
    function padronizaNome($string) {
        //áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇºª²³>< 
        $string = mb_strtoupper($string);
        $palavra = strtr($string, "ßÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝ", "SAAAAAAACEEEEIIIIDNOOOOOOUUUUY");
        return $palavra;
    }

    /**
     * Função para completar números com zeros à esquerda
     * Nome: completaZeros
     * Autor:  Germano Costa
     * Data:  04/09/2013
     * @param type $numero
     * @deprecated Use a função PHP: str_pad($numero, 10, "0", STR_PAD_LEFT);
     * 
     */
    function completaZeros($numero, $digitos = 2) {
        return substr(str_repeat("0", $digitos) . $numero, -$digitos);
    }

    /**
     * Nome: formataDataMesExtenso
     * Autor:  Germano Costa
     * Data:  04/09/2013
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     * @param type $data
     * @param type $formato
     */
    function formataDataMesExtenso($data, $formato = "d/m/Y") {
        $d = date_parse_from_format($formato, $data);
        $data = self::completaZeros($d["day"]) . "/" . substr(self::mesExtenso($d["month"]), 0, 3) . "/" . $d["year"];
        if (strpos($formato, 'H')) {
            $data = $data . " " . self::completaZeros($d["hour"]) . ":" . self::completaZeros($d["minute"]) . ":" . self::completaZeros($d["second"]);
        }
        return $data;
    }

    /**
     * Nome: getIpRealUsuario
     * Autor: Fillipe Guimarães
     * Data: 25/11/2013
     * Nota: Esta função retorna o IP do usuário logado no sistema
     * Exemplo: getIpRealUsuario();
     */
    function getIpRealUsuario() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) // Se possível, obtém o ip da máquina do usuário
        { 
            $ip=$_SERVER['HTTP_CLIENT_IP']; 
        } 
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) // Verifica se o ip está passando pelo proxy
        { 
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; 
        } 
        else 
        { 
            $ip=$_SERVER['REMOTE_ADDR']; // ip do servidor
        } 
        $ip=explode(",",$ip);
        return $ip[0];
    }

    /**
     * @deprecated Utilize a classe DateTime do PHP (SPL)
     */
    function verificarData($data_comparar){
        $data_atual = new DateTime(date('m/d/Y'));
        $data_comparar = new DateTime( self::toDbDate($data_comparar) );
        if ($data_comparar > $data_atual){
            return false;
        }
        return true;
    }
    
    /**
     * Função para retirar caracteres gerais
     * @param type $string
     */
    function retiraCaracteresGerais($string) {
        $palavra = str_replace(" ", "", strtr($string, "./-", "   "));
        return $palavra;
    }
    
}
