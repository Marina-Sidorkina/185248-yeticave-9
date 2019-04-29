<?php
require_once "helpers.php";
require_once "data/layout.php";
require_once "mysql/requests.php";

$layout = include_template("layout.php", [
    "content" => $content,
    "title" => $title,
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "categories" => $categories
]);

print($layout);
?>
