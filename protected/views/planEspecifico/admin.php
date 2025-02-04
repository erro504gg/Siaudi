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
        'id' => 'plan-especifico-form',
        'enableAjaxValidation' => false,
    ));
    ?>    
    <?php
    echo $form->errorSummary($model);
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/PlanEspecifico.js');

    include_once(Yii::app()->basePath . '/../js/planEspecifico/tiny-mce-js.jsp');
    ?>
    <fieldset class="visivel"  >
        <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>


        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'data_log'); ?></div>
            <div class='field'>
                <?php
                if ($model->data_log) {
                    echo "<input type=hidden name='data_log' value='" . $model->data_log . "'> " . $model->data_log_formatado;
                } else {
                    echo "<input type=hidden name='data_log' value='" . date("Y-m-d G:i:s") . "'> " . date("d/m/Y");
                }
                ?>            
            </div>
        </div><!-- row -->
        <div style="height:5px;"/></div>  

<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'valor_exercicio'); ?></div>
    <div class='field'>

        <?php
        echo $form->textField($model, 'valor_exercicio', array('maxlength' => 4,
            'size' => 15,
            'ajax' => array(
                'type' => 'POST',
                'url' => CController::createUrl('PlanEspecifico/CarregaAcaoAjax'),
                'update' => '#acao_fk',
            )
        ));
        ?>
        (Ex: <?php echo date("Y"); ?>)
    </div>
</div><!-- row -->
<div class='row'>
    <div class='label'><?php echo $form->label($model, 'data_inicio_atividade'); ?> <span class="required">*</span></div>
    <div class='field'>
        <?php echo $form->textField($model, 'data_inicio_atividade', array('maxlength' => 10, 'size' => 25)); ?>
        (Ex: <?php echo date("d/m/Y"); ?>)
    </div>
</div>

<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'acao_fk'); ?></div>
    <div class='field'><?php
        $model_acao_fk = Acao::model()->findAll('valor_exercicio=:valor_exercicio ORDER BY nome_acao', array(':valor_exercicio' => (int) $model->valor_exercicio));
        $list_processofk = CHtml::listData($model_acao_fk, 'id', 'nome_acao');
        echo CHtml::dropDownList('acao_fk', $model->acao_fk, $list_processofk, array(
            'ajax' => array(
                'type' => 'POST',
                'url' => CController::createUrl('PlanEspecifico/CarregaAcaoSuregAjax'),
				'beforeSend' => 'function(){
									$("#valor_sureg").html("");
									$("#PlanEspecifico_escopo_acao_ifr").contents().find("#tinymce").html("");
								 }',
				'success' => 'function(data){
									var conteudo_div = $("#valor_sureg").html();
									$("#valor_sureg").html(conteudo_div + data);
							  }',
				'complete' => 'function(){
				        		$.ajax({
				            		type: "POST",
				            		url: "'.CController::createUrl('PlanEspecifico/CarregaEscopoAcaoAjax').'",
									data: {acao_fk: $("#acao_fk").val()},
				        		})
					            .done(function(msg) {    
									$("#PlanEspecifico_escopo_acao_ifr").contents().find("#tinymce").html(msg);
								});
						}',
				),
//                 'update' => '#valor_sureg'),
            'style' => 'width:525px;'));
        ?></div>
</div><!-- row -->        

<div style="height:5px;"/></div>                
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'valor_sureg'); ?> <span class="required">*</span></div>
    <div class='field'><?php
        if ($model->acao_fk) {
            $model_acao_sureg_fk = AcaoSureg::model()->carrega_acaosureg($model->acao_fk);
            $list_sureg_fk = CHtml::listData($model_acao_sureg_fk, 'id', 'nome_sureg');
        } else {
            $list_sureg_fk = array('');
        }
        echo CHtml::dropDownList('valor_sureg', $model->valor_sureg, $list_sureg_fk, array('style' => 'width:525px;'));
        ?></div>
</div><!-- row --> 
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'unidade_administrativa_fk'); ?></div>
    <div class='field'>
        <?php
        $unidadeAdministrativa_dados = CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition" => '"sureg" = true', 'order' => 'sigla')), 'id', 'sigla');
        $header = array(null => "Nenhuma");
        $unidadeAdministrativa_dados = $header + $unidadeAdministrativa_dados;
        $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
            'model' => $model,
            'dropDownAttribute' => 'unidade_administrativa_fk',
            'data' => $unidadeAdministrativa_dados,
            'dropDownHtmlOptions' => array(
                'id' => 'unidade_administrativa_fk',
            ),
            'options' => array(
                'selectedList' => 1,
                'minWidth' => '525',
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
<div style="height:5px;"/></div>  
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model_plan_auditor, 'plan_auditor'); ?></div>
    <div class='field'>
        <?php
        $plan_auditor_dados = CHtml::listData(Usuario::model()->findAll(array('condition' => 'perfil_fk='.Perfil::model()->find("nome_interno = 'SIAUDI_AUDITOR'")->id, 'order' => 'nome_usuario')), 'id', 'nome_usuario');
        $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
            'model' => $model_plan_auditor,
            'dropDownAttribute' => 'plan_auditor',
            'data' => $plan_auditor_dados,
            'dropDownHtmlOptions' => array(
                'id' => 'plan_auditor',
                'multiple' => true,
            ),
            'options' => array(
                'selectedList' => 2,
                'minWidth' => '525',
                'filter' => true,
            ),
            'filterOptions' => array(
                'width' => 150,
                'label' => Yii::t('application', 'Filtrar:'),
                'placeholder' => Yii::t('application', 'digite aqui'),
                'autoReset' => false,
            ),
        ));
        // marca no select multiplo as opções previamente salvas
        if (is_array($plan_auditor)) {
            echo "\n <script type='text/javascript'> { \n";
            foreach ($plan_auditor as $vetor) {
                echo "$(\"#plan_auditor option[value='" . $vetor->usuario_fk . "']\").attr('selected', 'selected'); \n";
            }
            echo "}</script>";
        }

        // marca no select múltiplo as ações que vieram do $_POST
        $plan_auditor2 = $_POST['PlanEspecificoAuditor']['plan_auditor'];
        if (sizeof($plan_auditor2) > 0) {
            echo "\n <script type='text/javascript'> { \n";
            foreach ($plan_auditor2 as $vetor) {
                echo "$(\"#plan_auditor option[value='" . $vetor . "']\").attr('selected', 'selected'); \n";
            }
            echo "}</script>";
        }
        ?>

    </div>
