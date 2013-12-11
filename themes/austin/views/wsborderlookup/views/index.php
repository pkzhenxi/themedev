<!--<div class="col-sm-12 clickbar" onclick="$('#OrderLookup').slideToggle('fast');">--><?//= Yii::t('global','Order Lookup')?><!--</div>-->
<!--<div class="containers" id="OrderLookup" style="display:hidden;">-->
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>get_class($this),
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>
<div class="container">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h1>Look up your order</h1>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <?php echo $form->labelEx($model,'emailPhone'); ?>
            <?php echo $form->emailField($model,'emailPhone',array('placeholder'=>'','class'=>'form-control')); ?>
            <?php echo $form->error($model,'emailPhone'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model,'orderId'); ?>
            <?php echo $form->textField($model,'orderId',array('placeholder'=>'','class'=>'form-control')); ?>
            <?php echo $form->error($model,'orderId'); ?>
        </div>
        <div class="modal-footer">
            <?php echo CHtml::submitButton('Search',array('class'=>'btn btn-block btn-primary')); ?>
        </div>
    </div>
</div><!-- form -->

<?php $this->endWidget(); ?>



