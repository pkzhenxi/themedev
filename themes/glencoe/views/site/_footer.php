<div class="footer">
    <div class="container">
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-xs-4">
                <div class="row" style="margin-left: 15px">
	                <?php $ccs = CreditCard::model()->findAllByAttributes(array('enabled'=>1));
	                    foreach ($ccs as $cc) : ?>
	                        <img style="padding-bottom: 2px" src="/themes/glencoe/css/images/payment/<?php echo str_replace(' ','-',strtolower($cc->label)); ?>-curved-32px.png">
	                <?php endforeach;
	                $paypal = false;
	                $payments = Modules::model()->findAllByAttributes(array('category'=>'payment','active'=>'1'));
	                foreach ($payments as $payment)
	                    if (stripos($payment->name,'paypal')!==false) $paypal = true;
	                if ($paypal) : ?>
	                <img src="/themes/glencoe/css/images/payment/paypal-curved-32px.png">
	                <?php endif; unset($paypal); unset($payments); ?>
                </div>
<!--                <div class="row" style="padding-top: 5px; margin-left: 15px">-->
<!--                    <img src="/themes/glencoe/css/images/securedbythawte.png" width="40%">-->
<!--                </div>-->
            </div>
            <div class="col-xs-3">
                <ul style="font-family: NexusLight;">
                    <?php
                    foreach (CustomPage::model()->bottomtabs()->findAll() as $arrTab)
                        echo '<li style="list-style-type: none;">'.CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link).'</li>'; ?>
                    <li style="list-style-type: none;">
                        <?php echo CHtml::link(Yii::t('global','Sitemap'),$this->createUrl('site/map')); ?>
                    </li>
                </ul>
            </div>
            <div class="col-xs-2">
                <ul style="font-family: NexusLight;">
                    <?php
                    foreach (CustomPage::model()->toptabs()->findAll() as $arrTab)
                        echo '<li style="list-style-type: none;">'.CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link).'</li>'; ?>
                </ul>
            </div>
            <div class="col-xs-3" style="text-align: right; color: #ffffff">
                <span style="font-family: NexusBold">
                    <?php echo _xls_get_conf('STORE_NAME')."<br>"; ?>
                </span>
                <span style="font-family: NexusLight">
                    <?php echo _xls_get_conf('STORE_ADDRESS1')."<br>";
                          echo _xls_get_conf('STORE_ADDRESS2')."<br>";
			              echo _xls_get_conf('STORE_HOURS')."<br>";
			              echo _xls_get_conf('STORE_PHONE')."<br>";
			              echo CHtml::link(_xls_get_conf('EMAIL_FROM'),'mailto:'._xls_get_conf('EMAIL_FROM'));
			        ?>
                </span>
            </div>
        </div>
        <div class="row">&nbsp;</div>
        <div class="row">
            <div class="col-xs-12" style="text-align: center;">
                <span style="font-family: NexusLight; color: #ffffff;"><br>&copy; <?= Yii::t('global', 'Copyright') ?> <?= date("Y"); ?> <?= _xls_get_conf('STORE_NAME') ?>. <?= Yii::t('global', 'All Rights Reserved'); ?>.</span>
            </div>
        </div>
        <div class="row">&nbsp;</div>
    </div>
</div>

