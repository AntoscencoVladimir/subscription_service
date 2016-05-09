<?php

/**
 * Todo: Главная задача данного сервера:
 * 1. На запрос примера: &limit=100&s=1, чтобы отвечал списком 100 емайлов
 * (которые не зарезервированны другим сервером + не находиться в списке emails_unsubscribed),
 * и во время ответа, сервер должен зарезервировать данные отосланные емайла под сервер 1 в колонке "reserved_server" из таблицы "emails".
 * 2. Добавляет статистику в таблицу emails_accessed
 * 3. Добавляет статистику в таблицу emails_sended
 * 4. Добавляет статистику в таблицу emails_unsubscribed
 */

require_once 'autoload.php';

$request = new Request();
$api = new ApiController();

$result = ['result' => false, 'error' => true];

if ($request->hasProperty('apikey') && $request->apikey === Config::SECRET) {
    if ($request->hasProperty('action')) {
        $action = Strings::underscoreToCamelCase($request->action) . 'Action';
        if (method_exists($api, $action)) {
            $result['result'] = call_user_func([$api, $action], $request);
            $result['error'] = false;
        }
    }

}

header('Content-Type: application/json;charset=utf-8');
echo json_encode($result);
die;
