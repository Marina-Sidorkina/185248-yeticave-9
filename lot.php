<?php
require_once "helpers.php";
require_once "data/layout.php";
require_once "mysql/requests.php";

$title;
$lot;
$categories;
$content;
$error;

if (isset($_GET["lot_id"])) {
  $id = (int) $_GET["lot_id"];
  $lot = get_lot_by_id($id)["lot"];
  $categories = get_lot_by_id($id)["categories"];
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

$layout = get_layout($content, $title, $is_auth, $user_name, $categories);

if ($error) {
  header("HTTP/ 1.1 404 Not found");
}

print($layout);
?>
