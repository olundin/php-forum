<?php
require_once "model/usermodel.php";
require_once "model/postmodel.php";
require_once "model/commentmodel.php";

require_once "controller/usercontroller.php";

if(!isset($usermdl)) $usermdl = new UserModel($db);
if(!isset($postmdl)) $postmdl = new PostModel($db);
if(!isset($commdl)) $commdl = new CommentModel($db);
if(!isset($userctrl)) $userctrl = new UserController($usermdl);

$u = $usermdl->getUser($_GET["id"]);
$postlist = $postmdl->listFromUser($_GET["id"]);
$comlist = $commdl->listFromUser($_GET["id"]);

$userctrl->invoke();
?>
<?php if(isset($_SESSION["user_id"]) && $_GET["id"] == $_SESSION["user_id"]) : ?>
  <div id="user-info">
    <img class="user-img" src="<?= !empty($u->img) ? "data:image;base64,".base64_encode($u->img) : "/img/profile-picture-default.jpg" ?>" alt="Profile picture" />
    <br />
    <table id="user-info-table">
      <form action="" method="post">
        <tr><td>Username:</td><td><input type="text" name="user_name" value="<?= $u->name ?>" placeholder="Username" required></td></tr>
        <tr><td>Email:</td><td><input type="email" name="user_email" value="<?= $u->email ?>" placeholder="Email" required></td></tr>
        <tr><td>Profile picture:</td><td><input type="text" name="user_img" placeholder="URL to image"></td></tr>
        <tr><td>Old password:</td><td><input type="password" name="user_password_old" placeholder="Old password"></td></tr>
        <tr><td>New password:</td><td><input type="password" name="user_password_new" placeholder="New password"></td></tr>
        <tr><td>Repeat password:</td><td><input type="password" name="user_password_new_repeat" placeholder="Repeat password"></td></tr>
        <tr><td></td><td><input type="submit" name="edit_user_submit" value="Save changes" /></td></tr>
      </form>
    </table>
    <br />
  </div>
<?php else: ?>
  <p>You must be logged in as the user you want to edit to edit it!</p>
<?php endif; ?>
