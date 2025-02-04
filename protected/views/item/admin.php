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

var valor_digitado;
$(function() {
    $(document).ready(function() {

    	if ($("#Item_valor_nao_se_aplica").is(":checked")){
			valor_digitado = $("#Item_valor_reais").val();
			$("#Item_valor_reais").attr("value", "");
			$("#Item_valor_reais").attr('disabled',  'disabled');	
		}
        
		$("#Item_valor_nao_se_aplica").click(function(){
			if ($("#Item_valor_nao_se_aplica").is(":checked")){
				valor_digitado = $("#Item_valor_reais").val();
				$("#Item_valor_reais").attr("value", "");
				$("#Item_valor_reais").attr('disabled',  'disabled');	
			} else {
				$("#Item_valor_reais").attr("value", valor_digitado);
				$("#Item_valor_reais").removeAttr('disabled');
			}
		})
    });
});

</script>

<?php

// verifica se relatório do item já foi homologado, bloqueando sua edição
if ($_GET["capitulo_fk"] && $model->isNewRecord) {
    $model->capitulo_fk = $_GET["capitulo_fk"];
}
if($model->capitulo_fk){
$capitulo = Capitulo::model()->findByAttributes(array('id' => $model->capitulo_fk));
$relatorio = Relatorio::model()->findByAttributes(array('id' => $capitulo->relatorio_fk));
}

