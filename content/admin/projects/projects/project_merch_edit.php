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
  $ret['sign_off']="";
if(isset($merch_id)&&$merch_id!='')
{
   $sql = "SELECT  reg.region,reg.rid,merch.st_time,merch.location,merch.store_num,str2.sto_num as str2_sto_num ,chain.chain,merch.m_id,str.sto_name,str.sto_num,merch.store_num,merch.st_time,merch.cid,merch.merch,merch.notes, merch.address, merch.phone, merch.city, merch.zip, merch.due_date,prj.prj_name as prjname, c.client, c.\"clientID\", m1.firstname as m1first, m1.lastname as m1last,merch.pid ";
        $sql .= " FROM projects as prj left join prj_merchants_new as merch on merch.pid=prj.pid  inner join \"clientDB\" as c on c.\"ID\" = merch.cid";
        $sql .= " left join tbl_chainmanagement as str on str.chain_id=merch.location";
        $sql.=" left join tbl_region as reg on reg.rid=merch.region ";
          $sql .= " left join tbl_chainmanagement as str2 on str2.chain_id=merch.store_num ";
		$sql.=" left join tbl_chain as chain on chain.ch_id=merch.location ";
        $sql .= " left join \"employeeDB\" as m1 on m1.\"employeeID\"=merch.merch  where  merch.m_id=".$merch_id;

       $result=pg_query($connection, $sql) or die("Error...");
       $row = pg_fetch_array($result);
 if(isset($row['due_date'])&&$row['due_date']!="") 
 $ret['due_date']=date('m/d/Y',$row['due_date']);

 $pid=$row['pid'];
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
   $ret['rid']=$row['rid'];
   $ret['cid'] = $row['cid'];
   $ret['sign_merch_list']='';
 $ralph_array=array();
 $query='select r_id from ralph_checklist_form where m_id='.$merch_id;
 if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$ralph_array[]=$row;    
}
 

$dmg_array=array();
$query='select dmg_id from dmg_form where m_id='.$merch_id;
 if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$dmg_array[]=$row;    
}

$dmgconv_array=array();
$query='select dmg_id from dmg_convnc_form where m_id='.$merch_id;
 if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$dmgconv_array[]=$row;    
}

$groc_array=array();
$query='select stat_bros_id from stater_bros_form where m_id='.$merch_id;
 if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$groc_array[]=$row;    
}

$frito_array=array();
$query='select frito_id from frito_lay_form where m_id='.$merch_id;
 if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$frito_array[]=$row;    
}

$pizza_array=array();
$query='select pizza_id from pizza_form where m_id='.$merch_id;
 if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$pizza_array[]=$row;    
}

$r_reset_array=array();
$query='select r_id from ralphs_reset_form where m_id='.$merch_id;
 if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$r_reset_array[]=$row;    
}
$query='select * from signmerch_list as l left join "employeeDB" as e on e."employeeID"=l.emp_id where pid='.$pid;
 if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$sign_merch_list[]=$row;   
}

foreach($sign_merch_list as $v)
{
 $ret['sign_merch_list'].='<tr><td style="height:30px;"><input type="hidden" value="'.$v['emp_id'].'" name="signmerch_off_arr[]">
<input type="hidden" value="'.$v['id'].'" name="signmerch_id_arr[]">'.$v['firstname'].' '.$v['lastname']
.'<img width="20" height="20" src="'.$mydirectory.'/images/delete.png" style="cursor:pointer;cursor:hand;" onclick="deletesignmerch(\''.$v['id'].'\');$(this).parent().parent().remove();" />'         
.'</td></tr>';    
}




