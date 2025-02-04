<?php
$this->pageTitle = Yii::app()->name . ' - Erro';
$this->breadcrumbs = array(
    'Erro',
);
?>
<div class="telaErro" style="text-align: center;">
    <img alt="Alerta" src="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params["tema"];?>/img/warning.png" width="50"  height="50"/>
    <h2>Error <?php echo $code; ?></h2>
    <?php echo CHtml::encode($message); ?>
</div>
