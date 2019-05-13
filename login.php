<?php
require_once "helpers.php";
require_once "data/layout.php";
require_once "mysql/requests.php";

$link = create_link();
$categories = get_categories($link);

$content = include_template("login.php", ["categories" => $categories]);
$title = "Вход";
$layout = get_layout($content, $title, $is_auth, $user_name, $categories);
print($layout);
?>
