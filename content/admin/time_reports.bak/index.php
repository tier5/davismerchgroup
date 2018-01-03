<?php
$page = 'time_reports';
require 'Application.php';
$dt=date('U') ;
$sql = '';
$status_val = 0;
if(isset($_GET['reset']))
{
if(isset($_SESSION['timerep'])) unset($_SESSION['timerep']);?>
<script type="text/javascript">
    location.href="index.php";
</script>
<?php }
if(isset($_GET['delete']))
{
    $sql = "delete from dtbl_odometer where time_id=".$_GET['delete'].";delete from dtbl_timesheet where time_id=".$_GET['delete']; 
    if(!($result=pg_query($connection,$sql))){
		print("Failed query1: " . pg_last_error($connection));
		exit;
	}
	$sql ='';
	pg_free_result($result);
}

if(isset($_POST['action']))
{
	extract($_POST);
	if(isset($approve_x))
		$status_val = 2;
	else if(isset($reject_x))
		$status_val = 1;
		
	foreach ($action as $id)
	{
            if(isset($delete_x))
               $sql .= "delete from dtbl_odometer where time_id=".$id.";delete from dtbl_timesheet where time_id=".$id.";"; 
                else
		$sql .= " UPDATE dtbl_timesheet set status = $status_val where time_id=$id ;";
	}       
	if(!($result=pg_query($connection,$sql))){
		print("Failed query1: " . pg_last_error($connection));
		exit;
	}
	$sql ='';
	pg_free_result($result);
}
include $mydirectory.'/header.php';
include($mydirectory.'/pagination.class.php');
$search_sql=" emp_id > 0";
$limit="";
$search_uri="?t=1";
$status_arr = array('pending','reject','approved');
if(isset($_REQUEST['employee']))
{
$_SESSION['timerep']['employee']=$_REQUEST['employee'];
}
if(isset($_REQUEST['region']))
{
$_SESSION['timerep']['region']=$_REQUEST['region'];
}
if(isset($_REQUEST['store']))
{
$_SESSION['timerep']['store']=$_REQUEST['store'];
}
if(isset($_REQUEST['start_dt']))
{
$_SESSION['timerep']['start_dt']=$_REQUEST['start_dt'];
}
if(isset($_REQUEST['end_dt']))
{
$_SESSION['timerep']['end_dt']=$_REQUEST['end_dt'];
}
if(isset($_REQUEST['status']))
{
$_SESSION['timerep']['status']=$_REQUEST['status'];
}
if(isset($_SESSION['timerep']['employee']))
{
	if($_SESSION['timerep']['employee'] > 0)
	{
		$search_sql .= " and emp_id=".$_SESSION['timerep']['employee'];
		$search_uri .= '&employee='.$_SESSION['timerep']['employee'];
	}
}
if(isset($_SESSION['timerep']['region']))
{
	if($_SESSION['timerep']['region'] > 0)
	{
		$search_sql .= " and e.region=".$_SESSION['timerep']['region'];
		$search_uri .= '&region='.$_SESSION['timerep']['region'];
	}
}
if(isset($_SESSION['timerep']['store']))
{
	if($_SESSION['timerep']['store'] != '')
	{
		$search_sql .= " and store='".$_SESSION['timerep']['store']."'";
		$search_uri .= '&store='.$_SESSION['timerep']['store'];
	}
}
if(isset($_SESSION['timerep']['start_dt']))
{
    $st=split(' ',$_SESSION['timerep']['start_dt']);
	$start = strtotime($st[0]);
	if($start)
	{
		$search_sql .= " and start_time>='".strtotime($_SESSION['timerep']['start_dt'])."'";
		$search_uri .= '&start_dt='.$st[0];		
	}
       
}
if(isset($_SESSION['timerep']['end_dt']))
{
    $et=split(' ',$_SESSION['timerep']['end_dt']);
	$end = strtotime($et[0]);
	if($_SESSION['timerep']['start_dt'] != '' && $_SESSION['timerep']['start_dt'] == $_SESSION['timerep']['end_dt'])
		$end = strtotime('+1 day', $end);		
	if($end)
	{
		$search_sql .= " and start_time<='".strtotime($_SESSION['timerep']['end_dt'])."'";
		$search_uri .= '&end_dt='.$et[0];
	}
}
if(isset($_SESSION['timerep']['status']) && $_SESSION['timerep']['status'] != '')
{
	$search_sql .= " and t.status='".$_SESSION['timerep']['status']."'";
	$search_uri .= '&status='.$_SESSION['timerep']['status'];
}

