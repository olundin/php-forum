<?php
require_once "model/categorymodel.php";

$catmdl = new CategoryModel($db);
$catlist = $catmdl->listAll();
?>

<ul id="category-list">
  <?php
  // Loops once for each category
  foreach($catlist as $c) :
  ?>
    <li>
      <a href="/?view=category&id=<?= $c[0]->id ?>" class="button orange"><?= $c[0]->name ?></a>
      <ul>
        <?php foreach($c[1] as $sc) : ?>
          <li><a href="/?view=subcategory&id=<?= $sc->id ?>" class="button-sub"><?= $sc->name ?></a></li>
        <?php endforeach; ?>
      </ul>
    </li>
  <?php
  endforeach;
  ?>
</ul>
