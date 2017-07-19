<?php
require('Application.php');
echo "<html>";
echo "<head>";
require('../header.php');


$query=('SELECT "employeeID","firstname","lastname","title"  '.
		 ' FROM "employeeDB" '.
		 'WHERE active = \'yes\' and (emp_type ISNULL OR emp_type = 0) ORDER BY lastname, firstname ASC ');
if(!($resultd=pg_query($connection,$query))){
	print("Failed queryd: " . pg_last_error($connection));
	exit;
}
while($rowd = pg_fetch_array($resultd)){
	$emp[]=$rowd;
}
pg_free_result($resultd);

$query=('SELECT distinct "title"  '.
		 ' FROM "employeeDB" '.
		 'WHERE active = \'yes\' and (emp_type ISNULL OR emp_type = 0) ');
if(!($resultd=pg_query($connection,$query))){
	print("Failed queryd: " . pg_last_error($connection));
	exit;
}
while($rowd = pg_fetch_array($resultd)){
	$title[]=$rowd;
}
pg_free_result($resultd);





$where='';
if(isset($_REQUEST['employee'])&&$_REQUEST['employee']!="")
 $where.=' and "employeeID"='.$_REQUEST['employee']; 
if(isset($_REQUEST['title'])&&$_REQUEST['title']!="")
 $where.=' and "title"=\''.$_REQUEST['title'].'\'';


$queryd="SELECT * ".
		 "FROM \"employeeDB\" ".
		 " WHERE active = 'yes' and (emp_type ISNULL OR emp_type = 0) ";


$queryd.=$where." ORDER BY lastname, firstname ASC ";

//echo $queryd;
if(!($resultd=pg_query($connection,$queryd))){
	print("Failed queryd: " . pg_last_error($connection));
	exit;
}
while($rowd = pg_fetch_array($resultd)){
	$datad[]=$rowd;
}
pg_free_result($resultd);
echo "<font face=\"arial\">";
echo "<blockquote>";
echo "<center><font size=\"5\">Internal Directory</font>";?>


<fieldset><legend><strong style="font-size:14px;">Search</strong></legend>
    <form accept-charset="utf-8" name="search_frm" id="search_frm" method="post" action="">
        <table width="100%" border="0" cellspacing="0" cellpadding="0"  style="padding-left:20px;">
        <tr>
          <td width="200" height="30" >Select Employee: </td>
          <td width="10">&nbsp;</td>
          <td width="200" ><select name="employee" id="employee">
              <option value="" selected="selected">Select All</option>
              <?php
			  foreach( $emp as $e){
				  if(isset($_REQUEST['employee']) && $_REQUEST['employee']==$e['employeeID'])
				  	echo '<option selected="selected" value="'.$e['employeeID'].'">'.$e['lastname'].' '.$e['firstname'].'</option>';
				  else
			  		echo '<option value="'.$e['employeeID'].'">'.$e['lastname'].' '.$e['firstname'].'</option>';
			  }
			  ?>              
            </select></td>
          <td width="10">&nbsp;</td>
          <td align="right">Title: </td>
          <td width="10">&nbsp;</td>
          <td><select style="width:150px;" name="title">
              <option value="" selected="selected">Select All</option>   
 <?php
			  foreach( $title as $e){
                              if(trim($e['title'])=="") continue;
				  if(isset($_REQUEST['title']) && $_REQUEST['title']==$e['title'])
				  	echo '<option selected="selected" value="'.$e['title'].'">'.$e['title'].'</option>';
				  else
			  		echo '<option value="'.$e['title'].'">'.$e['title'].'</option>';
			  }
			  ?> 
            </select></td>
        </tr>

        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="30"><input class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" type="submit" name="search" value="Search" />
            <input type="reset" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="cancel" value="Cancel" onclick="javascript:location.href='directorytoc.php'" /></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>         
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
      </table>
      </form></fieldset>


<?php echo "<p>";
echo "<table border=\"0\" width=\"100%\">";
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
