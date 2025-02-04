<div class="formulario" Style="width: 70%;">
    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>
    <?php
    if ($erros != ''):
        echo '<script>alert("' . $erros . '");</script>';
    endif;
    ?>
    <table width="600">
        <tr>
            <td><label for="campo1">Nome</label></td>
            <td><input maxlength="255" size="60" name="campo1" id="campo1" type="text" /></td>
        </tr>
        <tr>
            <td width="150" height="5"></td>
            <td></td>
        </tr>
        <tr>
            <td><label for="campo2">Tipo</label></td>
            <td>
                <select name="campo2" id="campo2">
                    <option value=""> Selecione ..</option>
                    <option value="1">Valor1</option>
                    <option value="2">Valor2</option>
                </select>
            </td>
        </tr>
    </table>
    <!--
    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>
    -->
    <div class="rowButtonsN1">
        <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>
        <input type='button' onclick="window.location = '<?php echo $this->createUrl('site/index') ?>'" class='botao' value='Limpar'>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->
