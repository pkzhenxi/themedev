 $(".search-toggle").click(function () {
      $(".search-form").toggleClass('active');
  });
  
    $(document.body).mousedown(function(event) {
        var target = $(event.target);
        if (!target.parents().andSelf().is('.search-form')) { // Clicked outside
            $(".search-form").removeClass('active');
        }
    });
	
$(".search-form").click(function(e) { // Wont toggle on any click in the div
e.stopPropagation();
});



 $(".mobile-nav-navigate").click(function () {
      $("nav.navigation").toggleClass('active');
  });
  