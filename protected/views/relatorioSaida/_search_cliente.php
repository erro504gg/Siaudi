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
<script type="text/javascript" src="js/RelatorioSaida2.js"></script>
<div class="formulario" Style="width: 70%;">
    
    <?php
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/mask.js');
    
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'id' =>'relatorioSaida'
            ));

    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar -  <?php echo $model->label(); ?></legend>
      
        <div class='row' >
            <div class='label'><?php echo $form->label($model, 'tipo_relatorio'); ?> *</div>
            <div class='field'>
                <?php echo $form->dropDownList($model, 'tipo_relatorio', array('1'=>'Não Homologados','2'=>'Homologados'), 
                        array('prompt' => Yii::t('app', ' Selecione'), 
                            'style' => 'width:200px;',
                            'onchange'=>'auditor_abre_div()',
                            'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('RelatorioSaida/CarregaRelatorioSaidaAjax'),
                            'update' => '#Relatorio_id') 
                         )
                        ); ?>
            </div>
        </div>    
            <div id="ano" style="display:none;">
                <div style="height:10px;"></div>                
               <div class='row'>                
                <div class='label'><?php echo $form->labelEx($model, 'ano'); ?> </div>
                <div class='field'><?php echo $form->textField($model, 'ano', 
                                                  array('maxlength' => 4,
                                                        'onkeypress'=>"return mascaraInteiro2(event);",
                                                        'size' => 5,
                                                        'ajax' => array(
                                                        'type' => 'POST',
                                                        'url' => CController::createUrl('RelatorioSaida/CarregaRelatorioSaidaAjax'),
                                                        'update' => '#Relatorio_id',)                                                    
                                                    )); ?> 
                                                    (Ex: <?php echo date("Y"); ?>)
                    <input type="button" name="busca_ano" value="Buscar">
                </div>
               </div>
                <div style="height:5px;"></div>
            </div>        
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'id'); ?> *</div>
                <div class='field'>
                    <?php
                     echo $form->dropDownList($model, 'id', array(), array(
                         'style' => 'width:200px;',
                         'onchange'=>'valida()'
                         )); ?>
                </div>          


                

    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>
    <br><br>
    <font size="2">
    <b>OBS.:   NÃO É PERMITIDO SALVAR, COPIAR OU IMPRIMIR O RELATÓRIO DE AUDITORIA.</b><br>
    TODAS AS OPERAÇÕES REALIZADAS NO SISTEMA SÃO AUDITADAS.</font>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->



