<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$feed = new feed($dbo);

$referencePath = "Feeds/";

$reference = $database->getReference($referencePath)->orderByChild("imported")->equalTo(false);

$snapshot = $reference->getSnapshot();

$result  = $snapshot->getValue();

foreach ($result as $key => $value) {

	$result = $feed->add($key, $value['text'], $value['userId'], $value['timestamp']);
	$database->getReference($referencePath.$key.'/imported')->set(true);
}

?>