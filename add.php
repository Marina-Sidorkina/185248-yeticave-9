<?php
require_once "helpers.php";
require_once "mysql/requests.php";
session_start();

$link = create_link();
$categories = get_categories($link);
$user_name = set_user($_SESSION);
check_categories($categories, $user_name);

if (!isset($_SESSION['user'])) {
    header('HTTP/1.0 403 Forbidden');
    $content = "Доступ заблокирован, необходимо зарегистрироваться!";
    $title = "Ошибка";
    $layout = get_layout($content, $title, $categories, $user_name);
    print($layout);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $lot = $_POST;
  $errors = check_lot_data($lot);

  if (!empty($_FILES["lot-img"]["name"])) {
    $tmp_name = $_FILES["lot-img"]["tmp_name"];
    $path = $_FILES["lot-img"]["name"];
    $file_type = mime_content_type($tmp_name);
    if ($file_type !== "image/png" and $file_type !== "image/jpeg" and $file_type !== "image/jpg"){
      $errors["lot-img"] = "Изображение должно быть в формате png, jpeg, или jpg";
    } else {
      move_uploaded_file($tmp_name, 'uploads/' . $path);
      $lot["lot-img"] = "uploads/" . $path;
    }
  } else {
    $errors["lot-img"] = 'Вы не загрузили файл';
  };

  if (!empty($errors)) {
    $content = include_template("add.php", ["categories" => $categories, "errors" => $errors, "lot" => $lot]);
    $title = "Ошибка";
    $layout = get_layout($content, $title, $categories, $user_name);
    print($layout);
  } else {
    $id = add_new_lot($lot);
    header("Location: lot.php?lot_id=" . $id);
  };
}
else {
  $content = include_template("add.php", ["categories" => $categories]);
  $title = "Добавить лот";
  $layout = get_layout($content, $title, $categories, $user_name);
  print($layout);
}
