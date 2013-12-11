<div class="row">
<div id="gridheader">

		<?php if (_xls_get_conf('ENABLE_CATEGORY_IMAGE', 0) && isset($this->category) && $this->category->ImageExist): ?>
	    <div id="category_image">
	        <img src="<?= $this->category->CategoryImage; ?>"/>
	    </div>
		<?php endif; ?>

	    <h1><?php echo $this->pageHeader; ?></h1>

	    <div class="subcategories">
			<?php  if(isset($this->subcategories) && (count($this->subcategories) > 0)): ?>

			<?php echo _sp("Subcategories"); ?>:
			<?php foreach ($this->subcategories as $item)
					echo CHtml::link(trim($item['label']), $item['link']); ?>
			<?php endif; ?>
	    </div>

		<?php if(isset($this->custom_page_content)): ?>
		    <div id="custom_content">
				<?php echo $this->custom_page_content; ?>
		    </div>
		<?php endif; ?>
</div>
</div>


<?php if (count($model) > 0): ?>

		<?php

		$ct=-1;

        echo '<div id="grid">';

		foreach($model as $objProduct):

        echo CHtml::tag('div',array(
                'class'=>'product_cell thumbnail col-xs-12 col-sm-'.(12/$this->gridProductsPerRow)),

            CHtml::tag('div',array(
                    'class'=>'product_cell_graphic',
                    'onClick'=>'js:window.location.href="'.$objProduct->Link.'"'),
                CHtml::link(CHtml::image($objProduct->ListingImage), $objProduct->Link)).

            CHtml::tag('div',array(
                    'class'=>'product_cell_label',
                    'onClick'=>'js:window.location.href="'.$objProduct->Link.'"'
                ),
                CHtml::link(_xls_truncate($objProduct->Title , 50), $objProduct->Link).
                CHtml::tag('span',array('class'=>'product_cell_price_slash'),$objProduct->SlashedPrice).' '.
                CHtml::tag('span',array('class'=>'product_cell_price'),$objProduct->Price)
            )
        );

		endforeach; ?>

        </div>
		<div id="paginator" class="col-xs-10 col-sm-12">
			<?php $this->widget('CLinkPager', array(
				'id'=>'pagination',
				'currentPage'=>$pages->getCurrentPage(),
				'itemCount'=>$item_count,
				'pageSize'=>_xls_get_conf('PRODUCTS_PER_PAGE'),
				'maxButtonCount'=>5,
				'firstPageLabel'=> '&laquo;&laquo;',
				'lastPageLabel'=> '&raquo;&raquo;',
				'prevPageLabel'=> '&laquo;',
				'nextPageLabel'=> '&raquo;',
				'header'=>'',
				'htmlOptions'=>array('class'=>'pagination'),
				)); ?>
        </div>


<?php endif; ?>
