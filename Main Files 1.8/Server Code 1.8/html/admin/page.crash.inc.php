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

                    $pathRef = "Crashes/ClientApp/".$user_id."/".$key_ticket;
                    $database->getReference($pathRef)->remove();
//echo($pathRef);
                    header("Location: /admin/crash");
                    break;
                }

                default: {

                    header("Location: /admin/crash");
                    exit;
                }
            }
        }

        header("Location: /admin/crash");
        exit;
    }

    $page_id = "crash";

    $css_files = array("mytheme.css");
    $page_title = "crashs | Admin Panel";


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
        
        <h1 class="mt-4">Crashes</h1>

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/admin/main">Home</a>
          </li>
          <li class="breadcrumb-item active">Crashs</li>
        </ol>

                <?php

                    /* Retrive reports data from firebase */
                    $referencePath = "Crashes/ClientApp/";

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
                                    <i class="fas fa-bug"></i>
                                    Crashes</div>
                                  <div class="card-body">
                                    <div class="table-responsive">
                                      <table class="table table-bordered" id="dataTable" data-sort-name="Date" data-sort-order="desc" width="100%" cellspacing="0">                
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Base</th>
                                                <th>Brand</th>
                                                <th>CrashText</th>
                                                <th>Manufacturer</th>
                                                <th>Model</th>
                                                <th>SDK</th>
                                                <th>App Version</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>

                                            <tfoot>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Base</th>
                                                    <th>Brand</th>
                                                    <th>CrashText</th>
                                                    <th>Manufacturer</th>
                                                    <th>Model</th>
                                                    <th>SDK</th>
                                                    <th>App Version</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php

                                                foreach ($result as $key => $value) {

                                                    foreach ($value as $key1 => $value1) {
                                                        
                                                        draw($value1,$key,$key1);
                                                    
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

    function draw($value, $user_id, $key_ticket)
    {

        ?>

            <tr>
                <td >
                    <h6>
                        <?php 
                        echo $value['timestamp'];
                        ?>
                        <br>
                        <?php 
                        $timestamp = $value['timestamp'];
                        $seconds = $timestamp / 1000;
                        echo date("d-m-Y H:i", $seconds);
                        ?>
                    </h6>
                </td>


                <td >
                    <h6>
                        <?php echo (array_key_exists('BASE', $value)) ? $value['BASE'] : '';?>
                    </h6>
                </td>

                <td >
                    <h6>
                        <?php echo (array_key_exists('BRAND', $value)) ? $value['BRAND'] : '';?>
                    </h6>
                </td>

                <td >
                    <h6>
                        <pre>
                        <?php echo (array_key_exists('CrashText', $value)) ? $value['CrashText'] : '';?>
                        </pre>
                    </h6>
                </td>

                <td >
                    <h6>
                        <?php echo (array_key_exists('MANUFACTURER', $value)) ? $value['MANUFACTURER'] : '';?>
                    </h6>
                </td>

                <td >
                    <h6>
                        <?php echo (array_key_exists('MODEL', $value)) ? $value['MODEL'] : '';?>
                    </h6>
                </td>

                <td >
                    <h6>
                        <?php echo (array_key_exists('SDK_INT', $value)) ? $value['SDK_INT'] : '';?>
                    </h6>
                </td>

                <td >
                    <h6>
                        <?php echo (array_key_exists('currentVersion', $value)) ? $value['currentVersion'] : '';?>
                    </h6>
                </td>

                <td><a href="/admin/crash?user_id=<?php echo $user_id; ?>&key_ticket=<?php echo $key_ticket; ?>&act=delete&access_token=<?php echo admin::getAccessToken(); ?>">Delete</a></td>
            </tr>

        <?php
    }