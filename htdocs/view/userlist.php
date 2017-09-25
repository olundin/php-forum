<?php
require_once "model/usermodel.php";
require_once "controller/usercontroller.php";

$usermdl = new UserModel($db);
$userctrl = new UserController($usermdl);

$userctrl->invoke();

if(!isset($_SESSION["user_id"])) :
?>
  <!-- Login form -->
  <form action="" method="post" class="login-form">
    <input type="text" name="user_name" placeholder="Username" required /><br />
    <input type="password" name="user_password" placeholder="Password" required /><br />
    <input type="submit" name="signin_submit" value="Sign in" /><br />
  </form>
  <!-- Register form -->
  <form action="" method="post" class="register-form">
    <input type="text" name="user_name" placeholder="Username" required /><br />
    <input type="password" name="user_password" placeholder="Password" required /><br />
    <input type="email" name="user_email" placeholder="Email" required /><br />
    <input type="submit" name="signup_submit" value="Sign up" /><br />
  </form>
<?php else :
  $u = $usermdl->getUser($_SESSION["user_id"]);
?>
  <!-- User info and shortcuts listed here -->
  <img class="user-img" src="<?= !empty($u->img) ? "data:image;base64,".base64_encode($u->img) : "/img/user-img-def.png" ?>" alt="Profile picture" />
  <p class="user-name"><?= $u->name ?></p>
  <p class="user-score">Score: <?= $u->score ?></p>
  <a href="/?view=user&id=<?= $u->id ?>" class="button orange"/>My profile</a>
  <form action="" method="post">
    <input type="submit" name="signout_submit" value="Sign out" class="button red"/>
  </form>
  <a href="/?view=submitpost" class="button green">Submit post</a>
<?php endif; ?>
