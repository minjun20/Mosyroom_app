<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$userfb = new userfb($dbo);

$referencePath = "users/";

$reference = $database->getReference($referencePath)->orderByChild("imported")->equalTo(false);

$snapshot = $reference->getSnapshot();

$result  = $snapshot->getValue();

foreach ($result as $key => $value) {

	$result = $userfb->add($key, $value['name'].' '.$value['userName']);
	$database->getReference($referencePath.$key.'/imported')->set(true);
}

?>