<?php

/*
  CONTROLLER STRUCTURE

  The controller is very basic. It decides what the index
  should display based on GET variables in the URL. The
  first one, called [x] determines what page the user is
  on (Front page, category, subcategory, post or user).
  This determines what view should be seen in the center
  of the page.

  GET variables after that are used to determine what
  data the view should present (what category, user e.t.c.).
*/

/* CONTROLLER CLASS */
class Controller {

  public function invoke() {
  }

  public function getView() {
    if(isset($_GET["view"])) {
      if($_GET["view"] == "category") return "view/postlist.php";
      else if($_GET["view"] == "subcategory") return "view/postlist.php";
      else if($_GET["view"] == "user") return "view/user.php";
      else if($_GET["view"] == "edituser") return "view/edituser.php";
      else if($_GET["view"] == "post") return "view/post.php";
      else if($_GET["view"] == "submitpost") return "view/submitpost.php";
      else if($_GET["view"] == "editpost") return "view/editpost.php";
      else return "view/error.php";
    }
    else return "view/postlist.php";
  }
}

?>
