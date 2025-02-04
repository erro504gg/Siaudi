<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/dialogs.js'); ?>

<?php
$this->beginWidget('application.common.extensions.jui.EDialog', array(
    'name' => 'dialog_visualizar',
    'htmlOptions' => array('title' => 'Visualizar'),
    'options' => array(
        'autoOpen' => false,
        'modal' => true,
        'title' => 'Visualizar Registro',
        'height' => 400,
        'width' => 600,
    ),
    'buttons' => array(
        "Fechar" => 'function(){$(this).dialog("close");}',
    ),
    'theme' => 'base',
        )
);
?>
<span id="visualizar_reg"> </span>
<?php $this->endWidget('application.common.extensions.jui.EDialog'); ?>


<?php
$this->beginWidget('application.common.extensions.jui.EDialog', array(
    'name' => 'dialog_excluir',
    'htmlOptions' => array('title' => 'Excluir Registro'),
    'options' => array(
        'autoOpen' => false,
        'modal' => true,
        'title' => 'Confirma a exclusão do registro',
        'height' => 400,
        'width' => 600,
    ),
    'buttons' => array(
        "Excluir" => 'function(){
           $.post($("#ctrl_excluir").val(), { id:$("#id_excluir").val(), YII_CSRF_TOKEN : "'. Yii::app()->request->getCsrfToken() .'"  },
               function(data) {
                    $("#dialog_excluir").dialog("close");
                    $.fn.yiiGridView.update($("#id_grid_excluir").val());
                    $("#mensagens").html(data);
           });
        }',
        "Fechar" => 'function(){$(this).dialog("close");}',
    ),
    'theme' => 'base',
        )
);
?>

<?php echo GxHtml::hiddenField('id','', array('id'=>'id_excluir')) ?>
<?php echo GxHtml::hiddenField('ctrl_excluir','', array('id'=>'ctrl_excluir')) ?>
<?php echo GxHtml::hiddenField('ctrl_excluir','', array('id'=>'id_grid_excluir')) ?>
<span id="excluir_reg"></span>

<?php $this->endWidget('application.common.extensions.jui.EDialog'); ?>
