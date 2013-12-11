var $masonry = $('.masonry');
var navigationTopOffset;
var navigationHeight;

jQuery(window).load(function(){
  
  
  navigationTopOffset = $('#nav').offset().top;
  navigationHeight = $('nav.main').height();
  
 
  
});

jQuery(document).ready(function($){
  


  $("nav.mobile select").change(function(){ window.location = jQuery(this).val(); });

  
  navigationTopOffset = $('#nav').offset().top;
  $(window).scroll(function(){
    navigationHeight = $('nav.main').height();
    navigationHeight += 20;
    if($(window).scrollTop() > navigationTopOffset){
      $('body').addClass('fixed-navigation');
      $('body').css('padding-top', navigationHeight + 'px');
    } else {
      $('body').removeClass('fixed-navigation');
      $('body').css('padding-top', '0');
    }
  });
  
}); // end document ready