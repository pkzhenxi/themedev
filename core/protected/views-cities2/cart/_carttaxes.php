<?php if($model->TaxTotal && Yii::app()->params['TAX_INCLUSIVE_PRICING']=='0'): ?>
	<?php foreach($model->Taxes as $tax=>$taxvalue): ?>
		<?php if($taxvalue): ?>
			<div class="row remove-bottom">
		        <div class="col-sm-2 col-sm-offset-6 cart_price"><span class="cart_label"><?= $tax; ?></span></div>
		        <div class="col-sm-2 cart_price"><?= _xls_currency($taxvalue); ?></div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>