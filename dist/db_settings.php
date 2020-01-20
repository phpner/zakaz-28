<?php

/*Подключение к БД*/
$user = "root";
$pass = "";
$dbh = new PDO('mysql:host=localhost;dbname=zakaz-28', $user, $pass);
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);