$sql="Select store.city,store.sto_num as store_num_val, t.time_id,t.emp_id,t.store,t.other as others,ch.chain as ch, t.start_time, t.end_time, t.hours_worked,t.lunch_hrs, t.reg_hours, t.ot_hours, t.status, e.firstname as f, e.lastname as l,reg.region as r,e.city as location, odo.daily_total, odo.reimburse_mile, odo.gas_rec, odo.other_exp, odo.misc_exp, odo.misc_exp_type from dtbl_timesheet t ".
"left join \"tbl_chainmanagement\" as store on store.chain_id=t.store_num::bigint ".
"left join tbl_chain as ch on t.store  like cast(ch.ch_id as character varying)  left join \"employeeDB\" as e on e.\"employeeID\" = emp_id left join tbl_region as reg on reg.rid=e.region left join dtbl_odometer as odo on odo.time_id=t.time_id   where ".$search_sql." ORDER BY time_id DESC";
//echo $sql;
//echo ' <br/> '.date('m/d/Y h:i a','1395167400').'  '.date('m/d/Y h:i:a','1395167400');
if(!($result=pg_query($connection,$sql))){
	print("Failed query1: " . pg_last_error($connection));
	exit;
}
$items= pg_num_rows($result);
pg_free_result($result);
if($items > 0) {
	$p = new pagination;
	$p->items($items);
	$p->limit(10); // Limit entries per page
	//$uri=strstr($_SERVER['REQUEST_URI'], '&paging', true);
	//die($_SERVER['REQUEST_URI']);
	$uri= substr($_SERVER['REQUEST_URI'], 0,strpos($_SERVER['REQUEST_URI'], '&pg'));
	if(!$uri) {
		$uri=$_SERVER['REQUEST_URI'].$search_uri;
	}
	$p->target($uri);
	$p->currentPage($_GET[$p->pg]); // Gets and validates the current page
	$p->calculate(); // Calculates what to show
	$p->parameterName('pg');
	$p->adjacents(1); //No. of page away from the current page
	
	if(!isset($_GET['pg'])) {
	$p->page = 1;
	} else {
	$p->page = $_GET['pg'];
	}
	//Query for limit paging
	$limit = "LIMIT " . $p->limit." OFFSET ".($p->page - 1) * $p->limit;
}
$sql = $sql. " ". $limit;

//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("Failed query2 " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$time[]=$row;
}
pg_free_result($result);

//$sql = 'SELECT "employeeID" as id, firstname as f, lastname as l, company_rep FROM "employeeDB" WHERE active=\'yes\' order by lastname asc;';
$sql ='SELECT "employeeID" as id, firstname as f, lastname as l,  company_rep FROM "employeeDB" where active=\'yes\' and (emp_type = 0 OR emp_type is null)  ORDER BY firstname ASC ';
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("Failed query3: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$emp[]=$row;
}
pg_free_result($result);

