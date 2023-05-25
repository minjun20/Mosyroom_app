<?php

     if (admin::isSession()) {

        header("Location: /");
    }

    $admin = new admin($dbo);
    if ($admin->getCount() > 0) {

        header("Location: /");
    } 


    include_once("../sys/core/initialize.inc.php");

    $page_id = "install";

    $error = false;
    $error_message = array();
    $l0msdfjsdf1u = false;
    $l0msdfjsdfio = false;
    $l0msdfjsdfiu = array();

    $pcode = '';
    $user_username = '';
    $user_fullname = '';
    $user_password = '';
    $user_password_repeat = '';
    
    $error_token = false;
    $error_username = false;
    $error_fullname = false;
    $error_password = false;
    $error_password_repeat = false;

    if (!empty($_POST)) {

        $error = false;

        $pcode = isset($_POST['p_c']) ? $_POST['p_c'] : '';
        $user_username = isset($_POST['user_username']) ? $_POST['user_username'] : '';
        $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
        $user_fullname = isset($_POST['user_fullname']) ? $_POST['user_fullname'] : '';
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $pcode = helper::clearText($pcode);
        $user_username = helper::clearText($user_username);
        $user_fullname = helper::clearText($user_fullname);
        $user_password = helper::clearText($user_password);
        $user_password_repeat = helper::clearText($user_password_repeat);

        $pcode = helper::escapeText($pcode);
        $user_username = helper::escapeText($user_username);
        $user_fullname = helper::escapeText($user_fullname);
        $user_password = helper::escapeText($user_password);
        $user_password_repeat = helper::escapeText($user_password_repeat);

        
        if (helper::getAuthenticityToken() !== $token) {

            $error = true;
            $error_token = true;
            $error_message[] = 'Error!';
        }else{

            $auth = helper::addAuthenticityToken($pcode);
            if (!$auth[base64_decode('c3VjY2Vzcw==')]) {
                $l0msdfjsdf1u = true;
                $l0msdfjsdfio = true;
                $l0msdfjsdfiu[] = base64_decode('RXJyb3Ih');
            }

        }

        if (!helper::isCorrectLogin($user_username)) {

            $error = true;
            $error_username = true;
            $error_message[] = 'Incorrect username.';
        }

        if (!helper::isCorrectPassword($user_password)) {

            $error = true;
            $error_password = true;
            $error_message[] = 'Incorrect password.';
        }

        if (!$error && !$l0msdfjsdf1u) {

            $admin = new admin($dbo);

            // Create admin account
 
            $result = array();
            $result = $admin->signup($user_username, $user_password, $user_fullname);

            if ($result['error'] === false) {

                $access_data = $admin->signin($user_username, $user_password);

                if ($access_data['error'] === false) {

                    $clientId = 0; // Desktop version
                    
                    $databaseRules->updateRules($ruleSet);

                    admin::createAccessToken();

                    admin::setSession($access_data['accountId'], admin::getAccessToken());

                    // Redirect to Admin Panel main page

                    header("Location: /admin/main");
                    exit;
                }

                header("Location: /install");
            } 
            
        }elseif (!$error && $l0msdfjsdf1u) {
            # code...
            $error = true;
            $error_message = $l0msdfjsdfiu;
        }
    }

    helper::newAuthenticityToken();

    $page_title = APP_TITLE;

    include_once("../html/common/admin_header.inc.php");
?>




<body id="page-top">

    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

        <a class="navbar-brand mr-1" href="/install
        "><?php echo $page_title; ?> - Install Page </a>

    </nav>

  <div id="wrapper">

    <div id="content-wrapper">

      <div class="container-fluid">

                <div class="row">

                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-body">

                            <h1>Warning!</h1>
                            <p>Remember that now Create an account administrator!</p>

                            <div class="errors-container" style="<?php if (!$error) echo "display: none"; ?>">
                                <p class="title">Errors</p>
                                <ul>
                                    <?php

                                    foreach ($error_message as $msg) {

                                        echo "<li>{$msg}</li>";
                                    }
                                    ?>
                                </ul>
                            </div>

                            <form accept-charset="UTF-8" action="/install" class="form-material m-t-40" id="remind-form" method="post">

                                <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">


                                    <div class="form-group">
                                        <label >Purchase Code</label>
                                        <input placeholder="Purchase Code" id="p_c" type="text" required="required" name="p_c" class="form-control form-control-line" value="<?php echo $pcode; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label >Username</label>
                                        <p style="color: grey;">Username must be more than 4 charachters with no spaces</p>
                                        <input placeholder="Username" id="user_username" type="text" required="required" name="user_username" class="form-control form-control-line" value="<?php echo $user_username; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label >Fullname</label>
                                        <input placeholder="Fullname" id="user_fullname" type="text" required="required" name="user_fullname" class="form-control form-control-line" value="<?php echo $user_fullname; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label >Password</label>
                                        <p style="color: grey;">Password must be more than 6 charachters with no spaces</p>
                                        <input placeholder="Password" id="user_password" type="password" required="required" name="user_password" class="form-control form-control-line" value="<?php echo $user_password; ?>">
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-info text-uppercase waves-effect waves-light" name="commit" type="submit">Install</button>
                                        </div>
                                    </div>

                            </form>

                            </div>
                        </div>

                    </div>


                </div>

            </div>
        </div>
    </div>

    <?php

    include_once("../html/common/admin_footer.inc.php");
    ?>


</body>


</html>