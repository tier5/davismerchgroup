<?php
require("Application.php");

?>
<style>

input[type=radio] {
	height: 200px;
   	width: 200px;
   	display: inline-block;
   	cursor: pointer;
   	vertical-align: middle;
   	background: #FFF;
   	border: 1px solid #d2d2d2;
   	border-radius: 100%;
}

input[type=submit] {
        width: 250px;
        height: 150px;
        font-size:50px;
        font-weight:bold;
}

</style>
<?php

$code = $_POST['code'];
$prj_name = $_POST['prj_name'];

// 24 hours = 86400
$now = time();
$workday_set_month = date("n", $now);
$workday_set_day = date("j", $now);
$workday_set_year = date("Y", $now);
$workday_set = mktime(0, 0, 0, $workday_set_month, $workday_set_day, $workday_set_year);
$over_24hours = $now - 86400;


$query1 = ("SELECT \"employeeID\", firstname, lastname ".
	"FROM \"employeeDB\" ".
	"WHERE \"active\" = 'yes' AND clockinid = '$code' ");
if(!($result1 = pg_query($connection,$query1))){
	print("Failed query1: $query1 " . pg_last_error($connection));
	exit;
}
while($row1 = pg_fetch_array($result1)){
	$data1[] = $row1;
}

require("./timeclock_header.php");

//echo "code = $code <br>";
//echo "prj_name = $prj_name <br>";
//print_r($_POST);

if(count($data1) != '1'){
	echo "<meta http-equiv=\"refresh\" content=\"2;timeclock1.php?prj_name=$prj_name\">";
	echo "<font face=\"arial\">";
	echo "<center>";
	echo "You have entered an incorrect clockin ID. This screen will refresh in 2 seconds";
	exit;
}else{

	$query2 = ("SELECT id, emp_id, prj_name, status, workday, break1, lunch, break2, eod ".
		"FROM timeclock_new ".
		"WHERE emp_id = '".$data1[0]['employeeID']."' AND prj_name = '$prj_name' ");
	if(!($result2 = pg_query($connection,$query2))){
		print("Failed query2: $query2 " . pg_last_error($connection));
		exit;
	}
	while($row2 = pg_fetch_array($result2)){
		$data2[] = $row2;
	}

	echo "<br>";

	echo "<font size=\"50px\">";
	echo "<center>";

//	echo "now = ".date("m/d/Y H:i:s", $now)." <br>";
//	echo "over_24hours = ".date("m/d/Y H:i:s", $over_24hours)." <br>";

	if(count($data2) < '1'){
		$query3 = ("INSERT INTO timeclock_new ".
			"(emp_id, prj_name, status, workday, total, clockin, break1, lunch, break2, eod, mileage) ".
			"VALUES ".
			"('".$data1[0]['employeeID']."', '$prj_name', '1', '$workday_set', '$now', '0', '0', '0', '0', '0') ");
		if(!($result3 = pg_query($connection,$query3))){
			print("Failed query3: $query3 <br> " . pg_last_error($connection));
			exit;
		}

		echo "Clocked in ";
	}else{
		
		$clocked_in = 0;
		$break1 = 0;
		$lunch = 0;
		$break2 = 0;
		$eod = 0;
		$total = 0;

		for($i=0, $z=count($data2); $i < $z; $i++){

			if($data2[$i]['status'] == 1){
				$clocked_in = 1;
				$data2[$i]['total'] = bcsub($now, $data2[$i]['clockin']);
			}
			$total = bcadd($total, $data2[$i]['total']);

			if($data2[$i]['break1'] == 1){
				$break1 = 1;
			}
			if($data2[$i]['lunch'] == 1){
				$lunch = 1;
			}
			if($data2[$i]['break2'] == 1){
				$break2 = 1;
			}
			if($data2[$i]['eod'] == 1){
				$eod = 1;
			}
		}

		if($clocked_in == 0){
			$query3 = ("INSERT INTO timeclock_new ".
				"(emp_id, prj_name, status, workday, clockin, break1, lunch, break2, eod, mileage) ".
				"VALUES ".
				"('".$data1[0]['employeeID']."', '$prj_name', '1', '$workday_set', '$now', '0', '0', '0', '0', '0') ");
			if(!($result3 = pg_query($connection,$query3))){
				print("Failed query3: $query3 <br> " . pg_last_error($connection));
				exit;
			}

			echo "Clocked in ";
		}elseif($eod == 1){
			echo "You have clocked in earlier and then selected you were done for the day";
		}else{
	
			echo "Already Clocked In";

			echo "<form action=\"timeclock2.php\" method=\"POST\">";

			echo "<table align=\"center\">";

			if($break1 == 0){
				echo "<tr>";
				echo "<td><font size=\"50px\"><b>Take Break 1:</b></td>";
				echo "<td><input type=\"radio\" name=\"clockout_type\" value=\"1\"></td>";
				echo "</tr>";
			}

			if($lunch == 0 AND $total >= 18000){ // 5 hours
				echo "<tr>";
				echo "<td><font size=\"50px\"><b>Take Lunch:</b></td>";
				echo "<td><input type=\"radio\" name=\"clockout_type\" value=\"2\"></td>";
				echo "</tr>";
			}

			if($break2 == 0 AND $total >= 21600){ // 6 hours
				echo "<tr>";
				echo "<td><font size=\"50px\"><b>Take Break 2:</b></td>";
				echo "<td><input type=\"radio\" name=\"clockout_type\" value=\"3\"></td>";
				echo "</tr>";
			}
	
			echo "<tr>";
			echo "<td><font size=\"50px\"><b>End of Day:</b></td>";
			echo "<td><input type=\"radio\" name=\"clockout_type\" value=\"4\"></td>";
			echo "</tr>";

			echo "<tr>";
			echo "<td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Submit\"></td>";
			echo "</tr>";
	
			echo "</table>";

			echo "</form>";

		}

	}

}

require("./timeclock_trailer.php");
?>
