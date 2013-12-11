<?php $this->layout='//layouts/column1'; ?>
<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'product',
    'htmlOptions'=>array('role'=>'form','class'=>'form-horizontal',),
));

//Note we used form-named DIVs with the Yii CHtml::tag() command so our javascript can update fields when choosing matrix items
?>
	<div id="product_details" class="row">
        <div id="photo-box" class="col-sm-4">
                <?= $this->renderPartial('/product/_photos', array('model'=>$model), true); ?>
        </div>

        <div id="info-box" class="col-sm-8">

            <div class="productheader">
                <h3 class="title"><?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'title')),$model->Title); ?></h3>
                <div class="col-sm-3 offset1 brand">
                    <?php if(isset($model->family)): ?>
                        <h4>By: <?= CHtml::link($model->family->family,$model->family->Link) ?></h4>
                    <?php endif; ?>
                </div>
                <div class="col-sm-4 code disabled ">
                    <?php if (_xls_get_conf('SHOW_TEMPLATE_CODE', true)): ?>
<!--                            <h4>--><?//= CHtml::tag('div',array('id'=>CHtml::activeId($model,'code')),$model->code); ?><!--</h4 >-->
                        <h5><em><?= CHtml::tag('p', array('id'=>''),$model->code) ?></em></h5>
                    <?php endif; ?>
                </div>
            </div>

            <div class="clearfix"></div>

            <?php if (_xls_get_conf('USE_SHORT_DESC'))
                echo CHtml::tag('div',
                    array('id'=>CHtml::activeId($model,'description_short'),'class'=>'row description'),
                    $model->WebShortDescription);
            ?>

            <div id="purchase-options">

                <div class="row">
                    <div id="pricing" class="col-sm-4">
                        <?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'FormattedPrice'),'class'=>'price'),$model->Price); ?>
                        <?php

                        if (!$model->SlashedPrice || ($model->SlashedPrice == $model->Price)) {
                            echo CHtml::tag('div',array('id'=>CHtml::activeId($model,'FormattedRegularPrice').'_wrap','class'=>'price_reg'),
//                                    'style'=>(!$model->SlashedPrice ? 'display:none' : '')),
                            Yii::t('product', 'Regular Price').": ".
                                CHtml::tag('col-sm-',array('id'=>CHtml::activeId($model,'FormattedRegularPrice'),
                                    'class'=>'price_slash'),$model->SlashedPrice));
                        }
                        ?>

                        <?= CHtml::tag('div',array('id'=>CHtml::activeId($model,'InventoryDisplay'),'class'=>'stock'),
                            $model->InventoryDisplay); ?>
                    </div>
                </div>



                    <div class="row">
                        <?php if ($model->IsMaster): ?>
                            <div class="col-sm-10"><hr></div>
                            <div class="clearfix"></div>
                            <?= $this->renderPartial('/product/_matrixdropdown', array('form'=>$form,'model'=>$model), true); ?>
                        <?php endif; ?>
                    </div>



                    <div class="clearfix"></div>
                    <div class="col-sm-10"><hr></div>

                        <?php if (!_xls_get_conf('DISABLE_CART', false)): ?>
                         <div id="detail-add-row" class="row">
                            <div class="col-sm-3 form-group">
                                <?php echo (_xls_get_conf('SHOW_QTY_ENTRY') ? '' : 'style="display:none"'); ?>
                                    <?php echo $form->labelEx($model,'intQty',array('class'=>'col-sm-3 control-label')); ?>
                                <div class="col-sm-8">
                                    <?php echo $form->numberField($model,'intQty',array('type'=>'number','class'=>'form-control')); ?>
                                </div>
                            </div>
                            <?= CHtml::tag('div',array(
                                'class'=>'add-btn col-sm-4',
                                'onClick'=>CHtml::ajax(array(
                                        'url'=>array('cart/AddToCart'),
                                        //If we are viewing a matrix product, Add To Cart needs to pass selected options, otherwise just our model id
                                        'data'=>($model->IsMaster ?
                                                'js:{"'.'product_size'.'": $("#SelectSize option:selected").val(),
                                                "'.'product_color'.'": $("#SelectColor option:selected").val(),
                                                "'.'id'.'": '.$model->id.',
                                                "'.'qty'.'": $("#'.CHtml::activeId($model,'intQty').'").val() }'
                                                : array('id'=>$model->id,'qty'=>'js:$("#'.CHtml::activeId($model,'intQty').'").val()')),
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
                            ),CHtml::link('<span class="glyphicon glyphicon-shopping-cart"></span> '.Yii::t('product', 'Add to Cart'), '#',array('class'=>'btn btn-primary btn-lg')));
                            ?>

                         </div>
                         <div id="wishlist-sharing" class="row">
                             <div class="col-sm-4">

                                 <?php if (_xls_get_conf('ENABLE_WISH_LIST'))
                                     echo CHtml::tag('div',array(
                                         'class'=>'',
                                         'onClick'=>CHtml::ajax(array(
                                                 'url'=>array('wishlist/add'),
                                                 'data'=>array('id'=>$model->id,
                                                     'qty'=>'js:$("#'.CHtml::activeId($model,'intQty').'").val()',
                                                     'size'=>'js:$("#SelectSize option:selected").val()',
                                                     'color'=>'js:$("#SelectColor option:selected").val()'),
                                                 'type'=>'POST',
                                                 'success' => 'function(data) {
                                        if (data=="multiple")
                                            $("#WishitemShare").dialog("open");
                                         else alert(data); }'
                                             )),
                                     ),CHtml::link('<span class="glyphicon glyphicon-heart"></span> '.Yii::t('product', 'Add to Wish List'), '#',array('class'=>'btn btn-block btn-default')));
                                 ?>
                                 <?php endif; ?>

                             </div>
                             <div id="add-images" class="col-sm-5">
                                 <?php if(_xls_get_conf('SHOW_SHARING'))
                                     echo $this->renderPartial('/site/_sharing_tools',array('product'=>$model),true); ?>
                             </div>
                         </div>
                <?php if (!is_null($model->autoadd())): ?>
                    <div class="row">
                        <div id="auto-add" class="col-sm-10">
                            <?php
                            $this->widget('zii.widgets.grid.CGridView', array(
                                'id' => 'autoadd',
                                'dataProvider' => $model->autoadd(),
                                'showTableOnEmpty'=>false,
                                'selectableRows'=>0,
                                'emptyText'=>'',
                                'summaryText' => Yii::t('global',
                                        'The following products will be added to your cart automatically with this purchase'),
                                'hideHeader'=>false,
                                'columns' => array(
                                    'SliderImageTag:html',
                                    'TitleTag:html',
                                    'Price',
                                ),
                            ));
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                </div>

                <div class="row">
                    <div class="col-sm-12 description">
                        <h4><?= Yii::t('product', 'Product Description')?></h4>
                        <?= CHtml::tag('div',
                            array('id'=>CHtml::activeId($model,'description_long'),'class'=>'description'),
                            $model->WebLongDescription); ?>
                    </div>

                    <div class="facebook_comments">
                        <?php if(_xls_facebook_login() && _xls_get_conf('FACEBOOK_COMMENTS')): ?>
                            <h2><?= Yii::t('product', 'Comments about this product')?></h2>
                            <?php  $this->widget('ext.yii-facebook-opengraph.plugins.Comments', array(
                                'href' => $this->CanonicalUrl,
                            )); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


    </div>

