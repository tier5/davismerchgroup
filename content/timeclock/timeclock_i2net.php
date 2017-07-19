<?php
//  FileName:  timeclock_i2net.php
//  Usage:  timeclock snap-on procedures
//  Copyright ©12/2002-2006 by Eugene M. Murray at (i2cg) Interactive Ideas Consulting Group, Inc.
//
	require("./ui_adm_facade.php");
	require("./timeclock_header.php");
	$access_device_type_dflt="brwsr";
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$displayid=$_POST["displayID"];
		//$currtime=date("Y-m-d H:i:s");
		$currtime=time();
		$oot=$OF_ref->getobjectref("oo_timeclock");

		if ($oot->verify_person($db,$displayID)) {
			$get_arr=$oot->getObjectKeys();
			$get_name=trim( $get_arr["lastname"].", ".$get_arr["firstname"].' '.$get_arr["middlename"]);
//			$oot->list_array("this->keys: get_name($get_name)", $this->keys, "verify_person");
			
			if ($oot->is_clockedin($db,$displayID)) {
				$timeclock_id_last=$oot->getLastClockin();
				print "Time Record Found for: &nbsp;<b>$get_name</b> &nbsp;<br>\n";
				//
				$get_arr=$oot->getObject($db,0, $timeclock_id_last);
				
				$intime=$get_arr["clock_in"];
				$diff=($currtime - $intime);
				$diffmin=($diff / 60);
//				print "Minute Diff: ".round($diffmin)."<br>\n";
				$diffhour=($diffmin / 60);
//				print "Hour Diff: ".round($diffhour,2)."<br>\n";
				$total=round($diffhour,2);
				$oot->updateObject($db,$timeclock_id_last,$access_device_type);
				print "<br/><b>&nbsp;&nbsp; $get_name - Clocked Out ($timeclock_id_last)!</b><br>\n";
			} 
			else {			
				print "&nbsp;&nbsp; Creating New Time Record for: &nbsp;<b>$get_name</b> &nbsp;<br>\n";
//				print "Bu ID: ".$oot->business_unit_id."<br>\n";
				$oot->addObject($db,$displayID,$access_device_type);
				print "<br/><font size=\"4\"><b>&nbsp;&nbsp;&nbsp; $get_name - Clocked In (".$oot->keys["timeclock_id"].")!</b></font>\n";
			}
		}
		else {
			print "<br/>&nbsp;&nbsp;&nbsp; <b><u>No Person found</u> for this identification number.</b><br>\n";
		}

	}

	$screen_delay=05;
	print "<br/>";
	print "<table border=\"0\">\n";
	print "<tr><td width=\"5%\">";
	print "&nbsp;</td>";
	print "<td>";
	$URL_address='http://'.$host.'/timeclock/';
	print "<table border=\"2\">\n";
	print "<tr><td height=\"25\" valign=\"top\" onclick=\"parent.location='$URL_address';\" >";
	print "<br/><a href=\"$URL_address\"><font size=\"6\"><b>&nbsp; Return &nbsp;</b></font></a>";
	print "</td>\n</table>\n";

	print "</td></tr>\n";
	print "</table>\n";
	require("./timeclock_trailer.php");

	print '<meta http-equiv="refresh" content="'.$screen_delay.';URL=http://'.$host.'/timeclock/" >';
?>
