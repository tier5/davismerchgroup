<?php
require 'Application.php';

$sql="Select time_id, start_time, end_time, hours_worked, reg_hours, ot_hours, status from dtbl_timesheet where emp_id='".$_GET['user']."' and start_time >= ".$_GET['start']." and start_time <= ".$_GET['end'];
if(!($result=pg_query($connection,$sql))){
	print("Failed query: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$time[]=$row;
}
pg_free_result($result);

//print_r($time);
$status_type = array('Submitted', 'Rejected', 'Approved');
$className = array('fc-event-pending', 'fc-event-reject', 'fc-event-approved');
$return_arr = array();

for($i=0;$i < count($time); $i++)
{
	$title = "<br/><strong>Total Hrs : </strong>".$time[$i]['hours_worked']."<br/><strong>Reg. Hrs : </strong>".$time[$i]['reg_hours']."<br/><strong>OT Hrs : </strong>".$time[$i]['ot_hours']."<br/><strong>Status : </strong>".$status_type[$time[$i]['status']];
	$date = date('Y-m-d', $time[$i]['start_time']);
	$return_arr[]=array(
			'id' => $time[$i]['time_id'],
			'title' => $title,
			'start' => $date,
			'className'=> $className[$time[$i]['status']],
			'url' => "#"
		);
}
echo json_encode($return_arr);
return;
?>
