<?php
require 'Application.php';
$dt = $_POST['dt'];
$time_id = 0;
$title = 'Preview Time Entry';
$status_type = array('Waiting For Approval', 'Rejected', 'Approved');
if(isset($_POST['id']) && $_POST['id'] > 0)
	$time_id = $_POST['id'];

$end = strtotime(date('Y/m/d',$dt).' 23:59:59');


$sql = 'SELECT t.time_id, t.start_time, t.end_time, t.emp_id, t.store, t.hours_worked, t.reg_hours, t.ot_hours, t.break1, t.break2, t.lunch_start, t.lunch_end, t.lunch_hrs, t.store_num, t.status, t.company_rep,t.client, odo.ot_id, odo.start_read, odo.end_read, odo.daily_total, odo.adj_miles, odo.reimburse_mile, odo.gas_rec, odo.misc_exp, odo.food, odo.lodging, odo.toll_fees, odo.park_fees, odo.other_exp, odo.misc_exp_type FROM dtbl_timesheet as t left join dtbl_odometer as odo on odo.time_id=t.time_id WHERE start_time >= '.$dt.' and start_time <= '.$end. ' and emp_id = '.$_GET['user'];
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
//print_r($time);
if(isset($time) && $time['time_id'] > 0)
{
	extract($time);
}




