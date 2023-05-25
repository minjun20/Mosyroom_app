// Call the dataTables jQuery plugin
$(document).ready(function($) {
  $.noConflict();
  $('#dataTable').DataTable();
  $('#maindataTable').DataTable( {
    "order": [[ 0, "desc" ]]
} );
});