if ($relatorio->data_relatorio) {
    echo "<center><b>Não é possível editar este item, pois ele pertence a um relatório já homologado.</b></center>";
} else {
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl.'/js/mask.js');      
    $cs->registerScriptFile($baseUrl.'/js/jquery_maskmoney/jquery.maskMoney.js');
    $cs->registerScriptFile($baseUrl . '/js/Item.js');

    if (!$model->isNewRecord) {
        ?>
        <div class="formulario" Style="width:70%;">
            <fieldset class="visivel">
                <legend class="legendaDiscreta"> Navegação </legend> 
                <div style="width:100%;height:45px;">
                    <div class='tabelaListagemItensWrapper' style="width:170px;margin-left:10%;position:absolute;">
                        <a href="<?php echo '../../capitulo/admin/' . $model->capitulo_fk; ?>">
                            <img src="<?php echo Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/seta1.png"; ?>" border="0" align="left"></a>
                        &nbsp; &nbsp; 
                        <a href="<?php echo '../../capitulo/admin/' . $model->capitulo_fk; ?>">Voltar para capítulo</a>
                    </div>

                    <div class='tabelaListagemItensWrapper' style="width:200px;margin-left:25%;position:absolute;">
                        <a href="<?php echo '../../item/index?yt0=Consultar&Item%5Bcapitulo_fk%5D=' . $model->id; ?>">
                            <img src="<?php echo Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/seta2.png"; ?>" border="0" align="left"></a>
                        &nbsp; &nbsp; 
                        <a href="<?php echo '../../item/index?yt0=Consultar&Item%5Bcapitulo_fk%5D=' . $model->id; ?>">Ver outros itens deste capítulo</a>
                    </div>


                    <div class='tabelaListagemItensWrapper' style="width:200px;margin-left:42.5%;position:absolute;">
                        <a href="<?php echo '../../recomendacao/index?yt0=Consultar&Recomendacao%5Bitem_fk%5D=' . $model->id; ?>">
                            <img src="<?php echo Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/seta3.png"; ?>" border="0" align="left"></a>
                        &nbsp; &nbsp; 
                        <a href="<?php echo '../../recomendacao/index?yt0=Consultar&Recomendacao%5Bitem_fk%5D=' . $model->id; ?>">Ver recomendações deste item</a>
                    </div>
                </div><!-- row -->

        </div>
        </fieldset>
        </div>
    <?php } ?>    
    <div class="formulario" Style="width: 58%;">
        <?php
        include_once(Yii::app()->basePath . '/../js/relatorio/tiny-mce-js.jsp');
        $form = $this->beginWidget('GxActiveForm', array(
            'id' => 'item-form',
            'enableAjaxValidation' => false,
        ));

        ?>    
        <?php echo $form->errorSummary($model); ?>
        <fieldset class="visivel">
            <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>


            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'id'); ?></div>
                <div class='field'><?php echo $form->textField($model, 'id', array('size' => 5, 'readonly' => 'true')); ?></div>
            </div><!-- row -->

            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'relatorio_fk'); ?></div>
                <div class='field'><?php
                    $list_relatoriofk = array(null => "Selecione") + GxHtml::listDataEx(Relatorio::model()->relatorio_por_especie_combo(), 'id', 'sigla_auditoria');
                    echo CHtml::dropDownList('relatorio_fk', $relatorio->id, $list_relatoriofk, array('style' => 'width:130px;',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('Capitulo/CarregaCapituloAjax'),
                            'update' => '#capitulo_fk',)
                    ));
                    ?>
                </div>
                <div class='row'>
                    <div class='label'><?php echo $form->labelEx($model, 'capitulo_fk'); ?></div>
                    <div class='field'>
                        <?php
                        $model_capitulo_fk = Capitulo::model()->findAll('relatorio_fk=:relatorio_fk ORDER BY numero_capitulo_decimal', array(':relatorio_fk' => (int) $relatorio->id));
                        $list_capitulofk = CHtml::listData($model_capitulo_fk, 'id', 'numero_capitulo');
                        echo CHtml::dropDownList('capitulo_fk', $capitulo->id, $list_capitulofk, array('style' => 'width:130px;',
                            'ajax' => array(
                                'type' => 'POST',
                                'url' => CController::createUrl('Item/CarregaItemAjax'),
                                'update' => '#item_fk',)
                        ));
                        ?>
                    </div>
                </div><!-- row -->


                <div style="height:8px;"></div>        
                <div class='row'>
                    <div class='label'><?php echo $form->labelEx($model, 'valor_reais'); ?> *</div>
                    <div class='field'>
                    	<?php echo $form->textField($model, 'valor_reais', array('style' => 'width:125px;')); ?>
 						<?php
							echo $form->checkBox ( $model, 'valor_nao_se_aplica', array (
														   'value' => '1',
														   'uncheckValue' => '0' 
												 		    )
												 ) . " " . $form->label ( $model, 'valor_nao_se_aplica' );
						?> 
                    </div>
                </div><!-- row -->
                <div style="height:12px;"></div>
                <div class='row'>
                    <div class='label'><?php echo $form->labelEx($model, 'nome_item'); ?></div>
                    <div class='field'><?php echo $form->textField($model, 'nome_item', array('maxlength' => 100, 'style' => 'width:527px;')); ?></div>
                </div><!-- row -->
                <div style="height:10px;"></div>    
                <div class='row'>
                    <div class='label'><?php echo $form->labelEx($model, 'objeto_fk'); ?></div>
                    <div class='field'><?php echo $form->dropDownList($model, 'objeto_fk', GxHtml::listDataEx(Objeto::model()->findAllAttributes(null, true)), array('style' => 'width:200px;')); ?></div>
                </div><!-- row -->
                <div style="height:10px;"></div>    
                <div class='row'>
                    <div class='label'><?php echo $form->labelEx($model, 'descricao_item'); ?></div>
                    <Table border="0" cellpadding="0 cellspacing=0"><tr><td>
                                <?php echo $form->textArea($model, 'descricao_item', array('cols' => '80', 'rows' => '5', 'style' => 'margin-left:300px;', 'class' => 'tinymce_advanced')); ?>
                            </td></tr></table>
                </div><!-- row -->

        </fieldset>
        <p class="note">
            <?php echo Yii::t('app', 'Fields with'); ?>
            <span class="required">*</span>
            <?php echo Yii::t('app', 'are required'); ?>.
        </p>
        <div class="rowButtonsN1">
            <input type="hidden" name="inserir_item" id="inserir_item" value="0">
            <input type="hidden" name="inserir_recomendacao" id="inserir_recomendacao" value="0">
            <input type="button" name="text" value="Gravar e inserir outro item" id="form_submit" onclick="envia_item('item');" class="botao" />
            <input type="button" name="text" value="Gravar e inserir recomendação" id="form_submit" onclick="envia_item('recomendacao');" class="botao" />

            <?php echo GxHtml::submitButton(Yii::t('app', 'Gravar e sair'), array('class' => 'botao')); ?>
            <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Item/index'), array('class' => 'imitacaoBotao')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div><!-- form -->
<?php } ?>
