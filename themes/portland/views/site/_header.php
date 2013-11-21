<div id="topbar" class="row-fluid" >
	<div id="searchentry" class="span4" >
		<?php echo $this->renderPartial("/site/_search",array(),true); ?>
	</div>
	<div class="span4 logo" >
		<?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE')), Yii::app()->baseUrl."/"); ?>
	</div>
	<div class="span4 loginDiv">
		<div id="login">
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
		<?php if(_xls_get_conf('ENABLE_WISH_LIST',0)): ?>
			<div class="wishlists">
				<?php 
					if(Yii::app()->user->isGuest) {
						echo CHtml::link(Yii::t('global', 'Wish Lists'), array("wishlist/search"));					
					}
					else {
						echo CHtml::link(Yii::t('global', 'Wish Lists'), array("/wishlist"));
					}
				?>
			</div>
		<?php endif; ?>
		<div id="checkoutlink" class="wishlists shoppingcartholder">
			<?php echo CHtml::link(Yii::t('cart','n==1#Cart ({n})|n>1#Cart ({n})',Yii::app()->shoppingcart->totalItemCount), array('cart/index')) ?>
			<?= $this->renderPartial('/site/_sidecart',null, true); ?>
		</div>
		<?php if(_xls_get_conf('LANG_MENU',0)): ?>
			<div id="langmenu">
				<?php $this->widget('application.extensions.'._xls_get_conf('PROCESSOR_LANGMENU').'.'._xls_get_conf('PROCESSOR_LANGMENU')); ?>
			</div>
		<?php endif; ?>
	</div>
</div>