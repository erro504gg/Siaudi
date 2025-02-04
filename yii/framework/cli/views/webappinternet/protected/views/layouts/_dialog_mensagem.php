<?php
Yii::app()->clientScript->scriptMap['jquery.js'] = false;
?>
<?php
$this->beginWidget('application.common.extensions.jui.EDialog', array(
    'name' => 'dialog_mensagem',
    'htmlOptions' => array('title' => 'Mensagem de alerta'),
    'options' => array(
        'modal' => true,
        'title' => 'Mensagem de alerta',
        'height' => 200,
        'width' => 400,
    ),
    'buttons' => array(
        "Fechar" => 'function(){$(this).dialog("close");}',
    ),
    'theme' => 'base',
        )
);
?>
<?php
echo $mensagem;
?>
<?php $this->endWidget('application.common.extensions.jui.EDialog'); ?>
