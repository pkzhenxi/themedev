$(document).ready(function () {
	$('.mobile-menu-btn').click(function () {
		$(".col1,.col2").toggleClass("show");
		$("#container").toggleClass("noverflow");
	});
});