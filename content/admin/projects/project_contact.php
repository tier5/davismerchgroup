<?php
require('Application.php');

extract($_POST);
$ret=array();
	$ret['add']='';
	$ret['phone']='';

$sql="select * from tbl_chainmanagement  where chain_id =".$store_num;
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("Failed query: " . pg_last_error($connection));
	exit;
}

	$row=pg_fetch_array($result);
	
	$ret['add']=$row['address'];
	$ret['phone']=$row['phone'];
	$ret['city']=$row['city'];
	$ret['zip']=$row['zip'];
	
	pg_free_result($result);


header('Content-type: application/json'); 
echo json_encode($ret);
?>