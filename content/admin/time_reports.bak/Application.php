<?php
//error_reporting(0);
$appdir="../../";
require('../../Application.php');
$mydirectory="../..";
require_once('../../function/ClassFunc.php');
require_once('../../function/ClassDB.php');
include "../../function/Pagination.inc.php"; 
$objFrm=new ClassFunc();
$objDB=new ClassDB();
$getPage= new CommonUtilities();

if((isset($_SESSION['perm_admin']) && $_SESSION['perm_admin'] == "on")||(isset($_SESSION['perm_humanresources']) && $_SESSION['perm_humanresources'] == "on")
        ||(isset($_SESSION['perm_timesheet']) && $_SESSION['perm_timesheet'] == "on")){
}else{
	require('../../header.php');
	echo "<body bgcolor=\"#FFFFFF\">";
	echo "<br><br>";
	echo "<center>";
	echo "<font face=\"arial\">";
	echo "<b>The User ".$_SESSION['firstname']." ".$_SESSION['lastname']." does not have access to this area.</b>";
	echo "</font>";
	echo "</center>";
	echo "</body>";
	require('../../trailer.php');
	exit;
}
?>