<div id="related" class="row">
    <div class="text-center">
        <h3>You may also be interested in the following</h3>
    </div>
    <?php
            $this->widget('ext.JCarousel.JCarousel', array(
                'dataProvider' => $model->related(),
                'thumbUrl' => '$data->SliderImage',
                'imageUrl' => '$data->Link',
//						'summaryText' => Yii::t('global',
//							'You may be interested in the following'),
                'emptyText'=>'',
                'titleText' => '$data->Title',
                'captionText' => '$data->Title."<br>"._xls_currency($data->sell)',
                'target' => 'do-not-delete-this',
                //'wrap' => 'circular',
                'visible' => true,
                'skin' => 'slider',
                'clickCallback'=>'window.location.href=itemSrc;'
            ));
        ?>
</div>


<?php $this->endWidget(); ?>


<?php

/* This is our add to wish list box, which remains hidden until used */
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id'=>'WishitemShare',
    'options'=>array(
	    'title'=>Yii::t('wishlist','Add to Wish List'),
	    'autoOpen'=>false,
	    'modal'=>'true',
	    'width'=>'330',
	    'height'=>'250',
	    'scrolling'=>'no',
	    'resizable'=>false,
	    'position'=>'center',
	    'draggable'=>false,
    ),
));
echo $this->renderPartial('/wishlist/_addtolist', array('model'=>$WishlistAddForm,'objProduct'=>$model) ,true);
$this->endWidget('zii.widgets.jui.CJuiDialog'); ?>

