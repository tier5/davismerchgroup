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
$time_before_12_hours = $now - 43200;
$workday_set_month = date("n", $now);
$workday_set_day = date("j", $now);
$workday_set_year = date("Y", $now);
$workday_set = mktime(0, 0, 0, $workday_set_month, $workday_set_day, $workday_set_year);
$next_workday_set = $workday_set + 86400;
$over_24hours = $now - 86400;

$query0 = ("SELECT pid, prj_name ".
	"FROM projects ".
	"WHERE prj_name = '$prj_name' ");
if(!($result0 = pg_query($connection,$query0))){
	print("Failed query0: $query0 " . pg_last_error($connection));
	exit;
}
while($row0 = pg_fetch_array($result0)){
	$data0[] = $row0;
}

$pid = $data0[0]['pid'];

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

$employeeID = $data1[0]['employeeID'];

$query11 = ("SELECT \"employeeID\", firstname, lastname ".
	"FROM \"employeeDB\" ".
	"WHERE  \"employeeID\" = '$employeeID' ");

if(!($result11 = pg_query($connection,$query11))){
	print("Failed query11: $query11 " . pg_last_error($connection));
	exit;
}

while($row11 = pg_fetch_array($result11)){
	$data11[] = $row11;
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

	$query4 = ("SELECT pmn.location as store, ".
		"pmn.store_num as store_num, ".
		"pmn.m_id as m_id, ".
		"c.\"ID\" as c_id, ".
		"c.client as client ".
		"FROM prj_merchants_new pmn ".
		"LEFT JOIN \"clientDB\" c ON c.\"ID\" = pmn.cid ".
		"WHERE pmn.merch = '".$data1[0]['employeeID']."' AND pmn.pid = '$pid' ");
	if(!($result4 = pg_query($connection,$query4))){
		print("Failed query4: $query4 " . pg_last_error($connection));
		exit;
	}
	while($row4 = pg_fetch_array($result4)){
		$data4[] = $row4;
	}

	if(count($data4) != '1'){
		echo "<meta http-equiv=\"refresh\" content=\"2;timeclock1.php?prj_name=$prj_name\">";
		echo "<font face=\"arial\">";
		echo "<center>";
		echo "This person is not assigned to this project. This screen will refresh in 2 seconds";
		exit;
	}else{
	
		$query2 = ("SELECT id, emp_id, prj_name, status, total, workday, clockin, break1, lunch, break2, eod ".
			"FROM timeclock_new ".
			"WHERE emp_id = '".$data1[0]['employeeID']."' AND prj_name = '$prj_name' AND clockin >= '$time_before_12_hours' ");
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
				"(emp_id, prj_name, status, workday, total, clockin, break1, lunch, break2, eod, mileage, store_num, client, pid, m_id, store, cid) ".
				"VALUES ".
				"('".$data1[0]['employeeID']."', '$prj_name', '1', '$workday_set', '0', '$now', '0', '0', '0', '0', '0', '".$data4[0]['store_num']."', '".$data4[0]['client']."', '$pid', '".$data4[0]['m_id']."', '".$data4[0]['store']."', '".$data4[0]['c_id']."') ");
			if(!($result3 = pg_query($connection,$query3))){
				print("Failed query3: $query3 <br> " . pg_last_error($connection));
				exit;
			}
	
			echo "<meta http-equiv=\"refresh\" content=\"2;timeclock1.php?prj_name=$prj_name\">";
			echo "(".$data11[0]['firstname']." ".$data11[0]['lastname'].") Start of day ";
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
					$clocked_in_id = $data2[$i]['id'];
					$clocked_in_time = $data2[$i]['clockin'];
					$data2[$i]['total'] = bcsub($now, $data2[$i]['clockin']);
				}

//				echo "total = $total + ".$data2[$i]['total']." <br>";

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
	
			if($clocked_in == 0 AND $eod == 0){
				$query3 = ("INSERT INTO timeclock_new ".
					"(emp_id, prj_name, status, workday, clockin, break1, lunch, break2, eod, mileage, store_num, client, pid, m_id, store, cid) ".
					"VALUES ".
					"('".$data1[0]['employeeID']."', '$prj_name', '1', '$workday_set', '$now', '0', '0', '0', '0', '0', '".$data4[0]['store_num']."', '".$data4[0]['client']."', '$pid', '".$data4[0]['m_id']."', '".$data4[0]['store']."', '".$data4[0]['c_id']."') ");
				if(!($result3 = pg_query($connection,$query3))){
					print("Failed query3: $query3 <br> " . pg_last_error($connection));
					exit;
				}
	
				echo "<meta http-equiv=\"refresh\" content=\"2;timeclock1.php?prj_name=$prj_name\">";
				echo "(".$data11[0]['firstname']." ".$data11[0]['lastname'].") End of Lunch";
	
			}elseif($eod == 1){
	
				echo "<meta http-equiv=\"refresh\" content=\"4;timeclock1.php?prj_name=$prj_name\">";
				echo "You have clocked in earlier and then selected you were done for the day";
	
			}else{
	
				echo "Already Clocked In";
	
				echo "<form action=\"timeclock3.php\" method=\"POST\">";
				echo "<input type=\"hidden\" name=\"code\" value=\"$code\">";
				echo "<input type=\"hidden\" name=\"prj_name\" value=\"$prj_name\">";
				echo "<input type=\"hidden\" name=\"clocked_in_id\" value=\"$clocked_in_id\">";
				echo "<input type=\"hidden\" name=\"clocked_in_time\" value=\"$clocked_in_time\">";
				echo "<input type=\"hidden\" name=\"store_num\" value=\"".$data4[0]['store_num']."\">";
				echo "<input type=\"hidden\" name=\"client\" value=\"".$data4[0]['client']."\">";
				echo "<input type=\"hidden\" name=\"cid\" value=\"".$data4[0]['c_id']."\">";
				echo "<input type=\"hidden\" name=\"pid\" value=\"$pid\">";
				echo "<input type=\"hidden\" name=\"m_id\" value=\"".$data4[0]['m_id']."\">";
				echo "<input type=\"hidden\" name=\"store\" value=\"".$data4[0]['store']."\">";

				echo "Total = $total <br>";
	
				echo "<table align=\"center\">";
	
				if($break1 == 0 AND $total >= 5400){ // 1 & 1/2 hours
					echo "<tr>";
					echo "<td><font size=\"50px\"><b>Take Break 1:</b></td>";
					echo "<td><input type=\"radio\" name=\"clockout_type\" value=\"1\"></td>";
					echo "</tr>";
				}
	
				//if($lunch == 0 AND $total >= 18000){ // 5 hours
				if($lunch == 0 AND $total >= 14400){ // 4 hours
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

}

require("./timeclock_trailer.php");
?>
