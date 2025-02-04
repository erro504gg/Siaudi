<h1>Bem-vindo ao Gii Gerador de Código !</h1>

<p>
	Você pode usar os geradores a seguir para construir a sua aplicação Yii:
</p>
<ul>
	<?php foreach($this->module->controllerMap as $name=>$config): ?>
	<li><?php echo CHtml::link(ucwords(CHtml::encode($name).' generator'),array('/gii/'.$name));?></li>
	<?php endforeach; ?>
</ul>

