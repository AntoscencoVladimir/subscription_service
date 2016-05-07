<?php

/**
 * Todo: Главная задача файла:
 *
 * Регистририруеться переход, IP, браузер и перенаправляет на адрес http://google.com. В базе параметра
 * ?id=XXX можно выташить информацию как: email, когда было отосланно. Каждой письме отправленной cron.php,
 * будет сгенерирован уникальный ID для ?h=XXX
 */

require_once 'autoload.php';

function get_client_ip_env() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}


if (isset($_GET['h'])) {
    $entity = new EmailsEntity();
    $entity->findOneByField('hash', $_GET['h']);
    if ($entity->getId()) {
        $entity->ip = get_client_ip_env();
        $entity->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $entity->accessed = 1;
        $entity->country = geoip_country_code3_by_name(get_client_ip_env());
        $entity->save();
    }
}

header('Location: http://google.com');