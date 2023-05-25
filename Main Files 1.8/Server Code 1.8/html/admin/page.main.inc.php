<?php


if (!admin::isSession()) {

    header("Location: /admin/login");
    exit;
}

/*
 Add Update System
*/
  /* Retrive reports data from firebase */
  $referencePathUpdate = "UpdateAppVersion/";
  $referencePathAPICall = "API_CALL/ice_servers/0/uri";

  $referenceUpdate = $database->getReference($referencePathUpdate);
  $referenceAPICall = $database->getReference($referencePathAPICall);

  $snapshotUpdate = $referenceUpdate->getSnapshot();

  $UpdateAppVersion  = $snapshotUpdate->getValue();

  if ($UpdateAppVersion == null) {
    $referenceUpdate->set("0.1");
    $referenceAPICall->set("stun:stun.l.google.com:19302");
    
  }



$stats = new stats($dbo);
$admin = new admin($dbo);

/*
  Retrive Last 10 Users
*/
$users = [];
$referencePathUsers = "users";
$referenceUsers = $database->getReference($referencePathUsers);
$snapshotUsers0 = $referenceUsers->getSnapshot();
if($snapshotUsers0->hasChildren()){
  $snapshotUsers = $referenceUsers->orderByChild('timestamp')->limitToLast(10)->getSnapshot();
  if($snapshotUsers!=null){
    $resUsers = $snapshotUsers->getValue();
  
    if($resUsers != null && is_countable($resUsers)){
      $usersIds = array_keys($resUsers);
      //array_reverse($usersIds);
      $users = $auth->getUsers($usersIds);
    }
  }
}



//===================================================================

$Counter_users = 0;
$Counter_feeds = 0;
$Counter_feeds_vip = 0;
$Counter_questions = 0;
$Counter_ReportsAPP = 0;
$Counter_ReportsFeeds = 0;
$Counter_ReportsComments = 0;
$Counter_ReportsChats = 0;
$Counter_ReportsUsers = 0;
$Counter_ContactUs = 0;
$Counter_Crashes = 0;

// ===========================================================================
// ===========================================================================

$referencePath = "ADMIN_PANEL/statistiques/";
$reference = $database->getReference($referencePath);
$snapshot = $reference->orderByKey()->limitToLast(10)->getSnapshot();
$result_charts = $snapshot->getValue();
if ($result_charts != null) {
  $dayes_dates = array();
  $array_new_users = array();
  $users_previous_value = 0;
  foreach ($result_charts as $key => $value) {
    $dayes_dates[] = $key;
    $array_new_users[] = $value['Counter_users']-$users_previous_value;
    $users_previous_value = $value['Counter_users'];
  }
}


$reference = $database->getReference($referencePath);
$snapshot = $reference->orderByKey()->limitToLast(1)->getSnapshot();

$last_date_update_stat = "";

$res = $snapshot->getValue();
if ($res != null) {
  foreach ($res as $key => $value) {
    $Counter_users = $value['Counter_users'];
    $Counter_feeds = $value['Counter_feeds'];
    $Counter_feeds_vip = $value['Counter_feeds_vip'];
    $Counter_questions = $value['Counter_questions'];
    $Counter_ReportsAPP = $value['Counter_ReportsAPP'];
    $Counter_ReportsFeeds = $value['Counter_ReportsFeeds'];
    $Counter_ReportsComments = $value['Counter_ReportsComments'];
    $Counter_ReportsChats = $value['Counter_ReportsChats'];
    $Counter_ReportsUsers = $value['Counter_ReportsUsers'];
    $Counter_ContactUs = $value['Counter_ContactUs'];
    $Counter_Crashes = $value['Counter_Crashes'];
    $last_date_update_stat = $key;
  }
}

// ===========================================================================
// ===========================================================================

