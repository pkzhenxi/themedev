<li style="padding-left: 10px; padding-top: 5px;">
    <?php if (count(Yii::app()->shoppingcart->cartItems)==0)
        echo Yii::t('cart','Your cart is empty');
    else { ?>
        <table>
            <?php $model = Yii::app()->shoppingcart;
            foreach ($model->cartItems as $item) { ?>
                <tr>
                    <td class="cartdropdown" id="cartqty-dropdown"><?= $item->qty ?></td>
                    <td class="cartdropdown" id="cartdesc-dropdown">
                        <?= _xls_truncate($item->description, 65, "...\n", true) ?>
                    </td>
                    <td class="cartdropdown" id="cartsell-dropdown"><?= _xls_currency($item->sell) ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td>&nbsp;</td>
                <td id="cartlabel-dropdown">
                    <?= Yii::t('cart','Subtotal'); ?>
                </td>
                <td id="cartsubtotal-dropdown">
                    <?= _xls_currency($model->subtotal); ?>
                </td>
            </tr>
        </table>
    <?php } ?>
</li>
<li class="divider"></li>
<li id="cartlink-dropdown"><?php echo CHtml::link(Yii::t('cart','Edit Cart'),array('/cart')) ?></li>
<li id="cartlink-dropdown"><?php echo CHtml::link(Yii::t('cart','Checkout'),array('cart/checkout')); ?></li>