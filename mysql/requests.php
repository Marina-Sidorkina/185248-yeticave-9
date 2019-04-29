<?php
function createLink() {
  $link = mysqli_connect("localhost", "root", "", "yeti");
  mysqli_set_charset($link, "utf8");
  return $link;
}

function getCategoriesLink($link) {
  $sql = 'SELECT id, title, char_code FROM categories';
  return mysqli_query($link, $sql);
}

function getAdvertsLink($link) {
  $sql = 'SELECT c.title "category", l.title "title", l.price "price", l.picture_url "url", l.expired_at "expirationDate" FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE NOW() < l.expired_at AND l.winner_id IS NULL
    ORDER BY l.created_at DESC';
  return mysqli_query($link, $sql);
}

function getArray($result) {
  return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getData() {
  $link = createLink();
  if (!$link) {
    $template = "error.php";
    $params = ["error" => mysqli_connect_error()];
  } else {

    $categoriesLink = getCategoriesLink($link);
    if ($categoriesLink) {
      $categories = getArray($categoriesLink);
    }
    else {
      $template = "error.php";
      $params = ["error" => mysqli_error($link)];
    }

    $advertsLink = getAdvertsLink($link);
    if ($advertsLink) {
      $adverts = getArray($advertsLink);
      $template = "main.php";
      $params = ["categories" => $categories, "adverts" => $adverts];
    }
    else {
      $template = "error.php";
      $params = ["error" => mysqli_error($link)];
    }
  }
  return [
    "params" => $params,
    "template" => $template,
    "categories" => $categories
  ];
}
?>
