<?php
require('Application.php');
require("./timeclock_header.php");

$prj_name = $_POST['prj_name'];
if($prj_name == ""){
	$prj_name = $_GET['prj_name'];
}

?>

<table align="right">
	<tr>
		<td><a href="<?php echo $mydirectory."/index.php";?>"><img src="<?php echo $mydirectory."/images/top01.gif";?>"></a></td>
	</tr>
</table>

<script type="text/javascript">
function addCode(key){
	var code = document.forms[0].code;
	if(code.value.length < 10){
		code.value = code.value + key;
	}
}

function emptyCode(){
	document.forms[0].code.value = "";
}
</script>

<style>
body {
	text-align:center; 
	background-color:#FFFFFF; 
	font-family:Verdana, Arial, Helvetica, sans-serif;
}	

#keypad {
	margin:auto; 
	margin-top:20px;
}

#keypad tr td {
	vertical-align:middle; 
	text-align:center; 
	border:1px solid #000000; 
	font-size:50px; 
	font-weight:bold; 
	width:200px; 
	height:150px; 
	cursor:pointer; 
	background-color:#666666; 
	color:#CCCCCC;
}

#keypad tr td:hover {
	background-color:#999999; 
	color:#FFFF00;
}

.display {
	width:650px; 
	margin:10px auto auto auto; 
	background-color:#000000; 
	color:#00FF00; 
	font-size:50px; 
	border:1px solid #999999;
}

#message {
	text-align:center; 
	color:#009900; 
	font-size:14px; 
	font-weight:bold; 
	display:none;
}

input[type=submit] {
	width: 250px;
	height: 150px;
	font-size:50px;
	font-weight:bold;
}

</style>

<?php

echo "<form action=\"timeclock2.php\" method=\"POST\">";
echo "<input type=\"hidden\" name=\"prj_name\" value=\"".$prj_name."\">";
echo "<div align=\"center\">Project Name ( $prj_name )</div>";
echo "<table id=\"keypad\" cellpadding=\"5\" cellspacing=\"3\">";
	echo "<tr>";
		echo "<td onclick=\"addCode('1');\">1</td>";
		echo "<td onclick=\"addCode('2');\">2</td>";
		echo "<td onclick=\"addCode('3');\">3</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td onclick=\"addCode('4');\">4</td>";
		echo "<td onclick=\"addCode('5');\">5</td>";
		echo "<td onclick=\"addCode('6');\">6</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td onclick=\"addCode('7');\">7</td>";
		echo "<td onclick=\"addCode('8');\">8</td>";
		echo "<td onclick=\"addCode('9');\">9</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td onclick=\"addCode('*');\">*</td>";
		echo "<td onclick=\"addCode('0');\">0</td>";
		echo "<td onclick=\"addCode('#');\">#</td>";
	echo "</tr>";
echo "</table>";

echo "<input type=\"text\" name=\"code\" value=\"\" class=\"display\" readonly=\"readonly\">";
echo "<br><br>";
echo "<table align=\"center\">";
echo "<tr>";
echo "<td>";
echo "<input type=\"submit\" name=\"submit\" value=\"Clockin\">";
echo "</td>";

echo "</form>";

echo "<form action=\"\" method=\"POST\">";
echo "<input type=\"hidden\" name=\"prj_name\" value=\"".$prj_name."\">";

echo "<td>";
echo "<input type=\"submit\" name=\"submit\" value=\"Clear\">";
echo "</td>";
echo "</tr>";
echo "</form>";

echo "</table>";

require("./timeclock_trailer.php"); 
?>
