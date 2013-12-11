<?php
/*
 * This file is used in a renderPartial() to display the cart within another view
 * Because our cart is pulled from the component, we can render from anywhere
 *
 * If our controller set intEditMode to be true, then this becomes an edit form to let the user change qty
 */

//This file is also used for receipts which may be independent of 
//our current cart. If we've been passed a Cart object, use that
if (!isset($model)) $model = Yii::app()->shoppingcart; ?>
    <div id="genericcart" class="table-responsive col-sm-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= Yii::t('cart','Description'); ?></th>
                    <th><?= Yii::t('cart','Price'); ?></th>
                    <th><?= Yii::t('cart','Qty'); ?></th>
                    <th><?= Yii::t('cart','Total'); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($model->cartItems as $item): ?>
                    <tr>
                        <td><a href="<?php echo $item->Link; ?>"><?=  _xls_truncate($item->description, 65, "...\n", true); ?></a></td>
                        <td><?= ($item->discount) ? sprintf("<strike>%s</strike> ", _xls_currency($item->sell_base))._xls_currency($item->sell_discount) : _xls_currency($item->sell);  ?></td>
                        <td>
                            <?php
                            if (isset($this->intEditMode) && $this->intEditMode)
                                echo CHtml::numberField(CHtml::activeId($item,'qty')."_".$item->id,$item->qty,array('id'=>'cart-quantity','class'=>'','type'=>'number','min'=>'0'));
                            else echo $item->qty;
                            ?>

                        </td>
                        <td><?= _xls_currency($item->sell_total) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>



