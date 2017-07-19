<?php
require 'Application.php';
extract($_POST);
if($upid > 0)
{
    $query  = "SELECT file_name, file_type FROM prj_uploads WHERE upid=$upid;";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed File query: " . pg_last_error($connection));
        exit;
    }
    $row = pg_fetch_array($result);
    pg_free_result($result);
    
    if($row['file_type'] == 'F')
    {
        @unlink ($file_dir.$row['file_name']);
    }
    else if($row['file_type'] == 'I')
    {
        @unlink ($image_dir.$row['file_name']);
        @unlink ($image_dir.'thumb/'.$row['file_name']);
    }
    $query = "DELETE FROM prj_uploads WHERE upid=$upid;";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed Delete query: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
}
require 'get_project_files.php';

echo $html;
?>
