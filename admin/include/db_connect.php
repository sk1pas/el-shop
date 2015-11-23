<?php
defined('myyshop') or die('Доступа нет!');
$db_host     = 'localhost';
$db_user     = 'admin';
$db_pass     = '11223344';
$db_database = 'db_shop';
$link = mysql_connect($db_host, $db_user, $db_pass) or die("Нет соединения с БД".mysql_error());
//$link = mysql_connect($db_database, $link) or die("Нет соединения с БД".mysql_error());
mysql_select_db($db_database) or die("Не могу подключиться к базе.");
mysql_query("SET names cp1251");
?>