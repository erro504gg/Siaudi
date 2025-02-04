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
<?
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($baseUrl . '/protected/common/extensions/jqplot/jquery.jqplot.css');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/jquery.jqplot.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.barRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.categoryAxisRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.pointLabels.min.js');

?>

<script type="text/javascript">
    function salvarImagem() {
        $('#container').jqplotSaveImage();
    }


    $(function() {
        plot2b = jQuery.jqplot('container',
                [[
//[2, 'Uas'], [4, 'Cibrius'], [10, 'Licitações e Contratos'], [7, 'Doação']                
<?php

//Função recursiva para obter valor de um determinado campo.
function array_value_recursive($key, array $arr) {
    $val = array();
    array_walk_recursive($arr, function($v, $k) use ($key, &$val) {
                if ($k == $key) {
                    array_push($val, $v);
                }
            });
    return count($val) > 1 ? $val : array_pop($val);
}

//$total_geral = 1;
$array_total_geral = array_value_recursive('total_por_objeto', $dados);
$total_geral = 0;
if (count($array_total_geral) > 1) {
    foreach($array_total_geral as $total){
    	$total_geral += $total;
    }
} else {
    $total_geral = $array_total_geral;
}
$str = '';
foreach ($dados as $vetor) {
    ;
    $str = $str . "[" . $vetor['total_por_objeto'] . ",'" . $vetor['nome_objeto'] . "']";
}
$str = str_replace('][', '],[', $str);

echo $str;
?>
                    ]], {
            seriesDefaults: {
                renderer: $.jqplot.BarRenderer,
                pointLabels: {show: true, location: 'e', edgeTolerance: -15},
                shadowAngle: 135,
                rendererOptions: {
                    barDirection: 'horizontal',
                    varyBarColor: true,
                    barWidth: 15

                }
            },
            title: 'Percentual das recomendações por ação de auditoria/<?= $valor_exercicio ?>',
            axes: {
                yaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    rendererOptions: {tickRenderer: $.jqplot.AxisTickRenderer,
                        tickOptions: {mark: null,
                            fontSize: 12
                        }
                    }
                    //y2axis:{ticks:[0, 100], tickOptions:{formatString:'%d\%'}}                    
                }
            },
            highlighter: {show: false},
            grid: {
                drawGridlines: true,
                drawBorder: true,
                shadow: true
            }
        });
        
        var imgData = $('#container').jqplotToImageStr({}); 
        var imgElem = $('<img/>').attr('src',imgData); 
        $('#container').hide();
        $('#chartdivimg').empty(); // remove the old graph
        $('#chartdivimg').append(imgElem);

    });
</script>

<div class="tabelaListagemItensWrapper">
    <div align="center" >
        <div id="container" style="height: 400px; width: 400px; margin: 0 auto"></div>
        <div id="chartdivimg" style="height: 400px; width: 400px; margin: 0 auto"></div>
    </div>
    <div style="height:16px;"></div>
    <div align='center'>
        <fieldset class="visivel" Style="width: 70%;" >
            <div class="tabelaListagemItens">
                <table border="0" cellpadding="0" cellpadding="0" align="center">
                    <tr>
                        <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="6">Objetos da ação</th>
                    </tr>
                    <? foreach ($dados as $vetor): ?>
                        <tr>
                            <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="3"><?= $vetor['nome_objeto']; ?></th>
                        </tr>
                        <tr>
                            <td align="center" colspan="3"><?= round((($vetor['total_por_objeto'] / $total_geral) * 100), 2) . "%"; ?></td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div> 

        </fieldset>
    </div>
</div>






