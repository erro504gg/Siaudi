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
<?php
// verifica se relatório do item já foi homologado, bloqueando sua edição

if ($_REQUEST["item_fk"] && $model->isNewRecord) {
    $model->item_fk = $_REQUEST["item_fk"];
}
if ($model->item_fk != "") {
    $item = Item::model()->findbypk($model->item_fk);
    $capitulo = Capitulo::model()->findByAttributes(array('id' => $item->capitulo_fk));
    $relatorio = Relatorio::model()->findByAttributes(array('id' => $capitulo->relatorio_fk));
}
if ($relatorio->data_relatorio) {
    echo "<center><b>Não é possível editar esta recomendação, pois ela pertence a um relatório já homologado.</b></center>";
} else {
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/mask.js');
    $cs->registerScriptFile($baseUrl . '/js/Recomendacao.js');
    $cs->registerCssFile($baseUrl . '/css/estiloChamada.css');

    if (!$model->isNewRecord) {
        ?>
        <div class="formulario" Style="width:70%;">
            <fieldset class="visivel">
                <legend class="legendaDiscreta"> Navegação </legend> 
                <div style="width:100%;height:45px;">
                    <div class='tabelaListagemItensWrapper' style="width:170px;margin-left:15%;position:absolute;">
                        <a href="<?php echo '../../item/admin/' . $model->item_fk; ?>">
                            <img src="<?php echo Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/seta1.png"; ?>" border="0" align="left"></a>
                        &nbsp; &nbsp; 
                        <a href="<?php echo '../../item/admin/' . $model->item_fk; ?>">Voltar para item</a>
                    </div>

                    <div class='tabelaListagemItensWrapper' style="width:250px;margin-left:30%;position:absolute;">
                        <a href="<?php echo '../../recomendacao/index?yt0=Consultar&Recomendacao%5Bitem_fk%5D=' . $model->item_fk; ?>">
                            <img src="<?php echo Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/seta2.png"; ?>" border="0" align="left"></a>
                        &nbsp; &nbsp; 
                        <a href="<?php echo '../../recomendacao/index?yt0=Consultar&Recomendacao%5Bitem_fk%5D=' . $model->item_fk; ?>">Ver outras recomendações deste item</a>
                    </div>                
                </div>
            </fieldset>
        </div>
    <?php } ?>      
    <div class="formulario" Style="width: 58%;">
        <?php
        include_once(Yii::app()->basePath . '/../js/relatorio/tiny-mce-js.jsp');
        $form = $this->beginWidget('GxActiveForm', array(
            'id' => 'recomendacao-form',
            'enableAjaxValidation' => false,
        ));


        $_jj = Yii::app()->getClientScript();
        Yii::app()->clientScript->registerScript('valida_recomendacao', "
        $('#relatorio_fk').live('change',function(){
            relatorio_fk = $(this).val();        
                $.ajax({
                    url:'" . Yii::app()->createUrl('Capitulo/CarregaCapituloAjax') . "',
                    type:'POST',
                    data: 'relatorio_fk=' + relatorio_fk,
                    success: function(data){
                            $('select#capitulo_fk').html(data);
                            $('#item_fk').empty();
                            $('#unidade_administrativa_fk').empty();                            
                    },
                    error: function(data) { 
                        alert(\"Ocorreu um erro no sistema.\");
                        alert(data);
                    },
                });
        });
        
        $('#capitulo_fk').live('change',function(){
            capitulo_fk = $(this).val();        
                $.ajax({
                    url:'" . Yii::app()->createUrl('Item/CarregaItemAjax') . "',
                    type:'POST',
                    data: 'capitulo_fk=' + capitulo_fk,
                    success: function(data){
                            $('select#item_fk').html(data);
                            $('#unidade_administrativa_fk').empty();                            
                    },
                    error: function(data) { 
                        alert(\"Ocorreu um erro no sistema.\");
                        alert(data);
                    },
                });
        });
        ");
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
                    echo CHtml::dropDownList('relatorio_fk', $relatorio->id, $list_relatoriofk, array('style' => 'width:250px;'));
                    /*
                      echo CHtml::dropDownList('relatorio_fk', $relatorio->id, $list_relatoriofk, array('style' => 'width:250px;',
                      'ajax' => array(
                      'type' => 'POST',
                      'url' => CController::createUrl('Capitulo/CarregaCapituloAjax'),
                      'update' => '#capitulo_fk',)
                      )); */
                    ?>
                </div>

            </div><!-- row -->
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'capitulo_fk'); ?></div>
                <div class='field'>
                    <?php
                    $model_capitulo_fk = Capitulo::model()->findAll('relatorio_fk=:relatorio_fk ORDER BY numero_capitulo_decimal', array(':relatorio_fk' => (int) $relatorio->id));
                    $list_capitulofk = CHtml::listData($model_capitulo_fk, 'id', 'numero_capitulo');
                    echo CHtml::dropDownList('capitulo_fk', $capitulo->id, $list_capitulofk, array('style' => 'width:250px;',));

                    /*
                      echo CHtml::dropDownList('capitulo_fk', $capitulo->id, $list_capitulofk, array('style' => 'width:250px;',
                      'ajax' => array(
                      'type' => 'POST',
                      'url' => CController::createUrl('Item/CarregaItemAjax'),
                      'update' => '#item_fk',)
                      )); */
                    ?>
                </div>
            </div><!-- row -->
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'item_fk'); ?></div>
                <div class='field'><?php //echo $form->dropDownList($model, 'item_fk', GxHtml::listDataEx(Item::model()->findAllAttributes(null, true)));       ?>
                    <?php
                    $model_item_fk = Item::model()->findAll('capitulo_fk=:capitulo_fk ORDER BY nome_item', array(':capitulo_fk' => (int) $capitulo->id));
                    $list_itemFk = CHtml::listData($model_item_fk, 'id', 'nome_item');
                    echo CHtml::dropDownList('item_fk', $item->id, $list_itemFk, array(
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('Item/CarregaSuregAjax'),
                            'update' => '#unidade_administrativa_fk',),
                        'style' => 'width:250px;'));
                    ?>                
                </div>
            </div><!-- row -->
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'unidade_administrativa_fk'); ?></div>
                <div class='field'>
                    <?php
                    $model_sureg_fk = UnidadeAdministrativa::model()->sureg_por_item($model->item_fk);
                    $list_suregFk = CHtml::listData($model_sureg_fk, 'id', 'sigla');
                    echo CHtml::dropDownList('unidade_administrativa_fk', $model->unidade_administrativa_fk, $list_suregFk, array('style' => 'width:250px;'));
                    ?>                 
                </div>
            </div><!-- row -->
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'recomendacao_tipo_fk'); ?></div>
                <div class='field'><?php echo $form->dropDownList($model, 'recomendacao_tipo_fk', array(null => "Selecione") + GxHtml::listDataEx(RecomendacaoTipo::model()->findAllAttributes(null, true)), array('style' => 'width:250px;')); ?></div>
            </div><!-- row -->
            
            <? //Se a sugestão já estiver gravada (alteração), então não mostra a div abaixo.
                if ($model->id && RecomendacaoTipo::model()->find("id = ".$model->recomendacao_tipo_fk." AND nome_tipo not ilike '%recomendação%'")){
                    $display_detalhamento_recomendacao="none";
                } else{
                    $display_detalhamento_recomendacao="block";
                }
          ?>
        <div id='detalhamento_recomendacao' style='display:<?=$display_detalhamento_recomendacao;?>'>            
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'recomendacao_gravidade_fk'); ?> <span class="required">*</span></div>
                <div class='field'><?php echo $form->dropDownList($model, 'recomendacao_gravidade_fk', array(null => "Selecione") + GxHtml::listDataEx(RecomendacaoGravidade::model()->findAllAttributes(null, true)), array('style' => 'width:250px;')); ?></div>
            </div><!-- row -->
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'recomendacao_categoria_fk'); ?> <span class="required">*</span></div>
                <div class='field'><?php
                    echo $form->dropDownList($model, 'recomendacao_categoria_fk', array(null => "Selecione") + GxHtml::listDataEx(RecomendacaoCategoria::model()->findAllAttributes(null, true)), array(
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('RecomendacaoSubcategoria/CarregaSubcategoriaAjax'),
                            'update' => '#recomendacao_subcategoria_fk',),
                        'style' => 'width:250px;'));
                    ?></div>
            </div><!-- row -->
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'recomendacao_subcategoria_fk'); ?> <span class="required">*</span></div>
                <div class='field'><?php //echo $form->dropDownList($model, 'recomendacao_subcategoria_fk', GxHtml::listDataEx(RecomendacaoSubcategoria::model()->findAllAttributes(null, true)));        ?>
                    <?php
                    $model_subcategoria_fk = RecomendacaoSubcategoria::model()->findAll('recomendacao_categoria_fk=:recomendacao_categoria_fk ORDER BY id', array(':recomendacao_categoria_fk' => (int) $model->recomendacao_categoria_fk));
                    $list_subcategoriaFk = CHtml::listData($model_subcategoria_fk, 'id', 'nome_subcategoria');
                    echo CHtml::dropDownList('recomendacao_subcategoria_fk', $model->recomendacao_subcategoria_fk, $list_subcategoriaFk, array('style' => 'width:250px;'));
                    ?>                  
                </div>
            </div><!-- row -->
        </div>
            
            
            
            <div class='row'>
                <Table border="0" cellpadding="0 cellspacing=0">
                    <tr>
                        <td align="right"><br>
                            <?php
                            echo CHtml::ajaxSubmitButton(
                                    Yii::t('app', ' '), $this->createUrl('/Recomendacao/ImportacaoAjax'), array(
                                'dataType' => 'html',
                                'success' => "function (data) { 
	                                        $('#importar_recomendacao').html(data);
	                                        $('#dialog_importar_recomendacao').dialog('open');
	                                    }"
                                    ), array(
                                'id' => 'bt_importar_recomendacao',
                                'value' => 'Importar recomendação',
                                'class' => 'botaoRecomendacao',
                                    )
                            );
                            $this->beginWidget('application.common.extensions.jui.EDialog', array(
                                'name' => 'dialog_importar_recomendacao',
                                'htmlOptions' => array('title' => 'Importar Recomendação'),
                                'options' => array(
                                    'autoOpen' => false,
                                    'modal' => true,
                                    'title' => 'Importar Recomendação',
                                    'width' => 800,
                                    'height' => 600
                                ),
                                'buttons' => array(
                                    "Fechar" => 'function(){
	                                    	$(this).dialog("close");
										 }'
                                ),
                                'UseBundledStyleSheet' => false,
                            ));
                            echo '<div id="importar_recomendacao"></div>';
                            $this->endWidget('application.common.extensions.jui.EDialog');
                            ?>
                        </td>
                    </tr>
                    <tr><td>
                            <?php
                            echo $form->textArea($model, 'descricao_recomendacao', array('cols' => '80', 'rows' => '5', 'style' => 'margin-left:300px;', 'class' => 'tinymce_advanced'));
                            //echo $form->textArea($model, 'descricao_recomendacao', array('cols' => '80', 'rows' => '5', 'style' => 'margin-left:0px;width:600px;height:100px;')); 
                            ?>
                        </td></tr>
                    <?php
                    $perfil = strtolower(Yii::app()->user->role);
                    $perfil = str_replace("siaudi2", "siaudi", $perfil);
                    if ($perfil == "siaudi_gerente") :
                        ?>
                        <tr>
                            <td align="right">
                                <?= $form->checkBox($model, "bolRecomendacaoPadrao", array("value" => "1", "uncheckValue" => "0")) . "<label for='bolDeAcordo'> Salvar como Recomendação Padrão</label>"; ?>
                            </td>
                        </tr>
                    <? endif; ?>
                </table>
            </div><!-- row -->
        </fieldset>
        <p class="note">
            <?php echo Yii::t('app', 'Fields with'); ?>
            <span class="required">*</span>
            <?php echo Yii::t('app', 'are required'); ?>.
        </p>
        <div class="rowButtonsN1">
            <input type="hidden" name="inserir_recomendacao" id="inserir_recomendacao" value="0">
            <input type="button" name="text" value="Gravar e inserir outra recomendação" id="form_submit" onclick="envia_recomendacao();" class="botao" />
            <?php echo GxHtml::submitButton(Yii::t('app', 'Gravar e sair'), array('class' => 'botao')); ?>
            <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Recomendacao/index'), array('class' => 'imitacaoBotao')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div><!-- form -->
<?php } ?>
