create database if not exists spav
  default character set utf8
  collate utf8_unicode_ci;
use `spav`;

drop table if exists `application_message`;

create table `application_message` (
  `id`          int(11)                      not null auto_increment,
  `language_id` int(11)                      not null,
  `text`        text collate utf8_unicode_ci not null,
  `sql_state`   int(11)                      not null,
  `active`      tinyint(2)                            default '1',
  primary key (`id`)
)
  engine = InnoDB
  auto_increment = 47
  default charset = utf8
  collate = utf8_unicode_ci;

--
-- Dumping data for table `application_message`
--

lock tables `application_message` write;
alter table `application_message`
  disable keys;

insert into `application_message`
values
  (2, 2, 'Пустые значения', -2, 1),
  (3, 1, 'Empty values', -2, 1),
  (9, 2, 'Непредвиденная ошибка', -9, 1),
  (10, 2, 'Unexpected error', -9, 1)
;

alter table `application_message`
  enable keys;
unlock tables;

--
-- Table structure for table `error_log`
--

drop table if exists `error_log`;


create table `error_log` (
  `id`         int(11)                      not null auto_increment,
  `text`       text collate utf8_unicode_ci not null,
  `created_at` datetime                     not null default CURRENT_TIMESTAMP,
  `user_id`    int(11)                               default null,
  primary key (`id`),
  unique key `yf_error_log_id_uindex` (`id`),
  key `fk_yf_error_log_1_idx` (`user_id`)
)
  engine = InnoDB
  auto_increment = 63
  default charset = utf8
  collate = utf8_unicode_ci;

--
-- Table structure for table `language`
--

drop table if exists `language`;


create table `language` (
  `id`   int(11)                 not null auto_increment,
  `name` varchar(45)
         collate utf8_unicode_ci not null,
  primary key (`id`)
)
  engine = InnoDB
  auto_increment = 3
  default charset = utf8
  collate = utf8_unicode_ci;

--
-- Dumping data for table `language`
--

lock tables `language` write;
alter table `language`
  disable keys;

insert into `language` values (1, 'en_US'), (2, 'ru_RU');

alter table `language`
  enable keys;
unlock tables;

--
-- Table structure for table `test_table`
--

drop table if exists `test_table`;


create table `test_table` (
  `id`   int(11)                 not null auto_increment,
  `key`  varchar(100)
         collate utf8_unicode_ci not null,
  `name` varchar(100)
         collate utf8_unicode_ci          default null,
  primary key (`id`),
  unique key `test_table_id_uindex` (`id`),
  unique key `test_table_key_uindex` (`key`)
)
  engine = InnoDB
  auto_increment = 104
  default charset = utf8
  collate = utf8_unicode_ci;
