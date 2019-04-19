<?php
require_once "helpers.php";
require_once "data/general.php";
require_once "data/layout.php";
require_once "data/main.php";
require_once "lib/main.php";

$content = include_template("main.php", [
    "categories" => $categories,
    "adverts" => $adverts
]);

$layout = include_template("layout.php", [
    "content" => $content,
    "title" => $title,
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "categories" => $categories
]);

print($layout);
?>