$sql4 = 'Select * from "tbl_region" ORDER BY region ASC ';
if (!($result = pg_query($connection, $sql4))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row3 = pg_fetch_array($result)){
	$data_region[]=$row3;
}
pg_free_result($result);
?>
<div id="message" class="message_fixed"></div>
<div id="loading" class="message_fixed"><img src="../../images/loading.gif" alt="Loading..." /></div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><fieldset><legend><strong style="font-size:14px;">Search</strong></legend><form accept-charset="utf-8" name="search_frm" id="search_frm" method="post" action="index.php"><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="padding-left:20px;">
        <tr>
          <td width="200" height="30">Select Employee: </td>
          <td width="10">&nbsp;</td>
          <td width="200"><select name="employee" id="employee">
              <option value="all">Select All</option>
              <?php
			  foreach( $emp as $e){
				  if(isset($_SESSION['timerep']['employee']) && $_SESSION['timerep']['employee']==$e['id'])
				  	echo '<option selected="selected" value="'.$e['id'].'">'.$e['f'].' '.$e['l'].'</option>';
				  else
			  		echo '<option value="'.$e['id'].'">'.$e['f'].' '.$e['l'].'</option>';
			  }
			  ?>              
            </select></td>
          <td width="10">&nbsp;</td>
          <td>Status: </td>
          <td width="10">&nbsp;</td>
          <td ><select  name="status" id="status">
          <option <?php if(isset($_SESSION['timerep']['status']) && $_SESSION['timerep']['status']=='') echo 'selected="selected"'; ?> value="">-Select Status-</option>
              <option <?php if(isset($_SESSION['timerep']['status']) && $_SESSION['timerep']['status']==0 && $_SESSION['timerep']['status']!='') echo 'selected="selected"'; ?> value="0">Pending</option>
              <option <?php if(isset($_SESSION['timerep']['status']) && $_SESSION['timerep']['status']==1) echo 'selected="selected"'; ?> value="1">Rejected</option>
              <option <?php if(isset($_SESSION['timerep']['status']) && $_SESSION['timerep']['status']==2) echo 'selected="selected"'; ?> value="2">Approved</option>
               <option <?php if(isset($_SESSION['timerep']['status']) && $_SESSION['timerep']['status']==3) echo 'selected="selected"'; ?> value="3">Generated</option>
            </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Region:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select id="region"  name="region">
                                <option value="" selected="selected">--Select--</option>
                        <?php    for ($i = 0; $i < count($data_region); $i++) {
    			echo '<option value="'.$data_region[$i]['rid'].'" ';
    				if (isset($_SESSION['timerep']['region'] ) && $_SESSION['timerep']['region'] == $data_region[$i]['rid'])
        			echo 'selected="selected" ';
    				echo '>' . $data_region[$i]['region'] . '</option>';
				} ?>
                                </select></td>
       
        </tr>
        <tr>
          <td height="30">Start Date: </td>
          <td>&nbsp;</td>
          <td><input name="start_dt" type="text" value="<?php if(isset($_SESSION['timerep']['start_dt'])) echo $_SESSION['timerep']['start_dt']; ?>" id="start_dt"/></td>
          <td>&nbsp;</td>
          <td>End Date: </td>
          <td>&nbsp;</td>
          <td><input name="end_dt" type="text" value="<?php if(isset($_SESSION['timerep']['end_dt'])) echo $_SESSION['timerep']['end_dt']; ?>" id="end_dt"/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="30"><input class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" type="submit" name="search" value="Search" />
            <input type="reset" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="cancel" value="Cancel" onclick="javascript:location.href='index.php?reset=1'" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><input class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" onclick="javascript:summary();" type="button" value="View Summary Report" />
              <input class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" onclick="javascript:spreadSheet();" type="button" value="Generate Spreadsheet" />
    <input  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" onclick="javascript:payRoll();" type="button" value="Payroll Report" />           
     <input  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" onclick="javascript:clocking_report();" type="button" value="Clocking Report" />    
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
      </table>
      </form></fieldset>
      <br />
      <form action="index.php<?php echo $search_uri; ?>" method="post" name="action_frm" accept-charset="utf-8"><p>Please Select a record and click on the icons below to change the status of the record.</p><fieldset style="<?php if($_SESSION['perm_admin'] == "on") echo 'width:100px;'; else echo 'width:70px;';?>height:40px;"><legend style="font-weight:bold;">Action</legend>
      <div style="height:30px;">
 <table width="100%"><tr>        
    <td><span style="float:left;"><input  title="Approve" type="image" src="<?php echo $mydirectory.'/images/approved.png'; ?>" name="approve" /></span></td>
     <td><span style="float:right;"><input title="Reject" type="image" src="<?php echo $mydirectory.'/images/reject.png'; ?>" name="reject" />
       </span></td><?php if($_SESSION['perm_admin'] == "on"){ ?><td><span style="float:right;"><input onclick="return confirm('Do you really want to delete these time entry?');" title="Delete" style="width:25px;height:25px;" type="image" src="<?php echo $mydirectory.'/images/delete.png'; ?>" name="delete" /></span></td><?php }?>
     </tr>
     <tr><td>&nbsp;</td></tr>
 </table>
          </div>
     </fieldset>
