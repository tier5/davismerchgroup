<?php
require('Application.php');
require($JSONLIB.'jsonwrapper.php');
extract($_POST);
$ret_arr = array();
$ret_arr['rid'] = $rid;
$ret_arr['msg']='';
$ret_arr['error']='';

if(isset($rid) && $rid !="")
{
$sql = 'SELECT count(*) as count from tbl_region where rid=' .$rid;
	if(!($result=pg_query($connection,$sql)))
		{
			$return_arr['error'] = "Basic tab :".pg_last_error($connection);
			echo json_encode($return_arr);
			return;
		}
		$row = pg_fetch_array($result);
		pg_free_result($result);
		
}
			if(isset($row['count']) && $row['count']!="")
		{
		$sql = 'UPDATE "tbl_region" SET "region"=\''.$region.'\' WHERE "rid"=\''.$rid.'\'';
		}
		
		else
		{
			if (!isset($region) || $region == '')
    $message .= 'Region is Required! ';

if ($message != '')
{
    $ret['error'] = $message;
    echo json_encode($ret);
    return;
}
			if ($rid == 0){
			 $sql2    = "select count(region)as count from tbl_region where region='$region' ";
    if (!($result = pg_query($connection, $sql2)))
    {
        $ret['error'] = "Failed check project name: " . pg_last_error($connection);
        echo json_encode($ret);
        return;
    }
    $row = pg_fetch_array($result);
    pg_free_result($result);
    if (isset($row['count']) && $row['count'] > 0)
    {
        $ret['error'] = 'Region already exist.!';
        echo json_encode($ret);
        return;
    }
			}
$sql="INSERT INTO tbl_region (";
		
		if(isset($region) && $region!="")
		$sql.='"region"';
		$sql.=")";

		$sql.=" VALUES (";
		if(isset($region) && $region!="")
		$sql.="'".trim($region)."'";		
		$sql.=" )";
		}
		//echo $sql;
		if ($sql != '')
		{
			if(!($result=pg_query($connection,$sql)))
			{
				$return_arr['error'] = "Basic tab :".pg_last_error($connection);
				echo json_encode($return_arr);
				return;
			}
			pg_free_result($result);
			$ret_arr['msg'] = 'Region information submitted successfully';
		}

                if(!isset($rid)||$rid=="")
                {
  $sql="select max(rid) as rid from tbl_region"; 
  $result=pg_query($connection,$sql);
   $row = pg_fetch_array($result);
  pg_free_result($result);
  $ret_arr['rid']= $row['rid'];
  
                }
header('Content-type: application/json');
echo json_encode($ret_arr);
		?>