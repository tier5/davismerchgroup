<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
if(!isset($_REQUEST['pid'])||$_REQUEST['pid']=='')
{
echo '<br/><h3>Please save the project first...</h3>';
exit();
}
extract($_POST);

$cl_list=array();
$query="select cl.client from projects as prj left join project_main as main on main.m_pid=prj.m_pid".
 " left join prj_signoff_clients as cl on cl.pid=main.m_pid where prj.pid=".$pid;
$result=pg_query($connection, $query);
while ($row = pg_fetch_array($result))
{
    $cl_list[] = $row;
}
pg_free_result($result);
$client_sql='';
foreach($cl_list as $cl)
{
if($cl['client']>0){}else continue;    
if($client_sql!='') $client_sql.=' OR ';    
  $client_sql .= ' "ID"='.$cl['client'];  
}
$client_sql=' AND ('.$client_sql.') ';
$client_sql='';

if($is_client){
    $client_sql = ' AND "ID"='.$client_id;
}
$query  = ("SELECT \"ID\", \"clientID\", \"client\", \"active\" " .
        "FROM \"clientDB\" " .
        "WHERE \"active\" = 'yes' $client_sql " .
        "ORDER BY \"client\" ASC");
//echo $query;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result))
{
    $client[] = $row;
}
pg_free_result($result);




