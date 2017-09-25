<?php

require_once "libs/imagemanipulator.php";
$imgman = new ImageManipulator();
$url = $imgman->getFirstUrl("http://imgur.com/gallery/is0q6");
if(!empty($url)) {
  echo "<img src='".$imgman->getFirstImageUrl($url)."' />";
}

?>
