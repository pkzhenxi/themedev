<div class="col-sm-2 col-xs-4">
    <?php echo CHtml::htmlButton(
        Yii::t('cart', 'Share Cart')."&nbsp&nbsp<span class='glyphicon glyphicon-share icon-white'></span>",
        array('class'=>'btn btn-link',
            'onClick'=>'js:jQuery($("#CartShare")).dialog("open");return false;',

        )
    ); ?>
</div>
<div class="col-sm-offset-3 col-sm-3">
    <?= CHtml::htmlButton(
        Yii::t('cart','Continue Shopping')."&nbsp&nbsp<span class='glyphicon glyphicon-chevron-right icon-white'></span>",
        array('id'=>'cartcontinue','class'=>'btn btn-block btn-default',
            'onClick'=>'js:window.location.href="'. $this->returnUrl.'"')
    );
    ?>
</div>
<div id="checkout-btn" class="col-sm-offset-1 col-sm-3">
    <?= CHtml::tag('div',array(
            'id'=>'cart-checkout',
            'class'=>'btn btn-block btn-primary checkoutlink',
            'onClick'=>'js:window.location.href="'. $this->CreateUrl("cart/checkout").'"'),
        Yii::t('cart','Checkout'));
    ?>
</div>