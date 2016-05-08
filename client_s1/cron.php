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

$controller = new RequestApiController();

print $controller->getEmailsAction();
print $controller->sendAccessedStatisticAction();
print $controller->sendSendedEmailsStatisticAction();
print $controller->sendUnsubscribedStatisticAction();