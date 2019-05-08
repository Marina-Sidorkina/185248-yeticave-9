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

function getActiveLotsLink($link) {
  $sql = 'SELECT c.title "category", l.id "id", l.title "title", l.description "description", l.price "price", l.bet_step "step", l.picture_url "url", l.expired_at "expirationDate" FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE NOW() < l.expired_at AND l.winner_id IS NULL
    ORDER BY l.created_at DESC';
  return mysqli_query($link, $sql);
}

function getLotByIdLink($link, $id) {
  $sql = 'SELECT c.title "category", l.id "id", l.title "title", l.description "description", l.price "price", l.bet_step "step", l.picture_url "url", l.expired_at "expirationDate" FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE l.id = "'. $id .'"';
  return mysqli_query($link, $sql);
}

function getArray($result) {
  return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getCategories($link) {
  if (!$link) {
    $template = "error.php";
    $params = ["error" => mysqli_connect_error()];
  }
  else {
    $categoriesLink = getCategoriesLink($link);
  }
  if ($categoriesLink) {
    return getArray($categoriesLink);
  }
};

function getActiveLots() {
  $link = createLink();
  if (!$link) {
    $template = "error.php";
    $params = ["error" => mysqli_connect_error()];
  }
  else {
    $lotsLink = getActiveLotsLink($link);
    $categories = getCategories($link);
    if ($lotsLink and $categories) {
      $lots = getArray($lotsLink);
      $template = "main.php";
      $params = ["categories" => $categories, "lots" => $lots];
    }
    else {
      $template = "error.php";
      $params = ["error" => mysqli_error($link)];
    }
    return [
      "params" => $params,
      "template" => $template,
      "categories" => $categories
    ];
  }
}

function getLotById($id) {
  $link = createLink();
  if (!$link) {
    $template = "error.php";
    $params = ["error" => mysqli_connect_error()];
  }
  else {
    $lotLink = getLotByIdLink($link, $id);
    $categories = getCategories($link);
    if ($lotLink and $categories) {
      $lot = mysqli_fetch_array($lotLink, MYSQLI_ASSOC);
      return ["categories" => $categories, "lot" => $lot];
    }
  }
}
