<?php
//  Usage: timeclock snap-on component
//  Copyright ©12/2002-2006 by Eugene M. Murray at (i2cg) Interactive Ideas Consulting Group, Inc.
//
print "<html><head> \n ";
print "<title>$HTTP_HOST - Timeclock Entry  ::</title> \n";
require("./html_meta_info.php");

?>
<script language="JavaScript">
var userID;
userID="";
function handleClick(addint) {
        userID=userID + addint;
}
function getID() {
        document.submitform.displayID.value = userID;
}
function getVID() {
	document.submitme.displayID.value = userID;
}
function resetID() {
        userID = "";
}

</script>
<style>

TD {
        COLOR: #000000; FONT-FAMILY: verdana, Helvetica, sans-serif; FONT-SIZE: 11px; FONT-WEIGHT: normal
}
A:link {
        COLOR: #003399; TEXT-DECORATION: none
}
A:visited {
        COLOR: #003399; TEXT-DECORATION: none
}
A:active {
        COLOR: #cc0000; TEXT-DECORATION: none
}
A:hover {
        COLOR: #cc0000; TEXT-DECORATION: underline
}
</style></head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#ffffff">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tbody><tr>
<td background="images/tbg.gif"><img src="images/ttop.gif" width="550" height="164" border="0"></td>
<td><a href="<?php echo $mydirectory."/index.php";?>"><input type="button" value="Home"></a></td>
</tr>
</tbody></table>

