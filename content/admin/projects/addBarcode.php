<?php
require 'Application.php';
extract($_POST);

 $query  = 'update out_of_stock set  client_id='.$cl_id.',"bar_code"=\''.
        str_replace("\0", "~~NULL_BYTE~~", serialize($_POST['barcode'])).'\' where stock_id='.$stock_id;   

echo 'hhh'.$query;
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed File query: " . pg_last_error($connection));
        exit;
    }
    

    
   // header('content-type:application/json');
   // echo json_encode($row);
?>