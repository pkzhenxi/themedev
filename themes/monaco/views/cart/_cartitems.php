<?php
/*
 * This file is used in a renderPartial() to display the cart within another view
 * Because our cart is pulled from the component, we can render from anywhere
 *
 * If our controller set intEditMode to be true, then this becomes an edit form to let the user change qty
 */
    if (!isset($model)) $model = Yii::app()->shoppingcart;
?>







<div class='row-fluid cart-header'>
    <div class='span5'>Item</div>
    <div class='span1'>Remove</div>
    <div class='span1'><?= Yii::t('cart','Price'); ?></div>
    <div class='span4'><?= Yii::t('cart','Quantity'); ?></div>
    <div class='span1'><?= Yii::t('cart','Subtotal'); ?></div>
</div>


<?php foreach($model->cartItems as $item): ?>
<div class='cart-item row-fluid'>
    <div class='item span5'>
        <?php if ($item->product->ProductPhotos) { ?>
            <img src="<?php echo $item->product->ProductPhotos[0]['image']; ?>" style='max-width: 128px' />
        <?php } ?>
        <div class='desc'>
            <a href="<?php echo $item->Link; ?>"><?=  _xls_truncate($item->description, 65, "...\n", true); ?></a>
        </div>
    </div>
    <div class='span1'>
        X
    </div>
    <div class='span1'>
        <?= ($item->discount) ? sprintf("<strike>%s</strike> ", _xls_currency($item->sell_base))._xls_currency($item->sell_discount) : _xls_currency($item->sell);  ?>
    </div>
    <div class='span4'>
            <?php
                if (isset($this->intEditMode) && $this->intEditMode)
                    echo CHtml::textField(CHtml::activeId($item,'qty')."_".$item->id,$item->qty,array('class'=>'cart_qty_box'));
                    else echo $item->qty;
            ?>         
    </div>
    <div class='span1'>
        <?= _xls_currency($item->sell_total) ?>
    </div>
</div>
<?php endforeach; ?>