<?php
require('Application.php');
require('../header.php');

$accounting=$_POST['accounting'];
$admin=$_POST['admin'];
$humanresources=$_POST['humanresources'];
$directory=$_POST['directory'];
$calendar=$_POST['calendar'];
$operations=$_POST['operations'];
$sales=$_POST['sales'];
$support=$_POST['support'];
$production=$_POST['production'];
$purchasing=$_POST['purchasing'];
$external=$_POST['external'];
$login=$_POST['login'];
$pid=$_POST['ID'];
$eid=$_POST['employeeID'];
$timesheet=$_POST['timesheet'];
$manager=$_POST['manager'];

if($debug == "on"){
	echo "accounting IS $accounting<br>";
	echo "admin IS $admin<br>";
	echo "humanresources IS $humanresources<br>";
	echo "directory IS $directory<br>";
	echo "calendar IS $calendar<br>";
	echo "operations IS $operations<br>";
	echo "sales IS $sales<br>";
	echo "support IS $support<br>";
	echo "production IS $production<br>";
	echo "purchasing IS $purchasing<br>";
	echo "external IS $external<br>";
	echo "login IS $login<br>";
	echo "pid IS $pid<br>";
	echo "eid IS $eid<br>";
}

if($accounting == "on"){
}else{
	$accounting="off";
	
}

if($timesheet == "on"){
}else{
	$timesheet="off";
}


if($admin == "on"){
}else{
	$admin="off";
}

if($humanresources == "on"){
}else{
	$humanresources="off";
}

if($directory == "on"){
}else{
	$directory="off";
}

if($calendar == "on"){
}else{
	$calendar="off";
}

if($operations == "on"){
}else{
	$operations="off";
}

if($sales == "on"){
}else{
	$sales="off";
}

if($support == "on"){
}else{
	$support="off";
}

if($production == "on"){
}else{
	$production="off";
}

if($purchasing == "on"){
}else{
	$purchasing="off";
}

if($external == "on"){
}else{
	$external="off";
}

if($manager == "on"){
}else{
	$manager="off";
}

if($login == "on"){
}else{
	$login="off";
}

$query1=("SELECT \"employeeID\", \"firstname\", \"lastname\" ".
		 "FROM \"employeeDB\" ".
		 "WHERE \"employeeID\" = '$eid' ");
if(!($result1=pg_query($connection,$query1))){
	print("Failed query1: " . pg_last_error($connection));
	exit;
}
while($row1= pg_fetch_array($result1)){
	$data1[]=$row1;
}


if(!isset($pid) OR $pid == ""){

	$query2 = ("INSERT INTO \"permissions\" ".
	"(\"accounting\", \"admin\", \"humanresources\", \"directory\", \"calendar\", \"operations\", \"sales\", \"support\", \"production\", \"purchasing\", \"external\", \"login\", \"employee\", \"manager\", \"timesheet\") ".
	"VALUES ('$accounting', '$admin', '$humanresources', '$directory', '$calendar', '$operations', '$sales', '$support', '$production', '$purchasing', '$external', '$login', '$eid', '$manager', '$timesheet')");

}else{

	$query2=("UPDATE \"permissions\" ".
			 "SET ".
			 "\"accounting\" = '$accounting', ".
			 "\"admin\" = '$admin', ".
			 "\"humanresources\" = '$humanresources', ".
			 "\"directory\" = '$directory', ".
			 "\"calendar\" = '$calendar', ".
			 "\"operations\" = '$operations', ".
			 "\"sales\" = '$sales', ".
			 "\"support\" = '$support', ".
			 "\"production\" = '$production', ".
			 "\"purchasing\" = '$purchasing', ".
			 "\"external\" = '$external', ".
			 "\"login\" = '$login', ".
			 "\"employee\" = '$eid' ,".
	       	          "\"manager\" = '$manager' ,".
			 "\"timesheet\" = '$timesheet' ".
			 "WHERE \"ID\" = '$pid' ");

}

if(!($result2=pg_query($connection,$query2)))
{
	print("Failed query2: $query2 <br> " . pg_last_error($connection));
	exit;
}
echo "Thanks For Updating ".$data1[0]['firstname']." ".$data1[0]['lastname']."'s Account";
require('../trailer.php');
?>
