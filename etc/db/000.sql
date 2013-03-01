--
-- ������ ������� ����, ������� ����� ��������� ������������
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
INSERT INTO `requests` (`title`, `url`) VALUES ("����������� ��", "register_ip");
INSERT INTO `requests` (`title`, `url`) VALUES ("����������� ���", "register_ooo");
INSERT INTO `requests` (`title`, `url`) VALUES ("����������� ��� ��� ���", "register_zao_oao");
INSERT INTO `requests` (`title`, `url`) VALUES ("������� �����", "buy_ready_firms");
INSERT INTO `requests` (`title`, `url`) VALUES ("�������� ���������� �����", "open_account");
INSERT INTO `requests` (`title`, `url`) VALUES ("����������� ���", "register_kkm");
INSERT INTO `requests` (`title`, `url`) VALUES ("����������� ������� ����� � ����", "register_emissions");
INSERT INTO `requests` (`title`, `url`) VALUES ("������������� �����������", "reorganize_firm");
INSERT INTO `requests` (`title`, `url`) VALUES ("���������� �����������", "liquidate_firm");
INSERT INTO `requests` (`title`, `url`) VALUES ("����������� ����� � ��������������� �������� ����� � ������������ ���������", "legal_address_full");
INSERT INTO `requests` (`title`, `url`) VALUES ("����������� ����� �� ����������� ����", "legal_address_economy");
INSERT INTO `requests` (`title`, `url`) VALUES ("�������� ����������� �����", "change_legal_address");
INSERT INTO `requests` (`title`, `url`) VALUES ("�������� ����������� �����", "prolong_legal_address");
INSERT INTO `requests` (`title`, `url`) VALUES ("������ ������������ ������ �� ����", "choice_legal_address");
INSERT INTO `requests` (`title`, `url`) VALUES ("������������� ������������", "accounting_service");
INSERT INTO `requests` (`title`, `url`) VALUES ("���������� ����", "rent_office");
INSERT INTO `requests` (`title`, `url`) VALUES ("������ ����", "buy_office");
INSERT INTO `requests` (`title`, `url`) VALUES ("��������� ������� ���������", "choice_placement");
INSERT INTO `requests` (`title`, `url`) VALUES ("����������� ������ � �������������", "register_estate_transactions");
INSERT INTO `requests` (`title`, `url`) VALUES ("����������� ������������", "legal_advice");
INSERT INTO `requests` (`title`, `url`) VALUES ("������������ ������� � �������", "manufacture_stamps");
INSERT INTO `requests` (`title`, `url`) VALUES ("�����-���� �� �������������� ������", "pricelist_extras");
INSERT INTO `requests` (`title`, `url`) VALUES ("�����-���� �� ����������� �����������", "pricelist_register");
INSERT INTO `requests` (`title`, `url`) VALUES ("�������� �� ����������� ������", "reliable_legal_address");

--
-- ������� email'�� ��� �������� �� ��� ������
--
CREATE TABLE `requests_email` (
`id` INT NOT NULL AUTO_INCREMENT ,
`email` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
);

--
-- Changelog ��������� � ��
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
-- ������� �������������
--
CREATE TABLE `users` (
`id` INT NOT NULL AUTO_INCREMENT ,
`login` VARCHAR( 255 ) NOT NULL ,
`passwd` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
);
INSERT INTO `users` (`login`, `passwd`) VALUES ("admin", MD5("werfgh"));

--
-- ������� ��������
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
-- ��������� �����
--
CREATE TABLE `ad_blocks` (
`id` INT NOT NULL AUTO_INCREMENT ,
`title` VARCHAR( 255 ) NOT NULL ,
`text` TEXT NOT NULL ,
`link` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
);

--
-- �������� �������� � ����� �������
--
CREATE TABLE `requests_option` (
`id` INT NOT NULL AUTO_INCREMENT ,
`request_id` INT NOT NULL ,
`field` VARCHAR( 50 ) NOT NULL ,
`value` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
);

--
-- �������� ������� � �������
--
CREATE TABLE `requests_banner` (
`id` INT NOT NULL AUTO_INCREMENT ,
`request_id` INT NOT NULL ,
`banner_id` INT NOT NULL ,
PRIMARY KEY ( `id` )
);

--
-- �������� ��������� ������ � �������
--
CREATE TABLE `requests_adblock` (
`id` INT NOT NULL AUTO_INCREMENT ,
`request_id` INT NOT NULL ,
`adblock_id` INT NOT NULL ,
PRIMARY KEY ( `id` )
);


--
--  ������ �� ������� ������� ����
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
-- ������ �� ������� �������� ��� �������
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
-- ������ �� ������� ��.�������
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