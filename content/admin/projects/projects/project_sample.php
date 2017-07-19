<?php
require('Application.php');
$ret=array();
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
?>
<div id="proj_tab<?php echo $tabid;?>">
<form action="project_submit.php" method="post" id="project_form">
 
    <table width="90%" class="project">
        <tr>
            <td valign="top">
                <table align="center" id="proj_tbl">
                
                     <tr id="u_file" <?php if($pid == 0) echo 'style="display:none;"' ?>>
                        <td align="right">Uploads:</td>
                        <td align="left"><input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="file" id="file" onchange="javascript:ajaxFileUpload('file');" value="<?php echo stripslashes($datalist['file']); ?>"/></td>
                    </tr>
                    <tr>
                        <td align="right">Client: </td>
                        <td><select name="cid" id="cid" onchange="u_cid(this)">
                                <?php
                                $c_index = -1;
                                if ($pid == 0)
                                    $c_index = 0;
                                $script  = "\n" . '<script type="text/javascript"> ' . "\nvar client = {";
                                for ($i = 0; $i < count($client); $i++)
                                {
                                    $script .= "\n" . $client[$i]['ID'] . ":'" . $client[$i]['clientID'] . "',";
                                    if ($project['cid'] == $client[$i]['ID'])
                                    {
                                        echo '<option selected="selected" value="' . $client[$i]['ID'] . '">' . $client[$i]['client'] . '</option>';
                                        $c_index = $i;
                                    }
                                    else
                                    {
                                        echo '<option value="' . $client[$i]['ID'] . '">' . $client[$i]['client'] . '</option>';
                                    }
                                }
                                $script = substr($script, 0, strlen($script) - 1);
                                $script .= "}\n$( '#due_date' ).datepicker();</script>";
                                ?>
                            </select> </td>
                    </tr>
                    <?php if(0){?>
                    <tr>
                        <td align="right">Client ID:</td>
                        <td align="left"><input type="text" readonly="readonly" name="client_id" id="client_id" value="<?php if ($c_index >= 0) echo $client[$c_index]['clientID'] ?>"/></td>
                    </tr><?php } ?>
                    <tr>
                        <td align="right">Site Location:</td>
                        <td align="left"><input type="text" name="location" value="<?php echo $project['location'] ?>"/></td>
                    </tr>
                    <tr>
                        <td align="right">Start Time:</td>
                        <td align="left"><input type="text" name="st_time" id="st_time" readonly="readonly" onclick="javascript:showDate(this);" value="" /></td>
                    </tr>
                   
                    <tr>
                        <td align="right">Start Date:</td>
                        <td align="left"><input type="text" onclick="javascript:showDate(this);"  name="due_date" id="due_date" readonly="readonly" value="<?php if($pid > 0) echo date('m/d/Y', $project['due_date']);?>" /></td></td>
                    </tr>
                    <tr>
                        <td align="right">Merchandiser :</td>
                        <td align="left"><select name="merch_1" ><option value="0">--Select--</option>
                                <?php
                                for ($i = 0; $i < count($employee); $i++)
                                {
                                    if ($project['merch_1'] == $employee[$i]['employeeID'])
                                    {
                                        echo '<option selected="selected" value="' . $employee[$i]['employeeID'] . '">' . $employee[$i]['firstname'] . ' ' . $employee[$i]['lastname'] . '</option>';
                                        $c_index = $i;
                                    }
                                    else
                                    {
                                        echo '<option value="' . $employee[$i]['employeeID'] . '">' . $employee[$i]['firstname'] . ' ' . $employee[$i]['lastname'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                  
 
                   
                    
                   <tr>
<td align="right">Notes:</td>
<td><textarea name="notes" cols="25" rows="4"><?php echo $project['notes'] ?></textarea></td>
</tr>
<tr>
                    <tr>
                        <td colspan="2" align="center" valign="top">
                            <input type="button" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" id="submitButton" name="submitButton" value="Save" onclick="javascript: submit_form();"/>
                            <input type="reset" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" value="Reset" />
                        </td>
                    </tr>
                </table>
            </td>
            <td valign="top" align="right">
                <div style="height:330px;overflow-y: auto;overflow-x: hidden;" id="file_view">
                </div>
            </td>
        </tr>
    </table>
</td>
</form>
</div>
    

<?php
/*$ret['html']=$html;
header('Content-type: application/json');
echo json_encode($ret);*/
?>