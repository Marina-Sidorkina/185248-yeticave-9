<?php
require_once "helpers.php";
require_once "data/layout.php";
require_once "mysql/requests.php";

$link = createLink();
$categories = getCategories($link);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $lot = $_POST;
  $errors = checkLotData($lot);

  if (!empty($_FILES["lot-img"]["name"])) {
    $tmp_name = $_FILES["lot-img"]["tmp_name"];
    $path = $_FILES["lot-img"]["name"];
    $file_type = mime_content_type($tmp_name);
    if ($file_type !== "image/png" and $file_type !== "image/jpeg"){
      $errors["lot-img"] = "Изображение должно быть в формате png или jpeg";
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
    $layout = getLayout($content, $title, $is_auth, $user_name, $categories);
    print($layout);
  } else {
    $id = addNewLot($lot);
    header("Location: lot.php?lot_id=" . $id);
  };
}
else {
  $content = include_template("add.php", ["categories" => $categories]);
  $title = "Добавить лот";
  $layout = getLayout($content, $title, $is_auth, $user_name, $categories);
  print($layout);
}
?>
