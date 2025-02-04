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
 * Classe para manipulação/transformação de strings específicas não oferecidas 
 * via funções nativas do PHP.
 * 
 * Todos os métodos devem transformar a string de alguma forma.
 * 
 * Esta classe teve origem de metodos retirados da antiga CMask, que passou a
 * acomodar helpers de validação e tratamento de datas, dentre outros, além da 
 * falta de padronização nos nomes dos métodos.
 * 
 * As manipulações de Data não foram migradas, para dar preferencia ao uso da 
 * classe DateTime nativa do PHP.
 * 
 * Ao criar um metodo, favor siga este processo:
 * 1. Verifique se o método que precisa já não existe, para não haver duplicação
 * 2. Mantenha o mesmo padrão de nomenclatura
 * 3. Verifique novamente se o método que precisa já não existe. :)
 * 4. Evite ao máximo o acoplamento.
 * 
 * @package \common\util
 */
class CTransform
{
    /**
     * Remove os acentos e caracteres especiais e transformar para caixa alta
     * 
     * @param string $string String a ser manipulada
     * @return string String em maiusculo e sem caracteres especiais
     */
    public static function removeEspeciais($string)
    {
        return preg_replace('/[`^~\'"]/', null, iconv('ISO-8859-1', 'ASCII//TRANSLIT', $string));
    }


    /**
     * Transforma uma string numérica em valor (float), removendo os separadores
     * de milhar.
     * 
     * Exemplo: CTransform::stringParaValor('9.999,99'); // retorna (float) 9999.99
     * 
     * @param string $string A ser transformada
     * @return float|integer|null Valor transformado ou nulo caso não seja uma string numérica
     */
    public static function stringParaValor($string)
    {
        if (empty($string)) {
            return null;
        }

        $string = str_replace(".", "", $string);
        $string = str_replace(",", ".", $string);
        if (strpos($string, '.')) {
            return (float) $string;
        }
        return (integer) $string;
    }

    /**
     * Trasnforma um numero em extenso, pt-BR, a partir do número.
     * 
     * Exemplo:
     *   CTransform::numeroParaExtenso(12345678.90, "real", "reais", "centavo", "centavos")
     * 
     * @author Chrystian Toigo
     * @since 06/12/2011
     * @param float $valor Número (ponto flutuante) a ser convertido
     * @return string|null Número por extenso, ou nulo caso um número não seja passado
     */
    public static function numeroParaExtenso($valor, $moedaSing, $moedaPlur, $centSing, $centPlur)
    {
        if (!is_numeric($valor)) {
            return null;
        }

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
     * Transforma o mês em extenso, pt-BR, a partir do número do mês.
     * 
     * Exemplo: CTransform::mesParaExtenso(2); // retorna "FEVEREIRO"
     * 
     * @param integer $mes Mês no formato numérico. ex.: 1, 2, 10, ...
     * @return string|null Mês por extenso ou nulo caso um mes invalido seja passado
     */
    public static function mesParaExtenso($mes)
    {
        if ($mes > 0 && $mes < 13) {
            $meses = array(
                "JANEIRO", "FEVEREIRO", "MARÇO", "ABRIL", "MAIO", "JUNHO",
                "JULHO", "AGOSTO", "SETEMBRO", "OUTUBRO", "NOVEMBRO", "DEZEMBRO"
            );
            return $meses[--$mes];
        }
        return null;
    }

    /**
     * Retorna uma enumeração (do português) com os ítens de um determinado array.
     * 
     * Exemplo:
     *   entrada: CTransform::arrayParaEnumeracao(array("Arroz", "Feijão", "Ovo"))
     *   retorna: (string) "Arroz, Feijão e Ovo"
     * 
     * @author igor.costa
     * @param array $array O array desejado para transformação
     * @return string Com a enumeração em português.
     */
    public function arrayParaEnumeracao($array)
    {
        return join(' e ', array_filter(array_merge(array(join(', ', array_slice($array, 0, -1))), array_slice($array, -1))));
    }
}
