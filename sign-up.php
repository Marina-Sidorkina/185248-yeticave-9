<?php
require_once "helpers.php";
require_once "mysql/requests.php";
session_start();

$user_name = set_user();
$link = create_link();
$categories = get_categories($link);
check_categories($categories, $user_name);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $errors = [];
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
    $layout = get_layout($content, $title, $categories, $user_name);
    print($layout);
  }

} else {
  $content = include_template("sign-up.php", ["categories" => $categories]);
  $title = "Регистрация";
  $layout = get_layout($content, $title, $categories, $user_name);
  print($layout);
}
