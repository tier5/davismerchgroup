<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
echo "<title>$compname Internal Intranet</title>";
echo "<head>";
echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$mydirectory/style.css\" media=\"all\" />";
echo "<link rel='stylesheet' type='text/css' href='".$mydirectory."/css/jquery-ui-1.8.19.custom.css' />"
			."<style type='text/css'>"
			.".gridVal{"
	.'background: url("'.$mydirectory.'/css/images/ui-bg_highlight-hard_100_f4f0ec_1x100.png") repeat-x scroll 50% top #F2F5F7;'
	.'height:25px;'
    .'border: 1px solid #DDDDDD;'
	.'font-family:Tahoma, Verdana, Arial, Helvetica;'
 	.'font-size:12px;'
    .'color: #362B36;}'
	.'</style>';
if(isset($page))
{
	switch($page)
	{
		case 'timesheet':
		{
	 echo "<link rel='stylesheet' type='text/css' href='".$mydirectory."/css/jquery-ui-timepicker-addon.css' />"
	."<link rel='stylesheet' type='text/css' href='".$mydirectory."/js/fullcalendar/fullcalendar.css' />"
	."<link rel='stylesheet' type='text/css' href='".$mydirectory."/js/fullcalendar/fullcalendar.print.css' media='print' />"
    
	."<style type='text/css'>"
	.'#calendar {'
		.'width: 900px;'
		.'margin: 0 auto;'
		."text-align: center;"
		."font-size: 14px;"
		.'font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;'
		.'}'

.'</style>';
			break;
		}
		case 'time_reports':
		{
			
	break;
		}
                case 'project_grid':
                {
                    echo "<link rel='stylesheet' href='".$mydirectory."/project.css' />" ;
                    echo '<link rel="stylesheet" type="text/css" href="'.$mydirectory.'/css/flexigrid.pack.css" />';                   
                   break;
                }
		
	}
}

echo "</head>";
echo "<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>";
echo "<table height=\"100%\" width=\"100%\" border=0 cellpadding=0 cellspacing=0>";
echo "<tr>";
echo "<td height=\"79\" colspan=\"2\" background=\"$mydirectory/images/bg3.gif\">";
echo "<table border=0 cellpadding=0 cellspacing=0>";
echo "<tr>";
echo "<td><img src=\"$mydirectory/images/logo.gif\" width=\"425\" height=\"79\" border=0></td><td>";
//<!---------------------------- top --------------------------------------->
$querytime=("SELECT * ".
		 "FROM \"timeclock\" ".
		 "WHERE \"firstname\" = '$_SESSION[firstname]' AND \"lastname\" = '$_SESSION[lastname]' AND \"status\" = 'in'");
