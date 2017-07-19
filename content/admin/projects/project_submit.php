<?php

require 'Application.php';
$message = '';
$ret     = array();
$ret['error'] = '';
$ret['msg']   = '';


extract($_POST);
$ret['pid'] = $pid;
//$pid=$form_pid;
if($type!='merch')
{
if(!isset($pid)||$pid=="")
    $pid=0;

if (!isset($prj_name) || $prj_name == '')
    $message .= 'Project Name missing! ';


$sql          = '';
if ($pid == 0)
{
 //$sql    = "select prj_name from projects where m_pid='$m_pid' and status=1 order by pid desc limit 1";
 //echo $sql;
//    if (!($result = pg_query($connection, $sql)))
//    {
//        $ret['error'] = "Failed check project name: " . pg_last_error($connection);
//        echo json_encode($ret);
//        return;
//    }
//    $row = pg_fetch_array($result);
//    pg_free_result($result);
  /*  if (isset($row['count']) && $row['count'] > 0)
    {
        $ret['error'] = 'Project name already exist.!';
        echo json_encode($ret);
        return;
    }*/

    $seq=intval($row['prj_name']);
    if($seq>=100){ $seq+=1;}
    else{$seq=100;}
    
    $sql = "insert into projects (cid,status,created_by,m_pid,merch_num_stat,num_merch";
    
    if(isset($img)&&$img!='')
       $sql .=",img_file"; 
    $sql .=") values(";
   
   // $sql .= "'" . pg_escape_string($seq) . "'";
 
    $sql .= "1,1,".$_SESSION['employeeID'].",".$m_pid.",0";
    //echo $sql;
    if(isset($num_merch)&&$num_merch>0)
    $sql .= ",$num_merch";
    else $sql .= ",0";
    
     if(isset($img)&&$img!='')
       $sql .=",".$img;
   $sql .=");";
   
}
else if ($pid > 0)
{
    $sql = "Update projects set status = 1 ";
  
   // $sql .= ", prj_name = '" . pg_escape_string($prj_name) . "'";
     $sql .= ", m_pid = '" . $m_pid . "'";
   if(isset($img)&&$img!='')
        $sql .= ", img_file = '" . pg_escape_string($img) . "'";
   
    if(isset($num_merch)&&$num_merch>0)
    $sql .= ",num_merch = $num_merch";
    else $sql .= ",num_merch = 0";
 $sql.=" where pid=".$pid;   
}
if ($sql != '')
{
    if (!($result = pg_query($connection, $sql)))
    {
        $ret['error'] = "Failed to add / edit project: " . pg_last_error($connection);
        echo json_encode($ret);
        return;
    }
    $sql = '';
    pg_free_result($result);
    
    
    
    
    
}   
    
  

 if ($pid == 0)
    {
        $sql    = "select max(pid) as max_pid from projects ";
        if (!($result = pg_query($connection, $sql)))
        {
            $ret['error'] = "Failed check project name: " . pg_last_error($connection);
            echo json_encode($ret);
            return;
        }
        $row          = pg_fetch_array($result);
        pg_free_result($result);
        $sql = '';
        if (isset($row['max_pid']) && $row['max_pid'] > 0)
        {
            $ret['pid'] = $row['max_pid'];
            $pid=$row['max_pid'];
        }
    }
    
}
//echo 'pid-'.$pid;
    
  if(isset($pid)&&$pid!=""&&isset($merch_1)&&$merch_1!=0)
    {

    
    
    
    if(isset($merch_id_hdn)&&$merch_id_hdn>0)
    {
 
    $sql='select main.name,prj.prj_name from prj_merchants_new as merch left join projects as prj on prj.pid=merch.pid
 left join project_main as main on main.m_pid=prj.m_pid     
 where merch.due_date='.strtotime($due_date).'and  merch.st_time=\''.$st_time.'\' and merch.merch ='.$merch_1.' and merch.m_id!='.$merch_id_hdn.' and merch.pid!='.$pid;       
    
    //echo $sql;
  if (!($result = pg_query($connection, $sql)))
    {
        $ret['error'] = "Failed to add / edit project: " . pg_last_error($connection);
        echo json_encode($ret);
        return;
    }
  $row=pg_fetch_array($result);  
 if(pg_num_rows($result)>0)
 {
$ret['error'] = "This merchandiser is assigned to Project: ".$row['name']." , JobID: ".$row['prj_name']." at the selected date and time..." ;
        echo json_encode($ret);
        return;     
 }
    $sql = '';
    pg_free_result($result);   
    
 $sql ='update  prj_merchants_new set ';
  $sql .=' cid='.$cid;
  $sql .=',merch='.$merch_1;
  if(isset($location)&&$location!="")
  $sql.=',location='.$location;
    if(isset($due_date)&&$due_date!="")
    {
         $date_arr = explode('/',$due_date);
    $due_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);
     $sql.=',due_date='.$due_date;   
    }
     if(isset($notes)&&$notes!="")
    $sql.=",notes='".pg_escape_string($notes)."'";       
  
if(isset($address) && $address!="")
$sql.=",address='".pg_escape_string($address)."'";     
	   
	   if(isset($phone) && $phone!="")
         $sql.=',phone=\''.$phone.'\'';           
	
	   
	   if(isset($city) && $city!="")
        $sql.=",city='".pg_escape_string($city)."'";          
	
	  if(isset($zip) && $zip!="")
                 $sql.=',zip=\''.$zip.'\'';  
	
     
     if(isset($st_time)&&$st_time!="")
       $sql.=",st_time='".$st_time."'";      
  
     
       if(isset($store_num)&&$store_num!="")
     $sql.=',store_num='.$store_num;  
	 
	 if(isset($region1) && $region1!="")
	 $sql.=',region='.$region1;
         else  $sql.=',region=0';
 
  $sql.=' where m_id='.$merch_id_hdn;      
  //echo $sql;
    }                  
 else{
   $sql='select main.name,prj.prj_name from prj_merchants_new as merch left join projects as prj on prj.pid=merch.pid
 left join project_main as main on main.m_pid=prj.m_pid     
 where merch.due_date='.strtotime($due_date).'and  merch.st_time=\''.$st_time.'\' and merch.merch ='.$merch_1.' and merch.pid!='.$pid;     
  if (!($result = pg_query($connection, $sql)))
    {
        $ret['error'] = "Failed to add / edit project: " . pg_last_error($connection);
        echo json_encode($ret);
        return;
    }
  $row=pg_fetch_array($result);  
 if(pg_num_rows($result)>0)
 {
$ret['error'] = "This merchandiser is assigned to Project: ".$row['name']." , JobID: ".$row['prj_name']." at the selected date and time..." ;
        echo json_encode($ret);
        return;     
 }
    $sql = '';
    pg_free_result($result);     
     
     
     $sql ='insert into  prj_merchants_new(pid,cid,merch';   
   
    $sql_add="";
  if(isset($location)&&$location!="")
      $sql_add.=',location';
    if(isset($due_date)&&$due_date!="")
    {
        
      $sql_add.=',due_date';
    }
     if(isset($notes)&&$notes!="")
      $sql_add.=',notes';
	  
	  if(isset($address) && $address!="")
	   $sql_add.=',address';
	   
	   if(isset($phone) && $phone!="")
	   $sql_add.=',phone';
	   
	   if(isset($city) && $city!="")
	    $sql_add.=',city';
	  if(isset($zip) && $zip!="")
	    $sql_add.=',zip';
	if(isset($region1) && $region1!="")
		$sql_add.=',region';     
     if(isset($st_time)&&$st_time!="")
      $sql_add.=',st_time';
     
       if(isset($store_num)&&$store_num!="")
      $sql_add.=',store_num';
  $sql.=$sql_add.")values(".$pid.",".$cid.",".$merch_1;
        
  
  $sql_add="";
  if(isset($location)&&$location!="")
      $sql_add.=",'".$location."'";
    if(isset($due_date)&&$due_date!="")
    {
        $date_arr = explode('/',$due_date);
    $due_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);
         $sql_add.=",".$due_date;
    }
     
     if(isset($notes)&&$notes!="")
      $sql_add.=",'".$notes."'";
	  
	  if(isset($address)&&$address!="")
      $sql_add.=",'".$address."'";
	  
	  if(isset($phone)&&$phone!="")
      $sql_add.=",'".$phone."'";
	  
	  if(isset($city) && $city!="")
	  $sql_add.=",'".$city."'";
	  
	  if(isset($zip) && $zip!="")
	  $sql_add.=",'".$zip."'";
	  
	  if(isset($region1) && $region1!="")
	  $sql_add.=", '".$region1."'";
	  
      
     if(isset($st_time)&&$st_time!="")
         $sql_add.=",'".$st_time."'";
     
      if(isset($store_num)&&$store_num!="")
          $sql_add.=",'".$store_num."'";
          
      $sql.=$sql_add.")";         

    
    }  

 $sql2='update projects set merch_num_stat=1 where pid='.$pid;
