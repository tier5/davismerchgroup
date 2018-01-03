<?php
require('Application.php');
$return_arr = array();
$return_arr['fileName'] = "";

extract($_REQUEST);
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
    //$st=split(' ',$_REQUEST['start_dt']);
	$start = strtotime($_REQUEST['start_dt']);
	if($start)
	{
		$search_sql .= " and t.start_time>='".$start."'";
				
	}
       
}
if(isset($_REQUEST['end_dt']))
{
    //$et=split(' ',$_REQUEST['end_dt']);
	$end = strtotime($_REQUEST['end_dt']);
	if($_REQUEST['start_dt'] != '' && $_REQUEST['start_dt'] == $_REQUEST['end_dt'])
		$end = strtotime('+1 day', $end);		
	if($end)
	{
		$search_sql .= " and t.start_time<='".$end."'";
		
	}
}
if(isset($_REQUEST['region']))
{
	if($_REQUEST['region'] > 0)
	{
		$search_sql .= " and e.region=".$_REQUEST['region'];
	}
}
if(isset($_REQUEST['status']) && $_REQUEST['status'] != '')
{
	$search_sql .= " and t.status='".$_REQUEST['status']."'";
	
}


$sql = 'SELECT distinct e.*, '.
	'reg.region as region, '.
	't.id as time_id, '.
	't.clockin as start_time, '.
	't.clockout as end_time, '.
	't.emp_id, '.
	't.store, '.
	't.hours_worked, '.
	't.reg_hours, '.
	't.ot_hours, '.
	't.break1, '.
	't.break2, '.
	't.lunch_start, '.
	't.lunch_end, '.
	't.lunch_hrs, '.
	't.store_num, '.
	't.approval as status, '.
	't.company_rep as company_rep, '.
	't.client as client, '.
	't.lunch_time as lunch_time, '.
	't.other as other, '.
	't.dt_hours as dt_hours, '.
	't.pid as pid, '.
	't.m_id as m_id, '.
	't.mileage as milage '.
//	'odo.* '.
	'FROM timeclock_new as t '.
	'left join "employeeDB" as e on e."employeeID" = t.emp_id '.
//	'left join dtbl_odometer as odo on odo.time_id = t.id '.
	'left join tbl_region as reg on reg.rid = e.region ';  

  if($search_sql!="")  
$sql .= ' WHERE t.emp_id>0 '.$search_sql;

$sql .= ' order by e.lastname,e.firstname';
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




error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/New_York');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once $mydirectory.'/PHPExcel/Classes/PHPExcel.php';

$border_row=array();
$join_type='left join';
$where = " and  prj.status =1";  //$client_sql";
//if ($query) $where .= " AND $qtype LIKE '%".  pg_escape_string($query)."%' ";
$cl_flag=0;
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);


$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Davis Merchandising Group")
							 ->setLastModifiedBy("Davis")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


$ex_clm=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI',
 'AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ',
    'BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');



$ex_cl_i=1;


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$ex_cl_i.':N'.$ex_cl_i);
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'Time Reports');
 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':N'.$ex_cl_i)->getFont()->setBold('true');
 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setSize(16);
$ex_cl_i+=1;
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'S or H');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ex_cl_i ,'EMPLOYEE');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ex_cl_i,'REGION');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ex_cl_i,'City/Location');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ex_cl_i,'PAY RATE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ex_cl_i,'O/T RATE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ex_cl_i,'D/T RATE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,'REG Hours');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ex_cl_i,'OT Hours');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ex_cl_i,'DT Hours');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ex_cl_i,'Total Miles');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ex_cl_i,'MILEAGE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ex_cl_i,'EXPENSES');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ex_cl_i,'GRAND TOTAL');


 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':N'.$ex_cl_i)->getFont()->setBold('true');
 $ex_cl_i+=1;

