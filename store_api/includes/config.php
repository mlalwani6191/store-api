<?php
date_default_timezone_set("Asia/Calcutta");
function classLoader($class) {
    include 'classes/' . $class . '.php';
}
spl_autoload_register('classLoader');

/*Database settings */
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD","");
define("DB_NAME", "demo_store");
define("COOKIE_FILE_NAME", $_SERVER['DOCUMENT_ROOT'].'/cookie.txt');
define("API_POST_URL", "http://localhost/store_api/api.php/");

/*
* Create the instance of Database 
*
*/
$instance = Database::getInstance(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$objDatabase = $instance->getConnection();
?>
