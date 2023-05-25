<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$feed = new feed($dbo);

    $reponse0 = array("reponse" =>  array());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $apiKey = isset($_POST['API_KEY_SERVER']) ? $_POST['API_KEY_SERVER'] : '';
    $searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';

    $apiKey = trim($apiKey);
    $apiKey = strip_tags($apiKey);
    $apiKey = htmlspecialchars($apiKey);

    $searchQuery = trim($searchQuery);
    $searchQuery = strip_tags($searchQuery);
    $searchQuery = htmlspecialchars($searchQuery);

    if ($apiKey == API_KEY_SERVER && strlen($searchQuery) > 2) {

    $result = $feed->search($searchQuery);

    //print_r($result);
    array_push($reponse0['reponse'], $result);

	}
}
echo json_encode($reponse0);

?>
