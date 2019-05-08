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
  if ($link) {
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
  if ($link) {
    $lotLink = getLotByIdLink($link, $id);
    $categories = getCategories($link);
    if ($lotLink and $categories) {
      $lot = mysqli_fetch_array($lotLink, MYSQLI_ASSOC);
      return ["categories" => $categories, "lot" => $lot];
    }
  }
}


function getCategoryIdByName($link, $name) {
  $sql = 'SELECT * FROM categories c WHERE c.title = "'. $name .'"';
  $result = mysqli_query($link, $sql);
  return mysqli_fetch_array($result, MYSQLI_ASSOC)["id"];
}

function addNewLot($lot) {
    $link = createLink();
    $category = getCategoryIdByName($link, $lot['category']);
    $user_id = 1;

    $sql = "INSERT INTO lots (
      created_at,
      winner_id,
      user_id,
      category_id,
      expired_at,
      title,
      description,
      picture_url,
      price,
      bet_step
    ) VALUES (
      NOW(), NULL, ?, ?, ?, ?, ?, ?, ?, ?
    )";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $user_id,
        $category['id'],
        $lot['lot-date'],
        $lot['lot-name'],
        $lot['message'],
        $lot['lot-img'],
        $lot['lot-rate'],
        $lot['lot-step']
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}
