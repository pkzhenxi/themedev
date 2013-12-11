
<div id="wishlistdisplay" class="col-sm-12">

    <div class="modal-header">
        <p class="lead"><?= Yii::t('global','Wish List') ?>:</p>
        <h2><?= $model->registry_name; ?></h2>
	    <div class="event_date"><?php if($model->event_date) echo Yii::t('global','Event Date').": ".$model->event_date; ?></div>
    </div>
	<?php if ($model->IsMine): ?>
		<?= CHtml::tag('div',array(
			'class'=>'col-sm-2 addcart',
			'onClick'=>'js:window.location.href="'.Yii::app()->createUrl('/wishlist').'"'),
			CHtml::link(Yii::t('global','View All Lists'), '#'));
		?>
		<?= CHtml::tag('div',array(
			'class'=>'col-sm-2 sharelist',
			'onClick'=>'js:window.location.href="'.Yii::app()->createUrl('wishlist/edit',array('code'=>$model->gift_code)).'"'),
			CHtml::link(Yii::t('global','Settings'), '#'));
		?>
		<?= CHtml::tag('div',array(
			'class'=>'col-sm-2 sharelist',
			'onClick'=>'js:jQuery($("#WishitemShare")).dialog("open");return false;'),
			CHtml::link(Yii::t('global','Share'), '#'));
		?>
	<?php endif; ?>

	<div class="clearfix"></div>

    <div id="wishlist-item-table" class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?= Yii::t('global','Products') ?></th>
                    <th><?= Yii::t('global','Qty') ?></th>
                    <th><?= Yii::t('global','Status') ?></th>
                    <th><?= Yii::t('global','Edit') ?></th>
                </tr>
                <td></td>
            </thead>
            <tbody>



	<?php foreach ($model->wishlistItems as $item): ?>
		<?php if ($item->product != null): ?>
        <tr>
            <td>
				<a href="<?= $item->product->Link; ?>">
                    <img src="<?= $item->product->MiniImage ?>" />
                </a>
                <a href="<?php echo $item->product->Link; ?>"><?=  _xls_truncate($item->product->Title, 65, "...\n", true); ?></a>
                <?= ($item->comment ? "<div class=\"comment\">".$item->comment."</div>" : "") ?>
            </td>

            <td>
<!--                --><?//= $item->id ?>
                <?= $item->qty; ?>
                <?= ($item->priority <> 1 ? "<div class=\"comment\">".$item->Priority."</div>" : "") ?>
            </td>

            <td>
                <?php
                if (is_null($item->cart_item_id) && $item->product->IsAddable)
                    echo CHtml::ajaxLink(
                        Yii::t('product', 'Add to Cart'),
                        array('cart/AddToCart'),
                        array('data'=>array(
                            'id'=>$item->product_id,
                            'qty'=>$item->qty,
                            'wishid'=>$item->id,
                        ),
                            'type'=>'POST',
                            'dataType'=>'json',
                            'success' => 'js:function(data){
			                    if (data.action=="alert") {
			                      alert(data.errormsg);
								} else if (data.action=="success") {
									$("#shoppingcart").html(data.shoppingcart);
					                $("#addToCart'.$item->id.'").prop("disabled", true);
					                $("#addToCart'.$item->id.'").addClass("disabled");
					        }}'
                        ), array('id'=>'addToCart'.$item->id, 'class'=>''));
                else echo $item->PurchaseStatus;
                ?>
            </td>
            <td>
                <?php echo CHtml::ajaxLink(Yii::t('global','Edit'),array('wishlist/edititem'),
                    array(
                        'type' => 'POST',
                        'dataType'=>'json',
                        'success'=>'js:function(data){
				                    $("#WishlistEditForm_qty").val(data.qty);
				                    $("#WishlistEditForm_qty_received").val(data.qty_received);
				                    $("#WishlistEditForm_priority").val(data.priority);
				                    $("#WishlistEditForm_comment").val(data.comment);
				                    $("#WishlistEditForm_code").val(data.code);
				                    $("#WishlistEditForm_id").val(data.id);
		                            $("#WishitemEdit").dialog("open");
									}',
                        'data'=>array('code'=>$model->gift_code,'id'=>$item->id)
                    ), array('id'=>'editItem'.$item->id, 'class'=>'editwish'));
                ?>
            </td>


		<?php endif; ?>
	<?php endforeach; ?>
            </tbody>
        </table>
    </div>

	<?php if (count($model->wishlistItems)==0): ?>
	    <div class="col-sm-10">
		    <?php if ($model->IsMine)
		        echo Yii::t('wishlist','Your Wish List is empty. To add items, you can click Add To Wish List while viewing any product.');
		    else
				echo Yii::t('wishlist','This Wish List is empty.');
		    ?>
	    </div>
	<?php endif; ?>

	<div class="clearfix spaceafter"></div>

	<?php if ($model->Sharable && $model->IsMine): ?>
		<div class="ten columns wishshare">
		    <?php echo Yii::t('wishlist','You can share this wish list with anyone using the URL: {url}',
		               array('{url}'=>"<br>".Yii::app()->createAbsoluteUrl('wishlist/view',array('code'=>$model->gift_code)))); ?>
	    </div>
	<?php endif; ?>

</div>
<?php
/* This is our modal edit box, which remains hidden until used */
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'WishitemEdit',
	'options'=>array(
		'title'=>Yii::t('wishlist','Edit Wish List Item'),
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'380',
		'height'=>'390',
		'scrolling'=>'no',
		'resizable'=>false,
		'position'=>'center',
		'draggable'=>false,
	),
));
echo $this->renderPartial('_edititem', array('model'=>$formmodel));
$this->endWidget('zii.widgets.jui.CJuiDialog');

/* This is our sharing box, which remains hidden until used */
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'WishitemShare',
	'options'=>array(
		'title'=>Yii::t('wishlist','Share my Wish List'),
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'380',
		'height'=>'430',
		'scrolling'=>'no',
		'resizable'=>false,
		'position'=>'center',
		'draggable'=>false,
	),
));
$this->renderPartial('_sharelist', array('model'=>$WishlistShare));
$this->endWidget('zii.widgets.jui.CJuiDialog');