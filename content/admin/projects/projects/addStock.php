<?php
require 'Application.php';
extract($_POST);

if($type=='updt')
 $query  = 'update out_of_stock set  client_id='.$cl_id.' where glry_id='.$glry_id;   
else
$query  = "insert into out_of_stock (pid,client_id) values('".$pid."','".$cl_id."')";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed File query: " . pg_last_error($connection));
        exit;
    }
    
    
    $query  = "select stock_id from out_of_stock where pid='".$pid."' order by stock_id desc limit 1";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed File query: " . pg_last_error($connection));
        exit;
    }
  
    
    $row = pg_fetch_array($result);
    
    header('content-type:application/json');
    echo json_encode($row);
?>