$query  = ("SELECT \"employeeID\", firstname, lastname FROM \"employeeDB\" where active='yes' and (emp_type = 0 OR emp_type is null)  ORDER BY firstname ASC;");
//echo $query;
if (!($result = pg_query($connection, $query)))
{
    print("Failed employee query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result))
{
    $employee[] = $row;
}
pg_free_result($result);


$query1=("SELECT chain, status FROM tbl_chain WHERE status = '1' ");
if(!($result1=pg_query($connection,$query1))){
	print("Failed query1: " . pg_last_error($connection));
	exit;
}
while($row1 = pg_fetch_array($result1)){
	$data1[]=$row1;
}


$pid = 0;
if (isset($_REQUEST['pid']) && $_REQUEST['pid'] > 0)
    $pid = $_REQUEST['pid'];

if ($pid > 0)
{
    $query  = ("SELECT pid, cid, \"location\", prj_name,img_file, due_date, merch_1, merch_2, merch_3, notes FROM projects$ext where pid = $pid");
    
   // echo $query;
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed project query: " . pg_last_error($connection));
        exit;
    }
    while ($row = pg_fetch_array($result))
    {
        $project = $row;
    }
    pg_free_result($result);
    unset($row);
   
 

//print_r($merch_list);
}
$query = ("SELECT ch_id, chain from tbl_chain order by chain asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$data=array();
while ($row = pg_fetch_array($result)) {
    $data[] = $row;
}

$sql4 = 'Select * from "tbl_region" ORDER BY region ASC ';
if (!($result = pg_query($connection, $sql4))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row3 = pg_fetch_array($result)){
	$data_region[]=$row3;
}
pg_free_result($result);


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
?>
<div id="prj_msg" ></div>
<table width="95%" >
  <tr>
     <td align="left" width="200px" ># of merchandisers assigned:</td>
     <td align="left" width="100px"  ><input style="width:100px;" type="text" name="num_merch" id="num_merch" value="<?php echo $num_merch;?>"/></td>
<td>
<!--<input type="button" align="left" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" id="submitButton" name="submitButton" value="Save" onclick="javascript: submit_merch_num(<?php echo $pid;?>);"/>-->      
</td>    
            </tr>
               
</table>

<?php  $f_prj_2=1; require 'merch_list.php'; 

$merch_count=count($merch_list);
if($merch_count>0)
$merch_count-=1;
else $merch_count=0;

$data_store=array();
$sq_3='';
if(isset($merch_list[$merch_count]['location'])) {$sq_3=' where sto_name='.$merch_list[$merch_count]['location'];
$sql3 = 'Select * from "tbl_chainmanagement" '.$sq_3.' ORDER BY sto_num ASC ';
if (!($result = pg_query($connection, $sql3))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row3 = pg_fetch_array($result)){
	$data_store[]=$row3;
}
}

$query='select cl.* from "prj_signoff_clients" as cl left join projects'.$ext.' as prj on prj.m_pid=cl.pid where prj.pid='.$_REQUEST['pid'];
//echo $query;
$result = pg_query($connection, $query);
$frm_lst=array();
while($frm_lst[]=pg_fetch_array($result));
//print_r($frm_lst);
//echo 'cnt--'.count($frm_lst);
 if($frm_lst[0]['frm_name']=='all')
{
  $frm_lst[]=array('frm_name'=>'dmg_form');  
  $frm_lst[]=array('frm_name'=>'dmg_convenience_form');  
  $frm_lst[]=array('frm_name'=>'stater_bros_form');  
  $frm_lst[]=array('frm_name'=>'frito_lay_rest_form');  
  $frm_lst[]=array('frm_name'=>'pizza_form');  
  $frm_lst[]=array('frm_name'=>'ralphs_reset');  
  $frm_lst[]=array('frm_name'=>'ralphs_checklist');  
}
$frmname_lst=array('dmg_form'=>'DMG Chain Form','dmg_convenience_form'=>'DMG Convenience Form','stater_bros_form'=>'Grocery Form',
    'frito_lay_rest_form'=>'Frito-Lay Form','pizza_form'=>'Nestle Form','ralphs_reset'=>'Ralphs Reset Form','ralphs_checklist'=>'Ralphs Daily Checklist Form');


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
if(count($sign_merch_list)>0)
{
$edit_signoff=1;    
}
 else
{
$edit_signoff=0;    
}
?>
<br/>
<?php if(!isset($is_client)||$is_client!=1) {?>
<form action="project_submit.php" method="post" id="project_form">
    <input type="hidden" name="form_pid" id="pid" value="<?php echo $pid;?>"/>
    <input style="width:100px;" type="hidden" name="num_merch"  value="<?php echo $num_merch;?>"/>
    <?php if($_SESSION['perm_admin'] == "on"){?>
    Sign&nbspOff&nbspedit&nbsppermission:&nbsp<select  id="signmerch_add_sel" >
          <?php  for ($i = 0; $i < count($employee); $i++)
                                {
                                   
                   echo '<option value="' . $employee[$i]['employeeID'] . '"';
                   if($employee[$i]['employeeID']==$merch_list[$merch_count]['merch'])
                   { echo ' selected="selected" ';
                       }
                   
                  echo '>' . $employee[$i]['firstname'] . ' ' . $employee[$i]['lastname'] . '</option>';
                                   
                            
     }?>
         
     </select>&nbsp;<img width="20" height="20" src="<?php echo $mydirectory;?>/images/add.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:addnewsignmerch();"/>    
     <br/>
     <table id="signmerch_add_block">
<?php if(count($sign_merch_list)>0){ foreach($sign_merch_list as $v)
{
echo '<tr><td style="height:30px;"><input type="hidden" value="'.$v['emp_id'].'" name="signmerch_off_arr[]">
<input type="hidden" value="'.$v['id'].'" name="signmerch_id_arr[]">'.$v['firstname'].' '.$v['lastname']
.'<img width="20" height="20" src="'.$mydirectory.'/images/delete.png" style="cursor:pointer;cursor:hand;" onclick="deletesignmerch(\''.$v['id'].'\');$(this).parent().parent().remove();" />'         
.'</td></tr>'; }?>    
       
     </table
     <?php }} ?>
 <br/><br/>    
<table width="95%">

    <tr>
        <td>
            <fieldset style="border: 1px solid #333333;" width="80%"><legend><strong>Add Merchandiser</strong></legend>
                <table width="80%">
                    <tr>
                        <td align="right">Merchandiser:</td><td><select name="merch_1" id="merch_1" style="width: 200px;" onchange="javascript:get_region();"><option value="0">--Select--</option>
                                <?php
                                
                                for ($i = 0; $i < count($employee); $i++)
                                {
                                   
                   echo '<option value="' . $employee[$i]['employeeID'] . '"';
                   if($employee[$i]['employeeID']==$merch_list[$merch_count]['merch'])
                   { echo ' selected="selected" ';
                       }
                   
                  echo '>' . $employee[$i]['firstname'] . ' ' . $employee[$i]['lastname'] . '</option>';
                                   
                                }
                                ?>
                            </select>
   <input type="hidden" name="merch_id_hdn" id="merch_id_hdn" value="0"/>                         
                        </td>
                        <td>&nbsp;</td>
                        <td align="right">Region:</td>
                        <td>
                            <select id="region1"  name="region1">
                                <option value="" selected="selected">--Select--</option>
                        <?php    for ($i = 0; $i < count($data_region); $i++) {
    			echo '<option value="'.$data_region[$i]['rid'].'" ';
    				if (isset($merch_list[$merch_count]['region'] ) && $merch_list[$merch_count]['region'] == $data_region[$i]['region'])
        			echo 'selected="selected" ';
    				echo '>' . $data_region[$i]['region'] . '</option>';
				} ?>
                                </select>
                           </td>
                        <td align="right">&nbsp;</td><td>&nbsp;</td>
                        </tr>
                    <tr>
                        <td align="right">Start Date:</td><td><input size="15px" type="text" name="due_date" id="due_date" class="date_field"  value="<?php 
                        if(isset($merch_list[$merch_count]['due_date'])&&$merch_list[$merch_count]['due_date']!="")
                        echo date('m/d/Y',$merch_list[$merch_count]['due_date'] );
                        else echo date('m/d/Y');
                        ?>" /></td>
                        <td width="20px">&nbsp;</td>
                        <td align="right">Start Time</td><td><input type="text" size="10px" name="st_time" id="st_time"  value="<?php echo $merch_list[0]['st_time'] ?>" />
                        <input type="hidden" size="10px"  id="st_time_hdn"  value="<?php if($merch_list[$merch_count]['st_time']!='')echo $merch_list[$merch_count]['st_time'];
                        else echo '01:00 AM';?>" />
                        </td>
                        
                    </tr>
                    <tr>
                        <td align="right">Chain:</td><td><select name="location" onchange="javascript:getProjectstorenum();" id="location" style="width: 200px;" ><option value="0" selected>--Select--</option>
                                <?php
                    for ($i = 0; $i < count($data); $i++) {
                        echo '<option value="' . $data[$i]['ch_id'] . '"';
                         if($data[$i]['ch_id']==$merch_list[$merch_count]['location'])
                    echo ' selected="selected" ';
                        echo '>' . $data[$i]['chain'] . '</option>';
                    }
                    ?> 
                            </select></td>
                        <td width="20px">&nbsp;</td>
                        <td align="right">Client:</td><td><select name="cid" id="cid" style="width: 200px;">
                                <?php
                                $c_index = -1;
                                if ($pid == 0)
                                    $c_index = 0;
                                for ($i = 0; $i < count($client); $i++)
                                {
                                   
                                        echo '<option value="' . $client[$i]['ID'] . '"';
                    if($client[$i]['ID'] ==$merch_list[$merch_count]['cid'])
                    echo ' selected="selected" ';
                                        echo '>' . $client[$i]['client'] . '</option>';
                                    
                                }
                                ?>
                            </select></td>
                        
                    </tr>
                    
                      <tr>
                        <td align="right">Store#:</td>
                         <td><select name="store_num" id="store_num" onchange="javascript:get_projectcontact();">
                         <option value="">----SELECT----</option>  
                         <?php
                    for ($i = 0; $i < count($data_store); $i++) {
                        echo '<option value="' . $data_store[$i]['chain_id'] . '"';
                         if($data_store[$i]['chain_id']==$merch_list[$merch_count]['store_num'])
                    echo ' selected="selected" ';
                        echo '>' . $data_store[$i]['sto_num'] . '</option>';
                    }
                    ?>                
                    
                </select>
            </td>
                        
                        
                        
                    </tr>
                    <tr>
                        <td align="right">Address:</td>
                        <td><input type="text" readonly="readonly" name="address" id="address" style="width:275px;" value="<?php echo $merch_list[$merch_count]['address'];?>"   /></td>
                        <td align="right">Phone:</td>
                        <td><input type="text" id="phone" readonly="readonly" name="phone" value="<?php echo $merch_list[$merch_count]['phone'];?>"  /></td>
                    </tr>
                    <tr>
                        <td align="right">City:</td>
                        <td><input type="text" readonly="readonly" name="city" id="city" value="<?php echo $merch_list[$merch_count]['city'];?>"   /></td>
                        <td align="right">Zip:</td>
                        <td><input type="text" id="zip" readonly="readonly" name="zip" value="<?php echo $merch_list[$merch_count]['zip'];?>"  /></td>
                    </tr>
                    
                  <?php /*?><tr>
                  <td align="right">Region:</td>
                  <td><select name="region">
                  <?php
                    for ($i = 0; $i < count($data_region); $i++) {
                        echo '<option value="' . $data_region[$i]['rid'] . '"';
                         if($data_region[$i]['rid']==$merch_list[0]['region'])
                    echo ' selected="selected" ';
                        echo '>' . $data_region[$i]['region'] . '</option>';
                    }
                    ?>     
                  </select></td>
                  </tr><?php */?>
                    <tr>
                        <td align="right">Notes:</td><td colspan="2"><textarea name="notes" id="notes" cols="40" rows="7"><?php echo $merch_list[$merch_count]['notes'];?></textarea></td>
    <td colspan="4">
       <?php if($_SESSION['perm_admin'] == "on"){?>   
       <br/><br/> 
        Sign&nbspOff&nbspForm&nbsp:&nbsp<select  id="sign_off_add_sel" >
            <option value="">--Select--</option> 
         <?php foreach($frm_lst as $frm){ if($frm=='' || $frmname_lst[$frm['frm_name']]=='') continue;?>
         <option value="<?php echo $frm['frm_name'];?>.php"><?php echo $frmname_lst[$frm['frm_name']];?></option>    
       <?php }?>
         
     </select>&nbsp;<img width="20" height="20" src="<?php echo $mydirectory;?>/images/add.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:addnewSignForm();"/>    
     <br/>
     <table id="sign_off_add_block">
     </table>   
     
     <?php }?>   
    </td>                    
                        
                    </tr>                     
                </table>
            </fieldset>
        </td>
    </tr>
 <?php    if($_SESSION['emp_type']==0&&($_SESSION['perm_admin'] == "on"||$_SESSION['perm_manager'] == "on")) {  ?>
    <tr>
        <td colspan="5" align="left" valign="top">
            <input type="button" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" id="submitButton" name="submitButton" value="Save" onclick="javascript: submit_form('merch');"/>
            <input type="button" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" value="Reset" onclick="javascript:resetForm()" />
           <input type="button" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" value="New Job" onclick="javascript:newJob()" />
        </td>
    </tr>
     <?php } ?>
</table>
</form>
<?php }?>
<div  style="height:150px;overflow:auto;">
<table width="95%" >
 <tr><td colspan="8" ><div  width="97%" style="overflow-y: auto;overflow-x: hidden;" id="file_view">
                </div></td></tr>  
</table></div>


