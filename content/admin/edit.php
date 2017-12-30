<?php
require('Application.php');
require('../header.php');
$empid=$_GET['employeeID'];
$query1=("SELECT * ".
		 "FROM \"employeeDB\" ".
		 "WHERE \"employeeID\" = '$empid'");
if(!($result1=pg_query($connection,$query1))){
	print("Failed query1: " . pg_last_error($connection));
	exit;
}
while($row1=pg_fetch_array($result1)){
	$data1=$row1;
}

//print_r($data1);
$datehired=$data1['datehired'];
$datehired1=$datehired;
$monthnamehired=date("F", $datehired1);
$monthhired=date("m", $datehired1);
$dayhired=date("d", $datehired1);
$yearhired=date("Y", $datehired1);
$date_hrd=date('m/d/Y',$datehired1);
//$date_hrd=$monthhired.'/'.$dayhired.'/'.$yearhired;

$query2=("SELECT * ".
		 "FROM \"permissions\" ".
		 "WHERE \"employee\" = '$empid'");
if(!($result2=pg_query($connection,$query2))){
	print("Failed query2: " . pg_last_error($connection));
	exit;
}
while($row2 = pg_fetch_array($result2)){
	$data2[]=$row2;
}
$query1=("SELECT \"ID\", \"clientID\", \"client\", \"active\" ".
		 "FROM \"clientDB\" ".
		 "WHERE \"active\" = 'yes' ".
		 "ORDER BY \"client\" ASC");
if(!($result1=pg_query($connection,$query1))){
	print("Failed query1: " . pg_last_error($connection));
	exit;
}
while($row1 = pg_fetch_array($result1)){
	$data_client[]=$row1;
}
pg_free_result($result1);

$sql3 = 'Select rid, region from "tbl_region" ORDER BY region ASC ';
if (!($result = pg_query($connection, $sql3))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row3 = pg_fetch_array($result)){
	$data_region[]=$row3;
}
pg_free_result($result);

?>
<form action="editemp.php" method="post">
<input type="hidden" name="emp_type" value="<?php echo $data1['emp_type']; ?>" />
<table width="70%" align="center">
<tr bgcolor="#C0C0C0">
<td colspan="2"><font face="arial"><b>Edit Employee Record</b></font></td>
</tr>

                <tr id="client" <?php if($data1['emp_type']==0){?> style="display:none" <?php } ?>>
                <td align="right"><font face="arial" color="red">*(r)</font><font face="arial"><b>Client Name </b></font></td>
                <td align="left"><select name="clientname" >
                   <?php
					for($i=0; $i <count($data_client); $i++){						
						echo '<option value="' . $data_client[$i]['ID'] . '" ';
						if (isset($data1['client_Id']) && $data1['client_Id'] != "" && $data1['client_Id'] == $data_client[$i]['ID'])
						echo 'selected="selected" ';
                        echo '>' . $data_client[$i]['client'] . '</option>';
                    }
					
						
					?> 
                </select> </td>
              </tr>          
