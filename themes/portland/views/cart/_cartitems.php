<?php
/*
 * This file is used in a renderPartial() to display the cart within another view
 * Because our cart is pulled from the component, we can render from anywhere
 *
 * If our controller set intEditMode to be true, then this becomes an edit form to let the user change qty
 */
    if (!isset($model)) $model = Yii::app()->shoppingcart;
?>

<div id="genericcart">
    <table class="table">
        <thead>
            <tr>
              <th><?= Yii::t('cart','Qty'); ?></th>
              <th><?= Yii::t('cart','Description'); ?></th>
              <th><?= Yii::t('cart','Price'); ?></th>
              <th><?= Yii::t('cart','Total'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($model->cartItems as $item): ?>
                <tr>
                    <td>
                        <?php
                            if (isset($this->intEditMode) && $this->intEditMode)
                                echo CHtml::textField(CHtml::activeId($item,'qty')."_".$item->id,$item->qty,array('class'=>'cart_qty_box'));
                                else echo $item->qty;
                        ?>                    
                    </td>
                    <td><a href="<?php echo $item->Link; ?>"><?=  _xls_truncate($item->description, 65, "...\n", true); ?></a></td>
                    <td>
                        <?= ($item->discount) ? sprintf("<strike>%s</strike> ", _xls_currency($item->sell_base))._xls_currency($item->sell_discount) : _xls_currency($item->sell);  ?>
                    </td>
                    <td><?= _xls_currency($item->sell_total) ?></td>
                </tr>
            <?php endforeach; ?>
            
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td><?= Yii::t('cart','Subtotal'); ?></td>
                <td><span id="cartSubtotal"><?= _xls_currency($model->subtotal); ?></span></td>
            </tr>

            <?php echo $this->renderPartial('/cart/_carttaxes',array('model'=>$model),true); ?>

            <tr>
                <td></td>
                <td></td>
                <td><?= Yii::t('cart',"Shipping"); ?></td>
                <td><span id="cartShipping"><?= _xls_currency($model->shipping_sell); ?></span></td>
            </tr> 

            <tr>
                <td></td>
                <td></td>
                <td><?= Yii::t('cart',"Total"); ?></td>
                <td><span id="cartTotal"><?= _xls_currency($model->total); ?></span></td>
            </tr>



            <?php if($model->PromoCode): ?>
                <div class="row-fluid remove-bottom">
                     <div class="span4 offset6 promoCode"><?= Yii::t('cart',"Promo Code {code} Applied",array('{code}'=>"<strong>".$model->PromoCode."</strong>")); ?>
                     </div>
                </div>
            <?php endif; ?>

        </tbody>
    </table>
</div>


