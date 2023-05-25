<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$feed = new feed($dbo);

$reponse0 = array("reponse" =>  array());
$result = array();

//&API_KEY_SERVER=azerty123456789&userId=dIw3XQQpsqgWyjEQp4dHvoaiB1D2&timestamp=1610276500865

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $apiKey = isset($_POST['API_KEY_SERVER']) ? $_POST['API_KEY_SERVER'] : '';
    $userId = isset($_POST['userId']) ? $_POST['userId'] : '';
    $timestamp = isset($_POST['timestamp']) ? $_POST['timestamp'] : '';

    $apiKey = trim($apiKey);
    $apiKey = strip_tags($apiKey);
    $apiKey = htmlspecialchars($apiKey);

    $userId = trim($userId);
    $userId = strip_tags($userId);
    $userId = htmlspecialchars($userId);

    $timestamp = trim($timestamp);
    $timestamp = strip_tags($timestamp);
    $timestamp = htmlspecialchars($timestamp);
    
    if ($apiKey == API_KEY_SERVER && strlen($userId) > 7) {


//============= GET FOLLOWINGS ============================
        $referencePath = "Followings/".$userId;

        $reference = $database->getReference($referencePath);

        $snapshot = $reference->getSnapshot();

        $resultF  = $snapshot->getValue();

        //print_r($resultF);

        if ($resultF != null) {


            array_push($result, ...$feed->getByUserId(array_keys($resultF), $timestamp));


/*
            foreach ($resultF as $key => $value) {

                array_push($result, ...$feed->getByUserId($key, $timestamp));

            }*/
        }

//=========================================

    //print_r($result);
    array_push($reponse0['reponse'], ...$result);

	}
}

echo json_encode($reponse0);

?>
