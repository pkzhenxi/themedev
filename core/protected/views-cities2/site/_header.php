<div id="topbar" class="row">
	<div class="col-sm-9">
		<div id="headerimage">
			<?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE')), Yii::app()->baseUrl."/"); ?>
		</div>
	</div>
	<div class="col-sm-3">
		<div id="login">
			<?php if(Yii::app()->user->isGuest): ?>
				<?php echo CHtml::ajaxLink(Yii::t('global','Login'),array('site/login'),
					array('onClick'=>'js:jQuery($("#LoginForm")).dialog("open")'),
					array('id'=>'btnLogin')); ?>
				&nbsp;/&nbsp;
				<a href="<?= _xls_site_url('myaccount/edit'); ?>"><?php echo Yii::t('global', 'Register'); ?></a>
			<?php else: ?>
				<?php echo CHtml::link(CHtml::image(Yii::app()->user->profilephoto).Yii::app()->user->firstname, array('/myaccount')); ?>
				&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo CHtml::link(Yii::t('global', 'Logout'), array("site/logout")); ?>
				<?php endif; ?>
		</div>

		<?php if(_xls_get_conf('LANG_MENU',0)): ?>
			<div id="langmenu">
				<?php $this->widget('application.extensions.'._xls_get_conf('PROCESSOR_LANGMENU').'.'._xls_get_conf('PROCESSOR_LANGMENU')); ?>
				</div>
		<?php endif; ?>

		<div id="shoppingcart">
			<?= $this->renderPartial('/site/_topcart',null, true); ?>
		</div>
		<div class="shoppingnavigation">
			<?php if(_xls_get_conf('ENABLE_WISH_LIST',0)) echo CHtml::link(Yii::t('cart','Wish Lists'),array('/wishlist')) ?>
			<?php echo CHtml::link(Yii::t('cart','Checkout'),array('cart/checkout')) ?>

		</div>


	</div>
</div>