if (isset($_GET['id'])) {

      $userId = isset($_GET['id']) ? $_GET['id'] : 0;

      $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;

      if ($accessToken == 0) {
          $accessToken = isset($_POST['access_token']) ? $_POST['access_token'] : '';        
      }

      $act = isset($_GET['act']) ? $_GET['act'] : '';

      if ($accessToken === admin::getAccessToken()) {

          switch ($act) {

              case "enableUser": {

                  $auth->enableUser($userId);

                  break;
              }

              case "disableUser": {

                  $auth->disableUser($userId);
                  
                  break;
              }

              case "deleteUser": {

                  $auth->deleteUser($userId);
                  
                  break;
              }

              default: {        

              }
        }
      }  
  }


    $page_id = "main";

    $css_files = array("mytheme.css");
    $page_title = "Dashboard";

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
<h1 class="mt-4">Dashboard</h1>
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Home</a>
          </li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <!-- Icon Cards-->
        <div class="row">
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-users"></i>
                </div>
                <div class="mr-5" id="Counter_users_1">Users: </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-success o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-align-justify"></i>
                </div>
                <div class="mr-5" id="Counter_feeds_1">Total Feeds: </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-envelope-open"></i>
                </div>
                <div class="mr-5" id="Counter_contact_us_1">Total Contacts: </div>
                <div class="mr-5" id="Counter_reports_1">Total Reports: </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-danger o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-bug"></i>
                </div>
                <div class="mr-5" id="Counter_crashes_1">Total Crashes: </div>
              </div>
            </div>
          </div>
        </div>

        <a class="btn btn-secondary btn-block" href="/admin/refresh.statistiques">Refresh Statistiques</a>


                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-area mr-1"></i>
                                <i class="fas fa-fw fa-users"></i>
                                New Users Area Chart
                            </div>
                            <div class="card-body"><canvas id="myAreaChart" width="100%" height="30"></canvas></div>
                            <div class="card-footer small text-muted">Updated <?php echo $last_date_update_stat; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar mr-1"></i>
                                        <i class="fas fa-align-justify"></i>
                                        Feeds Bar Chart
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="50"></canvas></div>
                                    <div class="card-footer small text-muted">Updated <?php echo $last_date_update_stat; ?></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        <i class="fas fa-exclamation-triangle"></i>

                                        Reports Pie Chart
                                    </div>
                                    <div class="card-body"><canvas id="myPieChart" width="100%" height="50"></canvas></div>
                                    <div class="card-footer small text-muted">Updated <?php echo $last_date_update_stat; ?></div>

                                </div>
                            </div>
                        </div>

        <!-- Area Statistics-->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-chart-bar"></i>
            Full Statistics
          </div>
          
          <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-left">Name</th>
                        <th>Count</th>
                    </tr>
              </thead>
              <tbody>
                    <tr class="text-primary">
                        <td class="text-left">Users</td>
                        <td  id="Counter_users_2"></td>
                    </tr>

                    <!-- ************************************************************************* -->
                    
                    <tr class="text-success">
                        <td class="text-left">Total Feeds</td>
                        <td  id="Counter_feeds_2"></td>
                    </tr>
                    
                    <tr class="text-success">
                        <td class="text-left">Total Feeds VIP</td>
                        <td  id="Counter_feeds_vip_2"></td>
                    </tr>
                    
                    <tr class="text-success">
                        <td class="text-left">Total Questions FAQ</td>
                        <td  id="Counter_question_2"></td>
                    </tr>

                    <!-- ************************************************************************* -->

                    <tr class="text-warning">
                        <td class="text-left">Total Contacts</td>
                        <td  id="Counter_contact_us_2"></td>
                    </tr>
                    <tr class="text-warning">
                        <td class="text-left">Total Reports App</td>
                        <td  id="Counter_r_App_2"></td>
                    </tr>
                    <tr class="text-warning">
                        <td class="text-left">Total Reports Feeds</td>
                        <td  id="Counter_r_Feeds_2"></td>
                    </tr>
                    <tr class="text-warning">
                        <td class="text-left">Total Reports Comments</td>
                        <td  id="Counter_r_Comments_2"></td>
                    </tr>
                    <tr class="text-warning">
                        <td class="text-left">Total Reports Chats</td>
                        <td  id="Counter_r_Chats_2"></td>
                    </tr>
                    <tr class="text-warning">
                        <td class="text-left">Total Reports Users</td>
                        <td  id="Counter_r_Users_2"></td>
                    </tr>

                    <!-- ************************************************************************* -->

                    <tr class="text-danger">
                        <td class="text-left">Total Crashes</td>
                        <td  id="Counter_crashes_2"></td>
                    </tr>

                    <!-- ************************************************************************* -->

              </tbody>
            </table>
          </div>
        </div>
        <!-- End Statistics-->


        <!-- Users -->
        <div class="card mb-3" id="usersTable">
          <div class="card-header">
            <i class="fas fa-users"></i>
            The recently registered users</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="maindataTable" data-sort-name="Reg Date" data-sort-order="desc" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="sorting_desc">Reg Date</th>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>UserName</th>
                        <th>SignIn</th>
                        <th>Type</th>
                        <th>State</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="sorting_desc">Reg Date</th>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>UserName</th>
                        <th>SignIn</th>
                        <th>Type</th>
                        <th>State</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php

                          if($users != null)
                          foreach ($users as $user) {

                            $result  = $resUsers[$user->uid];

                            $image = "";
                            $type = "";
                            $userName = "";

                            $image = "";
                            $type = "";
                            $userName = "";

                            if ($result != null) {

                              if (array_key_exists('image', $result)) {
                              
                                $image = $result['image'];

                              }
  
                              if (array_key_exists('type', $result)) {
                            
                                $type = $result['type'];

                              }
  
                              if (array_key_exists('userName', $result)) {
                            
                                $userName = $result['userName'];

                              }

                            draw($user, $image, $type, $userName);

                            }

                          }

                    ?>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      <!-- End Users -->



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

  <!-- Delete Question Modal-->
  <div class="modal fade" id="deleteUserModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete User?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Delete" below if you want to remove this User from the FireBase authentication.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          
