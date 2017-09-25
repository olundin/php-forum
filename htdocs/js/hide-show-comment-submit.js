// This function is called when the user clicks "reply" on any comment.
// This shows/hides the hidden reply form form that comment

function showCommentSubmit(cmtId) {
  if(!$(".cmtsbmt" + cmtId).is(":visible")) {
    $(".cmtsbmt" + cmtId).show();
  }
  else if($(".cmtsbmt" + cmtId).is(":visible")) {
    $(".cmtsbmt" + cmtId).hide();
  }
}
