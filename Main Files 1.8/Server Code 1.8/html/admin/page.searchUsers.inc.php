<?php

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

    $query = '';
    $result = null;
    if (!empty($_POST)) {
        $query = isset($_POST['query']) ? $_POST['query'] : '';
        $authenticity_token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        if ($authenticity_token === helper::getAuthenticityToken()) {
            $userfb = new userfb($dbo);

            if (strlen($query) > 2) {

                $result = $userfb->search($query);

            }
        }
    }

    $page_id = "searchUsers";

    helper::newAuthenticityToken();

    $page_title = "Search Users| Admin Panel";

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
        <h1 class="mt-4">Search Users</h1>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/admin/main">Home</a>
          </li>
          <li class="breadcrumb-item active">Search Users</li>
        </ol>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Search Users</h4>

                                    <div class="alert alert-info" role="alert">
                                      Only synchronized users (Firebase -> Mysql) will appear in the result . 
                                    <button class="btn waves-effect waves-light btn-info synchronize"
                                    href="javascript:void(0)" 
                                    onclick="myFunction(); return false;">
                                    Synchronize Now
                                    </button>
                                    </div>

                                <form class="form-material m-t-40"  method="post" action="/admin/searchUsers">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group">
                                        <label >Search By (Name - UserName)</label>
                                        <input placeholder="Search text" id="query" type="text" name="query" pattern=".{3,}"   required title="3 characters minimum" maxlength="100" class="form-control form-control-line" value="<?php echo $query; ?>">
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>


                </div>

                <?php
                    if ($result != null) {
                        $inbox_loaded = count($result['ids']);
                    }else{
                        $inbox_loaded = 0;
                    }

                    if ($inbox_loaded != 0) {

                        ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Users</h4>
                                        <div class="table-responsive">

                                            <table class="table color-table info-table">

                                                <thead>
                                                    <tr>
                                                        <th>Reg Date</th>
                                                        <th>ID</th>
                                                        <th>Photo</th>
                                                        <th>Name</th>
                                                        <th>UserName</th>
                                                        <th>Phone</th>
                                                        <th>Type</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                    <?php
                        foreach ($result['ids'] as $key => $value) {


                        /* Retrive user data from firebase */
                        $referencePath = "users"."/".$value;

                        $reference = $database->getReference($referencePath);

                        $snapshot = $reference->getSnapshot();

                        $userFirebase = $snapshot->getValue();

                            draw($userFirebase);
                        
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
                                            <h4 class="card-title">Search is empty.</h4>
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
<script src="/admin/js/jquery.min.js"></script>

<script type="text/javascript">
function myFunction() {
//$('button.loadeMore').hide();
    $('button.synchronize').html('synchronize ...');

    $.ajax({
        type: 'GET',
        url: '/admin/import.users',
        timeout: 30000,
        success: function(response){

            $('button.synchronize').html('Synchronize success');

        },
        error: function(xhr, type){

            $('button.synchronize').show();
            $('button.synchronize').html('Synchronize Now');

        }
    });
};
</script>
</html>

<?php

    function draw($value)
    {
        ?>
        <tr>
            <td>

                <?php 
                $timestamp = $value['timestamp'];
                $seconds = $timestamp / 1000;
                echo date("d-m-Y H:i", $seconds); ?>                
                    
            </td>

            <td>
                <small class="text-muted" style="font-size: 60%;">
                <?php echo $value['id']; ?>
                </small>
            </td>
            <td>

            <?php

                if (strlen($value['image']) != 0) {

                    ?>
                        <img style="width: 50px;height: 50px;" src="<?php echo $value['image']; ?>" width="50" height="50" alt="user" />
                    <?php

                } else {

                    ?>
                        <img style="width: 50px;height: 50px;" class="card-img-top" width="50" height="50" src="/img/image-profile.png">
                    <?php
                }
            ?>

            </td>

            <td><?php echo $value['name']; ?></td>

            <td><?php echo $value['userName']; ?></td>

            <td><?php echo $value['phone']; ?></td>
            <td>
            <?php

            if ($value['type'] == 1) {

                echo "<span>Account is verified.</span>";

            } else if ($value['type'] == 2) {
                echo "<span>Account is admin.</span>";

            } else if ($value['type'] == 3) {
                echo "<span>Account is Pro / Teacher.</span>";
            } else {

                echo "<span>Account is not verified.</span>";
            }
            ?>
            </td>

                <td><a  target="_blank" href="/admin/profile?user_id=<?php echo $value['id']; ?>">Go to Profile</a></td>
        </tr>

        <?php
    }
