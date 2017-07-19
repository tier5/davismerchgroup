<?php
require('Application.php');
require($JSONLIB.'jsonwrapper.php');
extract($_POST);
$ret_arr = array();
$ret_arr['ch_id'] = $ch_id;
$ret_arr['msg']='';
$ret_arr['error']='';

if(isset($ch_id) && $ch_id !="")
{
$sql = 'SELECT count(*) as count from tbl_chain where ch_id=' .$ch_id;
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
		$sql = 'UPDATE "tbl_chain" SET "chain"=\''.$chain_name.'\',"status"=\''.$status.'\' WHERE "ch_id"=\''.$ch_id.'\'';
		}
		
		else
		{
			if (!isset($chain_name) || $chain_name == '')
    $message .= 'Chain is Required! ';

if ($message != '')
{
    $ret['error'] = $message;
    echo json_encode($ret);
    return;
}
			if ($ch_id == 0){
			 $sql2    = "select count(chain)as count from tbl_chain where chain='$chain_name' ";
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
        $ret['error'] = 'Chain already exist.!';
        echo json_encode($ret);
        return;
    }
			}
$sql="INSERT INTO tbl_chain (";
		
		if(isset($chain_name) && $chain_name!="")
		$sql.='"chain"';
		if(isset($status) && $status!="")
		$sql.=', "status"';
	
		$sql.=")";

		$sql.=" VALUES (";
		if(isset($chain_name) && $chain_name!="")
		$sql.="'".trim($chain_name)."'";
		if(isset($status) && $status!="")
		$sql.=" ,'".$status."'";
		
		
		
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
			$ret_arr['msg'] = 'Chain information submitted successfully';
		}

                if(!isset($ch_id)||$ch_id=="")
                {
  $sql="select max(ch_id) as ch_id from tbl_chain"; 
  $result=pg_query($connection,$sql);
   $row = pg_fetch_array($result);
  pg_free_result($result);
  $ret_arr['ch_id']= $row['ch_id'];
  
                }
header('Content-type: application/json');
echo json_encode($ret_arr);
		?>