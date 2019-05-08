<?php
require_once "helpers.php";
require_once "data/layout.php";
require_once "mysql/requests.php";

$link = createLink();
$categories = getCategories($link);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $errors = [];
  $lot = $_POST;
  $required_fields = ["lot-name", "category", "message", "lot-rate", "lot-step", "lot-date"];

  foreach ($required_fields as $key) {
    if (empty($_POST[$key])) {
      $errors[$key] = "Это поле надо заполнить";
    }
  }

  if ($lot["category"] == "Выберите категорию") {
    $errors["category"] = "Выберите категорию";
  }

  if (!is_date_valid($lot["lot-date"])) {
    $errors["lot-date"] = "Добавьте дату в формате ГГГГ-ММ-ДД";
  }

  if (!checkInterval($lot["lot-date"])) {
    $errors["lot-date"] = "Добавьте дату больше текущей даты";
  }

  if (!(is_numeric($lot["lot-rate"])) or (((int) $lot["lot-rate"]) <= 0)) {
    $errors["lot-rate"] = "Содержимое поля должно быть числом больше нуля";
  }

  if (!(is_numeric($lot["lot-step"])) or (((int) $lot["lot-step"]) <= 0)) {
    $errors["lot-step"] = "Содержимое поля должно быть целым числом больше ноля";
  }

  if (!empty($_FILES["lot-img"]["name"])) {
    $tmp_name = $_FILES["lot-img"]["tmp_name"];
    $path = $_FILES["lot-img"]["name"];
    $file_type = mime_content_type($tmp_name);
    if ($file_type !== "image/png" || $file_type !== "image/jpeg"){
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
  } else {
    $id = addNewLot($lot);
    header("Location: lot.php?lot_id=" . $id);
  };
}
else {
  if ($categories) {
    $content = include_template("add.php", ["categories" => $categories]);
    $title = "Добавить лот";
  } else {
    $content = include_template("error.php", ["error" => mysqli_connect_error()]);
    $title = $error_title;;
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
