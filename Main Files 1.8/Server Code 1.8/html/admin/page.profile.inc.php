<?php


    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

    $error = false;
    $error_message = '';

    if (isset($_GET['user_id'])) {

        $accountId = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;
        $act = isset($_GET['act']) ? $_GET['act'] : '';

                            /* Retrive reports data from firebase */
                    $referencePath = "users/".$accountId;

                    $reference = $database->getReference($referencePath);

                    $snapshot = $reference->getSnapshot();

                    $result  = $snapshot->getValue();
/*
echo "string";
print_r($result);
 */
        if ($accessToken === admin::getAccessToken()) {

            switch ($act) {

                case "block": {

                        $database->getReference("users/".$accountId.'/baned')->set(true);

                    header("Location: /admin/profile/?user_id=".$accountId);
                    break;
                }

                case "unblock": {

                        $database->getReference("users/".$accountId.'/baned')->set(false);

                    header("Location: /admin/profile/?user_id=".$accountId);
                    break;
                }

                case "verify": {

                        $database->getReference("users/".$accountId.'/type')->set(1);

                    header("Location: /admin/profile/?user_id=".$accountId);
                    break;
                }

                case "vip": {

                        $database->getReference("users/".$accountId.'/type')->set(3);


                    header("Location: /admin/profile/?user_id=".$accountId);
                    break;
                }


                case "admin": {

                        $database->getReference("users/".$accountId.'/type')->set(2);


                    header("Location: /admin/profile/?user_id=".$accountId);
                    break;
                }

                case "unverify": {

                        $database->getReference("users/".$accountId.'/type')->set(0);

                    header("Location: /admin/profile/?user_id=".$accountId);
                    break;
                }

                case "delete_photo": {

                        $database->getReference("users/".$accountId.'/image')->set("");

                    header("Location: /admin/profile/?user_id=".$accountId);
                    break;
                }


                default: {
                    header("Location: /admin/profile/?user_id=".$accountId);
                    exit;
                }
            }
        }

    } else {

        header("Location: /admin/main");
        exit;
    }
/*
    if ($accountInfo['error'] === true) {

        header("Location: /admin/main");
        exit;
    }
*/
    $page_id = "account";


    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "Account Info | Admin Panel";

    include_once("../html/common/admin_header.inc.php");
