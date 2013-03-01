--
-- Список главных форм, которые может заполнить пользователь
--
CREATE TABLE `requests` (
`id` INT NOT NULL AUTO_INCREMENT ,
`title` VARCHAR( 255 ) NOT NULL ,
`is_active` TINYINT NOT NULL DEFAULT 1,
`url` VARCHAR( 255 ) NOT NULL ,
`descr` TEXT,
`descr_wiki` TEXT,
`descrfull` TEXT,
`descrfull_wiki` TEXT,
`is_descrfull_active` SMALLINT,
`file`  VARCHAR( 255 ) NOT NULL
PRIMARY KEY ( `id` )
);
INSERT INTO `requests` (`title`, `url`) VALUES ("Регистрация ИП", "register_ip");
INSERT INTO `requests` (`title`, `url`) VALUES ("Регистрация ООО", "register_ooo");
INSERT INTO `requests` (`title`, `url`) VALUES ("Регистрация ЗАО или ОАО", "register_zao_oao");
INSERT INTO `requests` (`title`, `url`) VALUES ("Готовые фирмы", "buy_ready_firms");
INSERT INTO `requests` (`title`, `url`) VALUES ("Открытие расчетного счета", "open_account");
INSERT INTO `requests` (`title`, `url`) VALUES ("Регистрация ККМ", "register_kkm");
INSERT INTO `requests` (`title`, `url`) VALUES ("Регистрация эмиссии акций в ФСФР", "register_emissions");
INSERT INTO `requests` (`title`, `url`) VALUES ("Реорганизация предприятий", "reorganize_firm");
INSERT INTO `requests` (`title`, `url`) VALUES ("Ликвидация предприятий", "liquidate_firm");
INSERT INTO `requests` (`title`, `url`) VALUES ("Юридический адрес с предоставлением рабочего места и безналичными платежами", "legal_address_full");
INSERT INTO `requests` (`title`, `url`) VALUES ("Юридический адрес по экономичной цене", "legal_address_economy");
INSERT INTO `requests` (`title`, `url`) VALUES ("Изменить юридический адрес", "change_legal_address");
INSERT INTO `requests` (`title`, `url`) VALUES ("Продлить юридический адрес", "prolong_legal_address");
INSERT INTO `requests` (`title`, `url`) VALUES ("Подбор юридического адреса по ИФНС", "choice_legal_address");
INSERT INTO `requests` (`title`, `url`) VALUES ("Бухгалтерское обслуживание", "accounting_service");
INSERT INTO `requests` (`title`, `url`) VALUES ("Арендовать офис", "rent_office");
INSERT INTO `requests` (`title`, `url`) VALUES ("Купить офис", "buy_office");
INSERT INTO `requests` (`title`, `url`) VALUES ("Подобрать нежилое помещение", "choice_placement");
INSERT INTO `requests` (`title`, `url`) VALUES ("Регистрация сделок с недвижимостью", "register_estate_transactions");
INSERT INTO `requests` (`title`, `url`) VALUES ("Юридическая консультация", "legal_advice");
INSERT INTO `requests` (`title`, `url`) VALUES ("Производство печатей и штампов", "manufacture_stamps");
INSERT INTO `requests` (`title`, `url`) VALUES ("Прайс-лист на дополнительные услуги", "pricelist_extras");
INSERT INTO `requests` (`title`, `url`) VALUES ("Прайс-лист на регистрацию организаций", "pricelist_register");
INSERT INTO `requests` (`title`, `url`) VALUES ("Гарантии на юридические адреса", "reliable_legal_address");

--
-- Таблица email'ов для отправки на них заявок
--
CREATE TABLE `requests_email` (
`id` INT NOT NULL AUTO_INCREMENT ,
`email` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
);

--
-- Changelog изменений в БД
--
CREATE TABLE `common_changelog` (
    `id`                INT NOT NULL AUTO_INCREMENT ,
    `file`              VARCHAR(255) NOT NULL,
    `query_at`          TIMESTAMP  NOT NULL,
    `query_by`          VARCHAR(255)  NOT NULL,
    `time`              FLOAT  NOT NULL,
    PRIMARY KEY ( `id` )
);
INSERT INTO `common_changelog` (`file`, `query_at`, `query_by`, `time`) VALUES ("/000.sql", "1970-01-01", "root", 0);

