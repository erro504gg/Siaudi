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
				$form = $this->beginWidget ( 'GxActiveForm', array (
						'id' => 'feriado-form',
						'enableAjaxValidation' => false 
				) );
				?>    
    <?php echo $form->errorSummary($model); ?>
    <script language="javascript">
            $('formulario').ready(function() {
                $('#Feriado_data_feriado').mask("99/99/9999");
            });
    </script>
	<fieldset class="visivel">
		<legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>
		<div class='row'>
			<div class='label'><?php echo $form->labelEx($model, 'data_feriado'); ?></div>
			<div class='field'><?php
			$form->widget ( 'zii.widgets.jui.CJuiDatePicker', array (
					'language' => 'pt-BR',
					'model' => $model,
					'attribute' => 'data_feriado',
					'value' => $model->data_feriado,
					'htmlOptions' => array (
							'size' => 10,
							'style' => 'width:70px;' 
					),
					'options' => array (
							'showButtonPanel' => true,
							'changeYear' => true,
							'dateFormat' => 'dd/mm/yy',
							'changeMonth' => true,
							'changeYear' => true 
					) 
			) );
			;
			?></div>
		</div>
		<!-- row -->
		<div style="height: 5px;"></div>
		<div class='row'>
			<div class='label'><?php echo $form->labelEx($model, 'nome_feriado'); ?></div>
			<div class='field'><?php echo $form->textField($model, 'nome_feriado', array('maxlength' => 60, 'size' => 35)); ?></div>
		</div>
		<!-- row -->
		<div class="row">
			<div class='label'></div>
			<div class="field">
                <?php
					echo $form->checkBox ( $model, 'repetir_todo_ano', array (
							'value' => '1',
							'uncheckValue' => '0' 
					) ) . " " . $form->label ( $model, 'repetir_todo_ano' );
				?>
            </div>
		</div>
	</fieldset>
	<p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
        <?php echo Yii::t('app', 'are required'); ?>.
    </p>
	<div class="rowButtonsN1">
        <?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); ?>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Feriado/index?' . $_SERVER['QUERY_STRING']), array('class' => 'imitacaoBotao')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
<!-- form -->
