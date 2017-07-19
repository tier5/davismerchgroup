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

function countRec($fname,$tname) {
	$sql = "SELECT count($fname) FROM $tname ";
	$result = runSQL($sql);
	while ($row = pg_fetch_array($result)) {
		return $row[0];
	}
}

$sort = "ORDER BY $sortname $sortorder";
$start = (($page-1) * $rp);

$limit = "OFFSET $start LIMIT $rp";

$where = " WHERE prj.status = $status  $client_sql";
if ($query) $where .= " AND $qtype LIKE '%".  pg_escape_string($query)."%' ";

$sql = "SELECT prj.*, c.client, c.\"clientID\", m1.firstname as m1first, m1.lastname as m1last, m2.firstname as m2first, m2.lastname as m2last,  m3.firstname as m3first, m3.lastname as m3last ";
$sql .= " FROM projects as prj inner join \"clientDB\" as c on c.\"ID\" = prj.cid";

$sql .= " left join \"employeeDB\" as m1 on m1.\"employeeID\"=prj.merch_1 left join \"employeeDB\" as m2 on m2.\"employeeID\"=prj.merch_2 left join \"employeeDB\" as m3 on m3.\"employeeID\"=prj.merch_3 ";
$sql .= "$where $sort $limit";

$result = runSQL($sql);
while($out = pg_fetch_array($result)){
    $rows[] = $out;
}
$total = countRec("prj.prj_name","projects as prj inner join \"clientDB\" as c on c.\"ID\" = prj.cid $where");

header("Content-type: application/json");
$jsonData = array('page'=>$page,'total'=>$total,'rows'=>array());
foreach($rows AS $row){
	//If cell's elements have named keys, they must match column names
	//Only cell's with named keys and matching columns are order independent.    
	$entry = array('id'=>$row['pid'],
		'cell'=>array(
			'prj.prj_name'=>$row['prj_name'],			
                        'edit'=>'<img src="'.$mydirectory.'/images/edit.png" class="hand" alt="Edit" title="Edit: '.$row['prj_name'].'" onclick="show_project('.$row['pid'].')" />',
'complete'=> '<span style="background-color: #69C73D; padding:1px; margin-top:-2px;"><input type="checkbox" class="green" name="complete" value="'.$row['pid'].'"'.$checkbox.' onclick="javascript: if(confirm(\'Are you sure want to close the project\')) { close_project('.$row['pid'].'); } else { return false; }"></span>',

						'email'=>'<a style="cursor:hand;cursor:pointer;"  onclick="send_email(' . $row['pid'] . ');"><img src="'.$mydirectory.'/images/email.png"  title="Email:" /></a>'

		),
	);
	$jsonData['rows'][] = $entry;
}
echo json_encode($jsonData);