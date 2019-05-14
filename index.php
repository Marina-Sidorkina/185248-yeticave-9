<?php
require_once "helpers.php";
require_once "mysql/requests.php";
session_start();

$main_block_class = "container";
$link = create_link();
$categories = get_categories($link);
$user_name = set_user($_SESSION);
$content = include_template(get_active_lots()["template"], get_active_lots()["params"]);
$title = "Главная страница";

$layout = include_template("layout.php", [
    "content" => $content,
    "title" => $title,
    "user_name" => $user_name,
    "categories" => $categories,
    "main_block_class" => $main_block_class
]);

print($layout);
?>
