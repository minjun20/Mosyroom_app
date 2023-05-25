<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$feed_vip = new feed_vip($dbo);

$referencePath = "FeedsVip/";

$reference = $database->getReference($referencePath)->orderByChild("imported")->equalTo(false);

$snapshot = $reference->getSnapshot();

$result  = $snapshot->getValue();

foreach ($result as $key => $value) {

	$result = $feed_vip->add($key, $value['text'], $value['userId'], $value['timestamp']);
	$database->getReference($referencePath.$key.'/imported')->set(true);
}

?>