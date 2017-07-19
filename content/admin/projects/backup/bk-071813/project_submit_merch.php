<?php
require 'Application.php';
extract($_POST);
$ret = array();
$ret['merch_id'] = '';
$ret['status'] = -1;

        $sql = 'delete  from prj_merchants_new where m_id=' . $merch_id;
        pg_query($connection, $sql) or die("Error...");

?>
<script type="text/javascript">
    $(document).ready(function(){

        $('#st_time').timepicker({
            ampm: true,	
            minuteGrid: 15
        });
  
    });
</script>