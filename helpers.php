<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function get_layout($content, $title, $categories, $user_name) {
  return $layout = include_template("layout.php", [
    "content" => $content,
    "title" => $title,
    "user_name" => $user_name,
    "categories" => $categories
  ]);
};

function format_price($number) {
   $result = ceil($number);
   if ($result >= 1000) {
       $result = number_format($result, 0, "", " ");
   }
   return $result;
}

function get_time_params($expirationDate) {
  $timeLeft = strtotime($expirationDate) - time();
  $hours_left = floor($timeLeft / 3600);
  $minutes_left = floor(($timeLeft % 3600) / 60);
  $seconds_left = floor(($timeLeft % 3600) % 60);
  $expiringMark = ($hours_left <= 1) ? "timer--finishing" : null;
  return [
      "hours_left" => $hours_left,
      "minutes_left" => $minutes_left,
      "seconds_left" => $seconds_left,
      "expiration_mark" => $expiringMark
  ];
}

function check_interval($date) {
  $interval = date_diff(date_create("today"), date_create($date));
  return ($interval->format("%a")) >= 1;
}

function check_required_fields($fields, $form) {
  $errors = [];
  foreach ($fields as $key) {
    if (empty($form[$key])) {
      $errors[$key] = "Это поле надо заполнить";
    }
  }
  return $errors;
}

function check_lot_data($lot) {
  $required_fields = ["lot-name", "category", "message", "lot-rate", "lot-step", "lot-date"];
  $errors = check_required_fields($required_fields, $lot);

  if ($lot["category"] == "Выберите категорию") {
    $errors["category"] = "Выберите категорию";
  }

  if (!is_date_valid($lot["lot-date"])) {
    $errors["lot-date"] = "Добавьте дату в формате ГГГГ-ММ-ДД";
  }

  if (!check_interval($lot["lot-date"])) {
    $errors["lot-date"] = "Добавьте дату больше текущей даты";
  }

  if (!(is_numeric($lot["lot-rate"])) or ($lot["lot-rate"] <= 0)) {
    $errors["lot-rate"] = "Содержимое поля должно быть числом больше нуля";
  }

  if (!(is_numeric($lot["lot-step"])) or ($lot["lot-step"] <= 0) or !(filter_var($lot["lot-step"], FILTER_VALIDATE_INT))) {
    $errors["lot-step"] = "Содержимое поля должно быть целым числом больше нуля";
  }

  return $errors;
}

function check_categories($categories, $user_name) {
  if (!$categories) {
    $content = include_template("error.php", ["error" => "Извините, в настоящий момент страница недоступна..."]);
    $title = "Ошибка";
    $layout = get_layout($content, $title, $categories, $user_name);
    print($layout);
    exit();
  }
}

function set_user() {
  if ($_SESSION) {
    $user_name = $_SESSION["user"]["name"];
  } else {
    $user_name = null;
  }
  return $user_name;
}

function get_bet_block_status($lot, $all_bets) {
  return isset($_SESSION["user"])
      and (strtotime($lot["expirationDate"]) > time())
      and $lot["user_id"] !== $_SESSION["user"]["id"]
      and ($all_bets[count($all_bets) - 1]["user"]) !== ($_SESSION["user"]["name"]);
}

function get_formatted_time($bet_time) {
  $result;
  $difference = time() - strtotime($bet_time);
  if ($difference < 86400 and $difference >= 3600) {
    $hours = floor($difference / 3600);
    $result = $hours . " " . get_noun_plural_form($hours, "час", "часа", "часов") . " назад";
  } else if ($difference < 3600 and $difference >= 60) {
    $minutes = floor($difference / 60);
    $result = $minutes . " " . get_noun_plural_form($minutes, "минута", "минуты", "минут") . " назад";
  } else if ($difference < 60) {
    $seconds = floor($difference);
    $result = $seconds . " " . get_noun_plural_form($seconds, "секунду", "секунды", "секунд") . " назад";
  } else {
    $result = date("d.m.y", strtotime($bet_time)) . " в " . date("H:i", strtotime($bet_time));
  }
  return $result;
}
