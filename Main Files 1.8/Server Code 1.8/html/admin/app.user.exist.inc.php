<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//&API_KEY_SERVER=6cec6c53-7a8e-4948-bae8-54931f21f238&phone=213700000001

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $apiKey = isset($_POST['API_KEY_SERVER']) ? $_POST['API_KEY_SERVER'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

    $apiKey = trim($apiKey);
    $apiKey = strip_tags($apiKey);
    $apiKey = htmlspecialchars($apiKey);

    $phone = trim($phone);
    $phone = strip_tags($phone);
    $phone = htmlspecialchars($phone);

    if ($apiKey == API_KEY_SERVER && strlen($phone) > 7 && !preg_match('#[^0-9]#',$phone)) {


        $reponse = array("exist" => false);

        $auth = $factory->createAuth();

        try {

            $user = $auth->getUserByPhoneNumber("+".$phone); // '+88700800001'
            $user2 = $auth->getUserByEmail($phone."@email.com"); // '88700800001@email.com'

            $reponse['exist'] = true;
        } catch (Throwable $e) {
            //echo $e->getMessage();
            $reponse['exist'] = false;
        }

        echo json_encode($reponse);

    }
}
?>