<!-- <form accept-charset="utf-8" name="apr_frm" id="apr_frm" method="post" action="index.php">
 <input type="hidden" name="status" value="2"/>    
 <input class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all"  type="submit" value="Approved Items" /> 
 </form>-->
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td height="30" width="20px" align="left" valign="top" class="grid001">Select&nbsp;<input type="checkbox" class="checkbox" id="select_all" onclick="toggleChecked(this.checked)" /></td>
          <td height="30" align="left" valign="top" class="grid001">Employee</td>
          <td align="left" valign="top" class="grid001">Region</td>
          <td align="left" valign="top" class="grid001">Date</td>
          <td align="left" valign="top" class="grid001">Store Name </td>
          <td align="left" valign="top" class="grid001">Store # </td>
          <td align="left" valign="top" class="grid001">City/Location </td>
          <td align="left" valign="top" class="grid001">Lunch (hrs)</td>
          <td align="left" valign="top" class="grid001">Total hrs </td>
          <td align="left" valign="top" class="grid001">O.T</td>
          <td align="left" valign="top" class="grid001">Daily Miles</td>
          <td align="left" valign="top" class="grid001">Reimbursable Miles</td>         
          <td align="left" valign="top" class="grid001">Misc. Expense ($)</td>
          <td align="left" valign="top" class="grid001">Total Expense($)</td>
          <td align="left" valign="top" class="grid001">Status</td>
          <td align="left" valign="top" class="grid001">View</td>
    <?php  if($_SESSION['perm_admin'] == "on"||$_SESSION['perm_humanresources'] == "on"||$_SESSION['perm_timesheet'] == "on")
{ ?><td align="left" valign="top" class="grid001">Edit</td>
          <?php }?>
