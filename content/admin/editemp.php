<?php
require('Application.php');
require('../header.php');

if(isset($_POST['emp_type']) && $_POST['emp_type'] < 1){
if(!isset($_POST['firstname']) OR $_POST['firstname'] == ""){
	$error.="First Name is required!<br>";
}
if(!isset($_POST['lastname']) OR $_POST['lastname'] == ""){
	$error.="Last Name is required!<br>";
}
if(!isset($_POST['address']) OR $_POST['address'] == ""){
	$error.="Address is required!<br>";
}
if(!isset($_POST['city']) OR $_POST['city'] == ""){
	$error.="City is required!<br>";
}
if(!isset($_POST['state']) OR $_POST['state'] == ""){
	$error.="State is required!<br>";
}
if(!isset($_POST['zip']) OR $_POST['zip'] == ""){
	$error.="Zip is required!<br>";
}
if(!isset($_POST['cell']) OR $_POST['cell'] == ""){
	$error.="Cell is required!<br>";
}
if(!isset($_POST['email']) OR $_POST['email'] == ""){
	$error.="Email is required!<br>";
}
}
if(isset($error) && $error != ''){
	require('error_editemp.php');
	exit;
}

$emp_type = 0;
if(isset($_POST['emp_type']))
	$emp_type=$_POST['emp_type'];

if($emp_type == 0){
    
$date_arr = explode('/',$_POST['date_hire']);
$datehired= strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);
/*$monthhired=date("m", mktime(1, 1, 1,$date_arr[0], 1, 2005));
$dayhired=date("d", mktime(1, 1, 1, 1,$date_arr[1], 2005));
$yearhired=date("Y", mktime(1, 1, 1, 1, 1, date("Y")-$date_arr[2]));

$datehired=mktime(0, 0, 0, $monthhired, $dayhired, $yearhired);*/
$firstname=  pg_escape_string($_POST['firstname']);
$lastname=pg_escape_string($_POST['lastname']);
$title=pg_escape_string($_POST['title']);
$address=pg_escape_string($_POST['address']);
$phone=$_POST['phone'];
$pager=$_POST['pager'];
$alphapager=$_POST['alphapager'];
$cell=$_POST['cell'];
$email=pg_escape_string($_POST['email']);
$city=pg_escape_string($_POST['city']);
$state=pg_escape_string($_POST['state']);
$zip=$_POST['zip'];
$salary=$_POST['salary'];
$wage=$_POST['wage'];
$region=$_POST['region'];
}

$client_Id=$_POST['clientname'];
$usernamenew=$_POST['usernamenew'];
$passwordnew=$_POST['passwordnew'];
$poppassword=$_POST['poppassword'];
$clockinid = $_POST['clockinid'];

$empid=$_POST['employeeID'];
if(isset($debug) AND $debug == "on"){
	
	echo "lastname IS $lastname<br>";
	echo "empid IS $empid<br>";
	echo "poppassword IS $poppassword<br>";
	echo "wage IS $wage<br>";
	echo "salary IS $salary<br>";
	echo "datehired IS $datehired<br>";
	echo "zip IS $zip<br>";
	echo "state IS $state<br>";
	echo "city IS $city<br>";
	echo "password IS $passwordnew<br>";
	echo "username IS $usernamenew<br>";
	echo "client_Id IS $clientname<br>";
	echo "email IS $email<br>";
	echo "cell IS $cell<br>";
	echo "alphapager IS $alphapager<br>";
	echo "pager IS $pager<br>";
	echo "phone IS $phone<br>";
	echo "address IS $address<br>";
	echo "title IS $title<br>";
	echo "region IS $region<br>";
}
if(isset($_POST['emp_type']) && $_POST['emp_type'] == 1){
	$sql= "SELECT client FROM \"clientDB\" where \"ID\"=$client_Id;";
	if(!($result=pg_query($connection,$sql))){
		print("Failed client query: " . pg_last_error($connection));
		exit;
	}
	$row = pg_fetch_array($result);
	$firstname = $row['client'];
	pg_free_result($result);
}
$prequery1 = ("SELECT * ".
	"FROM \"employeeDB\" ".
	"WHERE \"employeeID\" != '$empid' ");
