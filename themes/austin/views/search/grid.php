<div class="container">
<div id="gridheader">
    <div class="row">
		<?php if (_xls_get_conf('ENABLE_CATEGORY_IMAGE', 0) && isset($this->category) && $this->category->ImageExist): ?>
	    <div id="category_image">
	        <img src="<?= $this->category->CategoryImage; ?>"/>
	    </div>
		<?php endif; ?>

	    <div class="col-sm-6"><h1><?php echo $this->pageHeader; ?></h1></div>

	    <div id="subcategories" class="col-sm-6">
            <ul class="nav nav-pills">
                <?php
                    if(isset($this->subcategories) && (count($this->subcategories) > 0)):
                        foreach ($this->subcategories as $item)
                            echo '<li>'.CHtml::link(trim($item['label']), $item['link']);
                    endif;
                ?>
            </ul>
	    </div>

		<?php if(isset($this->custom_page_content)): ?>
		    <div id="custom_content">
				<?php echo $this->custom_page_content; ?>
		    </div>
		<?php endif; ?>

	</div>


<?php if (count($model) > 0):

//		$ct=-1;
		foreach($model as $objProduct): ?>

<!--			Our product cell is a nested div, containing the graphic and text label with clickable javascript-->
            <div class="row product_cell">
					<?php
                        echo CHtml::tag('div',array(
                            'class'=>'product_cell_graphic col-sm-4',
                            'onClick'=>'js:window.location.href="'.$objProduct->Link.'"'),
                            CHtml::link(CHtml::image($objProduct->ListingImage), $objProduct->Link)
                            );
                    ?>
<!--//					echo CHtml::tag('div',array(-->
<!--//					    'class'=>'product_cell_label col-sm-8',-->
<!--//					    'onClick'=>'js:window.location.href="'.$objProduct->Link.'"',-->
<!--//				        ),-->
                    <div class="product_cell_label col-sm-8">
                        <div class="row">
                            <?= CHtml::link(_xls_truncate($objProduct->Title , 50), $objProduct->Link); ?>
                        </div>
                        <div class="row">
                            <?= CHtml::tag('span',array('class'=>'product_cell_price_slash'),$objProduct->SlashedPrice); ?>
                        </div>
                        <div id="grid-sale-price" class="row">
                            <?= CHtml::tag('span',array('class'=>''),$objProduct->Price); ?>
                        </div>

                        <div id="grid-description" class="row">
                            <?php
                                preg_match('/^([^.!?]*[\.!?]+){0,'.Yii::app()->theme->config->NUM_TEASER_SENTENCES.'}/', strip_tags($objProduct->WebLongDescription), $teaser);
                                echo $teaser[0];
                            ?>
                        </div>
                        <div id="grid-add" class="row">
                            <?= CHtml::tag('div',array(
                                'class'=>'add-btn col-sm-offset-8 col-sm-4',
                                'onClick'=>CHtml::ajax(array(
                                        'url'=>array('/cart/AddToCart'),
                                        //If we are viewing a matrix product, Add To Cart needs to pass selected options, otherwise just our model id
                                        'data'=>($objProduct->IsMaster ?
                                                'js:{"'.'product_size'.'": $("#SelectSize option:selected").val(),
                                                    "'.'product_color'.'": $("#SelectColor option:selected").val(),
                                                    "'.'id'.'": '.$objProduct->id.',
                                                    "'.'qty'.'": $("#'.CHtml::activeId($objProduct,'intQty').'").val() }'
                                                : array('id'=>$objProduct->id,'qty'=>'1')),
                                        'type'=>'POST',
                                        'dataType'=>'json',
                                        'success' => 'js:function(data){
                                                if (data.action=="alert") {
                                                  alert(data.errormsg);
                                                } else if (data.action=="success") {
                                                    '.(_xls_get_conf('AFTER_ADD_CART') ?
                                                'window.location.href="'.$this->createUrl("/cart").'"' :
                                                '$("#shoppingcart").html(data.shoppingcart);').'
                                                }}'
                                    )),
                            ),CHtml::link('<span class="glyphicon glyphicon-plus"></span> '.'&nbsp&nbsp'.Yii::t('product', 'Add to Cart'), '#',array('class'=>'btn btn-default')));
                            ?>
                        </div>
                    </div>
            </div>
		<?php endforeach; ?>
</div>

<div class="clearfix"></div>

<div id="paginator" class="col-sm-12">
    <?php $this->widget('CLinkPager', array(
        'id'=>'pagination',
        'currentPage'=>$pages->getCurrentPage(),
        'itemCount'=>$item_count,
        'pageSize'=>_xls_get_conf('PRODUCTS_PER_PAGE'),
        'maxButtonCount'=>5,
        'firstPageLabel'=> '<< '.Yii::t('global','First'),
        'lastPageLabel'=> Yii::t('global','Last').' >>',
        'prevPageLabel'=> '< '.Yii::t('global','Previous'),
        'nextPageLabel'=> Yii::t('global','Next').' >',
        'header'=>'',
        'htmlOptions'=>array('class'=>'pagination'),
        )); ?>
</div>

<?php endif; ?>
</div>

