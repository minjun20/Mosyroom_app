<?php

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

    $stats = new stats($dbo);

    if (isset($_GET['act'])) {

        $act = isset($_GET['act']) ? $_GET['act'] : '';
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
        $key_ticket = isset($_GET['key_ticket']) ? $_GET['key_ticket'] : 0;
        $token = isset($_GET['access_token']) ? $_GET['access_token'] : '';

        //$user_id = helper::clearText($ticketId);
        //$key_ticket = helper::clearText($ticketId);

        if (admin::getAccessToken() === $token) {

            switch ($act) {

                case "delete" : {

                    $pathRef = "ContactUs/".$user_id."/".$key_ticket;
                    $database->getReference($pathRef)->remove();
//echo($pathRef);
                    header("Location: /admin/support");
                    break;
                }

                default: {

                    header("Location: /admin/support");
                    exit;
                }
            }
        }

        header("Location: /admin/support");
        exit;
    }

    $page_id = "support";

    $css_files = array("mytheme.css");
    $page_title = "Support | Admin Panel";


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

        <h1 class="mt-4">Support</h1>

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/admin/main">Home</a>
          </li>
          <li class="breadcrumb-item active">Support</li>
        </ol>

                <?php

                    /* Retrive reports data from firebase */
                    $referencePath = "ContactUs";

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
                                    Contact Messages (Support)</div>
                                  <div class="card-body">
                                    <div class="table-responsive">
                                      <table class="table table-bordered" id="dataTable" data-sort-name="Date" data-sort-order="desc" width="100%" cellspacing="0">                
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Photo</th>
                                                <th>From User</th>
                                                <th>Email</th>
                                                <th>Message</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>

                                            <tfoot>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Photo</th>
                                                    <th>From User</th>
                                                    <th>Email</th>
                                                    <th>Message</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php

                                                foreach ($result as $key => $value) {

                                                    /* Retrive user data from firebase */
                                                    $referencePath = "users"."/".$key;

                                                    $reference = $database->getReference($referencePath);



                                                    $snapshot = $reference->getSnapshot();

                                                    if (!$snapshot->exists()) {
                                                                continue;     
                                                    }


                                                    $userFirebase = $snapshot->getValue();
                                                    $userName = $userFirebase['userName'];
                                                    $userPhoto = $userFirebase['image'];

                                                    /* End Retrive user data from firebase */

                                                    foreach ($value as $key1 => $value1) {
                                                        
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

    function draw($value, $name, $photo, $user_id, $key_ticket)
    {

        ?>
            <tr>

                <td>
                    <h6 style="white-space: normal;">

                        <?php echo (array_key_exists('Name', $value)) ? $value['Name'] : '';?>
                            
                    </h6>
                </td>

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

                            <a  target="_blank" href="/admin/profile?user_id=<?php echo $user_id; ?>"><?php echo $name; ?></a>


                            <?php

                        } else {

                            ?>
                                <h6>Unknown user</h6>
                            <?php
                        }
                    ?>
                </td>
                <td>
                    <h6 style="white-space: normal;">
                    
                        <?php echo (array_key_exists('Email', $value)) ? $value['Email'] : '';?>
                        
                    </h6>
                </td>
                <td>
                    <h6 style="white-space: normal;">

                        <?php echo (array_key_exists('Message', $value)) ? $value['Message'] : '';?>

                    </h6>
                </td>


                <td><a href="/admin/support?user_id=<?php echo $user_id; ?>&key_ticket=<?php echo $key_ticket; ?>&act=delete&access_token=<?php echo admin::getAccessToken(); ?>">Delete</a></td>
            </tr>

        <?php
    }