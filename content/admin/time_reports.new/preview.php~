<?php
require 'Application.php';
$time_id = 0;
$title = 'Preview Time Entry';
$status_type = array('Waiting For Approval', 'Rejected', 'Approved');
if(isset($_POST['id']) && $_POST['id'] > 0)
	$time_id = $_POST['id'];
if($time_id > 0){

$sql = 'SELECT * FROM dtbl_timesheet as t left join dtbl_odometer as odo on odo.time_id=t.time_id WHERE t.time_id= '.$time_id;
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$time=$row;
}
pg_free_result($result);

if(isset($time) && $time['time_id'] > 0)
{
	extract($time);
}
?>
<div id="preview">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="20">&nbsp;</td>
                <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="100" height="30">Date: </td>
                        <td width="10">&nbsp;</td>
                        <td style="font-size:12px;font-weight:bold;"><?php echo date('m/d/Y', $start_time); ?></td>
                        <td width="100" height="30" align="right"> Client: </td>
                        <td width="10">&nbsp;</td>
                        <td style="font-size:12px;font-weight:bold;"><?php echo ($client); ?></td>
                        <td width="200">&nbsp;</td>
                        <td width="100" height="30">Status: </td>
                        <td width="10">&nbsp;</td>
                        <td style="font-size:12px;font-weight:bold;"><?php echo $status_type[$status]; ?></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </table>
                      <table width="100%" border="0" cellspacing="1" cellpadding="0">
                      <tr>                       
                        <td height="30" align="left" valign="top" class="grid001">Store Name </td>
                        <td align="left" valign="top" class="grid001">Store#:</td>
                        <td align="left" valign="top" class="grid001">Company Rep </td>
                        <td align="left" valign="top" class="grid001">Start Time </td>
                        <td align="left" valign="top" class="grid001">End Time </td>
                        <td align="left" valign="top" class="grid001">Break1</td>
                        <td align="left" valign="top" class="grid001">Break2</td>
                        <td align="left" valign="top" class="grid001">Lunch Time</td>
                        <td align="left" valign="top" class="grid001">Lunch Start</td>
                        <td align="left" valign="top" class="grid001">Lunch End</td>
                        <td align="left" valign="top" class="grid001">Lunch Hours</td>
                        <td align="left" valign="top" class="grid001">Hours Worked </td>
                        <td align="left" valign="top" class="grid001">Reg Hours </td>
                        <td align="left" valign="top" class="grid001">O.T &nbsp;</td>
                        </tr>
                      <tr>                        
                        <td height="30"  align="left" valign="top" class="gridWhite"><?php echo $store; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $store_num; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $company_rep; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo date('m/d/Y h:i a', $start_time); ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo date('m/d/Y h:i a', $end_time); ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo ($break1)?'Yes':'No'; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo ($break2)? 'Yes':'No'; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo ($lunch_time)?'Yes':'No'; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $lunch_start; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $lunch_end; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $lunch_hrs; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $hours_worked; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $reg_hours; ?></td>
                        <td align="left" valign="top" class="gridWhite"><?php echo $ot_hours; ?></td>
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
        <td align="left" valign="top" class="gridWhite"><?php echo $reimburse_mile; ?></td>
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
            </table>
</div>
<script type="text/javascript">
$( "#subpage" ).dialog({ title: "Preview Time Records" });
$( "#subpage" ).dialog({ width: 950 });
$( "#subpage" ).dialog({ buttons: {"OK": function() {$(this).dialog( "close" );}}});
</script>
<?php
}
?>