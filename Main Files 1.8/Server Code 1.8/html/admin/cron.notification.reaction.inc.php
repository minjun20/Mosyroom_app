<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;


$referencePath = "Notifications/";

$reference = $database->getReference($referencePath);

$snapshot = $reference->getSnapshot();

$result  = $snapshot->getValue();

foreach ($result as $key => $value) {


		$referencePath0 = "Notifications/".$key;

		$reference0 = $database->getReference($referencePath0)->orderByKey()->limitToLast(1);

		$snapshot0 = $reference0->getSnapshot();

		$result0  = $snapshot0->getValue();

		$lastKey = endKey($result0);
		//echo $lastKey;

		if ($lastKey != "") {

			$lastMsg = $result0[$lastKey];

            if (array_key_exists('timestamp', $lastMsg) 
            	&& array_key_exists('delivered', $lastMsg)
            	&& array_key_exists('notificationType', $lastMsg)
            	&& array_key_exists('toUserId', $lastMsg)) {
			
				$timeparts = explode(" ",microtime());
				$currenttime = bcadd(($timeparts[0]*1000),bcmul($timeparts[1],1000));

				$diffrence = ($currenttime - $lastMsg['timestamp'])/1000;

				if ($lastMsg['delivered'] == false && $diffrence >= 15 && $diffrence <= 915) {

										
					$referencePathU = "user_fcm_ids/".$lastMsg['toUserId'];

					$referenceU = $database->getReference($referencePathU);

					$snapshotU = $referenceU->getSnapshot();

					$token  = $snapshotU->getValue();

					if (is_string($token) && strlen($token)>0) {


							$title = APP_NAME;
							$body = BODY_NOTIFICATION_REACTION;

			                $notification = Notification::fromArray([
			                    'title' => $title,
			                    'body' => $body,
			                ]);

	    	                $notification = Notification::create($title, $body);

	                		$messaging = $factory->createMessaging();

			                $message = CloudMessage::withTarget('token', $token)
			                ->withNotification($notification) 
			                ;


			                 $messaging->send($message);

					}
				}

			}
		}
/*
		foreach ($result0 as $key0 => $value0) {
			echo $key0;

		}
*/
	
}

function endKey($array){
end($array);
return key($array);
}

?>