//print_r($datalist);

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
      		$r_mile+=$datalist[$i]['daily_total'];
		 */
		
		if(isset($emp_ls[$j]['emp'])&&$emp_ls[$j]['emp']==$datalist[$i]['employeeID'])
		{$flag=1; break;}    
	}
	//echo $datalist[$i]['lastname'].'-'.$reg_hr;
	$datalist[$i]['daily_total'] = 0;
	$datalist[$i]['misc_exp'] = 0;
	$datalist[$i]['daily_total']-=30;
	if($datalist[$i]['daily_total']<0) $datalist[$i]['daily_total']=0;
	if($flag==0)
	{
    		$cnt=count($emp_ls);
    		$emp_ls[$cnt]=array();
    		$emp_ls[$cnt]['milage']=0;
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
		$emp_ls[$cnt]['dt_rate']= $wage*2;  
		$emp_ls[$cnt]['reg_hours']=$datalist[$i]['reg_hours'];
		$emp_ls[$cnt]['ot_hours']=$datalist[$i]['ot_hours'];
		$emp_ls[$cnt]['dt_hours']=$datalist[$i]['dt_hours'];
		$emp_ls[$cnt]['r_mile']=$datalist[$i]['daily_total'];
		//$emp_ls[$cnt]['milage']=$datalist[$i]['reimburse_mile'];//($datalist[$i]['reimburse_mile']*.405)-($wage/2);
		if($emp_ls[$cnt]['milage']<0)$emp_ls[$cnt]['milage']=0;
		$emp_ls[$cnt]['misc_exp']=$datalist[$i]['misc_exp'];
		$emp_ls[$cnt]['daily_tot']=$datalist[$i]['daily_total'];
		$emp_ls[$cnt]['grant_total']= 0;//($datalist[$i]['reg_hours']* $wage) + ($datalist[$i]['ot_hours'] * $emp_ls[$cnt]['ot_rate']) + ($emp_ls[$cnt]['milage']) + ($emp_ls[$cnt]['misc_exp']);
		$emp_ls[$i]['milage']=0;
	}
	else {
 		//$milage=($datalist[$i]['reimburse_mile']*.405)-($emp_ls[$j]['wage']/2);
		//if($milage<0)$milage=0;
		//$emp_ls[$j]['milage']+=$datalist[$i]['reimburse_mile'];

  		$emp_ls[$j]['reg_hours']+=$datalist[$i]['reg_hours'];  
  		$emp_ls[$j]['ot_hours']+=$datalist[$i]['ot_hours'];
  		$emp_ls[$j]['dt_hours']+=$datalist[$i]['dt_hours'];
  		$emp_ls[$j]['misc_exp']+=$datalist[$i]['misc_exp'];
  		$emp_ls[$j]['daily_tot']+=$datalist[$i]['daily_total'];
  		$emp_ls[$j]['r_mile']+=$datalist[$i]['daily_total'];
		//$emp_ls[$j]['grant_total']+= ($datalist[$i]['reg_hours']* ($emp_ls[$j]['wage']) ) + ($datalist[$i]['ot_hours'] * $emp_ls[$j]['ot_rate']) + $milage + ($datalist[$i]['misc_exp']);

	}
}
for($i=0;$i<count($emp_ls);$i++)
{
	if(!isset($emp_ls[$i]['emp_name'])||$emp_ls[$i]['emp_name']=='') continue;
 	//  if($emp_ls[$i]['emp_name']=='') continue;
	if($emp_ls[$i]['r_mile']>=30){    
  		$emp_ls[$i]['wage']=str_replace('$','',$emp_ls[$i]['wage']);   
		//$emp_ls[$i]['milage']= ($emp_ls[$i]['r_mile']*$mileage_rate)-($emp_ls[$i]['wage']/2);   
 		$emp_ls[$i]['milage']= ($emp_ls[$i]['r_mile']*$mileage_rate);  
		if($emp_ls[$i]['milage']<0) $emp_ls[$i]['milage']=0;
	}
	else $emp_ls[$i]['milage']=0;
	
	$emp_ls[$i]['grant_total']= ($emp_ls[$i]['reg_hours']* ($emp_ls[$i]['wage']) ) + ($emp_ls[$i]['ot_hours'] * $emp_ls[$i]['ot_rate']) + ($emp_ls[$i]['dt_hours'] * $emp_ls[$i]['dt_rate']) + $emp_ls[$i]['milage']+ ($emp_ls[$i]['misc_exp']);
    
}


$tot_reg_hours=0;
$tot_ot_hours=0;
$tot_dt_hours=0;
$tot_grant_total=0;
//echo $emp_lst[2].'ff1';
//print_r($emp_ls);
$content ="";
for($i=0; $i < count($emp_ls); $i++)
{
    
    if(!isset($emp_ls[$i]['emp_name'])||$emp_ls[$i]['emp_name']=='') continue;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,rtrim(str_replace('"','""',$emp_ls[$i]['soh']))); 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ex_cl_i,$emp_ls[$i]['emp_name']);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ex_cl_i,$emp_ls[$i]['region']);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ex_cl_i,$emp_ls[$i]['city']);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ex_cl_i,$emp_ls[$i]['wage']);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ex_cl_i,rtrim(str_replace('"','""',$emp_ls[$i]['ot_rate'])));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ex_cl_i,rtrim(str_replace('"','""',$emp_ls[$i]['dt_rate'])));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,rtrim(str_replace('"','""',$emp_ls[$i]['reg_hours'])));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ex_cl_i,rtrim(str_replace('"','""',$emp_ls[$i]['ot_hours'])));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ex_cl_i,rtrim(str_replace('"','""',$emp_ls[$i]['dt_hours'])));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ex_cl_i,rtrim(str_replace('"','""',$emp_ls[$i]['r_mile'])));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ex_cl_i,rtrim(str_replace('"','""',round($emp_ls[$i]['milage'],2))));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ex_cl_i,rtrim(str_replace('"','""',$emp_ls[$i]['misc_exp'])));
         
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ex_cl_i,'$'.rtrim(str_replace('"','""',$emp_ls[$i]['grant_total'])));
        
         $tot_reg_hours+=$emp_ls[$i]['reg_hours'];    
 $tot_ot_hours+=$emp_ls[$i]['ot_hours'];  
 $tot_dt_hours+=$emp_ls[$i]['dt_hours'];  
 $tot_grant_total+=$emp_ls[$i]['grant_total'];  
$border_row[]=$ex_cl_i;
	
$ex_cl_i+=1;
}

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ex_cl_i,"TOTALS");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,$tot_reg_hours);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ex_cl_i,$tot_ot_hours);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ex_cl_i,$tot_dt_hours);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ex_cl_i,$tot_grant_total);
$border_row[]=$ex_cl_i;

        foreach($border_row as $row)
        {
     $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':N'.$row)->applyFromArray($styleArray);       
        }
unset($styleArray);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Payroll-'.date('m/d/Y').'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;		
?>
