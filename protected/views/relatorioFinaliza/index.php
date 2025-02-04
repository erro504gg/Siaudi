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
    $cs->registerScriptFile($baseUrl.'/js/RelatorioFinaliza.js');  
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
        'id'=>'finaliza'
            ));
    $mostra_confirmacao=0;    
    ?>
    
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Selecionar -  <?php echo $model->label(); ?></legend>
            <?php
                $relatorio_dados = array(null => "Selecione") + CHtml::listData(Relatorio::model()->relatorio_por_especie(null,"data_finalizado is null and (nucleo='f' or (nucleo='t' and login_pre_finaliza is not null and data_pre_finalizado is not null)) "),'id','sigla_auditoria');
                if(sizeof($relatorio_dados)<2) { echo 'Sem relatórios para finalizar'; }
                else { 
                    $mostra_confirmacao=1;
             ?>
           <div class='row'>
            <div class='label'><?php echo $form->label($model, 'id'); ?>  <span class="required">*</span></div>
            <div class='field'>
                <?php
                $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                    'model' => $model,
                    'dropDownAttribute' => 'id',
                    'data' => $relatorio_dados ,
                    'dropDownHtmlOptions' => array(
                        'id' => 'id',
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
        <div style="height:10px;"></div>            
        </div> 
        

           <div class='row'>
            <div class='label'><?php echo $form->label($model, 'prazo_manifestacao'); ?>  <span class="required">*</span></div>
            <div class='field'>
            <?php
            $array_manifestacao = array('1'=>'5 dias úteis', '0'=>'Sem manifestação (ir para homologação)');
            echo $form->radioButtonList($model,'prazo_manifestacao',$array_manifestacao,array('separator'=>'<br>'));
            ?>
            </div>
        </div>     
            <? } ?> 
    </fieldset>
    
    <? if ($mostra_confirmacao){ ?>
        <p class="note">
    <?php echo Yii::t('app', 'Fields with'); ?>
            <span class="required">*</span>
            <?php echo Yii::t('app', 'are required'); ?>.
        </p>
        <div class="rowButtonsN1">
                <a class="imitacaoBotao" href="javascript:valida();">Confirmar</a>
    <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('./'), array('class' => 'imitacaoBotao')); ?>
        </div>
    <? } ?>
    
    <?php $this->endWidget(); ?>
</div><!-- search-form -->
