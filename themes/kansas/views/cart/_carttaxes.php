<?php if($model->TaxTotal && Yii::app()->params['TAX_INCLUSIVE_PRICING']=='0'): ?>
	<?php foreach($model->Taxes as $tax=>$taxvalue): ?>
		<?php if($taxvalue): ?>
			<div class="row">
		        <div class="col-sm-6 cart-label"><h4 class="cart_label"><?= $tax; ?></h4></div>
		        <div class="col-sm-6 cart-price"><h4><?= _xls_currency($taxvalue); ?></h4></div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>