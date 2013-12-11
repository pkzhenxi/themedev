<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
   
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * template Edit Cart display
 *
 *
 *
 */

$form = $this->beginWidget('CActiveForm', array(
	'id'=>'ShoppingCart',
	'action'=>array('cart/updatecart'),

));


?>
<div id="cartItems" class="spaceafter"><?php $this->renderPartial('/cart/_cartitems'); ?></div>

<div class="clearfix"></div>

<?php if (isset($this->intEditMode) && $this->intEditMode): ?>
    <div class="row">
        <div class="col-sm-8 errorMessage">
            <p class="lead"><?php echo Yii::t('cart','Note: Change quantity to zero to remove an item from your cart.'); ?></p>
        </div>

        <div class="col-xs-4 col-sm-2">
            <?php echo CHtml::ajaxButton(
                Yii::t('cart', 'Update Cart'),
                array('cart/updatecart'),
                array('data'=>'js:$("#ShoppingCart").serialize()',
                    'type'=>'POST',
                    'dataType'=>'json',
                    'success' => 'js:function(data){
	                    if (data.action=="alert") {
	                      alert(data.errormsg);
						} else if (data.action=="success") {
							 location.reload();
						}}'
                ),
                array('class'=>'btn btn-link'));
            ?>
        </div>
        <div id="empty-button" class="col-sm-2">
            <?php echo CHtml::ajaxButton(
                Yii::t('cart', 'Empty Cart'),
                array('cart/clearcart'),
                array('data'=>array(),
                    'type'=>'POST',
                    'dataType'=>'json',
                    'success' => 'js:function(data){
	                    if (data.action=="alert") {
	                      alert(data.errormsg);
						} else if (data.action=="success") {
							 location.reload();
						}}'
                ),array('confirm'=>Yii::t('cart',"Are you sure you want to erase your cart items?"),
                    'class'=>'btn btn-link'
                )); ?>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-sm-2 col-xs-4">
        <?php echo CHtml::htmlButton(
            Yii::t('cart', 'Share Cart')."&nbsp&nbsp<span class='glyphicon glyphicon-share icon-white'></span>",
            array('class'=>'btn btn-link',
                'onClick'=>'js:jQuery($("#CartShare")).dialog("open");return false;',

            )
        ); ?>
    </div>
</div>

<div id="keepshopping" class="row">
    <div class="col-sm-offset-8 col-sm-3">
        <?= CHtml::htmlButton(
            Yii::t('cart','Continue Shopping')."&nbsp&nbsp<span class='glyphicon glyphicon-chevron-right icon-white'></span>",
            array('id'=>'cartcontinue','class'=>'btn btn-block btn-default',
                'onClick'=>'js:window.location.href="'. $this->returnUrl.'"')
        );
        ?>
    </div>
    <div class="col-sm-1 h4">
        Or
    </div>
</div>

<div id="checkout-row" class="row">
    <div class="col-sm-offset-8 col-sm-4">
        <?= CHtml::htmlButton(
            Yii::t('cart','Checkout'),
              array('id'=>'cartcheckout',
                'class'=>'btn btn-block btn-primary',
                'onClick'=>'js:window.location.href="'. $this->CreateUrl("cart/checkout").'"')
            );
        ?>
    </div>
</div>


<?php $this->endWidget(); ?>

<div class="clearfix"></div>

<div class="row">
    <?php $this->renderPartial('/cart/_ordersummary'); ?>
</div>

<?php
/* This is our sharing box, which remains hidden until used */
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'CartShare',
	'options'=>array(
		'title'=>Yii::t('wishlist','Share my Cart'),
        'class'=>'btn btn-block',
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'380',
		'height'=>(Yii::app()->user->isGuest ? '580' : '430'),
		'scrolling'=>'yes',
		'resizable'=>false,
		'position'=>'center',
		'draggable'=>false,
	),
));
$this->renderPartial('/cart/_sharecart', array('model'=>$CartShare));
$this->endWidget('zii.widgets.jui.CJuiDialog');




