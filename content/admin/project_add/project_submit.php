<?php
require('Application.php');
require($JSONLIB.'jsonwrapper.php');
extract($_POST);
$ret_arr = array();
$ret_arr['pid'] = $pid;
$ret_arr['msg']='';
$ret_arr['error']='';

$sql='';
$col='';
$val='';
$up_qr='';
foreach($proj as $key=>$p)
{    
if($p!='')
{
    if($key=='date_complet')
    {
$date_arr = explode('/',$p);
$p= strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);    
    }  
 if($col!='')
 {
 $col.=",";
 $val.=",";
 $up_qr.=",";
 }
$col.='"'.$key.'"';
$val.="'".pg_escape_string($p)."'";
$up_qr.='"'.$key.'"=\''.pg_escape_string($p).'\'';
}
    
}

if($pid>0)
    $sql='update "project_main" set '.$up_qr.' where m_pid='.$pid;
else
  $sql='insert into "project_main" ('.$col.',"created_by",status) values('.$val.','.$_SESSION['employeeID'].',1)';
                
                
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
			$ret_arr['msg'] = 'Project information submitted successfully';
		}
  if($pid>0){}
  else{
      $sql='select m_pid from "project_main" order by m_pid desc limit 1';
      if(!($result=pg_query($connection,$sql)))
			{
				$return_arr['error'] = "Basic tab :".pg_last_error($connection);
				echo json_encode($return_arr);
				return;
			}
           $row= pg_fetch_array($result);             
	   pg_free_result($result);
           $pid=$row['m_pid'];
  }
  $ret_arr['pid'] = $pid;
$sql='';  
foreach($sign_off as $key=>$sign)
{
if(isset($hdn_sign_id[$key])&&$hdn_sign_id[$key]>0)  
$sql.='update "prj_signoff_clients" set client='.$client[$key].',frm_name=\''.$sign.'\' where sign_id='.$hdn_sign_id[$key].';';    
else    
$sql.='insert into "prj_signoff_clients" (pid,client,frm_name) values('.$pid.','.$client[$key].',\''.$sign.'\');';    
}
//echo $sql;
pg_query($connection,$sql);
header('Content-type: application/json');
echo json_encode($ret_arr);
		?>