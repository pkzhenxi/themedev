<div class="modal-body">

<?php
/* This is the contents of the modal login dialog box. It's a Render Partial since we don't need the full HTML wrappers */
?>
<?php if(_xls_facebook_login()): ?>
	<?php $this->widget('ext.yii-facebook-opengraph.plugins.LoginButton', array(
		'show_faces'=>false,
		'size'=>'large',
		'text'=> Yii::t('global','Log in with Facebook'),
		'scope' => 'email,user_location,publish_actions',
		'on_login'=>'window.location.href="'.Yii::app()->createUrl('facebook/create').'";',
        'htmlOptions'=>array('class'=>'btn'),
        )
	); ?>
<?php endif; ?>
<div class="login">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>false,
        'focus'=>array($model,'email'),
        'htmlOptions'=>array('role'=>'form'),
        ));   ?>
	    <?php echo $form->errorSummary($model); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->emailField($model,'email',array('placeholder'=>'','class'=>'form-control')); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>
        <div class="form-group">
            <?php echo $form->labelEx($model,'password'); ?>
            <?php echo $form->passwordField($model,'password',array('placeholder'=>'', 'class'=>'form-control','autocomplete'=>"off")); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>

        <div class="row">
            <div class="checkbox form-group col-sm-6">
                <?php echo $form->checkBox($model,'rememberMe',array('class'=>'')); ?>
                <?php echo $form->label($model,'rememberMe'); ?>
                <?php echo $form->error($model,'rememberMe'); ?>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                <?php echo CHtml::ajaxLink(Yii::t('global','Forgot Password?'),
                    array('site/forgotpassword'),
                    array(
                        'type'=>"POST",
                        'data'=>'js:jQuery($("#login-form")).serialize()',
                        'dataType' => 'json',
                        'success'=>'js:function(data) {
			                if (data.status=="success") {
			                    alert(data.message);
			                    $.ajax({url:data.url});
			                } else  alert(data.message);
						}'
                    ),
                    array('class'=>'btn btn-link control-label')
                ); ?>
                </div>
            </div>
        </div>
    </div>
</div>

	         <div class="modal-footer">
                 <?php echo CHtml::ajaxSubmitButton(Yii::t('global','Login'),
					array('site/login'),
					array(
						'type'=>"POST",
						'dataType'=>'json',
						'data'=>'js:jQuery($("#login-form")).serialize()',
						'beforeSend'=>'js:function() {
								$("#submitSpinner").show();
								$("#LoginForm_password_em_").html("").hide();
								$("#LoginForm_email_em_").html("").hide();
								}',
						'success'=>'js:function(data) {
			                if (data.status=="success") {
		                        location.reload();
		                    } else { for(var key in data.errormsg) {
			                    var value = data.errormsg[key];
								$("#LoginForm_"+key+"_em_").html(value).show();
								$("#submitSpinner").hide();
							}}
		                 }',
					),array('id'=>'btnModalLogin','name'=>'btnModalLogin','class'=>'h2 btn btn-block btn-primary')); ?>
<!--	            <span id="submitSpinner" style="display:none">-->
<!--				    --><?php //echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif')?>
<!--	            </span>-->
	        </div>


<?php $this->endWidget(); ?>

