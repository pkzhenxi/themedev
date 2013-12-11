<div class="container">
  <header class="row" id="header">
    <div class="span12 clearfix">
      <div class="logo"> <?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE')), Yii::app()->baseUrl."/"); ?> </div>
      <!-- /.logo -->
      <div class="cart-summary"> <a class="clearfix" href="<?php echo Yii::app()->baseUrl."/cart/"; ?>">
        <div class="icon">View cart</div>
        <div class="details"> <?php echo Yii::t('cart','n==1#<span class="note">Your Cart</span> <span class="item-count">{n} item</span>|n>1#<span class="note">Your Cart</span> <span class="item-count">{n} items</span>',Yii::app()->shoppingcart->totalItemCount); ?> </div>
        <!-- /.details --> 
        </a> </div>
      <!-- /.cart-summary --> 
    </div>
    <!-- /.span12 --> 
    
    
    
    
    
  </header>
  <section class="row" id="nav">
    <div class="span12">
      <nav class="main clearfix">
        <?php $this->widget('application.extensions.wsmenu.wsmenu', array(
					'categories'=> $this->MenuTree,
					'menuheader'=> Yii::t('global','Products'),
					'showarrow'=>false,
					'htmlOptions'=>array('class'=>'horizontal unstyled clearfix')
				)); //products dropdown menu ?>
        <?php if (count(CustomPage::model()->toptabs()->findAll()))
				$this->widget('zii.widgets.CMenu', array(
				'id'=>'main-menu',
				// 'itemCssClass'=>'menutab'.round(12/count(CustomPage::model()->toptabs()->findAll())),
				'items'=>CustomPage::model()->toptabs()->findAll(),
				'htmlOptions'=>array('class'=>'horizontal unstyled clearfix pull-left')
			)); ?>
        <ul class="horizontal unstyled clearfix pull-right">
          <?php if(Yii::app()->user->isGuest): ?>
          <li class="fr"><?php echo CHtml::ajaxLink(Yii::t('global','Login'),array('site/login'),
					array('onClick'=>'js:jQuery($("#LoginForm")).dialog("open")'),
					array('id'=>'customer_login_link')); ?></li>
          <li class="fr"><?php echo CHtml::link(Yii::t('global','Register'),array('myaccount/edit'),
					array('id'=>'customer_register_link')); ?></li>
          <?php else: ?>
          <li class="fr"><?php echo CHtml::link(Yii::t('global', 'Logout'), array("site/logout")); ?></li>
          <li class="fr"><?php echo CHtml::link(CHtml::image(Yii::app()->user->profilephoto).Yii::app()->user->firstname, array('/myaccount')); ?></li>
          <?php endif; ?>
          <li id="cart-link" class="show-when-fixed"> <a href="<?php echo Yii::app()->baseUrl."/cart/"; ?>"><?php echo Yii::t('cart','n==1#Your Cart ({n}) item</span>|n>1#Your Cart ({n}) items',Yii::app()->shoppingcart->totalItemCount); ?></a> </li>
        </ul>
      </nav>
      <!-- /.main -->
      <nav class="mobile clearfix">
   <?php
$pages = CHtml::listData(CustomPage::model()->toptabs()->findAll(),'url','title');
// Add extra options here: I am actually prepending with this syntax,
// but you are free to append or interleave instead. Array keys are the values.
$staticabove = array(
    '/'     => Yii::t('sequim','Home'), 
);

$products = CHtml::listData($this->MenuTree,'url','label');


$staticbelow = array(
    'cart' => Yii::t('cart','n==1#Your Cart ({n})|n>1#Your Cart ({n})',Yii::app()->shoppingcart->totalItemCount), 
);


 echo CHtml::dropDownList('listname', '<selected value>',
    $staticabove + $pages + $products + $staticbelow,
    array('empty'=>Yii::t('sequim','(Select a Page)')));
?>

        <ul class="horizontal unstyled clearfix fr">
          <?php if(Yii::app()->user->isGuest): ?>
          <li class="fr"><?php echo CHtml::ajaxLink(Yii::t('global','Login'),array('site/login'),
					array('onClick'=>'js:jQuery($("#LoginForm")).dialog("open")'),
					array('id'=>'customer_login_link')); ?></li>
          <li class="fr"><?php echo CHtml::link(Yii::t('global','Register'),array('myaccount/edit'),
					array('id'=>'customer_register_link')); ?></li>
          <?php else: ?>
          <li class="fr"><?php echo CHtml::link(Yii::t('global', 'Logout'), array("site/logout")); ?></li>
          <li class="fr"><?php echo CHtml::link(CHtml::image(Yii::app()->user->profilephoto).Yii::app()->user->firstname, array('/myaccount')); ?></li>
          <?php endif; ?>
        </ul>
      </nav>
      <!-- /.mobile --> 
    </div>
    <!-- /.span12 --> 
  </section>
</div>
