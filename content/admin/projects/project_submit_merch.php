<?php
require 'Application.php';
extract($_POST);
$ret = array();
$ret['merch_id'] = '';
$ret['status'] = -1;

        $sql = 'delete  from prj_merchants_new where m_id=' . $merch_id;
        $sql .= ';delete  from dtbl_odometer where time_id=(select time_id from dtbl_timesheet where m_id=' . $merch_id.' limit 1) ';
        $sql .= ';delete  from dtbl_timesheet where m_id=' . $merch_id;
         $sql .= ';delete  from ralph_checklist_form where m_id=' . $merch_id;
         $sql .= ';delete  from dmg_form where m_id=' . $merch_id;
         $sql .= ';delete  from dmg_convnc_form where m_id=' . $merch_id;
         $sql .= ';delete  from stater_bros_form where m_id=' . $merch_id;
         $sql .= ';delete  from frito_lay_form where m_id=' . $merch_id;
         $sql .= ';delete  from pizza_form where m_id=' . $merch_id;
         $sql .= ';delete  from ralphs_reset_form where m_id=' . $merch_id;
        pg_query($connection, $sql) or die("Error...");
$sql='update projects  set merch_num_stat=0 where pid='.$pid;
pg_query($connection, $sql);      

?>
<script type="text/javascript">
    $(document).ready(function(){

        $('#st_time').timepicker({
            ampm: true,	
            minuteGrid: 15
        });
  
    });
</script>