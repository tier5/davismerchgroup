<?php
require 'Application.php';
$dt = $_POST['dt'];
$time_id = 0;
$title = 'Preview Time Entry';
$status_type = array('Waiting For Approval', 'Rejected', 'Approved','Generated');
$joblist=array();
$sql = 'Select distinct prj.pid,prj.prj_name from "projects" as prj left join prj_merchants_new as m on m.pid=prj.pid where m.merch='.$_SESSION['employeeID'];
if (!($result = pg_query($connection, $sql))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
// echo $sql;
 while($row=pg_fetch_array($result))
 {
$joblist[] = $row;
 }
pg_free_result($result);

if(isset($_POST['id']) && $_POST['id'] > 0)
	$time_id = $_POST['id'];

$end = strtotime(date('Y/m/d',$dt).' 23:59:59');
$time=array();


{
$sql = 'SELECT t.time_id,t.pid,p.prj_name,t.m_id,merch.due_date,merch.st_time,ch.chain, t.start_time, t.end_time, t.emp_id, t.store, t.other as others, t.hours_worked, t.reg_hours, t.ot_hours,t.dt_hours, t.lunch_time,'
.'t.break1, t.break2, t.lunch_start, t.lunch_end, t.lunch_hrs, store.sto_num as store_num_val, t.store_num, t.status, t.company_rep, t.client, odo.ot_id, odo.start_read,'
    .'odo.end_read, odo.daily_total, odo.adj_miles, odo.reimburse_mile, odo.gas_rec, odo.misc_exp, odo.food, odo.lodging, odo.toll_fees,'
        .'odo.park_fees, odo.other_exp, odo.misc_exp_type, odo.misc_notes FROM dtbl_timesheet as t '
        .'left join dtbl_odometer as odo on odo.time_id=t.time_id'
    .' left join "tbl_chainmanagement" as store on store.chain_id=t.store_num::bigint left join projects as p on p.pid=t.pid '
    .' left join prj_merchants_new as merch on merch.m_id=t.m_id '       
        .' left join tbl_chain as ch on t.store  like cast(ch.ch_id as character varying)';
if(isset($time_id)&&$time_id>0)
{
$sql.=' WHERE t.time_id= '.$time_id;    
}
else{    
$sql.=' WHERE start_time >= '.$dt.' and start_time <= '.$end. ' and emp_id = '.$_SESSION['employeeID'];
}
$sql.=' order by t.time_id';
//echo $sql;

//$sql="Select (select city from tbl_store where cast(sid as character varying) like t.store limit 1) as city,t.time_id,t.store,ch.chain as ch, t.start_time, t.end_time, t.hours_worked,t.lunch_hrs, t.reg_hours, t.ot_hours, t.status, e.firstname as f, e.lastname as l,reg.region as r,e.city as location, odo.daily_total, odo.reimburse_mile, odo.gas_rec, odo.other_exp, odo.misc_exp_type from dtbl_timesheet t ".
//"left join tbl_chain as ch on t.store  like cast(ch.ch_id as character varying)  left join \"employeeDB\" as e on e.\"employeeID\" = t.emp_id left join tbl_region as reg on reg.rid=e.region left join dtbl_odometer as odo on odo.time_id=t.time_id".  
//        ' WHERE t.start_time >= '.$dt.' and t.start_time <= '.$end. ' and t.emp_id = '.$_SESSION['employeeID'];

//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}

while($row = pg_fetch_array($result))
{
	$time[]=$row;
}
pg_free_result($result);
}
//print_r($time);
if(!isset($_POST['add_new'])&& isset($time) && $time[0]['time_id'] > 0)
{
	extract($time[0]);
        $dt=$start_time;
}
if((count($time)<=0||isset($_POST['add_new'])||isset($_POST['id']))&&(isset($_POST['add_new'])||isset($_POST['from_report'])||(!isset($status) || $status == 1|| $status == 3)))
    {

if(isset($_POST['from_prject'])&& $_POST['from_prject']==1 && $_POST['m_id'] > 0)
{
$sql='select prj.*,p.prj_name,chain.chain as prj_storename,store.sto_num as prj_storenum,cl.client as prj_client from prj_merchants_new as prj left join projects as p on prj.pid=p.pid'
.' left join tbl_chain as chain on chain.ch_id=prj.location '    
.' left join tbl_chainmanagement as store on store.chain_id=prj.store_num ' 
.' left join "clientDB" as cl on cl."ID"=prj.cid '         
.' where m_id='.$_POST['m_id'];
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
$time1=array();
while($row = pg_fetch_array($result))
{
	$time1=$row;
}
pg_free_result($result);
//print_r($time1);
}    
 if((count($time1)<=0)&&(count($time)<=0)&&($_SESSION['perm_admin'] != "on"))
 {
    echo '<h3>You are not allowed to fill timesheet directly.<br/>You can only fill hours in the generated timesheet</h3>'; 
    exit();
 }
    
$title = 'Add Daily Time Record';

$sql3 = 'Select ch_id, chain from "tbl_chain" ORDER BY chain ASC ';
if (!($result = pg_query($connection, $sql3))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row3 = pg_fetch_array($result)){
	$data_chain[]=$row3;
}
$data_store=array();
if(isset($time[0]['store']) && $time[0]['store']!=''){
$sql4 = 'select * from tbl_chainmanagement   where sto_name='.$time[0]['store'].' ORDER BY sto_num ASC';
if (!($result = pg_query($connection, $sql4))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row4 = pg_fetch_array($result)){
	$data_store[]=$row4;
}
pg_free_result($result);
}
else if(isset($time1['location']) && $time1['location']!=''){
$sql4 = 'select * from tbl_chainmanagement   where sto_name='.$time1['location'].' ORDER BY sto_num ASC';
//echo $sql4;
if (!($result = pg_query($connection, $sql4))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row4 = pg_fetch_array($result)){
	$data_store[]=$row4;
}
pg_free_result($result);
}

$query1=("SELECT \"ID\", \"clientID\", \"client\", \"active\" ".
		 "FROM \"clientDB\" ".
		 "WHERE \"active\" = 'yes' ".
		 "ORDER BY \"client\" ASC");
if(!($result1=pg_query($connection,$query1))){
	print("Failed query1: " . pg_last_error($connection));
	exit;
}
while($row1 = pg_fetch_array($result1)){
	$data_client[]=$row1;
}
pg_free_result($result1);


$schedule=array();
 if (isset($time1['pid'])||isset($time[0]['pid']))    
{
 if(isset($time1['pid'])) $pid=$time1['pid'];   
 else if(isset($time[0]['pid'])) $pid=$time[0]['pid']; 
$sql='select * from prj_merchants_new where pid='.$pid.' and merch='.$_SESSION['employeeID'].' order by m_id';    
if (!($result = pg_query($connection, $sql))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
// echo $sql;
 while($row=pg_fetch_array($result))
 {
$schedule[] = $row;
 }
pg_free_result($result);    
}

?>

<div id="form_div">
  <form id="timesheet_form">
     <?php  if(isset($_POST['from_report'])&&$_POST['emp_id']>0) {?>
  <input type="hidden" name="employeeid" value="<?php echo $_POST['emp_id']; ?>" />     
      <?php }?>
  <input type="hidden" name="time_id" value="<?php if(!isset($_POST['add_new'])) echo $time_id; ?>" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="lightup">
      <tr>
        <td width="20">&nbsp;</td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td><?php if(isset($_POST['from_timesheet'])&&(!($time_id>0))&&($_SESSION['perm_admin'] != "on")){}else{ ?>Job ID#: <?php }?> </td>
    <td>
 <?php
  if((!isset($_POST['from_report']))&&((isset($time_id)&&$time_id>0)||(isset($time[0]['status']) && $time[0]['status']==3)||(isset($_POST['from_proj'])&&$_POST['from_proj']==1)||($_SESSION['perm_admin'] != "on"))) 
  {?>
        <strong><?php  if(isset($time1['pid']) && $time1['pid'] !=''){ echo $time1['prj_name'];} 
        else if(isset($time[0]['pid']) && $time[0]['pid']){ echo $time[0]['prj_name'];} 
        ?></strong> 
     <input type="hidden" name="pid" value="<?php  if(isset($time1['pid']) && $time1['pid'] !=''){ echo $time1['pid'];} 
        else if(isset($time[0]['pid']) && $time[0]['pid']){ echo $time[0]['pid'];} 
        ?>"/>   
  <?php }
  else{
 ?>
  <select   name="pid" id="pid" style="font-faimly:verdana;font-size:10;width:150px; height:25px;"  <?php 
                  //   if((isset($time_id)&&$time_id>0)||(isset($time[0]['status']) && $time[0]['status']==3)||(isset($_POST['from_proj'])&&$_POST['from_proj']==1)) echo ' onfocus="this.defaultIndex=this.selectedIndex;" onchange="this.selectedIndex=this.defaultIndex;" ';
            echo ' onchange="javascript:changeSchedule();" ';       
                     ?>>
                    <option value="">----SELECT-----</option>                      
                         <?php                      
                    for ($i = 0; $i < count($joblist); $i++) {
                        echo '<option value="' . $joblist[$i]['pid'] . '" ';
                         if ((isset($time1['pid']) && $time1['pid'] == $joblist[$i]['pid'])||isset($time[0]['pid']) && $time[0]['pid'] == $joblist[$i]['pid'])
                    echo ' selected="selected" ';
                        echo '>' . $joblist[$i]['prj_name'] . '</option>';
                    }
                    ?>                
                    
                </select> 
        <?php }?>
    </td>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
       <td><?php if(isset($_POST['from_timesheet'])&&(!($time_id>0))&&($_SESSION['perm_admin'] != "on")){}else{ ?>Schedule: <?php }?></td>
    <td><?php
  if((!isset($_POST['from_report']))&&((isset($time_id)&&$time_id>0)||(isset($time[0]['status']) && $time[0]['status']==3)||(isset($_POST['from_proj'])&&$_POST['from_proj']==1)||($_SESSION['perm_admin'] != "on"))) 
  {?>
        <strong><?php  if(isset($time1['m_id']) && $time1['m_id'] !=''){ echo date('m/d/Y',$time1['due_date']) .' '.$time1['st_time'];} 
        else if(isset($time[0]['m_id']) && $time[0]['m_id']){ echo date('m/d/Y',$time[0]['due_date']) .' '.$time[0]['st_time'];} 
        ?></strong> 
     <input type="hidden" name="m_id" value="<?php  if(isset($time1['m_id']) && $time1['m_id'] !=''){ echo $time1['m_id'];} 
        else if(isset($time[0]['m_id']) && $time[0]['m_id']){ echo $time[0]['m_id'];} 
        ?>"/>   
  <?php }
  else{
 ?>
  <select   <?php 
                     //if((isset($time_id)&&$time_id>0)||(isset($time[0]['status']) && $time[0]['status']==3)||(isset($_POST['from_proj'])&&$_POST['from_proj']==1)) echo ' onfocus="this.defaultIndex=this.selectedIndex;" onchange="this.selectedIndex=this.defaultIndex;" ';
                     
           echo ' onchange="javascript:popUpProjFields();" ';       
                     ?>   name="m_id" id="m_id" style="font-faimly:verdana;font-size:10;width:150px; height:25px;">                   
                         <?php                      
                    for ($i = 0; $i < count($schedule); $i++) {
                        echo '<option value="' . $schedule[$i]['m_id'] . '" ';
                         if ((isset($time1['m_id']) && $time1['m_id'] == $schedule[$i]['m_id'])||isset($time[0]['m_id']) && $time[0]['m_id'] == $schedule[$i]['m_id'])
                    echo ' selected="selected" ';
                        echo '>' . date('m/d/Y',$schedule[$i]['due_date']) .' '.$schedule[$i]['st_time']. '</option>';
                    }
                    ?>                
                    
                </select>     
     <?php }?>
    </td>
    </tr>
                       <tr>
                      <td><?php if(isset($_POST['from_timesheet'])&&(!($time_id>0))&&($_SESSION['perm_admin'] != "on")){}else{ ?> <span style="color:#CC0000;font-weight: bold;">* </span>Store Name:  <?php }?></td>
                     <td><?php
  if((!isset($_POST['from_report']))&&((isset($time_id)&&$time_id>0)||(isset($time[0]['status']) && $time[0]['status']==3)||(isset($_POST['from_proj'])&&$_POST['from_proj']==1)||($_SESSION['perm_admin'] != "on"))) 
  {?>
        <strong><?php  if(isset($time1['prj_storename']) && $time1['prj_storename'] !=''){ echo $time1['prj_storename'];} 
        else if(isset($time[0]['chain']) && $time[0]['chain']){ echo $time[0]['chain'];} 
        ?></strong> 
     <input type="hidden" name="store_name" value="<?php  if(isset($time1['location']) && $time1['location'] !=''){ echo $time1['location'];} 
        else if(isset($time[0]['store']) && $time[0]['store']){ echo $time[0]['store'];} 
        ?>"/>   
  <?php }
  else{
 ?><select <?php 
                   // if((isset($time_id)&&$time_id>0)||(isset($time[0]['status']) && $time[0]['status']==3)||(isset($_POST['from_proj'])&&$_POST['from_proj']==1)) echo ' onfocus="this.defaultIndex=this.selectedIndex;" onchange="this.selectedIndex=this.defaultIndex;" ';
                 echo ' onchange="javascript:getstorenum();" ';
                     ?> name="store_name"  id="store_name" style="font-faimly:verdana;font-size:10;width:150px; height:25px;">
                     <option value="">------SELECT------</option>
                     <option value="0" <?php
					 if((isset($time[0]['store']) && $time[0]['store']==0)||(isset($time1['location']) && $time1['location']==0))
					 echo ' selected="selected" ';
                     ?>>Other</option>
                    <?php
			for ($i = 0; $i < count($data_chain); $i++) {
    			echo '<option value="'.$data_chain[$i]['ch_id'].'" ';
    				if ((isset($time[0]['store']) && $time[0]['store'] == $data_chain[$i]['ch_id'])||(isset($time1['location']) && $time1['location'] == $data_chain[$i]['ch_id']))
        			echo 'selected="selected" ';
    				echo '>' . $data_chain[$i]['chain'] . '</option>';
				}
		?>
        </select><?php }?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td height="30"><?php if(isset($_POST['from_timesheet'])&&(!($time_id>0))&&($_SESSION['perm_admin'] != "on")){}else{ ?><span style="color:#CC0000;font-weight: bold;">* </span>Store #: <?php }?></td>

                    <td><?php
  if((!isset($_POST['from_report']))&&((isset($time_id)&&$time_id>0)||(isset($time[0]['status']) && $time[0]['status']==3)||(isset($_POST['from_proj'])&&$_POST['from_proj']==1)||($_SESSION['perm_admin'] != "on"))) 
  {?>
        <strong><?php  if(isset($time1['store_num']) && $time1['store_num'] !=''){ echo $time1['prj_storenum'];} 
        else if(isset($time[0]['store_num_val']) && $time[0]['store_num_val']){ echo $time[0]['store_num_val'];} 
        ?></strong> 
     <input type="hidden" name="store_num" value="<?php  if(isset($time1['store_num']) && $time1['store_num'] !=''){ echo $time1['store_num'];} 
        else if(isset($time[0]['store_num']) && $time[0]['store_num']){ echo $time[0]['store_num'];} 
        ?>"/>   
  <?php }
  else{
 ?><select   <?php 
                  //   if((isset($time_id)&&$time_id>0)||(isset($time[0]['status']) && $time[0]['status']==3)||(isset($_POST['from_proj'])&&$_POST['from_proj']==1)) echo ' onfocus="this.defaultIndex=this.selectedIndex;" onchange="this.selectedIndex=this.defaultIndex;" ';
                  
                     ?>   name="store_num" id="store_num123" style="font-faimly:verdana;font-size:10;width:150px; height:25px;<?php  if($time[0]['store']==0&&$time[0]['store']!='')echo 'display:none;';?>">
                    <option value="">----SELECT-----</option>                      
                         <?php                      
                    for ($i = 0; $i < count($data_store); $i++) {
                        echo '<option value="' . $data_store[$i]['chain_id'] . '" ';
                         if ((isset($time[0]['store_num']) && $time[0]['store_num'] == $data_store[$i]['chain_id'])||(isset($time1['store_num']) && $time1['store_num'] == $data_store[$i]['chain_id']))
                    echo ' selected="selected" ';
                        echo '>' . $data_store[$i]['sto_num'] . '</option>';
                    }
                    ?>                
                    
                </select><?php }?></td>
                  </tr>
                 
                  <tr id="show_other" style="<?php if($time[0]['store']!=0||$time[0]['store']=='') echo 'display:none;';?>">
                   <td> <span style="color:#CC0000;font-weight: bold;">*</span>Other: </td>
                  <td><input type="text" name="other" value="<?php echo $others; ?> " style="font-faimly:verdana;font-size:10;width:150px; height:20px;" /></td>
                  <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>              
                       
                  <tr>
                    <td><?php if(isset($_POST['from_timesheet'])&&(!($time_id>0))&&($_SESSION['perm_admin'] != "on")){}else{ ?><span style="color:#CC0000;font-weight: bold;">* </span>Client: <?php }?></td>
                    
                 
                    <td><?php
   if((isset($_POST['from_timesheet'])&&!($time_id>0)))
   { ?>
    <input type="text" style="display:none;" name="from_timesheet_flg" value="1"/>    
   <?php }
  if((!isset($_POST['from_report']))&&((isset($time_id)&&$time_id>0)||(isset($time[0]['status']) && $time[0]['status']==3)||(isset($_POST['from_proj'])&&$_POST['from_proj']==1)||($_SESSION['perm_admin'] != "on"))) 
  {?>
        <strong><?php  if(isset($time1['cid']) && $time1['cid'] !=''){ echo $time1['prj_client'];} 
        else if(isset($time[0]['client']) && $time[0]['client']){ echo $time[0]['client'];} 
        ?></strong> 
     <input type="hidden" name="client" value="<?php  if(isset($time1['prj_client']) && $time1['prj_client'] !=''){ echo $time1['prj_client'];} 
        else if(isset($time[0]['client']) && $time[0]['client']){ echo $time[0]['client'];} 
        ?>"/>   
  <?php }
  else{
 ?><select  <?php 
                   //  if((isset($time_id)&&$time_id>0)||(isset($time[0]['status']) && $time[0]['status']==3)||(isset($_POST['from_proj'])&&$_POST['from_proj']==1)) echo ' onfocus="this.defaultIndex=this.selectedIndex;" onchange="this.selectedIndex=this.defaultIndex;" ';
                   
                     ?>   id="client" name="client" style="font-faimly:verdana;font-size:10;width:180px; height:25px; size:15px;">
    <option value="">-----------Select-----------</option>     
    <?php
									
					for ($i = 0; $i < count($data_client); $i++) {
    			echo '<option value="'.$data_client[$i]['client'].'" ';
    	if ((isset($time[0]['client']) && $time[0]['client'] == $data_client[$i]['client'])||(isset($time1['cid']) && $time1['cid'] == $data_client[$i]['ID']))
        			echo 'selected="selected" ';
    				echo '>' . $data_client[$i]['client'] . '</option>';
				}	
					?> 

  </select><?php }?></td>
                    
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                   </tr>   
     <?php

   if(!isset($_POST['add_new'])&& isset($time) && $time[0]['time_id'] > 0)
{
       
    $date_r=date('m/d/Y/h/i/a',$start_time);   

    $date_r2=date('m/d/Y/h/i/a',$end_time); 
    
     $dr=split('/',$date_r);

 $dr2=split('/',$date_r2);
    
       // echo 'ggg';
    //print_r($date_r2);
 //$dt=$start_time;
   }
   
 if((isset($time1['due_date'])&&$time1['due_date']!=''))
 {
  $date=date('m/d/Y/', $time1['due_date']); 
    if(isset($time1['st_time'])&&$time1['st_time']!='')
    {
 $dt1=split(' ',$time1['st_time']); 

     $dt2=split(':',$dt1[0]);
     $dr[3]=$dt2[0];
     $dr[4]=$dt2[1];
     $dr[5]=$dt1[1];  

    }
   // echo $date;
 }
 else
 {
  $date=date('m/d/Y/h/i/a', $dt);    
 }
 
 
 if((isset($time1['due_date'])&&$time1['due_date']!=''))
 {
  $date2=date('m/d/Y/h/i/a', strtotime('+1 day',$time1['due_date'])); 
 }
 else
 {
   $date2=date('m/d/Y/h/i/a', strtotime('+1 day',$dt));    
 }

 
 
 
 



 $d=split('/',$date);

 $d2=split('/',$date2);
 
?>                   
                  <tr>
                    <td height="30">Start Date & Time: </td>
    <td width="10"><div class="custom_time_picker"><table><tr><td>Yr</td><td>Mnt</td><td>Dy</td></tr>     
<tr>
<td><select class="cust_time_yr" onchange="changeTime($(this),'date');">         
<option><?php echo $d[2]; ?></option>
</select></td>
<td><select class="cust_time_mnt" onchange="changeTime($(this),'date');">
<option><?php echo $d[0]; ?></option>
</select></td>
<td><select class="cust_time_dy" id="start_day" onchange="changeTime($(this),'date');">
<option><?php echo $d[1]; ?></option>
</select></td>
</tr>
   <tr><td>Hr</td><td>Min</td><td>A/P</td></tr>      
   <tr>    <td><select id="start_hr" class="cust_time_hr" onchange="changeTime($(this),'date');">
 <?php  for($i=1;$i<=12;$i++){ ?>           
<option <?php if($i==$dr[3]) echo ' selected';?>><?php echo sprintf("%02s", $i); ?></option>
<?php } ?>
</select></td>
<td><select id="start_min" class="cust_time_min" onchange="changeTime($(this),'date');">
  <?php  for($i=0;$i<60;$i++){ ?>          
<option <?php if($i==$dr[4]) echo ' selected';?>><?php echo sprintf("%02s", $i); ?></option>
<?php } ?>
</select></td>
<td><select id="start_ap" class="cust_time_am" onchange="changeTime($(this),'date');">
<option <?php if('am'==strtolower($dr[5])) echo ' selected';?>>am</option>
<option <?php if('pm'==strtolower($dr[5])) echo ' selected';?>>pm</option>
</select>
</td></tr>
</table>
 <input style="display:none;" onchange="chng_cust_time_val($(this))" class="cust_time_text" id="start_date" type="text" class="textBox" readonly="readonly" value="<?php if(isset($start_time)){ echo date('m/d/Y h:i a', $start_time); } else{ echo date('m/d/Y',$dt);if(isset($time1['st_time'])) echo ' '.$time1['st_time'];else echo' 01:00 am'; }?>" />                           
</div></td>
                    <td><input type="hidden" name="start_date" id="hdn_start_date" value="<?php if(isset($start_time)){ echo date('m/d/Y h:i a', $start_time); } else{ echo date('m/d/Y',$dt).' 01:00 am'; } ?>" /></td>
                    <td width="10">&nbsp;</td>
                    <td>End Date & Time: </td>
                    <td width="10">&nbsp;</td>
                    <td><div class="custom_time_picker"><table><tr><td>Yr</td><td>Mnt</td><td>Dy</td></tr>     
<tr>
<td><select class="cust_time_yr" onchange="changeTime($(this),'date');">         
<option <?php if($dr2[2]==$d[2]) echo ' selected';?>><?php echo $d[2]; ?></option>
<?php if($d[2]!=$d2[2]){ ?>
<option <?php if($dr2[2]==$d2[2]) echo ' selected';?>><?php echo $d2[2]; ?></option>
<?php } ?>
</select></td>
<td><select class="cust_time_mnt" onchange="changeTime($(this),'date');">
<option <?php if($dr2[0]==$d[0]) echo ' selected';?>><?php echo $d[0]; ?></option>
<?php if($d[0]!=$d2[0]){ ?>
<option <?php if($dr2[0]==$d2[0]) echo ' selected';?>><?php echo $d2[0]; ?></option>
<?php } ?>
</select></td>
<td><select id="end_day" class="cust_time_dy" onchange="changeTime($(this),'date');">
<option <?php if($dr2[1]==$d[1]) echo ' selected';?>><?php echo $d[1]; ?></option>
<option <?php if($dr2[1]==$d2[1]) echo ' selected';?>><?php echo $d2[1]; ?></option>
</select></td>
</tr>
   <tr><td>Hr</td><td>Min</td><td>A/P</td></tr>      
   <tr>    <td><select id="end_hr" class="cust_time_hr" onchange="changeTime($(this),'date');">
 <?php  for($i=1;$i<=12;$i++){ ?>           
<option <?php if($i==$dr2[3]) echo ' selected';?>><?php echo sprintf("%02s", $i); ?></option>
<?php } ?>
</select></td>
<td><select id="end_min" class="cust_time_min" onchange="changeTime($(this),'date');">
  <?php  for($i=0;$i<60;$i++){ ?>          
<option <?php if($i==$dr2[4]) echo ' selected';?>><?php echo sprintf("%02s", $i); ?></option>
<?php } ?>
</select></td>
<td><select id="end_ap" class="cust_time_am" onchange="changeTime($(this),'date');">
<option <?php if('am'==$dr2[5]) echo ' selected';?>>am</option>
<option <?php if('pm'==$dr2[5]) echo ' selected';?>>pm</option>
</select>
</td></tr>
</table>
 <input style="display:none;" onchange="chng_cust_time_val($(this))" class="cust_time_text" id="end_date" type="text" class="textBox" readonly="readonly" value="<?php if(isset($end_time)){ echo date('m/d/Y h:i a', $end_time); } else{ echo date('m/d/Y',$dt).' 01:00 am'; }?>" />                           
</div>
<input type="hidden" id="hdn_end_date" name="end_date" value="<?php if(isset($end_time)){ echo date('m/d/Y h:i a', $end_time); } else{ echo date('m/d/Y',$dt).' 01:00 am'; } ?>" /></td>
                  </tr>
                  <tr id="break_1" <?php if(!isset($break1)){ echo ' class="tr_hide"';} ?>>
                    <td width="100" height="30"><span style="color:#CC0000;font-weight: bold;">* </span>1st Break: </td>
                    <td width="10"><input id="break1"  type="checkbox" <?php if(isset($break1) && $break1 > 0){ echo ' checked="checked" ';} ?> value="1" /></td>
                    <td>&nbsp;</td>
                    <td width="10">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="10">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  
               
         <tr id="lunch" <?php if(!isset($lunch_hrs) || $lunch_hrs <= 0){ echo ' class="tr_hide"';} ?>>            
             <td colspan="5">
<table width="100%">
    
    <tr>
               <td width="95" align="left"><span style="color:#CC0000;font-weight: bold;">* </span>Lunch:</td>
                    <td width="10"><input type="checkbox" id="lunch_chk" <?php if(isset($lunch_time) && $lunch_time > 0){ echo ' checked="checked" ';} ?> value="1"/></td>
             <td class="lunch_time" <?php if(isset($lunch_time) && $lunch_time > 0){ echo 'style="visibility:visible;"'; }else echo 'style="visibility:hidden;"';?>>
      <table width="100%"><tr><td>
             
  <div class="custom_time_picker"><table>
<tr><td>Yr</td><td>Mnt</td><td>Dy</td></tr> 
<?php  if(isset($lunch_start))
 {
 if(isset($l1)) unset($l1);   
 if(isset($l2)) unset($l2); 
 if(isset($l3)) unset($l3);     
 $l1=split(' ',$lunch_start);   
 $l2=split('/',$l1[0]);  
 $l3=split(':',$l1[1]);  
 } ?>
<tr>
<td><select class="cust_time_yr lunch_date_fields" onchange="changeTime($(this),'date');">         
<option <?php if($l2[2]==$d[2]) echo ' selected';?>><?php echo $d[2]; ?></option>
<?php if($d[2]!=$d2[2]){ ?>
<option <?php if($l2[2]==$d2[2]) echo ' selected';?>><?php echo $d2[2]; ?></option>
<?php } ?>
</select></td>
<td><select class="cust_time_mnt lunch_date_fields" onchange="changeTime($(this),'date');">
<option <?php if($l2[0]==$d[0]) echo ' selected';?>><?php echo $d[0]; ?></option>
<?php if($d[0]!=$d2[0]){ ?>
<option <?php if($l2[0]==$d2[0]) echo ' selected';?>><?php echo $d2[0]; ?></option>
<?php } ?>
</select></td>
<td><select  class="cust_time_dy lunch_date_fields" onchange="changeTime($(this),'date');">
<option <?php if($l2[1]==$d[1]) echo ' selected';?>><?php echo $d[1]; ?></option>
<option <?php if($l2[1]==$d2[1]) echo ' selected';?>><?php echo $d2[1]; ?></option>
</select></td>
</tr>          
   <tr><td>Hr</td><td>Min</td><td>A/P</td></tr>      
   <tr><td><select class="cust_time_hr" onchange="changeTime($(this),'date');">
 <?php  


 for($i=1;$i<=12;$i++){ ?>           
<option <?php if(isset($l3[0])&&$l3[0]==$i) echo ' selected';?>><?php echo sprintf("%02s", $i); ?></option>
<?php } ?>
</select></td>
<td><select class="cust_time_min" onchange="changeTime($(this),'date');">
  <?php  for($i=0;$i<60;$i++){ ?>          
<option <?php if(isset($l3[1])&&$l3[1]==$i) echo ' selected';?>><?php echo sprintf("%02s", $i); ?></option>
<?php } ?>
</select></td>
<td><select class="cust_time_am" onchange="changeTime($(this),'date');">
<option <?php if(isset($l1[2])&&$l1[2]=='am') echo ' selected';?>>am</option>
<option <?php if(isset($l1[2])&&$l1[2]=='pm') echo ' selected';?>>pm</option>
</select>
</td></tr>
</table>
 <input style="display:none;"  onchange="chng_cust_time_val($(this))" class="cust_time_text" id="lunch_st" name="lunch_start" type="text" class="textBox" readonly="readonly" value="<?php if(isset($lunch_start)){ echo $lunch_start; }else echo $d[0].'/'.$d[1].'/'.$d[2].' 01:00 am'; ?>" />                           
</div></td><td>  
    <div class="custom_time_picker"><table>
<tr><td>Yr</td><td>Mnt</td><td>Dy</td></tr> 
<?php  if(isset($lunch_start))
 {
 if(isset($l1)) unset($l1);   
 if(isset($l2)) unset($l2); 
 if(isset($l3)) unset($l3); 
 $l1=split(' ',$lunch_end);   
 $l2=split('/',$l1[0]);  
 $l3=split(':',$l1[1]);  
 } ?>
<tr>
<td><select class="cust_time_yr lunch_date_fields" onchange="changeTime($(this),'date');">         
<option <?php if($l2[2]==$d[2]) echo ' selected';?>><?php echo $d[2]; ?></option>
<?php if($d[2]!=$d2[2]){ ?>
<option <?php if($l2[2]==$d2[2]) echo ' selected';?>><?php echo $d2[2]; ?></option>
<?php } ?>
</select></td>
<td><select class="cust_time_mnt lunch_date_fields" onchange="changeTime($(this),'date');">
<option <?php if($l2[0]==$d[0]) echo ' selected';?>><?php echo $d[0]; ?></option>
<?php if($d[0]!=$d2[0]){ ?>
<option <?php if($l2[0]==$d2[0]) echo ' selected';?>><?php echo $d2[0]; ?></option>
<?php } ?>
</select></td>
<td><select  class="cust_time_dy lunch_date_fields" onchange="changeTime($(this),'date');">
<option <?php if($l2[1]==$d[1]) echo ' selected';?>><?php echo $d[1]; ?></option>
<option <?php if($l2[1]==$d2[1]) echo ' selected';?>><?php echo $d2[1]; ?></option>
</select></td>
</tr>            
   <tr><td>Hr</td><td>Min</td><td>A/P</td></tr>      
   <tr><td><select class="cust_time_hr" onchange="changeTime($(this),'date');">
 <?php  

 for($i=1;$i<=12;$i++){ ?>           
<option <?php if(isset($l3[0])&&$l3[0]==$i) echo ' selected';?>><?php echo sprintf("%02s", $i); ?></option>
<?php } ?>
</select></td>
<td><select class="cust_time_min" onchange="changeTime($(this),'date');">
  <?php  for($i=0;$i<60;$i++){ ?>          
<option <?php if(isset($l3[1])&&$l3[1]==$i) echo ' selected';?>><?php echo sprintf("%02s", $i); ?></option>
<?php } ?>
</select></td>
<td><select class="cust_time_am" onchange="changeTime($(this),'date');">
<option <?php if(isset($l1[2])&&$l1[2]=='am') echo ' selected';?>>am</option>
<option <?php if(isset($l1[2])&&$l1[2]=='pm') echo ' selected';?>>pm</option>
</select>
</td></tr>
</table>
 <input style="display:none;" onchange="chng_cust_time_val($(this))" class="cust_time_text" id="lunch_ed" name="lunch_end" type="text" class="textBox" readonly="readonly" value="<?php if(isset($lunch_end)){ echo $lunch_end; }else echo $d[0].'/'.$d[1].'/'.$d[2].' 01:00 am'; ?>" />                           
</div>                       
             </td></tr></table>
                      <input type="hidden" name="lunch_hrs" id="lunch_hrs" value="<?php if(isset($lunch_hrs)){ echo $lunch_hrs; } else { echo 0; } ?>" /></td>
                    <td width="10">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="10">&nbsp;</td>
                    <td>&nbsp;</td>    
</tr>
</table>                
             </td>    
                  
                  
                          </tr>          
                  <tr id="break_2" <?php if(!isset($break2)||$break2<=0){ echo ' class="tr_hide"';} ?>>
                    <td width="100" height="30"><span style="color:#CC0000;font-weight: bold;">* </span>2nd Break: </td>
                    <td width="10"><input id="break2"  type="checkbox" <?php if(isset($break2) && $break2 > 0){ echo ' checked="checked" ';} ?> value="1" /></td>
                    <td>&nbsp;</td>
                    <td width="10">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="10">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="30">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Hours Worked: </td>
                    <td>&nbsp;</td>
                    <td><input name="hours_worked" id="hrs_work" type="text" class="readonly" readonly="readonly" value="<?php if(isset($hours_worked)){ echo $hours_worked; } else { echo 0; } ?>" /></td>
                  </tr>
                  <tr>
                    <td height="30">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Reg. Hours: </td>
                    <td>&nbsp;</td>
                    <td><input name="reg_hours" id="reg_hrs" type="text" class="readonly" readonly="readonly" value="<?php if(isset($reg_hours)){ echo $reg_hours; } else { echo 0; } ?>" /></td>
                  </tr>
                  <tr>
                    <td height="30">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>O.T:</td>
                    <td>&nbsp;</td>
                    <td><input name="ot_hours" id="ot" type="text" class="readonly" readonly="readonly" value="<?php if(isset($ot_hours)){ echo $ot_hours; } else { echo 0; } ?>" /></td>
                  </tr>
  <tr>
                    <td height="30">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>D.T:</td>
                    <td>&nbsp;</td>
                    <td><input name="dt_hours" id="dt" type="text" class="readonly" readonly="readonly" value="<?php if(isset($dt_hours)){ echo $dt_hours; } else { echo 0; } ?>" /></td>
                  </tr>                 
                  <tr>
                    <td height="30" colspan="3"><strong>ODOMETER READING (Mandatory)</strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                <fieldset style="padding:10px;">
                  <h1>Expenditure Summary (Enter odometer reading daily)</h1>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="150" height="30">Beginning: </td>
                      <td width="10">&nbsp;</td>
                      <td><input name="odo_start" id="odo_start" type="text" class="textBox" value="<?php if(isset($start_read)){ echo $start_read; } else { echo 0; } ?>" /></td>
                      <td width="10">&nbsp;</td>
                      <td width="200">Ending: </td>
                      <td width="10">&nbsp;</td>
                      <td><input name="odo_end" id="odo_end" type="text" class="textBox" value="<?php if(isset($end_read)){ echo $end_read; } else { echo 0; } ?>" /></td>
                    </tr>
                    <tr>
                      <td height="30">Daily Total : </td>
                      <td>&nbsp;</td>
                      <td><input name="odo_daily" id="odo_daily" type="text" readonly="readonly" class="readonly" value="<?php if(isset($daily_total)){ echo $daily_total; } else { echo 0; } ?>" /></td>
                      <td>&nbsp;</td>
                      <td height="30">Reimbursable Miles : </td>
                      <td>&nbsp;</td>
                      <td><input name="odo_reim" id="odo_reim" type="text" readonly="readonly" class="readonly" value="<?php if(isset($daily_total)){ $daily_total-=30; if($daily_total<=0) $daily_total=0;echo $daily_total; } else { echo 0; } ?>" /></td>
                    </tr>
                    <tr>
                      <td width="150" height="30">Food: </td>
                      <td width="10">&nbsp;</td>
                      <td><input name="odo_food" id="odo_food" type="text" class="textBox" value="<?php if(isset($food)){ echo $food; } else { echo 0; } ?>" /></td>
                      <td>&nbsp;</td>
                      <td height="30">Expenses ($):</td>
                      <td>&nbsp;</td>
                      <td><input name="odo_mis_exp" id="odo_mis_exp" type="text" readonly="readonly" class="readonly" value="<?php if(isset($misc_exp)){ echo $misc_exp; } else { echo 0; }?>" /></td>
                   
                    </tr>
                    <tr>
                      <td width="150" height="30" align="left">Lodging: </td>
                      <td width="10">&nbsp;</td>
                      <td><input name="odo_lodging" id="odo_lodging" type="text" class="textBox" value="<?php if(isset($lodging)){ echo $lodging; } else { echo 0; } ?>" /></td>
                      <td width="10">&nbsp;</td>
                     </tr>  
                   
                      <tr>
                        <td width="150" height="30" align="left">Toll Fees: </td>
                        <td width="10">&nbsp;</td>
                        <td><input name="odo_toll_fees" id="odo_toll_fees" type="text" class="textBox" value="<?php if(isset($toll_fees)){ echo $toll_fees; } else { echo 0; } ?>" /></td>
                      <td width="10">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      
                      
                    </tr>
                    <tr>
                      <td width="150" height="30" align="left">Parking Fees: </td>
                      <td width="10">&nbsp;</td>
                      <td><input name="odo_park_fees" id="odo_park_fees" type="text" class="textBox" value="<?php if(isset($park_fees)){ echo $park_fees; } else { echo 0; } ?>" /></td>
                      <td width="10">&nbsp;</td>
                      </tr>
                      
                      <tr>
                        <td>Misc Expense :</td>
                        <td>&nbsp;</td>
                        <td><input name="odo_misc" id="odo_misc" type="text" class="textBox" value="<?php if(isset($other_exp)){ echo $other_exp; } else { echo ''; } ?>" /></td>
                      <td width="10">&nbsp;</td>
                      <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="150" height="30" align="left">Misc Expense Notes: </td>
                      <td width="10">&nbsp;</td>
                      <td><input name="misc_notes" type="text" class="textBox" value="<?php echo $misc_notes; ?>" /></td>
                      <td style="width:250px;"><span style="color:#CC0000; font-size:9px">*30 miles per day is deducted for commuting</span></td>
                      <td width="10">&nbsp;</td>
                      </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                </fieldset>
                <br />
              <br /></td>
            </tr>
          </table></td>
        <td width="20">&nbsp;</td>
      </tr>
    </table>
  </form>
  <p><strong style="font-style:italic;">I certify the hours and expenses shown here are correct and I understand they will be verified by Davis Merchandising Group LLC.</strong></p>
</div>
<script type="text/javascript">


	<?php if(isset($time['store']) && $time['store']==0) { ?>
	$(document).ready(function(){
	            $("#show_other").show();
				$("#sto_num_text").removeAttr("disabled");
				$("#sto_num_text").show();
				$("#store_num123").attr("disabled","disabled");
				$("#store_num123").hide();
	  });
	<?php } ?>
						 

function getstorenum(){	
        $('#store_num123').html('<option value="">----SELECT-----</option>');
        showLoading();
	data ='chain='+$("#store_name").val();
	    $.ajax({
		 type: "POST",
		 url: "<?php echo $base_url;?>content/timesheet/project_storenum.php",
		 data: data,

		 success: function(data) 
		 {
              hideLoading();       
        if(data==''||data==null) 
        $('#store_num123').html('<option value="">----SELECT-----</option>');     
        else $('#store_num123').html(data);
        // alert(data);
             
                 },
             error:function(){
              hideLoading();      
             }
		 });
			if ($("#store_name").val()==0&&$("#store_name").val()!='')   
			{
				$("#show_other").show();
				$("#sto_num_text").removeAttr("disabled");
				$("#sto_num_text").show();
				$("#store_num123").attr("disabled","disabled");
				$("#store_num123").hide();
				}
				else    
				{
				$("#show_other").hide();
				$("#sto_num_text").attr("disabled","disabled");
				$("#sto_num_text").hide();
				$("#store_num123").show();
				$("#store_num123").removeAttr("disabled");
				}
	}
	


$( "#sub_content" ).dialog({ title: "<?php echo $title; ?>" });
$( "#sub_content" ).dialog({ buttons: {"Agree and Save": function() {
showLoading();


data=$('#timesheet_form').serialize();
if(!$('#break_1').hasClass('tr_hide'))
    {
if($('#break1').attr('checked')=="checked")
data+='&break1=1';
else
 data+='&break1=2';   
    }
if(!$('#lunch').hasClass('tr_hide'))
    {    
if($('#lunch_chk').attr('checked')=="checked")
data+='&lunch_chk=1';
else
    {
        
 if(!confirm('Do you want to waive the lunch time?'))
    data+='&lunch_chk=2';
else data+='&lunch_chk=1&lunch_waive=1';
    }
    }
    
if(!$('#break_2').hasClass('tr_hide'))
    {  
if($('#break2').attr('checked')=="checked")
data+='&break2=1';
else
    data+='&break2=2';
    }
	
	if(!$('#lunch').hasClass('tr_hide'))
    {  
if($('#lunch_st').val()!='' && $('#lunch_ed').val()!='')
data+='&lunch_st=1';
else
    data+='&lunch_st=2';
    }

//alert(data);
$.ajax({type: 'POST',url: '<?php echo $base_url;?>/content/timesheet/timesheet_submit.php',data:data,dataType: 'json',success: function(data){
 if(data.error != ''){msg.html(data.error);showMessage('#C33');}else {msg.html(data.msg);showMessage('#390');sub_content.dialog( "close" );   
 <?php if($_POST['from_proj']==1||$_POST['from_report']==1) { ?>
     
     
 <?php }else{?>
 var date = $("#calendar").fullCalendar('getDate');
  var month_int = date.getMonth();    
  location.href='index.php?m='+month_int;
 <?php }?>
 } hideLoading();}});

	},
		Cancel: function() {
                <?php if($_POST['from_proj']==1||$_POST['from_report']==1) { ?>
     
     
 <?php }else{?>
                    calendar.fullCalendar('refetchEvents');
                 <?php } ?>   
			$( this ).dialog( "close" );
		}
	}
});



$('#lunch_chk').change(function(){
if($('#lunch_chk:checked').length > 0)
	$('.lunch_time').css({visibility:'visible'});
else
	$('.lunch_time').css({visibility:'hidden'});
});


$('#odo_start').change(function(){
 calculate_odo();
});
$('#odo_end').change(function(){
 calculate_odo();
});

$('#odo_food').change(function(){
 calculate_misc();
});
$('#odo_lodging').change(function(){
 calculate_misc();
});

$('#odo_toll_fees').change(function(){
 calculate_misc();
});
$('#odo_park_fees').change(function(){
 calculate_misc();
});
$('#odo_misc').change(function(){
 calculate_misc();
});
function showOther()
{	
if($('#client').val()=='other')
{
$('#client_td').css({visibility: "visible"});$('#client_input').val('');$('#client_input').focus();
}
else
{$('#client_td').css({visibility: "hidden"});$('#client_input').val($('#client').val());
}	   	
}

  function changeTime(obj,type) {
   //   alert('k'+obj.parents('.custom_time_picker').children('.cust_time_text').length);
   var time='';
if(type=='date')
    {   
time+=obj.parents('.custom_time_picker').find('.cust_time_mnt').val()+'/'+obj.parents('.custom_time_picker').find('.cust_time_dy').val()+'/'+
obj.parents('.custom_time_picker').find('.cust_time_yr').val()+' ';   

    }

time+=obj.parents('.custom_time_picker').find('.cust_time_hr').val()+':'+obj.parents('.custom_time_picker').find('.cust_time_min').val()+' '+
obj.parents('.custom_time_picker').find('.cust_time_am').val();
obj.parents('.custom_time_picker').children('.cust_time_text').val(time);
if($('#start_day').val()==$('#end_day').val())
    {        
   $('.lunch_date_fields').each(function(){
   $(this).find('option:eq(1)').hide(); 
   });
  
    }
    else
       {
    $('.lunch_date_fields').each(function(){
   $(this).find('option:eq(1)').show(); 
   });         
       }
//alert(time);
calculate_hrs($('#end_date').val());
//alert(obj.parents('.custom_time_picker').children('.cust_time_text').val());
$('div .message').hide();
      return false;
    }
    
 function changeSchedule()   
 {
 showLoading();     
var data='pid='+$('#pid').val();
 $.ajax({type: 'POST',
 url: '<?php echo $base_url;?>/content/timesheet/changeSchedule.php',
 data:data,
 dataType: 'html',
 success: function(data){
     
  hideLoading();
 $('#m_id').html(data).trigger('change');    
 },
 error:function()
 {
  hideLoading();    
 }
});        
 }
 
 function popUpProjFields()
 {
   showLoading();     
var data='m_id='+$('#m_id').val();
 $.ajax({type: 'POST',
 url: '<?php echo $base_url;?>/content/timesheet/popUpProjFields.php',
 data:data,
 dataType: 'json',
 success: function(res){
     hideLoading();
 $('#store_name').val(res.chain);    
 $('#store_num123').html(res.store_num_list);
 $('#store_num123').val(res.store_num);  
 $('#client').val(res.client); 
   $('#start_hr').val(res.start_hr); 
   $('#start_min').val(res.start_min); 
   $('#start_ap').val(res.start_ap); 
    $('#end_hr').val(res.start_hr); 
   $('#end_min').val(res.start_min); 
   $('#end_ap').val(res.start_ap);
 },
 error:function()
 {
  hideLoading();    
 }
});    
 }
</script>

<?php
} else {
?>
<div id="preview" width="100%">
      <?php $num=1;$add_new_show_flag=0;foreach($time as $val){
  extract($val); 
   if($status==3) $add_new_show_flag=1;  
  ?>                      
                 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="100" height="30">Date: </td>
                        <td width="10">&nbsp;</td>
                        <td style="font-size:12px;font-weight:bold;"><?php echo date('m/d/Y', $start_time); ?></td>
                        <td width="100" height="30" align="center"> Client: </td>
                        <td width="10">&nbsp;</td>
                        <td style="font-size:12px;font-weight:bold;"><?php echo($client); ?></td>
                        <td width="200">&nbsp;</td>
                    
                        <td width="100" height="30">Status: </td>
                        <td width="10">&nbsp;</td>
                        <td style="font-size:12px;font-weight:bold;"><?php echo $status_type[$status]; ?>&nbsp;&nbsp;</td>
              <?php if($status==1||$status==3){?>          
                      <td>&nbsp;&nbsp;&nbsp;<input type="button" value="Click Here to Enter Hours" style="cursor:pointer;"  onclick="javascript:loadTimesheet_edit(<?php echo $time_id; ?>);"/>&nbsp;&nbsp;&nbsp;</td>
                        <td><img style="cursor:pointer;" width="25px" height="25px" src="<?php echo $base_url;?>content/images/delete.png" onclick="javascript:loadTimesheet_del(<?php echo $time_id; ?>,<?php echo $dt;?>);"/></td>
                        <?php }else{?>
         <td>&nbsp;</td><td>&nbsp;</td>               
                        <?php }?>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        
                      </tr>
                    </table>
                      <table width="100%" border="0" cellspacing="1" cellpadding="0">
                      <tr>    
                       
                        <td height="30" align="left" valign="top" class="grid001">Store Name </td>
                        <td align="left" valign="top" class="grid001">Store#:</td>
                        <td align="left" valign="top" class="grid001">Company Rep </td>
                        <td align="left" valign="top" class="grid001">Start Time </td>
                        <td align="left" valign="top" class="grid001">End Time </td>
                        <td align="left" valign="top" class="grid001">Lunch Time</td>
                        <td align="left" valign="top" class="grid001">Break1</td>
                        <td align="left" valign="top" class="grid001">Break2</td>
                        <td align="left" valign="top" class="grid001">Lunch Start</td>
                        <td align="left" valign="top" class="grid001">Lunch End</td>
                        <td align="left" valign="top" class="grid001">Lunch Hours</td>
                        <td align="left" valign="top" class="grid001">Hours Worked </td>
                        <td align="left" valign="top" class="grid001">Reg Hours </td>
                        <td align="left" valign="top" class="grid001">O.T &nbsp;</td>
                        <td align="left" valign="top" class="grid001">D.T &nbsp;</td>
                   <!--     <td align="left" valign="top" class="grid001">Edit</td>
                        <td align="left" valign="top" class="grid001">Delete</td>-->
                        </tr>
  <tr>   
 
                        <td height="30"  align="left" valign="top" class="gridWhite"><?php if($store==0) { echo 'Other'; } else echo $chain; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php if($store==0) { echo $others; } else echo $store_num_val; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $company_rep; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo date('m/d/Y h:i a', $start_time); ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo date('m/d/Y h:i a', $end_time); ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo ($lunch_time)?'Yes':'No'; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo ($break1)?'Yes':'No'; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo ($break2)? 'Yes':'No'; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $lunch_start; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $lunch_end; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $lunch_hrs; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $hours_worked; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $reg_hours; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $ot_hours; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $dt_hours; ?></td>
                    <!--    <td align="left" valign="top" class="gridWhite"><?php //echo $reg_hours; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php //echo $ot_hours; ?></td>-->
                       </tr>  
                    
                    </table>
                      <br />
<br />
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	
		<h2>Expenditure Summary (Enter odometer reading daily)</h2>
	
	  <table width="100%" border="0" cellspacing="1" cellpadding="0">
              
      <tr>
        <td height="30" align="left" valign="top" class="grid001">Beginning&nbsp;</td>
        <td align="left" valign="top" class="grid001">&nbsp;Ending</td>
        <td align="left" valign="top" class="grid001">&nbsp;Daily Total</td>
        <td align="left" valign="top" class="grid001">&nbsp;Reimbursable Miles</td>
        <td align="left" valign="top" class="grid001">&nbsp;Gas Rec if not being paid Mileage</td>
        <td align="left" valign="top" class="grid001">&nbsp;Food</td>
          <td align="left" valign="top" class="grid001">&nbsp;Lodging</td>
          <td align="left" valign="top" class="grid001">&nbsp;Toll Fee</td>
          <td align="left" valign="top" class="grid001">&nbsp;Parking Fee</td>
          <td align="left" valign="top" class="grid001">&nbsp;Misc Expense</td>
        <td align="left" valign="top" class="grid001">&nbsp;Total Expenses</td>
        
        
      </tr>

      <tr>
        <td height="30" align="left" valign="top" class="gridWhite"><?php echo $start_read; ?></td>
        <td align="left" valign="top" class="gridWhite"><?php echo $end_read; ?></td>
        <td align="left" valign="top" class="gridWhite"><?php echo $daily_total; ?></td>
        <td align="left" valign="top" class="gridWhite"><?php $daily_total-=30;if($daily_total<=0)$daily_total=0; echo $daily_total; ?></td>
        <td align="left" valign="top" class="gridWhite"><?php echo $gas_rec; ?></td>
        <td align="left" valign="top" class="gridWhite"><?php echo $food; ?></td>
        <td align="left" valign="top" class="gridWhite"><?php echo $lodging; ?></td>
        <td align="left" valign="top" class="gridWhite"><?php echo $toll_fees; ?></td>
         <td align="left" valign="top" class="gridWhite"><?php echo $park_fees; ?></td>
              <td align="left" valign="top" class="gridWhite"><?php echo $other_exp; ?></td>
        <td align="left" valign="top" class="gridWhite"><?php echo $misc_exp; ?></td>
   
      </tr>
 
      <tr>
        <td height="30" align="left" valign="top" class="gridWhite2">&nbsp;</td>
        <td align="left" valign="top" class="gridWhite2">&nbsp;</td>
        <td align="left" valign="top" class="gridWhite2">&nbsp;</td>
        <td align="left" valign="top" class="gridWhite2">&nbsp;</td>
        <td align="left" valign="top" class="gridWhite2">&nbsp;</td>
        <td align="left" valign="top" class="gridWhite2">&nbsp;</td>
        <td align="left" valign="top" class="gridWhite2">&nbsp;</td>
      </tr>
    </table>	 
	  </td>
  </tr>
</table>
</td>
                  </tr>
                </table></td>
                <td width="20">&nbsp;</td>
              </tr>
             
   

            </table><br/>
    <?php }?>     
 
 <br/>

         <?php  if($_SESSION['perm_admin'] == "on"){
          // if($add_new_show_flag!=1){
             ////if(count($time)<2){?><input type="button" value="Add New" onclick="loadTimesheet_new('dt=<?php echo $dt;
         if(isset($_POST['from_prject'])&&$_POST['from_prject']==1) echo '&from_prject=1&m_id='.$_POST['m_id'];
         ?>');"/><?php // }
         //else echo '<b>Fill the hours to add new entry...</b>';
         }?>
</div>
<script type="text/javascript">
$( "#sub_content" ).dialog({ title: "<?php echo $title; ?>" });
$( "#sub_content" ).dialog({ buttons: { "Ok": function() { $(this).dialog("close"); } } });
</script>
<?php
}
?>