//if($row['timesheet']=='on')
    {
$title = 'Add Daily Time Record';
?>

<div id="form_div">
  <form id="timesheet_form">
  <input type="hidden" name="time_id" value="<?php echo $time_id; ?>" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="lightup">
      <tr>
        <td width="20">&nbsp;</td>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>Store Name: </td>
                    <td><input name="store_name" type="text" class="textBox" value="<?php echo $store; ?>" /></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td height="30">Store #: </td>
                    <td>&nbsp;</td>
                    <td><input name="store_num" type="text" class="textBox" value="<?php echo $store_num; ?>" /></td>
                  </tr>
                  <tr>
                    <td>Client Name: </td>
                    <?php 
					$select = 0;
					?>
                 
                    <td><select id="client" style="font-faimly:verdana;font-size:10;width:150px; height:25px;" onChange="showOther();">
    <option value="">--------Select--------</option>     
    <option <?php if(strtolower(trim($client)) == '7up bottling co.'){ echo 'selected ="selected"'; $select = 1; }?> value="7up Bottling Co.">7up Bottling Co.</option>
                <option <?php if(strtolower(trim($client)) == 'acosta'){ echo 'selected ="selected"'; $select = 1; }?> value="Acosta">Acosta</option>
                <option <?php if(strtolower(trim($client)) == 'advantage'){ echo 'selected ="selected"'; $select = 1;}?> value="Advantage">Advantage</option>
                <option <?php if(strtolower(trim($client)) == 'alta dena'){ echo 'selected ="selected"'; $select = 1;}?> value="Alta Dena">Alta Dena</option>
                <option <?php if(strtolower(trim($client)) == 'ampm') {echo 'selected ="selected"'; $select = 1;}?> value="AMPM">AMPM</option>
                <option <?php if(strtolower(trim($client)) == 'coca cola'){ echo 'selected ="selected"'; $select = 1;}?> value="Coca Cola">Coca Cola</option>
                <option <?php if(strtolower(trim($client)) == 'copa di vino') {echo 'selected ="selected"'; $select = 1;}?> value="Copa Di Vino">Copa Di Vino</option>
                <option <?php if(strtolower(trim($client)) == 'evolution fresh'){ echo 'selected ="selected"'; $select = 1;}?> value="Evolution Fresh">Evolution Fresh</option>
                <option <?php if(strtolower(trim($client)) == 'frito lay') {echo 'selected ="selected"'; $select = 1;}?> value="Frito Lay">Frito Lay</option>
                <option <?php if(strtolower(trim($client)) == 'general mills'){ echo 'selected ="selected"'; $select = 1;}?> value="General Mills">General Mills</option>
                <option <?php if(strtolower(trim($client)) == 'hostess brands'){ echo 'selected ="selected"'; $select = 1;}?> value="Hostess Brands">Hostess Brands</option>
                <option <?php if(strtolower(trim($client)) == 'kelloggs snacks'){ echo 'selected ="selected"'; $select = 1;}?> value="Kelloggs Snacks">Kelloggs Snacks</option>
                <option <?php if(strtolower(trim($client)) == 'kraft foods') {echo 'selected ="selected"'; $select = 1;}?> value="Kraft Foods">Kraft Foods</option>
                 <option <?php if(strtolower(trim($client)) == 'market centre') {echo 'selected ="selected"'; $select = 1;}?> value="Market Centre">Market Centre</option>
                  <option <?php if(strtolower(trim($client)) == 'mission foods') {echo 'selected ="selected"'; $select = 1;}?> value="Mission Foods">Mission Foods</option>
                  <option <?php if(strtolower(trim($client)) == 'monster energy') {echo 'selected ="selected"'; $select = 1;}?> value="Monster Energy">Monster Energy</option>
                  <option <?php if(strtolower(trim($client)) == 'nestle dsd') {echo 'selected ="selected"'; $select = 1;}?> value="Nestle DSD">Nestle DSD</option>
                    <option <?php if(strtolower(trim($client)) == 'nestle water') {echo 'selected ="selected"'; $select = 1;}?> value="Nestle Water">Nestle Water</option>
                 <option <?php if(strtolower(trim($client)) == 'pepperidge farms') {echo 'selected ="selected"'; $select = 1;}?> value="Pepperidge Farms">Pepperidge Farms</option>
                 <option <?php if(strtolower(trim($client)) == 'pepsi beverages') {echo 'selected ="selected"'; $select = 1;}?> value="Pepsi Beverages">Pepsi Beverages</option>
                 <option <?php if(strtolower(trim($client)) == 'ralphs') {echo 'selected ="selected"'; $select = 1;}?> value="Ralphs">Ralphs</option>
                 <option <?php if(strtolower(trim($client)) == 'red bull') {echo 'selected ="selected"'; $select = 1;}?> value="Red Bull">Red Bull</option>
                  <option <?php if(strtolower(trim($client)) == 'snapple'){ echo 'selected ="selected"'; $select = 1;}?> value="Snapple">Snapple</option>
                  <option <?php if(strtolower(trim($client)) == 'snyders'){ echo 'selected ="selected"'; $select = 1;}?> value="Snyders">Snyders</option>
                  <option <?php if(strtolower(trim($client)) == 'speed engery'){ echo 'selected ="selected"'; $select = 1;}?> value="SPEED Engery">SPEED Engery</option>
                 <option <?php if(strtolower(trim($client)) == 'stater bros'){ echo 'selected ="selected"'; $select = 1;}?> value="Stater Bros">Stater Bros</option>
                 <option <?php if($select == 0 && trim($client) != '') {echo 'selected ="selected"';}?> value="other">Other</option>
     

  </select></td>
                    <td id="client_td" colspan="2" <?php if($select > 0 || trim($client) == '') echo 'style="visibility:hidden"'; ?>><input type="text" name="client" id="client_input" value="<?php echo $client;?>" class="textBox" /></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                   </tr>                 
                  <tr>
                    <td height="30">Start Date & Time: </td>
                    <td width="10"><input id="start_date" type="text" class="textBox" readonly="readonly" value="<?php if(isset($start_time)){ echo date('m/d/Y h:i a', $start_time); } else{ echo date('m/d/Y',$dt).' 08:00 am'; }?>" /></td>
                    <td><input type="hidden" name="start_date" id="hdn_start_date" value="<?php if(isset($start_time)){ echo $start_time; } else{ echo $dt; } ?>" /></td>
                    <td width="10">&nbsp;</td>
                    <td>End Date & Time: </td>
                    <td width="10">&nbsp;</td>
                    <td><input id="end_date" type="text" class="textBox" readonly="readonly" value="<?php if(isset($end_time)){ echo date('m/d/Y h:i a', $end_time); } else{ echo date('m/d/Y',$dt).' 08:00 am'; }?>" />
                      <input type="hidden" id="hdn_end_date" name="end_date" value="<?php if(isset($end_time)){ echo $end_time; } else{ echo $dt; } ?>" /></td>
                  </tr>
                  <tr id="break_1" <?php if(!isset($break1)){ echo ' class="tr_hide"';} ?>>
                    <td width="100" height="30">1st Break: </td>
                    <td width="10"><input id="break1" name="break1" type="checkbox" <?php if(isset($break1) && $break1 > 0){ echo ' checked="checked" ';} ?> value="1" /></td>
                    <td>&nbsp;</td>
                    <td width="10">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="10">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  
                  <tr id="lunch" <?php if(!isset($lunch_hrs) || $lunch_hrs <= 0){ echo ' class="tr_hide"';} ?>>
                    <td>Lunch: </td>
                    <td width="10"><input id="lunch_st" name="lunch_start" type="text" class="textBox" style="width:70px;" readonly="readonly" value="<?php if(isset($lunch_start)){ echo $lunch_start; } ?>" />
                    <input id="lunch_ed" name="lunch_end" type="text" class="textBox" style="width:70px;"  readonly="readonly" value="<?php if(isset($lunch_end)){ echo $lunch_end; } ?>" /></td>
                    <td>&nbsp;&nbsp;
                      <input type="hidden" name="lunch_hrs" id="lunch_hrs" value="<?php if(isset($lunch_hrs)){ echo $lunch_hrs; } else { echo 0; } ?>" /></td>
                    <td width="10">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="10">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr id="break_2" <?php if(!isset($break2)){ echo ' class="tr_hide"';} ?>>
                    <td width="100" height="30">2nd Break: </td>
                    <td width="10"><input id="break2" name="break2" type="checkbox" <?php if(isset($break2) && $break2 > 0){ echo ' checked="checked" ';} ?> value="1" /></td>
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
                      <td height="30">Reimburable Miles : </td>
                      <td>&nbsp;</td>
                      <td><input name="odo_reim" id="odo_reim" type="text" readonly="readonly" class="readonly" value="<?php if(isset($reimburse_mile)){ echo $reimburse_mile; } else { echo 0; } ?>" /></td>
                    </tr>
                    <tr>
                      <td height="30">Gas Rec if not being paid Mileage ($):</td>
                      <td>&nbsp;</td>
                      <td><input name="odo_gas" id="odo_gas" type="text" class="textBox" value="<?php if(isset($gas_rec)){ echo $gas_rec; } else { echo 0; } ?>" /></td>
                      <td>&nbsp;</td>
                      <td height="30">Expenses ($):</td>
                      <td>&nbsp;</td>
                      <td><input name="odo_mis_exp" id="odo_mis_exp" type="text" readonly="readonly" class="readonly" value="<?php if(isset($misc_exp)){ echo $misc_exp; } else { echo 0; }?>" /></td>
                   
                    </tr>
                    <tr>
                      <td width="150" height="30">Food: </td>
                      <td width="10">&nbsp;</td>
                      <td><input name="odo_food" id="odo_food" type="text" class="textBox" value="<?php if(isset($food)){ echo $food; } else { echo 0; } ?>" /></td>
                      <td width="10">&nbsp;</td>
                     </tr>  
                   
                      <td width="150" height="30" align="left">Lodging: </td>
                      <td width="10">&nbsp;</td>
                      <td><input name="odo_lodging" id="odo_lodging" type="text" class="textBox" value="<?php if(isset($lodging)){ echo $lodging; } else { echo 0; } ?>" /></td>
                      <td width="10">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      
                      
                    </tr>
                    <tr>
                      <td width="150" height="30" align="left">Toll Fees: </td>
                      <td width="10">&nbsp;</td>
                      <td><input name="odo_toll_fees" id="odo_toll_fees" type="text" class="textBox" value="<?php if(isset($toll_fees)){ echo $toll_fees; } else { echo 0; } ?>" /></td>
                      <td width="10">&nbsp;</td>
                      </tr>
                      
                      <td width="150" height="30" align="left">Parking Fees: </td>
                      <td width="10">&nbsp;</td>
                      <td><input name="odo_park_fees" id="odo_park_fees" type="text" class="textBox" value="<?php if(isset($park_fees)){ echo $park_fees; } else { echo 0; } ?>" /></td>
                      <td width="10">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    
                    <tr>
                      <td>Misc Expense :</td>
                      <td>&nbsp;</td>
                      <td><input name="odo_misc" id="odo_misc" type="text" class="textBox" value="<?php if(isset($other_exp)){ echo $other_exp; } else { echo ''; } ?>" /></td>
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


$( "#sub_content" ).dialog({ title: "<?php echo $title; ?>" });
$( "#sub_content" ).dialog({ buttons: {"Agree and Save": function() {
		showLoading();
$.ajax({type: 'POST',url: 'timesheet_submit.php',data: $('#timesheet_form').serialize(),dataType: 'json',success: function(data){
 calendar.fullCalendar('refetchEvents');if(data.error != ''){msg.html(data.error);showMessage('#C33');}else {msg.html(data.msg);showMessage('#390');} sub_content.dialog( "close" );hideLoading();}});
	},
		Cancel: function() {
			$( this ).dialog( "close" );
		}
	}
});
$('#start_date').datetimepicker({
    onClose: function(dateText, inst) {
        var endDateTextBox = $('#end_date');
        if (endDateTextBox.val() != '') {
            var testStartDate = new Date(dateText);
            var testEndDate = new Date(endDateTextBox.val());
            if (testStartDate > testEndDate) endDateTextBox.val(dateText);
			else calculate_hrs(endDateTextBox.val());}
        else {endDateTextBox.val(dateText);}
    },
    onSelect: function (selectedDateTime){
        var start = $(this).datetimepicker('getDate');
        $('#end_date').datetimepicker('option', 'minDate', new Date(start.getTime()));
    },
	 ampm: true,
	minDate: new Date(<?php echo $dt*1000;?>),
	minuteGrid: 15,
	maxDate: new Date(<?php echo strtotime('23 hour, 59 minutes',$dt)*1000;?>)
});
$('#end_date').datetimepicker({
    ampm: true,
	minDate: new Date(<?php echo $dt*1000;?>),
	minuteGrid: 15,
	maxDate: new Date(<?php echo strtotime('+1 day, 23 hour, 59 minutes',$dt)*1000;?>),
	 onClose: function(dateText, inst){
		 calculate_hrs(dateText);		
	 }
});
$('#lunch_st').timepicker({
    ampm: true,
	minuteGrid: 15,
	onClose: function(dateText, inst) {
	  var endtime = $('#lunch_ed');
	  var currentDate = new Date();
	  var testStartDate = new Date(Date.parse(currentDate.getFullYear()+ '/' + currentDate.getMonth() +'/'+ currentDate.getDate() +' '+ dateText, "yyyy/mm/dd HH:mm:ss"));   
	  testStartDate = new Date(testStartDate.getTime()+Math.floor(30*60*1000));
	  var hours = testStartDate.getHours();
	  var minutes = testStartDate.getMinutes();
	  var suffix = " am";
	  if (hours >= 12) {suffix = " pm";hours = hours - 12;}
	  if (hours == 0) {hours = 12;}
	  var enddate_format = ((hours < 10) ? '0'+hours:hours) + ':' + ((minutes < 10) ? '0'+minutes:minutes) + suffix;
	  if (endtime.val() != '') {
		  var testEndDate = new Date(Date.parse(currentDate.getFullYear()+ '/' + currentDate.getMonth() +'/'+ currentDate.getDate() +' '+ endtime.val(), "yyyy/mm/dd HH:mm:ss"));           			
		  if (testStartDate > testEndDate)
			  endtime.val(enddate_format);
	  }
	  else {endtime.val(enddate_format);}
	  calculate_hrs($('#end_date').val());
    }
});
$('#lunch_ed').timepicker({
    ampm: true,
	minuteGrid: 15,
	onClose: function(dateText, inst) { calculate_hrs($('#end_date').val());}
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
</script>

<?php
}


?>