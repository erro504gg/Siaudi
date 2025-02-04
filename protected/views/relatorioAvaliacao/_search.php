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
<script type='text/javascript'>

    $(function() {
        $(document).ready(function() {
            $("#usuario_fk").multiselect('disable');
            $('#usuario_fk').multiselect('refresh');
        });
    });

</script>
<div class="formulario" Style="width: 70%;">

    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
    ));
    echo $form->errorSummary($model);
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar - <?php echo $model->label(); ?></legend>

        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'valor_exercicio'); ?>*</div>
            <div class='field'>
                <?php
                $form->widget('CMaskedTextField', array(
                    'model' => $model,
                    'attribute' => 'valor_exercicio',
                    'mask' => '9999',
                    'placeholder' => '_',
                    'completed' => "function(){
                                        $.ajax({
                                            url: '/siaudi2/RelatorioAvaliacao/CarregaAuditorPorExercicioAjax',
                                           type: 'POST',
                                          async: false,
                                           type: 'POST',
                                           data: 'valor_exercicio=' + $(this).val(),
                                        success: function(dados) {
                                                    if (dados != ''){
                                                       $('#usuario_fk').html(dados);
                                                       $('#usuario_fk').multiselect('enable');
                                                       $('#usuario_fk').multiselect('refresh');
                                                    } else {
                                                       alert('Não existem avalições para o exercício informado.');
                                                       $('#usuario_fk').multiselect('disable');
                                                       $('#usuario_fk').multiselect('refresh');
                                                    }
                                                 }
                                             });                        
                                            }",
                    'htmlOptions' => array(
                        'maxlength' => 4,
                    )
                ));
                ?>  (Ex: <?php echo date("Y"); ?>)
            </div>
        </div>                                
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'usuario_fk'); ?>*</div>
            <div class='field'>
                <?php
                $plan_auditor_dados = array(null => "Selecione") + CHtml::listData(Usuario::model()->findAll(array('order' => 'nome_usuario')), 'id', 'nome_usuario');
                $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                    'model' => $model,
                    'dropDownAttribute' => 'usuario_fk',
                    'data' => $plan_auditor_dados,
                    'dropDownHtmlOptions' => array(
                        'id' => 'usuario_fk',
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

                                        <div class="rowButtonsN1">


        <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>

        <input type="reset" class='botao' value='Limpar'>
        
    </div>
    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>
    <? if ($bNaoEncontrouRegistro) : ?>
        <fieldset class="visivel">
            Não foram encontrados registros para o(s) parâmetro(s) informado(s). 
        </fieldset> 
    <? endif ?>
    <?php $this->endWidget(); ?>
</div><!-- search-form -->
