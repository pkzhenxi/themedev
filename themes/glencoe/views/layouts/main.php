<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
	<!-- Head section -->
	<?php echo $this->renderPartial("/site/_head",null,true,false); ?>

	<body>
		<?php echo $this->sharingHeader; ?>

        <?php echo $this->renderPartial("/site/_header",null,true,false); ?>

        <hr>

        <div class="container">
            <div class="row row-offcanvas-left row-offcanvas">

                <?php echo $this->renderPartial("/site/_navigation",null,true,false); ?>

                <!-- Product Grid -->
                <?php echo $content; ?>

            </div>
            </div>
        </div>

        <!-- footer -->
        <?php echo $this->renderPartial("/site/_footer",null,true,false); ?>



		<?php echo $this->sharingFooter; ?>

<!--		--><?php //echo $this->loginDialog; ?>

	</body>
</html>