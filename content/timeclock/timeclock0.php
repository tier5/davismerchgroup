<?php
require("Application.php");

?>
<style>

select {
	font-size: 50px;
}

input[type=submit] {
	width: 250px;
	height: 150px;
	font-size:50px;
	font-weight:bold;
}

</style>
<?php

//name
//prj_name
//chain
//sto_num
//city

$query1 = ("SELECT p.pid as pid, ".
		"p.prj_name as prj_name ".
//		"pmn.confirm as confirm, ".
//		"pmn.merch as merch, ".
//		"pmn.m_id as m_id, ".
//		"pmn.city as city, ".
//		"pmn.view_stat as view_stat, ".
//		"pmn.st_time as st_time, ".
//		"pmn.due_date as due_date ".
//		"tc.chain as chain, ".
//		"tcm.sto_num as sto_num, ".
//		"pm.name as name ".
		"FROM projects as p ".
//		"LEFT JOIN prj_merchants_new as pmn ON pmn.pid = p.pid ".
//		"LEFT JOIN tbl_chain as tc ON tc.ch_id = pmn.location ".
//		"LEFT JOIN tbl_chainmanagement as tcm ON tcm.chain_id = pmn.store_num ".
//		"LEFT JOIN project_main as pm ON pm.m_pid = p.m_pid ".
		"WHERE p.status = '1' ".
		"ORDER BY p.prj_name DESC ");
if(!($result1 = pg_query($connection,$query1))){
	print("Failed query1: $query1 " . pg_last_error($connection));
	exit;
}
while($row1 = pg_fetch_array($result1)){
	$data1[] = $row1;
}

require("./timeclock_header.php");

echo "<div align=\"center\">";

echo "<form action=\"timeclock1.php\" method=\"POST\">";
echo "<table>";
echo "<tr>";
echo "<td><b><font size=\"50px\">Project Name:</font></b></td>";
echo "<td><select name=\"prj_name\">";
for($i=0, $z=count($data1); $i < $z; $i++){
	echo "<option value=\"".$data1[$i]['prj_name']."\">".$data1[$i]['prj_name']."</option>";
}
echo "</select></td>";

echo "<td><input type=\"submit\" name=\"submit\" value=\"Next\"></td>";
echo "</tr>";
echo "</table>";
echo "</form>";

echo "</div>";

require("./timeclock_trailer.php"); 
?>
