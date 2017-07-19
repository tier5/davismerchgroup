<?php
$page = 'timesheet';

require 'Application.php';
include '../../header.php';

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

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr><td>

            <b>Select user :</b> 
<?php

$sql="Select firstname, lastname, \"employeeID\" from \"employeeDB\"";
if(!($result=pg_query($connection,$sql))){
	print("Failed query: " . pg_last_error($connection));
	exit;
}

 echo "<select name='user' id='user'>";
while($row = pg_fetch_array($result))
{
	
      $opt="<option";
      if($_SESSION['employeeID']==$row['employeeID']){ $opt.=" selected='selected' ";}
      $opt.=" value='".$row['employeeID']."'>". $row['firstname']." ".$row['lastname']."</option>";
      echo $opt;
        
}
echo "</select>";
pg_free_result($result);
     
?>
        </td></tr>
</table>
<div id='calendar'></div>
<div id="dialogue">
<div id='sub_content'></div>
</div>
<script type='text/javascript' src='../../js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='../../js/jquery/jquery-ui-1.8.19.custom.min.js'></script>
<script type='text/javascript' src='../../js/jquery/jquery-ui-timepicker-addon.js'></script>
<script type='text/javascript' src='../../js/fullcalendar/fullcalendar.min.js'></script>
<script type='text/javascript' src='../../js/timesheet2.js'></script>
<?php
include '../../trailer.php';
?>