<!-- HEADER: logo, store info & social buttons, search bar, login, cart dropdown -->

<div class="container" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="col-xs-6">
            <?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE')), Yii::app()->baseUrl."/"); ?>
        </div>

        <div class="col-xs-3" style="font-family: NexusLight;">
            <ul class="nav social">
                <?php if (_xls_get_conf('SOCIAL_FACEBOOK')) echo '<li><a href="'._xls_get_conf('SOCIAL_FACEBOOK').'" id="fbbtn"></a></li>';
                      if (_xls_get_conf('SOCIAL_LINKEDIN')) echo '<li><a href="'._xls_get_conf('SOCIAL_LINKEDIN').'" id="libtn"></a></li>';
                      if (_xls_get_conf('SOCIAL_PINTEREST')) echo '<li><a href="'._xls_get_conf('SOCIAL_PINTEREST').'" id="pibtn"></a></li>';
                      if (_xls_get_conf('SOCIAL_TWITTER')) echo '<li><a href="'._xls_get_conf('SOCIAL_FACEBOOK').'" id="twbtn"></a></li>';
                      if (_xls_get_conf('SOCIAL_INSTAGRAM')) echo '<li><a href="'._xls_get_conf('SOCIAL_INSTAGRAM').'" id="igbtn"></a></li>';
                ?>
            </ul>
            <span class="glyphicon glyphicon-phone-alt"></span><?php echo ' '._xls_get_conf('STORE_PHONE'); ?>
            <br><span class="glyphicon glyphicon-envelope"></span><?php echo ' <a href="mailto:'._xls_get_conf('EMAIL_FROM').'">'._xls_get_conf('EMAIL_FROM').'</a>'; ?>
            <br><?php if(_xls_get_conf('LANG_MENU',0)): ?>
                <div id="langmenu">
                    <?php $this->widget('application.extensions.'._xls_get_conf('PROCESSOR_LANGMENU').'.'._xls_get_conf('PROCESSOR_LANGMENU')); ?>
                </div>
            <?php endif; ?>
        </div>


        <div class="col-xs-3" style="text-align: right">
	        <?php echo $this->renderPartial("/site/_search",array(),true); ?>
	        <?php if(!Yii::app()->user->isGuest): ?>
		        <p style="text-align: center; margin-bottom: 0px; font-family: NexusLight">
		        <?php echo 'Welcome back '.CHtml::link(Yii::app()->user->firstname, array('/myaccount')); ?>
		        </p>
	        <?php else: ?><p></p>
	        <?php endif; ?>
                <table border="0">
                    <tr>
                        <td style="text-align: left; width: 50%; font-family: NexusLight;">

                                <?php if(Yii::app()->user->isGuest): ?>
                                     <?php echo CHtml::link('<img src="/themes/glencoe/css/images/login.png" width="25%">&nbsp;&nbsp;'.Yii::t('global', strtoupper('Login')), array("site/login")); ?>
                                <?php else: ?>
                                    <?php echo CHtml::link('<img src="/themes/glencoe/css/images/logout.png" width="25%">&nbsp;&nbsp;'.Yii::t('global', strtoupper('Log out')), array("site/logout")); ?>
                                <?php endif; ?>
                        </td>
                        <td style="text-align: left; font-family: NexusLight;">
                            <div class="btn-group">
                                <button class="btncart" data-toggle="dropdown">
                                    <img src="/themes/glencoe/css/images/carticon.png" width="40%">CART <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu" id="shoppingcart">
                                    <li style="padding-left: 10px; padding-top: 5px;">
                                        <?php if (count(Yii::app()->shoppingcart->cartItems)==0)
                                            echo Yii::t('cart','Your cart is empty');
                                            else { ?>
                                                <table>
                                                    <?php $model = Yii::app()->shoppingcart;
                                                    foreach ($model->cartItems as $item) { ?>
                                                        <tr>
                                                            <td class="cartdropdown" id="cartqty-dropdown"><?= $item->qty ?></td>
                                                            <td class="cartdropdown" id="cartdesc-dropdown">
                                                                <?= _xls_truncate($item->description, 65, "...\n", true) ?>
                                                            </td>
                                                            <td class="cartdropdown" id="cartsell-dropdown"><?= _xls_currency($item->sell) ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td id="cartlabel-dropdown">
                                                            <?= Yii::t('cart','Subtotal'); ?>
                                                        </td>
                                                        <td id="cartsubtotal-dropdown">
                                                            <?= _xls_currency($model->subtotal); ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                        <?php } ?>
                                    </li>
                                    <li class="divider"></li>
                                    <li id="cartlink-dropdown"><?php echo CHtml::link(Yii::t('cart','Edit Cart'),array('/cart')) ?></li>
                                    <li id="cartlink-dropdown"><?php echo CHtml::link(Yii::t('cart','Checkout'),array('cart/checkout')); ?></li>

                                </ul>
                            </div>

                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>


<!-- END HEADER -->