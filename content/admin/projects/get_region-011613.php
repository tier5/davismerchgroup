<?php
require('Application.php');

extract($_POST);
$ret=array();
	$ret['reg']='';
	$sql="select * from \"employeeDB\"  where \"employeeID\" =".$merch_1;
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("Failed query: " . pg_last_error($connection));
	exit;
}

	$row=pg_fetch_array($result);
	
	$ret['reg']=$row['region'];
	
	
	pg_free_result($result);


header('Content-type: application/json'); 
echo json_encode($ret);
?>