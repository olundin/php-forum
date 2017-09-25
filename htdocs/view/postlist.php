<?php
require_once "model/postmodel.php";
require_once "controller/postcontroller.php";

require_once "libs/parsedown.php"; // Used for viewing markdown formatted posts

if(!isset($postmdl)) $postmdl = new PostModel($db);
if(!isset($postctrl)) $postctrl = new PostController($postmdl);

$parsedown = new Parsedown();
$plist = $postctrl->getPostList($postmdl);
?>

<ul id="category-list">
  <?php
  foreach($plist as $p) :
    $p->content = $parsedown->text($p->content); // Parse markdown
  ?>
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
        <tr class='post_creator'>
          <td>Created by:</td>
          <td><a href="/?view=user&id=<?= $p->creator ?>"><?= $p->creator_name ?></a></td>
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
  <?php
  endforeach;
  ?>
</ul>
