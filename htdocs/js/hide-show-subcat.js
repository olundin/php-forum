$(document).ready(function() {
  // Hide / show subcategories on hover.
  $("#category-list li").hover(
    function(){
      $(this).children("ul").stop(true, true).slideDown("fast");
    },
    function(){
      $(this).children("ul").stop(true, true).slideUp("fast");
    }
  );
});
