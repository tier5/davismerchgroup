<?php
require('Application.php');
echo "<html>";
echo "<head>";
require('../header.php');
$queryd=("SELECT * ".
		 "FROM \"employeeDB\" ".
		 "WHERE active = 'yes' ");
if(!($resultd=pg_query($connection,$queryd))){
	print("Failed queryd: " . pg_last_error($connection));
	exit;
}
while($rowd = pg_fetch_array($resultd)){
	$datad[]=$rowd;
}
echo "<font face=\"arial\">";
echo "<blockquote>";
echo "<center><font size=\"5\">Internal Directory</font>";
echo "<p>";
echo "<table border=\"0\" width=\"40%\">";
echo "<tr>".
                              '<td class="grid001">Name</td> '.
                        	'<td class="grid001">Title</td> '.
                            '<td class="grid001">Address</td> '.
                        	 '<td class="grid001">Phone</td> '.
                                
                            
                          
                            '</tr>';
for($i=0; $i <= count($datad); $i++){
	
	echo "<tr>";
	echo "<td class=\"gridVal\" align=\"left\"><font face=\"arial\"><a href=record.php?employeeID=".$datad[$i]['employeeID'].">".$datad[$i]['lastname']." ".$datad[$i]['firstname']."</a></font></td>";
	echo "<td class=\"gridVal\" align=\"left\"><font face=\"arial\"><b>".$datad[$i]['title']."</b></font></td>";
	echo "<td class=\"gridVal\" align=\"left\"><font face=\"arial\"><b>".$datad[$i]['address']."</b></font></td>";
	echo "<td class=\"gridVal\" align=\"left\"><font face=\"arial\"><b>".$datad[$i]['phone']."</b></font></td>";
	echo "</tr>";
}
echo "</table>";
require('../trailer.php');
?>
