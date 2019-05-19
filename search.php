<?php
require_once "helpers.php";
require_once "mysql/requests.php";
session_start();

$layout;
$link = create_link();
$categories = get_categories($link);
$user_name = set_user();
check_categories($categories, $user_name);

$search = $_GET["search"] ?? "";

if ($search) {
  $lots = get_search_result($search) ?? [];
  if ($lots) {
    $content = include_template("search.php", ["categories" => $categories, "lots" => $lots, "search" => $search]);
    $title = "Поиск";
    $layout = get_layout($content, $title, $categories, $user_name);
  } else {
    $content = "По вашему запросу ничего не найдено...";
    $title = "Поиск";
    $layout = get_layout($content, $title, $categories, $user_name);
  }
} else {
  $content = "Выберите ключевые слова для поиска...";
  $title = "Поиск";
  $layout = get_layout($content, $title, $categories, $user_name);
}

print($layout);
