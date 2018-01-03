<?php
//  FileName:  index.php
//  Usage:  main procedure controller 
//  Copyright ©12/2002-2006 by Eugene M. Murray at (i2cg) Interactive Ideas Consulting Group, Inc.
//

require("./timeclock_header.php"); 
//
//
function TDWrapper($arg, $handle_click)  {
	$on_click = ' onclick="handleClick('.$handle_click.');" ';
	if ($handle_click < 0)
		$on_click = '';
	$rtn='';
	$rtn .='<td>'."\n";
	$rtn .=$arg;
	$rtn .='<tr><td '.$on_click.' >&nbsp;'."\n";
	$rtn .='</td></tr>'."\n";
	$rtn .='</table>'."\n";
	$rtn .='</td>'."\n";
	return $rtn;
}
function bldButton($img)  {
	$img_width = ' width="72" ';
	$img_height = ' height="67" ';
	$rtn='';
	$rtn .='<table border="0" cellpadding="0" cellspacing="0" background="'.$img.'" '.$img_width.' '.$img_height.' >';
	return $rtn;
}
//
//  ----------- details
//
$rtn='';
$rtn .='<center>'."\n";
$rtn .='<table width="550" border="0" cellpadding="0" cellspacing="0">'."\n";
$rtn .='<tbody><tr>'."\n";
$rtn .='<td align="center">'."\n";
$rtn .='<table border="0" cellpadding="0" cellspacing="0">'."\n";

$rtn .='<tbody><tr>'."\n";

//$rtn .='<td onclick="handleClick(1);" ><a href="#" ><img src="i2net_files/1.gif" border="0"></a></td>'."\n";
$rtn .=TDWrapper(bldButton("images/1.gif"), 1 );
//$rtn .='<td><a href="#" onclick="handleClick(2);"><img src="i2net_files/2.gif" border="0"></a></td>'."\n";
$rtn .=TDWrapper(bldButton("images/2.gif"), 2 );
//$rtn .='<td><a href="#" onclick="handleClick(3);"><img src="i2net_files/3.gif" border="0"></a></td>'."\n";
$rtn .=TDWrapper(bldButton("images/3.gif"), 3 );
$rtn .='</tr>'."\n";

$rtn .='<tr>'."\n";
//<td><a href="#" onclick="handleClick(4);"><img src="i2net_files/4.gif" border="0"></a></td>
$rtn .=TDWrapper(bldButton("images/4.gif"), 4 );
//<td><a href="#" onclick="handleClick(5);"><img src="i2net_files/5.gif" border="0"></a></td>
$rtn .=TDWrapper(bldButton("images/5.gif"), 5 );
//<td><a href="#" onclick="handleClick(6);"><img src="i2net_files/6.gif" border="0"></a></td>
$rtn .=TDWrapper(bldButton("images/6.gif"), 6 );
$rtn .='</tr>'."\n";

$rtn .='<tr>'."\n";
//<td><a href="#" onclick="handleClick(7)"><img src="i2net_files/7.gif" border="0"></a></td>
$rtn .=TDWrapper(bldButton("images/7.gif"), 7 );
//<td><a href="#" onclick="handleClick(8);"><img src="i2net_files/8.gif" border="0"></a></td>
$rtn .=TDWrapper(bldButton("images/8.gif"), 8 );
//<td><a href="#" onclick="handleClick(9);"><img src="i2net_files/9.gif" border="0"></a></td>
$rtn .=TDWrapper(bldButton("images/9.gif"), 9 );
$rtn .='</tr>'."\n";

$rtn .='<tr>'."\n";
//<td><a href="#"><img src="i2net_files/star.gif" border="0"></a></td>
$rtn .=TDWrapper(bldButton("images/star.gif"), -1 );
//<td><a href="#" onclick="handleClick(0);"><img src="i2net_files/0.gif" border="0"></a></td>
$rtn .=TDWrapper(bldButton("images/0.gif"), 0 );
//<td><a href="#"><img src="i2net_files/pound.gif" border="0"></a></td>
$rtn .=TDWrapper(bldButton("images/pound.gif"), -1 );
$rtn .='</tr>'."\n";

print $rtn;
?>


</tbody></table>

</td>
<td align="center">
<img src="images/t01.gif" border="0"><p>
<form name="submitform" action="timeclock2.php" onsubmit="getID();" method="POST">
<input type="hidden" value="" name="displayID">
<input type="image" src="images/t03.gif" name="submit"><a href="#" onlclick="resetID();"><img src="images/t04.gif" border="0"></a>
<input type="text" name="martin" value="" class="display" readonly="readonly">
</form>
<br>
<!---
<form name="submitme" action="timeclock.php" onsubmit="getVID();" method="POST">
<input type="hidden" value="timeclock_view_index" name="action">
<input type="hidden" value="" name="displayID">
<input type="hidden" value="browser" name="access_device_type">
<input type="image" src="images/t02.gif" name="submit">
</form>
--->
</p></td>
</tr>
</tbody></table>
</center>
<?php require("./timeclock_trailer.php"); ?>
