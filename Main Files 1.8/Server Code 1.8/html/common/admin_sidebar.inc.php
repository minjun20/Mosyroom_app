<?php

$page_search = false;
$page_reports = false;
if (strpos($page_id, 'search') === 0) {
    $page_search = true;
}elseif (strpos($page_id, 'reports') === 0) {
    $page_reports = true;
}
?>

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link <?php if (isset($page_id) && $page_id === "main") { echo "active";} ?>" href="/admin/main">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <div class="sb-sidenav-menu-heading">General</div>

<?php 
if ($page_search) {
?>

                <a class="nav-link " href="#" data-toggle="collapse" data-target="#collapseLayouts0" aria-expanded="true" aria-controls="collapseLayouts0">
<?php
}else{
?>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts0" aria-expanded="false" aria-controls="collapseLayouts0">
<?php
}
?>




                    <div class="sb-nav-link-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    Search
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>


<?php 
if ($page_search) {
?>

                <div class="collapse show" id="collapseLayouts0" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
<?php
}else{
?>

                <div class="collapse" id="collapseLayouts0" aria-labelledby="headingOne" data-parent="#sidenavAccordion">

<?php
}
?>






                    <nav class="sb-sidenav-menu-nested nav">


                      <a class="nav-link <?php if (isset($page_id) && $page_id === "searchUsers") { echo "active";} ?>" href="/admin/searchUsers">
                        Search Users
                      </a>
                      <a class="nav-link <?php if (isset($page_id) && $page_id === "searchFeeds") { echo "active";} ?>" href="/admin/searchFeeds">
                        Search Feeds
                      </a>
                      <a class="nav-link <?php if (isset($page_id) && $page_id === "searchFeedsVip") { echo "active";} ?>" href="/admin/searchFeedsVip">
                        Search Feeds VIP
                      </a>


                    </nav>
                </div>



<?php 
if ($page_reports) {
?>

                <a class="nav-link " href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="true" aria-controls="collapseLayouts">
<?php
}else{
?>

                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
<?php
}
?>


                    <div class="sb-nav-link-icon">
                      <i class="fas fa-fw fa-exclamation-triangle"></i>
                    </div>
                    Reports
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>


<?php 
if ($page_reports) {
?>

                <div class="collapse show" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
<?php
}else{
?>

                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">

<?php
}
?>

                    <nav class="sb-sidenav-menu-nested nav">

                      <a class="nav-link <?php if (isset($page_id) && $page_id === "reports") { echo "active";} ?>" href="/admin/report">
                        Reports App
                      </a>

                      <a class="nav-link <?php if (isset($page_id) && $page_id === "reportsChats") { echo "active";} ?>" href="/admin/reportChats">
                        Reports Chats
                      </a>

                      <a class="nav-link <?php if (isset($page_id) && $page_id === "reportsComments") { echo "active";} ?>" href="/admin/reportsComments">
                        Reports Comments
                      </a>

                      <a class="nav-link <?php if (isset($page_id) && $page_id === "reportsFeeds") { echo "active";} ?>" href="/admin/reportsFeeds">
                        Reports Feeds
                      </a>

                      <a class="nav-link <?php if (isset($page_id) && $page_id === "reportsUsers") { echo "active";} ?>" href="/admin/reportsUsers">
                        Reports Users
                      </a>
                    </nav>
                </div>

                <a class="nav-link <?php if (isset($page_id) && $page_id === "crash") { echo "active";} ?>" href="/admin/crash">
                    <div class="sb-nav-link-icon"><i class="fas fa-fw fa-bug"></i></div>
                    Crashs
                </a>

                <a class="nav-link <?php if (isset($page_id) && $page_id === "support") { echo "active";} ?>" href="/admin/support">
                    <div class="sb-nav-link-icon"><i class="fas fa-fw fa-envelope"></i></div>
                    Contacts
                </a>


                <a class="nav-link <?php if (isset($page_id) && $page_id === "gcm") { echo "active";} ?>" href="/admin/gcm">
                    <div class="sb-nav-link-icon"><i class="fas fa-fw fa-bell"></i></div>
                    Push Notifications
                </a>


                <div class="sb-sidenav-menu-heading">Settings</div>


                <a class="nav-link <?php if (isset($page_id) && $page_id === "settings") { echo "active";} ?>" href="/admin/settings">
                    <div class="sb-nav-link-icon"><i class="fas fa-fw fa-cog"></i></div>
                    Settings
                </a>

                <a class="nav-link"  data-toggle="modal" data-target="#logoutModal">
                    <div class="sb-nav-link-icon"><i class="fas fa-fw fa-power-off"></i></div>
                    Logout
                </a>


            </div>
        </div>
    </nav>
</div>