?>

    <body class="sb-nav-fixed" id="page-top">

        <?php

            include_once("../html/common/admin_topbar.inc.php");
        ?>

        <div id="layoutSidenav">


        <?php

            include_once("../html/common/admin_sidebar.inc.php");
        ?>


            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">


                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor">Dashboard</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                            <li class="breadcrumb-item active">Account Info</li>
                        </ol>
                    </div>
                </div>

                <?php
                    if ($result != null) {

                        ?>

                <div class="row">

                    <div class="col-lg-8">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Account Info</h4>
                                <h6 class="card-subtitle">
                                    <a href="/admin/gcm/?user_id=<?php echo $accountId; ?>&user_name=<?php echo $result['userName']; ?>">
                                        <button class="btn waves-effect waves-light btn-info">Send Personal FCM Message</button>
                                    </a>
                                </h6>

                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Value/Count</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-address-card"></i></td>
                                            <td class="text-left">userName:</td>
                                            <td><?php echo $result['userName']; ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="col-sm-1"><i class="far fa-address-card"></i></td>
                                            <td class="text-left">Fullname:</td>
                                            <td><?php echo $result['name']; ?></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-mobile-alt"></i></td>
                                            <td class="text-left">Sign With:</td>
                                            <td><?php echo $result['signeInMethod']; ?></td>
                                            <td></td>
                                        </tr>

                                        <?php 
                                        if ($result['signeInMethod'] == 'phone') {
                                        ?>
                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-mobile-alt"></i></td>
                                            <td class="text-left">phone:</td>
                                            <td><?php echo $result['phone']; ?></td>
                                            <td></td>
                                        </tr>
                                        <?php 
                                        }
                                        ?>
                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-venus-mars"></i></td>
                                            <td class="text-left">gender:</td>
                                            <td><?php 

                                                if ($result['gender'] == USER_GENDER_0) {
                                                    echo "<span>Female</span>";
                                                } else {

                                                    echo "<span>Mal</span>";
                                                }
                                                ?>
                                            </td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-coins"></i></td>
                                            <td class="text-left">balance:</td>
                                            <td><?php echo $result['balance']; ?></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="col-sm-1">
                                                <i class="fas fa-suitcase"></i>
                                            </td>
                                            <td class="text-left">Level/Job:</td>
                                            <td><?php echo $result['level']; ?></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-city"></i></td>
                                            <td class="text-left">city:</td>
                                            <td><?php echo $result['city']; ?></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-calendar"></i></td>
                                            <td class="text-left">date Created:</td>
                                            <td>
                                                <?php 
                                                $timestamp = $result['timestamp'];
                                                $seconds = $timestamp / 1000;
                                                echo date("d-m-Y H:i", $seconds); 
                                                ?>
                                            </td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-align-justify"></i></td>
                                            <td class="text-left">feedsCount:</td>
                                            <td><?php echo $result['feedsCount']; ?></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-birthday-cake"></i></td>
                                            <td class="text-left">birthDay:</td>
                                            <td><?php echo $result['birthDay']; ?></td>
                                            <td></td>
                                        </tr>



                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-ad"></i></td>
                                            <td class="text-left">AdMob (on/off AdMob in account):</td>
                                            <td>
                                                <?php
                                                    echo "Soon";
                                                ?>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-id-badge"></i></td>
                                            <td class="text-left">Account state:</td>
                                            <td>
                                                <?php
                                                if (array_key_exists('baned', $result)) {
                                                    if ($result['baned'] == false) {

                                                        echo "<span>Account is active</span>";

                                                    } else {

                                                        echo "<span>Account is banned</span>";
                                                    }
                                                } else {

                                                        echo "<span>Never Banned Before</span>";
                                                 }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (array_key_exists('deleted', $result) && $result['deleted'] == true) {
                                                    echo "Account Deleted";
                                                }else{

                                                    if (array_key_exists('baned', $result)) {

                                                        if ($result['baned'] == false) {

                                                            ?>
                                                            <a class="btn btn-danger" href="/admin/profile/?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=block">Block</a>
                                                            <?php

                                                        } else {

                                                            ?>
                                                            <a class="btn btn-warnings" href="/admin/profile/?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=unblock">Unblock</a>
                                                            <?php
                                                        }
                                                    }else{
                                                        ?>
                                                            <a class="btn btn-danger" href="/admin/profile/?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=block">Block</a>
                                                        <?php                                                   
                                                    }
                                                }
                                                
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-sm-1"><i class="fas fa-check-circle"></i></td>
                                            <td class="text-left">Account verified:</td>
                                            <td>
                                                <?php

                                                if ($result['type'] == 1) {

                                                    echo "<span>Account is ".USER_TYPE_1.".</span>";

                                                } else if ($result['type'] == 2) {
                                                    echo "<span>Account is ".USER_TYPE_2.".</span>";

                                                } else if ($result['type'] == 3) {
                                                    echo "<span>Account is ".USER_TYPE_3.".</span>";
                                                } else {

                                                    echo "<span>Account is ".USER_TYPE_0.".</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>

                    <div class="btn-group">
                      <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="">
                        Action
                        <span class="caret"></span>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                          

                        <?php

                    if (array_key_exists('type', $result)) {
                        if ($result['type'] == 1) {

                            ?>
                            <a class="dropdown-item"  href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=unverify">Set Normal</a>
  <div class="dropdown-divider"></div>
                            <a class="dropdown-item"  href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=vip">Set VIP</a>
  <div class="dropdown-divider"></div>
                            <a class="dropdown-item"  href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=admin">Set Admin</a>


                            <?php

                        } else if ($result['type'] == 2) {

                            ?>

                             <a class="dropdown-item" href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=verify">Set Verified</a>
  <div class="dropdown-divider"></div>
                            <a class="dropdown-item"  href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=unverify">Set Normal</a>
  <div class="dropdown-divider"></div>
                            <a class="dropdown-item"  href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=vip">Set VIP</a>

                            <?php

                        } else if ($result['type'] == 3) {

                            ?>

                             <a class="dropdown-item" href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=verify">Set Verified</a>
  <div class="dropdown-divider"></div>
                            <a class="dropdown-item"  href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=unverify">Set Normal</a>
  <div class="dropdown-divider"></div>
                            <a class="dropdown-item"  href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=admin">Set Admin</a>

                            <?php

                        } else {

                            ?>
                            <a class="dropdown-item"  href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=verify">Set Verified</a>
  <div class="dropdown-divider"></div>
                            <a class="dropdown-item"  href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=vip">Set VIP</a>
  <div class="dropdown-divider"></div>
                            <a class="dropdown-item"  href="/admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=admin">Set Admin</a>


                            <?php
                        }
                    }
                        ?>

                      </div>
                    </div>

                                            </td>
                                        </tr>
     
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                </div>

                    <div class="col-lg-4">
                        <!-- Column -->
                        <div class="card">

                            <?php

                                if (strlen($result['image']) != 0) {

                                    ?>
                                        <img height="200" src="<?php echo $result['image']; ?>" alt="user" />
                                    <?php

                                } else {

                                    ?>
                                        <img class="card-img-top" src="/img/image-profile.png">
                                    <?php
                                }
                            ?>

                                <?php


                                    if (strlen($result['image']) != 0) {

                                        ?>
                                            <p><a href="admin/profile?user_id=<?php echo $result['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=delete_photo">Delete Photo</a></p>
                                        <?php

                                    }
                                ?>

                        </div>
                        <!-- Column -->
                    </div>


                </div>




  <?php

                    /* Retrive reports data from firebase */
                    $referencePath = "ChatsUsers/".$accountId;

                    $reference = $database->getReference($referencePath);

                    $snapshot = $reference->getSnapshot();

                    $result  = $snapshot->getValue();
                    
                    if ($result != null) {
                        $inbox_loaded = count($result);
                    }else{
                        $inbox_loaded = 0;
                    }

                    if ($inbox_loaded != 0) {

                        ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Users -->
                                <div class="card mb-3" id="reportsTable">
                                  <div class="card-header">
                                    <i class="fas fa-envelope"></i>
                                    Chats</div>
                                  <div class="card-body">
                                    <div class="table-responsive">
                                      <table class="table table-bordered" id="dataTablereports" data-sort-name="Date" data-sort-order="desc" width="100%" cellspacing="0">                
                                        <thead>
                                            <tr>
                                                <th>Update Time</th>
                                                <th>image</th>
                                                <th>with User</th>
                                                <th>last Message</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>

                                            <tfoot>
                                                <tr>
                                                    <th>Update Time</th>
                                                    <th>image</th>
                                                    <th>with User</th>
                                                    <th>last Message</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php

                                                //foreach ($result as $key => $value) {

                                                    foreach ($result as $key => $value) {
                                                    

                                                    if (strpos($key, 'group') !== false) {
                                                        $chatImage =  "group";
                                                        $chatUserName = $value['chatName'];
                                                        $withId = $key;

                                                        }else{

                                                        $chatImage =  $value['chatImage'];
                                                        $chatUserName = $value['chatName'];
                                                        $withId = $value['withId'];

draw($key, $value['timeUpdated'],$chatImage,$chatUserName, $withId,$value['lastMessage']);

                                                        }

                                
                                                    
                                                    }


                                                //}
                                                    ?>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php

                    } else {

                        ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h4 class="card-title">List is empty.</h4>
                                            <p class="card-text">This means that there is no data to display :)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ?>








                        <?php

                    } else {

                        ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h4 class="card-title"> Empty.</h4>
                                            <p class="card-text">This means that there is no Profile With this ID :)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ?>

          </div>

        </main>
          <!-- Sticky Footer -->
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted"><?php echo APP_TITLE; ?> © <?php echo APP_YEAR; ?></div>
                            <div>
                                <a href="<?php echo COMPANY_URL; ?>"><?php echo APP_VENDOR; ?></a>
                            </div>
                        </div>
                    </div>
                </footer>
    </div>

            <?php

                include_once("../html/common/admin_footer.inc.php");
            ?>

    </div> <!-- End Wrapper -->

</body>

</html>


<?php

                        
    function draw($id, $timeUpdated, $chatImage, $chatUserName, $withId,$lastMessage)
    {

        ?>
            <tr>

                <td>

                        <?php 
                        $seconds = $timeUpdated / 1000;
                        echo date("y-m-d H:i", $seconds);
                        ?>
                        
                </td>

                <td style="width:50px;">
                    <?php

                        if (strlen($chatImage) != 0) {

                            ?>
                            <img width="50" height="50" src="<?php echo $chatImage; ?>">
                            <?php

                        } else {

                            ?>
                            <img src="<?php echo APP_URL; ?>/img/image-profile.png" width="50" height="50">
                            <?php
                        }
                    ?>
                </td>

                <td>
                    <a target="_blank" href="/admin/profile?user_id=<?php echo $withId; ?>">
                    
                    <?php echo $chatUserName; ?>
                        
                    </a>
                </td>

                <td>
                    
                    <?php echo $lastMessage; ?>
                        
                </td>

                <td><a  target="_blank" href="/admin/chat?chat_id=<?php echo $id; ?>">Go to</a></td>

            </tr>

        <?php
    }
