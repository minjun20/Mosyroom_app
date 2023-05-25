<?php

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

    $stats = new stats($dbo);

    if (isset($_GET['act'])) {

        $act = isset($_GET['act']) ? $_GET['act'] : '';
        $feed_id = isset($_GET['feed_id']) ? $_GET['feed_id'] : 0;
        $key_ticket = isset($_GET['key_ticket']) ? $_GET['key_ticket'] : 0;
        $token = isset($_GET['access_token']) ? $_GET['access_token'] : '';

        //$user_id = helper::clearText($ticketId);
        //$key_ticket = helper::clearText($ticketId);

        if (admin::getAccessToken() === $token) {

            switch ($act) {

                case "delete" : {

                    $pathRef = "Reports/Feeds/".$feed_id."/".$key_ticket;
                    $database->getReference($pathRef)->remove();
//echo($pathRef);
                    header("Location: /admin/reportsFeeds");
                    break;
                }

                default: {

                    header("Location: /admin/reportsFeeds");
                    exit;
                }
            }
        }

        header("Location: /admin/reportsFeeds");
        exit;
    }

    $page_id = "reportsFeeds";

    $css_files = array("mytheme.css");
    $page_title = "reportsFeeds | Admin Panel";

 $reasons = array(REPORT_REASON_1, REPORT_REASON_2, REPORT_REASON_3, REPORT_REASON_4);

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

                    <h1 class="mt-4">Reports Feeds</h1>

                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item">
                        <a href="/admin/main">Home</a>
                      </li>
                      <li class="breadcrumb-item active">reports Feeds</li>
                    </ol>

                <?php

                    /* Retrive reports data from firebase */
                    $referencePath = "Reports/Feeds";

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
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Reports Feeds</div>
                                  <div class="card-body">
                                    <div class="table-responsive">
                                      <table class="table table-bordered" id="dataTable" data-sort-name="Date" data-sort-order="desc" width="100%" cellspacing="0">                
                                        <thead>
                                            <tr>
                                                <th>Photo</th>
                                                <th>From User</th>
                                                <th>Feed</th>
                                                <th>Reason</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>

                                            <tfoot>
                                                <tr>
                                                    <th>Photo</th>
                                                    <th>From User</th>
                                                	<th>Feed</th>
                                                    <th>Reason</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php

                                                foreach ($result as $key => $value) {





                                                    foreach ($value as $key1 => $value1) {
                                                        

                                                    /* Retrive user data from firebase */
                                                    $referencePath = "users"."/".$key1;

                                                    $reference = $database->getReference($referencePath);



                                                    $snapshot = $reference->getSnapshot();

                                                    $userFirebase = $snapshot->getValue();
                                                    $userName = $userFirebase['userName'];
                                                    $userPhoto = $userFirebase['image'];

                                                    /* End Retrive user data from firebase */

                                                        draw($value1, $userName, $userPhoto,$key,$key1);
                                                    
                                                    }


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
                                            <h4 class="card-title">List is empty.</h4>
                                            <p class="card-text">This means that there is no data to display :)</p>
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



    </div> <!-- End Main Wrapper -->






</body>

        <?php

            include_once("../html/common/admin_footer.inc.php");
        ?>



</html>

<?php

    function draw($value, $name, $photo, $feed_id, $key_ticket)
    {

    global $reasons;

        ?>
            <tr>

                <td style="width:50px;">

                    <?php

                        if (strlen($photo) != 0) {

                            ?>
                            <img width="50" height="50" src="<?php echo $photo; ?>">
                            <?php

                        } else {

                            ?>

                            <img src="<?php echo APP_URL; ?>/img/image-profile.png" width="50" height="50">

                            <?php
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if (strlen($name) != 0) {

                            ?>
                            <a  target="_blank" href="/admin/profile?user_id=<?php echo $key_ticket; ?>"><?php echo $name; ?></a>
                            <?php

                        } else {

                            ?>
                                <h6>Unknown user</h6>
                            <?php
                        }
                    ?>
                </td>


                <td><a  target="_blank" href="/admin/post?feed_id=<?php echo $feed_id; ?>">Go to Feed</a></td>


                <td>
                    <h6 style="white-space: normal;">

                        <?php echo $reasons[$value['Reason']-1];?>

                    </h6>
                </td>


                <td><a href="/admin/reportsFeeds?feed_id=<?php echo $feed_id; ?>&key_ticket=<?php echo $key_ticket; ?>&act=delete&access_token=<?php echo admin::getAccessToken(); ?>">Delete</a></td>
            </tr>

        <?php
    }