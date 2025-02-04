<div class="formulario" Style="width: 70%;">
    <?php $ajax = ($this->enable_ajax_validation) ? 'true' : 'false'; ?>
    <?php echo '<?php '; ?>
    $form = $this->beginWidget('GxActiveForm', array(
    'id' => '<?php echo $this->class2id($this->modelClass); ?>-form',
    'enableAjaxValidation' => <?php echo $ajax; ?>,
    ));
    <?php echo '?>'; ?>
    <?php echo "\n<?php echo \$form->errorSummary(\$model); ?>"; ?>

    <fieldset class="visivel">
        <legend class="legendaDiscreta"> <?php echo '<?php'; ?> echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo '<?php echo $model->label(); ?>'; ?></legend>


        <?php foreach ($this->tableSchema->columns as $column): ?>
            <?php if (!$column->autoIncrement): ?>
                <?php echo ($column->name != 'id' ? "<div class='row'>\n<div class='label'><?php echo " . $this->generateActiveLabel($this->modelClass, $column) . "; ?></div>\n" : ''); ?>
                <?php echo ($column->name != 'id' ? "<div class='field'><?php " . $this->generateActiveField($this->modelClass, $column) . "; ?></div>\n </div><!-- row -->" : ''); ?>

            <?php endif; ?>
        <?php endforeach; ?>

        <?php foreach ($this->getRelations($this->modelClass) as $relation): ?>
            <?php if ($relation[1] == GxActiveRecord::HAS_MANY || $relation[1] == GxActiveRecord::MANY_MANY): ?>
                <div class="label"><label><?php echo '<?php'; ?> echo GxHtml::encode($model->getRelationLabel('<?php echo $relation[0]; ?>')); ?></label></div>
                <div class="field"><?php echo '<?php ' . $this->generateActiveRelationField($this->modelClass, $relation) . "; ?>"; ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </fieldset>
    <p class="note">
        <?php echo "<?php echo Yii::t('app', 'Fields with'); ?>\n<span class=\"required\">*</span>\n<?php echo Yii::t('app', 'are required'); ?>"; ?>.
    </p>
    <div class="rowButtonsN1">
        <?php
        echo "<?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'),array('class' => 'botao')); ?>\n";
        echo "<?php echo CHtml::link(Yii::t('app', 'Cancel'),\$this->createUrl('{$this->modelClass}/index?' . \$_SERVER['QUERY_STRING']), array('class' => 'imitacaoBotao')); ?>\n";
        ?>
    </div>
<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
</div><!-- form -->
