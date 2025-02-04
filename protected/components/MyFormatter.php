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

class MyFormatter extends CFormatter {

    public function formatBoolean($value) {
        return $value ? Yii::t('app', 'Sim') : Yii::t('app', 'Não');
    }

    public function formatAcao($value) {
        switch ($value) {
            case 0:
                return 'Recebido';
                break;
            case 1:
                return 'Recusado';
                break;
            case 2:
                return 'Enviado';
                break;
            default:
                break;
        }
    }

    public function formatCnpj($value) {
        return CMask::getFormataCNPJ($value);
    }
    
	public function converteData($data){
		return (preg_match('/\//',$data)) ? implode('-', array_reverse(explode('/', $data))) : implode('/', array_reverse(explode('-', $data)));
	}
    
	public function converteTimeStamp($data){
		$data2 = explode(" ",$data);
                $data2 = $data2[0];
                $data2 = explode("-",$data2);
                $data2 = $data2[2]."/".$data2[1]."/".$data2[0];
                return $data2;
	}

 
        public function formataMoeda($value) {
               return  number_format($value, 2, ',', '.');
           }    
           
           
// converte um número romano para inteiro
public function roman2number($roman){
    $conv = array(
        array("letter" => 'I', "number" => 1),
        array("letter" => 'V', "number" => 5),
        array("letter" => 'X', "number" => 10),
        array("letter" => 'L', "number" => 50),
        array("letter" => 'C', "number" => 100),
        array("letter" => 'D', "number" => 500),
        array("letter" => 'M', "number" => 1000),
        array("letter" => 0, "number" => 0)
    );
    $arabic = 0;
    $state = 0;
    $sidx = 0;
    $len = strlen($roman);

    while ($len >= 0) {
        $i = 0;
        $sidx = $len;

        while ($conv[$i]['number'] > 0) {
            if (strtoupper(@$roman[$sidx]) == $conv[$i]['letter']) {
                if ($state > $conv[$i]['number']) {
                    $arabic -= $conv[$i]['number'];
                } else {
                    $arabic += $conv[$i]['number'];
                    $state = $conv[$i]['number'];
                }
            }
            $i++;
        }

        $len--;
    }

    return($arabic);
}

// converte um número inteiro para romano
public function number2roman($num,$isUpper=true) {
    $n = intval($num);
    $res = '';

    /*** roman_numerals array ***/
    $roman_numerals = array(
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1
    );

    foreach ($roman_numerals as $roman => $number)
    {
        $matches = intval($n / $number);
        $res .= str_repeat($roman, $matches);
        $n = $n % $number;
    }
    if($isUpper) return $res;
    else return strtolower($res);
}           
}
