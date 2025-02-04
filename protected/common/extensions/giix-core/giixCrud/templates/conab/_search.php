<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<div class="formulario" Style="width: 70%;">

    <?php echo "<?php \$form = \$this->beginWidget('GxActiveForm', array(
	'action' => Yii::app()->createUrl(\$this->route),
	'method' => 'get',
)); ?>\n"; ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Dados da Consulta</legend>
        <?php foreach ($this->tableSchema->columns as $column): ?>
            <?php
            $field = $this->generateInputField($this->modelClass, $column);
            if (strpos($field, 'password') !== false)
                continue;
            ?>
            <?php echo ($column->name != 'id' ? "\n<div class='row'>\n<div class='label'><?php echo \$form->label(\$model, '{$column->name}'); ?></div>\n" : ''); ?>
            <?php echo ($column->name != 'id' ? "<div class='field'>\n<?php " . $this->generateSearchField($this->modelClass, $column) . "; ?>\n</div>\n</div>" : ''); ?>
        <?php endforeach; ?>


    </fieldset>

    <p class="note">
        <b><?php echo "<?php echo Yii::t('app', 'Fields with'); ?> <span class=\"required\">*</span> <?php echo Yii::t('app', 'are required'); ?>"; ?>.</b>
    </p>
        <div class="rowButtonsN1">

        <?php echo "\n<?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>\n"; ?>

        <?php echo "<input type='button' onclick='window.location=siprod/{$this->modelClass}' class='botao' value='Limpar'>"; ?>

            </div>

    <?php echo "<?php \$this->endWidget(); ?>\n"; ?>
</div><!-- search-form -->
