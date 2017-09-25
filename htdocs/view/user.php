<?php
require_once "model/usermodel.php";
require_once "model/postmodel.php";
require_once "model/commentmodel.php";

if(!isset($usermdl)) $usermdl = new UserModel($db);
if(!isset($postmdl)) $postmdl = new PostModel($db);
if(!isset($commdl)) $commdl = new CommentModel($db);

// Default to logged in user
if(isset($_GET["id"])) $uid = $_GET["id"];
else $uid = $_SESSION["user_id"];

$u = $usermdl->getUser($uid);
$plist = $postmdl->listFromUser($uid);
$comlist = $commdl->listFromUser($uid);
?>
<div id="user-info">
  <img class="user-img" src="<?= !empty($u->img) ? "data:image;base64,".base64_encode($u->img) : "/img/user-img-def.png" ?>" alt="Profile picture" />
  <table id="user-info-table">
    <tr><td>Username:</td><td><?= $u->name ?></td></tr>
    <tr><td>Email:</td><td><?= $u->email ?></td></tr>
    <tr><td>Joined:</td><td><?= $u->date ?></td></tr>
    <tr><td>Score:</td><td><?= $u->score ?></td></tr>
    <tr><td>Posts:</td><td><?= $u->num_posts ?></td></tr>
    <tr><td>Comments:</td><td><?= $u->num_comments ?></td></tr>
  </table>
  <br />
  <?php if(isset($_SESSION["user_id"]) && $uid == $_SESSION["user_id"]) echo "<a href='/?view=edituser&id=".$_SESSION["user_id"]."'>edit profile</a>"; ?>
</div>
<div id="user-submits">
  <div id="user-posts">
    <span class="user-submits-header">Posts (<?= $u->num_posts ?>)</span>
    <?php foreach($plist as $p) : ?>
      <div class="post">
        <div class="post-content">
          <span class="post-header"><a href="/?view=post&id=<?= $p->id ?>"><?= $p->subject ?></a></span>
          <span class="post-date">Posted on: <time><?= $p->date ?></time></span>
          <p class="post-preview"><?= $p->content ?></p>
        </div>
        <table class="post-data">
          <tr class='post-score'>
            <td>Score:</td>
            <td><?= $p->score ?></td>
          </tr>
          <tr class='post-category'>
            <td>Category:</td>
            <td><a href="/?view=subcategory&id=<?= $p->category ?>"><?= $p->category_name ?></a></td>
          </tr>
          <tr class='post-views'>
            <td>Views:</td>
            <td><?= $p->views ?></td>
          </tr>
          <tr class='post-comments'>
            <td>Comments:</td>
            <td><?= $p->num_comments ?></td>
          </tr>
        </table>
        <img class="post-thumbnail" src="<?= !empty($p->img) ? $p->img : "/img/post-img-def.png" ?>" alt="post-thumbnail" />
      </div>
    <?php endforeach; ?>
  </div>
  <div id="user-comments">
    <span class="user-submits-header">Comments (<?= $u->num_comments ?>)</span>
    <?php foreach($comlist as $c) : ?>
      <div class="comment">
        <span class="comment-data">
          Submitted to <a href="?view=post&id=<?= $c->post ?>">this post</a> at <time><?= $c->date ?></time>
        </span>
        <span class="comment-content">
          <?= $c->content ?>
        </span>
      </div>
    <?php endforeach; ?>
  </div>
</div>
