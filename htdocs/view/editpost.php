<?php
require_once "model/postmodel.php";
require_once "controller/postcontroller.php";

if(!isset($postmdl)) $postmdl = new PostModel($db);
if(!isset($postctrl)) $postctrl = new PostController($postmdl);

$p = $postmdl->getPost($_GET["id"])[0]; // Current post
$postctrl->invoke(); // Checks for submits
?>

<div id="submit-post-form">
  <?php if(!isset($_SESSION["user_id"]) && $_SESSION["user_id"] != $p->creator) : ?>
    <!-- User not logged in, not allowed to submit -->
    <p>You need to be signed in as the user who created this post to edit it.</p>
  <?php else : ?>
    <!-- Submit post form -->
    <form action="" method="post">
      <input type="hidden" name="post_id" value="<?= $p->id ?>" />
      <input type="text" name="post_subject" placeholder="Post subject" value="<?= $p->subject ?>" required /><br />
      <textarea name="post_content" placeholder="Post content" required /><?= $p->content ?></textarea><br />
      <p>Tip: Use markdown to format your post! (HTML-tags will be removed). Post thumbnail will be found automagically!</p>
      <div id="submit-post-options">
        <input type="submit" name="post_edit_submit" value="Edit post" class="button" />
      </div>
    </form>
  <?php endif; ?>
</div>
