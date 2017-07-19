<?php
$page = 'timesheet';

require 'Application.php';
include '../header.php';
$script = '';
if(isset($_GET['clockin']) && $_GET['clockin'] == 1)
{
	$script = "<script type='text/javascript'>loadTimesheet('dt='+".date('U').");</script>";
}
 else if (isset($_GET['m']) && $_GET['m'] >= 0 && $_GET['m'] < 12)
{
    $month = $_GET['m'];
}
?>
<div id="message" class="message_fixed"></div>
<div id="loading" class="message_fixed"><img src="../images/loading.gif" alt="Loading..." /></div>
<center><h1>Click on a day to Enter your time</h1></center>
<div style="width:350px;position:absolute;left:50%;top:215px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25px" height="25" class="fc-event-approved">&nbsp;</td>
    <td width="80px"> &nbsp;&nbsp;Approved </td>
    <td width="5px">&nbsp;</td>
    <td width="25px" class="fc-event-pending">&nbsp;</td>
    <td width="80px">&nbsp;&nbsp;Pending</td>
    <td width="5px">&nbsp;</td>
    <td width="25px" class="fc-event-reject">&nbsp;</td>
    <td width="80px">&nbsp;&nbsp;Rejected</td>
  </tr>
</table></div>
<div id='calendar'></div>
<div id="dialogue">
<div id='sub_content'></div>
</div>
<script type="text/javascript">
 var from_proj=2;  
 var from_report=2;
    </script>
<script type='text/javascript' src='../js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='../js/jquery/jquery-ui-1.8.19.custom.min.js'></script>
<script type='text/javascript' src='../js/jquery/jquery-ui-timepicker-addon.js'></script>
<script type='text/javascript' src='../js/fullcalendar/fullcalendar.min.js'></script>
<script type='text/javascript' src='../js/timesheet.js'></script>
<script type='text/javascript'>
var calendar = $('#calendar').fullCalendar({
	theme: true,
	editable: false,
	events: "month_view.php",
 <?php 
 if(isset($month)){
    echo 'month : '. $month.',';
 }
 ?>
	dayClick: function(date, allDay, jsEvent, view) {

		req = $.fullCalendar.parseDate(date);
		n = new Date();
		n.setHours(0);n.setMinutes(0);n.setSeconds(0);n.setMilliseconds(0);		
		if(req.getTime() <= n.getTime()){
		ts = Math.round(req.getTime() / 1000);
		loadTimesheet('dt='+ts+'&from_timesheet=1');
		}
		else{msg.html('You selected a day in Future.');showMessage('#C33');}
	},	
	eventClick: function(event, delta) {
		ts = Math.round(($.fullCalendar.parseDate(event.start)).getTime() / 1000);
		loadTimesheet('dt='+ts+'&from_timesheet=1');
	},
	
	loading: function(bool) {
		if (bool) showLoading();
		else hideLoading();
	}			
	});
        
        
      
function loadTimesheet_new(dt)
{
$( "#sub_content" ).dialog('close');
loadTimesheet(dt+'&add_new=1&from_timesheet=1'); 
}
function loadTimesheet_edit(time_id)
{
$( "#sub_content" ).dialog('close');
loadTimesheet('id='+time_id); 
}
function loadTimesheet_del(time_id,dt)
{
 var data='time_id='+time_id;  
$.ajax({
 url:'delete_temesheet.php',   
 type:'post',
 data:data,
success:function(){
$( "#sub_content" ).dialog('close');
loadTimesheet('dt='+dt);
}
});
}

        
</script>
<?php
echo $script;
include '../trailer.php';
?>