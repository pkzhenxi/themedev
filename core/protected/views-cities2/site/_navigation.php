<div id="menubar" class="row">

	<div class="col-sm-9">
		<?php if (count(CustomPage::model()->toptabs()->findAll()))
			$this->widget('zii.widgets.CMenu', array(
			'id'=>'menutabs',
			'itemCssClass'=>'menutab menuheight menuunderline col-sm-'.round(12/count(CustomPage::model()->toptabs()->findAll())),
			'items'=>CustomPage::model()->toptabs()->findAll()
		)); ?>
	</div>

	<div id="searchentry" class="col-sm-3">
		<?php echo $this->renderPartial("/site/_search",array(),true); ?>
	</div>

</div><!-- menubar -->

<div class="clearfix"></div>