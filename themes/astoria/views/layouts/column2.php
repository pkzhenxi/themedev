<?php $this->beginContent('//layouts/main'); ?>



<div class="row-fluid">
	<div class="span12">

		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
	        'links'=>$this->breadcrumbs,
			'homeLink'=>CHtml::link('Home', '/site/index', array('class' => 'home-link')),
			'separator'=>' / ',
	        ));	?> <!-- breadcrumbs -->
		<?php $this->widget('bootstrap.widgets.TbAlert', array(
			'block'=>true, // display a larger alert block?
			'fade'=>true, // use transitions?
			'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
			'alerts'=>array( // configurations per alert type
				'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'danger'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
			),
			)); ?><!-- flash messages -->
	    <div id="viewport" class="row-fluid">
		    <?php echo $content; ?>
	    </div>
	</div>


</div>
<script>
$(document).ready(function() {
		//shopping card
		var shoppingcart = $('#shoppingcartwrapper');
		//check to see if cart is full
		if ( $(window).width() > 960) {
			if ($('.minicart_desc a').attr('id') !== 'product_link0'){
//	            $(shoppingcart).css({ height: '380px',width: '300px' });
//	            $(shoppingcart).delay(2500).animate({ height: '32px', width: '40px' }, 'normal', 'linear', 'easein');
	            $(shoppingcart).hover(function(){
						$(this).dequeue().stop().animate({ height: '380px',width: '300px'});
					}, function() {
		                $(this).delay(3000).animate({ height: '32px',width: '40px' }, 'normal', 'linear', 'easein');
					});
			}
		//animation for empty cart
			if ($('.minicart_desc a').attr('id') == 'product_link0'){
				$(shoppingcart).hover(function(){
							$(this).dequeue().stop().animate({ height: '302px',width: '300px' });
					}, function() {
			            $(this).animate({ height: '32px',width: '40px' }, 'normal', 'linear', 'easein');
					});
			}
		}
		//set width of shopping cart div on click
		$('#sidebar .clickbar').click(function(){
			$(shoppingcart).width(300).height(550);
		});

		//add class to register menu div
		$('#login').parent().addClass('loginDiv');
		//menu positioning
		var span2width = $('#menubar .span2 li').width();
		var span7width = $('#menubar .span7').outerWidth();
		//var menuwidth = $(window).width();
		$('#menubar .span7').css('marginLeft', -((-span2width / 1.5) + span7width / 2 ));


		if ( $(window).width() < 1444) {
			$('#menubar .span7').css('marginLeft', -((-span2width / 1.5) + span7width / 2 ));
		}
		$(window).resize(function() {
			if ( $(window).width() < 1444) {
				$('#menubar .span7').css('marginLeft', -((-span2width / 1.5) + span7width / 2 ));
			}
		});
		if ( $(window).width() < 1200) {
			$('#menubar .span7').css('marginLeft', -((-span2width * 1.25) + span7width / 2 ));
		}
		$(window).resize(function() {
			if ( $(window).width() < 1200) {
				$('#menubar .span7').css('marginLeft', -((-span2width * 1.25) + span7width / 2 ));
			}
		});

		//fix body margin on relative positioned menu
		var contentMargin = $('#menubar .span7').height();
		if ( $(window).width() < 961) {
			$('#custom_content').css('marginTop', (contentMargin * 1.5));
		}

		//set background of hovered list

		$('#menubar .span2').hover(function(){
			$('#menubar .dropspace li a').addClass('hoveredList');
			$('#menubar .dropspace li a').animate({backgroundColor: 'rgba(58,61,68,0.7)'});
			}, function() {
    			$('#menubar .dropspace li a').removeClass('hoveredList');
			});

		//add a different than index page to subpages
		if($('#homepage-flag').length < 1) {
    		$('#headerimagebg').addClass('subpage-header');
    		$('#wrapperDiv').addClass('subpagewrapper');
		} else {
			$('#headerimagebg').addClass('index-header');
			$('#wrapperDiv').addClass('subpagewrapper');
		}

		//categories styling
		$('#gridheader').parent().attr('id', 'wrapperDiv');
		$('#checkout').parent().addClass('registerDiv');


		//contact page

		});
</script>

<?php $this->endContent();