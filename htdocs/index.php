<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, maximum-scale=1, minimum-scale=1" />
  <title>Forum</title>

  <link rel="stylesheet" type="text/css" href="/css/main.css">
  <link rel="stylesheet" type="text/css" href="/css/forum.css">
  <link rel="stylesheet" type="text/css" href="/css/post.css">
  <link rel="stylesheet" type="text/css" href="/css/user.css">
  <link rel="stylesheet" type="text/css" href="/css/misc.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="/js/day-night-toggle.js"></script>
  <script src="/js/hide-show-subcat.js"></script>
  <script src="/js/hide-show-comment-submit.js"></script>
</head>

<body>
  <?php
    ob_start(); // Output buffering. Makes it possible to use header() since the outputs are made after the page is loaded.
    // TODO: FINISH DOCUMENTATION
    // TODO: POST THUMBNAIL
    // TODO: Maybe change view counter a bit. Increment on each new user instead of each new IP.

    session_start();

    include_once "model/db.php";
    include_once "controller/controller.php";

    $db = new DB();
    $ctrl = new Controller();

    $ctrl->invoke();
    $view = $ctrl->getView(); // returns view based on $_GET["view"]
  ?>

  <div id="container">
    <div id="left">
      <h3>Categories</h3>
      <?php
        include_once "view/categorylist.php";
      ?>
    </div>

    <div id="center">
      <div id="center-header">
        <p id="center-header-left">
        </p>
        <p id="center-header-center">
          Forum
        </p>
        <p id="center-header-right">
        </p>
      </div>
      <div id="center-main">
        <?php include_once $view; ?>
      </div>
    </div>

    <div id="right">
      <?php include_once "view/userlist.php"; ?>
      <div class="day-night-switch">
        <label class="switch">
          <input type="checkbox" id="day-night-checkbox">
          <span class="slider round"></span>
        </label>
      </div>
    </div>
  </div>
</body>

</html>
