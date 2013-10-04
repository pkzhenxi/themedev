<div id="shoppingcartbottom" class="row">
	<div class="cart_label col-sm-6">
        <?= CHtml::link(Yii::t('checkout','{n} item in cart|{n} items in cart',
	        Yii::app()->shoppingcart->cartQty
	        ),array('cart/index')) ?>
	</div>
	<div class="cart_price col-sm-4">
		<?= CHtml::link(Yii::t('checkout','{subtotal}',
			array('{subtotal}'=>_xls_currency(Yii::app()->shoppingcart->subtotal)
			)),array('cart/index')) ?>
	</div>
	<div class="carticon col-sm-1">&nbsp;</div>
</div>
