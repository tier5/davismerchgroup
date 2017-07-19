<?php
require 'Application.php';
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'name';
$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
if($_SESSION['perm_admin'] == "on")
{
require './flexgrid_storage.php';
}
$query = isset($_POST['query']) ? $_POST['query'] : false;
$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
$ret = array();
$ret['error']= '';
$ret['out'] = '';
$status = 1;
$client_sql = '';
 if($_SESSION['emp_type']!=0) {
  $is_client=1;   
 }
if($is_client==1){
    $client_sql = ' AND c."ID"='.$client_id;
}
if(isset($_GET['close']) && $_GET['close'] == 1)
{
	$status = 0;
        $checkbox = ' disabled="disabled" checked="checked" ';
}

function runSQL($rsql) {
global $connection, $ret;
     if(!($result=pg_query($connection,$rsql))){
        $ret['error'] = "Failed to get project: " . pg_last_error($connection);
        echo json_encode($ret);
        return false;
    }
 return $result;
}

function countRec($sql) {
	//$sql = "SELECT count($fname) FROM $tname ";
       // echo $sql;
	$result = runSQL($sql);
		//return $row[0];
	
    return(pg_num_rows($result));
}

$sort = "ORDER BY $sortname $sortorder";
$start = (($page-1) * $rp);

$limit = "OFFSET $start LIMIT $rp";
$join_type='left join';
$where = " and  prj.status =". $status;  //$client_sql";
if ($query) $where .= " AND $qtype LIKE '%".  pg_escape_string($query)."%' ";
$cl_flag=0;

if(isset($_GET['date_from'])&&$_GET['date_from']!="")
{
     $date_arr = explode('/',$_GET['date_from']);
    $from_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);

 $where.=' AND m."due_date">='.$from_date;   
 $join_type='inner join';
 $cl_flag=1;
}
if(isset($_GET['date_to'])&&$_GET['date_to']!="")
{
      $date_arr = explode('/',$_GET['date_to']);
    $to_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);
 $where.=' AND m."due_date"<='.$to_date;  
 $join_type='inner join';
 $cl_flag=1;
}

$where_main="";
if(isset($_GET['project'])&&$_GET['project']!="")
{
      
 $where_main=" where prj.m_pid=".$_GET['project'];   
 //$join_type='inner join';
 $cl_flag=1;
}
if(isset($_GET['jobid_num'])&&$_GET['jobid_num']!="")
{
  if($where_main=='') $where_main=" where";  
  else $where_main.=" and ";
  
 $where_main.="  prj.prj_name like '%".$_GET['jobid_num']."%'";   
 //$join_type='inner join';
 $cl_flag=1;
}



if(isset($_GET['st_name'])&&$_GET['st_name']!="")
{
      
 $where.=" AND ch.ch_id =".$_GET['st_name']."";   
 $join_type='inner join';
 $cl_flag=1;
}



if(isset($_GET['sto_num'])&&$_GET['sto_num']!="")
{
      
 $where.=" AND st.chain_id =".$_GET['sto_num']."";  
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_GET['merch'])&&$_GET['merch']!="")
{
      
 $where.=" AND mer.merch =".$_GET['merch']."";  
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_GET['time'])&&$_GET['time']!="")
{
      
 $where.="  AND mer.st_time like'%".$_GET['time']."%'";
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_GET['region'])&&$_GET['region']!="")
{
      
 $where.=" AND reg.rid =".$_GET['region']."";   
 $join_type='inner join';
 $cl_flag=1;
}
if($_SESSION['emp_type']!=0) 
{
  $where.=" AND mer.cid='".$_SESSION['client_id']."'";   
  $join_type='inner join';
}
if($_SESSION['perm_admin'] != "on" && $_SESSION['emp_type']==0)
{
    
if($_SESSION['perm_manager'] == "on")
     $where.=" AND mer.region=(select region from \"employeeDB\" where \"employeeID\"=".$_SESSION['employeeID']." limit 1) OR prj.created_by ='".$_SESSION['employeeID']."'"; 
 else   
  $where.=" AND mer.merch='".$_SESSION['employeeID']."' OR prj.created_by ='".$_SESSION['employeeID']."'";   
  $join_type='inner join';
  
//  if($where_main==='') $where_main.=' where ';
//        else $where_main.=' or';
//        $where_main.=" prj.created_by ='".$_SESSION['employeeID']."'";
}
    if($where_main==='') $where_main.=' where ';
        else $where_main.=' and';
if(isset($_GET['close']) && $_GET['close'] == 1)
{

$where_main.=" prj.status =0 "; 
}  else
{
$where_main.="  prj.status =1 ";     
}




