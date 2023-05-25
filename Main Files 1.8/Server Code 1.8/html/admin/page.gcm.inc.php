<?php

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

    $error = false;
    $error_message = '';

    $stats = new stats($dbo);
    $admin = new admin($dbo);
    $gcm = new gcm($dbo);

    $topic = 'allUsers';

    $forAllUsers = true;

    if (isset($_GET['user_id'])) {

        $accountId = isset($_GET['user_id']) ? $_GET['user_id'] : '0';
        $user_name = isset($_GET['user_name']) ? $_GET['user_name'] : 'USER';
        
        if ($accountId!='0') {
            /* Retrive token from firebase */
            $referencePath = "user_fcm_ids/".$accountId;

            $reference = $database->getReference($referencePath);

            $snapshot = $reference->getSnapshot();

            $deviceToken  = $snapshot->getValue();
            if ($deviceToken!=null) {
                $forAllUsers = false;
            }
        }
    }

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $body = isset($_POST['body']) ? $_POST['body'] : '';
        $title = isset($_POST['title']) ? $_POST['title'] : '';

        $title = helper::clearText($title);
        $title = helper::escapeText($title);

        $body = helper::clearText($body);
        $body = helper::escapeText($body);

        if ($authToken === helper::getAuthenticityToken()) {

             if (strlen($body) != 0 && strlen($title) != 0) {

                $notification = Notification::fromArray([
                    'title' => $title,
                    'body' => $body,
                ]);

                $notification = Notification::create($title, $body);


                if ($forAllUsers) {
                    $toId = "0";
                    $message = CloudMessage::withTarget('topic', $topic)
                    ->withNotification($notification) 
                    ;
                }else{
                    $toId = $accountId;
                    $message = CloudMessage::withTarget('token', $deviceToken)
                    ->withNotification($notification) 
                    ;
                }

                 $messaging->send($message);

                $gcm->setData($title, $body, $toId);

            }

        }

        //header("Location: /admin/gcm");
        //exit;
    }

    $page_id = "gcm";

    helper::newAuthenticityToken();

    $page_title = "Firebase Cloud Messages | Admin Panel";

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
        <h1 class="mt-4">Push Notifications</h1>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/admin/main">Home</a>
          </li>
          <li class="breadcrumb-item active">Send message (FCM) for all users</li>
        </ol>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Send Push Notification</h4>

                                <form class="form-material m-t-40"  method="post">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <?php
                                        if ($forAllUsers) {
                                      ?>

                                    <div class="alert alert-info" role="alert">
                                      This Push Notification will sent to All Users
                                    </div>

                                    <?php
                                        }else{
                                     ?>

                                    <div class="alert alert-warning" role="alert">
                                      This Push Notification will sent only to 
                                        <a class="alert-link">
                                            <?php echo $user_name; ?>
                                        </a>
                                        with this ID
                                        <a class="alert-link">
                                            <small class="text-muted">
                                            <?php echo $accountId; ?>
                                            </small>
                                        </a>
                                    </div>
                                 
                                    <?php
                                        }
                                      ?>

                                    <div class="form-group">
                                        <label >Message title</label>
                                        <input placeholder="Message title" id="title" type="text" name="title" maxlength="100" class="form-control form-control-line">
                                    </div>


                                    <div class="form-group">
                                        <label >Message body</label>
                                        <input placeholder="text body" id="body" type="text" name="body" maxlength="100" class="form-control form-control-line">
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Send</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>


                </div>

                <?php
                    $result = $stats->getGcmHistory();

                    $inbox_loaded = count($result['data']);

                    if ($inbox_loaded != 0) {

                        ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Recently sent messages</h4>
                                        <div class="table-responsive">

                                            <table class="table color-table info-table">

                                                <thead>
                                                    <tr>
                                                        <th class="text-left">Id</th>
                                                        <th>Message title</th>
                                                        <th>Message</th>
                                                        <th>To</th>
                                                        <th>Create At</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php

                                                        foreach ($result['data'] as $key => $value) {

                                                            draw($value);
                                                        }

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
                                            <h4 class="card-title">History is empty.</h4>
                                            <p class="card-text">This means that there is no data to display :)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ?>



      </div>
      <!-- /.container-fluid -->
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
    <!-- /.content-wrapper -->



    </div> <!-- End Main Wrapper -->






</body>

        <?php

            include_once("../html/common/admin_footer.inc.php");
        ?>

</html>

<?php

    function draw($itemObj)
    {
        ?>

        <tr>
            <td class="text-left"><?php echo $itemObj['id']; ?></td>
            <td><?php echo $itemObj['msgTitle']; ?></td>
            <td><?php echo $itemObj['msg']; ?></td>
            <td>

                <?php
                 
                 
                 if ($itemObj['toId'] == '0' || $itemObj['toId'] == '') {
                      echo "All Users";
                  }else {

                    ?>
                    <a  target="_blank" href="/admin/profile?user_id=<?php echo $itemObj['toId']; ?>">See Profile</a>
                    <?php

                  }  
                 
                 ?>
                    
            </td>
            <td><?php echo date("Y-m-d H:i:s", $itemObj['createAt']); ?></td>
        </tr>

        <?php
    }
