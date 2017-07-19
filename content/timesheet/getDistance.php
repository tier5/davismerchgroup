<?php
require('Application.php');
extract($_POST);

$sql='select distance from distance where store_id='.$st_id.' and member_id=';

if(isset($emp_id)&&$emp_id>0)
{
  $sql.=$emp_id;   
}
else{
  $sql.=$_SESSION['employeeID'];  
}

if(!($result=pg_query($connection,$sql))){
		print("Failed query1: " . pg_last_error($connection));
		exit;
	}
 $row= pg_fetch_array($result);     
 $res=array('distance'=>'','stat'=>'no');
 if(isset($row['distance'])&&$row['distance']>0)
 {
 $res['distance']=$row['distance'];   
 $res['stat']='yes';
 }
  header('content-type:application/json');
  echo json_encode($res);  
?>