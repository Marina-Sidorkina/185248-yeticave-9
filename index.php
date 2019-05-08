<?php
require_once "helpers.php";
require_once "data/layout.php";
require_once "mysql/requests.php";

$main_block_class = "container";
$categories = getActiveLots()["categories"];
$content = include_template(getActiveLots()["template"], getActiveLots()["params"]);

$layout = include_template("layout.php", [
    "content" => $content,
    "title" => $index_title,
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "categories" => $categories,
    "main_block_class" => $main_block_class
]);

print($layout);
?>