if(!($resulttime=pg_query($connection,$querytime))){
	print("Failed querytime: " . pg_last_error($connection));
	exit;
}
while($rowtime=pg_fetch_array($resulttime)) {
	$datatime[]=$rowtime;
}
echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
echo "<tr>";
echo "<td>";
echo "<font face=\"Verdana\" size=\"+1\" color=\"000000\">";
$date=date("g:i A l, F jS, Y");
echo "<b>$date</b></font><br>";
echo "<font face=\"arial\" size=\"-1\">You are logged in as <b>". $_SESSION['firstname']." ". $_SESSION['lastname']."</b><br>";
if(count($datatime) != 0) {
	$timesnow=mktime();
	$times=$datatime[0]['clockin'];
	$times1=date("m/d/Y", $times);
	$times2=date("g:i A", $times);
	echo "You have been clocked in since <b>$times2</b> on <b>$times1</b><br>";
	if(bcsub("$timesnow", "$times") > 86400){
		echo "<font color=\"red\">You have been clocked in for more than 24 hours</font>";
	}
}else{
	echo "You are not clocked in.";
}
echo "</font>";
echo "</td>";
echo "</tr>";
echo "</table>";
//<!------------------------------ top end ------------------------------->
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td height=\"19\" class=\"color2\" align=\"center\">Welcome to the Intranet</td>";
echo "<td class=\"color1\"><img src=\"$mydirectory/images/02.gif\" width=\"1\" height=\"19\"  border=0></td>";
echo "</tr>";
echo "<tr>";
echo "<td height=\"12\" colspan=\"2\" background=\"$mydirectory/images/bg2.gif\"><img src=\"$mydirectory/images/bg2.gif\" width=\"2\" height=\"12\"  border=0></td>";
echo "</tr>";
echo "<tr>";
echo "<td height=\"*\" width=\"175\" valign=\"top\" class=\"color1\"><img src=\"$mydirectory/images/01.gif\" width=\"175\" height=\"71\" alt=\"Menu\" border=0><br>";
//<!-------------------------------- menu -------------------------------------->
echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">";
echo "<tr><td class=\"menu\" onmouseover=\"this.className='menu_on';\" onmouseout=\"this.className='menu'\" onClick=\"parent.location='$mydirectory/directory/directorytoc.php'\">Internal Directory</td></tr>";
echo "<tr><td class=\"menu\" onmouseover=\"this.className='menu_on';\" onmouseout=\"this.className='menu'\" onClick=\"parent.location='$mydirectory/officecal/list_calendar.php'\">Office Calendar</td></tr>";
echo "<tr><td class=\"menu\" onmouseover=\"this.className='menu_on';\" onmouseout=\"this.className='menu'\" onClick=\"parent.location='$mydirectory/accounting/'\">Accounting</td></tr>";
echo "<tr><td class=\"menu\" onmouseover=\"this.className='menu_on';\" onmouseout=\"this.className='menu'\" onClick=\"parent.location='$mydirectory/production/productiontoc.php'\">Production</td></tr>";
echo "<tr><td class=\"menu\" onmouseover=\"this.className='menu_on';\" onmouseout=\"this.className='menu'\" onClick=\"parent.location='http://mail.i2net.com/sqwebmail'\">E-Mail</td></tr>";
echo "<tr><td class=\"menu\" onmouseover=\"this.className='menu_on';\" onmouseout=\"this.className='menu'\" onClick=\"parent.location='$mydirectory/sales/'\">Sales</td></tr>";
echo "<tr><td class=\"menu\" onmouseover=\"this.className='menu_on';\" onmouseout=\"this.className='menu'\" onClick=\"parent.location='$mydirectory/operations/'\">Operations</td></tr>";
echo "<tr><td class=\"menu\" onmouseover=\"this.className='menu_on';\" onmouseout=\"this.className='menu'\" onClick=\"parent.location='$mydirectory/humanresources/'\">Human Resources</td></tr>";
echo "<tr><td class=\"menu\" onmouseover=\"this.className='menu_on';\" onmouseout=\"this.className='menu'\" onClick=\"parent.location='$mydirectory/support/'\">Support</td></tr>";
echo "<tr><td class=\"menu\" onmouseover=\"this.className='menu_on';\" onmouseout=\"this.className='menu'\" onClick=\"parent.location='$mydirectory/admin/'\">Administration</td></tr>";
echo "<tr><td class=\"menu\" onmouseover=\"this.className='menu_on';\" onmouseout=\"this.className='menu'\"  onclick=\"window.open('http://www.i2net.com/', 'frame1');\">WWW Site</td></tr>";
echo "</table>";
//<!----------------------- menu end ---------------------------->
echo "</td>";
echo "<td height=\"*\" width=\"100%\" align=\"center\" valign=\"top\">";
echo "<table width=\"100%\"><tr>";
echo "<td align=\"center\"><a href=\"$mydirectory/index.php\"><img src=\"$mydirectory/images/top01.gif\" border=\"0\"></a></td>";
//<!----------------------------------------------------------- Clock IN-Out ---------------------------------------------->
if(count($datatime) == "0") {
	echo "<form action=\"$mydirectory/humanresources/timeclock/clockin.php\" method=\"POST\">";
	echo "<td align=\"center\">";
	echo "<input type=\"Hidden\" name=\"FirstName\" value=\"".$_SESSION['firstname']."\">";
	echo "<input type=\"Hidden\" name=\"LastName\" value=\"".$_SESSION['lastname']."\">";
	echo "<input type=\"image\" src=\"$mydirectory/images/clockin.gif\" value=\"Clock In\" border=\"0\">";
	echo "</td>";
	echo "</form>";
}else{
	echo "<form action=\"$mydirectory/humanresources/timeclock/clockout.php\" method=\"POST\">";
	echo "<td align=\"center\">";
	echo "<input type=\"Hidden\" name=\"FirstName\" value=\"".$_SESSION['firstname']."\">";
	echo "<input type=\"Hidden\" name=\"LastName\" value=\"".$_SESSION['lastname']."\">";
	echo "<input type=\"image\" src=\"$mydirectory/images/clockout.gif\" value=\"Clock Out\" border=\"0\">";
	echo "</td>";
	echo "</form>";
}
//<!----------------------------------------------------------- Clock IN-Out end ---------------------------------------------->
echo "<td align=\"center\"><a href=\"$mydirectory/logout.php\"><img src=\"$mydirectory/images/top03.gif\" border=\"0\"></a></td>";
echo "<td align=\"center\"><a href=\"$mydirectory/help.php\"><img src=\"$mydirectory/images/top04.gif\" border=\"0\"></a></td>";
echo "</tr></table>";
echo "<table width=\"100%\"><tr><td>";
?>
