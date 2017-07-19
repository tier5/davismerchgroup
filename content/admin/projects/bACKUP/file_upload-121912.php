<?php
require 'Application.php';

extract($_POST);
$error           = "";
$msg             = "";
$fileElementName = $_POST['fid'];
if (!empty($_FILES[$fileElementName]['error']))
{
    switch ($_FILES[$fileElementName]['error'])
    {

        case '1':
            $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            break;
        case '2':
            $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            break;
        case '3':
            $error = 'The uploaded file was only partially uploaded';
            break;
        case '4':
            $error = 'No file was uploaded.';
            break;
        case '6':
            $error = 'Missing a temporary folder';
            break;
        case '7':
            $error = 'Failed to write file to disk';
            break;
        case '8':
            $error = 'File upload stopped by extension';
            break;
        case '999':
        default:
            $error = 'No error code avaiable';
    }
}
elseif (empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
{
    $error = 'No file was uploaded..';
}
else
{
    $msg       = 'File Uploaded!<br/>';
    $msg .= "Name: " . $_FILES[$fileElementName]['name'] . "<br/>";
    $msg .= "Size: " . formatSizeUnits(@filesize($_FILES[$fileElementName]['tmp_name']));
    $file_type = $_FILES[$fileElementName]['type'];
    $file_type = explode('/', $file_type);
    $disp_name = $_FILES[$fileElementName]['name'];
    $ext       = pathinfo($_FILES[$fileElementName]['name'], PATHINFO_EXTENSION);
    $file_name = substr($disp_name, 0, abs(strlen($disp_name) - (strlen($ext)+1))) . '_' . date('U') . '.' . $ext;
    $sql = '';
    if (strtolower($file_type[0]) == 'image')
    {
        require 'image_util.php';
        @move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $image_dir . $file_name);
        $img = new ImageUtil();
        if (file_exists($image_dir . $file_name))
        {
            $img->load($image_dir . $file_name);
            //resize image to 640 X 480
            if ($img->getWidth() > $img->getHeight())
            {
                if ($img->getWidth() > 640)
                    $img->resizeToWidth(640);
            }
            else
            {
                if ($img->getHeight() > 480)
                    $img->resizeToHeight(480);
            }
            $img->save($image_dir . $file_name, $img->getImageType());
            //generate thumb
            if ($img->getWidth() > $img->getHeight())
                $img->resizeToWidth(100);
            else
                $img->resizeToHeight(100);
            $img->save($image_dir . 'thumb/' . $file_name, $img->getImageType());
            $sql = "INSERT INTO prj_uploads(pid, file_type, file_name, disp_name)VALUES (".
                    "$pid, 'I', '$file_name', '$disp_name');";
        }
        else
        {
            $error = 'Unable to copy file to system directory!';
        }
    }
    else
    {
       @move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $file_dir . $file_name);
       if (file_exists($file_dir . $file_name))
       {
       $sql = "INSERT INTO prj_uploads(pid, file_type, file_name, disp_name)VALUES (".
                    "$pid, 'F', '$file_name', '$disp_name');";
       }
       else
       {
           $error = 'Unable to copy file to system directory!';
       }
    }
    //for security reason, we force to remove all uploaded file
    @unlink($_FILES[$fileElementName]);
    if($sql != '')
    {
        if (!($result = pg_query($connection, $sql)))
        {
            $error = "Failed to store information to database! ";
        }
        $sql = '';
        if($result)
            pg_free_result($result);
    }
    require 'get_project_files.php';
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }
    return $bytes;
}

echo "{";
echo "error: '" . $error . "',\n";
echo "msg: '" . $msg . "',\n";
echo "pid: '" . $pid . "',\n";
echo "html: '". $html. "'\n";
echo "}";
?>