if(!($resultpre1 = pg_query($connection,$prequery1))){
	print("Failed prequery1: " . pg_last_error($connection));
	exit;
}
while($rowpre1 = pg_fetch_array($resultpre1)){
	$datapre1[] = $rowpre1;
}
for($i=0, $z=count($datapre1); $i < $z; $i++){
	if($datapre1[$i]['username'] == "$usernamenew"){
		$error .= "The username you selected is already in use<br>";
	}
	if($datapre1[$i]['clockinid'] == "$clockinid"){
		$error .= "The clockin ID you selected is already in use by ".$datapre1[$i]['username']." cause i see ".$datapre1[$i]['clockinid']." equal to $clockinid <br>";
	}
}
if(isset($error)){
	echo "error is ($error)";
}else{

//echo 'EMPloyee'. $emp_type ;
$query1="UPDATE \"employeeDB\" SET \"firstname\" = '$firstname', \"lastname\" = '$lastname', \"address\" = '$address', \"cell\" = '$cell', \"email\" = '$email', \"city\" = '$city', \"state\" = '$state', \"zip\" = '$zip', ";
		 if($emp_type != 1){
		 $query1 .="\"title\" = '$title', ".
		 "\"phone\" = '$phone', ".
		 "\"pager\" = '$pager', ".
		 "\"alphapager\" = '$alphapager', ".
		 "\"datehired\" = '$datehired', ".
		 "\"salary\" = '$salary', ".
		 "\"wage\" = '$wage', ".
		 "\"region\" = '$region', ";
		 }
		 else
		 {
			 $query1 .= "\"client_Id\" = '$client_Id', ";
		 }
		 $query1 .= "\"username\" = '$usernamenew', ".
		 "\"password\" = '$passwordnew', ".
		 "\"poppassword\" = '$poppassword', ".
		 "\"clockinid\" = '$clockinid', ".
		 /*"\"emp_type\" = '$emp_type', ".*/
		 
		 "\"active\" = 'yes' ".
		 "WHERE \"employeeID\" = '$empid' "; //echo $query1;
if(!($result1=pg_query($connection,$query1))){
	print("Failed query1: " . pg_last_error($connection));
	exit;
}
$query2=("SELECT * ".
		 "FROM \"permissions\" ".
		 "WHERE \"employee\" = '$empid'");
if(!($result2=pg_query($connection,$query2))){
	print("Failed query2: " . pg_last_error($connection));
	exit;
}
while($row2 = pg_fetch_array($result2)){
	$data2[]=$row2;
}

//print_r($data2);

echo "<form action=\"editemp1.php\" method=\"post\">";
echo "<table width=\"80%\">";
echo "<tr>";
echo "<td colspan=\"5\" bgcolor=\"white\"><b>".$firstname." ".$lastname."'s Permissions</b></td>";
echo "</tr>";
echo "<tr>";
echo "<td><font face=\"arial\" size=\"-1\">Accounting</font></td>";
echo "<td><font face=\"arial\" size=\"-1\">Administration</font></td>";
echo "<td><font face=\"arial\" size=\"-1\">Human Resources</font></td>";
echo "<td><font face=\"arial\" size=\"-1\">Internal Directory</font></td>";
echo "<td><font face=\"arial\" size=\"-1\">Office Calendar</font></td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
if($data2[0]['accounting'] == "on"){
	echo "<input type=\"checkbox\" name=\"accounting\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"accounting\">";
}
echo "</td>";
echo "<td>";
if($data2[0]['admin'] == "on"){
	echo "<input type=\"checkbox\" name=\"admin\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"admin\">";
}
echo "</td>";
echo "<td>";
if($data2[0]['humanresources'] == "on"){
	echo "<input type=\"checkbox\" name=\"humanresources\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"humanresources\">";
}
echo "</td>";
echo "<td>";
if($data2[0]['directory'] == "on"){
	echo "<input type=\"checkbox\" name=\"directory\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"directory\">";
}
echo "</td>";
echo "<td>";
if($data2[0]['calendar'] == "on"){
	echo "<input type=\"checkbox\" name=\"calendar\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"calendar\">";
}
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td><font face=\"arial\" size=\"-1\">Operations</font></td>";
echo "<td><font face=\"arial\" size=\"-1\">Sales</font></td>";
echo "<td><font face=\"arial\" size=\"-1\">Support</font></td>";
echo "<td><font face=\"arial\" size=\"-1\">Production</font></td>";
echo "<td><font face=\"arial\" size=\"-1\">Purchasing</font></td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
if($data2[0]['operations'] == "on"){
	echo "<input type=\"checkbox\" name=\"operations\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"operations\">";
}
echo "</td>";
echo "<td>";
if($data2[0]['sales'] == "on"){
	echo "<input type=\"checkbox\" name=\"sales\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"sales\">";
}
echo "</td>";
echo "<td>";
if($data2[0]['support'] == "on"){
	echo "<input type=\"checkbox\" name=\"support\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"support\">";
}
echo "</td>";
echo "<td>";
if($data2[0]['production'] == "on"){
	echo "<input type=\"checkbox\" name=\"production\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"production\">";
}
echo "</td>";
echo "<td>";
if($data2[0]['purchasing'] == "on"){
	echo "<input type=\"checkbox\" name=\"purchasing\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"purchasing\">";
}
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td><font face=\"arial\" size=\"-1\">External User</font></td>";
echo "<td><font face=\"arial\" size=\"-1\">Edit Employee Timesheet </font></td>";
echo "<td><font face=\"arial\" size=\"-1\">Login </font></td>";
echo "<td><font face=\"arial\" size=\"-1\">Manager</font></td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
if($data2[0]['external'] == "on"){
	echo "<input type=\"checkbox\" name=\"external\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"external\">";
}
echo "</td>";

echo "<td>";
if($data2[0]['timesheet'] == "on"){
	echo "<input type=\"checkbox\" name=\"timesheet\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"timesheet\">";
}
echo "</td>";

echo "<td>";
if($data2[0]['login'] == "on"){
	echo "<input type=\"checkbox\" name=\"login\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"login\">";
}
echo "</td>";

echo "<td>";
if($data2[0]['manager'] == "on"){
	echo "<input type=\"checkbox\" name=\"manager\" checked>";
}else{
	echo "<input type=\"checkbox\" name=\"manager\">";
}
echo "</td>";

echo "</tr>";


echo "<tr>";
echo "<td><input type=\"hidden\" name=\"ID\" value=\"".$data2[0]['ID']."\"><input type=\"hidden\" name=\"employeeID\" value=\"$empid\"></td>";
echo "<td colspan=\"5\" align=\"center\">";
echo "<br><br>";
echo "<input type=\"submit\" value=\"   Enter Employee Permissions   \"></td>";
echo "</tr>";
echo "</table>";
echo "</form>";
}
require('../trailer.php');
?>
