<?php
/*
 * This file is used in a renderPartial() to display the cart within another view
 * Because our cart is pulled from the component, we can render from anywhere
 *
 * If our controller set intEditMode to be true, then this becomes an edit form to let the user change qty
 */

//This file is also used for receipts which may be independent of 
//our current cart. If we've been passed a Cart object, use that
if (!isset($model)) $model = Yii::app()->shoppingcart;

?>

<div id="genericcart">
    <div class="row">
        <div class="col-sm-3"><div class="cart_header"><?= Yii::t('cart','Description'); ?></div></div>
        <div class="col-sm-2 rightitem"><div class="cart_header"><?= Yii::t('cart','Price'); ?></div></div>
        <div class="col-sm-1">&nbsp;</div>
        <div class="col-sm-1 centeritem"><div class="cart_header"><?= Yii::t('cart','Qty'); ?></div></div>
        <div class="col-sm-1">&nbsp;</div>
        <div class="col-sm-2 rightitem"><div class="cart_header"><?= Yii::t('cart','Total'); ?></div></div>
	</div>
		<?php foreach($model->cartItems as $item): ?>
			<div class="row remove-bottom">
			    <div class="col-sm-3">
			        <a href="<?php echo $item->Link; ?>"><?=  _xls_truncate($item->description, 65, "...\n", true); ?></a>
			    </div>

			    <div class="col-sm-2 cart_price">
				    <?= ($item->discount) ? sprintf("<strike>%s</strike> ", _xls_currency($item->sell_base))._xls_currency($item->sell_discount) : _xls_currency($item->sell);  ?>
			    </div>

			    <div class="col-sm-1 centeritem cartdecor">x</div>

			    <div class="col-sm-1 centeritem"><div class="cart_qty"><?php
				        if (isset($this->intEditMode) && $this->intEditMode)
						    echo CHtml::textField(CHtml::activeId($item,'qty')."_".$item->id,$item->qty,array('class'=>'cart_qty_box'));
					        else echo $item->qty;
				    ?></div></div>

			    <div class="col-sm-1 centeritem cartdecor">=</div>

			    <div class="col-sm-2 cart_price"><?= _xls_currency($item->sell_total) ?></div>
			</div>
		<?php endforeach; ?>



	    <div class="row remove-bottom">

		    <div class="col-sm-2 col-sm-offset-6 cart_price"><div class="cart_label"><?= Yii::t('cart','Subtotal'); ?></div></div>
	        <div class="col-sm-2 cart_price"><div id="cartSubtotal"><?= _xls_currency($model->subtotal); ?></div></div>
		</div>
		    <div id="cartTaxes">
			    <?php echo $this->renderPartial('/cart/_carttaxes',array('model'=>$model),true); ?>
		    </div>

		<div class="row remove-bottom">
		        <div class="col-sm-2 col-sm-offset-6 cart_price"><div class="cart_label"><?= Yii::t('cart',"Shipping"); ?></div></div>
		        <div class="col-sm-2 cart_price"><div id="cartShipping"><?= _xls_currency($model->shipping_sell); ?></div></div>
		</div>
		<div class="row remove-bottom">
		        <div class="col-sm-2 col-sm-offset-6 cart_price"><?= Yii::t('cart',"Total"); ?></div>
		        <div class="col-sm-2 cart_price"><div id="cartTotal"><?= _xls_currency($model->total); ?></div></div>
		</div>
		<?php if($model->PromoCode): ?>
			<div class="row remove-bottom">
			     <div class="col-sm-4 col-sm-offset-6 promoCode"><?= Yii::t('cart',"Promo Code {code} Applied",array('{code}'=>"<strong>".$model->PromoCode."</strong>")); ?></div>
			</div>
		<?php endif; ?>

	<?php if (isset($this->intEditMode) && $this->intEditMode): ?>
		<div class="row">
			<div class="col-sm-12 errorMessage">
				<?php echo Yii::t('cart','Note: Change quantity to zero to remove an item from your cart.'); ?>
			</div>
		</div>
	<?php endif; ?>
</div>


