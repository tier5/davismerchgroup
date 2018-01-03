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

$sql = 'SELECT  store.city,store.sto_num as store_num_val,ch.chain,t.*,t.other as others,t.store as store, odo.*,e.firstname,e.lastname,odo.daily_total as daily_total,odo.other_exp as other_exp FROM dtbl_timesheet as t left join "employeeDB" as e on e."employeeID" = t.emp_id left join dtbl_odometer as odo on odo.time_id=t.time_id  ';  
//$sql.=' left join "tbl_store" as store on store.sid=t.store_num'
$sql.=' left join "tbl_store" as store on t.store_num like cast(store.sid as character varying)'.
//" left join tbl_store as store on t.store_num like cast(store.sid as character varying)"
        ' left join tbl_chain as ch on t.store  like cast(ch.ch_id as character varying)';
$search_sql=" t.emp_id > 0";
if(isset($time_id)&&$time_id!='all')
{
    
$search_sql .= ' and t.emp_id='.$time_id;
 }
 
 if(isset($_REQUEST['start_dt']))
{
    $st=split(' ',$_REQUEST['start_dt']);
	$start = strtotime($st[0]);
	if($start)
	{
		$search_sql .= " and t.start_time>='".$start."'";
	
	}
       
}
if(isset($_REQUEST['end_dt']))
{
    $et=split(' ',$_REQUEST['end_dt']);
	$end = strtotime($et[0]);
	if($_REQUEST['start_dt'] != '' && $_REQUEST['start_dt'] == $_REQUEST['end_dt'])
		$end = strtotime('+1 day', $end);		
	if($end)
	{
		$search_sql .= " and t.start_time<='".$end."'";
	
	}
}
if(isset($_REQUEST['status']) && $_REQUEST['status'] != '')
{
	$search_sql .= " and t.status='".$_REQUEST['status']."'";

}
 
 $sql.='where '.$search_sql;
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
//print_r($datalist);





header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=$filename");


$content = ',,Time Reports       '."\n\n";
$file=fopen($fullPath,'w');
$content .= 'Date,Employee,Client,Store Name & Number,Start time,Lunch(hrs),End Time,Hours Worked,Total Mile'."\n";
fwrite($file, $content);
//echo $content;
$content ="";
for($i=0; $i < count($datalist); $i++)
{
	$storenum_cust="";
   $travel=$datalist[$i]['misc_exp'];
   if($datalist[$i]['store']==0){
	   $storenum_cust=$datalist[$i]['others'].'-'.$datalist[$i]['store_num'];
	   } 
   else   $storenum_cust=$datalist[$i]['chain'].'-'.$datalist[$i]['store_num_val'];
   //$storenum_cust=$datalist[$i]['chain2']
	   //$storenum_cust=$datalist[$i]['store_num_val'].'-'.$datalist[$i]['store_num_val'];
   
   $content .= '"'.rtrim(str_replace('"','""',date('m/d/Y',$datalist[$i]['start_time']))).'",'; 
	$content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['firstname'].' '.$datalist[$i]['lastname'])).'",';
	$content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['client'])).'",';  
	$content .= '"'.rtrim(str_replace('"','""',$storenum_cust)).'",'; 
        $content .= '"'.rtrim(str_replace('"','""',date('h:i a', $datalist[$i]['start_time']))).'",'; 
        $content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['lunch_hrs'])).'",'; 
        $content .= '"'.rtrim(str_replace('"','""',date('h:i a', $datalist[$i]['end_time']))).'",'; 
        $content .= '"'.rtrim(str_replace('"','""',date('h:i a', $datalist[$i]['hours_worked']))).'",';
         $content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['daily_total'])).'"'."\n";
	//$content .= '"'.rtrim(str_replace('"','""',$datalist[$i]['misc_exp'])).'55"'."\n";
	
	
	fwrite($file, $content);
	$content ="";
}
fclose($file);
$return_arr['fileName'] = $filename;
echo json_encode($return_arr);
exit; 		
?>