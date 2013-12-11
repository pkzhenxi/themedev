<?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE')), Yii::app()->baseUrl."/", array('class'=>'logo') ); ?>
<?php $this->widget('application.extensions.wsmenu.wsmenu', array(
		'categories'=> Category::GetTree(),
		'menuheader'=> Yii::t('global','Products'),
		'showarrow'=>false,
	)); //products dropdown menu ?>
	<i></i>
<?php if (count(CustomPage::model()->toptabs()->findAll()))
	$this->widget('zii.widgets.CMenu', array(
	'id'=>'menutabs',
	'itemCssClass'=>'',
	'items'=>CustomPage::model()->toptabs()->findAll()
)); ?>

<span class='divider'></span>

	<div class="login">
		<?php if(Yii::app()->user->isGuest): ?>
			<?php echo CHtml::ajaxLink(Yii::t('global','	Login'),array('site/login'),
				array('onClick'=>'js:jQuery($("#LoginForm")).dialog("open")'),
				array('id'=>'btnLogin')); ?>
			&nbsp;/&nbsp;
			<a href="<?= _xls_site_url('myaccount/edit'); ?>"><?php echo Yii::t('global', 'Register'); ?></a>
		<?php else: ?>
			<?php echo CHtml::link(CHtml::image(Yii::app()->user->profilephoto).Yii::app()->user->firstname, array('/myaccount')); ?>
			&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo CHtml::link(Yii::t('global', 'Logout'), array("site/logout")); ?>
			<?php endif; ?>
	</div>

	<div class="checkoutlink" class="wishlists shoppingcartholder">
		<?php echo CHtml::link(Yii::t('Shopping Cart','n==1#Shopping Cart ({n})|n>1#Shopping Cart ({n})',Yii::app()->shoppingcart->totalItemCount), array('cart/index')) ?>
		<?= $this->renderPartial('/site/_sidecart',null, true); ?>
	</div>

	<?php if(_xls_get_conf('LANG_MENU',0)): ?>
		<div id="langmenu">
			<?php $this->widget('application.extensions.'._xls_get_conf('PROCESSOR_LANGMENU').'.'._xls_get_conf('PROCESSOR_LANGMENU')); ?>
		</div>
	<?php endif; ?>
