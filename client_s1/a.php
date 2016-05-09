<?php

/**
 * Todo: Главная задача файла:
 *
 * Регистририруеться переход, IP, браузер и перенаправляет на адрес http://google.com. В базе параметра
 * ?id=XXX можно выташить информацию как: email, когда было отосланно. Каждой письме отправленной cron.php,
 * будет сгенерирован уникальный ID для ?h=XXX
 */

require_once 'autoload.php';

if (isset($_GET['h'])) {
    $entity = new EmailsEntity();
    $entity->findOneByField('hash', $_GET['h']);
    if ($entity->getId()) {
        $entity->ip = Tools::getClientIp();
        $entity->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $entity->accessed = 1;
        $entity->country = geoip_country_code3_by_name(Tools::getClientIp());
        $entity->accessed_at = date('Y-m-d H:i:s');
        $entity->save();
    }
}

header('Location: http://google.com');