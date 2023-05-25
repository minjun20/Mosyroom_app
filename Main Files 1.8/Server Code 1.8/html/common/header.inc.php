<!DOCTYPE html>
<html lang="en" class="no-js">

<head>

<?php

$arrayUrl = explode('/',$_SERVER['REQUEST_URI']);

if (count($arrayUrl) == 3) {

    $feedId = $arrayUrl[2];
    $typeFeed = $arrayUrl[1];

    $feedId = trim($feedId);
    $feedId = strip_tags($feedId);
    $feedId = htmlspecialchars($feedId);

    $typeFeed = trim($typeFeed);
    $typeFeed = strip_tags($typeFeed);
    $typeFeed = htmlspecialchars($typeFeed);

    if (strlen($feedId) > 5) {

        if ($typeFeed == "feedVip") {
          $referencePath = "FeedsVip/".$feedId;
        }else if ($typeFeed == "question") {
          $referencePath = "Questions/".$feedId;
        }else {
          $referencePath = "Feeds/".$feedId;
        }

        $snapshot =  $database->getReference($referencePath)->getSnapshot();
        $result  = $snapshot->getValue();

        if ($result != null) {

            if (array_key_exists('text', $result)) {
                echo "<meta name='description' content='".$result['text']."'>";
                echo "<meta property='og:description' content='".$result['text']."'>";
            }

            if (array_key_exists('images', $result)) 
                echo "<meta property='og:image' content='".$result['images'][0]."'>";
            else
                echo "<meta property='og:image' content='".APP_URL."/img/logo.png'>";

            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            echo "<meta property='og:url' content='".$actual_link."' />";

        }else{
     
                echo "<meta property='og:image' content='".APP_URL."/img/logo.png'>";
                echo "<meta name='description' content='".META_TAG_DESCRIPTION."'>";
                echo "<meta property='og:description' content='".META_TAG_DESCRIPTION."'>";
        }

    }
}else{
    echo "<meta property='og:image' content='".APP_URL."/img/logo.png'>";
    echo "<meta name='description' content='".META_TAG_DESCRIPTION."'>";
    echo "<meta property='og:description' content='".META_TAG_DESCRIPTION."'>";
}


?>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta property="og:type" content="article" />
  
  <meta property="og:title" content="<?php echo APP_NAME ?> publication">
  <meta name="keywords" content="<?php echo META_TAG_KEYWORDS ?>">
  <meta name="author" content="<?php echo APP_NAME ?>">

  <title><?php echo $page_title; ?></title>


    <link href="https://fonts.googleapis.com/css?family=Heebo:400,700|Oxygen:700" rel="stylesheet">
    <link rel="stylesheet" href="/landingPage/dist/css/style.css">
    <script src="https://unpkg.com/scrollreveal@4.0.5/dist/scrollreveal.min.js"></script>

</head>