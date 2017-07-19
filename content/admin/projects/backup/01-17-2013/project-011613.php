<?php
require 'Application.php';
extract($_POST);
$client_sql = '';
if($is_client){
    $client_sql = ' AND "ID"='.$client_id;
}
$query  = ("SELECT \"ID\", \"clientID\", \"client\", \"active\" " .
        "FROM \"clientDB\" " .
        "WHERE \"active\" = 'yes' $client_sql " .
        "ORDER BY \"client\" ASC");
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
if (isset($_POST['pid']) && $_POST['pid'] > 0)
    $pid = $_POST['pid'];
if ($pid > 0)
{
    $query  = ("SELECT pid, cid, \"location\", prj_name,img_file, due_date, merch_1, merch_2, merch_3, notes FROM projects where pid = $pid");
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
   
    $merch_list=array();
   $sql = "SELECT m1.region,merch.location,merch.store_num,  str2.sto_num as str2_sto_num ,chain.chain,merch.m_id,str.sto_name,str.sto_num,merch.store_num,merch.st_time,merch.cid,merch.merch,merch.notes, merch.address, merch.phone, merch.city, merch.zip, merch.due_date,prj.prj_name as prjname, c.client, c.\"clientID\", m1.firstname as m1first, m1.lastname as m1last ";
        $sql .= " FROM projects as prj left join prj_merchants as merch on merch.pid=prj.pid  inner join \"clientDB\" as c on c.\"ID\" = merch.cid";
        $sql .= " left join tbl_store as str on str.sid=merch.location";
		$sql.=" left join tbl_region as reg on reg.rid=merch.region ";
          $sql .= " left join tbl_store as str2 on str2.sid=merch.store_num ";
		$sql.=" left join tbl_chain as chain on chain.ch_id=merch.location ";
        $sql .= " left join \"employeeDB\" as m1 on m1.\"employeeID\"=merch.merch  where prj.pid=" . $pid." order by merch.m_id desc";

        //echo $sql;

        if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
        while ($row = pg_fetch_array($result)) {
            $merch_list[] = $row;
        }
        pg_free_result($result);


}
$query = ("SELECT ch_id, chain from tbl_chain ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$data=array();
while ($row = pg_fetch_array($result)) {
    $data[] = $row;
}

$sql3 = 'Select * from "tbl_store" ORDER BY sto_num ASC ';
if (!($result = pg_query($connection, $sql3))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row3 = pg_fetch_array($result)){
	$data_store[]=$row3;
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
//$merch_count=count($merch_list);
 $script = "<script type='text/javascript'>\n$( '#due_date' ).datepicker(); $('#st_time').timepicker({ ampm: true, minuteGrid: 15});</script>";
?>
<div id="prj_msg" ></div>
<table width="95%" >
    <tr>
        <td>
            <table width="90%">
                <tr>
                    <td align="right">Project Name:</td>
                    <td><input type="text" class="textbox" id="prj_name" name="prj_name" value="<?php echo $project['prj_name'] ?>"/></td></tr>
                    
     <!--<tr id="u_file" <?php if($pid == 0) echo 'style="display:none;"' ?>>-->
       <tr>                 <td align="right" <?php if($pid == 0) echo 'style="display:none;"' ?>>Planogram:</td>"
                        <td align="left" <?php if($pid == 0) echo 'style="display:none;"' ?>><input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="plano" id="plano" onchange="javascript:ajaxFileUpload('plano','F', 960,720);" value="<?php echo stripslashes($datalist['file']); ?>"/></td>
 <td align="right" <?php if($pid == 0) echo 'style="display:none;"' ?>>Image Of Display:</td>
                        <td align="left" <?php if($pid == 0) echo 'style="display:none;"' ?>><input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="img" id="img" onchange="javascript:ajaxFileUpload('img','I', 960,720);" value="<?php echo stripslashes($datalist['file']); ?>"/></td>
                        
                         <td align="right" <?php if($pid == 0) echo 'style="display:none;"' ?>>Client Sign Off:</td>
                        <td align="left" <?php if($pid == 0) echo 'style="display:none;"' ?>><input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="cl_sign" id="cl_sign" onchange="javascript:ajaxFileUpload('cl_sign','F', 960,720);" value="<?php echo stripslashes($datalist['file']); ?>"/></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="88%" >
    <tr>
        <td style="width:150px;" class="grid001">Merchandiser </td>
        <td style="width:150px;" class="grid001">Region </td>
        <td style="width:60px;" class="grid001">Start Date</td>
        <td style="width:60px;" class="grid001">Start Time</td>
        <td style="width:60px;" class="grid001">Chain</td>
        <td style="width:60px;" class="grid001">Store#</td>
        <td style="width:150px;" class="grid001">Client</td>
        <td style="width:150px;" class="grid001">Notes</td>
        <td style="width:30px;"class="grid001">Edit</td>
        <td style="width:30px;" class="grid001">Delete</td>
    </tr>
    <tr><td colspan="10">
            <div  style="height:150px;overflow:auto;">
     <table width="100%" >       
  <?php 
  for($i=0;$i<count($merch_list);$i++)
  {
    echo '<tr ><td style="width:170px;padding-left: 10px;"  class="gridVal">' . $merch_list[$i]['m1first'] . ' ' . $merch_list[$i]['m1last'] .
         '<input type="hidden" name="merch[]" value="'.$merch_list[$i]['merch'].'"/></td>';
  echo '  <td style="width:170px;padding-left: 10px;" class="gridVal">' . $merch_list[$i]['region'] . '</td>';
    echo '   <td style="width:60px;padding-left: 10px;"  class="gridVal">';
             if(isset($merch_list[$i]['due_date'])&&$merch_list[$i]['due_date']!="") 
 echo date('m/d/Y',$merch_list[$i]['due_date']);

    echo '</td>';
    echo '  <td style="width:60px;padding-left: 10px;" class="gridVal">' . $merch_list[$i]['st_time'] . '</td>';
    echo ' <td style="width:60px;padding-left: 10px;"  class="gridVal">' . $merch_list[$i]['chain'] . '</td>';
     echo ' <td style="width:60px;padding-left: 10px;"  class="gridVal">' . $merch_list[$i]['str2_sto_num'] . '</td>';
    echo ' <td style="width:150px;padding-left: 10px;" width="150px" class="gridVal">' . $merch_list[$i]['client'] . '</td>';
    echo ' <td style="width:150px;padding-left: 10px;" width="150px" class="gridVal">' . $merch_list[$i]['notes'] . '</td>';
    echo '<td style="width:30px;padding-left: 10px;" width="30px" class="gridVal" ><img width="20" height="20" src="' . $mydirectory . '/images/edit.png" style="cursor:pointer;cursor:hand;" onclick="javascript:editMerchants(' . $merch_list[$i]['m_id'] . ')"/></td> ';
    echo '<td style="width:30px;padding-left: 10px;" width="60px" class="gridVal" ><img width="20" height="20" src="' . $mydirectory . '/images/1277880471_close.png" style="cursor:pointer;cursor:hand;" onclick="javascript:deleteMerchants(' . $merch_list[$i]['m_id'] . ',' . $pid . ')"/></td> </tr>';
}
  ?>
     </table> 
        </div>    
        </td></tr>           
</table>

<br/><br/>
<form action="project_submit.php" method="post" id="project_form">
    <input type="hidden" name="form_pid" id="pid" value="<?php echo $pid;?>"/>
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
                   if($employee[$i]['employeeID']==$merch_list[0]['merch'])
                   { //echo ' selected="selected" ';
                       }
                   
                  echo '>' . $employee[$i]['firstname'] . ' ' . $employee[$i]['lastname'] . '</option>';
                                   
                                }
                                ?>
                            </select>
   <input type="hidden" name="merch_id_hdn" id="merch_id_hdn" value="0"/>                         
                        </td>
                        <td>&nbsp;</td>
                        <td align="right">Region:</td>
                        <td><input type="text"  id="region1" name="region1" /></td>
                        <td align="right">&nbsp;</td><td>&nbsp;</td>
                        </tr>
                    <tr>
                        <td align="right">Start Date:</td><td><input size="15px" type="text" name="due_date" id="due_date"  value="<?php 
                        if(isset($merch_list[0]['due_date'])&&$merch_list[0]['due_date']!="")
                        echo date('m/d/Y',$merch_list[0]['due_date'] );
                        else echo date('m/d/Y');
                        ?>" /></td>
                        <td width="20px">&nbsp;</td>
                        <td align="right">Start Time</td><td><input type="text" size="10px" name="st_time" id="st_time"  value="<?php echo $merch_list[0]['st_time'] ?>" /></td>
                        
                    </tr>
                    <tr>
                        <td align="right">Chain:</td><td><select name="location" onchange="javascript:getstorenum();" id="location" style="width: 200px;" ><option value="0">--Select--</option>
                                <?php
                    for ($i = 0; $i < count($data); $i++) {
                        echo '<option value="' . $data[$i]['ch_id'] . '"';
                         if($data[$i]['ch_id']==$merch_list[0]['location'])
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
                    if($client[$i]['ID'] ==$merch_list[0]['cid'])
                    echo ' selected="selected" ';
                                        echo '>' . $client[$i]['client'] . '</option>';
                                    
                                }
                                ?>
                            </select></td>
                        
                    </tr>
                    
                      <tr>
                        <td align="right">Store#:</td>
                         <td><select name="store_num" id="store_num" onchange="javascript:get_contact();">
                         <option value="">----SELECT----</option>  
                         <?php
                    for ($i = 0; $i < count($data_store); $i++) {
                        echo '<option value="' . $data_store[$i]['sid'] . '"';
                         if($data_store[$i]['sid']==$merch_list[0]['store_num'])
                    echo ' selected="selected" ';
                        echo '>' . $data_store[$i]['sto_num'] . '</option>';
                    }
                    ?>                
                    
                </select>
            </td>
                        
                        
                        
                    </tr>
                    <tr>
                        <td align="right">Address:</td>
                        <td><input type="text" readonly="readonly" name="address" id="address" style="width:275px;" value="<?php echo $merch_list[0]['address'];?>"   /></td>
                        <td align="right">Phone:</td>
                        <td><input type="text" id="phone" readonly="readonly" name="phone" value="<?php echo $merch_list[0]['phone'];?>"  /></td>
                    </tr>
                    <tr>
                        <td align="right">City:</td>
                        <td><input type="text" readonly="readonly" name="city" id="city" value="<?php echo $merch_list[0]['city'];?>"   /></td>
                        <td align="right">Zip:</td>
                        <td><input type="text" id="zip" readonly="readonly" name="zip" value="<?php echo $merch_list[0]['zip'];?>"  /></td>
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
                        <td align="right">Notes:</td><td colspan="4"><textarea name="notes" id="notes" cols="40" rows="7"><?php echo $merch_list[0]['notes'];?></textarea></td>
                        
                    </tr>                     
                </table>
            </fieldset>
        </td>
    </tr>
    <tr>
        <td colspan="5" align="left" valign="top">
            <input type="button" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" id="submitButton" name="submitButton" value="Save" onclick="javascript: submit_form();"/>
            <input type="button" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" value="Reset" onclick="javascript:resetForm()" />
        </td>
    </tr>
</table>
</form>
<div  style="height:150px;overflow:auto;">
<table width="95%" >
 <tr><td colspan="8" ><div  width="97%" style="overflow-y: auto;overflow-x: hidden;" id="file_view">
                </div></td></tr>  
</table></div>
<?php echo $script; ?>