foreach($ralph_array as $r)
{
 $ret['sign_off'].='<tr><td style="height:30px;"><input type="hidden" value="ralphs_checklist.php" name="sign_off_arr[]">
<input type="hidden" value="'.$r['r_id'].'" name="sign_off_id_arr[]"><a href="./project.php?pid='.$pid.'&signtype=ralphs_checklist&fid='.$r['r_id'].'">Ralphs Daily Checklist Form</a>'
.'<img width="20" height="20" src="'.$mydirectory.'/images/delete.png" style="cursor:pointer;cursor:hand;" onclick="deleteForm(\''.$r['r_id'].'\',\'ralphreset\');$(this).parent().parent().remove();" />'         
.'</td></tr>';   
}
foreach($dmg_array as $r)
{
 $ret['sign_off'].='<tr><td style="height:30px;"><input type="hidden" value="dmg_form.php" name="sign_off_arr[]">
<input type="hidden" value="'.$r['dmg_id'].'" name="sign_off_id_arr[]"><a href="./project.php?pid='.$pid.'&signtype=dmg_form&fid='.$r['dmg_id'].'">DMG Chain Form</a>'
.'<img width="20" height="20" src="'.$mydirectory.'/images/delete.png" style="cursor:pointer;cursor:hand;" onclick="deleteForm(\''.$r['dmg_id'].'\',\'dmg\');$(this).parent().parent().remove();" />'         
.'</td></tr>';   
}
foreach($dmgconv_array as $r)
{
 $ret['sign_off'].='<tr><td style="height:30px;"><input type="hidden" value="dmg_convenience_form.php" name="sign_off_arr[]">
<input type="hidden" value="'.$r['dmg_id'].'" name="sign_off_id_arr[]"><a href="./project.php?pid='.$pid.'&signtype=dmg_convenience_form&fid='.$r['dmg_id'].'">DMG Convenience Form</a>'
.'<img width="20" height="20" src="'.$mydirectory.'/images/delete.png" style="cursor:pointer;cursor:hand;" onclick="deleteForm(\''.$r['dmg_id'].'\',\'dmgconv\');$(this).parent().parent().remove();" />'         
.'</td></tr>';   
}
foreach($groc_array as $r)
{
 $ret['sign_off'].='<tr><td style="height:30px;"><input type="hidden" value="stater_bros_form.php" name="sign_off_arr[]">
<input type="hidden" value="'.$r['stat_bros_id'].'" name="sign_off_id_arr[]"><a href="./project.php?pid='.$pid.'&signtype=stater_bros_form&fid='.$r['stat_bros_id'].'">Grocery Form</a>'
.'<img width="20" height="20" src="'.$mydirectory.'/images/delete.png" style="cursor:pointer;cursor:hand;" onclick="deleteForm(\''.$r['stat_bros_id'].'\',\'staterbros\');$(this).parent().parent().remove();" />'         
.'</td></tr>';   
}
foreach($frito_array as $r)
{
 $ret['sign_off'].='<tr><td style="height:30px;"><input type="hidden" value="frito_lay_rest_form.php" name="sign_off_arr[]">
<input type="hidden" value="'.$r['frito_id'].'" name="sign_off_id_arr[]"><a href="./project.php?pid='.$pid.'&signtype=frito_lay_rest_form&fid='.$r['frito_id'].'">Frito-Lay Form</a>'
.'<img width="20" height="20" src="'.$mydirectory.'/images/delete.png" style="cursor:pointer;cursor:hand;" onclick="deleteForm(\''.$r['frito_id'].'\',\'fritolay\');$(this).parent().parent().remove();" />'         
.'</td></tr>';   
}
foreach($pizza_array as $r)
{
 $ret['sign_off'].='<tr><td style="height:30px;"><input type="hidden" value="pizza_form.php" name="sign_off_arr[]">
<input type="hidden" value="'.$r['pizza_id'].'" name="sign_off_id_arr[]"><a href="./project.php?pid='.$pid.'&signtype=pizza_form&fid='.$r['pizza_id'].'">Nestle Form</a>'
.'<img width="20" height="20" src="'.$mydirectory.'/images/delete.png" style="cursor:pointer;cursor:hand;" onclick="deleteForm(\''.$r['pizza_id'].'\',\'pizza\');$(this).parent().parent().remove();" />'         
.'</td></tr>';   
}
foreach($r_reset_array as $r)
{
 $ret['sign_off'].='<tr><td style="height:30px;"><input type="hidden" value="ralphs_reset.php" name="sign_off_arr[]">
<input type="hidden" value="'.$r['r_id'].'" name="sign_off_id_arr[]"><a href="./project.php?pid='.$pid.'&signtype=ralphs_reset&fid='.$r['r_id'].'">Ralphs Reset Form</a>'
.'<img width="20" height="20" src="'.$mydirectory.'/images/delete.png" style="cursor:pointer;cursor:hand;" onclick="deleteForm(\''.$r['r_id'].'\',\'ralphreset\');$(this).parent().parent().remove();" />'         
.'</td></tr>';   
}


 }
//print_r($ret);
header('Content-Type: Application/json');
echo json_encode($ret);
?>
