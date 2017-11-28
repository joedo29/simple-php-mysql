<?php
session_start();
ob_start();

defined("DS") ? null : define("DS",DIRECTORY_SEPARATOR);
defined("FRONT") ? null : define("FRONT",__DIR__.DS."front");
defined("DB_HOST") ? null : define("DB_HOST","localhost:3306");
defined("DB_USER") ? null : define("DB_USER","root");
defined("DB_PASS") ? null : define ("DB_PASS", "whistler");
defined("DB_NAME") ? null : define ("DB_NAME", "BookShare_test");

$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS);

if(!$connection){
    die("no connection");
}
require_once("functions.php");// creates link to functions so we can excess them 



?>