<?php
require 'Application.php';
extract($_POST);
$ret=array();
$ret['status']=0;

if($pid > 0)
{

    $query = "DELETE FROM out_of_stock WHERE stock_id=$stock_id;";
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