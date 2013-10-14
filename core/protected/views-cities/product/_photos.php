<div class="row-fluid">
	<?php $this->widget('ext.starplugins.cloudzoom',array(
		'images'=>$model->ProductPhotos,
		'instructions'=>'<legend>'.
			(!empty(Yii::app()->params['IMAGE_FANCYBOX']) ?
				Yii::t('global','Hover to zoom, click for original') :
				Yii::t('global','Hover over image to zoom')).'</legend>',
		'fancyboxLicense'=>Yii::app()->params['IMAGE_FANCYBOX'],
		'css_target'=>'targetarea span11',
		'css_thumbs'=>'thumbs span11',
		'zoomClass'=>'cloudzoom',
		'zoomSizeMode'=>'lens',
		'zoomPosition'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? '3' : 'inside',
		'zoomOffsetX'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? 10 : 0,
		'zoomFlyOut'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? 'true' : 'false',
	));
	?>
</div>