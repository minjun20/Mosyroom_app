<?php

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

    $postId = 0;
    $postInfo = array();

    $comment_reported_id = "";
    if (isset($_GET['feed_id'])) {

        $postId = isset($_GET['feed_id']) ? $_GET['feed_id'] : '';
        $comment_reported_id = isset($_GET['comment_id']) ? $_GET['comment_id'] : '';
        $comment_delete_id = isset($_GET['comment_delete_id']) ? $_GET['comment_delete_id'] : '';
        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;
        $act = isset($_GET['act']) ? $_GET['act'] : '';

        if ($postId == "") {
        header("Location: /admin/main");
        exit;
        }

        /* Retrive reports data from firebase */
        $referencePath = "Feeds/".$postId;

        $reference = $database->getReference($referencePath);

        $snapshot = $reference->getSnapshot();

        $type = "Feeds";

        if (!$snapshot->exists()) {
            # code...
            $referencePath = "FeedsVip"."/".$postId;
            $reference = $database->getReference($referencePath);
            $snapshot = $reference->getSnapshot();
            
            $type = "Feeds Vip";

            if (!$snapshot->exists()) {
                $referencePath = "Questions"."/".$postId;
                $reference = $database->getReference($referencePath);
                $snapshot = $reference->getSnapshot();
                $type = "Questions";
                $filterId = "";
                if (!$snapshot->exists()) {
                    $type = "removed";
                }else{
                $referencePathQF = "Questions"."/".$postId."/filterId";
                $referenceQF = $database->getReference($referencePathQF);
                $snapshotQF = $referenceQF->getSnapshot();        
                $filterId = $snapshotQF->getValue();

                $isAnswered = false;
                
                if(substr($filterId, 0, 1) == 9){
                    $isAnswered = false;
                }else{
                    $isAnswered = true;
                }

                }
            }
        }


        $result  = $snapshot->getValue();




        if ($accessToken === admin::getAccessToken()) {

            switch ($act) {


                case "deletePost": {
                        if ($type != "removed") {
                            # code...
                            $database->getReference($type."/".$postId)->set(null);
                        }

                    header("Location: /admin/post/?feed_id=".$postId);
                    break;
                }
                case "setAnswered": {
                        if ($type == "Questions") {
                            # code...
                            $new_filterId = intval(substr_replace($filterId,8,0,1));
                            $database->getReference($type."/".$postId."/filterId")->set($new_filterId);
                        }

                    header("Location: /admin/post/?feed_id=".$postId);
                    break;
                }
                case "setNotAnswered": {
                        if ($type == "Questions") {
                            # code...
                            $new_filterId = intval(substr_replace($filterId,9,0,1));
                            $database->getReference($type."/".$postId."/filterId")->set($new_filterId);
                        }

                    header("Location: /admin/post/?feed_id=".$postId);
                    break;
                }
                case "deleteComment": {
                        if ($comment_delete_id != "") {
                            $database->getReference("Comments/".$postId."/".$comment_delete_id)->set(null);
                        }

                    header("Location: /admin/post/?feed_id=".$postId);
                    break;
                }

                default: {
                    header("Location: /admin/post/?feed_id=".$postId);
                    exit;
                }
            }
        }


    } else {

        header("Location: /admin/main");
        exit;
    }

    $page_id = "post";

    $css_files = array("mytheme.css");
    $page_title = "Post Info | Admin Panel";

    include_once("../html/common/admin_header.inc.php");
?>