<?php if($_SESSION['perm_admin'] == "on"){ ?>
          <td align="left" valign="top" class="grid001">Delete</td>
          <?php }?>
        </tr>
        <?php
		if(isset($time)){
		foreach ($time as $t)
		{
			echo '<tr>';
			echo '<td align="center" valign="top" class="gridVal"><input type="checkbox" name="action[]" class="checkbox" value="'.$t['time_id'].'" /></td>';
			echo '<td height="30" align="left" valign="top" class="gridVal">'.$t['f'].' '.$t['l'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['r'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.date('m/d/Y',$t['start_time']).'</td>'; ?>
			<td align="left" valign="top" class="gridVal"><?php if($t['store']==0) { echo 'Other'; } else echo $t['ch']; ?> </td>
                        <td align="left" valign="top" class="gridVal"><?php if($t['store']==0) { echo $t['others']; } else echo $t['store_num_val']; ?></td>
           <?php  echo '<td align="left" valign="top" class="gridVal">'.$t['city'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['lunch_hrs'].'</td>';
            echo '<td align="left" valign="top" class="gridVal">'.$t['hours_worked'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['ot_hours'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['daily_total'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">';
                     $t['daily_total']-=30;   if($t['daily_total']<=0) $t['daily_total']=0;
                       echo $t['daily_total'].'</td>';			
			echo '<td align="left" valign="top" class="gridVal">'.$t['other_exp'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['misc_exp'].'</td>';
			echo '<td align="left" valign="top" class="gridVal" >';
    if($t['status']==3){
 echo 'Generated';       
    }                    
    echo  '<img style="padding-left:10px;" src="'.$mydirectory.'/images/'.$status_arr[$t['status']].'.png" alt="'.$status_arr[$t['status']].'" />';
                           echo '</td>';
            echo '<td align="left" valign="top" class="gridVal"><a href="javascript:preview('.$t['time_id'].');"><img src="../../images/view.png" alt="Preview" width="24" height="24" border="0" /></a></td>';       
if($_SESSION['perm_admin'] == "on"||$_SESSION['perm_humanresources'] == "on"||$_SESSION['perm_timesheet'] == "on"){
 echo '<td align="left" valign="top" class="gridVal"><a href="#" onclick="javascript:loadTimesheet(\'id='.$t['time_id'].'&emp_id='.$t['emp_id'].'\');" ><img title="Edit" src="../../images/edit.png" alt="Edit" width="24" height="24" border="0" /></a></td>';            
}
if($_SESSION['perm_admin'] == "on"){
 echo '<td align="left" valign="top" class="gridVal"><a href="./index.php?delete='.$t['time_id'].'" onclick="return confirm(\'Do you really want to delete this time entry?\');" ><img title="Delete" src="../../images/delete.png" alt="Preview" width="24" height="24" border="0" /></a></td>';   
}
           echo '</tr>';
		}
		 echo 	'<tr>
			<td width="100%" height="30" class="grid001" colspan="18">'.$p->show().'</td>			
		  </tr>';
		}
		else
		{
			echo '<tr><td colspan="17" height="30" class="grid001">No Time Record found</td><tr>';
		}
	   ?>       
      </table></form></td>
  </tr>
</table>
<div id="subpage"></div>
<div id="dialogue">
<div id='sub_content'></div>
</div>


<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>


<link rel="stylesheet" type="text/css" href="<?php echo $mydirectory;?>/css/jquery-ui-1.8.19.custom.css" />
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-timepicker-addon.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory;?>/js/timesheet.js'></script>
<script type="text/javascript">
    var from_proj=0;
var from_report=1;
var loading = $("#loading");
var msg = $("#message");
$('document').ready(function() {
    
$('#start_dt').datetimepicker({
  
	 ampm: true,
	//minDate: new Date(<?php echo $dt*1000;?>),
	minuteGrid: 15
	//maxDate: new Date(<?php echo strtotime('23 hour, 59 minutes',$dt)*1000;?>)
});
$('#end_dt').datetimepicker({
  
	 ampm: true,
	//minDate: new Date(<?php echo $dt*1000;?>),
	minuteGrid: 15
	//maxDate: new Date(<?php echo strtotime('23 hour, 59 minutes',$dt)*1000;?>)
});
/*$('#start_dt').datepicker({
	onClose: function(dateText, inst) {
        var endDateTextBox = $('#end_dt');
		var st = new Date(dateText);
        if (endDateTextBox.val() != '') {            
            var testEndDate = new Date(endDateTextBox.val());
            if (st > testEndDate){ endDateTextBox.val(dateText);}
		}else {endDateTextBox.val(dateText);}
    },
    onSelect: function (selectedDateTime){
        var start = $(this).datepicker('getDate');
        $('#end_dt').datepicker('option', 'minDate', new Date(start.getTime()));
	}});
$('#end_dt').datepicker();*/
});
function toggleChecked(status) {
$(".checkbox").each( function() {
$(this).attr("checked",status);
})
}
function showLoading(){loading .css({visibility:"visible"}) .css({opacity:"1"}) .css({display:"block"});msg .css({visibility:"hidden"})}
//hide loading bar
function hideLoading(){loading.fadeTo(1000, 0, function(){loading .css({display:"none"});});};
$('#subpage').dialog({
	autoOpen: false,
	width: 910,
	modal: true,
	show: "blind",
	hide: "explode"
});
function preview(id){
	dataString= 'id='+id;
  showLoading();
  $.ajax({type: 'POST',url: 'preview.php',data: dataString,dataType: 'html',success: function(html){
  $('#subpage').html(html);
  $('#subpage').dialog( "open" );
  hideLoading();
  }});
}
function summary(){
	dataString=  $('#search_frm').serialize();
  showLoading();
  $.ajax({type: 'POST',url: 'summary.php',data: dataString,dataType: 'html',success: function(html){
  $('#subpage').html(html);
  $('#subpage').dialog( "open" );
  hideLoading();
  }});
}

function spreadSheet()
{
  //showLoading();
  data='time_id='+$('#employee').val()+'&start_dt='+$('#start_dt').val()+'&end_dt='+$('#end_dt').val()+'&status='+$('#status').val()+'&region='+$('#region').val();
  window.open("genSpreadSheet.php?"+data,"_blank")
  /*$.ajax({type: 'POST',
  url: 'genSpreadSheet.php',
  data:data,
  dataType: 'json',
  
  success: function(data){
  hideLoading();    
  location.href='download.php?file='+data.fileName;
}
  });*/
}

function payRoll(rate){
dataString=  $('#search_frm').serialize();
       
  showLoading();
  $.ajax({type: 'POST',url: 'genPayRoll.php',data: dataString,dataType: 'html',success: function(html){
  $('#subpage').html(html);
  $('#subpage').dialog( "open" );
  hideLoading();
  }});
}

function clocking_report()
{
  data=$('#search_frm').serialize();  
  window.open("dwnldClckingReport.php?"+data,"_blank")  
}

function dwldExcel()
{

 // showLoading();
  data=$('#search_frm').serialize();
    window.open("dwnldPayRoll.php?"+data,"_blank")
 /* $.ajax({type: 'POST',
  url: 'dwnldPayRoll.php',
  data:data,
  dataType: 'json',
  
  success: function(data){
  hideLoading();    
  location.href='download.php?file='+data.fileName;
}
  });*/
}

 function loadTimesheet(dataString){
     
 dataString+='&from_report=1';
showLoading();
$.ajax({type: 'POST',url: '<?php echo $mydirectory;?>/timesheet/addtime.php',data: dataString,dataType: 'html',success: function(html){
sub_content.html(html);
hideLoading();
sub_content.dialog( "open" );

//var sp=dataString.split('=');
//initDatetimepicker(sp[1]);
}});
} 


    

</script>
<?php
include $mydirectory.'/trailer.php';
?>
