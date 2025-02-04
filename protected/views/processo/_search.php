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
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    echo $form->errorSummary($model); 
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar -  <?php echo $model->label(); ?></legend>
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'valor_exercicio'); ?></div>
            <div class='field'>
                <?php //echo $form->textField($model, 'valor_exercicio'); 
                        $form->widget('CMaskedTextField', array(
                            'model' => $model,
                            'attribute' => 'valor_exercicio',
                            'mask' => '9999',
                            'placeholder' => '_',
                            'htmlOptions' => array('maxlength' => 4,'style'=>'width:85px;')
                        ));                
                ?>
            </div>
        </div>                
        <div style="height:4px"></div>
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'nome_processo'); ?></div>
            <div class='field'>
                <?php echo $form->textField($model, 'nome_processo', array('maxlength' => 200, 'size' => 35, 'style'=>'width:205px;')); ?>
            </div>
        </div>                                
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'tipo_processo_fk'); ?></div>
            <div class='field'>
                <?php echo $form->dropDownList($model, 'tipo_processo_fk', GxHtml::listDataEx(TipoProcesso::model()->findAllAttributes(null, true)), array('prompt' => Yii::t('app', ' Selecione'))); ?>
            </div>
        </div>    
        <div style="height:10px"></div>        
                        <div class="rowButtonsN1">


        <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>

        <input type="reset" class='botao' value='Limpar'>
        
    </div>
    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>

    <div class="rowButtonsN1">
        <?php echo CHtml::link(Yii::t('app', 'Create'), $this->createUrl('Processo/admin'), array('class' => 'imitacaoBotao')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->
