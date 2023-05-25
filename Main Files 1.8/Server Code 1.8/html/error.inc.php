<?php

    $page_title = "Error 404 | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body id="page-top">


        <?php

            include_once("../html/common/topbar.inc.php");
        ?>


  <div id="wrapper">

    <div id="content-wrapper">

      <div class="container-fluid">

                <div class="row">

                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-body" style="text-align: center;">
                                <h4 class="card-title">ERROR 404 Page Not Found</h4>

                                    <a href="/" class="btn btn-info text-uppercase waves-effect waves-light">
                                        Back To The Main Page
                                    </a>

                            </div>
                        </div>

                    </div>


                </div>

            </div>
        </div>
    </div>

    <?php

    include_once("../html/common/footer.inc.php");
    ?>


</body>
</html>