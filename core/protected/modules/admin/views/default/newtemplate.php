<div class="span9">
	<div class="hero-unit">
		<h4 class="newalert">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Theme Update Available</h4>

		<div class="editinstructions">
			<strong>Your selected theme <?php echo strtoupper(Yii::app()->theme->name) ?> has an update available, version <?php echo $oXML->themedisplayversion ?>.</strong>
			<p>
				<?php
					echo "To update the theme to this latest version, click the Update button for the theme in the <strong>".
						CHtml::link('Theme Gallery',$this->createUrl("theme/gallery"))."</strong>.";
				?>

			</p>

			<strong>Release Notes:</strong></br>
			<p>
				<?php echo $oXML->releasenotes; ?>
			</p>
		</div>

	</div>

</div>
