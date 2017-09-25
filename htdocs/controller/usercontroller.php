<?php

class UserController {

  private $model;

  function __construct($model) {
    $this->model = $model;
  }

  public function invoke() {
    if(isset($_POST["signin_submit"])) {
      $signin = $this->model->signIn($_POST["user_name"], $_POST["user_password"]);
      if($signin) {
        header("Refresh:0");
        echo "Signed in successfully!";
      } else {
        echo "Sign in failed";
      }
    }
    if(isset($_POST["signup_submit"])) {
      $signup = $this->model->signUp($_POST["user_name"], $_POST["user_password"], $_POST["user_email"]);

      if($signup === true) echo "Signup successful!";
      else if($signup == 1) echo "Username taken!";
      else if($signup == 2) echo "Email taken!";
      else echo "Signup failed.";
    }
    if(isset($_POST["signout_submit"])) {
      $signout = $this->model->signOut();
      if($signout === true) {
        header("Refresh:0");
        echo "Signed out successfully!";
      }
    }
    if(isset($_POST["edit_user_submit"])) {
      $updatedUser = $this->model->updateUser(
        $_POST["user_name"],
        $_POST["user_email"],
        $_POST["user_img"],
        $_POST["user_password_old"],
        $_POST["user_password_new"],
        $_POST["user_password_new_repeat"]
      );
      if($updatedUser) {
        echo "User updated successfully";
        header("Location: /?view=user");
      } else {
        "User update failed";
      }
    }
  }
}

?>
