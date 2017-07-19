<?php
require 'Application.php';
$sql='delete from dtbl_odometer where time_id='.$_POST['time_id'];
$sql.=';delete from dtbl_timesheet where time_id='.$_POST['time_id'];
echo 'sq---'.$sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
?>