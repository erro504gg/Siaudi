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
        <?php /*
          <div class='row'>
          <div class='label'><?php echo $form->label($model, 'id'); ?></div>
          <div class='field'>
          <?php echo $form->textField($model, 'id'); ?>
          </div>
          </div>
         */ ?>
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'id'); ?></div>
            <div class='field'><?php echo $form->dropDownList($model, 'id', GxHtml::listDataEx(Relatorio::model()->relatorio_por_especie_combo(), 'id', 'sigla_auditoria'), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:410px;')); ?>                
            </div>        
            <div class='row'>
                <div class='label'><?php echo $form->label($model, 'especie_auditoria_fk'); ?></div>
                <div class='field'>
                    <?php echo $form->dropDownList($model, 'especie_auditoria_fk', GxHtml::listDataEx(EspecieAuditoria::model()->findAllAttributes(null, true)), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:410px;')); ?>
                </div>
            </div>                                
            <div class='row'>
                <div class='label'><?php echo $form->label($model, 'categoria_fk'); ?></div>
                <div class='field'>
                    <?php echo $form->dropDownList($model, 'categoria_fk', GxHtml::listDataEx(Categoria::model()->findAllAttributes(null, true)), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:410px;')); ?>
                </div>
            </div>      
            <div class='row'>
                <div class='label'><?php echo $form->label($model, 'diretoria_fk'); ?></div>
                <div class='field'>
                    <?php
                    $relatorio_diretoria_dados = array(null => "Selecione") + CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition"=>'"diretoria" = true','order' => 'sigla')), 'id', 'sigla');
                    //debug($relatorio_diretoria_dados);
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model,
                        'dropDownAttribute' => 'diretoria_fk',
                        'data' => $relatorio_diretoria_dados,
                        'dropDownHtmlOptions' => array(
                            'id' => 'diretoria_fk',
                        ),
                        'options' => array(
                            'selectedList' => 1,
                            'minWidth' => '410',
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

            <div class='row'>
                <div class='label'><?php echo $form->label($model, 'unidade_administrativa_fk'); ?></div>
                <div class='field'>
                    <?php
                    $acao_sureg_dados = array(null => "Selecione") + CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition"=>'"sureg" = true','order' => 'sigla')), 'id', 'sigla');
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model,
                        'dropDownAttribute' => 'unidade_administrativa_fk',
                        'data' => $acao_sureg_dados,
                        'dropDownHtmlOptions' => array(
                            'id' => 'unidade_administrativa_fk',
                        ),
                        'options' => array(
                            'selectedList' => 1,
                            'minWidth' => '410',
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

                                            <div class="rowButtonsN1">


        <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>

        <input type="reset" class='botao' value='Limpar'>
        
    </div>
    </fieldset>


    <div class="rowButtonsN1">
        <?php echo CHtml::link(Yii::t('app', 'Create'), $this->createUrl('Relatorio/admin'), array('class' => 'imitacaoBotao')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->
