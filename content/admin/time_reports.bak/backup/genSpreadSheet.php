<?php
require('Application.php');
$return_arr = array();
$return_arr['fileName'] = "";

extract($_POST);
$datalist=array();
$path = $mydirectory."/upload_files/reports/";
$filename = "Time_Reports_csvfile.csv";
$fullPath = $path.$filename;
$return_arr = array();

$return_arr['fileName'] = "";
if(file_exists($fullPath))
{
	@ chmod($fullPath,0777);
	@ unlink($fullPath);
}

$sql = 'SELECT  t.*,odo.*,e.firstname,e.lastname,odo.daily_total as daily_total,odo.other_exp as other_exp,e.city as city FROM dtbl_timesheet as t left join "employeeDB" as e on e."employeeID" = t.emp_id left join dtbl_odometer as odo on odo.time_id=t.time_id  ';  
if(isset($time_id)&&$time_id!='all')
{
    
$sql .= ' WHERE t.emp_id='.$time_id;
 }
 
 $sql.=' order by t.time_id DESC';
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$datalist[]=$row;
}
pg_free_result($result);






header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=$filename");


$content = ',,,Time Reports       '."\n\n";
$file=fopen($fullPath,'w');
$content .= 'Merchandiser,Client,Store Name & Number,City/Location,Start,in-start,Travel,Daily Miles,Comments,Job Verified,Signoffs,Misc.Expenses'."\n";
fwrite($file, $content);
//echo $content;
$content ="";
for($i=0; $i < count($datalist); $i++)
{
   $travel=$datalist[$i]['misc_exp'];
	$content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['firstname'].' '.$datalist[$i]['lastname'])).'",';
	$content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['client'])).'",';  
	$content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['store'].'-'.$datalist[$i]['store_num'])).'",'; 
    $content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['city'])).'",';
	$content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['prj_estimatecost'])).'",';
	$content .= '"'.rtrim(str_replace('"','""',date('m/d/Y',$datalist[$i]['start_time'].'-'.$datalist[$i]['hours_worked']))).'",';
	$content .= '"'.rtrim(str_replace('"','""',$travel)).'",';
        $content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['daily_total'])).'",';
        $content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['tracking_number'])).'",';
         $content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['reimburse_mile'])).'",';
         $content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['prdtntrgtdelvry'])).'",';
         $content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['other_exp'])).'"'."\n";
	//$content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['misc_exp'])).'55"'."\n";
	
	
	fwrite($file, $content);
	$content ="";
}
fclose($file);
$return_arr['fileName'] = $filename;
echo json_encode($return_arr);
exit; 		
?>