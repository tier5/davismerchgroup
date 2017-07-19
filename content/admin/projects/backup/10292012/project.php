<?php
require 'Application.php';

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

$query  = ("SELECT \"employeeID\", firstname, lastname FROM \"employeeDB\" where active='yes' ORDER BY firstname ASC;");
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
    $query  = ("SELECT pid, cid, \"location\", prj_name, due_date, merch_1, merch_2, merch_3 FROM projects where pid = $pid and status = 1");
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
}
?>
<div id="prj_msg" ></div>
<form action="project_submit.php" method="post" id="project_form">
    <input type="hidden" name="pid" id="pid" value="<?php echo $pid ?>"/>
    <table width="90%" class="project">
        <tr>
            <td valign="top">
                <table align="center" id="proj_tbl">
                    <tr>
                        <td align="right">Choose Client: </td>
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
                    <tr>
                        <td align="right">Client ID:</td>
                        <td align="left"><input type="text" readonly="readonly" name="client_id" id="client_id" value="<?php if ($c_index >= 0) echo $client[$c_index]['clientID'] ?>"/></td>
                    </tr>
                    <tr>
                        <td align="right">Location:</td>
                        <td align="left"><input type="text" name="location" value="<?php echo $project['location'] ?>"/></td>
                    </tr>
                    <tr>
                        <td align="right">Project Name:</td>
                        <td align="left"><input type="text" name="prj_name" value="<?php echo $project['prj_name'] ?>"/></td>
                    </tr>
                    <tr>
                        <td align="right">Due Date:</td>
                        <td align="left"><input type="text" name="due_date" id="due_date" readonly="readonly" value="<?php if($pid > 0) echo date('m/d/Y', $project['due_date']);?>" /></td>
                    </tr>
                    <tr>
                        <td align="right">Merchandiser 1:</td>
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
                        <td align="right">Merchandiser 2:</td>
                        <td align="left">
                            <select name="merch_2" ><option value="0">--Select--</option>
<?php
for ($i = 0; $i < count($employee); $i++)
{
    if ($project['merch_2'] == $employee[$i]['employeeID'])
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
                        <td align="right">Merchandiser 3:</td>
                        <td align="left">
                            <select name="merch_3" ><option value="0">--Select--</option>
<?php
for ($i = 0; $i < count($employee); $i++)
{
    if ($project['merch_3'] == $employee[$i]['employeeID'])
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
                    <tr id="u_file" <?php if($pid == 0) echo 'style="display:none;"' ?>>
                        <td align="right">File:</td>
                        <td align="left"><input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="file" id="file" onchange="javascript:ajaxFileUpload('file');" value="<?php echo stripslashes($datalist['file']); ?>"/></td>
                    </tr>
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
<?php echo $script; ?>