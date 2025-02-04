<?php echo '<?php '; ?> echo $this->renderPartial('/layouts/_dialogo_view'); ?>

<?php echo '<?php '; ?>
$this->renderPartial('_search', array(
    'model' => $model,
));
?>
<?php echo "<?php if ( !is_null(\$dados) ): ?>" ?>
<div class="tabelaListagemItensWrapper">
    <div class="tabelaListagemItens">
    <?php echo '<?php'; ?> $this->widget('zii.widgets.grid.CGridView', array(
            'id' => '<?php echo $this->class2id($this->modelClass); ?>-grid',
            'dataProvider' => $dados,
            'summaryCssClass' => 'sumario',
            'columns' => array(
    <?php
    $count = 0;
    foreach ($this->tableSchema->columns as $column) {
            if (++$count == 7)
                    echo "\t\t/*\n";
            echo "\t\t" . $this->generateGridViewColumn($this->modelClass, $column).",\n";
    }
    if ($count >= 7)
            echo "\t\t*/\n";
    ?>
            array(
                'class' => 'application.components.MyButtonColumn',
                'deleteConfirmation' => "js:'Deseja remover o <?php echo ($this->modelClass); ?> '+$(this).parent().parent().children(':first-child').text()+'?'",
                'updateButtonUrl' => 'Yii::app()->createUrl("<?php echo ($this->modelClass); ?>/admin/".$data->id."?".$_SERVER["QUERY_STRING"])',
             )
            )
          )
        );
    ?>
    </div>
<?php echo "<?php endif;?>\n" ?>
</div>
