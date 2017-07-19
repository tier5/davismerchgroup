<?php
require 'Application.php';

$error = '';
if (isset($_POST['pid']) && $_POST['pid'] > 0) {
    $query1 = "UPDATE projects SET status =0 WHERE pid = ".$_POST['pid'];
    if (!($result1 = pg_query($connection, $query1))) {
        $error ("Failed Closing project: " . pg_last_error($connection));
        echo json_encode($error);
        return;
    }
    pg_free_result($result1);
    
}
echo json_encode($error);
return;