<?php

    $page_id = "update";

    include_once("../sys/core/initialize.inc.php");

    $update = new update($dbo);


    // v 1.3
    $update->addColumnToFeedsTable();
    $update->addColumnToFeedsTable2();
    $update->addColumnToFeedsVipTable();
    $update->addColumnToFeedsVipTable2();




    $css_files = array("main.css", "my.css");
    $page_title = APP_TITLE;

    include_once("../html/common/admin_header.inc.php");
?>

<body class="remind-page">

    <div class="wrap content-page">
        <div class="main-column">
            <div class="main-content">

                <div class="standard-page">

                    <div class="success-container" style="margin-top: 15px;">
                        <ul>
                            <b>Success!</b>
                            <br>
                            Database refactoring success!
                        </ul>
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