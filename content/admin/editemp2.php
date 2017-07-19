<?php
require('Application.php');
extract($_POST);
if(isset($delete_emp))
{
$query='';
$query2='';
foreach($check as $emp)
{
   if($query!='') $query.=' or ';
   {
   $query.='"employeeID"='.$emp;
   $query2.='"emp_id"='.$emp;
   }
}
    
  $query='delete from "employeeDB" where'.$query;  
  $query.=';delete from "flexgrid_storage" where'.$query2;  
 // echo $query;
  pg_query($connection,$query);
}

$querya=("SELECT * ".
		 "FROM \"employeeDB\" ".
		 "WHERE \"active\" = 'no'");
if(!($resulta=pg_query($connection,$querya))){
	print("Failed querya: " . pg_last_error($connection));
	exit;
}
while($rowa = pg_fetch_array($resulta)){
	$dataa[]=$rowa;
}
require('../header.php');
echo "<font face=\"arial\">";
echo "<blockquote>";
echo "<center><font size=\"5\">INTERNAL DIRECTORY ADMINISTRATION</font></center>";
echo "<p>";
echo "<center>";?>
<form method="post" action="">
<table width="600px">
<tr><td><input type="submit" value="Delete Selected" name="delete_emp"/></td></tr>
<?php echo "<tr>";
echo "<td align=\"center\"><b><font face=\"arial\" size=\"-1\">FUNCTION</font></b></td>";
echo "<td align=\"center\"><b><font face=\"arial\" size=\"-1\">NAME</font></b></td>";
echo "<td align=\"center\"><b><font face=\"arial\" size=\"-1\">TITLE</font></b></td>";
echo "<td align=\"center\"><b><font face=\"arial\" size=\"-1\">Select</font></b></td>";
echo "</tr>";
for($i=0; $i < count($dataa); $i++){
	echo "<tr>";
	echo "<td bgcolor=C0C0C0>";
	echo "<a href=\"edit.php?employeeID=".$dataa[$i]['employeeID']."\">";
	echo "<font face=\"arial\" size=\"-2\">EDIT</font></a><br>";
	echo "</td>";
	echo "<td bgcolor=C0C0C0><font face=\"arial\" size=\"-1\">".$dataa[$i]['firstname']." ".$dataa[$i]['lastname']."</font></td>";
	echo "<td bgcolor=C0C0C0><font face=\"arial\" size=\"-1\">".$dataa[$i]['title']."</font></td>";
        echo "<td bgcolor=C0C0C0><input type='checkbox' name='check[]' value='".$dataa[$i]['employeeID']."'/></td>";
	echo "</tr>";
}?>
</table>
</form>    
<?php require('../trailer.php');
?>
