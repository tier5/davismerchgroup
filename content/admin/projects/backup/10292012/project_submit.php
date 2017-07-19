<?php

require 'Application.php';
$message = '';
$ret     = array();
$ret['error'] = '';
$ret['msg']   = '';


extract($_POST);
$ret['pid'] = $pid;
if($due_date != '')//convert m/d/Y to Y-m-d
{
    $date_arr = explode('/',$due_date);
    $due_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);
}
if (!isset($prj_name) || $prj_name == '')
    $message .= 'Project Name missing! ';

if ($message != '')
{
    $ret['error'] = $message;
    echo json_encode($ret);
    return;
}
$sql          = '';
if ($pid == 0)
{
    $sql    = "select count(prj_name)as count from projects where prj_name='$prj_name' and status=1";
    if (!($result = pg_query($connection, $sql)))
    {
        $ret['error'] = "Failed check project name: " . pg_last_error($connection);
        echo json_encode($ret);
        return;
    }
    $row = pg_fetch_array($result);
    pg_free_result($result);
    if (isset($row['count']) && $row['count'] > 0)
    {
        $ret['error'] = 'Project name already exist.!';
        echo json_encode($ret);
        return;
    }

    $sql = "insert into projects (cid,location,prj_name,due_date,merch_1,merch_2,merch_3)values($cid";
    if ($location != '')
        $sql .= ", '" . pg_escape_string($location) . "'"; else
        $sql .= ", null";
    $sql .= ", '" . pg_escape_string($prj_name) . "'";
    if ($due_date >0)
        $sql .= ", $due_date"; else
        $sql .= ", null";
    if ($merch_1 > 0)
        $sql .= ", $merch_1"; else
        $sql .= ", null";
    if ($merch_2 > 0)
        $sql .= ", $merch_2"; else
        $sql .= ", null";
    if ($merch_3 > 0)
        $sql .= ", $merch_3"; else
        $sql .= ", null";
    $sql .= ");";
}
else if ($pid > 0)
{
    $sql = "Update projects set status = 1 ";
    if ($location != '')
        $sql .= ", location='" . pg_escape_string($location) . "'"; else
        $sql .= ", location = null";
    $sql .= ", prj_name = '" . pg_escape_string($prj_name) . "'";
    if ($due_date >0)
        $sql .= ", due_date = $due_date"; else
        $sql .= ",due_date = null";
    if ($merch_1 > 0)
        $sql .= ", merch_1 = $merch_1"; else
        $sql .= ", merch_1 = null";
    if ($merch_2 > 0)
        $sql .= ", merch_2 = $merch_2"; else
        $sql .= ", merch_2 = null";
    if ($merch_3 > 0)
        $sql .= ", merch_3 = $merch_3"; else
        $sql .= ", merch_3 = null";
    $sql .= " Where pid = $pid";
}
if ($sql != '')
{
    if (!($result = pg_query($connection, $sql)))
    {
        $ret['error'] = "Failed to add / edit project: " . pg_last_error($connection);
        echo json_encode($ret);
        return;
    }
    $sql = '';
    pg_free_result($result);
    if ($pid == 0)
    {
        $sql    = "select pid from projects where prj_name='$prj_name' and status=1";
        if (!($result = pg_query($connection, $sql)))
        {
            $ret['error'] = "Failed check project name: " . pg_last_error($connection);
            echo json_encode($ret);
            return;
        }
        $row          = pg_fetch_array($result);
        pg_free_result($result);
        $sql = '';
        if (isset($row['pid']) && $row['pid'] > 0)
        {
            $ret['pid'] = $row['pid'];
        }
    }
    $ret['msg']= "Successfuly saved the project details.";
}
echo json_encode($ret);
return;
?>