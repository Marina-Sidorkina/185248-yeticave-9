<?php
function create_link() {
  $link = mysqli_connect("localhost", "root", "", "yeti");
  mysqli_set_charset($link, "utf8");
  return $link;
}

function get_categories_link($link) {
  $sql = 'SELECT id, title, char_code FROM categories';
  return mysqli_query($link, $sql);
}

function get_active_lots_link($link) {
  $sql = 'SELECT c.title "category", l.id "id", l.title "title", l.description "description", l.price "price", l.bet_step "step", l.picture_url "url", l.expired_at "expirationDate" FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE NOW() < l.expired_at AND l.winner_id IS NULL
    ORDER BY l.created_at DESC';
  return mysqli_query($link, $sql);
}

function get_lot_by_id_link($link, $id) {
  $sql = 'SELECT c.title "category", l.id "id", l.title "title", l.description "description", l.price "price", l.bet_step "step", l.picture_url "url", l.user_id "user_id", l.expired_at "expirationDate" FROM lots l
    JOIN categories c ON l.category_id = c.id
    WHERE l.id = "'. $id .'"';
  return mysqli_query($link, $sql);
}

function get_array($result) {
  return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_categories($link) {
  if ($link) {
    $categoriesLink = get_categories_link($link);
  }
  if ($categoriesLink) {
    $result = get_array($categoriesLink) ?? [];
    return $result;
  }
};

function get_active_lots() {
  $link = create_link();
  if (!$link) {
    $template = "error.php";
    $params = ["error" => mysqli_connect_error()];
  }
  else {
    $lotsLink = get_active_lots_link($link);
    $categories = get_categories($link) ?? null;
    if ($lotsLink and $categories) {
      $lots = get_array($lotsLink);
      $template = "main.php";
      $params = ["categories" => $categories, "lots" => $lots];
    }
    else {
      $template = "error.php";
      $params = ["error" => mysqli_error($link)];
    }
    return [
      "params" => $params,
      "template" => $template
    ];
  }
}

function get_lot_by_id($id) {
  $link = create_link();
  if ($link) {
    $lotLink = get_lot_by_id_link($link, $id);
    $categories = get_categories($link);
    if ($lotLink and $categories) {
      $lot = mysqli_fetch_array($lotLink, MYSQLI_ASSOC);
      return ["categories" => $categories, "lot" => $lot];
    }
  }
}

function get_category_id_by_name($link, $name) {
  $sql = 'SELECT * FROM categories c WHERE c.title = "'. $name .'"';
  $result = mysqli_query($link, $sql);
  return mysqli_fetch_array($result, MYSQLI_ASSOC)["id"];
}

function add_new_lot($lot) {
  $link = create_link();
  $category = get_category_id_by_name($link, $lot['category']);
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
    $category,
    $lot["lot-date"],
    $lot["lot-name"],
    $lot["message"],
    $lot["lot-img"],
    $lot["lot-rate"],
    $lot["lot-step"]
  ]);
  mysqli_stmt_execute($stmt);
  return mysqli_insert_id($link);
}

function check_user($form) {
  $link = create_link();
  $email = mysqli_real_escape_string($link, $form['email']);
  $sql = 'SELECT * FROM users u
            WHERE u.email = "'. $email .'"';
  $result = mysqli_query($link, $sql);
  $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;
  return $user;
}

function add_new_user($form) {
  $link = create_link();
  $password = password_hash($form["password"], PASSWORD_DEFAULT);
  $sql = "INSERT INTO users (
    registered_at, name, email, password, contacts
  ) VALUES (
    NOW(), ?, ?, ?, ?
  )";
  $stmt = db_get_prepare_stmt($link, $sql, [
    $form["name"],
    $form["email"],
    $password,
    $form["message"]
  ]);
  mysqli_stmt_execute($stmt);
}

function add_new_bet($lot_id, $user_id, $bet) {
  $link = create_link();
  $sql = "INSERT INTO bets (
    created_at, lot_id, user_id, price
  ) VALUES (
    NOW(), ?, ?, ?
  )";
  $stmt = db_get_prepare_stmt($link, $sql, [
    $lot_id,
    $user_id,
    $bet
  ]);
  mysqli_stmt_execute($stmt);
  if (mysqli_insert_id($link)) {
    $sql = 'UPDATE lots SET price = "'. $bet .'" WHERE id = "'. $lot_id .'"';
    mysqli_query($link, $sql);
  }
}

function get_bets_by_lot($lot_id) {
  $link = create_link();
  $sql = 'SELECT u.name "user", b.lot_id, b.user_id, b.price, b.created_at FROM bets b
    JOIN users u ON b.user_id = u.id
    WHERE b.lot_id = "'. $lot_id .'"';
  $result = mysqli_query($link, $sql);
  $bets = get_array($result) ?? [];
  return $bets;
}

function get_user_bets($user_id) {
  $link = create_link();
  $sql = 'SELECT l.title "lot_title", l.id "lot_id", l.picture_url "url", l.winner_id "winner_id", l.expired_at "expired_at", c.title "category", b.price "price", b.created_at "bet_date" FROM bets b
    JOIN lots l ON b.lot_id = l.id
    JOIN categories c ON l.category_id = c.id
    WHERE b.user_id = "'. $user_id .'"
    ORDER BY b.created_at DESC';
  $result = mysqli_query($link, $sql);
  return get_array($result);
}
