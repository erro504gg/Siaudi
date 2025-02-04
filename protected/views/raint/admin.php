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
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();

$form = $this->beginWidget('GxActiveForm', array(
    'id' => 'raint-form',
    'enableAjaxValidation' => false,
'method' => 'POST',
        ));
?>    
<div class="formulario" Style="width: 70%;">
    <?php echo $form->errorSummary($model); ?>

    <div style="height:10px"></div>

    <div class="formulario" style="width: auto">		
        <fieldSet class="visivel">
    <div class='row'>
        <div class='label'><?php echo $form->labelEx($model, 'valor_exercicio'); ?></div>
        <div class='field'>
            <?php
            if (!$_GET["exercicio"]) {
                echo "<form action='./' method='post' id='RaintFormValidar' name='RaintFormValidar' onsubmit='relatorio_item_validar()'>";
                echo "<input type=hidden name='RaintFormValidar_dados' value=1>";
                $form->widget('CMaskedTextField', array(
                    'model' => $model,
                    'attribute' => 'valor_exercicio',
                    'mask' => '9999',
                    'placeholder' => '_',
                    'htmlOptions' => array('maxlength' => 4,
                        'value' => $_GET['exercicio'])
                ));
                echo "&nbsp;";
                if ($_REQUEST["consultar"] == 1) {
                    echo "<input type=hidden name='consultar' value=1>";
                }
                echo GxHtml::submitButton(Yii::t('app', 'Validar'), array('class' => 'botao'));
                echo "</form>";
            } else {
                echo $_GET["exercicio"] . "&nbsp; &nbsp;  &nbsp;  &nbsp; ";
            }
            ?>

        </div>
    </div><!-- row -->
    <center>
        <?php
        // verifica se usuário tem permissão de edição para o Raint
        $acesso_raint = Raint::model()->acesso_raint();

        if ($_GET["consultar"] && $acesso_raint) {
            echo "<center>";
            echo CHtml::link("Editar RAINT", $this->createUrl('Raint/admin?exercicio=' . $_GET['exercicio']), array('class' => 'imitacaoBotao'));
            echo "</center><br>";
        }
        ?>
    </center>
        </fieldset>
    </div>

    <?php
   $limite_inferior =Yii::app()->params['limite_inferior_exercicio'];              
    if ($_GET["exercicio"]>=$limite_inferior ) {
        $cs->registerCssFile($baseUrl . '/js/jquery_easyui/themes/default/easyui.css');
        $cs->registerCssFile($baseUrl . '/js/jquery_easyui/themes/icon.css');
        $cs->registerCssFile($baseUrl . '/css/default-calendar.css');
        $cs->registerScriptFile($baseUrl . '/js/jquery_easyui/jquery.easyui.min.js');
        include_once(Yii::app()->basePath . '/../js/raint/item-template-javascript.js');
        include_once(Yii::app()->basePath . '/../js/raint/tree-js.jsp');
        include_once(Yii::app()->basePath . '/../js/raint/tiny-mce-js.jsp');
        
        // verifica se usuário tem permissão de edição para o Raint
        $acesso_raint = Raint::model()->acesso_raint();
        $raint = Raint::model()->FindAllByAttributes(array('valor_exercicio' => $_REQUEST["exercicio"]));

        if (!$acesso_raint && sizeof($raint) == 0) {
            echo "<br><br>O RAINT não foi cadastrado neste exercício. ";
            echo "<div class='rowButtonsN1'>";
            echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Raint/index'), array('class' => 'imitacaoBotao'));
            echo "</div>";
        } else {        
        if ($_GET["consultar"]) {
            echo "<div style='position:absolute; left:360px; height:550px; width:780px; font-size:50px; z-index:100; '>&nbsp;</div>";
            $topo = ($acesso_raint)? "821":"789";
            echo "<div style='position:absolute; top:".$topo."px; left:1137px; height:19px; width:20px; font-size:50px; z-index:101; background-color:rgb(241, 241, 241);'>&nbsp;</div>";
        }                            
        ?>

        <div class="formulario" style="width: auto">			

            <fieldSet class="visivel">
                <legend class="legendaDiscreta"> Conteúdo do RAINT </legend>

                <table style="width:100%" border="0">
                    <tr>

                        <td style="width:300px;" valign="top" rowspan="3">


                            <div id="p" class="easyui-panel" title="" style="width:300px;height:550px;padding:10px;"  
                                 data-options="closable:false, collapsible:false,minimizable:false,maximizable:false" >  


                                <div style="margin:10px;">

                                    <a href="javascript:void(0)" onclick="$('#treeViewItens').tree('expandAll');" styleClass="buttonLink" title="Expandir todos">
                                        <img src="<? echo $baseUrl; ?>/images/expand_all.gif" style="display:inline;border:0;" />
                                    </a>

                                    <a href="javascript:void(0)" onclick="$('#treeViewItens').tree('collapseAll');" styleClass="buttonLink" title="Recolher todos">
                                        <img src="<? echo $baseUrl; ?>/images/collapse_all.gif" style="display:inline;border:0;" />
                                    </a>
                                    <?php if (!$_GET["consultar"] == 1) { ?>
                                        <a href="javascript:void(0)" onclick="treeAppendItem($('#treeViewItens'))" styleClass="buttonLink" title="Adicionar Item">
                                            <img src="<? echo $baseUrl; ?>/images/adicionar_item.gif" style="display:inline;border:0;" />
                                        </a>

                                        <a href="javascript:void(0)" onclick="treeAppendItemFilho($('#treeViewItens'))" styleClass="buttonLink" title="Adicionar Sub-Item">
                                            <img src="<? echo $baseUrl; ?>/images/adicionar_subitem.gif" style="display:inline;border:0;" />
                                        </a>

                                        <a href="javascript:void(0)" onclick="treeRemoveItem($('#treeViewItens'))" styleClass="buttonLink" title="Remover Item">
                                            <img src="<? echo $baseUrl; ?>/images/excluir.gif" style="display:inline;border:0;" />
                                        </a>					
                                    <?php } ?>
                                </div>

                                <hr>

                                <ul id="treeViewItens" animate="true" lines="false" dnd="true" class="treeview_template"> </ul>


                                <div id="menu_contexto" class="easyui-menu" style="width:120px;">
                                    <div onclick="treeAppend($('#treeViewItens'))" iconCls="icon-add">Adicionar item</div>
                                    <div onclick="treeRemove($('#treeViewItens'))" iconCls="icon-remove">Remover item</div>
                                    <div class="menu-sep"></div>
                                    <div onclick="expand()">Expandir</div>
                                    <div onclick="collapse()">Recolher</div>
                                </div>

                            </div>

                        </td>

                    </tr>

                    <tr>							
                        <td valign="top" style="height:100%">
                            <div id="divTitulo" style="display:inline">
                                <span style="font-size:11px"> <b> Titulo: </b> </span>
                                <textarea name="titulo" id="titulo" class="tinymce_simple" style="width:100%;"  <?php
                                if ($_GET["consultar"]) {
                                    echo "disabled=true";
                                }
                                ?>> </textarea>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div id="divTexto" style="display:inline">
                                <span style="font-size:11px"> <b> Texto: </b> </span>
                                <textarea name="texto" id="texto" class="tinymce_advanced"  style="width:500px; height:800px;"    <?php
                                if ($_GET["consultar"]) {
                                    echo "readonly";
                                }
                                ?>> </textarea>
                            </div>
                        </td>

                    </tr>

                </table>
                <div class="rowButtonsN1">
                    <?php if (!$_GET["consultar"]) { ?>
                            <input type="button" name="text"
                                   value="Adicionar atributo"                                               
                                   id="form_submit" onclick="showAtributos();" class="botao" />
                            
                        <input type="hidden" name="raint_sair" id="raint_sair" value="0">
                        <input type="hidden" name="exercicio" value="<?php echo $_GET["exercicio"]; ?>">
                        <input type="hidden" name="raint" id="raint" value="">
                        <input type="button" name="text"						
                               value="Gravar"
                               id="form_submit" onclick="envia_raint_json(0);" class="botao" />

                        <input type="button" name="text"						
                               value="Gravar e sair"
                               id="form_submit" onclick="envia_raint_json(1);" class="botao" />                                        


                        <input type="button" name="text"
                               value="Gravar e Gerar PDF"
                               id="form_submit" onclick="envia_raint_json(2);" class="botao" />   
                    <? }
                        if ($_GET["consultar"] == 1) {
                            echo CHtml::link(Yii::t('app', 'Gerar PDF'), $this->createUrl('Raint/ExportarPDFAjax?exercicio=' . $_GET['exercicio']), array('class' => 'imitacaoBotao')) . "&nbsp; ";
                        }                    
                    ?>

                    <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Raint/index'), array('class' => 'imitacaoBotao')); ?>   
                </div>
            </fieldSet>
        </div>

        <?
        }
        include_once(Yii::app()->basePath . '/../js/raint/item-template-atributos.jspf');
        include_once(Yii::app()->basePath . "/../js/raint/item-template-anexos.jspf");
        ?>

    <?php } ?>    
    <?php $this->endWidget(); ?>
</div><!-- form -->
