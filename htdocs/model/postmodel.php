<?php

/* POST CLASS */
class PostModel {

  private $db;

  function __construct($db) {
    $this->db = $db;
  }

  // SELECT: POST
  public function getPost($post) {
    $sql =
      "SELECT
        p.post_id AS id,
        p.post_subject AS subject,
        p.post_content AS content,
        p.post_date AS date,
        p.post_category AS category,
        p.post_creator AS creator,
        p.post_score AS score,
        sc.subcategory_name AS category_name,
        u.user_name AS creator_name,
        (SELECT COUNT(*) FROM postview WHERE post = p.post_id) AS views,
        (SELECT COUNT(*) FROM comment WHERE comment_post = p.post_id) AS num_comments,
        (SELECT img_url FROM postimg WHERE img_post = p.post_id LIMIT 1) AS img
      FROM
        post AS p INNER JOIN subcategory AS sc ON p.post_category = sc.subcategory_id INNER JOIN user AS u ON p.post_creator = u.user_id
      WHERE p.post_id = $post LIMIT 1";

    return $this->db->select($sql); // Returns post as object
  }

  // SELECT: POST LIST
  public function listAll() {
    $sql =
      "SELECT
        p.post_id AS id,
        p.post_subject AS subject,
        p.post_content AS content,
        p.post_date AS date,
        p.post_category AS category,
        p.post_creator AS creator,
        p.post_score AS score,
        sc.subcategory_name AS category_name,
        u.user_name AS creator_name,
        (SELECT COUNT(*) FROM postview WHERE post = p.post_id) AS views,
        (SELECT COUNT(*) FROM comment WHERE comment_post = p.post_id) AS num_comments,
        (SELECT img_url FROM postimg WHERE img_post = p.post_id LIMIT 1) AS img
      FROM
        post AS p INNER JOIN subcategory AS sc ON p.post_category = sc.subcategory_id INNER JOIN user AS u ON p.post_creator = u.user_id";

    return $this->db->select($sql); // Returns array of posts
  }
  public function listFromCat($cat) {
    $sql =
      "SELECT
        p.post_id AS id,
        p.post_subject AS subject,
        p.post_content AS content,
        p.post_date AS date,
        p.post_category AS category,
        p.post_creator AS creator,
        p.post_score AS score,
        sc.subcategory_name AS category_name,
        u.user_name AS creator_name,
        (SELECT COUNT(*) FROM postview WHERE post = p.post_id) AS views,
        (SELECT COUNT(*) FROM comment WHERE comment_post = p.post_id) AS num_comments,
        (SELECT img_url FROM postimg WHERE img_post = p.post_id LIMIT 1) AS img
      FROM
        post AS p INNER JOIN subcategory AS sc ON p.post_category = sc.subcategory_id INNER JOIN user AS u ON p.post_creator = u.user_id
      WHERE sc.subcategory_parent = $cat";

    return $this->db->select($sql); // Returns array of posts
  }
  public function listFromSubCat($subcat) {
    $sql =
      "SELECT
        p.post_id AS id,
        p.post_subject AS subject,
        p.post_content AS content,
        p.post_date AS date,
        p.post_category AS category,
        p.post_creator AS creator,
        p.post_score AS score,
        sc.subcategory_name AS category_name,
        u.user_name AS creator_name,
        (SELECT COUNT(*) FROM postview WHERE post = p.post_id) AS views,
        (SELECT COUNT(*) FROM comment WHERE comment_post = p.post_id) AS num_comments,
        (SELECT img_url FROM postimg WHERE img_post = p.post_id LIMIT 1) AS img
      FROM
        post AS p INNER JOIN subcategory AS sc ON p.post_category = sc.subcategory_id INNER JOIN user AS u ON p.post_creator = u.user_id
      WHERE sc.subcategory_id = $subcat";

    return $this->db->select($sql); // Returns array of posts
  }
  public function listFromUser($user) {
    $sql =
      "SELECT
        p.post_id AS id,
        p.post_subject AS subject,
        p.post_content AS content,
        p.post_date AS date,
        p.post_category AS category,
        p.post_creator AS creator,
        p.post_score AS score,
        sc.subcategory_name AS category_name,
        u.user_name AS creator_name,
        (SELECT COUNT(*) FROM postview WHERE post = p.post_id) AS views,
        (SELECT COUNT(*) FROM comment WHERE comment_post = p.post_id) AS num_comments,
        (SELECT img_url FROM postimg WHERE img_post = p.post_id LIMIT 1) AS img
      FROM
        post AS p INNER JOIN subcategory AS sc ON p.post_category = sc.subcategory_id INNER JOIN user AS u ON p.post_creator = u.user_id
      WHERE p.post_creator = $user";

    return $this->db->select($sql); // Returns array of posts
  }

  // INSERT: POST
  public function insertPost($subject, $content, $category, $creator) {
    // TODO: Possibly replace creator parameter with session variable.
    $subject = $this->db->quote(strip_tags($subject));
    $ogcontent = $content;
    $content = $this->db->quote(strip_tags($content));
    $category = $this->db->quote($category);
    $creator = $this->db->quote($creator);
    $date = $this->db->quote(date('Y-m-d G:i:s'));

    $newPost = $this->db->insert(
      "INSERT INTO post(post_id, post_subject, post_content, post_date, post_category, post_creator, post_score)
      VALUES(NULL, $subject, $content, $date, $category, $creator, 0)"
    );

    // Insert url to first found img in first found link in content
    require_once "libs/imagemanipulator.php";
    $imgman = new ImageManipulator();
    $url = $imgman->getFirstUrl($ogcontent);
    if(!empty($url)) {
      $postid = $this->db->lastInsertId();
      $this->updatePostImg($postid,  $this->db->quote($imgman->getFirstImageUrl($url)));
    }

    return $newPost;
  }

  // UPDATE: POST
  public function updatePost($id, $subject, $content) {
    $id = $this->db->quote($id);
    $subject = $this->db->quote(strip_tags($subject));
    $content = $this->db->quote(strip_tags($content));

    $editedPost = $this->db->update(
      "UPDATE post SET post_subject = $subject, post_content = $content WHERE post_id = $id"
    );
    return $editedPost;
  }

  // DELETE: POST
  public function deletePost($id) {
    $id = $this->db->quote($id);
    $deletedPost = $this->db->delete(
      "DELETE FROM post WHERE post_id = $id"
    );
    return $deletedPost;
  }

  public function updatePostImg($post, $url) {
    //$post = $this->db->quote($post);
    //$url = $this->db->quote($url);
    $this->db->delete(
      "DELETE FROM post WHERE img_post = $post"
    );
    $this->db->insert(
      "INSERT INTO postimg(img_id, img_url, img_post) VALUES(NULL, $url, $post)"
    );
  }

  // GET LAST INSERT ID
  public function lastInsertId() {
    return $this->db->lastInsertId();
  }

  // Increment view count
  public function incrementViewCount($id) {
    // Post view counter
    $user_ip = $this->db->quote($_SERVER["REMOTE_ADDR"]);
    $id = $this->db->quote($id);
    $check_ip = $this->db->select(
      "SELECT user_ip FROM postview WHERE post = $id AND user_ip = $user_ip"
    );
    if(count($check_ip) == 0) {
      // Only increment if the new visitor is unique (new ip)
      $this->db->insert(
        "INSERT INTO postview VALUES(NULL, $id, $user_ip)"
      );
    }
  }
}

?>
