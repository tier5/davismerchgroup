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



$sql3 = 'Select * from "project_main" ORDER BY m_pid DESC';

if (!($result = pg_query($connection, $sql3))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
 
while($row3 = pg_fetch_array($result)){
	$data_proj[]=$row3;
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
    $query  = ("SELECT pid, cid,m_pid,num_merch, \"location\", prj_name,img_file, due_date, merch_1, merch_2, merch_3, notes FROM projects where pid = $pid");
    
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
    
//print_r($merch_list);
    //print_r($project);
}


//$merch_count=count($merch_list);
 
?>
<div id="prj_msg" ></div>

<table width="95%" >
    <tr>
        <td>
            <table width="90%">
                <tr>
     <td align="right" >Project:</td>
         <td><select name="m_pid" id="m_pid">
        <?php
        foreach($data_proj as $proj)
        { ?>
             <option <?php if($proj['m_pid']==$project['m_pid']||$proj['m_pid']==$_POST['m_pid']) echo ' selected ';?> value="<?php echo $proj['m_pid'];?>"><?php echo $proj['name'];?></option>         
       <?php }
        ?>
         </select>
     </td>

                    <td align="right" <?php if($project['prj_name']=='') echo ' style="display:none;"';?>>Job ID#:</td>
<td valign="top">
    <input  readonly type="text" class="textbox" id="prj_name" name="prj_name" <?php if($project['prj_name']!='')echo ' value="'.$project['prj_name'].'"'; 
    else echo ' value="uu" style="display:none;"';?> />
<input type="button" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" id="submitButton" name="submitButton" value="Save" onclick="javascript: submit_form('proj');"/>                
                    </td></tr>
      
   
                    
  
<tr><td>&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>

   <!--<tr id="u_file" <?php if($pid == 0) echo 'style="display:none;"' ?>>-->
       <tr>                 <td align="right" <?php if($pid == 0) echo 'style="display:none;"' ?>>Planogram:</td>
                        <td align="left" <?php if($pid == 0) echo 'style="display:none;"' ?>><input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="plano" id="plano" onchange="javascript:ajaxFileUpload('plano','F', 960,720);" value="<?php echo stripslashes($datalist['file']); ?>"/></td>
 <td align="right" <?php if($pid == 0) echo 'style="display:none;"' ?>>Image Of Display:</td>
                        <td align="left" <?php if($pid == 0) echo 'style="display:none;"' ?>><input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="img" id="img" onchange="javascript:ajaxFileUpload('img','I', 960,720);" value="<?php echo stripslashes($datalist['file']); ?>"/></td>
                        
                          <td align="left">&nbsp;</td>
                        <td align="left">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
   
</table>






<br/><br/>

<div  >
<table width="95%" >
 <tr><td colspan="8" ><div  width="97%"  id="file_view">
 <?php $pid=$_REQUEST['pid'];
  ob_start();
 include 'get_project_files.php';   
 ob_get_clean();
 ?>     
                </div></td></tr>  
</table></div>


