<?php
require 'Application.php';
$break1 = 0;
$break2 = 0;
$return_arr = array();
$return_arr['error'] = "";
$return_arr['msg'] = "";
$adj_miles = 30;

extract($_POST);
$error = '';
if(isset($lunch_waive))
{
 $lunch_start=0;  
 $lunch_end=0;
}
$start_date=strtotime($start_date);
$end_date=strtotime($end_date);
$lunch_start2=strtotime($lunch_start);
$lunch_end2=strtotime($lunch_end);
$diff=$end_date-$start_date;
$lunch_hrs=0;
$reg_hours=0;
$ot_hours=0;
//$hrs_diff =round(($diff/1000/60/60)*100)/100;
$hrs_diff=round($diff/60/60,2);

if($hrs_diff>=6)
{
 $lunch_diff=$lunch_end2-$lunch_start2;
 $lunch_diff=round($lunch_diff/60/60,2);
 if($lunch_diff>0) $lunch_hrs=$lunch_diff;
 else $lunch_hrs=0;
 if($hrs_diff > $lunch_hrs)
 $hrs_diff -= $lunch_hrs;
 else $hrs_diff = 0;
}
$hours_worked=$hrs_diff;
if($hrs_diff>=8)
{
$reg_hours=8;
$ot_hours=round(($hrs_diff-8),2);
}
else $reg_hours=$hrs_diff;

if($ot_hours>4)
{
$dt_hours=round(($ot_hours-4),2);
$ot_hours=4;
}
else $dt_hours=0;

$odo_daily=0;
$odo_reim=0;
if($odo_end>$odo_start)
{
   $diff=$odo_end-$odo_start;
   $odo_daily=$diff;
   if($diff>30) $odo_reim=$diff-30;
   else $odo_reim=0;
}
else
{
$odo_daily=0;   
$odo_reim=0;
}
$sum = intval($odo_food) +  ($odo_lodging) +  intval($odo_toll_fees) +  intval($odo_park_fees) + intval($odo_misc);
$odo_mis_exp=(($sum*100)/100);
//echo 'hour-'.$hours_worked.'reg-hr:'.$reg_hours.'-lunch-hr'.$lunch_hrs.'-ot'.$ot_hours.'-dt-'.$dt_hours;
if(!isset($from_timesheet_flg)&&trim($store_name) == '')
    $error = 'Chain';
else if(!isset($from_timesheet_flg)&&trim($other) == '' && trim($store_name) == 0) 
    $error = 'Other';
	if(isset($checkbox) && trim($checkbox) == '')
    $error = 'Test';
if(!isset($from_timesheet_flg)&&isset($store_num) &&trim($store_name) != 0&& trim($store_num) == '')
    if($error != '')
        $error .= ', Store #';
    else
        $error = 'Store #';
if(!isset($from_timesheet_flg)&&isset($client) && (trim($client) == '' || trim($client) == '--------Select--------'))
    if($error != '')
        $error .= ', Client Name';
    else
        $error = 'Client Name';

if($error != '')
{
    $return_arr['error'] ="$error field(s) empty!";	
    echo json_encode($return_arr);
    return;
}      

if($start_date >= $end_date)
{
    $return_arr['error'] ="Please fill in correctly start time and end time!";	
    echo json_encode($return_arr);
    return;
}



if(isset($break1)&& $break1== 2)
{
    $return_arr['error'] ="Please Tick 1st Break!";	
    echo json_encode($return_arr);
    return;
}


if(isset($lunch_chk)&&$lunch_chk == 2)
{
   $return_arr['error'] ="Please Tick Lunch!";	
    echo json_encode($return_arr);
    return;
 
}

