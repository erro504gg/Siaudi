<!-- mensagems-->
<div id="mensagens">
    <?php if (Yii::app()->user->hasFlash('successo')): ?>
        <div class="sucesso">
            <p><?php echo Yii::app()->user->getFlash('successo'); ?></p>
        </div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('erro')): ?>
        <div class="erro">
            <p><?php echo Yii::app()->user->getFlash('erro'); ?></p>
        </div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('orientacao')): ?>
        <div class="orientacao">
            <p><?php echo Yii::app()->user->getFlash('orientacao'); ?></p>
        </div>
    <?php endif; ?>

    <?php if (Yii::app()->user->hasFlash('aviso')): ?>
        <div class="alerta">
            <p><?php echo Yii::app()->user->getFlash('aviso'); ?></p>
        </div>
    <?php endif; ?>

    <?php
    Yii::app()->clientScript->registerScript(
            'escondeMsg',
            '$(".sucesso").animate({opacity: 1.0}, 3000).fadeOut("slow");
             $(".alerta").animate({opacity: 1.0}, 5000).fadeOut("slow");'
            , CClientScript::POS_READY
    );
    ?>
</div>
