<?php

class CommentController {

  private $model;

  function __construct($model) {
    $this->model = $model;
  }

  public function invoke() {
    if(isset($_POST["comment_submit"])) {
      if(!isset($_SESSION["user_id"])) {
        echo "You must be signed in to comment";
      } else {
        $newComment = $this->model->insertComment($_POST["comment_content"], $_SESSION["user_id"], $_POST["comment_post"], $_POST["comment_parent"]);

        if($newComment) {
          echo "Comment submitted successfully!";
          header("Refresh:0");
        } else {
          echo "Comment submission failed";
        }
      }
    }
    if(isset($_POST["comment_delete_submit"])) {
      $deletedComment = $this->model->deleteComment($_POST["comment_id"]);
      if($deletedComment) {
        echo "Comment deleted successfully!";
        header("Refresh:0");
      } else {
        echo "Comment deletion failed";
      }
    }
  }
}

?>
