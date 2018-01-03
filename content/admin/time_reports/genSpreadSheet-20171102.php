<?php
require('Application.php');
$return_arr = array();
$return_arr['fileName'] = "";
//print_r($_GET);

extract($_REQUEST);
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

$sql = 'SELECT  store.city,store.sto_num as store_num_val,ch.chain,t.*,TO_CHAR(TO_TIMESTAMP(t.start_time), \'MM/DD/YYYY\') as st_date,TO_CHAR(TO_TIMESTAMP(t.start_time), \'HH12:MI AM\') as st_time,TO_CHAR(TO_TIMESTAMP(t.end_time), \'HH12:MI AM\') as end_time,TO_CHAR(TO_TIMESTAMP(t.end_time), \'YYYY\') as end_time_yr,t.other as others,t.store as store, odo.*,e.wage,e.firstname,e.lastname,odo.daily_total as daily_total,odo.other_exp as other_exp FROM dtbl_timesheet as t left join "employeeDB" as e on e."employeeID" = t.emp_id left join dtbl_odometer as odo on odo.time_id=t.time_id  ';  
//$sql.=' left join "tbl_store" as store on store.sid=t.store_num'
$sql.=' left join "tbl_chainmanagement" as store on store.chain_id=t.store_num::bigint '.
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
		$search_sql .= " and t.start_time>='".strtotime($_REQUEST['start_dt'])."'";
	
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
		$search_sql .= " and t.start_time<='".strtotime($_REQUEST['end_dt'])."'";
	
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


 //echo 'time---'.date('m/d/Y h:i a','1388896620');  


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


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$ex_cl_i.':K'.$ex_cl_i);
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'Time Reports');
 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':K'.$ex_cl_i)->getFont()->setBold('true');
 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setSize(16);
$ex_cl_i+=1;
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'Date');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ex_cl_i ,'Employee');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ex_cl_i,'Client');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ex_cl_i,'Store Name');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ex_cl_i,'Store Number');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ex_cl_i,'Start time');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ex_cl_i,'Lunch(hrs)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,'End Time');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ex_cl_i,'Hours Worked');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ex_cl_i,'Mileage');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ex_cl_i,'Total Mile');

 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':K'.$ex_cl_i)->getFont()->setBold('true');
 $ex_cl_i+=1;
 
//print_r($datalist);
for($i=0; $i < count($datalist); $i++)
{
	$storenum_cust="";
   $travel=$datalist[$i]['misc_exp'];
   if($datalist[$i]['store']==0){
      $storename_cust='Other';     
      $storenum_cust=$datalist[$i]['others'];
	   } 
   else   
   {
       $storename_cust=$datalist[$i]['chain'];     
      $storenum_cust=$datalist[$i]['store_num_val'];
   }
   //$storenum_cust=$datalist[$i]['chain2']
	   //$storenum_cust=$datalist[$i]['store_num_val'].'-'.$datalist[$i]['store_num_val'];
   $datalist[$i]['daily_total']-=30;
   if($datalist[$i]['daily_total']<0)  $datalist[$i]['daily_total']=0;
   $milage='';
   //if($datalist[$i]['wage']!='')
   {
   //$datalist[$i]['wage']=str_replace('$','',$datalist[$i]['wage']);
   $milage= ($datalist[$i]['daily_total']*$mileage_rate);
   if($milage<0) $milage=0;
   }
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,str_replace('"','""',$datalist[$i]['st_date'].(($datalist[$i]['status']==3)?' (Generated)':''))); 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ex_cl_i,(str_replace('"','""',$datalist[$i]['firstname'].' '.$datalist[$i]['lastname'])));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ex_cl_i,(str_replace('"','""',$datalist[$i]['client']))); 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ex_cl_i,rtrim(str_replace('"','""',$storename_cust))); 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ex_cl_i,rtrim(str_replace('"','""',$storenum_cust))); 
              
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i]['st_time']))); 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i]['lunch_hrs']))); 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,rtrim(str_replace('"','""',(($datalist[$i]['end_time_yr']>2000)?$datalist[$i]['end_time']:'')))); 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i]['hours_worked'])));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ex_cl_i,rtrim(str_replace('"','""',round($milage,2)))); 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i]['daily_total'])));

$border_row[]=$ex_cl_i;
	
$ex_cl_i+=1;
}
        foreach($border_row as $row)
        {
     $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':K'.$row)->applyFromArray($styleArray);       
        }
unset($styleArray);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Timereport-'.date('m/d/Y').'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;		
?>