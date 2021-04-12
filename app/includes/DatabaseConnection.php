<?php
$host = getAppConfig('db_host');
$dbname = getAppConfig('db_name');
$user = getAppConfig('db_username');
$password = getAppConfig('db_password');
$pdo = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);