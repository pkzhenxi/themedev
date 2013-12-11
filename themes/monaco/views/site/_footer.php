<div id="footer" class='container-fluid'>

	<div >

		<p class='links'>
			<?php
					foreach (CustomPage::model()->bottomtabs()->findAll() as $arrTab) {
						echo CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link).' / ';						
					}
					echo CHtml::link(Yii::t('global','Sitemap'),$this->createUrl('site/map'));
				?>
		</p>

		<p class='social'>
			<?php if (Yii::app()->theme->config->facebookUrl != "") { ?>
			<a href='<?php echo Yii::app()->theme->config->facebookUrl; ?>'>
				<img src='<?php echo Yii::app()->theme->baseUrl . "/css/assets/icons/facebook.png"?>' />
			</a>
			<?php }
			if (Yii::app()->theme->config->twitterUrl != "") { ?>
			<a href='<?php echo Yii::app()->theme->config->twitterUrl; ?>'>
				<img src='<?php echo Yii::app()->theme->baseUrl . "/css/assets/icons/twitter.png"?>' />
			</a>
			<?php }
			if (Yii::app()->theme->config->pinterestUrl != "") { ?>
				<a href='<?php echo Yii::app()->theme->config->pinterestUrl; ?>'>
					<img src='<?php echo Yii::app()->theme->baseUrl . "/css/assets/icons/pinterest.png"?>' />
				</a>
			<?php } ?>
		</p>

		<p class="copyright">
			&copy; <?= Yii::t('global', 'Copyright') ?> <?= date("Y"); ?> <?= _xls_get_conf('STORE_NAME') ?>. <?= Yii::t('global', 'All Rights Reserved'); ?>.
		</p>

	</div>

</div>