<a class="btn btn-danger" id="deleteLink" href="">Delete</a>

        </div>
      </div>
    </div>
  </div>
  



        <?php

            include_once("../html/common/admin_footer.inc.php");
        ?>


<script type="text/javascript">    
        
  Counter_users = <?php echo $Counter_users;?>;
  Counter_feeds = <?php echo $Counter_feeds;?>;
  Counter_feeds_vip = <?php echo $Counter_feeds_vip;?>;
  Counter_questions = <?php echo $Counter_questions;?>;
  Counter_ContactUs = <?php echo $Counter_ContactUs;?>;
  Counter_r_app = <?php echo $Counter_ReportsAPP;?>;
  Counter_r_feeds = <?php echo $Counter_ReportsFeeds;?>;
  Counter_r_comments = <?php echo $Counter_ReportsComments;?>;
  Counter_r_chats = <?php echo $Counter_ReportsChats;?>;
  Counter_r_users = <?php echo $Counter_ReportsUsers;?>;
  Counter_Crashes = <?php echo $Counter_Crashes;?>;

  total_counter_reports = Counter_r_app + Counter_r_feeds + Counter_r_comments + Counter_r_chats + Counter_r_users;
  total_counter_feeds = Counter_feeds + Counter_feeds_vip + Counter_questions;

  document.getElementById( "Counter_users_1" ).append(Counter_users);
  document.getElementById( "Counter_feeds_1" ).append(total_counter_feeds);
  document.getElementById( "Counter_reports_1" ).append(total_counter_reports);
  document.getElementById( "Counter_contact_us_1" ).append(Counter_ContactUs);
  document.getElementById( "Counter_crashes_1" ).append(Counter_Crashes);

  document.getElementById( "Counter_users_2" ).prepend(Counter_users);
  document.getElementById( "Counter_feeds_2" ).prepend(Counter_feeds);
  document.getElementById( "Counter_feeds_vip_2" ).prepend(Counter_feeds_vip);
  document.getElementById( "Counter_question_2" ).prepend(Counter_questions);
  document.getElementById( "Counter_contact_us_2" ).prepend(Counter_ContactUs);
  document.getElementById( "Counter_r_App_2" ).prepend(Counter_r_app);
  document.getElementById( "Counter_r_Feeds_2" ).prepend(Counter_r_feeds);
  document.getElementById( "Counter_r_Comments_2" ).prepend(Counter_r_comments);
  document.getElementById( "Counter_r_Chats_2" ).prepend(Counter_r_chats);
  document.getElementById( "Counter_r_Users_2" ).prepend(Counter_r_users);
  document.getElementById( "Counter_crashes_2" ).prepend(Counter_Crashes);

  /* ******************************************************** */
  //triggered when modal is about to be shown
  $('#deleteUserModel').on('show.bs.modal', function(e) {

      //get user-id attribute of the clicked delete link
      var userId = $(e.relatedTarget).data('user-id');

      //populate the model delete button
      $(e.currentTarget).find('#deleteLink').attr("href", userId);
  });

</script>

<script type="text/javascript">
  
  var datesArray = <?php echo json_encode($dayes_dates); ?>; 
  var usersArray = <?php echo json_encode($array_new_users); ?>; 

  var maxUsersArray = Math.max.apply(Math, usersArray)+5;
  var maxFeedsArray = Math.max(Counter_feeds, Counter_feeds_vip, Counter_questions)+5;

// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: datesArray,
    datasets: [{
      label: "New Users",
      lineTension: 0.3,
      backgroundColor: "rgba(2,117,216,0.2)",
      borderColor: "rgba(2,117,216,1)",
      pointRadius: 5,
      pointBackgroundColor: "rgba(2,117,216,1)",
      pointBorderColor: "rgba(255,255,255,0.8)",
      pointHoverRadius: 5,
      pointHoverBackgroundColor: "rgba(2,117,216,1)",
      pointHitRadius: 50,
      pointBorderWidth: 2,
      data: usersArray,
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 10
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: maxUsersArray,
          maxTicksLimit: 5
        },
        gridLines: {
          color: "rgba(0, 0, 0, .125)",
        }
      }],
    },
    legend: {
      display: false
    }
  }
});

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ["App","Users", "Feeds", "Comments", "Chats"],
    datasets: [{
      data: [Counter_r_app, Counter_r_users, Counter_r_feeds, Counter_r_comments, Counter_r_chats],
      backgroundColor: ['#A931D7', '#007bff', '#28a745', '#dc3545', '#ffc107'],
    }],
  },
});

// Bar Chart Example
var ctx = document.getElementById("myBarChart");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ["Feeds", "Feeds VIP", "Questions"],
    datasets: [{
      label: "Feeds",
      backgroundColor: "rgba(2,117,216,1)",
      borderColor: "rgba(2,117,216,1)",
      data: [Counter_feeds, Counter_feeds_vip, Counter_questions],
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'Item'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 6
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: maxFeedsArray,
          maxTicksLimit: 5
        },
        gridLines: {
          display: true
        }
      }],
    },
    legend: {
      display: false
    }
  }
});


</script>
</html>

<?php

    function draw($user,$userImage,$userType,$userName)
    {
        ?>

            <tr>

                <td>

                    <small class="text-muted">                      
                    <?php
                    
                    $date = $user->metadata->createdAt;
                    echo strtotime($date->format('Y-m-d h:m')), "<br>";
                    echo $date->format('Y-m-d h:m');

                    ?>
                      
                    </small>

                    
                </td>
                <td>
                    <small class="text-muted" style="font-size: 60%;"><?php echo $user->uid; ?></small>
                </td>
                <td style="width:50px;">

                    <?php

                        if (strlen($userImage) != 0) {

                            ?>
                            <img src="<?php echo $userImage; ?>" width="50" height="50">
                            <?php

                        } else {

                            ?>
                            <img src="<?php echo APP_URL; ?>/img/image-profile.png" width="50" height="50">

                            <?php
                        }
                    ?>
                </td>

                <td>
                    <small class="text-muted"><?php echo $userName; ?></small>
                </td>

                <td>
                    <small class="text-muted"><?php echo $user->providerData['0']->providerId; ?></small>
                    <small class="text-muted"><?php echo $user->phoneNumber; ?></small>
                </td>

                <td>
                    <small class="text-muted">
                      <?php
                      switch ($userType) {
                        case 0:
                          echo USER_TYPE_0;
                          break;
                        case 1:
                          echo USER_TYPE_1;
                          break;
                        case 2:
                          echo USER_TYPE_2;
                          break;
                        case 3:
                          echo USER_TYPE_3;
                          break;
                        default:
                          echo "UNKNOWN";
                      }
                      ?>
                      
                    </small>
                </td>


                <td>
                    <small class="text-muted">Ath <?php if ($user->disabled) echo "Disabled"; else echo "Enabled";?></small>
                </td>
                  <td>
                 
                    <div class="btn-group">
                      <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="">
                        Action
                        <span class="caret"></span>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                          <a class="dropdown-item" href="/admin/profile?user_id=<?php echo $user->uid; ?>">Go To Profile</a>
                          
                          <div class="dropdown-divider"></div>

                       <?php
                          if ($user->disabled) {

                              ?>
                          <a class="dropdown-item" href="/admin/main/?id=<?php echo $user->uid; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=enableUser">Enable Ath</a>
                              <?php

                          } else {

                              ?>
                          <a class="dropdown-item" href="/admin/main/?id=<?php echo $user->uid; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=disableUser">Disable Ath</a>
                              <?php
                          }
                          ?>
                        
                          <a class="dropdown-item" href="" data-toggle="modal" data-user-id="/admin/main/?id=<?php echo $user->uid; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=deleteUser" data-target="#deleteUserModel">Delete Ath</a>

                      </div>
                    </div>
                  </td>

            </tr>

        <?php
    }