<?php
require 'Application.php';
extract($_POST);
$client_sql = '';
if($is_client){
    $client_sql = ' AND "ID"='.$client_id;
}
$query  = ("SELECT \"ID\", \"clientID\", \"client\", \"active\" " .
        "FROM \"clientDB\" " .
        "WHERE \"active\" = 'yes' $client_sql " .
        "ORDER BY \"client\" ASC");
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result))
{
    $client[] = $row;
}
pg_free_result($result);

$query  = ("SELECT \"employeeID\", firstname, lastname FROM \"employeeDB\" where active='yes' and (emp_type = 0 OR emp_type is null)  ORDER BY firstname ASC;");
if (!($result = pg_query($connection, $query)))
{
    print("Failed employee query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result))
{
    $employee[] = $row;
}
pg_free_result($result);


$pid = 0;
if (isset($_POST['pid']) && $_POST['pid'] > 0)
    $pid = $_POST['pid'];
if ($pid > 0)
{
    $query  = ("SELECT pid, cid, \"location\", prj_name, due_date, merch_1, merch_2, merch_3, notes FROM projects where pid = $pid");
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed project query: " . pg_last_error($connection));
        exit;
    }
    while ($row = pg_fetch_array($result))
    {
        $project = $row;
    }
    pg_free_result($result);
    unset($row);
   
    $merch_list=array();
   $sql = "SELECT merch.m_id,str.sto_name,merch.location,merch.st_time,merch.cid,merch.merch,merch.notes,merch.due_date,prj.prj_name as prjname, c.client, c.\"clientID\", m1.firstname as m1first, m1.lastname as m1last ";
        $sql .= " FROM projects as prj left join prj_merchants as merch on merch.pid=prj.pid  inner join \"clientDB\" as c on c.\"ID\" = merch.cid";
        $sql .= " left join tbl_store as str on str.pid=merch.location";
        $sql .= " left join \"employeeDB\" as m1 on m1.\"employeeID\"=merch.merch  where prj.pid=" . $pid;

       /// echo $sql;

        if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
        while ($row = pg_fetch_array($result)) {
            $merch_list[] = $row;
        }
        pg_free_result($result);


}
$query = ("SELECT pid,sto_name FROM tbl_store ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result)) {
    $data[] = $row;
}
 $script = "<script type='text/javascript'>\n$( '#due_date' ).datepicker(); $('#st_time').timepicker({ ampm: true, minuteGrid: 15});</script>";
?>
<div id="prj_msg" ></div>
<form action="project_submit.php" method="post" id="project_form">
    <input type="hidden" name="form_pid" value="<?php echo $pid;?>"/>
<table width="95%">
    <tr>
        <td>
            <table width="30%">
                <tr>
                    <td align="right">Project Name:</td>
                    <td><input type="text" class="textbox" id="prj_name" name="prj_name" value="<?php echo $project['prj_name'] ?>"/></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <fieldset style="border: 1px solid #333333;" width="80%"><legend><strong>Add Merchandiser</strong></legend>
                <table width="80%">
                    <tr>
                        <td align="right">Merchandiser:</td><td><select name="merch_1" style="width: 200px;"><option value="0">--Select--</option>
                                <?php
                                for ($i = 0; $i < count($employee); $i++)
                                {
                                   
                   echo '<option value="' . $employee[$i]['employeeID'] . '">' . $employee[$i]['firstname'] . ' ' . $employee[$i]['lastname'] . '</option>';
                                   
                                }
                                ?>
                            </select></td>
                        <td width="20px">&nbsp;</td>
                        <td align="right">&nbsp;</td><td>&nbsp;</td>
                        
                    </tr>
                    <tr>
                        <td align="right">Start Date:</td><td><input size="15px" type="text" name="due_date" id="due_date" readonly="readonly" value="<?php echo date('m/d/Y');?>" /></td>
                        <td width="20px">&nbsp;</td>
                        <td align="right">Start Time</td><td><input type="text" size="10px" name="st_time" id="st_time"  value="" /></td>
                        
                    </tr>
                    <tr>
                        <td align="right">Location:</td><td><select name="location" style="width: 200px;" ><option value="0">--Select--</option>
                                <?php
                    for ($i = 0; $i < count($data); $i++) {
                        echo '<option value="' . $data[$i]['pid'] . '">' . $data[$i]['sto_name'] . '</option>';
                    }
                    ?> 
                            </select></td>
                        <td width="20px">&nbsp;</td>
                        <td align="right">Client:</td><td><select name="cid" id="cid" style="width: 200px;">
                                <?php
                                $c_index = -1;
                                if ($pid == 0)
                                    $c_index = 0;
                                for ($i = 0; $i < count($client); $i++)
                                {
                                   
                                        echo '<option value="' . $client[$i]['ID'] . '">' . $client[$i]['client'] . '</option>';
                                    
                                }
                                ?>
                            </select></td>
                        
                    </tr>
                    <tr>
                        <td align="right">Notes:</td><td colspan="4"><textarea name="notes" cols="40" rows="7"></textarea></td>
                        
                    </tr>                     
                </table>
            </fieldset>
        </td>
    </tr>
    <tr>
        <td colspan="5" align="left" valign="top">
            <input type="button" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" id="submitButton" name="submitButton" value="Save" onclick="javascript: submit_form();"/>
            <input type="reset" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" value="Reset" />
        </td>
    </tr>
</table>
</form>
<table width="95%">
    <tr>
        <td class="grid001">Merchandiser </td>
        <td class="grid001">Start Date</td>
        <td class="grid001">Start Time</td>
        <td class="grid001">Location</td>
        <td class="grid001">Client</td>
        <td class="grid001">Notes</td>
        <td class="grid001">Delete</td>
    </tr>
    
  <?php 
  for($i=0;$i<count($merch_list);$i++)
  {
    echo '<tr><td class="gridVal">' . $merch_list[$i]['m1first'] . ' ' . $merch_list[$i]['m1last'] . '</td>';
    echo '   <td class="gridVal">' . date('m/d/Y', $merch_list[$i]['due_date']) . '</td>';
    echo '  <td class="gridVal">' . $merch_list[$i]['st_time'] . '</td>';
    echo ' <td class="gridVal">' . $merch_list[$i]['sto_name'] . '</td>';
    echo ' <td class="gridVal">' . $merch_list[$i]['client'] . '</td>';
    echo ' <td class="gridVal">' . $merch_list[$i]['notes'] . '</td>';
    echo '<td class="gridVal" ><img width="20" height="20" src="' . $mydirectory . '/images/1277880471_close.png" style="cursor:pointer;cursor:hand;" onclick="javascript:deleteMerchants(' . $merch_list[$i]['m_id'] . ',' . $pid . ')"/></td> </tr>';
}
  ?>
   
</table>
<?php echo $script; ?>