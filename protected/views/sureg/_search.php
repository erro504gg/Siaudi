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

    <?php $form = $this->beginWidget('GxActiveForm', array(
	'action' => Yii::app()->createUrl($this->route),
	'method' => 'get',
)); ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Dados da Consulta</legend>
                                                                            
<div class='row'>
<div class='label'><?php echo $form->label($model, 'nome'); ?></div>
            <div class='field'>
<?php echo $form->textField($model, 'nome', array('maxlength' => 80,'size' => 35)); ?>
</div>
</div>                                
<div class='row'>
<div class='label'><?php echo $form->label($model, 'sigla'); ?></div>
            <div class='field'>
<?php echo $form->textField($model, 'sigla', array('maxlength' => 20,'size' => 20)); ?>
</div>
</div>                                
<div class='row'>
<div class='label'><?php echo $form->label($model, 'uf_fk'); ?></div>
            <div class='field'>
<?php echo $form->textField($model, 'uf_fk', array('maxlength' => 2,'size' => 2)); ?>
</div>
</div>                                
<div class='row'>
<div class='label'><?php echo $form->label($model, 'subordinante_fk'); ?></div>
            <div class='field'>
<?php echo $form->textField($model, 'subordinante_fk'); ?>
</div>
</div>        

    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>
                                <div class="rowButtonsN1">


        <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>

        <input type="reset" class='botao' value='Limpar'>
        
    </div>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->
