<?php
require('Application.php');
require($JSONLIB.'jsonwrapper.php');
extract($_POST);
$ret_arr = array();
$ret_arr['chain_id'] = $chain_id;
$ret_arr['msg']='';
$ret_arr['error']='';

if(isset($chain_id) && $chain_id !="")
{
$sql = 'SELECT count(*) as count from tbl_chainmanagement where chain_id=' .$chain_id;
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
		$sql = 'UPDATE "tbl_chainmanagement" SET "sto_name"=\''.trim($st_name).'\',"sto_num"=\''.trim($store).'\',"address"=\''.trim($address).'\',"city"=\''.trim($city).'\',"state"=\''.trim($state).'\',"zip"=\''.trim($zip).'\', "phone"=\''.trim($phone).'\', "fax"=\''.$fax.'\' WHERE "chain_id"=\''.$chain_id.'\'';
		}
		
		else
		{
			if (!isset($store) || $store == '')
    $message .= 'Store# is Required! ';

if ($message != '')
{
    $ret['error'] = $message;
    echo json_encode($ret);
    return;
}
			if ($chain_id == 0){
			 $sql2    = "select count(sto_num)as count from tbl_chainmanagement where sto_num='$store' and  sto_name=".$st_name;
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
        $ret['error'] = 'Store# already exist in the same chain.!';
        echo json_encode($ret);
        return;
    }
			}
$sql="INSERT INTO tbl_chainmanagement (";
		
		if(isset($st_name) && $st_name!="")
		$sql.='"sto_name"';
		if(isset($store) && $store!="")
		$sql.=', "sto_num"';		
		if(isset($address) && $address!="")
		$sql.=', "address"';		
		if(isset($city) && $city!="")
		$sql.=', "city"';
		if(isset($state) && $state!="")
		$sql.=', "state"';
		if(isset($zip) && $zip!="")
		$sql.=', "zip"';
		if(isset($phone) && $phone!="")
		$sql.=', "phone"';
		if(isset($fax) && $fax!="")
		$sql.=', "fax"';
		
		
		$sql.=")";
		$sql.=" VALUES (";
		if(isset($st_name) && $st_name!="")
		$sql.="'".trim($st_name)."'";
		if(isset($store) && $store!="")
		$sql.=" ,'".trim($store)."'";		
		if(isset($address) && $address!="")
		$sql.=" ,'".trim($address)."'";
		if(isset($city) && $city!="")
		$sql.=" ,'".trim($city)."'";
		if(isset($state) && $state!="")
		$sql.=" ,'".trim($state)."'";
		if(isset($zip) && $zip!="")
		$sql.=" ,'".trim($zip)."'";
		if(isset($phone) && $phone!="")
		$sql.=" ,'".trim($phone)."'";
		if(isset($fax) && $fax!="")
		$sql.=" ,'".$fax."'";
		
		
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
			$ret_arr['msg'] = 'Store information submitted successfully';
		}

header('Content-type: application/json');
echo json_encode($ret_arr);
		?>