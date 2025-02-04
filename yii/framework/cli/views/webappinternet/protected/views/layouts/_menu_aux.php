<div id="wrapperMenuAuxiliar">
    <div id="acessoSistemas">
        <img id="imgE" src="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/img/c_e_sistemas.jpg"
             width="20" height="20" />
        <label>Outros Sistemas:</label>
        <?php echo CHtml::dropdownlist('sistemas', 'nome', CHtml::listData($this->sistemas, 'url', 'nome'), array('empty' => 'Selecione', 'onchange' => 'location=this.options[this.selectedIndex].value;')); ?>
        <img id="imgD" src="<?php echo $this->path_theme ?>/img/c_d_sistemas.jpg" width="19" height="20" />
    </div>
    <div id="sair">
        <label id="lblUsuarioLogado"><?php echo Yii::app()->user->login ?> </label><a href="<?php echo Yii::app()->request->baseUrl; ?>/site/logout">[Sair]</a>
    </div>
    <div id="menuAuxiliar">
        <ul>
            <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/site/contatoAjax">Contato</a></li>
        </ul>
    </div>
</div>
