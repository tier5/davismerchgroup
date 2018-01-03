<?php
require("../../Application.php");
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$displayid=$_POST["displayID"];
		$currtime=time();

		$query0=("SELECT \"employeeID\", \"firstname\", \"lastname\", \"clockinid\" ".
				 "FROM \"employeeDB\" ".
				 "WHERE \"clockinid\" = '$displayid'");
		if(!($result0=pg_query($connection,$query0))){
			print("Failed query0: " . pg_last_error($connection));
			exit;
		}
		while($row0=pg_fetch_array($result0)){
			$data0[]=$row0;
		}
		$firstname=$data0[0]['firstname'];
		$lastname=$data0[0]['lastname'];
		if($debug == "on"){
			echo "displayid = $displayid<br>";
			echo "firstname = $firstname<br>";
			echo "lastname = $lastname<br>";
			echo "count data0 = ".count($data0)."<br>";
			exit;
		}
		if(count($data0) == 1){
			$query1=("SELECT * ".
					 "FROM \"timeclock\" ".
					 "WHERE \"firstname\" = '$firstname' AND \"lastname\" = '$lastname' AND \"status\" = 'in'");
			if(!($result1=pg_query($connection,$query1))){
				print("Failed query1: " . pg_last_error($connection));
				exit;
			}
			while($row1=pg_fetch_array($result1)){
				$data1[]=$row1;
			}
			if($debug == "on"){
				echo "count data1 = ".count($data1)."<br>";
				exit;
			}
			if(count($data1) != 0){
				$intime=$data1[0]['clockin'];
				$outtime=time();
				$total=($outtime - $intime);
				$total1=bcdiv("$total", "60", "0");
				$query2=("UPDATE \"timeclock\" ".
						 "SET ".
						 "\"out\" = '$outtime', ".
						 "\"status\" = 'out', ".
						 "\"total\" = '$total1' ".
						 "WHERE \"ID\" = '".$data1[0]['ID']."' ");
				if(!($result2=pg_query($connection,$query2))){
					print("Failed query2: " . pg_last_error($connection));
					exit;
				}
				require("./timeclock_header.php");
				echo "<meta http-equiv=\"refresh\" content=\"2;timeclock1.php\">";
				echo "<font face=\"arial\">";
				echo "<center>";
				echo $data0[0]['firstname']." ".$data0[0]['lastname']." Is now clocked out. This screen will refresh in 2 seconds";
				require("./timeclock_trailer.php");
			}else{
				$query3=("INSERT INTO \"timeclock\" ".
						 "(\"firstname\", \"lastname\", \"workday\", \"clockin\", \"status\") ".
						 "VALUES ".
						 "('".$data0[0]['firstname']."', '".$data0[0]['lastname']."', '".mktime(0, 0, 0, date("m"), date("d"), date("Y"))."', '".time()."', 'in')");
				if(!($result3=pg_query($connection,$query3))){
					print("Failed query3: " . pg_last_error($connection));
					exit;
				}
				require("./timeclock_header.php");
				echo "<meta http-equiv=\"refresh\" content=\"2;timeclock1.php\">";
				echo "<font face=\"arial\">";
				echo "<center>";
				echo $data0[0]['firstname']." ".$data0[0]['lastname']." Is now clocked in. This screen will refresh in 2 seconds";
				require("./timeclock_trailer.php");
			}
		}else{
			require("./timeclock_header.php");
			echo "<meta http-equiv=\"refresh\" content=\"2;timeclock1.php\">";
			echo "<font face=\"arial\">";
			echo "<center>";
			echo "You have entered an incorrect clockin ID. This screen will refresh in 2 seconds";
			require("./timeclock_trailer.php");
			exit;
		}
	}
?>
