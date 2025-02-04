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
<div class="formulario" Style="width: 70%;">
    <?php
        $dadosRiscoPreIdentificados = RiscoPre::model()->findAllAttributes(null, true);

        $form = $this->beginWidget('GxActiveForm', array(
            'id' => 'risco-pos-form',
            'enableAjaxValidation' => false,
        ));

        $_jj = Yii::app()->getClientScript();
        Yii::app()->clientScript->registerScript('importar_risco_pre', "
    $('#RiscoPos_id').live('change',function(){
        $.ajax({
            url:'" . Yii::app()->createUrl('RiscoPos/ImportaRiscoPreAjax') . "',
            type:'GET',
            data: 'id=' + $(this).val(),
            //dataType:'json',
            success: function(data){
             var obj = jQuery.parseJSON(data)
             //console.log(obj);
             if (obj != null){
                $('#RiscoPos_nome_risco').val(obj.nome_risco);
                $('#RiscoPos_descricao_impacto').val(obj.descricao_impacto);
                $('#RiscoPos_descricao_mitigacao').val(obj.descricao_mitigacao);
             }
             else {
                $('#RiscoPos_nome_risco').val('');
                $('#RiscoPos_descricao_impacto').val('');
                $('#RiscoPos_descricao_mitigacao').val('');
             }
            },
            error: function(data) {
                alert(\"Ocorreu um erro no sistema.\");
                alert(data);
            },
        });
    });");

        echo $form->errorSummary($model); 
    ?>
    <fieldset class="visivel">
         <p class="validateTips"></p>
        <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>

        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'importar_risco_pre'); ?></div>
            <div class='field'><?php echo $form->dropDownList($model, 'id', GxHtml::listDataEx($dadosRiscoPreIdentificados), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:150px;')); ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'nome_risco'); ?></div>
            <div class='field'><?php echo $form->textArea($model, 'nome_risco', array('cols' => '100', 'rows' => '5'));
    ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'descricao_impacto'); ?></div>
            <div class='field'><?php echo $form->textArea($model, 'descricao_impacto', array('cols' => '100', 'rows' => '5'));
    ?></div>
        </div><!-- row -->

        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'descricao_mitigacao'); ?></div>
            <div class='field'><?php
                echo $form->textArea($model, 'descricao_mitigacao', array('cols' => '100', 'rows' => '5')
                );
                ?></div>
        </div><!-- row -->

    </fieldset>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
        <?php echo Yii::t('app', 'are required'); ?>.
    </p>

    <?php $this->endWidget(); ?>
</div><!-- form -->