$sql ='SELECT distinct ( prj.prj_name ),main.name,prj.bulb_stat, prj.pid, chain.chain,m1.firstname, m1.lastname, reg.region, st.sto_num, m.st_time,m.due_date 
from projects  as prj  
 '.$join_type.' prj_merchants_new as m on m.m_id = (select mer.m_id from prj_merchants_new as mer left join tbl_region as reg on reg.rid=mer.region '
.' left join tbl_chain as ch on ch.ch_id=mer.location left join tbl_chainmanagement as st on st.chain_id = mer.store_num '
.'where mer.pid = prj.pid '.$where.' limit 1 )
left join "employeeDB" as m1 on m1."employeeID"= m.merch left join tbl_chainmanagement as st on st.chain_id = m.store_num  '.
   ' left join project_main as main on main.m_pid=prj.m_pid '.
 '  left join tbl_chain as chain on chain.ch_id=m.location  left join tbl_region as reg on reg.rid=m1.region '.$where_main ;
$total =countRec($sql);
$sql .= " $sort $limit";

//echo $sql;

$result = runSQL($sql);
while($out = pg_fetch_array($result)){
    $rows[] = $out;
}

if($is_client==1&&$cl_flag==0)
{
unset($rows);
//$total=0;
}

//$total =countRec("prj.prj_name","projects as prj  $where");
//echo 'total-'.$total; 
///print_r($rows);
header("Content-type: application/json");
$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
if($is_client==1&&$cl_flag==0)
{
 $jsonData['is_client']=1;   
}
if(count($rows)>0)
{
foreach($rows AS $row){
    
 //$del='<a  ';
 if(isset($_GET['close']) || $_GET['close'] == 1)
{
  $del='<a onclick="del_proj(' . $row['pid'] . ');" style="cursor:hand;cursor:pointer;"  ><img width="30px" height="30px" src="'.
          $mydirectory.'/images/delete.png"  title="Delete" /></a>';    
 }
 //else $del.='onclick="alert(\'First close the project and then delete...\');" ';
 //print_r($row);
 //$del.='style="cursor:hand;cursor:pointer;"  ><img width="30px" height="30px" src="'.$mydirectory.'/images/delete.png"  title="Delete" /></a>';   
	//If cell's elements have named keys, they must match column names
	//Only cell's with named keys and matching columns are order independent. 
 //$row['bulb_stat']='sign';
switch($row['bulb_stat'])
{
case 'none':
$bulb_stat='bulb_blue';    
break;  
case 'sign':
$bulb_stat='bulb_yellow';    
break;
case 'img':
$bulb_stat='bulb_yellow';    
break;
case 'both':
$bulb_stat='bulb_green';    
break;
default:
$bulb_stat='bulb_blue';    
break;
}
 if(isset($row['due_date'])&&$row['due_date']!='') $row['due_date']=date('m/d/Y',$row['due_date']);
 else $row['due_date']='';
	$entry = array('id'=>$row['pid'],
		'cell'=>array(
			'prj.prj_name'=>$row['prj_name'],
                    'm.due_date'=>$row['due_date'],
			'm.st_time'=>$row['st_time'],
			'chain.chain'=>$row['chain'],
                    'main.name'=>$row['name'],
			'st.sto_num'=>$row['sto_num'],
			'reg.region'=>$row['region'],
                        'edit'=>'<a href="project.php?pid='.$row['pid'].($_GET['close']==1?'&close=1':'').'"><img src="'.$mydirectory.'/images/'.($_SESSION['emp_type']==0?'edit.png':'view.png').'" class="hand" alt="Edit" title="Edit: '.$row['prj_name'].'" /></a>',
//'copy'=>'<img src="'.$mydirectory.'/images/save.png" class="hand" alt="Copy" title="Copy: '.$row['prj_name'].'" onclick="copy_project('.$row['pid'].')" />',
//'complete'=> '<span style="background-color: #69C73D; padding:1px; margin-top:-2px;"><input type="checkbox" class="green" name="complete" value="'.$row['pid'].'"'.$checkbox.' onclick="javascript: if(confirm(\'Are you sure want to close the project\')) { close_project('.$row['pid'].'); } else { return false; }"></span>',

'email'=>'<a style="cursor:hand;cursor:pointer;"  onclick="send_email(' . $row['pid'] . ');"><img src="'.$mydirectory.'/images/email.png"  title="Email" /></a>',
'view'=>'<a style="cursor:hand;cursor:pointer;"  onclick="view_merchandiser(' . $row['pid'] . ');"><img width="40px" height="40px" src="'.$mydirectory.'/images/lense.png"  title="View" /></a>',                   
'bulb_stat'=>'<img  width="30px" height="30px" alt="'.$bulb_stat.'" src="'.$mydirectory.'/images/'.$bulb_stat.'.png" class="hand" alt="Edit" title="Edit: '.$row['prj_name'].'" />',
//,'delete'=>$del

		),
	);
       
if(!isset($is_client)||$is_client!=1)
{
$entry['cell']['m1.firstname']=$row['firstname']." ".$row['lastname'];      
}
        
if($_SESSION['emp_type']==0) 
{
$entry['cell']['copy']='<img src="'.$mydirectory.'/images/save.png" class="hand" alt="Copy" title="Copy: '.$row['prj_name'].'" onclick="copy_project('.$row['pid'].')" />';
//$entry['cell']['complete']='<span style="background-color: #69C73D; padding:1px; margin-top:-2px;"><input type="checkbox" class="green" name="complete" value="'.$row['pid'].'"'.$checkbox.' onclick="javascript: if(confirm(\'Are you sure want to close the project\')) { close_project('.$row['pid'].'); } else { return false; }"></span>';
$entry['cell']['delete']=$del;
$entry['cell']['select']='<span style="background-color: #69C73D; padding:1px; margin-top:-2px;"> <input type="checkbox" class="green complete"  name="complete" value="'.$row['pid'].'" ></span>';
}
	$jsonData['rows'][] = $entry;
}
}
echo json_encode($jsonData);