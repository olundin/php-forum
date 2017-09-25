<?php

class PostController {

  private $model;

  function __construct($model) {
    $this->model = $model;
  }

  public function invoke() {
    if(isset($_POST["post_submit"])) {
      if(!isset($_SESSION["user_id"])) {
        echo "Post submission failed. You need to be signed in to submit posts!";
      //} else if($_POST["post_content"] != strip_tags($_POST["post_content"])) {
        // Compares content with content stripped from tags. If they are not equal, the content contains HTML-tags.
        //echo "<p>Your post contains HTML-tags, which is forbidden. Please use markdown to format your post instead!";
      } else {
        $newPost = $this->model->insertPost($_POST["post_subject"], $_POST["post_content"], $_POST["post_category"], $_SESSION["user_id"]);
        if($newPost) {
          echo "Post submitted successfully!";
          //header("Location: /?view=post&id=".$this->model->lastInsertId());
        } else {
          echo "Post submission failed.";
        }
      }
    }

    if(isset($_POST["post_edit_submit"])) {
      if(!isset($_SESSION["user_id"])) {
        echo "Post edit failed. You need to be signed in to edit your posts!";
      //} else if($_POST["post_content"] != strip_tags($_POST["post_content"])) {
      //  echo "<p>Your post contains HTML-tags, which is forbidden. Please use markdown to format your post instead!";
      } else {
        $editedPost = $this->model->updatePost($_POST["post_id"], $_POST["post_subject"], $_POST["post_content"]);
        if($editedPost) {
          echo "Post edited successfully!";
          header("Location: /?view=post&id=".$_POST["post_id"]);
        } else {
          echo "Post edit failed.";
        }
      }
    }

    if(isset($_POST["post_delete_submit"])) {
      $deletedPost = $this->model->deletePost($_POST["post_id"]);
      if($deletedPost) {
        echo "Post deleted successfully!";
        header("Location: /");
      } else {
         echo "Post deletion failed";
      }
    }
  }

  public function getPostList() {
    // Returns post list based on current action
    if(isset($_GET["view"])) {
      if($_GET["view"] == "category") return $this->model->listFromCat($_GET["id"]);
      else if($_GET["view"] == "subcategory") return $this->model->listFromSubCat($_GET["id"]);
    }
    else return $this->model->listAll();
  }
}

?>
