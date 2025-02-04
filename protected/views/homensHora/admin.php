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
        'id' => 'homens-hora-form',
        'enableAjaxValidation' => false,
            ));
    ?>    
<?php echo $form->errorSummary($model); ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>


        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'valor_exercicio'); ?></div>
            <div class='field'>
                <?php 
                $form->widget('CMaskedTextField', array(
                              'model' => $model,
                              'attribute' => 'valor_exercicio',
                              'mask' => '9999',
                              'placeholder' => '_',
                              'htmlOptions' => array('maxlength' => 4)
                              )); ?>  (Ex: <?php echo date("Y");?>)</div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'usuario_fk'); ?></div>
            <div class='field'>

                <?php
                 $plan_auditor_dados= CHtml::listData(Usuario::model()->findAll(array('order'=>'nome_usuario')), 'id', 'nome_usuario');
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                    'model' => $model,
                    'dropDownAttribute' => 'usuario_fk',
                    'data' => $plan_auditor_dados,
                    'dropDownHtmlOptions' => array(
                        'id' => 'usuario_fk',
                    ),
                    'options' => array(
                        'selectedList' => 2,
                        'minWidth' => '250',
                        'filter'=>true,                        
                        'multiple' => false,                                                
                    ),
                    'filterOptions'=> array(
                         'width'=>150,
                        'label' => Yii::t('application','Filtrar:'),
                        'placeholder'=>Yii::t('application','digite aqui'),
                        'autoReset'=>false,                        
                     ),                        
                ));  

                ?>
                
                
            </div>
        </div><!-- row -->
        <div style="height:10px;"></div>
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'valor_asterisco'); ?></div>
            <div class='field'><?php echo $form->textField($model, 'valor_asterisco'); ?>  (Ex: *, **, ***, etc)</div>
        </div><!-- row -->        
        <div style="height:10px;"></div>
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'valor_horas_homem'); ?></div>
            <div class='field'><?php echo $form->textField($model, 'valor_horas_homem'); ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'valor_ferias'); ?></div>
            <div class='field'><?php echo $form->textField($model, 'valor_ferias'); ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'valor_lic_premio'); ?></div>
            <div class='field'><?php echo $form->textField($model, 'valor_lic_premio'); ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'valor_outros'); ?></div>
            <div class='field'><?php echo $form->textField($model, 'valor_outros'); ?></div>
        </div><!-- row -->

    </fieldset>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
<?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
        <?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); ?>
    <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('HomensHora/index'), array('class' => 'imitacaoBotao')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->
