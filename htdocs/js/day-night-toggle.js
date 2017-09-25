$(document).ready(function() {
  $("#day-night-checkbox").change(function() {
    if(this.checked) {
      $("html").css("background-color", "#353535");
      $("html").css("color", "#e8e8e8");
    } else {
      $("html").css("background-color", "#fff");
      $("html").css("color", "#000");
    }
  });
});
