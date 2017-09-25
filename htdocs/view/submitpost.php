<?php
require_once "model/postmodel.php";
require_once "model/categorymodel.php";
require_once "controller/postcontroller.php";

if(!isset($postmdl)) $postmdl = new PostModel($db);
if(!isset($catmdl)) $catmdl = new CategoryModel($db);
if(!isset($postctrl)) $postctrl = new PostController($postmdl);

$catlist = $catmdl->listAll();
$postctrl->invoke(); // Checks for submits
?>

<div id="submit-post-form">
  <?php if(!isset($_SESSION["user_id"])) : ?>
    <!-- User not logged in, not allowed to submit -->
    <p>Please login or sign up to submit posts.</p>
  <?php else : ?>
    <!-- Submit post form -->
    <form action="" method="post">
      <input type="text" name="post_subject" placeholder="Post subject" required /><br />
      <textarea name="post_content" placeholder="Post content" required /></textarea><br />
      <p>Tip: Use <a target="_blank" href="https://guides.github.com/features/mastering-markdown/">markdown</a> to format your post! (HTML-tags will be removed). Post thumbnail will be found automagically!</p>
      <div id="submit-post-options">
        <select name="post_category">
          <?php
          // Loops once for each category
          foreach($catlist as $c) :
          ?>
            <optgroup label="<?= $c[0]->name ?>">
              <?php foreach($c[1] as $sc) : ?>
                <option value="<?= $sc->id ?>"><?= $sc->name ?></option>
              <?php endforeach; ?>
            </optgroup>
          <?php
          endforeach;
          ?>
        </select>
        <input type="submit" name="post_submit" value="Submit post" class="button gray" />
      </div>
    </form>
  <?php endif; ?>
</div>
