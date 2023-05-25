<?php

    if (admin::isSession()) {

        header("Location: /admin/main");
        exit;
    }

    $stats = new stats($dbo);
    $admin = new admin($dbo);

    $user_username = '';

    $error = false;
    $error_message = '';

    if (!empty($_POST)) {

        $user_username = isset($_POST['user_username']) ? $_POST['user_username'] : '';
        $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $user_username = helper::clearText($user_username);
        $user_password = helper::clearText($user_password);

        $user_username = helper::escapeText($user_username);
        $user_password = helper::escapeText($user_password);

        if (helper::getAuthenticityToken() !== $token) {

            $error = true;
            $error_message = 'Error!';
        }

        if (!$error) {

            $access_data = array();

            $admin = new admin($dbo);
            $access_data = $admin->signin($user_username, $user_password);

            if ($access_data['error'] === false) {

                $clientId = 0; // Desktop version

                admin::createAccessToken();

                admin::setSession($access_data['accountId'], admin::getAccessToken());

                header("Location: /admin/main");
                exit;

            } else {

                $error = true;
                $error_message = 'Incorrect login or password.';
            }
        }
    }

    helper::newAuthenticityToken();

    $page_id = "login";

    $css_files = array("mytheme.css");
    $page_title = "Admin| Log In";

    include_once("../html/common/admin_header.inc.php");
?>

    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">


        <form class="user" id="loginform" action="/admin/login" method="post">

	          <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">  
	          <p class="text-danger" style="<?php if (!$error) echo "display: none"; ?>"><?php echo $error_message; ?></p> 


	          <div class="form-group">
		        <label class="small mb-1" for="inputUserName">UserName</label>
		        <input class="form-control py-4"  name="user_username" value="<?php echo $user_username; ?>"  id="inputUserName" type="text" placeholder="User Name"  required="required" />
		    </div>
		    <div class="form-group">
		        <label class="small mb-1" for="inputPassword">Password</label>
		        <input class="form-control py-4" id="inputPassword" type="password" placeholder="Password" name="user_password" value="" required="required" />
		    </div>


	        <button class="btn btn-primary btn-user btn-block" type="submit">Log In</button>

        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
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
        </div>

<script src="/admin/js/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
<script src="/admin/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="/admin/js/scripts.js"></script>



<script src="/admin/js/Chart.min.js" crossorigin="anonymous"></script>
<script src="/admin/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="/admin/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="/admin/js/datatables-demo.js"></script>


</body>
</html>