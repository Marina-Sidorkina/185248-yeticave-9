<?php
require_once "helpers.php";
require_once "mysql/requests.php";
session_start();

$layout;
$link = create_link();
$categories = get_categories($link);
$user_name = set_user();
check_categories($categories, $user_name);
$categories_block = include_template("categories-block.php", ["categories" => $categories]);

$search = trim($_GET["search"] ?? "");

if ($search) {
  $lots = get_search_result($search) ?? [];
  if ($lots) {
    $cur_page = $_GET["page"] ?? 1;
    $page_items = 2;
    $items_count = count($lots);
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);
    $lots = array_slice($lots, $offset, $page_items);
    $content = include_template("search.php", ["categories" => $categories, "lots" => $lots,
      "search" => $search, "pages_count" => $pages_count,
      "pages" => $pages, "cur_page" => $cur_page]);
    $title = "Поиск";
    $layout = get_layout($content, $title, $categories, $user_name, $categories_block);
  } else {
    $content = "По вашему запросу ничего не найдено...";
    $title = "Поиск";
    $layout = get_layout($content, $title, $categories, $user_name, $categories_block);
  }
} else {
  $content = "Выберите ключевые слова для поиска...";
  $title = "Поиск";
  $layout = get_layout($content, $title, $categories, $user_name, $categories_block);
}

print($layout);
