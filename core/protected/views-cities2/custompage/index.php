<div id="custom_content" class="col-sm-12">
	<?php echo $model->page; ?>
</div>

<div class="col-sm-9 clearfix">
	<?php
		$this->widget('ext.JCarousel.JCarousel', array(
			'dataProvider' => $model->taggedProducts(),
			'thumbUrl' => '$data->SliderImage',
			'imageUrl' => '$data->Link',
			'emptyText'=>'',
			'titleText' => '$data->Title',
			'captionText' => '$data->Title."<br>"._xls_currency($data->Price)',
			'target' => 'do-not-delete-this',
			//'wrap' => 'circular',
			'visible' => true,
			'skin' => 'slider',
			'clickCallback'=>'window.location.href=itemSrc;'
		)); ?>
</div>
