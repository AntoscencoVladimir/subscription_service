<?php

/**
Todo: Главная задача данного сервера:
1. На запрос примера: &limit=100&s=1, чтобы отвечал списком 100 емайлов 
(которые не зарезервированны другим сервером + не находиться в списке emails_unsubscribed), 
и во время ответа, сервер должен зарезервировать данные отосланные емайла под сервер 1 в колонке "reserved_server" из таблицы "emails".
2. Добавляет статистику в таблицу emails_accessed
3. Добавляет статистику в таблицу emails_sended
4. Добавляет статистику в таблицу emails_unsubscribed
*/

require_once 'autoload.php';

/*
$test = new EmailsEntity();
$result = $test->findByField('emails', 'EmailsEntity', 'email', 'email2@spam4.me');
echo  $result[2]->email;
var_dump($result);
*/

$request = new Request();
$api = new ApiController();
$result = false;

if ($request->hasProperty('apikey') && $request->apikey === Config::SECRET ) {
    if ($request->hasProperty('action')) {
        switch ($request->action){
            case 'get_emails':
                $result['result'] = $api->getEmails($request->limit,$request->s);
                $result['error'] = false;
                break;
            case 'set_emails_accessed':
                $result['result'] = 'success';
                $result['error']  = false;
                break;
            case 'set_emails_sended':
                $result['result'] = 'success';
                $result['error']  = false;
                break;
            case 'set_emails_unsubsribed':
                $result['result'] = 'success';
                $result['error']  = false;
                break;
            default:
                $result = ['result' => false, 'error'=> true];
                break;
        }
    }

} else {
    $result = ['result' => false, 'error' => true];
}

echo json_encode($result);
die;
