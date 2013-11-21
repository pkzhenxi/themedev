<div class="span9">
	<div class="hero-unit nobottom">

		<h3><?php echo Yii::t('admin','Manage Header Images'); ?></h3>
		<div class="editinstructions">
			<?php echo Yii::t('admin','Upload header images and select which one you wish to use.'); ?>
			<?php  if(Yii::app()->user->fullname=="LightSpeed")
				echo "<p><strong>".Yii::t('admin','To upload a new image, drag and drop a file on top of the Add button. NOTE: You can also log into Admin Panel externally at {url} to use the Add button normally.',array('{url}'=>$this->createAbsoluteUrl("/admin")))."</strong></p>";
			else
				echo Yii::t('admin','To upload a new image to add to your collection, click Add and select your file.');

			echo " ".Yii::t('admin',"Provide an optional name and description after uploading.");

			if(_xls_get_conf('IMAGE_FORMAT')=="jpg")
				echo " ".Yii::t('admin','If your header image has transparency, it may show with an odd background here but will be correct on your home page.');
			?>
		</div>
		<div class="clearfix spaceafter"></div>

		<div class="row-fluid">
		<?php $this->widget('ext.galleryManager.GalleryManager', array(
			'gallery' => $gallery,
			'controllerRoute' => 'admin/gallery', //route to gallery controller
			));
		?>
		</div>
		<div class="clearfix spaceafter"></div>


</div>
</div>