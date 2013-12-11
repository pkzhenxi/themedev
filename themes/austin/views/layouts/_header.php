    <div id="headerrow">
        <div class="container">
            <div  class="row">
                <div id="headerimage" class="col-sm-4">
                    <?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE'),'',array('class'=>'img-responsive')), Yii::app()->baseUrl."/"); ?>
                </div>
                <div id="langmenu" class ="col-sm-2">
                    <?php
                    if(_xls_get_conf('LANG_MENU',0)):
                        $this->widget('application.extensions.'._xls_get_conf('PROCESSOR_LANGMENU').'.'._xls_get_conf('PROCESSOR_LANGMENU'));
                    endif;
                    ?>
                </div>

        <div id="acctbuttons" class="col-xs-6 col-sm-offset-1 col-sm-3">
            <?php if(Yii::app()->user->isGuest): ?>
<!--                --><?php //echo CHtml::ajaxLink(Yii::t('global','Login'),array('site/login'),
//                    array('onClick'=>'js:jQuery($("#LoginForm")).dialog("open")'),
//                    array('id'=>'btnLogin', 'class'=>'btn btn-link navbar-btn')); ?>

                    <a data-target="#LoginForm" data-toggle="modal" href="#" class="btn btn-link">Login</a>
                    <?php echo $this->renderPartial('/site/_login',array('model'=>new LoginForm())) ?>


                <?php echo CHtml::link(Yii::t('global', 'Register'),_xls_site_url('myaccount/edit'),array('class'=>'btn btn-link navbar-btn'));?>
            <?php else: ?>
                <?php echo CHtml::link(Yii::app()->user->firstname." (My Account)", array('/myaccount'),array('class'=>'btn btn-link navbar-btn')); ?>
              <i class="icon-user"></i>
                <?php echo CHtml::link(Yii::t('global', 'Logout'), array("site/logout"),array('class'=>'btn btn-link navbar-btn')); ?>
            <?php endif; ?>
        </div>

        <div id="findwishlist" class="col-sm-1">

            <?php if(_xls_get_conf('ENABLE_WISH_LIST')):
                //if(Yii::app()->user->isGuest):
                    echo CHtml::link(Yii::t('global','Find a Wish List'),Yii::app()->createUrl('wishlist/search'),array('class'=>'btn btn-link navbar-btn pull-right'));
                endif;
            ?>
        </div>

            <!-- To create a wishlist/order lookup dropdown -->
<!--            <div class="btn-group">-->
<!--                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">-->
<!--                    Action <span class="caret"></span>-->
<!--                </button>-->
<!--                <ul class="dropdown-menu" role="menu">-->
<!--                    <li><a href="#">Action</a></li>-->
<!--                    <li><a href="#">Another action</a></li>-->
<!--                    <li><a href="#">Something else here</a></li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li><a href="#">Separated link</a></li>-->
<!--                </ul>-->
<!--            </div>-->

            <div id="shoppingcartnav" class="col-sm-1">
                <?php
                    echo CHtml::link('Cart: '.Yii::app()->shoppingcart->cartQty,$this->CreateUrl("cart/index"),array("class"=>"btn btn-default navbar-btn"));
                ?>
            </div>

        </div>
    </div>
</div>