<?php

// ===========================================================================
// ===========================================================================
        $referencePath = "users";
        $reference = $database->getReference($referencePath);
        $res = $reference->shallow()->getValue();
        if ($res != null) 
          $Counter_users = count($res);
        else
          $Counter_users = 0;

// ===========================================================================
// ===========================================================================
        
        $referencePath = "Feeds";
        $reference = $database->getReference($referencePath);
        $res = $reference->shallow()->getValue();
        if ($res != null) 
          $Counter_feeds = count($res);
        else
          $Counter_feeds = 0;
// ===========================================================================
// ===========================================================================

        $referencePath = "FeedsVip";
        $reference = $database->getReference($referencePath);
        $res = $reference->shallow()->getValue();
        if ($res != null) 
          $Counter_feeds_vip = count($res);
        else
          $Counter_feeds_vip = 0;

// ===========================================================================
// ===========================================================================

        $referencePath = "Questions";
        $reference = $database->getReference($referencePath);
        $res = $reference->shallow()->getValue();
        if ($res != null) 
          $Counter_questions = count($res);
        else
          $Counter_questions = 0;

// ===========================================================================
// ===========================================================================


        $referencePath = "ContactUs/";
        $reference = $database->getReference($referencePath);
        $result_ContactUs = $reference->shallow()->getValue();

        $Counter_ContactUs = 0;

        if ($result_ContactUs != null)
        foreach ($result_ContactUs as $key => $value) {

          $referencePath = $referencePath.$key;
          $reference = $database->getReference($referencePath);
          if(is_countable($reference->shallow()->getValue())) {
            $Counter_ContactUs = count($reference->shallow()->getValue());
          }else{
            $Counter_ContactUs += 1;
          }
        }
        else
          $Counter_ContactUs = 0;

// ===========================================================================
// ===========================================================================
        $referencePath = "ReportsAPP/";
        $reference = $database->getReference($referencePath);
        $result_ReportsAPP = $reference->shallow()->getValue();

		$Counter_ReportsAPP = 0;
       
        if ($result_ReportsAPP != null)
        foreach ($result_ReportsAPP as $key => $value) {

          $referencePath = $referencePath.$key;
          $reference = $database->getReference($referencePath);
          if(is_countable($reference->shallow()->getValue())) {
          	$Counter_ReportsAPP = count($reference->shallow()->getValue());
          }else{
            $Counter_ReportsAPP += 1;
          }
        }
        else
          $Counter_ReportsAPP = 0;

// ===========================================================================
// ===========================================================================

        $referencePath = "Reports/Chats/";
        $reference = $database->getReference($referencePath);
        $result_ReportsChats = $reference->shallow()->getValue();

		$Counter_ReportsChats = 0;
        
        if ($result_ReportsChats != null)
        foreach ($result_ReportsChats as $key => $value) {

          $referencePath = $referencePath.$key;
          $reference = $database->getReference($referencePath);
          if(is_countable($reference->shallow()->getValue())) {
          	$Counter_ReportsChats = count($reference->shallow()->getValue());
          }else{
            $Counter_ReportsChats += 1;
          }
        }
        else
          $Counter_ReportsChats = 0;

// ===========================================================================
// ===========================================================================

        $referencePath = "Reports/Feeds/";
        $reference = $database->getReference($referencePath);
        $result_ReportsFeeds = $reference->shallow()->getValue();

		$Counter_ReportsFeeds = 0;
        
        if ($result_ReportsFeeds != null)
        foreach ($result_ReportsFeeds as $key => $value) {

          $referencePath = $referencePath.$key;
          $reference = $database->getReference($referencePath);
          if(is_countable($reference->shallow()->getValue())) {
          	$Counter_ReportsFeeds = count($reference->shallow()->getValue());
          }else{
            $Counter_ReportsFeeds += 1;
          }
        }
        else
          $Counter_ReportsFeeds = 0;

// ===========================================================================
// ===========================================================================

        $referencePath = "Reports/Comments/";
        $reference = $database->getReference($referencePath);
        $result_ReportsComments = $reference->shallow()->getValue();
        
		$Counter_ReportsComments = 0;
        
        if ($result_ReportsComments != null)
        foreach ($result_ReportsComments as $key => $value) {

          $referencePath = $referencePath.$key;
          $reference = $database->getReference($referencePath);
          if(is_countable($reference->shallow()->getValue())) {
          	$Counter_ReportsComments = count($reference->shallow()->getValue());
          }else{
            $Counter_ReportsComments += 1;
          }
        
        }
        else
          $Counter_ReportsComments = 0;
// ===========================================================================
// ===========================================================================

        $referencePath = "Reports/Users/";
        $reference = $database->getReference($referencePath);
        $result_ReportsUsers = $reference->shallow()->getValue();

      	$Counter_ReportsUsers = 0;
        
        if ($result_ReportsUsers != null)
        foreach ($result_ReportsUsers as $key => $value) {

          $referencePath = $referencePath.$key;
          $reference = $database->getReference($referencePath);
          if(is_countable($reference->shallow()->getValue())) {
          	$Counter_ReportsUsers = count($reference->shallow()->getValue());
          }else{
            $Counter_ReportsUsers += 1;
          }
        }
        else
          $Counter_ReportsUsers = 0;

// ===========================================================================
// ===========================================================================

        $referencePath = "Crashes/ClientApp/";
        $reference = $database->getReference($referencePath);
        $snapshot = $reference->getSnapshot();

        $Counter_Crashes = 0;
        if ($snapshot->hasChildren()){
            $result_Crashes = $reference->getChildKeys();
    
    
            if ($result_Crashes != null)
            foreach ($result_Crashes as $key => $value) {
    
              $referencePath = $referencePath.$key;
              $reference = $database->getReference($referencePath);
              if(is_countable($reference->shallow()->getValue())) {
                $Counter_Crashes = count($reference->shallow()->getValue());
              }else{
                $Counter_Crashes += 1;
              }
            }
            else
              $Counter_Crashes = 0;
        }
// ===========================================================================

/** 
 * This time is based on the default server time zone.
 * If you want the date in a different time zone,
 * say if you come from Nairobi, Kenya like I do, you can set
 * the time zone to Nairobi as shown below.
 */

//date_default_timezone_set('Africa/Nairobi');

// Then call the date functions
$date = date('Y-m-d');

$database->getReference('ADMIN_PANEL/statistiques/'.$date)
   ->set([
       'Counter_users' => $Counter_users,
       'Counter_feeds' => $Counter_feeds,
       'Counter_feeds_vip' => $Counter_feeds_vip,
       'Counter_questions' => $Counter_questions,
       'Counter_ReportsAPP' => $Counter_ReportsAPP,
       'Counter_ReportsFeeds' => $Counter_ReportsFeeds,
       'Counter_ReportsComments' => $Counter_ReportsComments,
       'Counter_ReportsChats' => $Counter_ReportsChats,
       'Counter_ReportsUsers' => $Counter_ReportsUsers,
       'Counter_ContactUs' => $Counter_ContactUs,
       'Counter_Crashes' => $Counter_Crashes
      ]);


header("Location: /admin/main");
exit;