<?php
require("Application.php");

$code = $_POST['code'];
$prj_name = $_POST['prj_name'];
$clocked_in_id = $_POST['clocked_in_id'];
$clocked_in_time = $_POST['clocked_in_time'];
$store_num = $_POST['store_num'];
$client = $_POST['client'];
$cid = $_POST['cid'];
$pid = $_POST['pid'];
$m_id = $_POST['m_id'];
$store = $_POST['store'];

$now = time();
$workday_set_month = date("n", $now);
$workday_set_day = date("j", $now);
$workday_set_year = date("Y", $now);
$workday_set = mktime(0, 0, 0, $workday_set_month, $workday_set_day, $workday_set_year);
$over_24hours = $now - 86400;

$clockout_type = $_POST['clockout_type']; // 1 = break1, 2 = lunch, 3 = break2, 4 = EOD

$query1 = ("SELECT \"employeeID\", firstname, lastname, mileage ".
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

for($i=0, $z=count($data2); $i < $z; $i++){
	$pre_total = bcadd($pre_total, $data2[$i]['total']);
}

$total = bcsub($now, $clocked_in_time);

$grand_total = bcadd($pre_total, $total);


if($clockout_type == 1){ // break1 clock out for 15 min and back in  ( 900 seconds = 15 min )

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
		"break1 = '1' ".
		"WHERE id = '$clocked_in_id' ");
	if(!($result3 = pg_query($connection,$query3))){
		print("Failed query3 BREAK 1: $query3 <br> " . pg_last_error($connection));
		exit;
	}

	$new_now = bcadd($now, 900);

	$query4 = ("INSERT INTO timeclock_new ".
		"(emp_id, prj_name, status, workday, clockin, break1, lunch, break2, eod, mileage, store_num, client, pid, m_id, store, cid) ".
		"VALUES ".
		"('".$data1[0]['employeeID']."', '$prj_name', '1', '$workday_set', '$new_now', '0', '0', '0', '0', '0', '$store_num', '$client', '$pid', '$m_id', '$store', '$cid') ");
	if(!($result4 = pg_query($connection,$query4))){
		print("Failed query4: $query4 <br> " . pg_last_error($connection));
		exit;
	}

	header("Refresh:0; url=timeclock1.php?prj_name=$prj_name");

}elseif($clockout_type == 2){ // lunch clockout

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
		"lunch = '1' ".
		"WHERE id = '$clocked_in_id' ");
	if(!($result3 = pg_query($connection,$query3))){
		print("Failed query3 LUNCH: $query3 <br> " . pg_last_error($connection));
		exit;
	}

	header("Refresh:0; url=timeclock1.php?prj_name=$prj_name");

}elseif($clockout_type == 3){ // break2 clockout and backin 15 min later

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
		"break2 = '1' ".
		"WHERE id = '$clocked_in_id' ");
	if(!($result3 = pg_query($connection,$query3))){
		print("Failed query3 BREAK 2: $query3 <br> " . pg_last_error($connection));
		exit;
	}

	$new_now = bcadd($now, 900);

	$query4 = ("INSERT INTO timeclock_new ".
		"(emp_id, prj_name, status, workday, clockin, break1, lunch, break2, eod, mileage, store_num, client, pid, m_id, store, cid) ".
		"VALUES ".
		"('".$data1[0]['employeeID']."', '$prj_name', '1', '$workday_set', '$new_now', '0', '0', '0', '0', '0', '$store_num', '$client', '$pid', '$m_id', '$store', '$cid') ");
	if(!($result4 = pg_query($connection,$query4))){
		print("Failed query4: $query4 <br> " . pg_last_error($connection));
		exit;
	}
	
	header("Refresh:0; url=timeclock1.php?prj_name=$prj_name");

}elseif($clockout_type == 4){ // EOD and will put in mileage if eligable

	if($data1[0]['mileage'] == '1'){
	
		require("./timeclock_header.php");
	
		echo "<form action=\"timeclock4.php\" method=\"POST\">";
		echo "<input type=\"hidden\" name=\"code\" value=\"$code\">";
		echo "<input type=\"hidden\" name=\"prj_name\" value=\"$prj_name\">";
		echo "<input type=\"hidden\" name=\"clocked_in_id\" value=\"$clocked_in_id\">";
		echo "<input type=\"hidden\" name=\"clocked_in_time\" value=\"$clocked_in_time\">";
		echo "<input type=\"hidden\" name=\"store_num\" value=\"$store_num\">";
		echo "<input type=\"hidden\" name=\"client\" value=\"$client\">";
		echo "<input type=\"hidden\" name=\"cid\" value=\"$cid\">";
		echo "<input type=\"hidden\" name=\"pid\" value=\"$pid\">";
		echo "<input type=\"hidden\" name=\"m_id\" value=\"$m_id\">";
		echo "<input type=\"hidden\" name=\"store\" value=\"$store\">";

		echo "<table align=\"center\">";
		echo "<tr>";
		echo "<td><b>Mileage:</b></td>";
		echo "<td><input type=\"text\" name=\"mileage\"></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\"Enter\"></td>";
		echo "</tr>";
	
		echo "</table>";
		echo "</form>";
	
		require("./timeclock_trailer.php");

	}else{

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
			"eod = '1' ".
			"WHERE id = '$clocked_in_id' ");
		if(!($result3 = pg_query($connection,$query3))){
			print("Failed query3 EOD: $query3 <br> " . pg_last_error($connection));
			exit;
		}

		header("Refresh:0; url=timeclock1.php?prj_name=$prj_name");

	}
}

?>
