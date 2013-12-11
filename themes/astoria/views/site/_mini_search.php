
<form method="get" action="<?php echo _xls_site_url('/search/results'); ?>">
  <input type="text" onfocus="if(this.value==this.defaultValue) this.value='';" value="Search..." class="search-field" id="q" name="q">
  <input type="image" id="seek" onclick="if( this.value == 'Search...' ) {this.value = '';};" name="search-button" class="search-button" src="<?=Yii::app()->baseUrl."/themes/astoria/img/icon-search.gif" ?>">
</form>
