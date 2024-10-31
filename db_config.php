<?php

require_once realpath(__DIR__ . "/vendor/autoload.php");

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$hostName = $_ENV['hostName'];
$userName = $_ENV['userName'];
$password = $_ENV['password'];
$dbName = $_ENV['dbName'];

$conn = new mysqli($hostName, $userName, $password, $dbName);
if ($conn) {
   // echo "connected";	
} else {
   // echo "not connected";
}
