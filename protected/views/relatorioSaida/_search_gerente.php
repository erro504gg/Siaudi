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
<script type="text/javascript">
    $(function() {
        $(document).ready(function() {
            $('#unidade_administrativa_fk').multiselect({
                click: function(event, ui) {
                    $("#Relatorio_id").html("");
                    CarregaRelatorioSaidaGerenteAjax();
                }
            });
        });
        $('#Relatorio_ano').on('blur', function() {
            $("#Relatorio_id").html("");
            CarregaRelatorioSaidaGerenteAjax();
        });
        $('#Relatorio_tipo_relatorio').click(function() {
            $("#Relatorio_id").html("");
            if ($(this).val() != "") {
                CarregaRelatorioSaidaGerenteAjax();
            }
        });
    });

    function CarregaRelatorioSaidaGerenteAjax() {
        $.ajax({
            type: 'POST',
            url: '<? echo CController::createUrl('RelatorioSaida/CarregaRelatorioSaidaGerenteAjax'); ?>',
            data: {unidade_administrativa_fk: $('#unidade_administrativa_fk').val(), tipo_relatorio: $('#Relatorio_tipo_relatorio').val(), ano: $('#Relatorio_ano').val()},
            success: function(data) {
                var dados = $("#Relatorio_id").html();
                $("#Relatorio_id").html(dados + data);
            }
        });
    }


</script>

<script type="text/javascript" src="js/RelatorioSaida2.js"></script>
<div class="formulario" Style="width: 70%;">

    <?php
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/mask.js');

    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'id' => 'relatorioSaida'
    ));
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar -  <?php echo $model->label(); ?></legend>

        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'tipo_relatorio'); ?> *</div>
            <div class='field'>
                <?php
                echo $form->dropDownList($model, 'tipo_relatorio', array('3' => 'Pré-Finalizados', '4' => 'Finalizados', '1' => 'Não Homologados', '2' => 'Homologados'), array('prompt' => Yii::t('app', ' Selecione'),
                    'style' => 'width:200px;',
                    'onchange' => 'auditor_abre_div()',
                        )
                );
                ?>
            </div>
        </div>  
        <div id="ano" style="display:none;">
            <div style="height:10px;"></div>                
            <div class='row'>                
                <div class='label'><?php echo $form->labelEx($model, 'ano'); ?> </div>
                <div class='field'><?php
                    echo $form->textField($model, 'ano', array('maxlength' => 4,
                        'onkeypress' => "return mascaraInteiro2(event);",
                        'size' => 5,
//                        'ajax' => array(
//                            'type' => 'POST',
////                            'beforeSend' => 'function(){
////                                                var unidade_administrativa_fk = $("#unidade_administrativa_fk").val();
////                                                if (unidade_administrativa_fk == "") { return false; }
////                                             }',
//                            'url' => CController::createUrl('RelatorioSaida/CarregaRelatorioSaidaGerenteAjax'),
//                            'update' => '#Relatorio_id',)
                    ));
                    ?> (Ex: <?php echo date("Y"); ?>)
                    <input type="button" name="busca_ano" value="Buscar">
                </div>
            </div>
            <div style="height:5px;"></div>
        </div>   
        <div id="unidade_administrativa" style="display:none;">
            <div style="height:10px;"></div>   
            <div class='row'>
                <div class='label'><?php echo $form->label($model, 'unidade_administrativa_fk'); ?></div>
                <div class='field'>
                    <?php
                    $acao_sureg_dados = array(null => "Selecione") + CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition" => '"sureg" = true', 'order' => 'sigla')), 'id', 'sigla');
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model,
                        'dropDownAttribute' => 'unidade_administrativa_fk',
                        'data' => $acao_sureg_dados,
                        'dropDownHtmlOptions' => array(
                            'id' => 'unidade_administrativa_fk',
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
            </div>  
            <div style="height:10px;"></div>   
        </div>

        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'id'); ?> *</div>
            <div class='field'>
                <?php
                echo $form->dropDownList($model, 'id', array(), array(
                    'style' => 'width:200px;',
                    'onchange' => 'valida()'
                ));
                ?>
            </div>          




    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->