pg_query($connection, $sql2);   
    }
    
    //echo "ghjkl".$sql;

if ($sql != '')
{
    if (!($result = pg_query($connection, $sql)))
    {
        $ret['error'] = "Failed to add / edit project: " . pg_last_error($connection);
        echo json_encode($ret);
        return;
    }
    $sql = '';
    pg_free_result($result); 
   if(isset($merch_id_hdn)&&$merch_id_hdn>0)
   {
 // $sql='delete from dtbl_timesheet where m_id='.$merch_id_hdn; 
  //echo $sql;
 // pg_query($connection, $sql);  
  $query="select m.*,cl.client from prj_merchants_new as m left join \"clientDB\" as cl on cl.\"ID\"=m.cid where m_id=".$merch_id_hdn;
 if (!($result = pg_query($connection, $query))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
    $row=pg_fetch_array($result);
    
    if($row['confirm']=='t')
    {
 $st_time=strtotime(date('m/d/Y',$row["due_date"]).' '.$row["st_time"]);
 //echo 'fff'.$st_time;
$sql='select * from dtbl_timesheet where m_id='.$row['m_id'].'  and emp_id='.$row['merch'];
//if($st_time!='') $sql.=' and start_time=\''.$st_time.'\'';
$result = pg_query($connection, $sql);
if(pg_num_rows($result)>0){
    $r=pg_fetch_array($result);
$sql='update dtbl_timesheet set m_id='.$row['m_id'];    
 if(isset($row['store_num']))
$sql.=',store_num='.$row["store_num"]; 
  if(isset($row['location']))
$sql.=',store='.$row["location"]; 
    if(isset($row['client']))
$sql.=',client=\''.pg_escape_string($row["client"]).'\''; 
    $sql.=' where time_id='.$r['time_id'];
 pg_query($connection, $sql);   
}
else{
$sql='insert into dtbl_timesheet (status,start_time,emp_id,store_num,store,pid,m_id,client) values(3' ;
if(isset($st_time)&&$st_time!='')
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
$sql.=',\''.pg_escape_string($row["client"]).'\''; 
else $sql.=',null';
 $sql.=")" ;
// echo $sql;
 pg_query($connection, $sql);       
    }
    }
   }
   else{
$sql='select max(m_id) as m_id from prj_merchants_new where pid='.$pid.' and merch='.$merch_1;
$result = pg_query($connection, $sql);
$r = pg_fetch_array($result);
pg_free_result($result); 
$merch_id_hdn=$r['m_id'];
   }
    
  $sql4 = 'Select * from "projects'.$ext.'" as p left join "prj_merchants_new'.$ext.'" as m on m.pid=p.pid where p.pid='.$pid;
if (!($result = pg_query($connection, $sql4))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
$row3 = pg_fetch_array($result);
//$num_merch=$row3['num_merch'];
if(isset($row3['m_id']) && $row3['m_id']>0)
{
 $num_merch=  pg_num_rows($result);
}
else{
$num_merch=0;    
}
pg_free_result($result);  
 $sql2='update projects set num_merch='.$num_merch.' where pid='.$pid;
pg_query($connection, $sql2);  


$sql='';
if(count($sign_off_arr)>0)
{
 $col_name=array();
 
// print_r($sign_off_arr);
foreach($sign_off_arr as $key=>$s)
{ 
$tname='';
switch(trim($s))
{
case 'ralphs_checklist.php' :
$tname='ralph_checklist_form'; 
$col_store_name='store_name';  
$col_store_num='store_num';
$col_date='date';
$col_address='address';
$col_city='city';
$col_cid='cid';
$col_fid='r_id';
break;  
case 'dmg_form.php' :
$tname='dmg_form';  
$col_store_name='store_name';  
$col_store_num='store_num';
$col_date='date';
$col_address='address';
$col_city='city';
$col_cid='cid';
$col_fid='dmg_id';    
break; 
case 'dmg_convenience_form.php' :
$tname='dmg_convnc_form';  
$col_store_name='store_name';  
$col_store_num='store_num';
$col_date='date';
$col_address='address';
$col_city='city';
$col_cid='cid';
$col_fid='dmg_id';    
break; 
case 'stater_bros_form.php' :
$tname='stater_bros_form'; 
$col_store_name='store_name';  
$col_store_num='store_num';
$col_date='date';
$col_address='address';
$col_city='city';
$col_cid='cid';
$col_fid='stat_bros_id';    
break; 
case 'frito_lay_rest_form.php' :
$tname='frito_lay_form';  
$col_store_name='store';  
$col_store_num='number';
$col_date='date';
$col_address='address';
$col_city='city';
$col_cid='cid';
$col_fid='frito_id';     
break; 
case 'pizza_form.php' :
$tname='pizza_form'; 
$col_store_name='store_name';  
$col_store_num='store_num';
$col_date='blit_date';
$col_address='address';
$col_city='city';
$col_cid='cid';
$col_fid='pizza_id';    
break; 
case 'ralphs_reset.php' :
$tname='ralphs_reset_form';  
$col_store_name='store_name';  
$col_store_num='store_num';
$col_date='date';
$col_address='address';
$col_city='city';
$col_cid='cid';
$col_fid='r_id';     
break;    
}
$col='m_id,pid';
$val=$merch_id_hdn.','.$pid;
$up_qr='m_id='.$merch_id_hdn;
if(isset($location) && $location!='')
{
 $col.=',"'.$col_store_name.'"'; 
 $val.=',\''.$location.'\'';
 $up_qr.=',"'.$col_store_name.'"=\''.$location.'\'';
}
if(isset($store_num) && $store_num!='')
{
 $col.=',"'.$col_store_num.'"'; 
 $val.=',\''.$store_num.'\'';
 $up_qr.=',"'.$col_store_num.'"=\''.$store_num.'\'';
}
$due_date=$_REQUEST['due_date'];
if(isset($due_date) && $due_date!='')
{
 $col.=',"'.$col_date.'"'; 
 $val.=',\''.$due_date.'\'';
 $up_qr.=',"'.$col_date.'"=\''.$due_date.'\'';
}
if(isset($address) && $address!='')
{
 $col.=',"'.$col_address.'"'; 
 $val.=',\''.pg_escape_string($address).'\'';
 $up_qr.=',"'.$col_address.'"=\''.pg_escape_string($address).'\'';
}
if(isset($city) && $city!='')
{
 $col.=',"'.$col_city.'"'; 
 $val.=',\''.pg_escape_string($city).'\'';
 $up_qr.=',"'.$col_city.'"=\''.pg_escape_string($city).'\'';
}
if(isset($cid) && $cid!='')
{
 $col.=',"'.$col_cid.'"'; 
 $val.=',\''.$cid.'\'';
 $up_qr.=',"'.$col_cid.'"=\''.$cid.'\'';
}
if(isset($sign_off_id_arr[$key])&&$sign_off_id_arr[$key]>0)
{
$sql.='update "'.$tname.'" set '.$up_qr.' where "'.$col_fid.'"='.$sign_off_id_arr[$key].';';    
}
else
{
$sql.='insert into "'.$tname.'" ('.$col.') values('.$val.');';    
}   
}
// echo $sql;   
}
if($sql!=''){
 pg_query($connection, $sql);    
}


if(isset($_REQUEST['signmerch_off_arr'])&&count($_REQUEST['signmerch_off_arr'])>0)
{
$qr='';
foreach($_REQUEST['signmerch_off_arr'] as $key=>$v)
{
if(isset($_REQUEST['signmerch_id_arr'][$key])&&$_REQUEST['signmerch_id_arr'][$key]>0)
{
// $qr.='update ';   
}
else{
$qr.='insert into "signmerch_list" ("pid","emp_id") values(\''.$pid.'\',\''.$v.'\');';
}  
}
//echo $qr;
if($qr!=''){
 pg_query($connection, $qr);    
}
}



    $ret['msg']= "Successfuly saved the project details.";    
}   

echo json_encode($ret);
return;
?>