--
-- Таблица пользователей
--
CREATE TABLE `users` (
`id` INT NOT NULL AUTO_INCREMENT ,
`login` VARCHAR( 255 ) NOT NULL ,
`passwd` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
);
INSERT INTO `users` (`login`, `passwd`) VALUES ("admin", MD5("werfgh"));

--
-- Таблица баннеров
--
CREATE TABLE `banners` (
`id` INT NOT NULL AUTO_INCREMENT ,
`title` VARCHAR( 255 ) NOT NULL ,
`file` VARCHAR( 255 ) NOT NULL ,
`width` SMALLINT ,
`height` SMALLINT ,
`type` VARCHAR( 5 ) NOT NULL ,
PRIMARY KEY ( `id` )
);

--
-- Рекламные блоки
--
CREATE TABLE `ad_blocks` (
`id` INT NOT NULL AUTO_INCREMENT ,
`title` VARCHAR( 255 ) NOT NULL ,
`text` TEXT NOT NULL ,
`link` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
);

--
-- Варианты селектов в форме запроса
--
CREATE TABLE `requests_option` (
`id` INT NOT NULL AUTO_INCREMENT ,
`request_id` INT NOT NULL ,
`field` VARCHAR( 50 ) NOT NULL ,
`value` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
);

--
-- Привязка банеров к запросу
--
CREATE TABLE `requests_banner` (
`id` INT NOT NULL AUTO_INCREMENT ,
`request_id` INT NOT NULL ,
`banner_id` INT NOT NULL ,
PRIMARY KEY ( `id` )
);

--
-- Привязка рекламных блоков к запросу
--
CREATE TABLE `requests_adblock` (
`id` INT NOT NULL AUTO_INCREMENT ,
`request_id` INT NOT NULL ,
`adblock_id` INT NOT NULL ,
PRIMARY KEY ( `id` )
);


--
--  Данные со списком готовых фирм
--
CREATE TABLE `data_ready_firms` (
`id` INT NOT NULL AUTO_INCREMENT ,
`title` VARCHAR( 255 ) NOT NULL ,
`register_at` DATE NOT NULL ,
`ifns` SMALLINT ,
`license` VARCHAR( 255 ) NOT NULL ,
`current_account` VARCHAR( 255 ) NOT NULL ,
`share_capital` VARCHAR( 255 ) NOT NULL ,
`taxation` VARCHAR( 255 ) NOT NULL ,
`director` VARCHAR( 255 ) NOT NULL ,
`price` DOUBLE NOT NULL ,
PRIMARY KEY ( `id` )
);


--
-- Данные со списком оснасток для печатей
--
CREATE TABLE `data_stamp_types` (
`id` INT NOT NULL AUTO_INCREMENT ,
`title` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
);
CREATE TABLE `data_stamp_models` (
`id` INT NOT NULL AUTO_INCREMENT ,
`type_id` INT NOT NULL ,
`title` VARCHAR( 255 ) NOT NULL ,
`size` VARCHAR( 50 ) NOT NULL ,
`price` VARCHAR( 50 ) NOT NULL ,
`file` VARCHAR( 255 ) ,
`file_width` SMALLINT ,
`file_height` SMALLINT ,
PRIMARY KEY ( `id` )
);


--
-- Данные со списком юр.адресов
--
CREATE TABLE `data_legal_addresses` (
`id` INT NOT NULL AUTO_INCREMENT ,
`district` VARCHAR( 255 ) ,
`ifns` SMALLINT ,
`index` VARCHAR( 255 ) ,
`address` VARCHAR( 255 ) ,
`rent_per_11` VARCHAR( 255 ) ,
`po_per_month` VARCHAR( 255 ) ,
`commission` VARCHAR( 255 ) ,
`contract_form` VARCHAR( 255 ) ,
`flag_1` SMALLINT ,
`flag_2` SMALLINT ,
`flag_3` SMALLINT ,
`flag_4` SMALLINT ,
`flag_5` SMALLINT ,
`flag_6` SMALLINT ,
`flag_7` SMALLINT ,
`flag_8` SMALLINT ,
`flag_9` SMALLINT ,
`flag_10` SMALLINT ,
`flag_11` SMALLINT ,
`flag_12` VARCHAR( 255 ) ,
`flag_13` VARCHAR( 255 ) ,
PRIMARY KEY ( `id` )
);