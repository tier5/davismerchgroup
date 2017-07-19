<?php
require 'Application.php';
extract($_POST);
$ret = array();
$ret['merch_id'] = "";
$ret['cid'] = "";
$ret['due_date'] = "";
$ret['merch'] = "";
$ret['notes'] = "";
$ret['sto_nam'] = "";
$ret['st_time'] = "";
$ret['location'] = "";
$ret['store_num'] ="";
$ret['address'] = "";
$ret['phone'] = "";
$ret['city'] = "";
$ret['zip'] = "";
$ret['merch'] = "";
$ret['client'] ="";
 $ret['m_id']="";
if(isset($merch_id)&&$merch_id!='')
{
   $sql = "SELECT  m1.region,merch.st_time,merch.location,merch.store_num,str2.sto_num as str2_sto_num ,chain.chain,merch.m_id,str.sto_name,str.sto_num,merch.store_num,merch.st_time,merch.cid,merch.merch,merch.notes, merch.address, merch.phone, merch.city, merch.zip, merch.due_date,prj.prj_name as prjname, c.client, c.\"clientID\", m1.firstname as m1first, m1.lastname as m1last ";
        $sql .= " FROM projects as prj left join prj_merchants as merch on merch.pid=prj.pid  inner join \"clientDB\" as c on c.\"ID\" = merch.cid";
        $sql .= " left join tbl_store as str on str.sid=merch.location";
          $sql .= " left join tbl_store as str2 on str2.sid=merch.store_num ";
		$sql.=" left join tbl_chain as chain on chain.ch_id=merch.location ";
        $sql .= " left join \"employeeDB\" as m1 on m1.\"employeeID\"=merch.merch  where  merch.m_id=".$merch_id;

       $result=pg_query($connection, $sql) or die("Error...");
       $row = pg_fetch_array($result);
 if(isset($row['due_date'])&&$row['due_date']!="") 
 $ret['due_date']=date('m/d/Y',$row['due_date']);

 $ret['merch'] = $row['merch'];
$ret['notes'] = $row['notes'];
$ret['sto_nam'] = $row['sto_nam'];
$ret['st_time'] = $row['st_time'];
$ret['location'] = $row['location'];
$ret['store_num']= $row['store_num'];
$ret['address'] = $row['address'];
$ret['phone'] = $row['phone'];
$ret['city'] = $row['city'];
$ret['zip'] = $row['zip'];
$ret['client'] = $row['client'];
 $ret['merch']=$row['merch'];
  $ret['m_id']=$row['m_id'];
   $ret['region']=$row['region'];
}
//print_r($ret);
header('Content-Type: Application/json');
echo json_encode($ret);
?>
