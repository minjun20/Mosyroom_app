<?php


$referencePath = "users/";

$reference = $database->getReference($referencePath)->orderByChild("deleted")->equalTo(true);

$snapshot = $reference->getSnapshot();

$result  = $snapshot->getValue();

foreach ($result as $key => $value) {



/*
Delete Auth
*/
$auth->deleteUser($key);


/*
Delete myfeeds and all Feeds/vip/questions
*/
$database->getReference('myFeeds/'.$key)->set(null);

$referencePath0 = "Feeds/";
$reference0 = $database->getReference($referencePath0)->orderByChild("userId")->equalTo($key);
$snapshot0 = $reference0->getSnapshot();
$result0  = $snapshot0->getValue();
foreach ($result as $key0 => $value0) {
$database->getReference($referencePath0.$key0)->set(null);
}

$referencePath0 = "FeedsVip/";
$reference0 = $database->getReference($referencePath0)->orderByChild("userId")->equalTo($key);
$snapshot0 = $reference0->getSnapshot();
$result0  = $snapshot0->getValue();
foreach ($result as $key0 => $value0) {
$database->getReference($referencePath0.$key0)->set(null);
}

$referencePath0 = "Questions/";
$reference0 = $database->getReference($referencePath0)->orderByChild("userId")->equalTo($key);
$snapshot0 = $reference0->getSnapshot();
$result0  = $snapshot0->getValue();
foreach ($result as $key0 => $value0) {
$database->getReference($referencePath0.$key0)->set(null);
}



/*
Delete UserName - token
*/


$referencePath0 = "userNames/";
$database->getReference($referencePath0.$value['userName'])->set(null);

$referencePath0 = "user_fcm_ids/";
$database->getReference($referencePath0.$key)->set(null);

/*
Delete Notifications
*/

$referencePath0 = "Notifications/";
$database->getReference($referencePath0.$key)->set(null);

/*
Change Name userName image
*/
$database->getReference($referencePath.$key.'/name')->set("Account Deleted");
$database->getReference($referencePath.$key.'/userName')->set("deleted");
$database->getReference($referencePath.$key.'/image')->set("");
$database->getReference($referencePath.$key.'/token')->set("");

}


