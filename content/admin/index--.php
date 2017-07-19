<?php
require('Application.php');
require('../header.php');
echo "<font face=\"arial\"";
echo "<blockquote>";
echo "<font face=\"arial\" size=\"+2\"<b><center>Administration</center></b></font>";
echo "<p>";
echo "<br><br>";
echo "<center>";
echo "<a href=\"addemp.php\">Add Employee</a> | ";
echo "<a href=\"editemp0.php\">Edit Employee</a> | ";
echo "<a href=\"Timeclock\">Time Clock</a> | ";
echo "<a href=\"knowledgebase/index.php\">Knowledgebase</a> | ";
echo "<a href=\"production/addclient.php\">Add Client</a> | ";
echo "<a href=\"production/editclient1.php\">Update/Delete Client</a> | ";
echo "<a href=\"officecal/admin_menu.php\">Calendar</a>";
echo "<p>";
echo "<a href=\"work/work.add.php\">New Workorder</a> | <a href=\"work/work.view.php\">View Workorders</a> | <a href=\"work/work.type.add1.php\">Add Type and Global Pricing</a> | <a href=\"work/work.type.view.php\">Edit Type and Global Pricing</a>";
echo "<p>";
echo "<a href=\"store/store_list.php\">Add/Edit Chain Management</a> | <a href=\"chain/list_chain.php\">Add/Edit Chain Category</a>";
echo "<p>";
echo "<a href=\"chain_new/chain_new_list.php\">Add/Edit New Chain Management</a>";
echo "<p>";
echo "<a href=\"region/region_list.php\">Add/Edit Region</a>";
require('../trailer.php');
?>
