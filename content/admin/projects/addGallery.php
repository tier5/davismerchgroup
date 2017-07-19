<?php
require 'Application.php';
extract($_POST);

if($type=='updt')
 $query  = 'update img_glry_main set  client_id='.$cl_id.' where glry_id='.$glry_id;   
else
$query  = "insert into img_glry_main (pid,client_id) values('".$pid."','".$cl_id."')";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed File query: " . pg_last_error($connection));
        exit;
    }
    
    
    $query  = "select glry_id from img_glry_main where pid='".$pid."' order by glry_id desc limit 1";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed File query: " . pg_last_error($connection));
        exit;
    }
  
    
    $row = pg_fetch_array($result);
    
    header('content-type:application/json');
    echo json_encode($row);
?>