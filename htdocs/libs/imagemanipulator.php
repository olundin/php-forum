<?php

// Just a quick class for some image manipulation

class ImageManipulator {

  public function getFirstUrl($string) {
    // The Regular Expression filter
    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
    // Check if there is a url in the text
    if(preg_match($reg_exUrl, $string, $url)) {
       // return first url
       return $url[0];
    } else {
       // no urls in the string
       return false;
    }
  }

  public function getFirstImageUrl($url) {
    $html = file_get_contents($url);
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    libxml_use_internal_errors(false);

    // http://ogp.me/ - Look for OpenGraph image
    $metas = $dom->getElementsByTagName("meta");
    foreach($metas as $meta) {
      if($meta->getAttribute("property") == "og:image") {
        // Return if found
        return $meta->getAttribute("content");
      }
    }
    // Else return URL to first image element
    return $dom->getElementsByTagName("img")[0]->getAttribute('src');
  }

  public function resizeImage($imgPath, $width, $height) {
    $thumb = imagecreatetruecolor($width, $height);
    $ext = pathinfo($imgPath, PATHINFO_EXTENSION);
    list($oldWidth, $oldHeight) = getimagesize($imgPath);
    if($ext = "gif") $source = imagecreatefromgif($imgPath);
    else if($ext = "jpeg") $source = imagecreatefromjpeg($imgPath);
    else if($ext = "png") $source = imagecreatefrompng($imgPath);
    else return false;

    imagecopyresized($thumb, $source, 0, 0, 0, 0, $width, $height, $oldWidth, $oldHeight);

    return $thumb;
  }
}


?>
