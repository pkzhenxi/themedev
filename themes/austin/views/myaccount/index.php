
<div id="orderdisplay">


		<div class="row">
	        <div class="col-sm-8">
	            <h3><?= Yii::t('global','Welcome'); ?> <?= $model->first_name ?></h3>
	        </div>
	        <div class="col-sm-4">
		        <?= CHtml::link(Yii::t('global','Edit Profile'),Yii::app()->createUrl('myaccount/edit'),array('class'=>'btn btn-link')); ?>
	        </div>
		</div>



	<div class="clearfix spaceafter"></div>

	<fieldset class="span12">
		<div class="row-fluid">
			<div class="span8"><h4><?= Yii::t('global','My Addresses') ?>:</h4></div>
			<div class="span4"><?= CHtml::link(Yii::t('global','Add new address'),Yii::app()->createUrl('myaccount/address'),array('class'=>'btn btn-link')); ?></div>
		</div>

		<div class="row-fluid">
			<?php foreach($model->customerAddresses as $objAddress): ?>
				<div class="span3 myaddress">
					<?= CHtml::link("<strong>".$objAddress->address_label."</strong><br>".$objAddress->address1."<br>".$objAddress->city." ".$objAddress->state." ".$objAddress->postal.
						($objAddress->id==$model->default_billing_id ? "<br><span class='default'>".Yii::t('global','Default Billing Address')."</span>" : "").
						($objAddress->id==$model->default_shipping_id ? "<br><span class='default'>".Yii::t('global','Default Shipping Address')."</span>" : ""),
						Yii::app()->createUrl('myaccount/address',array('id'=>$objAddress->id))); ?>
				</div>
			<?php endforeach; ?>
			<div class="clearfix spaceafter"></div>

		</div>
	</fieldset>
	<div class="clearfix spaceafter"></div>

	<fieldset class="span12">
	    <div class="row-fluid">
	        <div class="span8"><h4><?= Yii::t('global','My Orders') ?>:</h4></div>
	    </div>


		<?php if(count($model->carts(array('scopes'=>'complete'))) > 0): ?>
		  <?php foreach($model->carts(array('scopes'=>'complete')) as $objCart): ?>
	        <div class="row-fluid">
	            <div class="span2">
	                <?= CHtml::link($objCart->id_str,Yii::app()->createUrl('cart/receipt',array('getuid'=>$objCart->linkid)),array('class'=>'btn btn-link')); ?>
	            </div>
	            <div class="span3">
					<?= $objCart->DatetimeCreated; ?>
	            </div>
	            <div class="span3">
					<?= Yii::t('global',$objCart->status); ?>
	            </div>
	        </div>
		  <?php endforeach; ?>
		<?php else: ?>
			<?= Yii::t('global','You have not placed any orders with us yet'); ?>
		<?php endif; ?>

	</fieldset>


	<fieldset class="span12">
	    <div class="row-fluid">
	        <div class="span8"><h4><?= CHtml::link(Yii::t('global','My Wish Lists'),Yii::app()->createUrl('/wishlist')); ?></h4></div>
	        <div class="span4"><?= CHtml::link(Yii::t('global','Create a wish list.'),Yii::app()->createUrl('wishlist/create'),array('class'=>'btn btn-link')); ?></div>
	    </div>
	    <div class="row-fluid">
			<?php if(count($model->wishlists) > 0): ?>
				<?php foreach($model->wishlists as $objWishlist): ?>
		            <div class="span3">
			            <?= CHtml::link($objWishlist->registry_name,Yii::app()->createUrl('wishlist/view',array('code'=>$objWishlist->gift_code))); ?>
		            </div>
				<?php endforeach; ?>
			<?php else: ?>
				<?= Yii::t('global',"You have not created any wish list yet."); ?><br>

			<?php endif; ?>
	    </div>
	</fieldset>

	<?php if(_xls_get_conf('ENABLE_SRO')):   ?>
	<fieldset class="span12">
	    <div class="row-fluid">
	        <div class="span8"><h2><?= Yii::t('global','My Repairs') ?></h2></div>
	    </div>
	    <div class="row-fluid">
			<?php if(count($model->sros) > 0): ?>
			<?php foreach($model->sros as $sro): ?>
	            <div class="span3">
		            <?php echo CHtml::link($sro->ls_id,Yii::app()->createUrl('sro/view',array('code'=>$sro->GenerateLink()))); ?>
	            </div>
				<?php endforeach; ?>
			<?php else: ?>
			<?= Yii::t('global',"You have not placed any repair orders with us."); ?><br>
			<?php endif; ?>
	    </div>
	</fieldset>
	<?php endif;  ?>

</div>