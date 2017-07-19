<?php 
require('Application.php');
extract($_POST);
if($conf==''||$conf==null)
  $conf='FALSE';  
$query="update prj_merchants_new set confirm='".$conf."' where m_id=".$m_id;

 if (!($result = pg_query($connection, $query))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
        
$query="select m.*,cl.client from prj_merchants_new as m left join \"clientDB\" as cl on cl.\"ID\"=m.cid where m_id=".$m_id;
 if (!($result = pg_query($connection, $query))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
    $row=pg_fetch_array($result);
    $res=array('due_date'=>$row['due_date'],'merch'=>$row['merch']);
  //echo date('m/d/Y h:m a',strtotime(date('m/d/Y',$row["due_date"]).' '.$row["st_time"]));  
 // if(($row['merch']==$_SESSION['employeeID'])&&($conf=='TRUE')) 
    if(($conf=='TRUE')) 
  {
 $st_time=strtotime(date('m/d/Y',$row["due_date"]).' '.$row["st_time"]);     
$sql='select * from dtbl_timesheet where m_id='.$row['m_id'].'  and emp_id='.$row['merch']; 
$result = pg_query($connection, $sql);
if(pg_num_rows($result)>0){}
else{
$sql='insert into dtbl_timesheet (status,start_time,emp_id,store_num,store,pid,m_id,client) values(3' ;
if(isset($row['due_date']))
$sql.=','.$st_time; 
else $sql.=',null';

if(isset($row['merch']))
$sql.=','.$row["merch"]; 
else $sql.=',null';

if(isset($row['store_num']))
$sql.=','.$row["store_num"]; 
else $sql.=',null';

if(isset($row['location']))
$sql.=','.$row["location"]; 
else $sql.=',null';

if(isset($row['pid']))
$sql.=','.$row["pid"]; 
else $sql.=',null';

if(isset($row['m_id']))
$sql.=','.$row["m_id"]; 
else $sql.=',null';

if(isset($row['client']))
$sql.=',\''.$row["client"].'\''; 
else $sql.=',null';
 $sql.=")" ;
 //echo $sql;
 pg_query($connection, $sql);
 $sql='select time_id from dtbl_timesheet where m_id='.$row["m_id"].' order by time_id desc limit 1';
  $res=pg_query($connection, $sql);
  $r=  pg_fetch_array($res);
  pg_free_result($res);
$sql='insert into dtbl_odometer (time_id) values('.$r['time_id'].')'; 
pg_query($connection, $sql);
  }
  }
  else
{
$sql='select * from dtbl_timesheet where m_id='.$row["m_id"]; 
$result = pg_query($connection, $sql);
$sql='';
while($r=pg_fetch_array($result))
{
 $sql.='delete from dtbl_odometer where time_id='.$r['time_id'].';';   
}
if($sql!='')
{
     if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
}
      
  $sql='delete from dtbl_timesheet where m_id='.$row["m_id"]; 
 if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
  pg_query($connection, $sql);
  $m_id=$row["m_id"];
  require '../../merch_mail_deny.php';
}
    header('content-type:application/json');
    echo json_encode($res);
    
?>