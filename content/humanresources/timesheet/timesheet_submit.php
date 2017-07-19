<?php
require 'Application.php';
$break1 = 0;
$break2 = 0;
$return_arr = array();
$return_arr['error'] = "";
$return_arr['msg'] = "";
$adj_miles = 30;

extract($_POST);

$dt = strtotime(date('Y/m/d', $start_date));
$end = strtotime(date('Y/m/d',$start_date).' 23:59:59');

$sql = 'SELECT time_id FROM dtbl_timesheet WHERE start_time >= '.$dt.' and start_time <= '.$end. ' and emp_id = '.$_SESSION['employeeID'];
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$time_id=$row['time_id'];
}
pg_free_result($result);
if($time_id <= 0)
{
	
	$sql = 'INSERT INTO dtbl_timesheet(start_time, end_time, emp_id, store_num, store, company_rep, hours_worked, reg_hours, ot_hours, break1, break2, lunch_start, lunch_end, client, lunch_hrs, status) VALUES (';
	$sql.= "'$start_date', '$end_date', '".$_SESSION['employeeID']."'";
	if(trim($store_num) != '')
		$sql .= ", '$store_num'";
	else
		$sql .= ", null";
	if(trim($store_name) != '')
		$sql .= ", '$store_name'";
	else
		$sql .= ", null";
	if(trim($_SESSION['com_rep']) != '')
		$sql .= ", '".trim($_SESSION['com_rep'])."'";
	else
		$sql .= ", null";
	
	$sql .= ", '$hours_worked', '$reg_hours', '$ot_hours', '$break1', '$break2'";
	
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
            ." reimburse_mile, gas_rec, misc_exp, food, lodging, toll_fees, park_fees, other_exp) VALUES ($time_id";
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
	if(!($result=pg_query($connection,$sql))){
		$return_arr['error'] ="Error while storing ODOMETER info to database!";	
		echo json_encode($return_arr);
		return;
	}
	pg_free_result($result);
}
else
{
	$sql = "UPDATE dtbl_timesheet SET start_time='$start_date', end_time='$end_date', emp_id={$_SESSION['employeeID']}, hours_worked='$hours_worked', reg_hours='$reg_hours', ot_hours='$ot_hours', break1='$break1', break2='$break2', lunch_hrs='$lunch_hrs', status=0 ";
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