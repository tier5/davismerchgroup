<?php
require('Application.php');
require($JSONLIB.'jsonwrapper.php');
$error = "";
$msg = "";
$return_arr = array();
$return_arr['msg'] = "";
$return_arr['error'] = "";
$return_arr['name'] = "";
$return_arr['file_name']="";
$return_arr['index'] = "";
extract($_POST);
$fileElementName = $_POST['fileId'];
$return_arr['index'] = $_POST['index'];
if(!empty($_FILES[$fileElementName]['error']))
{
	switch($_FILES[$fileElementName]['error'])
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
else if(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
{
	$error = 'No file was uploaded..';
}
else 
{
	$file_type = $_FILES[$fileElementName]['type'];
	$file_name = $_FILES[$fileElementName]['name'];			
	$file_size = $_FILES[$fileElementName]['size'];			
	$file_tmp  = $_FILES[$fileElementName]['tmp_name'];
	$filePath_fileName = $upload_dir.$file_name;			
	$fileName = $file_name;
	$file_extension = substr($fileName,(strripos($fileName, '.')+1),strlen($fileName));
	if((strripos("I".$file_type,"image/") != FALSE)) // upload only image
	{
		require_once($mydirectory.'/imageUpload.class.php');
		
		$imageUpload = new ImageUpload($upload_dir);
		$filePath_fileName = $upload_dir."original/".$fileName;	
		if(file_exists($filePath_fileName)) {
		@ unlink($filePath_fileName);
		}		
		copy( $file_tmp, $filePath_fileName );
		@ chmod($filePath_fileName,0777);
		$return_arr["msg"]= "Requested image file uploaded sucessfully";
		$isImage = 1;
		if($width > 0 && $height > 0)
			$imageUpload->SetNormalWidthHeight($width,$height);
		$arr = $imageUpload->uploadImage($fileElementName);
		$return_arr['name'] = $arr['file'];
		$return_arr['error'] = $arr['error'];
	}
	else if(!strcasecmp($file_extension,'flv'))
	{	
		$return_arr['file_name'] = $fileName;	
		$filename = date('U').'_'.$fileName;
		$filePath_fileName = $upload_dir."video/".$filename;	
		if(file_exists($filePath_fileName)) {
		@ unlink($filePath_fileName);
		}		
		copy( $file_tmp, $filePath_fileName );
		@ chmod($filePath_fileName,0777);
		$fileElementType="image";
		$return_arr["msg"]= " File : Requested file uploaded sucessfully";
		$return_arr['name'] = $filename;
		
	}
	else
	{
		if(file_exists($fileElementName))		
		@unlink($fileElementName);
		@unlink($_FILES[$fileElementName]);
		$error="File Upload Error : Not a valid file type !";
		$return_arr["error"] = $error;
		echo json_encode($return_arr);
		return;
	}
}
//for security reason, we force to remove all uploaded file	
if(file_exists($fileElementName))		
	@unlink($fileElementName);	
$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['size']);
//for security reason, we force to remove all uploaded file
@unlink($_FILES[$fileElementName]);
if($error != "")
	$return_arr["error"] = $error;
echo json_encode($return_arr);
return;
?>
