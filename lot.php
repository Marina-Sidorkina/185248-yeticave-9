<?php
require_once "helpers.php";
require_once "data/layout.php";
require_once "mysql/requests.php";

if (isset($_GET["lot_id"])) {
  $id = (int) $_GET["lot_id"];
  $lot = getLotById($id)["lot"];
  $categories = getLotById($id)["categories"];
};

if ($lot) {
  $content = include_template("lot.php", ["lot" => $lot, "categories" => $categories]);
  $title = $lot["title"];
  $error = false;
} else {
  $content = include_template("404.php", ["categories" => $categories]);
  $title = $error_title;
  $error = true;
};

$layout = include_template("layout.php", [
    "content" => $content,
    "title" => $title,
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "categories" => $categories
]);

if ($error) {
  header("HTTP/ 1.1 404 Not found");
}
print($layout);
?>
