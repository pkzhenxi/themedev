<footer>
<div class="footer-wrapper">
      <div class="container">
          <section class="row foot">
              <article class="span3">
                  <strong>Quick Links</strong>
     
     
     
            <?php if (count(CustomPage::model()->bottomtabs()->findAll()))
				$this->widget('zii.widgets.CMenu', array(
				'id'=>'bl',
				// 'itemCssClass'=>'menutab'.round(12/count(CustomPage::model()->bottomtabs()->findAll())),
				'items'=>CustomPage::model()->bottomtabs()->findAll(),
				'htmlOptions'=>array('class'=>'bottom-links')
			)); ?>
        
     
     
              </article>
              <article class="span3">
                  
                  
                   <?php 
$social_url = Yii::app()->theme->config->about_text;  
if (!empty($social_url)) {
echo '<strong>About Us</strong>'.$social_url;
} ?> 
                  
                  
                  
                  
              </article>
              <article class="span3">
                  <strong>Address</strong>
                 
                 
                    
            
                 
                 	<?php
				echo ' <address class="row-fluid"><div class="pull-left clabel"><i class="icon-map-marker"></i></div>
                      <div class="pull-left cdata">'._xls_get_conf('STORE_NAME').'<br>';
				echo _xls_get_conf('STORE_ADDRESS1').'<br>';
				echo _xls_get_conf('STORE_ADDRESS2').'</div></address>';
				echo ' <address class="row-fluid">
                      <div class="pull-left clabel"><i class="icon-phone"></i></div>
                      <div class="pull-left cdata">'._xls_get_conf('STORE_PHONE').'</div></address>';
				echo '<address class="row-fluid">
                      <div class="pull-left clabel"><i class="icon-envelope"></i></div><div class="pull-left cdata"><a href="mailto:'._xls_get_conf('EMAIL_FROM').'">'._xls_get_conf('EMAIL_FROM').'</a></div></address>';
				?>
                 
                 
                 
                 
     
                 
                 
                 
                 
                 
                 
              </article>
              <article class="span3">
                  <strong>Business Hours</strong>
      		<?php
				echo _xls_get_conf('STORE_HOURS');
				?>
                  
    
                
                      
              </article>
          </section>
		  </div>
  </div>
          
          <section class="row-fluid doubleline">
             <div class="container">
              <div class="span7">
                  <!-- make these options in the backend -->
                  <div class="payments amex"></div>
                  <div class="payments mastercard"></div>
                  <div class="payments visa"></div>
                  <div class="payments paypal"></div>
                  
                  
                  
                  
                  <div class="payments ebay"></div>
                  
                  
                  
                  
                  
                  
                  <div class="payments visaelectron"></div>
                  
                  <div class="payments westernunion"></div>
                  
              </div>
            
            	<div id="searchentry" class="span5 customtext">	
                
                
                
                        
      <div class="search pull-right">
        <?php echo $this->renderPartial("/site/_mini_search",array(),true); ?>
        </div>
 
                
                
     
	</div>
            
             </div>
          </section>
  
          <section class="row-fluid social">
            <div class="container">
            <div class="pull-left">&copy; <?= Yii::t('global', 'Copyright') ?> <?= date("Y"); ?> <?= _xls_get_conf('STORE_NAME') ?>. <?= Yii::t('global', 'All Rights Reserved'); ?>.</div>
              <div class="pull-right footer-social">
          <ul class="unstyled clearfix">
          <?php 
$social_url = Yii::app()->theme->config->twitter;  
if (!empty($social_url)) {
echo '<li><a href="'.$social_url.'" title="Twitter" class="icon-social twitter ir">Twitter</a></li>';
} ?>
         <?php 
$social_url = Yii::app()->theme->config->facebook;  
if (!empty($social_url)) {
echo '<li><a href="'.$social_url.'" title="Facebook" class="icon-social facebook ir">Facebook</a></li>';
} ?>
         <?php 
$social_url = Yii::app()->theme->config->googleplus;  
if (!empty($social_url)) {
echo '<li><a href="'.$social_url.'" title="Google+" class="icon-social google ir">Google+</a></li>';
} ?>
         <?php 
$social_url = Yii::app()->theme->config->youtube;  
if (!empty($social_url)) {
echo '<li><a href="'.$social_url.'" title="YouTube" class="icon-social youtube ir">YouTube</a></li>';
} ?>
         <?php 
$social_url = Yii::app()->theme->config->vimeo;  
if (!empty($social_url)) {
echo '<li><a href="'.$social_url.'" title="Vimeo" class="icon-social vimeo ir">YouTube</a></li>';
} ?>
         <?php 
$social_url = Yii::app()->theme->config->instagram;  
if (!empty($social_url)) {
echo '<li><a href="'.$social_url.'" title="Instagram" class="icon-social instagram ir">YouTube</a></li>';
} ?>
         <?php 
$social_url = Yii::app()->theme->config->pinterest;  
if (!empty($social_url)) {
echo '<li><a href="'.$social_url.'" title="Pinterest" class="icon-social pinterest ir">Pinterest</a></li>';
} ?>
         <?php 
$social_url = Yii::app()->theme->config->rss_url;  
if (!empty($social_url)) {
echo '<li><a href="'.$social_url.'" title="RSS Feed" class="icon-social atom ir">RSS Feed</a></li>';
} ?>       
          </ul>
  
              </div>
              </div>
          </section>

  </footer>
  

<script src="<?=Yii::app()->theme->baseUrl."/js/custom.js" ?>"></script> 
