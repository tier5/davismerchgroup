<?php
require_once 'Application.php';
extract($_POST);

$sql='select fs_id from flexgrid_storage where emp_id='.$_SESSION['employeeID'];
$result = pg_query($connection, $sql);
if(pg_num_rows($result)<=0)
{
    $sql='insert into flexgrid_storage ("emp_id") values('.$_SESSION['employeeID'].')';
    pg_query($connection, $sql);
$sql='select fs_id from flexgrid_storage where emp_id='.$_SESSION['employeeID'];
$result = pg_query($connection, $sql);
$row=pg_fetch_array($result);
$fs_id=$row['fs_id'];
}
 else
{
$row=pg_fetch_array($result);
$fs_id=$row['fs_id'];  
}
pg_free_result($result);

$col='emp_id='.$_SESSION['employeeID'];
if(isset($sortname)&&$sortname!='')
{
 $col.=',sortname=\''.trim($sortname).'\'';  
}
if(isset($sortorder)&&$sortorder!='')
{
 $col.=',sortorder=\''.trim($sortorder).'\'';  
}

if(isset($colum)&&$colum!='')
{
    if($stat=='false')
 $col.=',col'.$colum.'=\'hide\'';  
else
 $col.=',col'.$colum.'=\'show\'';     
 

}



$sql='update flexgrid_storage set '.$col.' where fs_id='.$fs_id;

 pg_query($connection, $sql);

//echo $sql;
?>