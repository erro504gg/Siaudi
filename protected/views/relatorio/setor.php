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
<style type="text/css">
.fixed-top {
    opacity: 1;
    position: fixed;
    top: 5;
    right:70px;
}
</style>
<script type="text/javascript">
    function AdicionaElemento(e) {
        var s = document.getElementById('unidade_administrativa_fk_popup');
        var o = document.createElement('option');
        if (e.checked) {
            o.value = e.id;
            o.textContent = e.nextSibling.innerHTML;
            o.selected = "selected";
            s.appendChild(o);
        } else {
            removeOption(e.id);
        }
        $("#unidade_administrativa_fk_popup").multiselect('refresh');
    }

    function removeOption(value)
    {
        var s = document.getElementById('unidade_administrativa_fk_popup');
        var i;
        for (i = s.length - 1; i >= 0; i--) {
            if (s.options[i].value == value) {
                s.remove(i);
            }
        }
    }

    function procurar(){
    	window.find(this.textoPesquisa.value,false,true,true,true,false,false); 
    	return false;
    }    

    $(document).ready(function() {
    
        var value = window.parent.parent.document.getElementById('setor_fk').innerHTML;
        $("#unidade_administrativa_fk_popup").html(value);
        $("#unidade_administrativa_fk_popup").multiselect('refresh');
        $("#unidade_administrativa_fk_popup").multiselect("checkAll");
        
        var options = value;
        options.replace(/\n/gi,"#n#"); //expressão para retirar quebras de linha
        options = options.match(/(value)=("[^"]*")/gi); //expressão para obter somente o value dos options
        
        for (var index in options){
            var id = options[index].replace(/[^\d]+/g, ""); //remove tudo o que não é número
            var li = document.getElementById("campo_" + id);
            li.childNodes[0].checked = true;
        }

        $("#unidade_administrativa_fk_popup").multiselect({
            click: function(e) {
                if (!e.target.checked) {
                    var value = e.target.value;
                    removeOption(value);
                    $("#unidade_administrativa_fk_popup").multiselect('refresh');
                    $('input[id=' + value + ']').attr('checked', false);
                }

            }
        });
    });

    function fechar() {
        try {
//            alert(window.parent.parent.carregaAreas('teste'));
            var unidade_administrativa_fk_popup = $('#unidade_administrativa_fk_popup').html();
            window.parent.parent.carregaSetores(unidade_administrativa_fk_popup);
            window.parent.close();
        }
        catch (err) {
            alert(err);
        }
    }
</script>
<div id="popupBoxFundo">&nbsp;<iframe src="javascript:false;"></iframe></div>
<div id="popupBox">
    <div>
        <span class="indicadorCarregando" style="padding-bottom: 15px;">&nbsp;</span>
        Aguarde, o sistema está processando os dados.
    </div>
</div>
<div class="formulario" Style="width: 70%;">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloGeral.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloEspecifico.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/estilo_yii.css" />
    <?php
    $cs = Yii::app()->getClientScript();
    $cs->registerCoreScript('treeview');
    $baseUrl = $cs->getCoreScriptUrl();
    $cs->registerCSSFile(Yii::app()->request->baseUrl . '/themes/' . Yii::app()->params['tema'] . '/css/estiloGeral.css');
    $cs->registerCSSFile(Yii::app()->request->baseUrl . '/themes/' . Yii::app()->params['tema'] . '/css/estiloEspecifico.css');
    $cs->registerCSSFile(Yii::app()->request->baseUrl . '/themes/css/estilo_yii.css');
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'selecionar-setor-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
    ));
    ?>
    <fieldset class="visivel" style="width: 100; position: relative;">
        <legend class="legendaDiscreta">Menu</legend>
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'setor'); ?></div>
            <div class='field'>
                <?php
                $setor = Array();
                $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                    'model' => $model,
                    'dropDownAttribute' => 'unidade_administrativa_fk',
                    'data' => $setor,
                    'dropDownHtmlOptions' => array(
                        'id' => 'unidade_administrativa_fk_popup',
                        'multiple' => true,
                    ),
                    'options' => array(
                        'selectedList' => 5,
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

                // marca no select multiplo as opções previamente salvas

                if (is_array($setor)) {
                    echo "\n <script type='text/javascript'> { \n";
                    foreach ($setor as $vetor) {
                        echo "$(\"#unidade_administrativa_fk_popup option[value='" . $vetor->id . "']\").attr('selected', 'selected'); \n";
                    }
                    echo "}</script>";
                }
                ?> 
            </div>
        </div><!-- row -->
    </fieldset>
    <fieldset class="visivel fixed-top" style="position: fixed; z-index: 2000;">
        <legend class="legendaDiscreta">Pesquisar</legend>
        <input type="text" name="textoPesquisa" id="textoPesquisa" />
        <input type="button" name="text" value="Procurar" onclick="procurar();" class="botao" />
    </fieldset>
    <div class="clear"></div> 
    <div>
        <strong>Unidade Administrativa (Lotação)</strong>
        <?php
        $form->widget('CTreeView', array(
            'data' => $dataTree,
//            'url' => array('Relatorio/AjaxFillTree'),
            'animated' => 'fast', //quick animation
//             'collapsed' => 'false', //remember must giving quote for boolean value in here
            'htmlOptions' => array(
                'class' => 'treeview treeview-gray', //there are some classes that ready to use
            ),
        ));
        ?>
        <?php
        $this->endWidget();
        ?>
    </div>
</div>
