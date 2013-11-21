<?php if($model->TaxTotal && Yii::app()->params['TAX_INCLUSIVE_PRICING']=='0'): ?>
	<?php foreach($model->Taxes as $tax=>$taxvalue): ?>
		<?php if($taxvalue): ?>
            <tr>
                <td></td>
                <td></td>
                <td><?= $tax; ?></td>
                <td><?= _xls_currency($taxvalue); ?></td>
            </tr>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>