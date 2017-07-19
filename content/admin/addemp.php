<?php
require('Application.php');
require('../header.php');
$query = ("SELECT \"ID\", \"clientID\", \"client\", \"active\" " .
        "FROM \"clientDB\" " .
        "WHERE \"active\" = 'yes' " .
        "ORDER BY \"client\" ASC");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result)) {
    $data[] = $row;
}
pg_free_result($result);

$sql3 = 'Select rid, region from "tbl_region" ORDER BY region ASC ';
if (!($result = pg_query($connection, $sql3))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row3 = pg_fetch_array($result)){
	$data_region[]=$row3;
}
pg_free_result($result);

echo "<font face=\"arial\">";
echo "<blockquote>";
echo "<center><font size=\"5\">$compname Directory Administration</font></center>";
echo "<p>";
echo "</blockquote>";
?>
<form action="newemp.php" method="post">
  <table align="center">
        <tr>
            <td colspan=2><font face="arial"><b>Enter New User</b></font></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>User Type </b></font></td>
            <td><input type="radio" name="employeeType" value="0"  onclick="setVisibility('emp');" checked="checked"/>
                Employee
                <input type="radio" name="employeeType" value="1" onclick="setVisibility('client');"/> 
                Client  </td>
        </tr>
                <tr id="client" style="display:none">
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>Client Name </b></font></td>
            <td><select name="clientname">
                    <?php
                    for ($i = 0; $i < count($data); $i++) {
                        echo '<option value="' . $data[$i]['ID'] . '">' . $data[$i]['client'] . '</option>';
                    }
                    ?> 
                </select> </td>
        </tr>
        <tr><td colspan="2">
        <table id="emp_detail" align="center" width="100%">
         <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>First Name</b></font></td>
            <td><input type="text" name="firstnamenew" size="20"></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>Last Name</b></font></td>
            <td><input type="text" name="lastnamenew" size="20"></td>
        </tr>
        <tr>
        <td><font face="arial"><b>Region</b></font></td>
        <td><select name="region">
        <?php
                    for ($i = 0; $i < count($data_region); $i++) {
                        echo '<option value="' . $data_region[$i]['rid'] . '">' . $data_region[$i]['region'] . '</option>';
                    }
                    ?> 
                    </select></td>
        </tr>
        <tr>
            <td><font face="arial"><b>Title</b></font></td>
            <td><input type="text" name="titlenew" size="20"></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>Address</b></font></td>
            <td><input type="text" name="addressnew" size="30"></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>City</b></font></td>
            <td><input type="text" name="citynew" size="30"></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>State</b><font></td>
            <td><input type="text" name="statenew" size="3"></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>Zip</b></font></td>
            <td><input type="text" name="zipnew" size="10"></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>Phone</b></font></td>
            <td><input type="text" name="phonenew" size="20"></td>
        </tr>
        <tr>
            <td><font face="arial"><b>Pager</b></font></td>
            <td><input type="text" name="pagernew" size="20"></td>
        </tr>
        <tr>
            <td><font face="arial"><b>Alpha Pager</b></font></td>
            <td><input type="text" name="alphapagernew" size="20"></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>Cellular</b></font></td>
            <td><input type="text" name="cellnew" size="20"></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>Email</b></font></td>
            <td><input type="text" name="emailnew" size="20"></td>
        </tr>
        <tr>
<td><font face="arial" color="red">*(r)</font><font face="arial"><b>Date Hired</b></font></td>
<td>
    <input size="15px" type="text" name="date_hire" id="date_hire"/>
</td>
</tr>
        <tr>
            <td><font face="arial"><b>Salary</b></font></td>
            <td><select name="salarynew">
                    <option value="no">No</option>
                    <option value="yes">Yes</option>
                </select></td>
        </tr>
        <tr>
            <td><font face="arial"><b>Wage</b></font></td>
            <td><input type="text" name="wagenew" size="20"></td>
        </tr>
        </table>
        </td></tr>       
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>Username</b></font></td>
            <td><input type="text" name="newusername" size="20"></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>Password</b></font></td>
            <td><input type="text" name="newpassword" size="20"></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>POP Password</b></font></td>
            <td><input type="password" name="newpoppassword" size="20"></td>
        </tr>
        <tr>
            <td><font face="arial" color="red">*(r)</font><font face="arial"><b>Clockin ID</b></font></td>
            <td><input type="password" name="clockinid" size="20"></td>
        </tr>
    </table>
    <table width="80%">
        <tr>
        <td colspan=5 align="center"><br>
                <br>
                     <input type="Submit" value="     Enter New User     " /></td>
        </tr>
    </table>
</form>

<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script>  
<script type="text/javascript">
    function setVisibility(id)
    {				
        switch(id)
        {
            case 'emp':
                {
                    document.getElementById('client').style.display="none";
                    document.getElementById('emp_detail').style.display="";
                    break;
                }
            case 'client':
                {
                    document.getElementById('client').style.display="";
                    document.getElementById('emp_detail').style.display="none";
                    break;
                }
            default:
                {
                    document.getElementById('client').style.display="none";
                    document.getElementById('emp_detail').style.display="";
                }
        }
    }
    
    $('document').ready(function(){
 $("#date_hire").datepicker();
});
</script>
<?php
require('../trailer.php');
?>
