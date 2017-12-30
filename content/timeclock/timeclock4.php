<?php
require("Application.php");

$code = $_POST['code'];
$prj_name = $_POST['prj_name'];
$clocked_in_id = $_POST['clocked_in_id'];
$clocked_in_time = $_POST['clocked_in_time'];
$mileage = 0;

$store_num = $_POST['store_num'];
$client = $_POST['client'];
$cid = $_POST['cid'];
$pid = $_POST['pid'];
$m_id = $_POST['m_id'];
$store = $_POST['store'];

if(isset($_POST['mileage'])){
	$mileage = $_POST['mileage'];
}

$now = time();
$workday_set_month = date("n", $now);
$workday_set_day = date("j", $now);
$workday_set_year = date("Y", $now);
$workday_set = mktime(0, 0, 0, $workday_set_month, $workday_set_day, $workday_set_year);
$over_24hours = $now - 86400;

$clockout_type = $_POST['clockout_type']; // 1 = break1, 2 = lunch, 3 = break2, 4 = EOD

$query1 = ("SELECT \"employeeID\", firstname, lastname, mileage, mileage_deduction ".
	"FROM \"employeeDB\" ".
	"WHERE \"active\" = 'yes' AND clockinid = '$code' ");
if(!($result1 = pg_query($connection,$query1))){
	print("Failed query1: $query1 " . pg_last_error($connection));
	exit;
}
while($row1 = pg_fetch_array($result1)){
	$data1[] = $row1;
}

$query2 = ("SELECT id, emp_id, prj_name, status, workday, clockin, break1, lunch, break2, eod ".
	"FROM timeclock_new ".
	"WHERE emp_id = '".$data1[0]['employeeID']."' AND prj_name = '$prj_name' AND workday = '$workday_set' ");
if(!($result2 = pg_query($connection,$query2))){
	print("Failed query2: $query2 " . pg_last_error($connection));
	exit;
}
while($row2 = pg_fetch_array($result2)){
	        $data2[] = $row2;
}

$total = 0;
$pre_total = 0;
$grand_total = 0;
$rt = 0;
$ot = 0;
$dt = 0;
$hours_worked = 0;

$mileage = bcsub($mileage, $data1[0]['mileage_deduction']);

if($mileage >= 0){
}else{
	$mileage = 0;
}

for($i=0, $z=count($data2); $i < $z; $i++){
	$pre_total = bcadd($pre_total, $data2[$i]['total']);
}

$total = bcsub($now, $clocked_in_time);

$grand_total = bcadd($pre_total, $total);

if($pre_total >= 28800){ // 8 hours
	if($pre_total >= 43200){ // 12 hours
		$dt = $total;
	}elseif($grand_total >= 43200){

		$pre_ot = bcsub($pre_total, 28800);
		$ot = bcsub(14400, $pre_ot); // to get OT for this clockout
		$dt = bcsub($grand_total, 43200); // to get DT for this clockout
	}else{
		$ot = $total;
	}
}elseif($grand_total >= 28800){

	if($grand_total >= 43200){ // $total is RT OT and DT

		$rt = bcsub(28800, $pre_total); // this would be regular time
		$ot = 14400; // I know this because grand_total is over 43200 but pre_total was under 28800
		$dt = bcsub($grand_total, 43200);
	}else{ // $total is RT and OT
		$rt = bcsub(28800, $pre_total);
		$ot = bcsub($grand_total, 28800);
	}
}else{
	$rt = $total;
}

if($rt != 0){
	$rt = bcdiv(bcdiv($rt, 60, 2), 60, 2);
}
if($ot != 0){
	$ot = bcdiv(bcdiv($ot, 60, 2), 60, 2);
}
if($dt != 0){
	$dt = bcdiv(bcdiv($dt, 60, 2), 60, 2);
}
if($total != 0){
	$hours_worked = bcdiv(bcdiv($total, 60, 2), 60, 2);
}

$query3 = ("UPDATE timeclock_new ".
	"SET ".
	"clockout = '$now', ".
	"total = '$total', ".
	"status = '0', ".
	"hours_worked = '$hours_worked', ".
	"reg_hours = '$rt', ".
	"ot_hours = '$ot', ".
	"dt_hours = '$dt', ".
	"mileage = '$mileage', ".
	"eod = '1' ".
	"WHERE id = '$clocked_in_id' ");
if(!($result3 = pg_query($connection,$query3))){
	print("Failed query3: $query3 <br> " . pg_last_error($connection));
	exit;
}

header("Refresh:0; url=timeclock1.php?prj_name=$prj_name");

?>
