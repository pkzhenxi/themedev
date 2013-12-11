<div class='header'>

	<div class='mobile-menu-btn visible-phone'>
		<i></i>
		<i></i>
		<i></i>
	</div>

	<?php echo CHtml::link('', array('cart/index'), array('class'=>'mobile-cart-btn visible-phone')) ?>

	<h3>
		<?php echo Yii::app()->theme->config->headerSurtitle; ?>
	</h3>
	<h1>
		<span><?= _xls_get_conf('STORE_NAME') ?></span>
	</h1>
	<h3>
		<?php echo Yii::app()->theme->config->headerSubtitle; ?>
	</h3>

</div>