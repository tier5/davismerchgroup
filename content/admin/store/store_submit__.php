<?php
require('Application.php');
require($JSONLIB.'jsonwrapper.php');
extract($_POST);
$ret_arr = array();
$ret_arr['sid'] = $sid;
$ret_arr['msg']='';
$ret_arr['error']='';

if(isset($sid) && $sid !="")
{
$sql = 'SELECT count(*) as count from tbl_store where sid=' .$sid;
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
		$sql = 'UPDATE "tbl_store" SET "sto_name"=\''.$st_name.'\',"sto_num"=\''.$store.'\',"contact"=\''.$contact.'\',"address"=\''.$address.'\',"address2"=\''.$address2.'\',"city"=\''.$city.'\',"state"=\''.$state.'\',"zip"=\''.$zip.'\', "phone"=\''.$phone.'\', "fax"=\''.$fax.'\' WHERE "sid"=\''.$sid.'\'';
		}
		
		else
		{
			if (!isset($st_name) || $st_name == '')
    $message .= 'Store name is Required! ';

if ($message != '')
{
    $ret['error'] = $message;
    echo json_encode($ret);
    return;
}
			if ($sid == 0){
			 $sql2    = "select count(sto_name)as count from tbl_store where sto_name='$st_name' ";
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
        $ret['error'] = 'Store name already exist.!';
        echo json_encode($ret);
        return;
    }
			}
$sql="INSERT INTO tbl_store (";
		
		if(isset($st_name) && $st_name!="")
		$sql.='"sto_name"';
		if(isset($store) && $store!="")
		$sql.=', "sto_num"';
		if(isset($contact) && $contact!="")
		$sql.=', "contact"';
		if(isset($address) && $address!="")
		$sql.=', "address"';
		if(isset($address2) && $address2!="")
		$sql.=', "address2"';
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
		$sql.="'".$st_name."'";
		if(isset($store) && $store!="")
		$sql.=" ,'".$store."'";
		if(isset($contact) && $contact!="")
		$sql.=" ,'".$contact."'";
		if(isset($address) && $address!="")
		$sql.=" ,'".$address."'";
		if(isset($address2) && $address2!="")
		$sql.=" ,'".$address2."'";
		if(isset($city) && $city!="")
		$sql.=" ,'".$city."'";
		if(isset($state) && $state!="")
		$sql.=" ,'".$state."'";
		if(isset($zip) && $zip!="")
		$sql.=" ,'".$zip."'";
		if(isset($phone) && $phone!="")
		$sql.=" ,'".$phone."'";
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