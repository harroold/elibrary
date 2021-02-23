<?php
mb_internal_encoding("UTF-8");
session_start();
date_default_timezone_set('Europe/Prague');


$host = "localhost";
$port = 3306;
$dbname = "elibrary";
$username = "root";
$password = "";


$dsn = "mysql:host=$host;dbname=$dbname;port=$port;";
$driver_options = array(
   PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);               

function setFlash($content, $type) {
    $_SESSION["flash"][] = ["content" => $content, "type" => $type];
}

try {
    $db = new PDO($dsn, $username, $password, $driver_options);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    die();
}



