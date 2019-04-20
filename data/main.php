<?php
$diff = strtotime('tomorrow') - time();
$hours_left = floor($diff / 3600);
$minutes_left = floor(($diff % 3600) / 60);

$adverts = [
    [
        "title" => "2014 Rossignol District Snowboard",
        "category" => "Доски и лыжи",
        "price" => "10999",
        "url" => "img/lot-1.jpg",
        "hours" => $hours_left,
        "minutes" => $minutes_left,
        "isExpiringMark" => ($hours_left <= 1) ? "timer--finishing" : ""
    ],
    [
        "title" => "DC Ply Mens 2016/2017 Snowboard",
        "category" => "Доски и лыжи",
        "price" => "159999",
        "url" => "img/lot-2.jpg",
        "hours" => $hours_left,
        "minutes" => $minutes_left,
        "isExpiringMark" => ($hours_left <= 1) ? "timer--finishing" : ""
    ],
    [
        "title" => "Крепления Union Contact Pro 2015 года размер L/XL",
        "category" => "Крепления",
        "price" => "8000",
        "url" => "img/lot-3.jpg",
        "hours" => $hours_left,
        "minutes" => $minutes_left,
        "isExpiringMark" => ($hours_left <= 1) ? "timer--finishing" : ""
    ],
    [
        "title" => "Ботинки для сноуборда DC Mutiny Charocal",
        "category" => "Ботинки",
        "price" => "10999",
        "url" => "img/lot-4.jpg",
        "hours" => $hours_left,
        "minutes" => $minutes_left,
        "isExpiringMark" => ($hours_left <= 1) ? "timer--finishing" : ""
    ],
    [
        "title" => "Куртка для сноуборда DC Mutiny Charocal",
        "category" => "Одежда",
        "price" => "7500",
        "url" => "img/lot-5.jpg",
        "hours" => $hours_left,
        "minutes" => $minutes_left,
        "isExpiringMark" => ($hours_left <= 1) ? "timer--finishing" : ""
    ],
    [
        "title" => "Маска Oakley Canopy",
        "category" => "Разное",
        "price" => "5400",
        "url" => "img/lot-6.jpg",
        "hours" => $hours_left,
        "minutes" => $minutes_left,
        "isExpiringMark" => ($hours_left <= 1) ? "timer--finishing" : ""
    ]
];
?>
