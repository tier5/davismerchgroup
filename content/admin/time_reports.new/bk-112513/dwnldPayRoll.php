<?php
require('Application.php');
$return_arr = array();
$return_arr['fileName'] = "";

extract($_POST);
$datalist=array();
$path = $mydirectory."/upload_files/reports/";
$filename = "PayRoll_csvfile.csv";
$fullPath = $path.$filename;
$return_arr = array();

$return_arr['fileName'] = "";
if(file_exists($fullPath))
{
	@ chmod($fullPath,0777);
	@ unlink($fullPath);
}


if(isset($_REQUEST['employee']))
{
	if($_REQUEST['employee'] > 0)
	{
		$search_sql .= " and t.emp_id=".$_REQUEST['employee'];
		
	}
}
if(isset($_REQUEST['store']))
{
	if($_REQUEST['store'] != '')
	{
		$search_sql .= " and store='".$_REQUEST['store']."'";
	
	}
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


$sql = 'SELECT distinct e.*,reg.region as region,t.*,odo.* FROM dtbl_timesheet as t left join "employeeDB" as e on e."employeeID" = t.emp_id left join dtbl_odometer as odo on odo.time_id=t.time_id'
.' left join tbl_region as reg on reg.rid=e.region  ';  

  if($search_sql!="")  
$sql .= ' WHERE t.emp_id>0 '.$search_sql;

$sql .= ' order by e."employeeID"';
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


$content = ',,,Time Reports       '."\n\n";
$file=fopen($fullPath,'w');
$content .= 'S or H,EMPLOYEE,REGION,City/Location,PAY RATE,O/T RATE,REG Hours,OT Hours,DRIVE TIME MISC,MILEAGE,EXPENSES,GRAND TOTAL'."\n";
fwrite($file, $content);
//echo $content;
$emp_ls=array();
//echo count($emp_lst);
for($i=0; $i < count($datalist); $i++)
{
    $flag=0;
   
for($j=0;$j<count($emp_ls);$j++)
{
  /*  $emp_ls[$j]['reg_hours']+=$datalist[$i]['reg_hours'];
    $ot_hr+=$datalist[$i]['ot_hours'];
     $misc_exp+=$datalist[$i]['misc_exp'];
     $daily_tot+=$datalist[$i]['daily_total'];
      $r_mile+=$datalist[$i]['daily_total'];*/
if($emp_ls[$j]['emp']==$datalist[$i]['employeeID'])
{$flag=1; break;}    
}
//echo $datalist[$i]['lastname'].'-'.$reg_hr;
if($flag==0)
{
    $cnt=count($emp_ls);
$emp_ls[$cnt]['emp']=$datalist[$i]['employeeID'];
if($datalist[$i]['salary']=='Yes')
 $emp_ls[$cnt]['soh']="S"  ; 
else 
  $emp_ls[$cnt]['soh']="H"  ; 
$wage=intval(str_replace('$','',$datalist[$i]['wage']));
$emp_ls[$cnt]['emp_name']=$datalist[$i]['lastname'].' '.$datalist[$i]['firstname'];  
$emp_ls[$cnt]['region']=$datalist[$i]['region'];
$emp_ls[$cnt]['city']=$datalist[$i]['city'];
$emp_ls[$cnt]['wage']= $wage;  
$emp_ls[$cnt]['ot_rate']= $wage*1.5;  
$emp_ls[$cnt]['reg_hours']=$datalist[$i]['reg_hours'];
$emp_ls[$cnt]['ot_hours']=$datalist[$i]['ot_hours'];
$emp_ls[$cnt]['r_mile']=$datalist[$i]['daily_total'];
//$emp_ls[$cnt]['milage']=$datalist[$i]['reimburse_mile'];//($datalist[$i]['reimburse_mile']*.405)-($wage/2);
if($emp_ls[$cnt]['milage']<0)$emp_ls[$cnt]['milage']=0;
$emp_ls[$cnt]['misc_exp']=$datalist[$i]['misc_exp'];
$emp_ls[$cnt]['daily_tot']=$daily_tot;
$emp_ls[$cnt]['grant_total']= 0;//($datalist[$i]['reg_hours']* $wage) + ($datalist[$i]['ot_hours'] * $emp_ls[$cnt]['ot_rate']) + ($emp_ls[$cnt]['milage']) + ($emp_ls[$cnt]['misc_exp']);
$emp_ls[$i]['milage']=0;
}
else {
 //$milage=($datalist[$i]['reimburse_mile']*.405)-($emp_ls[$j]['wage']/2);
//if($milage<0)$milage=0;
//$emp_ls[$j]['milage']+=$datalist[$i]['reimburse_mile'];

  $emp_ls[$j]['reg_hours']+=$datalist[$i]['reg_hours'];  
  $emp_ls[$j]['ot_hours']+=$datalist[$i]['ot_hours'];
  $emp_ls[$j]['misc_exp']+=$datalist[$i]['misc_exp'];
  $emp_ls[$j]['daily_tot']+=$datalist[$i]['daily_total'];
  $emp_ls[$j]['r_mile']+=$datalist[$i]['daily_total'];
//$emp_ls[$j]['grant_total']+= ($datalist[$i]['reg_hours']* ($emp_ls[$j]['wage']) ) + ($datalist[$i]['ot_hours'] * $emp_ls[$j]['ot_rate']) + $milage + ($datalist[$i]['misc_exp']);

}
}
for($i=0;$i<count($emp_ls);$i++)
{
    if($emp_ls[$i]['emp_name']=='') continue;
 //  if($emp_ls[$i]['emp_name']=='') continue;
if($emp_ls[$i]['r_mile']>=30){    
$emp_ls[$i]['milage']= ($emp_ls[$i]['r_mile']*.405)-($emp_ls[$i]['wage']/2);   
if($emp_ls[$i]['milage']<0) $emp_ls[$i]['milage']=0;
}
else $emp_ls[$i]['milage']=0;
$emp_ls[$i]['grant_total']= ($emp_ls[$i]['reg_hours']* ($emp_ls[$i]['wage']) ) + ($emp_ls[$i]['ot_hours'] * $emp_ls[$i]['ot_rate']) + $emp_ls[$i]['milage']+ ($emp_ls[$i]['misc_exp']);
    
}


$tot_reg_hours=0;
$tot_ot_hours=0;
$tot_grant_total=0;
//echo $emp_lst[2].'ff1';
//print_r($emp_ls);
$content ="";
for($i=0; $i < count($emp_ls); $i++)
{
    
    if($emp_ls[$i]['emp_name']=='') continue;
  $content .= '"'.rtrim(str_replace('"','""',$emp_ls[$i]['soh'])).'",'; 
  $content .= '"'.$emp_ls[$i]['emp_name'].'",';
  $content .= '"'.$emp_ls[$i]['region'].'",';
  $content .= '"'.$emp_ls[$i]['city'].'",';
	$content .= '"'.$emp_ls[$i]['wage'].'",';
	$content .= '"'.rtrim(str_replace('"','""',$emp_ls[$i]['ot_rate'])).'",';
	$content .= '"'.rtrim(str_replace('"','""',$emp_ls[$i]['reg_hours'])).'",';
	$content .= '"'.rtrim(str_replace('"','""',$emp_ls[$i]['ot_hours'])).'",';
        $content .= '"'.rtrim(str_replace('"','""',$emp_ls[$i]['r_mile'])).'",';
         $content .= '"'.rtrim(str_replace('"','""',$emp_ls[$i]['milage'])).'",';
           $content .= '"'.rtrim(str_replace('"','""',$emp_ls[$i]['misc_exp'])).'",';
         
	$content .= '"$'.rtrim(str_replace('"','""',$emp_ls[$i]['grant_total'])).'"'."\n";
        
         $tot_reg_hours+=$emp_ls[$i]['reg_hours'];    
 $tot_ot_hours+=$emp_ls[$i]['ot_hours'];  
 $tot_grant_total+=$emp_ls[$i]['grant_total'];  
}


$content.='"","","",TOTALS,"",""';
$content.=',"'.$tot_reg_hours.'"';
$content.=',"'.$tot_ot_hours.'"';
$content.=',"","",""';
$content.=',"$'.$tot_grant_total.'"';

	fwrite($file, $content);
	$content ="";

fclose($file);
$return_arr['fileName'] = $filename;
echo json_encode($return_arr);
exit; 		
?>