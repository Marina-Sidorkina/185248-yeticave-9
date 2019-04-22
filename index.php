<?php
require_once "data/main.php";
require_once "helpers.php";
require_once "data/general.php";
require_once "data/layout.php";

$content = include_template("main.php", [
    "categories" => $categories,
    "adverts" => $adverts,
    "hours_left" => $hours_left,
    "minutes_left" => $minutes_left,
    "expiringMark" => $expiringMark
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
