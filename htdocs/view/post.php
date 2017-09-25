<!-- Post content and comments shown here -->
<?php
require_once "model/postmodel.php";
require_once "model/commentmodel.php";
require_once "controller/postcontroller.php";
require_once "controller/commentcontroller.php";
require_once "libs/parsedown.php"; // Used for viewing markdown formatted posts

if(!isset($postmdl)) $postmdl = new PostModel($db);
if(!isset($commdl)) $commdl = new CommentModel($db);
if(!isset($postctrl)) $postctrl = new PostController($postmdl);
if(!isset($comctrl)) $comctrl = new CommentController($commdl);

$postctrl->invoke(); // Checks for deletes
$comctrl->invoke(); // Checks for submits

$p = $postmdl->getPost($_GET["id"])[0]; // Current post
$parsedown = new Parsedown();
$p->content = $parsedown->text($p->content);
$comlist = $commdl->listFromPost($_GET["id"]);  // Post comment tree
$postmdl->incrementViewCount($_GET["id"]); // Increment view count
?>

<div id="post">
  <img id="post-thumbnail" src="<?= !empty($p->img) ? $p->img : "/img/post-img-def.png" ?>" alt="post-thumbnail" />
  <div id="post-header">
    <span id="post-subject"><?= $p->subject ?></span>
    <div id="post-data">
      Submitted by: <a href="/?view=user&id=<?= $p->creator ?>"><?= $p->creator_name ?></a> to <a href="/?view=subcategory&id=<?= $p->category ?>"><?= $p->category_name ?></a> at <time><?= $p->date ?></time><br />
      Points: <?= $p->score ?><br />
      Views: <?= $p->views ?><br />
      <?php if(isset($_SESSION['user_id'])) {
        // Allow user to delete post if he/she created it
        if($_SESSION['user_id'] == $p->creator)
          echo "<a href='/?view=editpost&id=$p->id'>edit</a> | <form method='post' action='' class='inline'><input type='hidden' name='post_id' value='$p->id'/ ><input type='submit' name='post_delete_submit' value='delete' class='link' /></form>";
        }
      ?>
    </div>
  </div>
  <div id="post-content">
    <?= $p->content ?>
  </div>
</div>
<div id="comment-section">
  <?php if(isset($_SESSION["user_id"])) : ?>
  <div class="comment-submit">
    <form action="" method="post">
      <input type="hidden" name="comment_parent" value="" />
      <textarea name="comment_content" placeholder="Type your comment here..." required></textarea><br />
      <input type="hidden" name="comment_post" value="<?= $p->id ?>" />
      <input type="submit" name="comment_submit" value="Submit comment" class="button gray" />
    </form>
  </div>
  <?php endif; ?>
  <div id="comment-list">
    <ul>
      <?php
        // Print comment tree
        $data = array();
        $index = array();
        if(count($comlist) > 0) {
          foreach($comlist as $c) {
            $id = $c->id;
            $parentid = $c->parent === NULL ? "NULL" : $c->parent; // Comment's parent. Null if null, else id.
            $data[$id] = $c;
            $index[$parentid][] = $id;
          }
        }
        printCommentBranch($data, $index, NULL, 0);


        function printCommentBranch($data, $index, $parentid, $level) {
          $parentid = $parentid === NULL ? "NULL" : $parentid;
          if(isset($index[$parentid])) {
            foreach($index[$parentid] as $id) {
              // Print comment
              echo
              "<li><div class='comment'>
                <span class='comment-data'>
                  Submitted by <a href='/?view=user&id=".$data[$id]->creator."'>".$data[$id]->creator_name."</a> at <time>".$data[$id]->date."</time>
                </span>
                <span class='comment-content'>".$data[$id]->content."</span>
                <span class='comment-actions'>";
              if(isset($_SESSION['user_id'])) {
                echo "<a href='#' onclick='showCommentSubmit(".$data[$id]->id.")'>reply</a>";
                if($_SESSION['user_id'] == $data[$id]->creator) echo " | <form method='post' action='' class='inline'><input type='hidden' name='comment_id' value='".$data[$id]->id."'/ ><input type='submit' name='comment_delete_submit' value='delete' class='link' /></form>";
              }
              echo "
                </span>
              </div>
              <div class='comment-submit cmtsbmt".$data[$id]->id."' hidden>
                <form action='' method='post'>
                  <input type='hidden' name='comment_parent' value='".$data[$id]->id."' />
                  <textarea name='comment_content' placeholder='Type your comment here...' required></textarea><br />
                  <input type='hidden' name='comment_post' value='".$data[$id]->post."' />
                  <input type='submit' name='comment_submit' value='Submit comment' class='button gray' />
                </form>
              </div><ul>";
              printCommentBranch($data, $index, $id, $level + 1);
              echo "</ul></li>";
            }
          }
        }
      ?>
    </ul>
  </div>
</div>
