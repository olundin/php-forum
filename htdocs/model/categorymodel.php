<?php

/* CATEGORY CLASS */
class CategoryModel {

  function __construct($db) {
    $this->db = $db;
  }

  // SELECT: POST LIST
  public function listCategories() {
    $sql = "SELECT category_id AS id, category_name AS name FROM category";
    return $this->db->select($sql); // Returns array of categories
  }

  public function listSubcategories($category) {
    $sql = "SELECT subcategory_id AS id, subcategory_name AS name FROM subcategory WHERE subcategory_parent = $category";
    return $this->db->select($sql); // Returns array of categories
  }

  public function listAll() {
    $res = array();
    $cats = $this->listCategories();
    for($i = 0; $i < count($cats); $i++) {
      $res[$i] = array($cats[$i], $this->listSubcategories($cats[$i]->id));
    }
    return $res;
  }
}

?>
