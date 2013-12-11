<?php
/**
* Created by Shannon Curnew
* Date: 9/21/13
* Time: 2:28 PM
 */
?>
<div id="login-header" class="container">

    <div id="headerimage" class="col-xs-12 col-sm-offset-3 col-sm-6 center-block">
        <?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE'),'Home',array('class'=>'img-responsive')), Yii::app()->baseUrl."/"); ?>
    </div>

        <div id="acctbuttons" class="pull-right col-sm-3 col-xs-12">
            <?php if(Yii::app()->user->isGuest): ?>
                <a  data-toggle="modal" href="#loginModal" class="btn btn-link hidden-xs col-sm-offset-3 col-sm-3">Login</a>
                <div id="xs-login-btn" class="visible-xs">
                    <a data-toggle="modal" href="#loginModal" class="btn btn-block btn-primary col-xs-12 h2">Login</a>
                </div>
                <div class="modal fade" id="loginModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="container">
                                <div class="modal-header">
                                    <div class="row">
                                        <button type="button" class="close btn btn-large" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h1>Login</h1>
                                    </div>
                                </div>
                                    <?php echo $this->renderPartial('/site/_login',array('model'=>new LoginForm())) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php echo CHtml::link(Yii::t('global', 'Register'),_xls_site_url('myaccount/edit'),array('class'=>'btn btn-link hidden-xs col-sm-3'));?>
                <?php echo CHtml::link(Yii::t('global', 'Register'),_xls_site_url('myaccount/edit'),array('class'=>'btn btn-block btn-default visible-xs col-xs-12'));?>

            <?php else: ?>
                <?php echo CHtml::link(Yii::app()->user->firstname." (My Account)", array('/myaccount'),array('class'=>'btn btn-link')); ?>
                <i class="icon-user"></i> <!--  CHtml::image(Yii::app()->user->profilephoto)-->
                <?php echo CHtml::link(Yii::t('global', 'Logout'), array("site/logout"),array('class'=>'btn btn-link')); ?>
            <?php endif; ?>
        </div>

</div>



