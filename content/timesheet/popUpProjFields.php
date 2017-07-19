<?php
require('Application.php');

extract($_POST);
$res=array();
$res['chain']='';
$res['store_num']='';
$res['store_num_list']='';
$res['client']='';
$res['start_date']='';
$res['start_time']='';
$res['start_hr']='';
$res['start_min']='';
$res['start_ap']='';
if($_POST['m_id']>0)
{

$sql='select prj.*,cl.client as cl_name from prj_merchants_new as prj left join "clientDB" as cl on cl."ID"=prj.cid where m_id='.$_POST['m_id'];
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("Failed query: " . pg_last_error($connection));
	exit;
}
//
	$row=pg_fetch_array($result);
	pg_free_result($result);

 $res['chain']=$row['location'];
$res['store_num']=$row['store_num'];
$res['client']=$row['cl_name'];
$res['start_date']=$row['due_date'];
//$res['start_time']=$row['st_time'];
$st=split(' ',$row['st_time']);
$st2=split(':',$st);
$res['start_hr']=$st2[0];
$res['start_min']=$st2[1];
$res['start_ap']=$st[1];

$sql='select * from tbl_chainmanagement where sto_name='.$row['location'];
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("Failed query: " . pg_last_error($connection));
	exit;
}
//
$res['store_num_list'].='<option value="">----SELECT-----</option>';
	while($row=pg_fetch_array($result))
        {
$res['store_num_list'].='<option value="'.$row['chain_id'].'">'.$row['sto_num'].'</option>';        
        }
	pg_free_result($result);

}
header('content-type:application/json');
echo json_encode($res);
?>