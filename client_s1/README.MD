##Настройки клиента находятся в файле core/Config.php

Целочисленный идентификационный номер сервера
> CLIENT_ID = '1'

Секретный API ключ, который указан в настройках API сервера
> API_SECRET_KEY = 'YOUR_PRIVATE_KEY'

URL API сервера
> SERVER_API_URL = 'http://localhost/subscription_service/server_api/api.php';

URL сервера, который отсылает письма
> CLIENT_SITE_URL = 'http://localhost/subscription_service/client_s1';

Количество запрашиваемых имейлов с сервера
> GET_EMAILS_LIMIT = 2;

От какого имейла отсылать письма
> FROM_EMAIL =  'webmaster@example.com';

Период времени между отправкой статистики в секундах (3600 = 1 час)
>STATISTICS_TIMEOUT = 3600; (1 час в секундах)

От чьего имени отсылать имейлы
> FROM_NAME = 'webmaster';

Имя файла БД SQLite3
> DB_NAME = 'database.db';

Путь к директории с базой данных
> DB_FULL_PATH = MAIL_CLIENT_ROOT_DIR . '/database';

Заменяемый символ в запросах
> DB_SYM_QUERY = '?';

##Требования к серверу:

[PHP](https://secure.php.net/downloads.php) >= 5.6.16

[Apache HTTP Server](https://httpd.apache.org/) >= 2.4.17

[Pecl GeoIP](https://pecl.php.net/package/geoip/1.1.0/) >= 1.1.0

[GeoIP.dat](http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz)

[SQLite3](https://www.sqlite.org/download.html) >= 3.12.2

[php-sqlite3](https://secure.php.net/manual/ru/book.sqlite3.php)

[php-curl](https://secure.php.net/manual/ru/book.curl.php)

[sendmail](http://www.sendmail.com/sm/open_source/)



##Дополнительная информация

[Настройка Senmail'a](https://linuxconfig.org/configuring-gmail-as-sendmail-email-relay)