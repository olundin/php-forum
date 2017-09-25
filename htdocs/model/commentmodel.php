<?php

/* COMMENT CLASS */
class CommentModel {

  private $db;

  function __construct($db) {
    $this->db = $db;
  }

  // SELECT: COMMENT LIST
  public function listFromPost($post) {
    $sql =
      "SELECT
        c.comment_id AS id,
        c.comment_post AS post,
        c.comment_parent AS parent,
        c.comment_content AS content,
        c.comment_date AS date,
        c.comment_creator AS creator,
        c.comment_score AS score,
        u.user_name AS creator_name
      FROM
        comment AS c INNER JOIN user AS u ON c.comment_creator = u.user_id
      WHERE
        c.comment_post = $post
      ORDER BY c.comment_score";

      return $this->db->select($sql);
  }

  public function listFromUser($user) {
    $sql =
      "SELECT
        c.comment_id AS id,
        c.comment_post AS post,
        c.comment_parent AS parent,
        c.comment_content AS content,
        c.comment_date AS date,
        c.comment_creator AS creator,
        c.comment_score AS score,
        u.user_name AS creator_name
      FROM
        comment AS c INNER JOIN user AS u ON c.comment_creator = u.user_id
      WHERE
        c.comment_creator = $user
      ORDER BY c.comment_score";

      return $this->db->select($sql);
  }

  // INSERT: COMMENT
  public function insertComment($content, $creator, $post, $parent) {
    $content = $this->db->quote($content);
    $creator = $this->db->quote($creator);
    $post = $this->db->quote($post);
    $parent = $this->db->quote($parent);
    $date = $this->db->quote(date('Y-m-d H:i:s'));

    $newComment = $this->db->insert(
      "INSERT INTO comment(comment_id, comment_parent, comment_content, comment_date, comment_post, comment_creator, comment_score)
      VALUES(NULL, NULLIF($parent, ''), $content, $date, $post, $creator, 0)"
    );

    return $newComment;
  }

  // DELETE: COMMENT
  public function deleteComment($id) {
    $id = $this->db->quote($id);
    $deletedComment = $this->db->delete(
      "DELETE FROM comment WHERE comment_id = $id"
    );
    return $deletedComment;
  }

  // DELETE: POST
  public function deletePost($id) {
    $id = $this->db->quote($id);
    $deletedPost = $this->db->delete(
      "DELETE FROM post WHERE post_id = $id"
    );
    return $deletedPost;
  }
}

?>
