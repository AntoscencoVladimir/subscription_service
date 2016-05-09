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

require_once 'autoload.php';

$service = new Service('php-cron-' . Config::CLIENT_ID . '.lock');

$controller = new RequestApiController();

while (1){
    $emails = json_decode($controller->getEmailsAction(), true);
    if ($emails['error'] === false && !empty($emails['result'])){
        $view = new View(MAIL_CLIENT_ROOT_DIR . '/views/');

        foreach ($emails['result'] as $emailTo) {
            $hash = sha1($emailTo);
            $emailObj = new Mail($view,Config::FROM_EMAIL);
            $emailObj->setFromName(Config::FROM_NAME);
            $result = $emailObj->send($emailTo,['url' => Config::CLIENT_SITE_URL .'/', 'hash' => $hash ], 'template_email');
            
            $entity = new EmailsEntity();
            $entity->email = $emailTo;
            $entity->status = (int) $result;
            $entity->hash = $hash;
            $entity->sended_at = date('Y-m-d H:i:s');
            $entity->save();
        }
    }
    usleep(1000);
    
    if ($service->getRuntime() >= Config::STATISTICS_TIMEOUT) {
        $controller->sendAccessedStatisticAction();
        $controller->sendSendedEmailsStatisticAction();
        $controller->sendUnsubscribedStatisticAction();
        $service->resetTimer();
    }
}


