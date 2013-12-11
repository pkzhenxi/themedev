<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
	<!-- <head> section -->
	<?php echo $this->renderPartial("/site/_head",null,true,false); ?>

	<body>
		<?php echo $this->sharingHeader; ?>
	
			<!-- template header -->
			<?php echo $this->renderPartial("/site/_header",null,true,false); ?>
	<div id="item-list" class="container text-center">

			<!-- Require the navigation -->
			<?php echo $this->renderPartial("/site/_navigation",null,true,false); ?>

			<!-- content (viewport) -->
			<?php echo $content; ?>
	</div>
			<!-- footer -->
			<?php echo $this->renderPartial("/site/_footer",null,true,false); ?>

	

		<?php echo $this->sharingFooter; ?>

		<?php echo $this->loginDialog; ?>

	</body>
</html>