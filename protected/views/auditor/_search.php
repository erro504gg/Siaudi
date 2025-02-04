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
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar -  <?php echo $model->label(); ?></legend>

        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'nome_login'); ?></div>
            <div class='field'>
                <?php echo $form->textField($model, 'nome_login', array('maxlength' => 50, 'size' => 35)); ?>
            </div>
        </div>                                                                            
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'nome_usuario'); ?></div>
            <div class='field'>
            <?php
                 $plan_usuario_dados= array(null=>"Selecione") + CHtml::listData(Usuario::model()->findAll(array('order'=>'nome_usuario')), 'id', 'nome_usuario');                 
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                    'model' => $model,
                    'dropDownAttribute' => 'nome_usuario',
                    'data' => $plan_usuario_dados,
                    'dropDownHtmlOptions' => array(
                        'id' => 'nome_usuario',
                    ),
                    'options' => array(
                        'selectedList' => 1,
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
        </div>                                
        <div style="height:3px"></div>
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'perfil_fk'); ?></div>
            <div class='field'>
                <?php echo $form->dropDownList($model, 'perfil_fk', GxHtml::listDataEx(Cargo::model()->findAllAttributes(null, true)), array('prompt' => Yii::t('app', ' Selecione'), 'style'=>'width:250px')); ?>
            </div>
        </div>                                
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'nucleo_fk'); ?></div>
            <div class='field'>
                <?php echo $form->dropDownList($model, 'nucleo_fk', GxHtml::listDataEx(Nucleo::model()->findAllAttributes(null, true)), array('prompt' => Yii::t('app', ' Selecione'), 'style'=>'width:250px')); ?>
            </div>
        </div>                
        <div align='right'>
            <input type="hidden" name="Usuario_sort" value="nome_usuario">
                <div class="rowButtonsN1">


        <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>

        <input type="reset" class='botao' value='Limpar'>
    </div>
    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>

    <div class="rowButtonsN1">
        <?php echo CHtml::link(Yii::t('app', 'Create'), $this->createUrl('Usuario/admin'), array('class' => 'imitacaoBotao')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->
