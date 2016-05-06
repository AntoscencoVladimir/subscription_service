--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 7.0.54.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 25.04.2016 17:14:22
-- Версия сервера: 5.5.46-0+deb7u1
-- Версия клиента: 4.1
--


-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

--
-- Описание для таблицы emails
--
DROP TABLE IF EXISTS emails;
CREATE TABLE emails (
  id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(255) DEFAULT NULL,
  reserved_server INT(11) UNSIGNED DEFAULT NULL,
  reserved_date DATETIME DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 7
AVG_ROW_LENGTH = 2730
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы emails_accessed
--
DROP TABLE IF EXISTS emails_accessed;
CREATE TABLE emails_accessed (
  id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  id_email BIGINT(20) UNSIGNED NOT NULL,
  ip VARCHAR(60) DEFAULT NULL,
  country VARCHAR(5) DEFAULT NULL,
  user_agent VARCHAR(255) NOT NULL,
  created DATETIME NOT NULL,
  PRIMARY KEY (id),
  INDEX IDX_emails_access_id_email (id_email),
  CONSTRAINT FK_emails_access_emails_id FOREIGN KEY (id_email)
    REFERENCES emails(id) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы emails_sended
--
DROP TABLE IF EXISTS emails_sended;
CREATE TABLE emails_sended (
  id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  id_email BIGINT(20) UNSIGNED NOT NULL,
  sender_server INT(11) UNSIGNED DEFAULT NULL,
  status TINYINT(1) UNSIGNED NOT NULL,
  created DATETIME NOT NULL,
  PRIMARY KEY (id),
  INDEX IDX_emails_sended_id_email (id_email),
  CONSTRAINT FK_emails_sended_emails_id FOREIGN KEY (id_email)
    REFERENCES emails(id) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы emails_unsubscribed
--
DROP TABLE IF EXISTS emails_unsubscribed;
CREATE TABLE emails_unsubscribed (
  id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  id_email BIGINT(20) UNSIGNED NOT NULL,
  ip VARCHAR(60) DEFAULT NULL,
  country VARCHAR(5) DEFAULT NULL,
  user_agent VARCHAR(255) NOT NULL,
  created DATETIME NOT NULL,
  PRIMARY KEY (id),
  INDEX IDX_emails_unsubscribed_id_email (id_email),
  CONSTRAINT FK_emails_unsubscribed_emails_id FOREIGN KEY (id_email)
    REFERENCES emails(id) ON DELETE CASCADE ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

-- 
-- Вывод данных для таблицы emails
--
INSERT INTO emails VALUES
(1, 'email1@spam4.me', NULL, NULL),
(2, 'email2@spam4.me', NULL, NULL),
(3, 'email3@spam4.me', NULL, NULL),
(4, 'email4@spam4.me', NULL, NULL),
(5, 'email5@spam4.me', NULL, NULL),
(6, 'email6@spam4.me', NULL, NULL);

-- 
-- Вывод данных для таблицы emails_accessed
--

-- Таблица test_email.emails_accessed не содержит данных

-- 
-- Вывод данных для таблицы emails_sended
--

-- Таблица test_email.emails_sended не содержит данных

-- 
-- Вывод данных для таблицы emails_unsubscribed
--

-- Таблица test_email.emails_unsubscribed не содержит данных

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;