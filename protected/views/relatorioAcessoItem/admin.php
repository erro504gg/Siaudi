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
<script type="text/javascript">
    $('#auditor_fk').live('change', function() {
        $('#auditor_fk').multiselect({
            close: function(event, ui) {
                $("#auditor_fk").multiselect("refresh");
            }
        });
    });

    $(function() {
        $(document).ready(function() {
		    $("#auditor_fk").multiselect({
		    	   click: function(e){
		    	       if( $(this).multiselect("widget").find("input:checked").length > 0){   
		    	           $("#relatorio_fk").removeAttr("disabled");
		    	           $("#item_fk").multiselect('enable');
			    		   $("#RelatorioAcessoItem_liberar_todos_itens_0").removeAttr("disabled");
		    	    	   $("#RelatorioAcessoItem_liberar_todos_itens_1").removeAttr("disabled");
		    	       } else {
		    	    	   $("#relatorio_fk").attr("disabled", "disabled");
		    	    	   $("#RelatorioAcessoItem_liberar_todos_itens_0").attr("disabled", "disabled");
		    	    	   $("#RelatorioAcessoItem_liberar_todos_itens_1").attr("disabled", "disabled");
		    	    	   $("#item_fk").multiselect('disable');
				   	   }
		    	   },
		    	   checkAll: function(){
		    		   $("#relatorio_fk").removeAttr("disabled");
		    		   $("#item_fk").multiselect('enable');
		    		   $("#RelatorioAcessoItem_liberar_todos_itens_0").removeAttr("disabled");
	    	    	   $("#RelatorioAcessoItem_liberar_todos_itens_1").removeAttr("disabled");
		    	   },
		    	   uncheckAll: function(){
		    		   $("#relatorio_fk").attr("disabled", "disabled");
	    	    	   $("#RelatorioAcessoItem_liberar_todos_itens_0").attr("disabled", "disabled");
	    	    	   $("#RelatorioAcessoItem_liberar_todos_itens_1").attr("disabled", "disabled");
		    		   $("#item_fk").multiselect('disable');
		    	   },
		   	});
        });
	});
	
</script>


<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/RelatorioAcessoItem.js');
?>

<div class="formulario" Style="width: 70%;">
    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'relatorio-acesso-item-form',
        'enableAjaxValidation' => false,
    ));

    $item = Item::model()->findByPk($model->item_fk);
    $capitulo = Capitulo::model()->findByPk($item->capitulo_fk);
    $relatorio = Relatorio::model()->findByPk($capitulo->relatorio_fk);
    $relatorios_homologados = Relatorio::model()->ComboRelatorioHomologado();
    ?>    
    <?php echo $form->errorSummary($model); ?>
    <? if ($relatorios_homologados[0] != 'Sem relatórios'): ?>
        <fieldset class="visivel">
            <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>
<div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'nome_login'); ?></div>
                <div class='field'>
                    <?php
                    $usuario_logado = Usuario::model()->findByAttributes(array('nome_login' => Yii::app()->user->login));
