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
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();    
    $cs->registerScriptFile($baseUrl.'/js/Resposta.js'); 
    $cs->registerScriptFile($baseUrl . '/js/mask.js');   
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'id'=>'Resposta'
            ));
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar - Relatório</legend>
        <div class='row'>
        		<div class='label'><?php echo $form->label($model, 'valor_exercicio'); ?></div>
        		<div class='field'>
                <?php
                    echo $form->textField($model, 'valor_exercicio', array('maxlength' => 4, 'size' => 25,
                    'onkeypress' => "return mascaraInteiro2(event);",
                    'ajax' => array(
                    'type' => 'POST',
                    'url' => CController::createUrl('resposta/CarregaRelatorioPorExercicioAjax'),
                    'update' => '#Resposta_relatorio_fk',)));
                ?>
                (Ex: <?php echo date("Y");?>)
                </div>    
        </div>
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'relatorio_fk'); ?></div>
            <div class='field'>
                <?php 
//                $resultado = Resposta::model()->FollowUp_combo();
                echo $form->dropDownList($model, 'relatorio_fk', array(), 
                                        array('prompt' => Yii::t('app', ' Selecione'),
                                              'onchange'=>'valida()')); ?>
        	</div>
        </div>                                
    </fieldset>
    
    <?php $this->endWidget(); ?>
</div><!-- search-form -->
