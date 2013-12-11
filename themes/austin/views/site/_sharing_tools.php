<div id="sharingtools" class="row">

    <div id="pinterest" class="col-sm-2">
        <a href="//www.pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.flickr.com%2Fphotos%2Fkentbrew%2F6851755809%2F&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest"
           data-pin-do="buttonPin" data-pin-config="above">
            <img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" />
        </a>
    </div>

    <div class="g-plusone col-sm-1" data-size="tall" data-annotation="none" data-width="50"></div>

    <?php if (_xls_facebook_login()): ?>
        <script>(function (d) {
            var js, id = 'facebook-jssdk';
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement('script');
            js.id = id;
            js.async = true;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1<?= "&appId="._xls_get_conf('FACEBOOK_APPID'); ?>";
            d.getElementsByTagName('head')[0].appendChild(js);
            }(document));
        </script>

        <div class="fb-like col-sm-offset-1 col-sm-2"
             data-href="<?= $this->getCanonicalUrl(); ?>" data-send="false" data-layout="button_count"
             data-width="90" data-show-faces="false" style="vertical-align:top;zoom:1;*display:inline">
        </div>

    <?php endif; ?>

    <a href="https://twitter.com/share" class="twitter-share-button" data-size="medium">Tweet</a>

    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

</div>