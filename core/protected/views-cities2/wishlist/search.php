<div id="wishlistdisplay" class="col-sm-12">
    <div class="row">
        <div class="col-sm-6">
            <h1><?= Yii::t('global','Wish List Search'); ?></h1>
        </div>
    </div>

    <div class="row">
	    <?= Yii::t('wishlist','Click on the wish list name to view.'); ?>
	</div>

    <div class="row rowborder">
        <div class="col-sm-3">
            <span class="cartlabel light"><?= Yii::t('global','Name'); ?></span>
        </div>

	    <div class="col-sm-2 heading">
            <span class="cartlabel light"><?= Yii::t('global','Contains'); ?></span>
        </div>

        <div class="col-sm-4 heading">
            <span class="cartlabel light"><?= Yii::t('global','Description'); ?></span>
        </div>


    </div>

	<?php foreach ($objWishlists as $objWishlist): ?>
	    <div class="row">
	        <div class="col-sm-3 alpha">
	            <span class="cartlabel">
		            <?php echo CHtml::link($objWishlist->registry_name,
			                Yii::app()->createUrl('wishlist/view',array('code'=>$objWishlist->gift_code)));
					?>
	        </div>

	        <div class="col-sm-2">
		        <?= Yii::t('global','{items} item|{items} items',array(count($objWishlist->wishlistItems),
		            '{items}'=>count($objWishlist->wishlistItems))); ?>
	        </div>

		    <div class="col-sm-4">
				<?= $objWishlist->registry_description ?>&nbsp;
	        </div>

	    </div>
	<?php endforeach; ?>

		<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'wishlistsearch',
		'enableClientValidation'=>true,
		'focus'=>array($model,'email'),
		));
		?>
        <div class="row">
            <div class="col-sm-9">
				<?php echo Yii::t('global','Search for a wish list by email address'); ?>
				<?php echo $form->textField($model,'email'); ?>
				<?php echo $form->error($model,'email'); ?>
            </div>
        </div>

        <div class="col-sm-9 submitblock" >
			<?php echo CHtml::submitButton(Yii::t('global','Search'), array('id'=>'btnSubmit'));  ?>
        </div>



		<?php $this->endWidget(); ?>



</div>