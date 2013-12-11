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

));?>

<?php
/*
 * This file is used in a renderPartial() to display the cart within another view
 * Because our cart is pulled from the component, we can render from anywhere
 *
 * If our controller set intEditMode to be true, then this becomes an edit form to let the user change qty
 */
    if (!isset($model)) $model = Yii::app()->shoppingcart;
?>

<?php $this->renderPartial('/cart/_cartitems'); ?>

<div class='row-fluid cart-adjust'>
	<div class='span4'>
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
		)); ?>
		/
		<?php echo CHtml::ajaxButton(
		Yii::t('cart', 'Clear Cart'),
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
		),array('confirm'=>Yii::t('cart',"Are you sure you want to erase your cart items?"))); ?>
	</div>
	<div class='offset6 span1'>
		<?= Yii::t('cart','Subtotal'); ?>
	</div>
	<div class='span1'>
		 <?= _xls_currency($model->subtotal); ?>
	</div>
</div>
<?php if($model->TaxTotal && Yii::app()->params['TAX_INCLUSIVE_PRICING']=='0'): ?>
	<?php foreach($model->Taxes as $tax=>$taxvalue): ?>
		<?php if($taxvalue): ?>
		<div class='row-fluid'>
            <div class='offset10 span1'>
                <?= $tax; ?>
            </div>
            <div class='span1'>
            	<?= _xls_currency($taxvalue); ?>
            </div>
        </div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
<div class='row-fluid'>
	<div class='offset10 span1'>
		<?= Yii::t('cart','Shipping'); ?>
	</div>
	<div class='span1'>
		<?= _xls_currency($model->shipping_sell); ?>
	</div>
</div>
<div class='row-fluid'>
	<div class='offset10 span1'>
		<?= Yii::t('cart',"Total"); ?> 
	</div>
	<div class='span1'>
		<?= _xls_currency($model->total); ?>
	</div>
</div>
<?php if($model->PromoCode): ?>
    <div class="row-fluid remove-bottom">
         <div class="span4 offset6 promoCode"><?= Yii::t('cart',"Promo Code {code} Applied",array('{code}'=>"<strong>".$model->PromoCode."</strong>")); ?>
         </div>
    </div>    
<?php endif; ?>


<div class="row-fluid">

	<?php echo CHtml::link(Yii::t('cart','Checkout'),array('cart/checkout'), array('class' => 'checkout big-button')) ?>

	<?= CHtml::tag('div',array(
		'id'=>'shoppingcartcontinue',
		'class'=>'span4 checkoutlink',
		'style' => 'float: right;',
		'onClick'=>'js:window.location.href="'. $this->returnUrl.'"'),
		Yii::t('cart','Continue Shopping'));
	?>

</div>

<?php $this->endWidget();


/* This is our sharing box, which remains hidden until used */
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'CartShare',
	'options'=>array(
		'title'=>Yii::t('wishlist','Share my Cart'),
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




