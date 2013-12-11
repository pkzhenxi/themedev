<?//= $this->renderPartial('/cart/_cartjs',array('model'=>$model),true); ?>


<?php $form = $this->beginWidget('CActiveForm', array(
		'id'=>'checkout',
		'enableClientValidation'=>true,
		'enableAjaxValidation'=>true,
		'htmlOptions'=>array(
			'onsubmit' => '$("#submitSpinner").show()')
	));

?>

<div class="modal-header">
    <h1 class=""><?= Yii::t('checkout','Time to pay up') ?></h1>
    <small><?= Yii::t('global','Fields with {*} are required.',array('{*}'=>'<span class="required">*</span>')) ?></small>

</div>

<div class="modal-body">
    <?php if(Yii::app()->user->isGuest): ?>
    <div class="customercontact">
        <div class="row">
            <h2 class="hidden-xs col-sm-4"><?= Yii::t('checkout','Customer Contact'); ?></h2>
            <h2 class="hidden-xs col-sm-offset-1 col-sm-4"><?= Yii::t('checkout','Create a Free Account!'); ?></h2>
        </div>

        <div class="row">
            <div id="billing-info" class="col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'contactFirstName',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'contactFirstName',
                               array('onChange' => 'js:if(!$("#'.CHtml::activeId($model,'shippingFirstName').'").val())
                               $("#'.CHtml::activeId($model,'shippingFirstName').'").val(this.value)', 'placeholder'=>'First Name', 'class'=>'form-control')); ?>
                    <?php echo $form->error($model,'contactFirstName'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'contactLastName',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'contactLastName',
                               array('onChange' => 'js:if(!$("#'.CHtml::activeId($model,'shippingLastName').'").val())
                               $("#'.CHtml::activeId($model,'shippingLastName').'").val(this.value)','placeholder'=>'Last Name','class'=>'form-control' )); ?>
                    <?php echo $form->error($model,'contactLastName'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model,'contactCompany',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'contactCompany',array('placeholder'=>'Company','class'=>'form-control')); ?>
                    <?php echo $form->error($model,'contactCompany'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model,'contactPhone',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'contactPhone',array('placeholder'=>'Phone Number','class'=>'form-control')); ?>
                    <?php echo $form->error($model,'contactPhone'); ?>
                </div>


                <div class="form-group">
                    <?php echo $form->labelEx($model,'contactEmail',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'contactEmail',array('placeholder'=>'Email','class'=>'form-control')); ?>
                    <?php echo $form->error($model,'contactEmail'); ?>
                </div>

                <?php if(Yii::app()->user->isGuest): ?>
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'contactEmail_repeat',array('class'=>'sr-only')); ?>
                        <?php echo $form->textField($model,'contactEmail_repeat',array('placeholder'=>'Confirm Email','class'=>'form-control')); ?>
                        <?php echo $form->error($model,'contactEmail_repeat'); ?>
                    </div>
                <?php endif; ?>
            </div>


            <div id="createaccount" class="col-sm-offset-1 col-sm-4">
                <p class="help-block">
                        <?php if (_xls_get_conf('REQUIRE_ACCOUNT',0))
                        echo Yii::t('checkout',
                            'Enter a password to create your account.');
                        else echo Yii::t('checkout',
                            'To save your information, enter a password here to create an account, or leave blank to check out as a guest.'); ?>
                </p>

                <div class="form-group">
                    <?php echo $form->labelEx($model,'createPassword',array('class'=>'sr-only')); ?>
                    <?php echo $form->passwordField($model,'createPassword',
                            array('placeholder'=>'Create Password','class'=>'form-control', 'autocomplete'=>"off")); ?>
                    <?php echo $form->error($model,'createPassword'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'createPassword_repeat',array('class'=>'sr-only')); ?>
                    <?php echo $form->passwordField($model,'createPassword_repeat',
                            array('placeholder'=>'Confirm Password', 'class'=>'form-control', 'autocomplete'=>'off')); ?>
                    <?php echo $form->error($model,'createPassword_repeat'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->checkBox($model,'receiveNewsletter'); ?>
                    <?php echo $form->label($model,'receiveNewsletter'); ?>
                    <?php echo $form->error($model,'receiveNewsletter'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div id="select-addresses">
<!--        If we have addresses from the address book, display for the user to choose, plus an option to add a new one-->
        <?php if(count($model->objAddresses)>0): ?>

            <div class="row">
                <div id="CustomerContactShippingAddress">
                    <h2 class="hidden-xs col-sm-4"><?= Yii::t('checkout','Choose your shipping address'); ?></h2>
                    <div class="billing-address">
                        <h2 class="hidden-xs col-sm-offset-1 col-sm-4"><?= Yii::t('checkout','Choose your billing address'); ?></h2>
                    </div>
                </div>
            </div>

            <div class="row">
				<div class="col-sm-4">
                    <?php foreach ($model->objAddresses as $objAddress): ?>
                        <div class="radio">
                            <label>
                                <?php echo $form->radioButton($model,'intShippingAddress',
                                    array('value'=>$objAddress->id,'uncheckValue'=>null,
                                          'onclick'=> 'js:$(".add-shipping-address").hide();
                                                       js:$("#btnCalculate").click();',
                                            'name'=>'shippingOptions'
                                    )); ?>
                                <?= $objAddress->address_label ?>
                            </label>
                        </div>
                        <address><?= $objAddress->formattedblock ?></address>
                    <?php endforeach; ?>

                    <div class="clearfix"></div>

                    <div class="radio">
                        <label>
                            <?php echo $form->radioButton($model,'intShippingAddress',array('value'=>0,'uncheckValue'=>null,
                                'onclick'=> 'js:$(".add-shipping-address").show(); updateShippingAuto();','checked'=>'checked',
                                'name'=>'shippingOptions'
                            )); ?>
                            <?= Yii::t('checkout','Or enter a new address'); ?>
                        </label>
                    </div>
	            </div>

                <div class="billing-address col-sm-offset-1 col-sm-4">
                    <?php foreach ($model->objAddresses as $objAddress): ?>
                        <div class="radio">
                            <label>
                                <?php echo $form->radioButton($model,'intBillingAddress',
                                    array('value'=>$objAddress->id,'uncheckValue'=>null,
                                        'onclick'=> 'js:$(".add-billing-address").hide();',
                                        'name'=>'billingOptions'
                                    )); ?>
                                <?= $objAddress->address_label ?>
                            </label>
                        </div>
                        <address><?= $objAddress->formattedblock ?></address>
                    <?php endforeach; ?>

                    <div class="clearfix"></div>

                    <div class="radio">
                        <label>
                            <?php echo $form->radioButton($model,'intBillingAddress',array('value'=>0,'uncheckValue'=>null,
                                'onclick'=> 'js:$(".add-billing-address").show();','checked'=>'checked',
                                'name'=>'billingOptions'
                            )); ?>
                            <?= Yii::t('checkout','Or enter a new address'); ?>
                        </label>
                    </div>
                </div>
<!--			--><?php //else: ?>
<!--			<div class="row">-->
<!--				<div style="display: none">-->
<!--				--><?php //echo $form->radioButton($model,'intShippingAddress',array('value'=>0,'uncheckValue'=>null,
//					'onclick'=> 'js:$(".add-billing-address").show();')); ?>
<!--	                </div>-->
<!--			</div>-->
<!--            <div class="row">-->
<!--<!--                <div style="display: none">-->
<!--                    --><?php //echo $form->radioButton($model,'intBillingAddress',array('value'=>0,'uncheckValue'=>null,
//                        'onclick'=> 'js:$("#CustomerContactBillingAddressAdd").show();')); ?>
<!--                </div>-->
<!--            </div>-->

            <?php endif; ?>

            </div>
        </div>

        <div class="row">
            <div id="add-address-header" class="add-address">
                <div class="add-shipping-address shipping-address">
                    <h2 class="hidden-xs col-sm-4"><?= Yii::t('checkout','Shipping Address') ?></h2>
                </div>
                <div class="add-billing-address billing-address">
                    <h2 class="hidden-xs col-sm-offset-1 col-sm-4"><?= Yii::t('checkout','Billing Address'); ?></h2>
                </div>
            </div>
        </div>
        <div class="row add-address">
            <div id="" class="add-shipping-address col-sm-4">
                <div class="form-group">
                    <?php echo $form->checkBox($model,'billingSameAsShipping',array(
                        'onclick'=>'js:jQuery($(".billing-address")).toggle()',
                        'disabled'=>Yii::app()->params['SHIP_SAME_BILLSHIP'],
                        'checked'=>'checked'
                    )); ?>
                    <?php echo $form->label($model,'billingSameAsShipping'); ?>
                    <?php echo $form->error($model,'billingSameAsShipping'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'shippingLabel',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'shippingLabel',array('class'=>'form-control', 'placeholder'=>'')); ?>
                    <?php echo $form->error($model,'shippingLabel'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model,'shippingFirstName',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'shippingFirstName',array('class'=>'form-control', 'placeholder'=>'First Name')); ?>
                    <?php echo $form->error($model,'shippingFirstName'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'shippingLastName',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'shippingLastName',array('class'=>'form-control', 'placeholder'=>'Last Name')); ?>
                    <?php echo $form->error($model,'shippingLastName'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model,'shippingAddress1',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'shippingAddress1',array('class'=>'form-control', 'placeholder'=>'Address Line 1')); ?>
                    <?php echo $form->error($model,'shippingAddress1'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'shippingAddress2',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'shippingAddress2',array('class'=>'form-control', 'placeholder'=>'Address Line 2')); ?>
                    <?php echo $form->error($model,'shippingAddress2'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model,'shippingCity',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'shippingCity',array('class'=>'form-control', 'placeholder'=>'City')); ?>
                    <?php echo $form->error($model,'shippingCity'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model,'shippingCountry',array('class'=>'control-label')); ?>
                    <?php echo $form->dropDownList($model,'shippingCountry',$model->getCountries(),array(
                        'ajax' => array(
                            'type'=>'POST',
                            'url'=>CController::createUrl('cart/getdestinationstates'),
                            'success'=>'js:function(data){
                                $("#' . CHtml::activeId( $model, 'shippingState') .'").html(data);
                                $("#' . CHtml::activeId( $model, 'shippingProvider') .'").html("");
                                $("#' . CHtml::activeId( $model, 'shippingPriority') .'").html(""); }',
                            'data' => 'js:{"'.'country_id'.'": $("#'.CHtml::activeId($model,'shippingCountry').
                                ' option:selected").val()}',
                        ),'class'=>'form-control')); ?>
                    <?php echo $form->error($model,'shippingCountry'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model,'shippingState',array('class'=>'control-label')); ?>
                    <?php echo $form->dropDownList($model,'shippingState',$model->getStates('shipping'),array(
                        'prompt' =>'--',
                        'ajax' => array(
                            'type'=>'POST',
                            'dataType'=>'json',
                            'url'=>CController::createUrl('cart/settax'),
                            'success'=>'js:function(data){ updateTax(data) }',
                            'data' => 'js:{"'.'state_id'.'": $("#'.CHtml::activeId($model,'shippingState').
                                ' option:selected").val(),
                                "'.'postal'.'": $("#'.CHtml::activeId($model,'shippingPostal').'").val()}',
                        ),'class'=>'form-control')); ?>
                    <?php echo $form->error($model,'shippingState'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model,'shippingPostal',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'shippingPostal',array(
                        'ajax' => array(
                            'type'=>'POST',
                            'dataType'=>'json',
                            'url'=>CController::createUrl('cart/settax'),
                            'success'=>'js:function(data){ updateTax(data) }',
                            'data' => 'js:{"'.'state_id'.'": $("#'.CHtml::activeId($model,'shippingState').
                                ' option:selected").val(),
                                "'.'postal'.'": $("#'.CHtml::activeId($model,'shippingPostal').'").val()}',
                        ),'class'=>'form-control','placeholder'=>'ZIP / Postal Code')); ?>
                    <?php echo $form->error($model,'shippingPostal'); ?>
                </div>


                <div class="form-group">
                    <?php echo $form->checkBox($model,'shippingResidential'); ?>
                    <?php echo $form->label($model,'shippingResidential'); ?>
                    <?php echo $form->error($model,'shippingResidential'); ?>
                </div>
            </div>

            <div class="row">
                <div class="add-billing-address billing-address col-sm-offset-1 col-sm-4">
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'billingLabel'); ?>
                        <?php echo $form->textField($model,'billingLabel',array('class'=>'form-control')); ?>
                        <?php echo $form->error($model,'billingLabel'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'billingAddress1',array('class'=>'sr-only')); ?>
                        <?php echo $form->textField($model,'billingAddress1',array('class'=>'form-control', 'placeholder'=>'Address Line 1')); ?>
                        <?php echo $form->error($model,'billingAddress1'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'billingAddress2',array('class'=>'sr-only')); ?>
                        <?php echo $form->textField($model,'billingAddress2',array('class'=>'form-control', 'placeholder'=>'Address Line 2')); ?>
                        <?php echo $form->error($model,'billingAddress2'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'billingCity',array('class'=>'sr-only')); ?>
                        <?php echo $form->textField($model,'billingCity',array('class'=>'form-control', 'placeholder'=>'City')); ?>
                        <?php echo $form->error($model,'billingCity'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'billingCountry'); ?>
                        <?php echo $form->dropDownList($model,'billingCountry',$model->getCountries(),array(
                            'ajax' => array(
                                'type'=>'POST',
                                'url'=>CController::createUrl('cart/getdestinationstates'), //url to call
                                'update'=>'#'.CHtml::activeId($model,'billingState'), //selector to update
                                'data' => 'js:{"country_id": $("#'.CHtml::activeId($model,'billingCountry').' option:selected").val()}',
                            ),'class'=>'form-control')); ?>
                        <?php echo $form->error($model,'billingCountry'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model,'billingState'); ?>
                        <?php echo $form->dropDownList($model,'billingState',
                            $model->getStates('billing'),array('prompt' =>'--','class'=>'form-control')); ?>
                        <?php echo $form->error($model,'billingState'); ?>
                    </div>
                    <div class="form-group">
                        <?php echo $form->labelEx($model,'billingPostal',array('class'=>'sr-only')); ?>
                        <?php echo $form->textField($model,'billingPostal',array('class'=>'form-control', 'placeholder'=>'ZIP / POstal Code')); ?>
                        <?php echo $form->error($model,'billingPostal'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->checkBox($model,'billingResidential'); ?>
                        <?php echo $form->label($model,'billingResidential'); ?>
                        <?php echo $form->error($model,'billingResidential'); ?>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div id="promocode" class="col-sm-12">
                <h2><?php echo Yii::t('checkout','Promo Code'); ?></h2>
                <p class="help-block">
                    <?php echo Yii::t('checkout','Enter a Promotional Code here to receive a discount.'); ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3" >
                <?php echo $form->textField($model,'promoCode',array('class'=>'form-control')); ?>
            </div>
            <div class="offset2 col-sm-4" >
                <?php echo CHtml::ajaxButton (Yii::t('checkout','Apply Promo Code'),
                    array('cart/applypromocode'),
                    array('type'=>"POST",
                        'dataType'=>'json',
                        'data'=>'js:jQuery($("#' . CHtml::activeId($model,'promoCode') .'")).serialize()',
                        'success' => 'js:function(data){
                            if (data.action=="alert") {
                              alert(data.errormsg);
                            } else if (data.action=="error") {
                                alert(data.errormsg);
                                $("#' . CHtml::activeId($model,'promoCode') .'_em_").html(data.errormsg).show();
                            } else if (data.action=="triggerCalc") {
                                $("#btnCalculate").click();
                                alert(data.errormsg);
                            } else if (data.action=="success") {
                                $("#cartItems").html(data.cartitems);
                                savedCartScenarios = data.cartitems;
                                $("#' . CHtml::activeId($model,'promoCode') .'_em_").html(data.errormsg).show();
                                alert(data.errormsg);
                                updateShippingAuto();
                            }
                        }'),
                    array('id' => 'CheckoutForm_btnPromoCode', 'class'=>'btn btn-primary')); ?>
            </div>
        </div>
    <div class="row">
        <div class="col-sm-6" >
            <?php echo $form->error($model,'promoCode'); ?>
        </div>
    </div>

    <div class="row">
        <div id="shipping-header" class="col-sm-12">
            <h2><?php echo Yii::t('checkout','Shipping'); ?></h2>
        </div>
        <div class="col-sm-3" >
            <?php echo CHtml::ajaxButton (
                Yii::t('checkout','Click to Calculate'),
                array('cart/ajaxcalculateshipping'),
                array('type'=>"POST",
                    'dataType'=>'json',
                    'data'=>'js:jQuery($("#checkout")).serialize()',
                    'onclick'=>'js:jQuery($("#shippingSpinner")).show(),
                                js:jQuery($("#' . CHtml::activeId( $model, 'shippingProvider') .'")).html(\'\'),
                                js:jQuery($("#' . CHtml::activeId( $model, 'shippingPriority') .'")).html(\'\')',
                    'success' => 'js:function(data){
                                    if (data.result=="error") alert(data.errormsg);
                                    savedShippingProviders = data.provider;
                                    savedShippingPrices = data.prices;
                                    savedTaxes = data.taxes;
                                    savedTotalScenarios = data.totals;
                                    savedShippingPriorities = data.priority;
                                    savedCartScenarios = data.cartitems;
                                    $("#' . CHtml::activeId( $model, 'shippingProvider') .'").html(data.provider);
                                    $("#' . CHtml::activeId( $model, 'shippingPriority') .'").html(data.priority);
                                    $("#' . CHtml::activeId( $model, 'paymentProvider') .'").html(data.paymentmodules);
                                    $("#shippingSpinner").hide();
                                    $("#shippingProvider_0").click();
                                    }',
                ), array('id'=>'btnCalculate', 'class'=>'btn btn-link'));
            ?>

        </div>
    </div>

    <div id="shipping-options" class="row">
        <div class="col-sm-3">
            <h3><?php echo $form->labelEx($model,'shippingProvider'); ?></h3>

<!--            --><?php //$providers = $model->getProviders(); ?>
<!--            --><?php //foreach($providers as $id=>$method): ?>
<!--                <div class="radio">-->
<!--                    <label>-->
<!--                        --><?php //echo $form->radioButton($model,'intShippingAddress',
//                            array('value'=>$id,'uncheckValue'=>null,
//                                'onclick' => 'updateShippingPriority(this.value)',
//
//                            )); ?>
<!--                        --><?//=$method;?>
<!--                    </label>-->
<!--                </div>-->
<!--            --><?php //endforeach; ?>

            <div id="shippingProviderRadio">
                <?php echo $form->radioButtonList($model,'shippingProvider',$model->getProviders(),
                    array(  'onclick' => 'updateShippingPriority(this.value)',
                        'separator'=>'')); ?>
            </div>
            <?php echo $form->error($model,'shippingProvider',null,false,false); ?>
            <div id="shippingSpinner" style="display:none"><?php
                echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif')?></div>
        </div>

        <div class="col-sm-5">
            <h3><?php echo $form->labelEx($model,'shippingPriority'); ?></h3>
            <div id='shippingPriorityRadio'>
                <?php echo $form->radioButtonList($model,'shippingPriority', $model->getPriorities($model->shippingProvider),
                    array(  'onclick' => 'updateCart(this.value)',
                        'separator'=>'')); ?>
            </div>
            <?php echo $form->error($model,'shippingPriority',null,false,false); ?>
        </div>
    </div>


	<?php //The contents of the div id=cartItems are refreshed through various AJAX actions such as taxes and shipping ?>
	<div id="checkoutShoppingCart" class="row">
        <div class="col-sm-8">
            <h2><?php echo Yii::t('checkout','Review Cart'); ?></h2>
            <?php $this->renderPartial('/cart/_cartitems',array('model'=>Yii::app()->shoppingcart)); ?>
	    </div>
    </div>

    <div id="carttotal" class="row">
        <div class="col-sm-offset-4 col-sm-4">
            <?php $this->renderPartial('/cart/_ordersummary',array('model'=>Yii::app()->shoppingcart)); ?>
        </div>
    </div>


	<div id="payment">
        <h2><?= Yii::t('checkout','Payment'); ?></h2>

        <div class="col-sm-2 form-group">
            <?php echo $form->labelEx($model,'paymentProvider'); ?>
            <?php echo $form->dropDownList($model,'paymentProvider',$model->GetPaymentModules(),array(
                    'onchange'=>'changePayment(this.value)','class'=>'form-control'
                )); ?>
            <?php echo $form->error($model,'paymentProvider'); ?>
        </div>


        <?php /* If we have payment modules with custom forms, they are rendered here */ ?>
        <div id="Payforms" class="row">
            <?php foreach($paymentForms as $moduleName=>$paymentForm)
                    echo $this->renderPartial('/cart/_paymentform',
                        array('moduleName'=>$moduleName,'form'=>$paymentForm,'model'=>$model),true);
            ?>
        </div>

        <?php /* The credit card form renders hidden and will display if a payment module needs it */ ?>
        <div id="CreditCardForm" style="display: none" class="">
            <div class="row">
                <div class="col-sm-4 form-group">
                    <?php echo $form->labelEx($model,'cardNameOnCard'); ?>
                    <?php echo $form->textField($model,'cardNameOnCard',array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'cardNameOnCard'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2 form-group">
                    <?php echo $form->labelEx($model,'cardType'); ?>
                    <?php echo $form->dropDownList($model,'cardType',$model->getCardTypes(), array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'cardType'); ?>
                </div>
                <div class="col-sm-2 form-group">
                    <?php echo $form->labelEx($model,'cardNumber'); ?>
                    <?php echo $form->textField($model,'cardNumber',array('autocomplete'=>'off', 'class'=>'form-control')); ?>
                    <?php echo $form->error($model,'cardNumber'); ?>
                </div>
                <div class="col-sm-1 form-group">
                    <?php echo $form->labelEx($model,'cardCVV'); ?>
                    <?php echo $form->textField($model,'cardCVV',array('autocomplete'=>'off','class'=>'form-control')); ?>
                    <?php echo $form->error($model,'cardCVV'); ?>
                </div>
                <div class="col-sm-2 form-group">
                    <?php echo $form->labelEx($model,'cardExpiryMonth'); ?>
                    <?php echo $form->dropDownList($model,'cardExpiryMonth',$model->getCardMonths(),array('prompt'=>'--','class'=>'form-control')); ?>
                    <?php echo $form->error($model,'cardExpiryMonth'); ?>
                </div>
                <div class="col-sm-1 form-group">
                    <?php echo $form->labelEx($model,'cardExpiryYear'); ?>
                    <?php echo $form->dropDownList($model,'cardExpiryYear',$model->getCardYears(),array('prompt'=>'--','class'=>'form-control')); ?>
                    <?php echo $form->error($model,'cardExpiryYear'); ?>
                </div>
            </div>
	    </div>
    </div>

	<div id="checkoutSubmit">
        <h2><?php echo Yii::t('checkout','Submit your order'); ?></h2>
        <div class="row">
            <div class="form-group col-sm-8">
                <?php echo $form->labelEx($model,'orderNotes'); ?>
                <?php echo $form->textArea($model,'orderNotes',array('rows'=>6, 'cols'=>90, 'class'=>'form-control')); ?>
                <?php echo $form->error($model,'orderNotes'); ?>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <?php echo $form->checkBox($model,'acceptTerms'); ?>
                <?php echo Yii::t('checkout',
                    'I hereby agree to the Terms and Conditions of shopping with {storename}',
                    array('{storename}'=>_xls_get_conf('STORE_NAME'))) ?>
                <?php echo $form->error($model,'acceptTerms'); ?>
            </div>
        </div>
    </div>

	<div class="row"></div>
	        <div id="submitSpinner" style="display:none">
		        <?php echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif')?>
	        </div>
            <div class="row">
                <div class="col-sm-2">
                    <?php echo CHtml::submitButton('Submit',array('id'=>'checkoutSubmitButton', 'class'=>'btn btn-block btn-primary')); ?>
                </div>
            </div>
    </div>
</div>

<?php $this->endWidget(); ?>


<!--Render javascript-->
<?= $this->renderPartial('/cart/_cartjs',array('model'=>$model),true); ?>