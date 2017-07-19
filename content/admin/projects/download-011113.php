<?php

require 'Application.php';

extract($_REQUEST);

if ($upid > 0)
{
    $query  = "SELECT file_name, file_type, disp_name FROM prj_uploads WHERE upid=$upid;";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed File query: " . pg_last_error($connection));
        exit;
    }
    $row = pg_fetch_array($result);
    pg_free_result($result);

    $fullPath = '';
    if($row['file_type'] == 'F')
    {
        $fullPath = $file_dir.$row['file_name'];
    }
    else if($row['file_type'] == 'I')
    {
        $fullPath = $image_dir.$row['file_name'];
    }
    
    if ($fd       = fopen($fullPath, "r"))
    {
        $fsize      = filesize($fullPath);
        $path_parts = pathinfo($fullPath);
        $ext        = strtolower($path_parts["extension"]);
        switch ($ext)
        {
            case "pdf":
                header("Content-type: application/pdf"); // add here more headers for diff. extensions
                header("Content-Disposition: attachment; filename=\"" . $row["disp_name"] . "\""); // use 'attachment' to force a download
                break;
            default;
                header("Content-type: application/octet-stream");
                header("Content-Disposition: filename=\"" . $row["disp_name"] . "\"");
        }
        header("Content-length: $fsize");
        header("Cache-control: private"); //use this to open files directly
        while (!feof($fd))
        {
            $buffer = fread($fd, 2048);
            echo $buffer;
        }
        fclose($fd);
    }
}
exit;
?>