<?php
require_once "helpers.php";
require_once "data/layout.php";
require_once "mysql/requests.php";

$title;
$content;
$errors = [];

$link = create_link();
$categories = get_categories($link);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $form = $_POST;
  $required_fields = ["email", "password", "name", "message"];
  $errors = check_required_fields($required_fields, $form);
  $user = check_user($form);

  if (!filter_var($form["email"], FILTER_VALIDATE_EMAIL)) {
    $errors["email"] = "Указан некорректный адрес";
  }

  if ($user) {
    $errors["email"] = "Аккаунт с указанным адресом уже зарегистрирован";
  }

  if (empty($errors) and !$user) {
    add_new_user($form);
    header("Location: login.php");
    exit();
  } else {
    $content = include_template("sign-up.php", ["categories" => $categories, "errors" => $errors, "form" => $form]);
    $title = "Ошибка";
    $layout = get_layout($content, $title, $is_auth, $user_name, $categories);
    print($layout);
  }

} else {
  $content = include_template("sign-up.php", ["categories" => $categories]);
  $title = "Регистрация";
  $layout = get_layout($content, $title, $is_auth, $user_name, $categories);
  print($layout);
}
?>
