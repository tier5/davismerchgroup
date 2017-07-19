<?php
require 'Application.php';
extract($_POST);
$ret=array();
$ret['status']=0;

if($pid > 0)
{
    $query  = "SELECT * FROM img_glry_files ".
    "  WHERE glry_id=".$glry_id;
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed File query: " . pg_last_error($connection));
        exit;
    }
   // echo $query;
    $query="";
    while($row = pg_fetch_array($result))
    {
    $fname[]=$row;    
    }
    pg_free_result($result);
    
    //print_r($fname);
    
    for($i=0;$i<count($fname);$i++)
    {
        @unlink ($img_glry_dir.$fname[$i]['file_name']);
         $query .= 'DELETE FROM img_glry_files WHERE fid='.$fname[$i]['fid'].';';
        // echo $i.'-'.$query.'<br/';
    }
    
   // echo 'll'.$query;
    $query .= "DELETE FROM img_glry_main WHERE glry_id=$glry_id;";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed Delete query: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
    $ret['status']=1;
}

header('content-type:application/json');
    echo json_encode($ret);


?>