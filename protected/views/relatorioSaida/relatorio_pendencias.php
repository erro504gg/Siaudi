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

<script>
    $(function() {
        $("#exercicio").mask("9999");
    });
    
</script>

<?php
    $exercicio = $_GET["exercicio"];

// se não foi passado o ano no formato correto para consulta, então abre formulário
if (!$exercicio_correto) { 
        echo "<div class=\"formulario\" Style=\"width: 70%;\">";
        $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
     echo $form->errorSummary($model); 
    ?>

        <div id="pesquisa">
            <br><br>
            <table border="0" cellpadding="0" align="center" class="labelcampo">
                <tr>
                    <td align="center">
                        Exercício:&nbsp;<font style="color:#999999">*&nbsp;</font>&nbsp;
                    </td>
                    <td align="center">
                        <input id="exercicio" name="exercicio" type="text" value="<?= date("Y") ?>" maxlength="4">
                    </td>
                </tr>
            </table>
            <table border="0" align="center" cellpadding="" cellspacing="10">
                <tr>
                    <td>
                        <input name="submit" type="submit" class="botao" value="Consultar">
                    </td>
                </tr>
            </table>
        </div>
        <div id="mensagens">
            <div>*&nbsp;&nbsp; Campos obrigatórios</div>
        </div>
    </div>
    <?php $this->endWidget(); 

// exibe resultado da consulta, conforme o exercício que foi passado
} else {
    ?>   


    <br><br><br><br>
    <div class="tabelaListagemItensWrapper">
        <div align="center" >
            <b>Quadro de Acompanhamento das Pendências dos Relatórios de Auditoria</b>
        </div>
        <div class="tabelaListagemItens">
            <table border="0" cellpadding="0" cellpadding="0" align="center">
                <tr>
                    <th align="center" bgcolor="#FFFFFF" rowspan="2">Relatório</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="2">Data</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="2">Unidade</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="2">Nº Rec.</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="12">
                <div align="center">Exercício  - <?= $exercicio; ?></div>
                </th>

                <th align="center" bgcolor="#FFFFFF" rowspan="2">Obs</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="2">Auditores</th>                
                </tr>
                <tr>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Jan</th>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Fev</th>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Mar</th>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Abr</th>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Mai</th>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Jun</th>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Jul</th>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Ago</th>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Set</th>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Out</th>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Nov</th>
                    <th align="center" bgcolor="#FFFFFF" colspan="1">Dez</th>
                </tr>
                <?php
                $relatorios = Relatorio::model()->Relatorio_pendentes_saida($exercicio);
                if (sizeof($relatorios) > 0) {
                    foreach ($relatorios as $vetor_relatorios) {
                        $data_relatorio = MyFormatter::converteData($vetor_relatorios[data_relatorio]);
                        echo "<tr>";
                        echo "<td valign=top align=center>" . $vetor_relatorios[numero_relatorio] . "</td>";
                        echo "<td valign=top align=center>" . $data_relatorio . "</td>";

                        // pega unidades auditadas do relatório             
                        echo "<td align=center  valign=top >";
                        $relatorio_sureg = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $vetor_relatorios[id]));
                        foreach ($relatorio_sureg as $vetor_suregs) {
                            $sureg = UnidadeAdministrativa::model()->findByAttributes(array('id' => $vetor_suregs->unidade_administrativa_fk));
                            echo $sureg->sigla . "/" . $sureg->uf_fk . "<br>";
                        }
                        echo "</td>";

                        // pega número de recomendações do relatório
                        $Recomendacao_relatorio = Recomendacao::model()->RecomendacaoporRelatorio($vetor_relatorios[id]);
                        $qtde_recomendacao = sizeof($Recomendacao_relatorio);
                        
                        echo "<td align=center  valign=top >" . $qtde_recomendacao . "</td>";
                           
                        //escreve os valores das recomendações para cada mês
                        $recomendacao_meses = array(1 => '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-');
                        $tooltip_meses = array(1 => '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-');
                        $tooltip_recomendacao = '';
                        if ($qtde_recomendacao > 0) {
                            $data = explode('/', $data_relatorio);
                            $mes_homologacao_relatorio = intval($data[1]);
                            foreach ($Recomendacao_relatorio as $vetor_dados) {
                                $tooltip_recomendacao .= $vetor_dados['numero_item'] . '.' . $vetor_dados['numero_recomendacao'] . ',';
                            }
                            for ($i = $mes_homologacao_relatorio; $i <= 12; $i++) {
                                $recomendacao_meses[$i] = $qtde_recomendacao;
                                $tooltip_meses[$i] = $tooltip_recomendacao;
                            }
                            foreach ($Recomendacao_relatorio as $vetor_dados) {
                                $Resposta = Resposta::model()->findAll('recomendacao_fk=' . $vetor_dados['id'] . ' and tipo_status_fk in (3, 4) order by id desc LIMIT 1');
                                if (sizeof($Resposta)) {
                                    $data_resposta = explode('/', $Resposta[0]['data_resposta']);
                                    $mes_resposta = intval($data_resposta[1]);
                                    for ($i = $mes_resposta; $i <= 12; $i++) {
                                        $recomendacao_meses[$i] = is_numeric($recomendacao_meses[$i]) ? $recomendacao_meses[$i] - 1 : $recomendacao_meses[$i];
                                        $tooltip_meses[$i] = str_replace($vetor_dados['numero_item'] . '.' . $vetor_dados['numero_recomendacao'] . ',', '', $tooltip_meses[$i]);
                                    }
                                }
                            }
                        }        
                        foreach ($recomendacao_meses as $index => $vetor) {
                            echo '<td align=center title="' . substr($tooltip_meses[$index], 0, -1) . '">' . $vetor . '</td>';
                        }

                        // pega a espécie de Auditoria
                        $relatorio = Relatorio::model()->findByPk($vetor_relatorios[id]);
                        $especie_auditoria = EspecieAuditoria::model()->findByPk($relatorio->especie_auditoria_fk);
                        $barraE = ($vetor_relatorios[categoria_fk] == 2) ? "/E" : ""; //inclui /E nos relatórios extraordinários
                        echo "<td align=center width=30  valign=top >" . $especie_auditoria->sigla_auditoria . $barraE . "</td>";

                        // pega os auditores do relatório
                        echo "<td>";
                        $relatorio_auditores = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $vetor_relatorios[id]));
                        foreach ($relatorio_auditores as $vetor_auditor) {
                            $Auditor = Usuario::model()->findByPK($vetor_auditor->usuario_fk);
                            echo $Auditor->nome_usuario . "<br>";
                        }
                        echo "</td>";
                    }
                } else {
                    ?>
                    <tr>
                        <Td colspan="18">Nenhum registro encontrado</td>
                    </tr>
    <?php } ?>
            </table>
        </div><br>
        <table>
            <tr>
                <td valign="top">Obs.:</td>
                <td align="left">
                    <?php
                    $especie_auditoria = EspecieAuditoria::model()->findAll('1=1 order by sigla_auditoria');
                    if (sizeof($especie_auditoria) > 0) {
                        foreach ($especie_auditoria as $vetor) {
                            echo "<b>" . $vetor[sigla_auditoria] . "</b> - " . $vetor[nome_auditoria] . "<br>";
                        }
                    }
                    ?>
                    <b>/E</b> - Relatório Exatraordinário<br>
                </td>
            </tr>
        </table>

    </div>

    <br><br>
    <center>
        <input name="btn_voltar" type="button" class="botao" value="Voltar" onclick="history.go(-1);">
    </center>
<?php } ?>
