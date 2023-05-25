  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="/admin/logout/?access_token=<?php echo admin::getAccessToken(); ?>&continue=/admin/login">Logout</a>
        </div>
      </div>
    </div>
  </div>


    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="/admin/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="/admin/js/scripts.js"></script>



<script src="/admin/js/Chart.min.js" crossorigin="anonymous"></script>
<script src="/admin/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="/admin/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>



<?php
$page_search = false;
if (strpos($page_id, 'search') === 0) {
    $page_search = true;
}
if ($page_id != "chat" && !$page_search) {
?>
<script src="/admin/js/datatables-demo.js"></script>
<?php  
}
?>
