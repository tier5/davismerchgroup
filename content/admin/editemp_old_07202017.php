<?php
require('Application.php');
$search_uri = "";
$limit = "";
$where="";
if(isset($_GET['employee'])&&$_GET['employee']!="")
{
      $where.=" and (coalesce(firstname, '') || ' ' || coalesce(lastname, '')) ilike  '%".$_GET['employee']."%'"; 
}
$querya=("SELECT * ".
		 "FROM \"employeeDB\" ".
		 "WHERE \"active\" = 'yes' ");
if($where!="")
    $querya.= $where;
	 $querya.=' order by firstname asc';
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
echo "<center>";
?>

<table>
<tr>
<td width="50px" bgcolor="C0C0C0"><b>User:</b> </td>
    <td bgcolor="C0C0C0"><input type="text" name="employee" id="employee" value="<?php echo $_GET['employee']; ?>"  /></td>
    <td bgcolor="C0C0C0"><input type="button" value="Search" onclick="javascript:search();"  /></td>.
    <td bgcolor="C0C0C0"><input type="reset" value="Cancel"  onclick="$('#employee').val('');search();" /></td>
    </tr>
</table>
<script type="text/javascript">
   datastr="";
   d="";
    d="<?php if(isset($_GET['employee'])&& $_GET['employee']!="") echo $_GET['employee']; else echo "";?>";
   if(d!="")
       {
   if( datastr=="")
       datastr+='?employee='+d;
   else
       datastr+='&employee='+d;
       }
</script>
<?php
echo "<table width=\"50%\">";
echo "<tr>";
echo "<td align=\"center\"><b><font face=\"arial\" size=\"-1\">FUNCTION</font></b></td>";
echo "<td align=\"center\"><b><font face=\"arial\" size=\"-1\">NAME</font></b></td>";
echo "<td align=\"center\"><b><font face=\"arial\" size=\"-1\">TITLE</font></b></td>";
echo "</tr>";
for($i=0; $i < count($dataa); $i++){
	echo "<tr style=\"width:5%;\">";
	echo "<td bgcolor=C0C0C0>";
	echo "<a href=\"edit.php?employeeID=".$dataa[$i]['employeeID']."\">";
	echo "<font face=\"arial\" size=\"-2\">EDIT</font></a><br>";
	echo "<a href=\"delete.php?employeeID=".$dataa[$i]['employeeID']."\">";
	echo "<font face=\"arial\" size=\"-2\">DEACTIVATE</font></a>";
	echo "</td>";
	echo "<td bgcolor=C0C0C0><font face=\"arial\" size=\"-1\">".$dataa[$i]['firstname']." ".$dataa[$i]['lastname']."</font></td>";
	echo "<td bgcolor=C0C0C0><font face=\"arial\" size=\"-1\">".$dataa[$i]['title']."</font></td>";
	echo "</tr>";
}
echo "</table>";
echo "<form action=\"editemp2.php\" method=\"post\">";
echo "<input type=\"submit\" name=\"submit\" value=\" View DeActivated Users \">";
echo "</form>";
?>
<script type="text/javascript" src="<?php echo $mydirectory; ?>/js/jquery.min.js"></script>
<script type="text/javascript">
 function  search()
 {
data="";

 if($.trim($("#employee").val())!="")
    {
    if(data=="")
      data+='?employee='+$.trim($("#employee").val());  
    else
      data+='&employee='+$.trim($("#employee").val());    
    }   
     
    
    
    if(data!="")
    location.href='editemp0.php'+data;
else
    location.href='editemp0.php';
 }
 </script>
<?php
require('../trailer.php');
?>