if(!isset($lunch_waive)){
  if(isset($lunch_chk )&&($lunch_chk == 1)&&(!isset($lunch_start)|| $lunch_start==''||!isset($lunch_end)|| $lunch_end==''))
{
    $return_arr['error'] ="Please Enter Lunch time!";	
    echo json_encode($return_arr);
    return;
}
//echo 'time---->'. strtotime($lunch_start).strtotime($lunch_end);
  if(isset($lunch_chk )&&($lunch_chk == 1)&&(strtotime($lunch_start)>=strtotime($lunch_end)))
{
    $return_arr['error'] ="Please fill in correctly  Lunch time!";	
    echo json_encode($return_arr);
    return;
}
  if(isset($lunch_chk )&&($lunch_chk == 1)&&((strtotime($lunch_start)<$start_date)||(strtotime($lunch_start)>$end_date)
   ||(strtotime($lunch_end)<$start_date)||(strtotime($lunch_end)>$end_date)))
{
    $return_arr['error'] ="Please fill in correctly  Lunch time11!";	
    echo json_encode($return_arr);
    return;
}
}
else{
    $lunch_chk=0;
}

if(isset($break2)&&$break2==2)
{
    $return_arr['error'] ="Please Tick 2nd Break!";	
    echo json_encode($return_arr);
    return;
}
else if($hours_worked<8)$break2=0;



if(isset($pid)&&$pid>0)
{
$sql='select * from prj_merchants_new where pid='.$pid.' and due_date>='.strtotime(date('Y/m/d',$start_date)).' and due_date<='.strtotime(date('Y/m/d',$end_date)).' and merch='.$_SESSION['employeeID'];
//echo $sql;
$result=pg_query($connection,$sql);
if(pg_num_rows($result)>0)
{    
}
 else
{
$return_arr['error'] ="You are not assigned any job at the selected time!";	
    echo json_encode($return_arr);
    return;    
}
}


$dt = strtotime(date('Y/m/d', $start_date));
$end = strtotime(date('Y/m/d',$start_date).' 23:59:59');

