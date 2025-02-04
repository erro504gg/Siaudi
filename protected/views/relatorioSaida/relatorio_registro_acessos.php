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
        $("#Relatorio_periodo_inicio").mask("99/99/9999");
        $("#Relatorio_periodo_fim").mask("99/99/9999");
    });
</script>

<?php
// se os parâmetros da busca foram passados corretamente, então mostra os resultados. 
if ($parametros) {
    ?>


    <div class="tabelaListagemItensWrapper">
        <div align="center" class="labelCampo">
            <table border="0" class="labelCampo" width="450">
                <?php
                if ($parametros['filtro_acesso'] == "cliente") {
                    $Relatorio = Relatorio::model()->findByPk($parametros['relatorio_id']);
                    $numero_relatorio = ($Relatorio->numero_relatorio) ? $Relatorio->numero_relatorio : "ID " . $Relatorio->id;
                    $data_relatorio = ($Relatorio->data_relatorio) ? $Relatorio->data_relatorio : "Não homologado";
                    ?>
                    <tr>
                        <td><b>Nº Relatório:</b> <?= $numero_relatorio; ?></td>
                        <td><b>Data:</b> <?= $data_relatorio; ?></td>
                        <td><b><?= $vetor_relatorio[nome_auditoria]; ?></b></td>
                    </tr>
                    <tr>
                        <td colspan="3 "valign="top"><table cellpadding="0" cellspacing="0">
                                <tr> 
                                    <td valign="top" align="right"><b>Unidade Auditada: </b></td>
                                    <td valign="top" align="left"><?php
                                        $relatorio_sureg = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $Relatorio->id));
                                        if (is_array($relatorio_sureg)) {
                                            foreach ($relatorio_sureg as $vetor_sureg) {
                                                $sureg = UnidadeAdministrativa::model()->findByAttributes(array('id' => $vetor_sureg->unidade_administrativa_fk));
                                                echo "&nbsp;" . $sureg->sigla . "/" . $sureg->uf_fk . "<br>";
                                            }
                                        }
                                        ?>                        
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?
                }
                // centraliza coluna abaixo caso filtro da busca seja de "auditor"
                $center = ($parametros['filtro_acesso'] == "auditor") ? "align='center'" : "";
                $acessos_de = ($parametros['filtro_acesso'] == "auditor") ? "auditores" : "clientes";
                ?>
                <tr>
                    <td colspan="3" <?php echo $center; ?>><b>Acessos de  <?= $acessos_de; ?> entre:</b> <?= $parametros['periodo_inicio']; ?> e <?= $parametros['periodo_fim']; ?></td>
                </tr>                
            </table>

            <div class="tabelaListagemItens">
                <table border="0" cellpadding="10" align="center">
                    <tr>
                        <th nowrap>Login</th>
                        <th nowrap>Data</th>
                        <th nowrap>Hora</th>
                        <th nowrap>IP</th>                        
                        <th nowrap>Ação</th>
                    </tr>
                    <?php
                    if (sizeof($busca_registros_acessos) > 0) {
                        foreach ($busca_registros_acessos as $vetor) {
                            $data = explode(" ", $vetor[data_entrada]);
                            $hora = $data[1];
                            $data = MyFormatter::converteData($data[0]);

                            // define a ação do usuário de acordo com o filtro da busca
                            $acao = "Entrou no sistema."; // ação padrão 
                            if ($parametros['filtro_acesso'] == "cliente") {
                                if ($vetor[relatorio_fk]) {
                                    $acao = "Acessou este relatório.";
                                }
                                if ($vetor[item_fk]) {
                                    $acao = "Acessou um item deste relatório.";
                                }
                            }
                            if ($parametros['filtro_acesso'] == "auditor") {
                                if ($vetor[relatorio_fk]) {
                                    $relatorio_acessado = Relatorio::model()->findByPk($vetor[relatorio_fk]);
                                    $numero_relatorio = ($relatorio_acessado->numero_relatorio) ? "Nº " . $relatorio_acessado->numero_relatorio . " de " . $relatorio_acessado->data_relatorio : "ID " . $relatorio_acessado->id;
                                    $acao = "Acessou o relatório " . $numero_relatorio . ".";
                                }
                                if ($vetor[item_fk]) {
                                    $item_acessado = Item::model()->findByPk($vetor[item_fk]);
                                    $capitulo = Capitulo::model()->findByAttributes(array('id' => $item_acessado->capitulo_fk));
                                    $relatorio_acessado = Relatorio::model()->findByAttributes(array('id' => $capitulo->relatorio_fk));
                                    $numero_relatorio = ($relatorio_acessado->numero_relatorio) ? "Nº " . $relatorio_acessado->numero_relatorio : "ID " . $relatorio_acessado->id;
                                    $acao = "Acessou um item do relatório " . $numero_relatorio . ".";
                                }
                            }

                            echo "<tr>
                            <td align=center>$vetor[nome_login]</td>
                            <td align=center>$data</td>
                            <td align=center>$hora</td>
                            <td align=center>$vetor[valor_ip]</td>                                
                            <td align=left>$acao</td>
                        </tr>";
                        }
                    } else {
                        echo "<tr>
                            <td colspan=5>Nenhum registro encontrado</td>
                            </tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div><br>
    <center><br>
        <?php echo CHtml::link(Yii::t('app', 'Back'), $this->createUrl('RelatorioSaida/RegistrosAcessosAjax'), array('class' => 'imitacaoBotao')); ?>
    </center>

<? } else { ?>
    <div class="formulario" Style="width: 70%;">
        <?php
        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl . '/js/RelatorioRegistrosAcessos.js');

        $form = $this->beginWidget('GxActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'get',
            'id' => 'relatorioSaida'
        ));
        echo $form->errorSummary($model);
        ?>
        <fieldset class="visivel">
            <legend class="legendaDiscreta">Consultar </legend>

            <div style='height:10px;'></div>
            <div class='row'>
                <div class='label'>Filtrar acessos de: <span class="required">*</span></div>
                <?
                $check_auditor = ($_GET["filtro_acesso"] == "auditor") ? "checked" : "";
                $check_cliente = ($_GET["filtro_acesso"] == "cliente") ? "checked" : "";
                ?>
                <div class='field'>
                    <?php
                    if (strtolower(Yii::app()->user->role) == "siaudi_auditor") {
                        $disabled_auditor = "disabled='true'";
                        $check_auditor = "unchecked";
                        $check_cliente = "checked";
                    } else {
                        $disabled_auditor = '';
                    }
                    ?>
                    <input id='acesso_auditor' <?= $disabled_auditor ?> name='filtro_acesso' type='radio' value="auditor" <?= $check_auditor; ?>/><label for="acesso_auditor"> Auditor</label> 
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                    <input id='acesso_cliente' name='filtro_acesso' type='radio' value="cliente"  <?= $check_cliente; ?>/><label for="acesso_cliente"> Cliente </label><br />
                </div>
            </div><!-- row -->        

            <? $display_cliente = ($_GET["filtro_acesso"] == "cliente") ? "blocked" : "none"; ?>
            <div class='row' id='relatorio_cliente' style='display:<?= $display_cliente; ?>'>        
                <div class='row'>
                    <div class='label'><?php echo $form->labelEx($model, 'unidade_administrativa_fk'); ?>  <span class="required"></span></div>
                    <div class='field'>
                        <?php
                        $relatorio_sureg_dados = array(0 => "Selecione") + CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition" => '"sureg" = true', 'order' => 'sigla')), 'id', 'sigla');
                        $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                            'model' => $model,
                            'dropDownAttribute' => 'unidade_administrativa_fk',
                            'data' => $relatorio_sureg_dados,
                            'dropDownHtmlOptions' => array(
                                'id' => 'unidade_administrativa_fk',
                                'ajax' => array(
                                    'type' => 'POST',
                                    'url' => CController::createUrl('RelatorioSaida/CarregaRelatorioSuregAjax'),
                                    'update' => '#Relatorio_id',)
                            ),
                            'options' => array(
                                'selectedList' => 1,
                                'minWidth' => '250',
                                'filter' => true,
                                'multiple' => false,
                            ),
                            'filterOptions' => array(
                                'width' => 150,
                                'label' => Yii::t('application', 'Filtrar:'),
                                'placeholder' => Yii::t('application', 'digite aqui'),
                                'autoReset' => false,
                            ),
                        ));
                        ?>

                    </div>
                </div><!-- row -->    
                <div style="height:5px;"></div>               


                <div class='row'>
                    <div class='label'>Nº Relatório  <span class="required">*</span></div>
                    <div class='field'>
                        <?php
                        echo $form->dropDownList($model, 'id', array(), array(
                            'style' => 'width:250px;',
                            'onchange' => 'valida()'
                        ));
                        ?>
                    </div>          
                </div>
            </div>            
            <div style="height:5px;"></div>        

            <div class='row'>
                <div class='label'>Período  <span class="required">*</span></div>
                <div class='field'>
                    <?php
                    $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'language' => 'pt-BR',
                        'model' => $model,
                        'attribute' => 'periodo_inicio',
                        'value' => $model->periodo_inicio,
                        'htmlOptions' => array('size' => 15),
                        'options' => array(
                            'showButtonPanel' => true,
                            'changeYear' => true,
                            'dateFormat' => 'dd/mm/yy',
                            'changeMonth' => true,
                            'changeYear' => true,
                            'maxDate' => date('d/m/Y'),
                        ),
                    ));
                    ;
                    ?>
                    &nbsp; &nbsp; 
                    a
                    &nbsp; &nbsp;  &nbsp; 
                    <?php
                    $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'language' => 'pt-BR',
                        'model' => $model,
                        'attribute' => 'periodo_fim',
                        'value' => $model->periodo_fim,
                        'htmlOptions' => array('size' => 15),
                        'options' => array(
                            'showButtonPanel' => true,
                            'changeYear' => true,
                            'dateFormat' => 'dd/mm/yy',
                            'changeMonth' => true,
                            'changeYear' => true,
                            'maxDate' => date('d/m/Y'),
                        ),
                    ));
                    ;
                    ?>                    


                </div>
            </div><!-- row -->


            <div align='right'><?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?></div>
        </fieldset>

        <p class="note">
            <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
        </p>
        <?php $this->endWidget(); ?>
    </div><!-- search-form -->
<? } ?>
