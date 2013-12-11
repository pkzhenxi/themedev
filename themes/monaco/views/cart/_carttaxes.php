<?php if($model->TaxTotal && Yii::app()->params['TAX_INCLUSIVE_PRICING']=='0'): ?>
	<?php foreach($model->Taxes as $tax=>$taxvalue): ?>
		<?php if($taxvalue): ?>
            <div class='offset8 span4'>
                <?= $tax; ?> <?= _xls_currency($taxvalue); ?>
            </div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>