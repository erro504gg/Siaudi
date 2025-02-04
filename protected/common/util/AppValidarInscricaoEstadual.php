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
 * Classe que realiza as validacoes de Isncricoes Estaduais
 *
 * @author marcelo.nogueira
 * @link http://www.sintegra.gov.br/insc_est.html
 * @version 1.0
 */
class AppValidarInscricaoEstadual {

    /**
     * Metodo responsavel por Chamar a validacao correspondente a partir da UF passada por paramentro
     * @param String $ie Numero da Inscricao estadual do Agente
     * @param String $uf uf da Inscricao Estadual do Agente
     * @return boolena
     */
    static public function checkIE($ie, $uf){
        if( strtoupper($ie) == 'ISENTO' ){
            return 1;
        }
        else{
            $uf = strtoupper($uf);
            $ie = ereg_replace("[()-./,:]", "", $ie);
            $comando = '$valida = self::checkIE'.$uf.'("'.$ie.'");';
            eval($comando);
            return $valida;
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do AC
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEAC($ie){
        if (strlen($ie) != 13){return 0;}
        else{
            if(substr($ie, 0, 2) != '01'){return 0;}
            else{
                $b = 4;
                $soma = 0;
                for ($i=0;$i<=10;$i++){
                    $soma += $ie[$i] * $b;
                    $b--;
                    if($b == 1){$b = 9;}
                }
                $dig = 11 - ($soma % 11);
                if($dig >= 10){$dig = 0;}
                if( !($dig == $ie[11]) ){return 0;}
                else{
                    $b = 5;
                    $soma = 0;
                    for($i=0;$i<=11;$i++){
                        $soma += $ie[$i] * $b;
                        $b--;
                        if($b == 1){$b = 9;}
                    }
                    $dig = 11 - ($soma % 11);
                    if($dig >= 10){$dig = 0;}

                    return ($dig == $ie[12]);
                }
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual de AL
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEAL($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            if(substr($ie, 0, 2) != '24'){return 0;}
            else{
                $b = 9;
                $soma = 0;
                for($i=0;$i<=7;$i++){
                    $soma += $ie[$i] * $b;
                    $b--;
                }
                $soma *= 10;
                $dig = $soma - ( ( (int)($soma / 11) ) * 11 );
                if($dig == 10){$dig = 0;}

                return ($dig == $ie[8]);
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do AM
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEAM($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            $b = 9;
            $soma = 0;
            for($i=0;$i<=7;$i++){
                $soma += $ie[$i] * $b;
                $b--;
            }
            if($soma <= 11){$dig = 11 - $soma;}
            else{
                $r = $soma % 11;
                if($r <= 1){$dig = 0;}
                else{$dig = 11 - $r;}
            }

            return ($dig == $ie[8]);
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do AM
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEAP($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            if(substr($ie, 0, 2) != '03'){return 0;}
            else{
                $i = substr($ie, 0, -1);
                if( ($i >= 3000001) && ($i <= 3017000) ){$p = 5; $d = 0;}
                elseif( ($i >= 3017001) && ($i <= 3019022) ){$p = 9; $d = 1;}
                elseif ($i >= 3019023){$p = 0; $d = 0;}

                $b = 9;
                $soma = $p;
                for($i=0;$i<=7;$i++){
                    $soma += $ie[$i] * $b;
                    $b--;
                }
                $dig = 11 - ($soma % 11);
                if($dig == 10){$dig = 0;}
                elseif($dig == 11){$dig = $d;}

                return ($dig == $ie[8]);
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual da BA
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEBA($ie){
        if (strlen($ie) != 8){return 0;}
        else{

            $arr1 = array('0','1','2','3','4','5','8');
            $arr2 = array('6','7','9');

            $i = substr($ie, 0, 1);

            if(in_array($i, $arr1)){$modulo = 10;}
            elseif(in_array($i, $arr2)){$modulo = 11;}

            $b = 7;
            $soma = 0;
            for($i=0;$i<=5;$i++){
                $soma += $ie[$i] * $b;
                $b--;
            }

            $i = $soma % $modulo;
            if ($modulo == 10){
                if ($i == 0) { $dig = 0; } else { $dig = $modulo - $i; }
            }else{
                if ($i <= 1) { $dig = 0; } else { $dig = $modulo - $i; }
            }
            if( !($dig == $ie[7]) ){return 0;}
            else{
                $b = 8;
                $soma = 0;
                for($i=0;$i<=5;$i++){
                    $soma += $ie[$i] * $b;
                    $b--;
                }
                $soma += $ie[7] * 2;
                $i = $soma % $modulo;
                if ($modulo == 10){
                    if ($i == 0) { $dig = 0; } else { $dig = $modulo - $i; }
                }else{
                    if ($i <= 1) { $dig = 0; } else { $dig = $modulo - $i; }
                }

                return ($dig == $ie[6]);
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do CE
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIECE($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            $b = 9;
            $soma = 0;
            for($i=0;$i<=7;$i++){
                $soma += $ie[$i] * $b;
                $b--;
            }
            $dig = 11 - ($soma % 11);

            if ($dig >= 10){$dig = 0;}

            return ($dig == $ie[8]);
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do DF
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEDF($ie){
        if (strlen($ie) != 13){return 0;}
        else{
            if( substr($ie, 0, 2) != '07' ){return 0;}
            else{
                $b = 4;
                $soma = 0;
                for ($i=0;$i<=10;$i++){
                    $soma += $ie[$i] * $b;
                    $b--;
                    if($b == 1){$b = 9;}
                }
                $dig = 11 - ($soma % 11);
                if($dig >= 10){$dig = 0;}

                if( !($dig == $ie[11]) ){return 0;}
                else{
                    $b = 5;
                    $soma = 0;
                    for($i=0;$i<=11;$i++){
                        $soma += $ie[$i] * $b;
                        $b--;
                        if($b == 1){$b = 9;}
                    }
                    $dig = 11 - ($soma % 11);
                    if($dig >= 10){$dig = 0;}

                    return ($dig == $ie[12]);
                }
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do ES
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEES($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            $b = 9;
            $soma = 0;
            for($i=0;$i<=7;$i++){
                $soma += $ie[$i] * $b;
                $b--;
            }
            $i = $soma % 11;
            if ($i < 2){$dig = 0;}
            else{$dig = 11 - $i;}

            return ($dig == $ie[8]);
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do GO
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEGO($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            $s = substr($ie, 0, 2);

            if( !( ($s == 10) || ($s == 11) || ($s == 15) ) ){return 0;}
            else{
                $n = substr($ie, 0, 7);

                if($n == 11094402){
                    if($ie[8] != 0){
                        if($ie[8] != 1){
                            return 0;
                        }else{return 1;}
                    }else{return 1;}
                }else{
                    $b = 9;
                    $soma = 0;
                    for($i=0;$i<=7;$i++){
                        $soma += $ie[$i] * $b;
                        $b--;
                    }
                    $i = $soma % 11;
                    if ($i == 0){$dig = 0;}
                    else{
                        if($i == 1){
                            if(($n >= 10103105) && ($n <= 10119997)){$dig = 1;}
                            else{$dig = 0;}
                        }else{$dig = 11 - $i;}
                    }

                    return ($dig == $ie[8]);
                }
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do MA
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEMA($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            if(substr($ie, 0, 2) != 12){return 0;}
            else{
                $b = 9;
                $soma = 0;
                for($i=0;$i<=7;$i++){
                    $soma += $ie[$i] * $b;
                    $b--;
                }
                $i = $soma % 11;
                if ($i <= 1){$dig = 0;}
                else{$dig = 11 - $i;}

                return ($dig == $ie[8]);
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do MT
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEMT($ie){
        if (strlen($ie) != 11){return 0;}
        else{
            $b = 3;
            $soma = 0;
            for($i=0;$i<=9;$i++){
                $soma += $ie[$i] * $b;
                $b--;
                if($b == 1){$b = 9;}
            }
            $i = $soma % 11;
            if ($i <= 1){$dig = 0;}
            else{$dig = 11 - $i;}

            return ($dig == $ie[10]);
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do MS
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEMS($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            if(substr($ie, 0, 2) != 28){return 0;}
            else{
                $b = 9;
                $soma = 0;
                for($i=0;$i<=7;$i++){
                    $soma += $ie[$i] * $b;
                    $b--;
                }
                $i = $soma % 11;
                if ($i == 0){$dig = 0;}
                else{$dig = 11 - $i;}

                if($dig > 9){$dig = 0;}

                return ($dig == $ie[8]);
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual de MG
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEMG($ie){
        if (strlen($ie) != 13){return 0;}
        else{
            $ie2 = substr($ie, 0, 3) . '0' . substr($ie, 3);

            $b = 1;
            $soma = "";
            for($i=0;$i<=11;$i++){
                $soma .= $ie2[$i] * $b;
                $b++;
                if($b == 3){$b = 1;}
            }
            $s = 0;
            for($i=0;$i<strlen($soma);$i++){
                $s += $soma[$i];
            }
            $i = substr($ie2, 9, 2);
            $dig = $i - $s;
            if($dig != $ie[11]){return 0;}
            else{
                $b = 3;
                $soma = 0;
                for($i=0;$i<=11;$i++){
                    $soma += $ie[$i] * $b;
                    $b--;
                    if($b == 1){$b = 11;}
                }
                $i = $soma % 11;
                if($i < 2){$dig = 0;}
                else{$dig = 11 - $i;};

                return ($dig == $ie[12]);
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do PA
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEPA($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            if(substr($ie, 0, 2) != 15){return 0;}
            else{
                $b = 9;
                $soma = 0;
                for($i=0;$i<=7;$i++){
                    $soma += $ie[$i] * $b;
                    $b--;
                }
                $i = $soma % 11;
                if ($i <= 1){$dig = 0;}
                else{$dig = 11 - $i;}

                return ($dig == $ie[8]);
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual da PB
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEPB($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            $b = 9;
            $soma = 0;
            for($i=0;$i<=7;$i++){
                $soma += $ie[$i] * $b;
                $b--;
            }
            $i = $soma % 11;
            if ($i <= 1){$dig = 0;}
            else{$dig = 11 - $i;}

            if($dig > 9){$dig = 0;}

            return ($dig == $ie[8]);
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do PR
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEPR($ie){
        if (strlen($ie) != 10){return 0;}
        else{
            $b = 3;
            $soma = 0;
            for($i=0;$i<=7;$i++){
                $soma += $ie[$i] * $b;
                $b--;
                if($b == 1){$b = 7;}
            }
            $i = $soma % 11;
            if ($i <= 1){$dig = 0;}
            else{$dig = 11 - $i;}

            if ( !($dig == $ie[8]) ){return 0;}
            else{
                $b = 4;
                $soma = 0;
                for($i=0;$i<=8;$i++){
                    $soma += $ie[$i] * $b;
                    $b--;
                    if($b == 1){$b = 7;}
                }
                $i = $soma % 11;
                if($i <= 1){$dig = 0;}
                else{$dig = 11 - $i;}

                return ($dig == $ie[9]);
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual de PE
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEPE($ie){
        if (strlen($ie) == 9){
            $b = 8;
            $soma = 0;
            for($i=0;$i<=6;$i++){
                $soma += $ie[$i] * $b;
                $b--;
            }
            $i = $soma % 11;
            if ($i <= 1){$dig = 0;}
            else{$dig = 11 - $i;}

            if ( !($dig == $ie[7]) ){return 0;}
            else{
                $b = 9;
                $soma = 0;
                for($i=0;$i<=7;$i++){
                    $soma += $ie[$i] * $b;
                    $b--;
                }
                $i = $soma % 11;
                if ($i <= 1){$dig = 0;}
                else{$dig = 11 - $i;}

                return ($dig == $ie[8]);
            }
        }
        elseif(strlen($ie) == 14){
            $b = 5;
            $soma = 0;
            for($i=0;$i<=12;$i++){
                $soma += $ie[$i] * $b;
                $b--;
                if($b == 0){$b = 9;}
            }
            $dig = 11 - ($soma % 11);
            if($dig > 9){$dig = $dig - 10;}

            return ($dig == $ie[13]);
        }
        else{return 0;}
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do PI
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIEPI($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            $b = 9;
            $soma = 0;
            for($i=0;$i<=7;$i++){
                $soma += $ie[$i] * $b;
                $b--;
            }
            $i = $soma % 11;
            if($i <= 1){$dig = 0;}
            else{$dig = 11 - $i;}
            if($dig >= 10){$dig = 0;}

            return ($dig == $ie[8]);
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do RJ
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIERJ($ie){
        if (strlen($ie) != 8){return 0;}
        else{
            $b = 2;
            $soma = 0;
            for($i=0;$i<=6;$i++){
                $soma += $ie[$i] * $b;
                $b--;
                if($b == 1){$b = 7;}
            }
            $i = $soma % 11;
            if ($i <= 1){$dig = 0;}
            else{$dig = 11 - $i;}

            return ($dig == $ie[7]);
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do RN
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIERN($ie){
        if( !( (strlen($ie) == 9) || (strlen($ie) == 10) ) ){return 0;}
        else{
            $b = strlen($ie);
            if($b == 9){$s = 7;}
            else{$s = 8;}
            $soma = 0;
            for($i=0;$i<=$s;$i++){
                $soma += $ie[$i] * $b;
                $b--;
            }
            $soma *= 10;
            $dig = $soma % 11;
            if($dig == 10){$dig = 0;}

            $s += 1;
            return ($dig == $ie[$s]);
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual do RS
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIERS($ie){
        if (strlen($ie) != 10){return 0;}
        else{
            $b = 2;
            $soma = 0;
            for($i=0;$i<=8;$i++){
                $soma += $ie[$i] * $b;
                $b--;
                if ($b == 1){$b = 9;}
            }
            $dig = 11 - ($soma % 11);
            if($dig >= 10){$dig = 0;}

            return ($dig == $ie[9]);
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual de RO
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIERO($ie){
        if (strlen($ie) == 9){
            $b=6;
            $soma =0;
            for($i=3;$i<=7;$i++){
                $soma += $ie[$i] * $b;
                $b--;
            }
            $dig = 11 - ($soma % 11);
            if($dig >= 10){$dig = $dig - 10;}

            return ($dig == $ie[8]);
        }
        elseif(strlen($ie) == 14){
            $b=6;
            $soma=0;
            for($i=0;$i<=12;$i++) {
                $soma += $ie[$i] * $b;
                $b--;
                if($b == 1){$b = 9;}
            }
            $dig = 11 - ( $soma % 11);
            if ($dig > 9){$dig = $dig - 10;}

            return ($dig == $ie[13]);
        }
        else{return 0;}
    }

    /**
     * Realiza a verificacao da Inscricao Estadual de RR
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIERR($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            if(substr($ie, 0, 2) != 24){return 0;}
            else{
                $b = 1;
                $soma = 0;
                for($i=0;$i<=7;$i++){
                    $soma += $ie[$i] * $b;
                    $b++;
                }
                $dig = $soma % 9;

                return ($dig == $ie[8]);
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual de SC
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIESC($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            $b = 9;
            $soma = 0;
            for($i=0;$i<=7;$i++){
                $soma += $ie[$i] * $b;
                $b--;
            }
            $dig = 11 - ($soma % 11);
            if ($dig <= 1){$dig = 0;}

            return ($dig == $ie[8]);
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual de SP
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIESP($ie){
        if( strtoupper( substr($ie, 0, 1) )  == 'P' ){
            if (strlen($ie) != 13){return 0;}
            else{
                $b = 1;
                $soma = 0;
                for($i=1;$i<=8;$i++){
                    $soma += $ie[$i] * $b;
                    $b++;
                    if($b == 2){$b = 3;}
                    if($b == 9){$b = 10;}
                }
                $dig = $soma % 11;
                return ($dig == $ie[9]);
            }
        }else{
            if (strlen($ie) != 12){return 0;}
            else{
                $b = 1;
                $soma = 0;
                for($i=0;$i<=7;$i++){
                    $soma += $ie[$i] * $b;
                    $b++;
                    if($b == 2){$b = 3;}
                    if($b == 9){$b = 10;}
                }
                $dig = $soma % 11;
                if($dig > 9){$dig = 0;}

                if($dig != $ie[8]){return 0;}
                else{
                    $b = 3;
                    $soma = 0;
                    for($i=0;$i<=10;$i++){
                        $soma += $ie[$i] * $b;
                        $b--;
                        if($b == 1){$b = 10;}
                    }
                    $dig = $soma % 11;

                    return ($dig == $ie[11]);
                }
            }
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual de SE
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIESE($ie){
        if (strlen($ie) != 9){return 0;}
        else{
            $b = 9;
            $soma = 0;
            for($i=0;$i<=7;$i++){
                $soma += $ie[$i] * $b;
                $b--;
            }
            $dig = 11 - ($soma % 11);
            if ($dig > 9){$dig = 0;}

            return ($dig == $ie[8]);
        }
    }

    /**
     * Realiza a verificacao da Inscricao Estadual de TO
     * @param String $ie Numero da Inscricao estadual do Agente
     * @return boolean
     */
    protected function checkIETO($ie){
        if (strlen($ie) != 11){return 0;}
        else{
            $s = substr($ie, 2, 2);
            if( !( ($s=='01') || ($s=='02') || ($s=='03') || ($s=='99') ) ){return 0;}
            else{
                $b=9;
                $soma=0;
                for($i=0;$i<=9;$i++){
                    if( !(($i == 2) || ($i == 3)) ){
                        $soma += $ie[$i] * $b;
                        $b--;
                    }
                }
                $i = $soma % 11;
                if($i < 2){$dig = 0;}
                else{$dig = 11 - $i;}

                return ($dig == $ie[10]);
            }
        }
    }
}
