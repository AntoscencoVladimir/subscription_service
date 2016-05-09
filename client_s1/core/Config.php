<?php

class Config
{
    const CLIENT_ID = 1;
    const API_SECRET_KEY = 'SECRET_KEY';
    const SERVER_API_URL = 'http://localhost/subscription_service/server_api/api.php';
    const CLIENT_SITE_URL = 'http://localhost/subscription_service/client_s1';
    const GET_EMAILS_LIMIT = 2;
    const FROM_EMAIL = 'webmaster@example.com';
    const FROM_NAME = 'webmaster';
    const STATISTICS_TIMEOUT = 3600;
    //const STATISTICS_TIMEOUT = 1;

    const DB_NAME = 'database.db';
    const DB_FULL_PATH = MAIL_CLIENT_ROOT_DIR . '/database';
    const DB_SYM_QUERY = '?';
}