<?php
require_once "helpers.php";
require_once "data/layout.php";

$link = mysqli_connect("localhost", "root", "", "yeti");
mysqli_set_charset($link, "utf8");

if (!$link) {
  $error = mysqli_connect_error();
  $content = include_template("error.php", ["error" => $error]);
} else {

  $sql = 'SELECT id, title, char_code FROM categories';
  $result = mysqli_query($link, $sql);

  if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
  } else {
    $error = mysqli_error($link);
    $content = include_template("error.php", ["error" => $error]);
  }

  $sql = 'SELECT c.title "category", l.title "title", l.price "price", l.picture_url "url", l.expired_at "expirationDate" FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE NOW() < l.expired_at AND l.winner_id IS NULL
    ORDER BY l.created_at DESC';
  $result = mysqli_query($link, $sql);

  if ($result) {
    $adverts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $content = include_template("main.php", [
        "categories" => $categories,
        "adverts" => $adverts
    ]);
  }
  else {
    $content = include_template("error.php", ["error" => mysqli_error($link)]);
  }
}

$layout = include_template("layout.php", [
    "content" => $content,
    "title" => $title,
    "is_auth" => $is_auth,
    "user_name" => $user_name,
    "categories" => $categories
]);

print($layout);
?>
