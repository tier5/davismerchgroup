<?php
require 'Application.php';
$time_id = 0;

$search_sql=" status = 2";
$status_arr = array('pending','reject','approved');

if(isset($_POST['employee']) && $_POST['employee'] > 0)
{
	$search_sql .= " and emp_id=".$_POST['employee'];
	if(isset($_POST['store']))
	{
		if($_POST['store'] != '')
		{
			$search_sql .= " and store='".$_POST['store']."'";
		}
	}
	if(isset($_POST['start_dt']))
	{
		$start = strtotime($_POST['start_dt']);
		if($start)
		{
			$search_sql .= " and start_time>='".$start."'";	
		}
	}
	if(isset($_POST['end_dt']))
	{
		$end = strtotime($_POST['end_dt']);
		if(($_POST['start_dt'] != '') && ($_POST['start_dt'] == $_POST['end_dt']))
			$end = strtotime('+1 day', $end);		
		if($end)
		{
			$search_sql .= " and start_time<='".$end."'";
		}
	}
	
	$sql="Select t.id as time_id, ".
		"t.clockin as start_time, ".
		"t.clockout as end_time, ".
		"t.hours_worked, ".
		"t.lunch_hrs, ".
		"t.reg_hours, ".
		"t.ot_hours, ".
		"t.status, ".
		"t.mileage as reimburse_mile, ".
		"e.firstname as f, ".
		"e.lastname as l ".
//		"odo.daily_total, ".
//		"odo.reimburse_mile, ".
//		"odo.gas_rec, ".
//		"odo.misc_exp, ".
//		"odo.misc_exp_type ".
		"from timeclock_new t ".
		"left join \"employeeDB\" as e on e.\"employeeID\" = emp_id ".
//		"left join dtbl_odometer as odo on odo.time_id = t.time_id  ".
		"where ".$search_sql." ".
		"ORDER BY t.id DESC";
	
	//echo $sql;
	if(!($result=pg_query($connection,$sql))){
		print("Failed query2 " . pg_last_error($connection));
		exit;
	}
	while($row = pg_fetch_array($result))
	{
		$summary[]=$row;
	}	
	pg_free_result($result);
	
	$emp_name = $summary[0]['f'].' '.$summary[0]['l'];
	$reg_hrs = 0;
	$ot_hrs = 0;
	$total_hrs = 0;
	$reimburse_miles = 0;
	$gas_rec = 0;
	$misc_exp = 0;
	foreach($summary as $sum){		
		if((float)$sum['reg_hours'] > 0)
			$reg_hrs += (float)$sum['reg_hours'];
		if((float)$sum['ot_hours'] > 0)
			$ot_hrs +=  (float)$sum['ot_hours'];
		if((float)$sum['hours_worked'] > 0)
			$total_hrs +=  (float)$sum['hours_worked'];
		if((float)$sum['reimburse_mile'] > 0)
			$reimburse_miles +=  (float)$sum['reimburse_mile'];
		if((float)$sum['gas_rec'] > 0)
			$gas_rec +=  (float)$sum['gas_rec'];
		if((float)$sum['misc_exp'] > 0)
			$misc_exp +=  (float)$sum['misc_exp'];
	}
?>

<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td height="30" colspan="2" align="left" valign="top" class="grid001">&nbsp;</td>
    <td align="left" valign="top" class="grid001">Reg&nbsp;(hrs)</td>
    <td align="left" valign="top" class="grid001">Overtime (hrs)</td>
    <td align="left" valign="top" class="grid001">TOTALS (hrs)</td>
  </tr>
  <tr>
    <td height="30" colspan="2" align="left" valign="top" class="grid001">Hours/Stores</td>
    <td align="left" valign="top" class="grid001"><?php echo $reg_hrs; ?></td>
    <td align="left" valign="top" class="grid001"><?php echo $ot_hrs; ?></td>
    <td align="left" valign="top" class="grid001"><?php echo $total_hrs; ?></td>
  </tr>
  <tr>
    <td height="30" align="left" valign="top" class="gridVal">Rate of Pay </td>
    <td align="left" valign="top" class="gridVal">$<input style="border:0;" width="20px;" type="text" value="12" id="rate_pay" /></td>
    <td align="left" valign="top" class="gridVal" id="reg_hrs">$<?php echo $reg_hrs*12; ?></td>
    <td align="left" valign="top" class="gridVal" id="ot_hrs">$<?php echo $ot_hrs*12; ?></td>
    <td align="left" valign="top" class="gridVal" id="total_hrs">$<?php echo $total_hrs*12; ?></td>
  </tr>
  <tr>
    <td height="30" align="left" colspan="4" valign="top" class="gridVal">Mileage</td>
    <td align="left" valign="top" class="gridVal">$<?php echo round($reimburse_miles*0.405,2); ?> </td>
  </tr>
  <tr>
    <td height="30" align="left" colspan="4" valign="top" class="gridVal">Gas&nbsp;</td>
    <td align="left" valign="top" class="gridVal">$<?php echo $gas_rec; ?></td>
  </tr>
  <tr>
    <td height="30" align="left" colspan="4" valign="top" class="gridVal">Misc Expenses </td>
    <td align="left" valign="top" class="gridVal">$<?php echo $misc_exp; ?></td>
  </tr>
  <tr>
    <td height="30" align="right" valign="top" class="gridWhite">Employee : </td>
    <td align="left" valign="top" colspan="4" class="gridWhite"><?php echo $emp_name; ?></td>
  </tr>
</table>
<script type="text/javascript">
$( "#subpage" ).dialog({ title: "Summary Reports" });
$( "#subpage" ).dialog({ width: 950 });
$( "#subpage" ).dialog({ buttons: {"Print": function(){printSelection($(this));},"Close": function() {$(this).dialog( "close" );}}});
$('#rate_pay').change( function(){
	$('#reg_hrs').html('$'+Math.round($('#rate_pay').val()*<?php echo $reg_hrs;?>*100)/100);
	$('#ot_hrs').html('$'+Math.round($('#rate_pay').val()*<?php echo $ot_hrs;?>*100)/100); 
	$('#total_hrs').html('$'+Math.round($('#rate_pay').val()*<?php echo $total_hrs;?>*100)/100); 
});
function printSelection(node){
  var content=node.html();
  var pwin=window.open('','print_content','width=900,height=400%');

  pwin.document.open();
  pwin.document.write('<html><body onload="window.print()"><head><style type="text/css"> .grid001{background-image:url(<?php echo $mydirectory; ?>/images/headerbg.jpg);font-family:Tahoma, Verdana, Arial, Helvetica;color:#000033;font-size:12px;font-weight:bold; padding-top:10px; padding-left:10px;}  .gridVal{background: url("<?php echo $mydirectory; ?>/css/images/ui-bg_highlight-hard_100_f2f5f7_1x100.png") repeat-x scroll 50% top #F2F5F7;height:25px;border: 1px solid #DDDDDD;font-family:Tahoma, Verdana, Arial, Helvetica;font-size:12px;color: #362B36;} </style></head><div><img src="<?php echo $mydirectory; ?>/images/logo.gif" alt="Davis Merchandising Group" />' +content+'</body></html>');
  pwin.document.close(); 
  setTimeout(function(){pwin.close();},1000);
}
</script>
<?php
} // employee selected if
else
{
?>
<p>Please select an Employee to view the Summary Report. The Summary Report will only show the Approved Records.</p>
<script type="text/javascript">
$( "#subpage" ).dialog({ title: "Summary Reports Error" });
$( "#subpage" ).dialog({ width: 500 });
$( "#subpage" ).dialog({ buttons: {"OK": function() {$(this).dialog( "close" );}}});
</script>
<?php
}
?>
