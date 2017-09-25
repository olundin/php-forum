<?php

/* USER CLASS */
class UserModel {

  private $db;

  function __construct($db) {
    $this->db = $db;
  }

  // USER MANAGEMENT
  public function getUser($user) {
    $sql =
    "SELECT
      u.user_id AS id,
      u.user_name AS name,
      u.user_email AS email,
      u.user_date AS date,
      u.user_score AS score,
      (SELECT img FROM userimg WHERE img_user = user_id LIMIT 1) AS img,
      (SELECT COUNT(*) FROM post WHERE post_creator = u.user_id) AS num_posts,
      (SELECT COUNT(*) FROM comment WHERE comment_creator = u.user_id) AS num_comments
    FROM user AS u
    WHERE u.user_id = $user
    LIMIT 1";

    return $this->db->select($sql)[0]; // Returns user as object
  }

  // SESSION HANDLING
  public function signIn($username, $password) {
    $username = $this->db->quote($username);
    $password = $this->db->quote($password);

    $user = $this->db->select("SELECT user_id AS id, user_name AS name FROM user WHERE user_name LIKE $username AND user_password LIKE $password LIMIT 1");
    if($user) {
      // Selection returned row. Credentials correct.
      if (session_status() == PHP_SESSION_NONE)
        session_start();
      $_SESSION["user_id"] = $user[0]->id;
      return true;
    } else {
      return false;
    }
  }

  public function signUp($username, $password, $email) {
    $username = $this->db->quote($username);
    $password = $this->db->quote($password);
    $email = $this->db->quote($email);
    $date = $this->db->quote(date('Y-m-d'));

    $duplicate = 0; // For remembering sign up errors

    if(count($this->db->select("SELECT user_name FROM user WHERE user_name = $username LIMIT 1")) > 0)
      $duplicate = 1;
    if(count($this->db->select("SELECT user_email FROM user WHERE user_email = $email LIMIT 1")) > 0)
      $duplicate = 2;

    if($duplicate == 0) {
      $newUser = $this->db->insert(
        "INSERT INTO user(user_name, user_password, user_email, user_date)
        VALUES($username, $password, $email, $date)"
      );
      if($newUser) {
        $this->signIn($username, $password, $email); // Sign in new user
        return true;
      } else {
        return false;
      }
    } else {
      return $duplicate; // For error printing
    }
  }

  public function signOut() {
    if (session_status() == PHP_SESSION_NONE)
      session_start();
    unset($_SESSION['user_id']);
    session_destroy();
    return true;
  }

  // UPDATE: USER
  public function updateUser($name, $email, $img, $password_old, $password_new, $password_new_repeat) {
    $name = $this->db->quote($name);
    $email = $this->db->quote($email);

    if(!empty($img)) $this->updateUserImg($_SESSION["user_id"], $img); // Change user image if URL is set

    // Password check
    if(!empty($password_old) && !empty($password_new) && !empty($password_new_repeat)) {
      // User probably wants to change password
      $password_old = $this->db->quote($password_old);
      $password_new = $this->db->quote($password_new);
      $password_new_repeat = $this->db->quote($password_new_repeat);
      $cur_password = $this->db->quote($this->db->select(
        "SELECT user_password FROM user WHERE user_id = ".$_SESSION["user_id"]
      )[0]->user_password);
      if($cur_password != $password_old) {
        echo "Password incorrect!";
        return false;
      } else if($password_new != $password_new_repeat) {
        echo "Your passwords must match!";
        return false;
      } else {
        // Password can be changed
        $this->db->update(
          "UPDATE user SET user_password = $password_new WHERE user_id = ".$_SESSION["user_id"]
        );
      }
    }
    // Change email and username (will always happen)
    $this->db->update(
      "UPDATE user SET user_name = $name, user_email = $email WHERE user_id = ".$_SESSION["user_id"]
    );
    return true;
  }

  public function updateUserImg($user, $url) {
    $this->db->delete(
      "DELETE FROM userimg WHERE img_user = '$user'"
    );
    $this->db->insert(
      "INSERT INTO userimg(img_id, img, img_user) VALUES(NULL, '".mysql_real_escape_string(file_get_contents($url))."', '$user')"
    );
  }
}

?>
