<?php
require 'Application.php';
$sql="Select time_id, start_time, end_time, hours_worked, reg_hours, ot_hours,dt_hours, status from dtbl_timesheet where emp_id='".$_SESSION['employeeID']."' and start_time >= ".$_GET['start']." and start_time <= ".$_GET['end']." order by time_id" ;
//echo $sql;
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
$status_type = array('Submitted', 'Rejected', 'Approved','Generated');
$className = array('fc-event-pending', 'fc-event-reject', 'fc-event-approved','fc-event-pending');
$return_arr = array();




for($i=0;$i < count($time); $i++)
{
$title = "<br/><strong>Total Hrs : </strong>".$time[$i]['hours_worked']."<br/><strong>Reg. Hrs : </strong>".$time[$i]['reg_hours'].
        "<br/><strong>OT Hrs : </strong>".$time[$i]['ot_hours']. "<br/><strong>DT Hrs : </strong>".$time[$i]['dt_hours']."<br/><strong>Status : </strong>".$status_type[$time[$i]['status']];


	$date = date('Y-m-d', $time[$i]['start_time']);
 $flag=0;
 foreach($return_arr as $key=>$res)
 {
 if($res['start']==$date){
    $res['title_val']['hours_worked']+= $time[$i]['hours_worked'];
    $res['title_val']['reg_hours']+= $time[$i]['reg_hours'];
    $res['title_val']['ot_hours']+= $time[$i]['ot_hours'];
$return_arr[$key]['title'] = "<br/><strong>Total Hrs : </strong>".$res['title_val']['hours_worked']."<br/><strong>Reg. Hrs : </strong>".$res['title_val']['reg_hours'].
        "<br/><strong>OT Hrs : </strong>".$res['title_val']['ot_hours']."<br/><strong>Status : </strong>";
  $flag=1;
  $status='Rejected'; 
 if($time[$i]['status']=='1') 
 {
     $return_arr[$key]['className']='fc-event-reject';
     $status='Rejected';
 }
 
 else if($time[$i]['status']=='2'&& $return_arr[$key]['className']!='fc-event-reject'&& $return_arr[$key]['className']!='fc-event-pending') 
 {
  $return_arr[$key]['className']='fc-event-approved';
   $status='Approved';
 }
 else if($time[$i]['status']=='0'&& $return_arr[$key]['className']!='fc-event-reject')
 {
  $return_arr[$key]['className']='fc-event-pending';
   $status='Submitted';  
 }
 $return_arr[$key]['title'].=$status;
 }    
 }
 if($flag==0){
	$return_arr[]=array(
			'id' => $time[$i]['time_id'],
			'title' => $title,
			'start' => $date,
			'className'=> $className[$time[$i]['status']],
			'url' => "#",
            'title_val'=>array('hours_worked'=>$time[$i]['hours_worked'],'reg_hours'=>$time[$i]['reg_hours'],'ot_hours'=>$time[$i]['ot_hours'],'dt_hours'=>$time[$i]['dt_hours'],'status'=>$time[$i]['status'])
		);
     
                }
}
echo json_encode($return_arr);
return;
?>
