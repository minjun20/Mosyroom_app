<?php

include_once("../sys/config/db.inc.php");

foreach ($C as $name => $val) {

    define($name, $val);
}

foreach ($B as $name => $val) {

    define($name, $val);
}

$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
$dbo = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));

spl_autoload_register(function($class)
{
    $filename = "../sys/class/class.".$class.".inc.php";

    if (file_exists($filename)) {

        include_once($filename);
    }
});

if(!isset($_SESSION)) {

    ini_set('session.cookie_domain', '.'.APP_HOST);
    session_set_cookie_params(0, '/', '.'.APP_HOST);
}

$helper = new helper($dbo);


// Firebase admin SDK PHP ****************************************
require __DIR__.'/vendor/autoload.php';


use Kreait\Firebase\Factory;
use Kreait\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Database\RuleSet;

$factory = (new Factory)
    ->withServiceAccount(__DIR__.'/secret/'.GOOGLE_SERVICE_ACCOUNT_JSON_FILE_NAME);

//=========================================================================
$databaseUriPattern = 'https://'.FIREBASE_DB_NAME.'.firebaseio.com';

$factory_database = (new Factory())
    ->withServiceAccount(__DIR__.'/secret/'.GOOGLE_SERVICE_ACCOUNT_JSON_FILE_NAME)
    ->withDatabaseUri($databaseUriPattern);

$database = $factory_database->createDatabase();
//=========================================================================
$db_url_rules = FIREBASE_DB_NAME;

$pos = strpos(FIREBASE_DB_NAME, '.');

if ($pos === false) {
} else {
    $db_url_rules = strtok(FIREBASE_DB_NAME, '.');
}

$databaseRulesUriPattern = 'https://'.$db_url_rules.'.firebaseio.com';

$factory_rules_database = (new Factory())
    ->withServiceAccount(__DIR__.'/secret/'.GOOGLE_SERVICE_ACCOUNT_JSON_FILE_NAME)
    ->withDatabaseUri($databaseRulesUriPattern);

$databaseRules = $factory_rules_database->createDatabase();
//=========================================================================

$auth = $factory->createAuth();
$messaging = $factory->createMessaging();
$notificationFirebase = Notification::create("title", "body");

// End Firebase admin SDK PHP **********************************