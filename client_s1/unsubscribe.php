<?php

/**
 * Todo: Главная задача файла:
 *
 * Регистрируеться ОТПИСКА, и показывает что УСПЕШНАЯ ОТПИСКА
 */

require_once 'autoload.php';

$view = new View(MAIL_CLIENT_ROOT_DIR . '/views/');
if (isset($_GET['h'])) {
    $entity = new EmailsEntity();
    $entity->findOneByField('hash', $_GET['h']);
    if ($entity->getId()) {
        $entity->ip = Tools::getClientIp();
        $entity->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $entity->country = geoip_country_code3_by_name(Tools::getClientIp());
        $entity->unsubscribed = 1;
        $entity->unsubscribed_at = date('Y-m-d H:i:s');
        if ($entity->save()) {
            $view->render('template_success_unsubscribed', ['email' => $entity->email]);
            exit(0);
        };
    }
}

$view->render('template_404');