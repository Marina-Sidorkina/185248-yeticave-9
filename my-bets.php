<?php
require_once "helpers.php";
require_once "mysql/requests.php";
session_start();

$link = create_link();
$categories = get_categories($link);
$user_name = set_user();
check_categories($categories, $user_name);

$user_bets = get_user_bets($_SESSION["user"]["id"]) ?? [];

$content = include_template("my-bets.php", ["categories" => $categories, "user_bets" => $user_bets]);
$title = "Мои ставки";
$layout = get_layout($content, $title, $categories, $user_name);

print($layout);
