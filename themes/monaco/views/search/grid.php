<div class='container-fluid'>
<?php if (count($model) > 0): ?>


		<?php
		$ct=-1;
		foreach($model as $objProduct):

			//Our product cell is a nested div, containing the graphic and text label with clickable javascript
			echo CHtml::tag('div',array(
		        'class'=>'product_cell'),

					CHtml::tag('div',array(
				    'class'=>'product_cell_graphic',
				    'onClick'=>'js:window.location.href="'.$objProduct->Link.'"'),
			        CHtml::link(CHtml::image($objProduct->ListingImage), $objProduct->Link)).

					CHtml::tag('div',array(
					    'class'=>'product_cell_label',
					    'onClick'=>'js:window.location.href="'.$objProduct->Link.'"'
				        ),
				        CHtml::link(_xls_truncate($objProduct->Title , 50), $objProduct->Link).
					        CHtml::tag('span',array('class'=>'product_cell_price_slash'),$objProduct->SlashedPrice).
					        CHtml::tag('span',array('class'=>'product_cell_price'),$objProduct->Price)
		            )
				);

		endforeach; ?>

		<div class="clearfix"></div>

		<div id="paginator" class="span12">
			<?php $this->widget('CLinkPager', array(
				'id'=>'pagination',
				'currentPage'=>$pages->getCurrentPage(),
				'itemCount'=>$item_count,
				'pageSize'=>_xls_get_conf('PRODUCTS_PER_PAGE'),
				'maxButtonCount'=>5,
				'firstPageLabel'=> Yii::t('global','First'),
				'lastPageLabel'=> Yii::t('global','Last'),
				'prevPageLabel'=> Yii::t('global','Previous'),
				'nextPageLabel'=> Yii::t('global','Next'),
				'header'=>'',
				'htmlOptions'=>array('class'=>'pagination'),
				)); ?>
        </div>


</div>
<?php endif;
