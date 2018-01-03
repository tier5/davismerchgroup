<?php
$page = 'time_reports';
require 'Application.php';

$sql = '';
$status_val = 0;

if(isset($_POST['action']))
{
	extract($_POST);
	if(isset($approve_x))
		$status_val = 2;
	else if(isset($reject_x))
		$status_val = 1;
		
	foreach ($action as $id)
	{
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
	if($_REQUEST['employee'] > 0)
	{
		$search_sql .= " and emp_id=".$_REQUEST['employee'];
		$search_uri .= '&employee='.$_REQUEST['employee'];
	}
}
if(isset($_REQUEST['store']))
{
	if($_REQUEST['store'] != '')
	{
		$search_sql .= " and store='".$_REQUEST['store']."'";
		$search_uri .= '&store='.$_REQUEST['store'];
	}
}
if(isset($_REQUEST['start_dt']))
{
	$start = strtotime($_REQUEST['start_dt']);
	if($start)
	{
		$search_sql .= " and start_time>='".$start."'";
		$search_uri .= '&start_dt='.$_REQUEST['start_dt'];		
	}
}
if(isset($_REQUEST['end_dt']))
{
	$end = strtotime($_REQUEST['end_dt']);
	if($_REQUEST['start_dt'] != '' && $_REQUEST['start_dt'] == $_REQUEST['end_dt'])
		$end = strtotime('+1 day', $end);		
	if($end)
	{
		$search_sql .= " and start_time<='".$end."'";
		$search_uri .= '&end_dt='.$_REQUEST['end_dt'];
	}
}
if(isset($_REQUEST['status']) && $_REQUEST['status'] != '')
{
	$search_sql .= " and status='".$_REQUEST['status']."'";
	$search_uri .= '&status='.$_REQUEST['status'];
}

$sql="Select t.time_id,t.store, t.start_time, t.end_time, t.hours_worked,t.lunch_hrs, t.reg_hours, t.ot_hours, t.status, e.firstname as f, e.lastname as l,odo.daily_total, odo.reimburse_mile, odo.gas_rec, odo.misc_exp, odo.misc_exp_type from dtbl_timesheet t left join \"employeeDB\" as e on e.\"employeeID\" = emp_id left join dtbl_odometer as odo on odo.time_id=t.time_id  where ".$search_sql." ORDER BY time_id DESC";
//echo $sql;
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

$sql = 'SELECT "employeeID" as id, firstname as f, lastname as l, company_rep FROM "employeeDB" WHERE active=\'yes\' order by lastname asc;';
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
              <option value="0">- Select Employee -</option>
              <option value="all">Select All</option>
              <?php
			  foreach( $emp as $e){
				  if(isset($_REQUEST['employee']) && $_REQUEST['employee']==$e['id'])
				  	echo '<option selected="selected" value="'.$e['id'].'">'.$e['l'].' '.$e['f'].'</option>';
				  else
			  		echo '<option value="'.$e['id'].'">'.$e['l'].' '.$e['f'].'</option>';
			  }
			  ?>              
            </select></td>
          <td width="10">&nbsp;</td>
          <td>Status: </td>
          <td width="10">&nbsp;</td>
          <td><select style="width:150px;" name="status">
          <option <?php if(isset($_REQUEST['status']) && $_REQUEST['status']=='') echo 'selected="selected"'; ?> value="">-Select Status-</option>
              <option <?php if(isset($_REQUEST['status']) && $_REQUEST['status']==0 && $_REQUEST['status']!='') echo 'selected="selected"'; ?> value="0">Pending</option>
              <option <?php if(isset($_REQUEST['status']) && $_REQUEST['status']==1) echo 'selected="selected"'; ?> value="1">Rejected</option>
              <option <?php if(isset($_REQUEST['status']) && $_REQUEST['status']==2) echo 'selected="selected"'; ?> value="2">Approved</option>
            </select></td>
        </tr>
        <tr>
          <td height="30">Start Date: </td>
          <td>&nbsp;</td>
          <td><input name="start_dt" type="text" value="<?php if(isset($_REQUEST['start_dt'])) echo $_REQUEST['start_dt']; ?>" id="start_dt"/></td>
          <td>&nbsp;</td>
          <td>End Date: </td>
          <td>&nbsp;</td>
          <td><input name="end_dt" type="text" value="<?php if(isset($_REQUEST['end_dt'])) echo $_REQUEST['end_dt']; ?>" id="end_dt"/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="30"><input class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" type="submit" name="search" value="Search" />
            <input type="reset" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="cancel" value="Cancel" onclick="javascript:location.href='index.php'" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><input class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" onclick="javascript:summary();" type="button" value="View Summary Report" />
              <input class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" onclick="javascript:spreadSheet();" type="button" value="Generate Spreadsheet" />
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
      <form action="index.php<?php echo $search_uri; ?>" method="post" name="action_frm" accept-charset="utf-8"><p>Please Select a record and click on the icons below to change the status of the record.</p><fieldset style="width:60px;height:40px;"><legend style="font-weight:bold;">Action</legend>
      <div style="height:30px;"><span style="float:left;"><input type="image" src="<?php echo $mydirectory.'/images/approved.png'; ?>" name="approve" /></span><span style="float:right;"><input type="image" src="<?php echo $mydirectory.'/images/reject.png'; ?>" name="reject" /></span>
     
          </div></fieldset>
<!-- <form accept-charset="utf-8" name="apr_frm" id="apr_frm" method="post" action="index.php">
 <input type="hidden" name="status" value="2"/>    
 <input class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all"  type="submit" value="Approved Items" /> 
 </form>-->
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td height="30" width="20px" align="left" valign="top" class="grid001">Select&nbsp;<input type="checkbox" class="checkbox" id="select_all" onclick="toggleChecked(this.checked)" /></td>
          <td height="30" align="left" valign="top" class="grid001">Employee</td>
          <td align="left" valign="top" class="grid001">Date</td>
          <td align="left" valign="top" class="grid001">Store Name </td>
          <td align="left" valign="top" class="grid001">Lunch (hrs)</td>
          <td align="left" valign="top" class="grid001">Total hrs </td>
          <td align="left" valign="top" class="grid001">O.T</td>
          <td align="left" valign="top" class="grid001">Daily Miles</td>
          <td align="left" valign="top" class="grid001">Reimbursable Miles</td>
          <td align="left" valign="top" class="grid001">Gas Receipt ($)</td>
          <td align="left" valign="top" class="grid001">Misc. Expense ($)</td>
          <td align="left" valign="top" class="grid001">Status</td>
          <td align="left" valign="top" class="grid001">View</td>
        </tr>
        <?php
		if(isset($time)){
		foreach ($time as $t)
		{
			echo '<tr>';
			echo '<td align="center" valign="top" class="gridVal"><input type="checkbox" name="action[]" class="checkbox" value="'.$t['time_id'].'" /></td>';
			echo '<td height="30" align="left" valign="top" class="gridVal">'.$t['l'].' '.$t['f'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.date('m/d/Y',$t['start_time']).'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['store'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['lunch_hrs'].'</td>';
            echo '<td align="left" valign="top" class="gridVal">'.$t['hours_worked'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['ot_hours'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['daily_total'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['reimburse_mile'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['gas_rec'].'</td>';
			echo '<td align="left" valign="top" class="gridVal">'.$t['misc_exp'].'</td>';
			echo '<td align="left" valign="top" class="gridVal" ><img style="padding-left:10px;" src="'.$mydirectory.'/images/'.$status_arr[$t['status']].'.png" alt="'.$status_arr[$t['status']].'" /></td>';
            echo '<td align="left" valign="top" class="gridVal"><a href="javascript:preview('.$t['time_id'].');"><img src="../../images/view.png" alt="Preview" width="24" height="24" border="0" /></a></td>';
           echo '</tr>';
		}
		 echo 	'<tr>
			<td width="100%" height="30" class="grid001" colspan="14">'.$p->show().'</td>			
		  </tr>';
		}
		else
		{
			echo '<tr><td colspan="14" height="30" class="grid001">No Time Record found</td><tr>';
		}
	   ?>       
      </table></form></td>
  </tr>
</table>
<div id="subpage"></div>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script>
<script type="text/javascript">
var loading = $("#loading");
var msg = $("#message");
$('document').ready(function() {
$('#start_dt').datepicker({
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
$('#end_dt').datepicker();
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
  showLoading();
  data='time_id='+$('#employee').val();
  $.ajax({type: 'POST',
  url: 'genSpreadSheet.php',
  data:data,
  dataType: 'json',
  
  success: function(data){
  hideLoading();    
  location.href='download.php?file='+data.fileName;
}
  });
}
</script>
<?php
include $mydirectory.'/trailer.php';
?>