</div><!-- row -->                        
<div style="height:5px;"></div>    
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'objeto_fk'); ?></div>
    <div class='field'><?php echo $form->dropDownList($model, 'objeto_fk', GxHtml::listDataEx(Objeto::model()->findAllAttributes(null, true, array('order' => 'nome_objeto'))), array('style' => 'width:200px;')); ?></div>
</div><!-- row -->
<div style="height:20x;"/></div>
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'escopo_acao'); ?></div>
    <div class='field'>
        <table>
            <tr>
                <td width="230"></td>
                <td><?php echo $form->textArea($model, 'escopo_acao', array('cols' => '100', 'rows' => '5', 'style' => 'margin-left:300px;', 'class' => 'tinymce_advanced')); ?></div></td>
            </tr>
        </table>
    </div><!-- row -->
</div><!-- row -->
<div style="height:15px;"/></div>              
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'observacao_representatividade'); ?></div>
    <div class='field'>
        <table>
            <tr>
                <td width="230"></td>
                <td><?php echo $form->textArea($model, 'observacao_representatividade', array('cols' => '100', 'rows' => '5', 'class' => 'tinymce_advanced')); ?></div></td>
            </tr>
        </table>

    </div><!-- row -->
    <div style="height:15px;"/></div>      
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'observacao_amostragem'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'observacao_amostragem', array('cols' => '100', 'rows' => '5', 'class' => 'tinymce_advanced')); ?></div>
</div><!-- row -->
<div style="height:15px;"/></div>                
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'observacao_questoes_macro'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'observacao_questoes_macro', array('cols' => '100', 'rows' => '5', 'class' => 'tinymce_advanced')); ?></div>
</div><!-- row -->
<div style="height:15px;"/></div>                
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'observacao_resultados'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'observacao_resultados', array('cols' => '100', 'rows' => '5', 'class' => 'tinymce_advanced')); ?></div>
</div><!-- row -->
<div style="height:15px;"/></div>                
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'observacao_legislacao'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'observacao_legislacao', array('cols' => '100', 'rows' => '5', 'class' => 'tinymce_advanced')); ?></div>
</div><!-- row -->
<div style="height:15px;"/></div>                
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'observacao_detalhamento'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'observacao_detalhamento', array('cols' => '100', 'rows' => '5', 'class' => 'tinymce_advanced')); ?></div>
</div><!-- row -->
<div style="height:15px;"/></div>                
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'observacao_tecnicas_auditoria'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'observacao_tecnicas_auditoria', array('cols' => '100', 'rows' => '5', 'class' => 'tinymce_advanced')); ?></div>
</div><!-- row -->
<div style="height:15px;"/></div>                
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'observacao_pendencias'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'observacao_pendencias', array('cols' => '100', 'rows' => '5', 'class' => 'tinymce_advanced')); ?></div>
</div><!-- row -->
<div style="height:15px;"/></div>                
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'observacao_custos'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'observacao_custos', array('cols' => '100', 'rows' => '5', 'class' => 'tinymce_advanced')); ?></div>
</div><!-- row -->
<div style="height:15px;"/></div>
<div class='row'>
    <div class='label'><?php echo $form->labelEx($model, 'observacao_cronograma'); ?></div>
    <div class='field'><?php echo $form->textArea($model, 'observacao_cronograma', array('cols' => '100', 'rows' => '5', 'class' => 'tinymce_advanced')); ?></div>
</div><!-- row -->


</fieldset>
<p class="note">
    <?php echo Yii::t('app', 'Fields with'); ?>
    <span class="required">*</span>
    <?php echo Yii::t('app', 'are required'); ?>.
</p>
<div class="rowButtonsN1">
    <input type="hidden" name="gerar_pdf" value="0" id="gerar_pdf" />                
    <?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); ?>
    <?php
    // gera o PDF somente se estiver na tela de edição

    if (!$model->isNewRecord) {
        ?>
        <input type="button" name="text" value="Confirmar e Gerar PDF" id="form_submit" onclick="envia_plan_especifico(1);" class="botao" />        
        <?php
    }
    echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('PlanEspecifico/index'), array('class' => 'imitacaoBotao'));
    ?>
</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
