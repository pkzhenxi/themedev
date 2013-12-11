<div class="footer-wrapper  module-count-3">
  <footer class="main-footer">
    <div class="links footer-module clearfix">
      <?php if (count(CustomPage::model()->bottomtabs()->findAll()))
				$this->widget('zii.widgets.CMenu', array(
				'id'=>'bl',
				// 'itemCssClass'=>'menutab'.round(12/count(CustomPage::model()->bottomtabs()->findAll())),
				'items'=>CustomPage::model()->bottomtabs()->findAll(),
				'htmlOptions'=>array('class'=>'bottom-links')
			)); ?>
    </div>
    <div class="contact footer-module clearfix">
      <h4>Contact Info</h4>
      <?php
				echo ' <address class="row-fluid"><i class="fa fa-map-marker"></i>
                      '._xls_get_conf('STORE_NAME').'<br>';
				echo _xls_get_conf('STORE_ADDRESS1').'<br>';
				echo _xls_get_conf('STORE_ADDRESS2').'</address>';
				echo ' <address class="row-fluid">
                      <i class="fa fa-mobile"></i>
                      '._xls_get_conf('STORE_PHONE').'</address>';
				echo '<address class="row-fluid">
                      <i class="icon-envelope"></i><a href="mailto:'._xls_get_conf('EMAIL_FROM').'">'._xls_get_conf('EMAIL_FROM').'</a></address>';
				?>
    </div>
    <div class="connect footer-module clearfix">
      <h4>Connect with us</h4>
      <ul class="social-options">
        <li><a target="_blank" href="twitter.com/astoria"><i class="fa fa-twitter"></i></a></li>
        <li><a target="_blank" href="facebook.com/astoria"><i class="fa fa-facebook"></i></a></li>
        <li><a target="_blank" href="pinterest.com/astoria"><i class="fa fa-pinterest"></i></a></li>
        <li><a target="_blank" href="instagram.com/astoria"><i class="fa fa-instagram"></i></a></li>
        <li><a target="_blank" href="/blogs/astoria.atom"><i class="fa fa-rss"></i></a></li>
      </ul>
      <form target="_blank" class="validate mailing-list-signup" name="mc-embedded-subscribe-form" method="post" action="">
        <input type="submit" value="Subscribe" name="subscribe" class="submit">
        <div class="email-container">
          <input type="email" value="" placeholder="Email address" name="EMAIL" class="email">
        </div>
      </form>
    </div>
  </footer>
</div>
<div class="copyright-wrap">
  <div class="copyright">
    <p>&copy;
      <?= Yii::t('global', 'Copyright') ?>
      <?= date("Y"); ?>
      <?= _xls_get_conf('STORE_NAME') ?>
      .
      <?= Yii::t('global', 'All Rights Reserved.'); ?>
    </p>
    <ul class="payment-options">
      <li class="payments visa"></li>
      <li class="payments mastercard"></li>
      <li class="payments amex"></li>
      <li class="payments paypal"></li>
    </ul>
  </div>
</div>
<script src="<?=Yii::app()->theme->baseUrl."/js/custom.js" ?>"></script> 