<?php
				if($data1['emp_type']==0)
				{
      
	echo "<tr>";
	echo "<td width=\"40%\" align=\"right\"><font face=\"arial\"><b>First Name<font color=\"red\">*</font></b></font></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"firstname\" size=\"46\" value=\"".$data1['firstname']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Last Name<font color=\"red\">*</font></b></font></td>";
	echo "<td align=\"left\"><INPUT TYPE=\"text\" NAME=\"lastname\" SIZE=46 VALUE=\"".$data1['lastname']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Region</b></font></td>";
	echo "<td><select name=\"region\">";
	
			for ($i = 0; $i < count($data_region); $i++) {
    			echo '<option value="'.$data_region[$i]['rid'].'" ';
    				if (isset($data1['region']) && $data1['region'] == $data_region[$i]['rid'])
        			echo 'selected="selected" ';
    				echo '>' . $data_region[$i]['region'] . '</option>';
				}
	echo "</select></td>";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	$title=str_replace("\"","&quot;",$data1['title']);
	echo "<td align=\"right\"><font face=\"arial\"><b>Title</b></font></td>";
	echo "<td align=\"left\"><INPUT TYPE=\"text\" NAME=\"title\" SIZE=46 VALUE=\"".$title."\"></td>";
	echo "</tr>";
	echo "<tr>";
	$address=str_replace("\"","&quot;",$data1['address']);
	echo "<td align=\"right\"><font face=\"arial\"><b>Address<font color=\"red\">*</font></b></font></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"address\" size=30 value=\"".$address."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>City<font color=\"red\">*</font></b></font></td>";
	echo "<td align=\"left\"><INPUT TYPE=\"text\" NAME=\"city\" SIZE=30 VALUE=\"".$data1['city']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>State<font color=\"red\">*</font></b></font></td>";
	echo "<td align=\"left\"><INPUT TYPE=\"text\" NAME=\"state\" SIZE=3 VALUE=\"".$data1['state']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Zip<font color=\"red\">*</font></b></font></td>";
	echo "<td align=\"left\"><INPUT TYPE=\"text\" NAME=\"zip\" SIZE=10 VALUE=\"".$data1['zip']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Phone</b></font></td>";
	echo "<td align=\"left\"><INPUT TYPE=\"text\" NAME=\"phone\" SIZE=20 VALUE=\"".$data1['phone']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Pager</b></font></td>";
	echo "<td align=\"left\"><INPUT TYPE=\"text\" NAME=\"pager\" SIZE=46 VALUE=\"".$data1['pager']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Alpha Pager</b></font></td>";
	echo "<td align=\"left\"><INPUT TYPE=\"text\" NAME=\"alphapager\" SIZE=46 VALUE=\"".$data1['alphapager']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Cellular<font color=\"red\">*</font></b></font></td>";
	echo "<td align=\"left\"><INPUT TYPE=\"text\" NAME=\"cell\" SIZE=46 VALUE=\"".$data1['cell']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Email<font color=\"red\">*</font></b></font></td>";
	echo "<td align=\"left\"><INPUT TYPE=\"text\" NAME=\"email\" SIZE=46 VALUE=\"".$data1['email']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Date Hired</b></font></td>";
	 echo'<td> <input size="15px" type="text" name="date_hire" id="date_hire" value="'.$date_hrd.'"/></td>';
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Salary</b></font></td>";
	echo "<td align=\"left\"><select name=\"salary\">";
	if($data1['salary'] == "No"){
		echo "<option value=\"".$data1['salary']."\" selected>No</option>";
		echo "<option value=\"Yes\">Yes</option>";
	}else{
		echo "<option value=\"Yes\" selected>Yes</option>";
		echo "<option value=\"No\">No</option>";
	}
	echo "</select>";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Wage&nbsp;$</b></font></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"wage\" size=\"20\" value=\"".$data1['wage']."\"></td>";
	echo "</tr>";
	} // if emp_type
	echo "<tr>";
	echo "<td  align=\"right\">
	<font face=\"arial\"><b>Username</b></font></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"usernamenew\" size=\"30\" value=\"".$data1['username']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Password</b></font></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"passwordnew\" size=\"30\" value=\"".$data1['password']."\"></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>POP Password</b></font></td>";
	echo "<td align=\"left\"><input type=\"password\" name=\"poppassword\" size=\"30\" value=\"".$data1['poppassword']."\"></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\" color=\"red\">NUMBERS ONLY</font><font face=\"arial\"><b>Clockin ID</b></font></td>";
	echo "<td align=\"left\"><input type=\"text\" name=\"clockinid\" value=\"".$data1['clockinid']."\" size=\"20\"></td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Mileage</b></font></td>";
	echo "<td align=\"left\">Yes<input type=\"radio\" name=\"mileage\" value=\"1\"> / No<input type=\"radio\" name=\"mileage\" value=\"0\"></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td align=\"right\"><font face=\"arial\"><b>Mileage Deduction</b></font></td>";
	echo "<td align=\"left\">-0<input type=\"radio\" name=\"mileage_deduction\" value=\"0\"> / -30<input type=\"radio\" name=\"mileage_deduction\" value=\"30\"> / -60<input type=\"radio\" name=\"mileage_deduction\" value=\"60\"> / -75<input type=\"radio\" name=\"mileage_deduction\" value=\"75\"></td>";
	echo "</tr>";

	echo "</table>";
echo "<input type=\"hidden\" name=\"employeeID\" value=\"".$data1['employeeID']."\">";
echo "<table width=\"80%\">";
echo "<tr>";
echo "<td colspan=\"5\" align=\"center\"><br><br><input type=\"Submit\" name=\"Edit Employees\" value=\"    Edit Employee   \"></td>";
echo "</tr>";
?>
</table>

</form>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script> 
<script type='text/javascript'>
   $('document').ready(function(){
 $("#date_hire").datepicker();
});
</script>
<?php 
require('../trailer.php');
?>