//$sql = 'SELECT time_id FROM dtbl_timesheet WHERE start_time >= '.$dt.' and start_time <= '.$end. ' and emp_id = '.$_SESSION['employeeID'];
$time_id=0;
if(isset($_POST['time_id'])&&$_POST['time_id']>0){
$sql = 'SELECT time_id FROM dtbl_timesheet WHERE time_id='.$_POST['time_id'];
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	$return_arr['error'] = ("DB ERROR: " . pg_last_error($connection));
	 echo json_encode($return_arr);
        return;
}
while($row = pg_fetch_array($result))
{
	$time_id=$row['time_id'];
}
pg_free_result($result);
}
if($time_id <= 0)
{
	
	$sql = 'INSERT INTO dtbl_timesheet(start_time, end_time, emp_id, store_num, store, other,pid,m_id, company_rep, hours_worked, reg_hours, ot_hours,dt_hours, break1, break2, ';
	if(isset($lunch_chk) && $lunch_chk > 0)
		$sql .= 'lunch_time,';
	$sql .= ' lunch_start, lunch_end, client, lunch_hrs, status) VALUES (';
	$sql.= "'$start_date', '$end_date', '".((isset($employeeid)&&$employeeid>0)?$employeeid:$_SESSION['employeeID'])."'";
	if(trim($store_num) != '')
		$sql .= ", '$store_num'";
	else
		$sql .= ", null";
	if(trim($store_name) != '')
		$sql .= ", '$store_name'";
	else
		$sql .= ", null";
        		
	if(trim($other) != '')
		$sql .= ", '$other'";
	else
		$sql .= ", null";
        if(trim($pid) != '')
		$sql .= ", '$pid'";
	else
		$sql .= ", null";
          if(trim($m_id) != '')
		$sql .= ", '$m_id'";
	else
		$sql .= ", null";
	if(trim($_SESSION['com_rep']) != '')
		$sql .= ", '".trim($_SESSION['com_rep'])."'";
	else
		$sql .= ", null";
	
	$sql .= ", '$hours_worked', '$reg_hours', '$ot_hours','$dt_hours', '$break1', '$break2'";
	if(isset($lunch_chk) && $lunch_chk > 0)
		$sql .= ", '$lunch_chk'";
	
	if(trim($lunch_start) != '')
		$sql .= ", '$lunch_start'";
	else
		$sql .= ", null";
	if(trim($lunch_end) != '')
		$sql .= ", '$lunch_end'";
	else
		$sql .= ", null";
	if(trim($client) != '')
		$sql .= ", '$client'";
	else
		$sql .= ", null";
	$sql .= ", '$lunch_hrs', 0);SELECT currval('dtbl_timesheet_time_id_seq') as id;";
	//echo $sql;	
	if(!($result=pg_query($connection,$sql))){
		$return_arr['error'] ="Error while storing timesheet info to database!";	
		echo json_encode($return_arr);
		return;
	}
	while($row = pg_fetch_array($result))
	{
		$time_id=$row['id'];
	}
	pg_free_result($result);
	$return_arr['msg'] = "Successfully submitted your time entry!";
	
	$sql = "INSERT INTO dtbl_odometer(time_id, start_read, end_read, daily_total, adj_miles," 
            ." reimburse_mile, gas_rec, misc_exp, misc_notes, food, lodging, toll_fees, park_fees, other_exp) VALUES ($time_id";
	if(trim($odo_start) != '' && $odo_start > 0)
		$sql .= ", '$odo_start'";
	else
		$sql .= ", 0";	
	if(trim($odo_end) != '' && $odo_end > 0)
		$sql .= ", '$odo_end'";
	else
		$sql .= ", 0";
	if(trim($odo_daily) != '' && $odo_daily > 0)
		$sql .= ", '$odo_daily'";
	else
		$sql .= ", 0";
	$sql .= ", $adj_miles";	
	if(trim($odo_reim) != '' && $odo_reim > 0)
		$sql .= ", '$odo_reim'";
	else
		$sql .= ", 0";
	if(trim($odo_gas) != '' && $odo_gas > 0)
		$sql .= ", '$odo_gas'";
	else
		$sql .= ", 0";
	if(trim($odo_mis_exp) != '' && $odo_mis_exp > 0)
		$sql .= ", '$odo_mis_exp'";
	else
		$sql .= ", 0";
	if(trim($misc_notes) != '')
		$sql .= ", '$misc_notes'";
	else
		$sql .= ", null";
	if(trim($odo_food) != '' && $odo_food > 0)
		$sql .= ", '$odo_food'";
	else
		$sql .= ", 0";
	if(trim($odo_lodging) != '' && $odo_lodging > 0)
		$sql .= ", '$odo_lodging'";
	else
		$sql .= ", 0";
	if(trim($odo_toll_fees) != '' && $odo_toll_fees > 0)
		$sql .= ", '$odo_toll_fees'";
	else
		$sql .= ", 0";
	if(trim($odo_park_fees) != '' && $odo_park_fees > 0)
		$sql .= ", '$odo_park_fees'";
	else
		$sql .= ", 0";
	if(trim($odo_misc) != '')
		$sql .= ", '$odo_misc'";
	else
		$sql .= ", null";
	$sql .= ");";
        
   //echo $sql;     
	if(!($result=pg_query($connection,$sql))){
		$return_arr['error'] ="Error while storing ODOMETER info to database!";	
		echo json_encode($return_arr);
		return;
	}
	pg_free_result($result);
}
else
{
    
   $sql='select * from dtbl_odometer where time_id='.$time_id;
   $result=pg_query($connection,$sql);
   if(pg_num_rows($result)>0){}
   else{
    $sql='insert into dtbl_odometer (time_id) values('.$time_id.')';
pg_query($connection,$sql);     
   }
    
	$sql = "UPDATE dtbl_timesheet SET start_time='$start_date', end_time='$end_date', emp_id=".((isset($employeeid)&&$employeeid>0)?$employeeid:$_SESSION['employeeID']).", hours_worked='$hours_worked', reg_hours='$reg_hours', ot_hours='$ot_hours', break1='$break1', break2='$break2', lunch_hrs='$lunch_hrs',dt_hours='$dt_hours', status=0 ";
	if(isset($lunch_chk) && $lunch_chk > 0)
		$sql .= ", lunch_time='$lunch_chk'";
	else
		$sql .= ", lunch_time='0'";
	if(trim($lunch_start) != '')
		$sql .= ", lunch_start ='$lunch_start'";
	else
		$sql .= ", lunch_start =null";
	if(trim($lunch_end) != '')
		$sql .= ", lunch_end='$lunch_end'";
	else
		$sql .= ", lunch_end=null";
   if(trim($store_num) != '')
		$sql .= ", store_num ='$store_num'";
	else
		$sql .= ", store_num =null";
	if(trim($store_name) != '')
		$sql .= ", store='$store_name'";
	else
		$sql .= ", store=null";
	if(trim($other) != '')
		$sql .= ", other='$other'";
	else
		$sql .= ", other=null";
        if(trim($pid) != '')
		$sql .= ", pid='$pid'";
	else
		$sql .= ", pid=null";
         if(trim($m_id) != '')
		$sql .= ", m_id='$m_id'";
	else
		$sql .= ", m_id=null";
	if(trim($_SESSION['com_rep']) != '')
		$sql .= ", company_rep='".trim($_SESSION['com_rep'])."'";
	else
		$sql .= ", company_rep=null";
	if(trim($client) != '')
		$sql .= ", client='$client'";
	else
		$sql .= ", client=null";
 	$sql .= " WHERE time_id=$time_id; ";
	
	$sql .= "UPDATE dtbl_odometer SET time_id=$time_id";
   
   if(trim($odo_start) != '' && $odo_start > 0)
		$sql .= ", start_read='$odo_start'";
	else
		$sql .= ", start_read=0";	
	if(trim($odo_end) != '' && $odo_end > 0)
		$sql .= ", end_read='$odo_end'";
	else
		$sql .= ", end_read=0";
	if(trim($odo_daily) != '' && $odo_daily > 0)
		$sql .= ", daily_total='$odo_daily'";
	else
		$sql .= ", daily_total=0";
	if(trim($odo_reim) != '' && $odo_reim > 0)
		$sql .= ", reimburse_mile='$odo_reim'";
	else
		$sql .= ", reimburse_mile=0";
	if(trim($odo_gas) != '' && $odo_gas > 0)
		$sql .= ", gas_rec='$odo_gas'";
	else
		$sql .= ", gas_rec=0";
	if(trim($odo_mis_exp) != '' && $odo_mis_exp > 0)
		$sql .= ", misc_exp='$odo_mis_exp'";
	else
		$sql .= ", misc_exp=0";
	if(trim($misc_notes) != '')
		$sql .= ", misc_notes='$misc_notes'";
	else
		$sql .= ", misc_notes=null";
	if(trim($odo_food) != '' && $odo_food > 0)
		$sql .= ", food='$odo_food'";
	else
		$sql .= ", food=0";
	if(trim($odo_lodging) != '' && $odo_lodging > 0)
		$sql .= ", lodging='$odo_lodging'";
	else
		$sql .= ", lodging=0";
	if(trim($odo_toll_fees) != '' && $odo_toll_fees > 0)
		$sql .= ", toll_fees='$odo_toll_fees'";
	else
		$sql .= ", toll_fees=0";
	if(trim($odo_park_fees) != '' && $odo_park_fees > 0)
		$sql .= ", park_fees='$odo_park_fees'";
	else
		$sql .= ", park_fees=0";
	if(trim($odo_misc) != '')
		$sql .= ", other_exp='$odo_misc'";
	else
		$sql .= ", other_exp=0";
 	$sql .= " WHERE time_id=$time_id;";
// echo $sql;	
	if(!($result=pg_query($connection,$sql))){
		$return_arr['error'] ="Error while Updating timesheet info to database!";	
		echo json_encode($return_arr);
		return;
	}
	pg_free_result($result);
	$return_arr['msg'] = "Successfully Updated your time entry!";
}
echo json_encode($return_arr);
return;
?>