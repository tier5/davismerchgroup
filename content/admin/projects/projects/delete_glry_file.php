<?php
require 'Application.php';
extract($_POST);
if($fid > 0)
{
    $query  = "SELECT file_name FROM img_glry_files WHERE fid=$fid;";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed File query: " . pg_last_error($connection));
        exit;
    }
    $row = pg_fetch_array($result);
    pg_free_result($result);
    
  
        @unlink ($img_glry_dir.$row['file_name']);
    
    
    $query = "DELETE FROM img_glry_files WHERE fid=$fid;";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed Delete query: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
}




require 'glry_view_imgs.php';

      
      
echo $html;

?>
