<?php
require_once "helpers.php";
require_once "mysql/requests.php";
session_start();

$title;
$lot;
$content;
$error;

$link = create_link();
$categories = get_categories($link);
$user_name = set_user();
check_categories($categories, $user_name);

if (isset($_GET["lot_id"])) {
  $id = (int) $_GET["lot_id"];
  $lot = get_lot_by_id($id)["lot"];
};

if ($lot) {
  $content = include_template("lot.php", ["lot" => $lot, "categories" => $categories]);
  $title = $lot["title"];
  $error = false;
} else {
  $content = include_template("404.php", ["categories" => $categories]);
  $title = "Страница не найдена";
  $error = true;
};

$layout = get_layout($content, $title, $categories, $user_name);

if ($error) {
  header("HTTP/ 1.1 404 Not found");
}

print($layout);
?>
