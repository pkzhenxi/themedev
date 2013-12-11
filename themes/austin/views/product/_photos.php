<div class="row">
	<?php $this->widget('ext.starplugins.cloudzoom',array(
		'images'=>$model->ProductPhotos,
		'instructions'=>'<legend>'.Yii::t('global','').'</legend>',
		'css_target'=>'detail-image',
		'css_thumbs'=>'thumbs',
		'zoomClass'=>'cloudzoom',
		'zoomSizeMode'=>'lens',
		'zoomPosition'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? '3' : 'inside',
		'zoomOffsetX'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? 10 : 0,
		'zoomFlyOut'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? 'true' : 'false',
	));
	?>
</div>