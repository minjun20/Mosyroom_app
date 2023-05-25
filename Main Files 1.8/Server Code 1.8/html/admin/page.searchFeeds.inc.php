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
            $feed = new feed($dbo);

            if (strlen($query) > 2) {

                $result = $feed->search($query);

            }
        }
    }

    $page_id = "searchFeeds";

    helper::newAuthenticityToken();

    $page_title = "Search Feeds| Admin Panel";

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
        <h1 class="mt-4">Search Feeds</h1>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/admin/main">Home</a>
          </li>
          <li class="breadcrumb-item active">Search Feeds</li>
        </ol>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Search Feeds</h4>

                                    <div class="alert alert-info" role="alert">
                                      Only synchronized Feeds (Firebase -> Mysql) will appear in the result . 
                                    <button class="btn waves-effect waves-light btn-info synchronize"
                                    href="javascript:void(0)" 
                                    onclick="myFunction(); return false;">
                                    Synchronize Now
                                    </button>
                                    </div>

                                <form class="form-material m-t-40"  method="post" action="/admin/searchFeeds">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group">
                                        <label >Search By (Text)</label>
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
                                        <h4 class="card-title">Feeds</h4>
                                        <div class="table-responsive">

                                            <table class="table color-table info-table">

                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>ID</th>
                                                        <th>User</th>
                                                        <th>Text Feed</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                    <?php
                        foreach ($result['ids'] as $key => $value) {


                        /* Retrive user data from firebase */
                        $referencePath = "Feeds"."/".$value;

                        $reference = $database->getReference($referencePath);

                        $snapshot = $reference->getSnapshot();
                        if ($snapshot->exists()) {
                            
                            $feedFirebase = $snapshot->getValue();
                            draw($feedFirebase, $value);
                        
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
        url: '/admin/import.feeds',
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

    function draw($value, $id)
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
                <?php echo $id; ?>
                </small>
            </td>

                <td><a  target="_blank" href="/admin/profile?user_id=<?php echo $value['userId']; ?>">Go to Profile</a></td>

            <td><?php echo $value['text']; ?></td>

            <td><a  target="_blank" href="/admin/post?feed_id=<?php echo $id; ?>">Go to Feed</a></td>

        </tr>

        <?php
    }
