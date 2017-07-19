<?php
require 'Application.php';
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'name';
$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
$query = isset($_POST['query']) ? $_POST['query'] : false;
$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
$ret = array();
$ret['error']= '';
$ret['out'] = '';
$status = 1;
$client_sql = '';
if($is_client){
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

$where = " WHERE  prj.status =". $status;  //$client_sql";
if ($query) $where .= " AND $qtype LIKE '%".  pg_escape_string($query)."%' ";

if(isset($_GET['date_from'])&&$_GET['date_from']!="")
{
     $date_arr = explode('/',$_GET['date_from']);
    $from_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);

 $where.=' AND m."due_date">='.$from_date;   
}
if(isset($_GET['date_to'])&&$_GET['date_to']!="")
{
      $date_arr = explode('/',$_GET['date_to']);
    $to_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);
 $where.=' AND m."due_date"<='.$to_date;   
}


if(isset($_GET['project'])&&$_GET['project']!="")
{
      
 $where.=" AND prj.pid =".$_GET['project']."";   
}

if(isset($_GET['st_name'])&&$_GET['st_name']!="")
{
      
 $where.=" AND ch.ch_id =".$_GET['st_name']."";   
}

if(isset($_GET['sto_num'])&&$_GET['sto_num']!="")
{
      
 $where.=" AND st.sto_num like'%".$_GET['sto_num']."%'";   
}

if(isset($_GET['merch'])&&$_GET['merch']!="")
{
      
 $where.=" AND merch =".$_GET['merch']."";   
}

if(isset($_GET['time'])&&$_GET['time']!="")
{
      
 $where.="  AND m.st_time like'%".$_GET['time']."%'";   
}




$sql = 'SELECT distinct prj.pid, prj.prj_name,ch.chain,m1.firstname, m1.lastname, (select sto_num from tbl_store where sid=m.store_num limit 1) as sto_num, m.st_time from "prj_merchants" as m left join projects as prj on prj.pid = (select mer.pid from prj_merchants as mer where mer.pid = m.pid  limit 1 )  left join tbl_chain as ch on ch.ch_id=m.location '.
' left join tbl_store as st on st.sid=m.store_num ' 
.' left join "employeeDB" as m1 on m1."employeeID"=(select merch from prj_merchants where pid=prj.pid limit 1)  '.$where ;
$total =countRec($sql);
$sql .= " $sort $limit";

//echo $sql;

$result = runSQL($sql);
while($out = pg_fetch_array($result)){
    $rows[] = $out;
}
//$total =countRec("prj.prj_name","projects as prj  $where");
//echo 'total-'.$total; 
///print_r($rows);
header("Content-type: application/json");
$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
if(count($rows)>0)
{
foreach($rows AS $row){
	//If cell's elements have named keys, they must match column names
	//Only cell's with named keys and matching columns are order independent.    
	$entry = array('id'=>$row['pid'],
		'cell'=>array(
			'prj.prj_name'=>$row['prj_name'],	
			'm.st_time'=>$row['st_time'],
			'ch.chain'=>$row['chain'],
			'prj.location'=>$row['sto_num'],
			'm1.firstname'=>$row['firstname']." ".$row['lastname'],
                        'edit'=>'<img src="'.$mydirectory.'/images/edit.png" class="hand" alt="Edit" title="Edit: '.$row['prj_name'].'" onclick="show_project('.$row['pid'].')" />',
'copy'=>'<img src="'.$mydirectory.'/images/save.png" class="hand" alt="Copy" title="Copy: '.$row['prj_name'].'" onclick="copy_project('.$row['pid'].')" />',
'complete'=> '<span style="background-color: #69C73D; padding:1px; margin-top:-2px;"><input type="checkbox" class="green" name="complete" value="'.$row['pid'].'"'.$checkbox.' onclick="javascript: if(confirm(\'Are you sure want to close the project\')) { close_project('.$row['pid'].'); } else { return false; }"></span>',

						'email'=>'<a style="cursor:hand;cursor:pointer;"  onclick="send_email(' . $row['pid'] . ');"><img src="'.$mydirectory.'/images/email.png"  title="Email:" /></a>'

		),
	);
	$jsonData['rows'][] = $entry;
}
}
echo json_encode($jsonData);