//                    $list_auditor = CHtml::listData(Usuario::model()->findAll(), 'id', 'nome_usuario');
                    $list_auditor = CHtml::listData(Usuario::ObtemUsuariosSubordinados(), 'id', 'nome_usuario');
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model,
                        'dropDownAttribute' => 'auditor_fk',
                        'data' => $list_auditor,
                        'dropDownHtmlOptions' => array(
                            'id' => 'auditor_fk',
                            'multiple' => true,
                        ),
                        'options' => array(
                            'selectedList' => 1,
                            'minWidth' => '410',
                            'filter' => true,
                        ),
                        'filterOptions' => array(
                            'width' => 150,
                            'label' => Yii::t('application', 'Filtrar:'),
                            'placeholder' => Yii::t('application', 'digite aqui'),
                            'autoReset' => false,
                        ),
                    ));
                    if (is_array($list_auditor)) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($list_auditor as $vetor) {
                            echo "$(\"#relatorio_auditor option[value='" . $vetor->id . "']\").attr('selected', 'selected'); \n";
                        }
                        echo "}</script>";
                    }
                    ?>                
                </div>
            </div><!-- row --> 
            <div style="height:5px;"></div>           
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'relatorio_fk'); ?></div>
                <div class='field'><?php
                    $list_relatoriofk = array(null => "Selecione") + $relatorios_homologados;
                    echo CHtml::dropDownList('relatorio_fk', $relatorio->id, $list_relatoriofk, array('disabled' => 'disabled','style' => 'width:130px;',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('Item/CarregaItemAgrupadoPorCapituloAjax'),
							'success' => "function(data){
								$('#item_fk').html(data);	
                        		$('#item_fk').multiselect('refresh');
							}",
							)
                    ));
                    ?>
                </div>
            </div>
            <div style="height:5px;"></div>
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'liberar_todos_itens'); ?></div>
                <div class='field' id="radio">
                    <?php
                    $accountStatus = array('1' => 'Sim', '0' => 'Não');
                    echo $form->radioButtonList($model, 'liberar_todos_itens', $accountStatus, array('separator' => '&nbsp&nbsp&nbsp&nbsp'));
                    ?>
                </div>
            </div><!-- row -->
            <div style="height:5px;"></div>
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'item_fk'); ?></div>
                <div class='field'>
                    <?php
                    $model_item_fk = Item::model()->findAll('capitulo_fk=:capitulo_fk ORDER BY nome_item', array(':capitulo_fk' => (int) $capitulo->id));
                    $list_itemFk = CHtml::listData($model_item_fk, 'id', 'nome_item');
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model,
                        'dropDownAttribute' => 'item_fk',
                        'data' => $list_itemFk,
                        'dropDownHtmlOptions' => array(
                            'id' => 'item_fk',
                            'multiple' => true,
                        ),
                        'options' => array(
                            'selectedList' => 1,
                            'minWidth' => '410',
                            'filter' => true,
                        ),
                        'filterOptions' => array(
                            'width' => 150,
                            'label' => Yii::t('application', 'Filtrar:'),
                            'placeholder' => Yii::t('application', 'digite aqui'),
                            'autoReset' => false,
                        ),
                    ));
                    if (is_array($list_itemFk)) {
                        echo "\n <script type='text/javascript'> { \n";
                        foreach ($list_itemFk as $vetor) {
                            echo "$(\"#relatorio_itens option[value='" . $vetor->id . "']\").attr('selected', 'selected'); \n";
                        }
                        echo "}</script>";
                    }
                    ?>

                </div>
            </div><!-- row -->
            <div style="height:5px;"></div>
            
            <!--<div style="height:5px;"></div>-->
            <!--            <div class='row'>
                            <div class='label'><?php // echo $form->labelEx($model, 'unidade_administrativa_fk');  ?></div>
                            <div class='field'>
            <?php
//                    $relatorio_sureg_dados = array("" => "Selecione") + CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition"=>'"sureg" = true','order' => 'sigla')), 'id', 'sigla');
//                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
//                        'model' => $model,
//                        'dropDownAttribute' => 'unidade_administrativa_fk',
//                        'data' => $relatorio_sureg_dados,
//                        'dropDownHtmlOptions' => array(
//                            'id' => 'unidade_administrativa_fk',
//                        ),
//                        'options' => array(
//                            'selectedList' => 1,
//                            'minWidth' => '410',
//                            'filter' => true,
//                            'multiple' => false,
//                        ),
//                        'filterOptions' => array(
//                            'width' => 150,
//                            'label' => Yii::t('application', 'Filtrar:'),
//                            'placeholder' => Yii::t('application', 'digite aqui'),
//                            'autoReset' => false,
//                        ),
//                    ));
            ?>
            
                            </div>
                        </div> row  -->
        </fieldset>

        <p class="note">
            <?php echo Yii::t('app', 'Fields with'); ?>
            <span class="required">*</span>
            <?php echo Yii::t('app', 'are required'); ?>.
        </p>
        <div class="rowButtonsN1">
            <?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); ?>
            <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('RelatorioAcessoItem/index'), array('class' => 'imitacaoBotao')); ?>
        </div>
        <?
    else:
        echo "Usuário sem vínculo com relatório homologado.";
        ?>
        <div class="rowButtonsN1">
            <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('RelatorioAcessoItem/index'), array('class' => 'imitacaoBotao')); ?>
        </div>
    <? endif; ?>
    <?php $this->endWidget(); ?>
</div><!-- form -->
