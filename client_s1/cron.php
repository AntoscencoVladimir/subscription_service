<?php

/**
 * Todo: Главная задача файла:
 *
 * 1. Вытаскивает 100 емайлов с главного сервера server_api
 * 2. Отправляет каждый час, статистика по отправки, переходов и отписок на главный сервер server_api.
 * 3. Отправка емайлов по шаблону template_email.html
 *
 * ОБЯЗАТЕЛЬНО!!! Вся база для сервера client_sXXX будет храниться в тип SQLITE. Должно быть создано структура на базе ТЗ
 * (емайлы для отсылки, данные о отсылки для учета переходов, статистика).
 * Статистика каждый час отправляет на главный сервер, очищая локальную базу.
 */
//echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";

require_once 'autoload.php';

$data = [
    "apikey" => "SECRET_KEY",
    "action" => "set_emails_accessed",
    "emails_accessed" => [
        [
            "country" => "MDA",
            "created" => "2016-05-06 12:12:12",
            "email" => "email1@spam4.me",
            "ip" => "127.0.0.1",
            "user_agent" => "chromium"
        ],
        [
            "country" => "USA",
            "created" => "2016 - 05 - 06 13:13:13",
            "email" => "email2@spam4.me",
            "ip" => "127.0.0.1",
            "user_agent" => "firefox"
        ],
        [
            "country" => "RU",
            "created" => "2016 - 05 - 06 11:11:11",
            "email" => "email3@spam4.me",
            "ip" => "127.0.0.1",
            "user_agent" => "opera"
        ]
    ]
];

$data_string = json_encode($data);

$ch = curl_init('http://localhost/subscription_service/server_api/api.php');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
    )
);

//$result = curl_exec($ch);
//curl_close($ch);
//print $result;

/************************************************/

$qry_str = "?apikey=SECRET_KEY&action=get_emails&limit=1&s=1";
$ch = curl_init('http://localhost/subscription_service/server_api/api.php' . $qry_str);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//$content = curl_exec($ch);
//curl_close($ch);
//print $content;
