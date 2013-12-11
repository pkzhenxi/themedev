<?php
/*
 * This file is used in a renderPartial() to display the cart within another view
 * Because our cart is pulled from the component, we can render from anywhere
 *
 * If our controller set intEditMode to be true, then this becomes an edit form to let the user change qty
 */
if (!isset($model)) $model = Yii::app()->shoppingcart;

//This file is also used for receipts

?>
    <div id="genericcart" class="table-responsive">
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
                        <td><?php
                            if (isset($this->intEditMode) && $this->intEditMode)
                                echo CHtml::numberField(CHtml::activeId($item,'qty')."_".$item->id,$item->qty,array('id'=>'cart-quantity','class'=>'form-control','type'=>'number','min'=>'0'));
                            else echo $item->qty;
                            ?>
                        </td>
                        <td><?= _xls_currency($item->sell_total) ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>

        </table>
        <?php if (isset($this->intEditMode) && $this->intEditMode): ?>
            <div class="row">
                <div class="col-sm-7">
                    <p class="lead"><?php echo Yii::t('cart','Note: Change quantity to zero to remove an item from your cart.'); ?></p>
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
                        array('class'=>'btn btn-block btn-default'));
                    ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