<style type="text/css">
.widget { width: 100%; }
.widget .panel-body { padding:0px; }
.widget .list-group { margin-bottom: 0; }
.widget .panel-title { display:inline }
.widget .label-info { float: right; }
.widget li.list-group-item {border-radius: 0;border: 0;border-top: 1px solid #ddd;}
.widget li.list-group-item:hover { background-color: rgba(86,61,124,.1); }
.widget .mic-info { color: #666666;font-size: 11px; }
.widget .action { margin-top:5px; }
.widget img { width: 50px; height: 50px; }
.widget .comment-text { font-size: 12px; }
.widget .btn-block { border-top-left-radius:0px;border-top-right-radius:0px; }
</style>


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

                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor">POST</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                            <li class="breadcrumb-item active">Post Info</li>
                        </ol>
                    </div>
                </div>

                <?php
                    if ($result != null) {

                    /* Retrive user data from firebase */
                    $referencePath1 = "users"."/".$result['userId'];

                    $reference1 = $database->getReference($referencePath1);



                    $snapshot1 = $reference1->getSnapshot();

                    $userFirebase1 = $snapshot1->getValue();

                    /* End Retrive user data from firebase */

                ?>


				<div class="card gedf-card col-xs-auto col-sm-auto col-md-8 col-lg-8  col-xl-8 mx-auto p-0">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mr-2">

                            <?php
                        if (strlen($userFirebase1['image']) != 0) {

                            ?>
                            <img src="<?php echo $userFirebase1['image']; ?>" class="rounded-circle" src="" alt="" width="45" />
                            <?php

                        } else {

                            ?>
                            <img src="<?php echo APP_URL; ?>/img/image-profile.png" class="rounded-circle" src="" alt="" width="45">
                            <?php
                        }
                            ?>

                                </div>
                                <div class="ml-2">
                                    <div class="h5 m-0">@<?php echo $userFirebase1['userName']; ?></div>
                                    <div class="h7 text-muted"><?php echo $userFirebase1['name']; ?></div>
                                </div>
                            </div>
                            <div>
                                <div class="dropdown">
                                    <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(38px, 38px, 0px);">
                                        <div class="h6 dropdown-header">Options</div>

                                        <a class="dropdown-item" 
                                        href="/admin/post?feed_id=<?php echo $postId; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=deletePost">
                                		Delete
                                		</a>
                                        <?php 
                                        if ($type == "Questions") {
                                            if ($isAnswered) {

                                            ?>
                                                <a class="dropdown-item" 
                                                href="/admin/post?feed_id=<?php echo $postId; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=setNotAnswered">
                                            	Set Not Answered
                                            	</a>
                                            <?php 
                                            }else{
                                            ?>
                                                <a class="dropdown-item" 
                                                href="/admin/post?feed_id=<?php echo $postId; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=setAnswered">
                                                Set Answered
                                                </a>
                                            <?php 
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                    
                        <div class="text-muted h7 mb-2"> 
						<i class="far fa-clock"></i>
							<?php 
								$timestamp = $result['timestamp'];
								$seconds = $timestamp / 1000;
								echo date("d-m-Y H:i", $seconds);
							?>                 	
                    	</div>
                        <a class="card-link" href="#">
                            <h5 class="card-title">Post Type: <?php echo $type; ?></h5>
                        </a>

                        <p class="card-text">
							<?php echo $result['text']; ?>
                        </p>



                        <?php
                            if (array_key_exists('videoUrl', $result)) {

                                if($result['videoUrl'] != "") {
                                //echo $value;
                                ?>


                                <video width="100%" controls>
                                  <source src="<?php echo $result['videoUrl'] ?>" type="video/mp4">
                                  Your browser does not support HTML video.
                                </video>


                                <?php
                                }
                            }
                        ?>


                        <?php
                            if (array_key_exists('images', $result)) {

                                foreach ($result['images'] as $key => $value) {
                                //echo $value;
                                ?>
                                <br>
                                <img class="card-img-overlay" style="position: relative;" width="200" height="200" src="<?php echo $value; ?>" alt="Card image">
                                <br>

                                <?php
                                }
                            }
                        ?>

                    </div>
                    <div class="card-footer">
                        <?php 
                        if ($type == "Questions") {
                        ?>
                        <a class="card-link"><i class="far fa-arrow-alt-circle-up"></i> Up</a>
                        <?php 
                        }else{
                        ?>
                        <a class="card-link"><i class="fas fa-heart"></i></i> Like</a>
                        <?php 
                        }
                        ?>
                        <a class="card-link"><i class="fa fa-comment"></i> Comment</a>
                        <a class="card-link"><i class="fas fa-share"></i></i> Share</a>
                    </div>
                </div>

<br>
<br>

  				<?php

                    /* Retrive reports data from firebase */
                    $referencePath = "Comments/".$postId;

                    $reference = $database->getReference($referencePath);

                    $snapshot = $reference->getSnapshot();

                    $result  = $snapshot->getValue();
                    
                    if ($result != null) {
                        $items_loaded = count($result);
                    }else{
                        $items_loaded = 0;
                    }

                    if ($items_loaded != 0) {

                        ?>
<div class="container">
    <div class="row">
        <div class="panel panel-default widget">
            <div class="panel-heading">
                <i class="fa fa-comment"></i> 
                <h5 class="panel-title">
                    Comments
                </h5>
                <span  class="badge"><?php echo $items_loaded; ?></span>
            </div>
            <div class="panel-body">
                <ul class="list-group">
                  
                  
                                                <?php

                                                    foreach ($result as $key => $value) {
                                                        
                                                    /* Retrive user data from firebase */
                                                    $referencePath1 = "users"."/".$value['userId'];

                                                    $reference1 = $database->getReference($referencePath1);



                                                    $snapshot1 = $reference1->getSnapshot();

                                                    $userFirebase1 = $snapshot1->getValue();
                                                    $userName = $userFirebase1['userName'];
                                                    $userImage = $userFirebase1['image'];

                                                    /* End Retrive user data from firebase */
                                                    $userName1 = "";
                                                    if (strlen($value['replyTo'])) {
                                                        
                                                        $referencePath1 = "users"."/".$value['userId'];

                                                        $reference1 = $database->getReference($referencePath1);



                                                        $snapshot1 = $reference1->getSnapshot();

                                                        $userFirebase1 = $snapshot1->getValue();
                                                        $userName1 = $userFirebase1['userName'];

                                                    }

                                                        draw($value, $key, $userName, $userImage, $userName1, $comment_reported_id,$postId);
                                                    
                                                    }

                                                    ?>

                </ul>
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
                                            <p class="card-text">This means that there is no Comments :)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ?>

                        <?php

                    } else {

                        ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h4 class="card-title">Empty.</h4>
                                            <p class="card-text">This means that there is no Post with this ID :)</p>
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

            <?php

                include_once("../html/common/admin_footer.inc.php");
            ?>

            <script type="text/javascript">

                window.Post || ( window.Post = {} );

                Post.remove = function (offset, fromUserId, accessToken) {

                    $.ajax({
                        type: 'GET',
                        url: '/admin/stream/?id=' + offset + '&fromUserId=' + fromUserId + '&access_token=' + accessToken + "&act=remove",
                        data: 'itemId=' + offset + '&fromUserId=' + fromUserId + "&access_token=" + accessToken,
                        timeout: 30000,
                        success: function(response){

                            $('div.item[data-id=' + offset + ']').remove();

                            window.location.href = "/admin/post_reports";
                        },
                        error: function(xhr, type){

                        }
                    });
                };

            </script>

    </div> <!-- End Wrapper -->

</body>

</html>

<?php

    function draw($value, $comment_id, $formUserName, $userImage, $toUserName, $comment_reported_id,$feed_id)
    {

        ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xs-2 col-md-1">
                            <?php
                        if (strlen($userImage) != 0) {

                            ?>
                            <img src="<?php echo $userImage; ?>" class="img-circle img-responsive" alt="" />
                            <?php

                        } else {

                            ?>
                            <img src="<?php echo APP_URL; ?>/img/image-profile.png"class="img-circle img-responsive" alt="" >
                            <?php
                        }
                            ?>
                            </div>
                            <div class="col-xs-10 col-md-11">
                                <div>
                                    <a href="/admin/profile?user_id=<?php echo $value['userId']; ?>">
                    
										<?php echo $formUserName; ?>
            						
            						</a>

                                    <div class="mic-info">
										<?php

										if (strlen($toUserName) != 0) {
										?>

                                        Reply To: 
                                        <a href="/admin/profile?user_id=<?php echo $value['replyTo']; ?>">
                    					<?php echo $toUserName; ?>
                                    	</a>

                    					<?php 
										}
                    					?>

                                    	 on 

				                        <?php 
				                        	$timestamp = $value['timestamp'];
				                        	$seconds = $timestamp / 1000;
				                        	echo date("d-m-Y H:i", $seconds);
				                        ?>
                                    	 ID = 
										<?php 
										if ($comment_reported_id == $comment_id) {
										?>
										<span style="color: red">
										<?php
											echo $comment_id; 
										?>    
										</span>
										<?php
										}else{
											echo $comment_id; 
										}

										?>

                                    </div>
                                </div>
                                <div class="comment-text">
                                    <?php echo $value['text']; ?>
                                </div>
                                <div class="action">
                                	<a class="btn btn-danger btn-xs" href="/admin/post?feed_id=<?php echo $feed_id; ?>&comment_delete_id=<?php echo $comment_id; ?>&act=deleteComment&access_token=<?php echo admin::getAccessToken(); ?>">
										 <i class="fas fa-trash-alt btn-xs"></i>
                                	</a>

                                        <?php 
                                        if ($comment_reported_id == $comment_id) {
                                        ?>
                                        <a class="btn btn-warning btn-xs">
                                             <i class="fas fa-exclamation-triangle btn-xs"></i>
                                        </a>
                                        <?php
                                        }
                                        ?>


                                </div>
                            </div>
                        </div>
                    </li>
        <?php
    }