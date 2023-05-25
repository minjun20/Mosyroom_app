<?php

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }


if (!empty($_POST)) {

        $chatId = isset($_POST['chatId']) ? $_POST['chatId'] : '';
        $firstMsgId = isset($_POST['firstMsgId']) ? $_POST['firstMsgId'] : '';
        $incomingId = isset($_POST['incomingId']) ? $_POST['incomingId'] : '';


        /* Retrive reports data from firebase */
        $referencePath0 = "chats/".$chatId;

        $reference0 = $database->getReference($referencePath0)->orderByKey()->endAt($firstMsgId)->limitToLast(10);

        $snapshot0 = $reference0->getSnapshot();

        $result0  = $snapshot0->getValue();

        array_pop($result0);

        $items_loaded = count($result0);

        if ($items_loaded > 0) {

            ob_start();

            foreach ($result0 as $key => $value) {

                if ($value['senderId'] == $incomingId) {
                    $incoming = true;        
                }else{
                    $incoming = false;        
                }

                    draw($key, $value,$incoming);


            }

            $result['html'] = ob_get_clean();

            if ($items_loaded >= 9) {

                ob_start();

                $firstMsgId0 = array_key_first($result0);
                $v = $result0[$firstMsgId0];
                $incomingId0 = $v['senderId'];
                ?>

                <button class="btn btn-info btn-block loadeMore"  href="javascript:void(0)" 
                onclick="myFunction(
                '<?php echo $chatId; ?>',
                '<?php echo $firstMsgId0; ?>',
                '<?php echo $incomingId0; ?>'
                ); return false;">Load More</button>

                <?php

                $result['html2'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
}



    $chatId = 0;

    if (isset($_GET['chat_id'])) {

        $chatId = isset($_GET['chat_id']) ? $_GET['chat_id'] : '';
        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;
        $act = isset($_GET['act']) ? $_GET['act'] : '';

        if ($chatId == "") {
        header("Location: /admin/main");
        exit;
        }

        /* Retrive reports data from firebase */
        $referencePath = "chats/".$chatId;

        $reference = $database->getReference($referencePath)->orderByKey()->limitToLast(10);

        $snapshot = $reference->getSnapshot();

        $result  = $snapshot->getValue();




        if ($accessToken === admin::getAccessToken()) {

            switch ($act) {


                case "deletePost": {

                    header("Location: /admin/chat?chat_id=".$chat_id);
                    break;
                }
                default: {
                    header("Location: /admin/chat?chat_id=".$chat_id);
                    exit;
                }
            }
        }


    } else {

        header("Location: /admin/main");
        exit;
    }

    $page_id = "chat";

    $css_files = array("mytheme.css");
    $page_title = "Chat Info | Admin Panel";

    include_once("../html/common/admin_header.inc.php");
?>

        <script type="text/javascript" src="/admin/js/jsViewer/json-viewer.js"></script>
        <link rel="stylesheet" type="text/css" href="/admin/css/jsViewer/json-viewer.css">

<style type="text/css">

.container{max-width:1170px; margin:auto;}
img{ max-width:100%;}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%; padding:
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 550px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.outgoing_msg_img {
  display: inline-block;
  width: 6%;
  float: right;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 100%;
}

 .sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.incoming_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
  padding-right: 10px;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 516px;
  overflow-y: auto;
}

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
                        <h3 class="text-themecolor">CHAT</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                            <li class="breadcrumb-item active">Chat Info</li>
                        </ol>
                    </div>
                </div>



<?php
if ($result != null) {
?>
<div class="container">
<h3 class=" text-center">Messaging</h3>
<div class="messaging">
      <div class="inbox_msg">
        <div class="mesgs">


<?php

$firstMsgId = array_key_first($result);
$lastMsgId = array_key_last($result);
$v = $result[$firstMsgId];
$incomingId = $v['senderId'];
?>
<div class="divLoadMore">
    <button class="btn btn-info btn-block loadeMore"  href="javascript:void(0)" 
    onclick="myFunction(
    '<?php echo $chatId; ?>',
    '<?php echo $firstMsgId; ?>',
    '<?php echo $incomingId; ?>'
    ); return false;">Load More</button>
</div>
<br> 
<br> 
        <div class="msg_history">

<?php

foreach ($result as $key => $value) {

    if ($value['senderId'] == $incomingId) {
        $incoming = true;        
    }else{
        $incoming = false;        
    }

        draw($key, $value,$incoming);


}

?>

          </div>
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

<!-- Modal -->
<div class="modal fade" id="MsgInfo" tabindex="-1" role="dialog" aria-labelledby="MsgInfoTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="MsgInfoTitle">Message Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


            <?php

                include_once("../html/common/admin_footer.inc.php");
            ?>

    </div> <!-- End Wrapper -->

</body>
<script src="/admin/js/jquery.min.js"  crossorigin="anonymous"></script>

<script type="text/javascript">
function myFunction(chatId,firstMsgId,incomingId) {

    //$('button.loadeMore').hide();
    $('button.loadeMore').html('Loading...');

    $.ajax({
        type: 'POST',
        url: '/admin/chat',
        data: 'chatId=' + chatId + "&firstMsgId=" + firstMsgId+ "&incomingId=" + incomingId,
        dataType: 'json',
        timeout: 30000,
        success: function(response){

            if (response.hasOwnProperty('html')){
                 console.log(response.html);
                $("div.msg_history").prepend(response.html).show();
            }

            if (response.hasOwnProperty('html2')){
                $("div.divLoadMore").html("").append(response.html2).show();
            }else{
            
                $('button.loadeMore').hide();

            }
        },
        error: function(xhr, type){

            $('button.loadeMore').show();
            $('button.loadeMore').html('Load More');

        }
    });
};

$(document).on("click", "#btn-msg-info", function () {

    var jsonObj = {};
    var jsonViewer = new JSONViewer();
     $(".modal-body").html("");
    document.querySelector(".modal-body").appendChild(jsonViewer.getContainer());
    
    jsonObj = $(this).data('id');

     var msgId = JSON.stringify($(this).data('id'));

    jsonViewer.showJSON(jsonObj);

});



</script>
</html>

<?php

    function draw($key, $value, $incoming)
    {

        if ($incoming) {
            # code...
        ?>
                  


            <div class="incoming_msg">
              <div class="incoming_msg_img"> 

                <?php
            if (strlen($value['senderImage']) != 0) {

                ?>
                <img src="<?php echo $value['senderImage']; ?>" alt="" />
                <?php

            } else {

                ?>
                <img src="<?php echo APP_URL; ?>/img/image-profile.png" alt="" >
                <?php
            }
                ?>
            </div>

              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>
                      
                    <?php echo $value['body']; ?>


                  </p>

                <?php
            if ($value['attachmentType'] == 2) {

                ?>
                <img src="<?php echo $value['attachment']['url']; ?>"  alt="" />
                <?php

            }
                ?>

                  <span class="time_date"> 

                    <?php 
                        $timestamp = $value['date'];
                        $seconds = $timestamp / 1000;
                        echo date("d-m-Y H:i", $seconds);
                    ?>
                    
                  </span>
                  <!-- Button trigger modal -->
                    <button type="button"  data-id="<?php echo htmlspecialchars(json_encode($value)); ?>" id="btn-msg-info" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#MsgInfo">
                      More Info
                    </button>
              </div>
              </div>
            </div>


<?php
}else{
?>

            <div class="outgoing_msg">
        
            <div class="outgoing_msg_img">
                <?php
            if (strlen($value['senderImage']) != 0) {

                ?>
                <img src="<?php echo $value['senderImage']; ?>" alt="" />
                <?php

            } else {

                ?>
                <img src="<?php echo APP_URL; ?>/img/image-profile.png" alt="" >
                <?php
            }
                ?>       
            </div>

              <div class="sent_msg">
                <p>
                    <?php echo $value['body']; ?>
                </p>
                <?php
                if ($value['attachmentType'] == 2) {

                    ?>
                    <img src="<?php echo $value['attachment']['url']; ?>"  alt="" />
                    <?php

                }
                ?>
                <span class="time_date"> 

                    <?php 
                        $timestamp = $value['date'];
                        $seconds = $timestamp / 1000;
                        echo date("d-m-Y H:i", $seconds);
                    ?>

                </span>
                  <!-- Button trigger modal -->
                <button type="button" data-id="<?php echo htmlspecialchars(json_encode($value)); ?>" id="btn-msg-info" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#MsgInfo">
                      More Info
                    </button>
                 </div>
    
            </div>

        <?php
    }
}