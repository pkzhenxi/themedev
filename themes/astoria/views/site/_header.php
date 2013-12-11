


<div class="header-wrapper  ">
  <header class="main-header clearfix">

    <!-- Store logo / title
    =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    <div class="branding">
        <h1 class="logo-image">

         <?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE')), Yii::app()->baseUrl."/", array('class' => 'regular-logo')); ?> 
  </h1>
      
    </div>

    <div class="mobile-nav">
      <span class="mobile-nav-item mobile-nav-navigate" data-toggle="dropdown"><i class="fa fa-bars"></i></span>
      <a class="mobile-nav-item mobile-nav-cart" href="<?php echo Yii::app()->baseUrl."/cart/"; ?>"><i class="fa fa-shopping-cart"></i></a>
      <a class="mobile-nav-item mobile-nav-account" href="<?php echo _xls_site_url('/myaccount/edit'); ?>"><i class="fa fa-user"></i></a>
    
        <?php if(Yii::app()->user->isGuest): ?>
        
                  <?php echo CHtml::ajaxLink(Yii::t('global','<i class="fa fa-sign-in"></i>'),array('site/login'),
					array('onClick'=>'js:jQuery($("#LoginForm")).dialog("open")'),
					array('id'=>'customer_login_small','class' => 'mobile-nav-item')); ?>
        
        
          <?php else: ?>
      <?php echo CHtml::link(Yii::t('global', '<i class="fa fa-sign-out"></i>'), array("site/logout"),
					array('id'=>'customer_login_small','class' => 'mobile-nav-item')); ?> 
          <?php endif; ?>
       
       
       
       
       
       
       
      <span class="mobile-nav-item mobile-nav-search search-toggle"><i class="fa fa-search"></i></span>
      <form method="get" action="<?php echo _xls_site_url('/search/results'); ?>" class="search-form mobile-search-form">
        <input type="text" value="" placeholder="Search" name="q" class="search-input">
      </form>
      
     
    </div>



<nav class="navigation dropdown" >




		<?php $this->widget('application.extensions.wsmenu.wsmenu', array(
			'categories'=> $this->MenuTree,
			'menuheader'=> Yii::t('global','Products'),
			'showarrow'=>true,
			'htmlOptions'=>array('class'=>'horizontal unstyled clearfix')
		)); //products dropdown menu ?>




    <!-- Main navigation
    =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    <?php if (count(CustomPage::model()->toptabs()->findAll()))
				$this->widget('zii.widgets.CMenu', array(
				'id'=>'main-menu',
				// 'itemCssClass'=>'menutab'.round(12/count(CustomPage::model()->toptabs()->findAll())),
				'items'=>CustomPage::model()->toptabs()->findAll(),
				'htmlOptions'=>array('class'=>'clearfix')
			)); ?>
            </nav>








    <!-- Action links
    =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= -->
    <div class="action-links" style="top: 50px; visibility: visible;">
    
    
    
      <form method="get" action="<?php echo _xls_site_url('/search/results'); ?>" class="search-form">
        <input type="text" value="" placeholder="Search" name="q" class="search-input">
      </form>
      
      
      
      
        <?php if(Yii::app()->user->isGuest): ?>
          <?php echo CHtml::link(Yii::t('global','<i class="fa fa-user"></i> Register'),array('myaccount/edit'),
					array('id'=>'customer_register_link'),
					array('id'=>'customer_register_link')); ?>
                     / 
          <?php echo CHtml::ajaxLink(Yii::t('global','Login'),array('site/login'),
					array('onClick'=>'js:jQuery($("#LoginForm")).dialog("open")'),
					array('id'=>'customer_login_link')); ?>
          <?php else: ?>
          <?php echo CHtml::link(Yii::t('global','<i class="fa fa-user"></i> ').Yii::app()->user->firstname, array('/myaccount')); ?>
           / 
                    <?php echo CHtml::link(Yii::t('global', 'Logout'), array("site/logout"),
					array('id'=>'customer_login_link')); ?>
          <?php endif; ?>
      
      
    <a href="<?php echo Yii::app()->baseUrl."/cart/"; ?>">  <span class="mini-cart-toggle">
 <?php echo Yii::t('cart','n==1#<i class="fa fa-shopping-cart"></i> Cart ({n})|n>1#<i class="fa fa-shopping-cart"></i> Cart ({n})',Yii::app()->shoppingcart->totalItemCount); ?>
  </span></a>

      <span class="search-toggle"><i class="fa fa-search"></i> Search</span>

      
    </div>

  </header>

  
</div>
