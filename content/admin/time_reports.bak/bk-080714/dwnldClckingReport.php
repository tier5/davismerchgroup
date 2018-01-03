<?php
require('Application.php');
$return_arr = array();
$return_arr['fileName'] = "";

extract($_REQUEST);
$datalist=array();
$path = $mydirectory."/upload_files/reports/";
$filename = "ClockingReport_csvfile.csv";
$fullPath = $path.$filename;
$return_arr = array();

$return_arr['fileName'] = "";
if(file_exists($fullPath))
{
	@ chmod($fullPath,0777);
	@ unlink($fullPath);
}


$sql = 'SELECT  t.*,odo.*,t.status as status_check,TO_CHAR(TO_TIMESTAMP(t.start_time),'.
' \'HH:MI AM\') as start_time,TO_CHAR(TO_TIMESTAMP(t.end_time), \'HH:MI AM\') as end_time,TO_CHAR(TO_TIMESTAMP(t.end_time), \'YYYY\') as end_time_yr,'
 .'prj.*,str.sto_name,c.client ,main.name,chain.chain,m.*,TO_CHAR(TO_TIMESTAMP(m.due_date), \'MM/DD/YYYY\') as due_date,'
 .'m1.firstname as m1first, m1.lastname as m1last,m1.wage,str2.sto_num as str2_sto_num,prj.prj_name as prj_name  FROM dtbl_timesheet as t'
 .' left join dtbl_odometer as odo on odo.time_id=t.time_id  ';  
$sql.=' left join "prj_merchants_new" as m on m.m_id=t.m_id ';
$sql.=' left join "projects" as prj on prj.pid=m.pid ';
 $sql.=' left join "clientDB" as c on c."ID"=m.cid ';
  $sql.=" left join tbl_chain as chain on chain.ch_id=m.location left join project_main as main on main.m_pid=prj.m_pid ";
 $sql .= ' left join "employeeDB" as m1 on m1."employeeID"=t.emp_id ';
  $sql .= " left join tbl_chainmanagement as str on str.chain_id=m.location  left join tbl_region as reg on reg.rid=m1.region ";
  $sql .= " left join tbl_chainmanagement as str2 on str2.chain_id=m.store_num ";
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
		$search_sql .= " and m1.region=".$_REQUEST['region'];
	}
}
if(isset($_REQUEST['status']) && $_REQUEST['status'] != '')
{
	$search_sql .= " and t.status='".$_REQUEST['status']."'";

}
if(isset($_REQUEST['employee']))
{
	if($_REQUEST['employee'] > 0)
	{
		$search_sql .= " and t.emp_id=".$_REQUEST['employee'];
		
	}
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


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$ex_cl_i.':Q'.$ex_cl_i);
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'Clocking Report');
 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':Q'.$ex_cl_i)->getFont()->setBold('true');
 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setSize(16);
$ex_cl_i+=1;
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'Project Name');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ex_cl_i ,'Job ID#');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ex_cl_i,'Start Date');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ex_cl_i,'Merchandiser');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ex_cl_i,'Client');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ex_cl_i,'Chain');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ex_cl_i,'Store#');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,'Start Time');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ex_cl_i,'End Time');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ex_cl_i,'Lunch Hours');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ex_cl_i,'Hours Worked');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ex_cl_i,'Reg Hours');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ex_cl_i,'O.T Hours');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ex_cl_i,'D.T Hours');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ex_cl_i,'Mileage');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$ex_cl_i,'Total mile');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$ex_cl_i,'Total money paid');
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ex_cl_i,'GRAND TOTAL');


 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':Q'.$ex_cl_i)->getFont()->setBold('true');
 $ex_cl_i+=1;

//print_r($datalist);

//echo $content;

//echo count($datalistt);





$content ="";
//echo 'cnt--'.count($datalist);
for($i=0; $i < count($datalist); $i++)
{
$da_tot= $datalist[$i]['daily_total'];
$cost=$da_tot*$datalist[$i]['wage'];
   $datalist[$i]['daily_total']-=30;
   if($datalist[$i]['daily_total']<0)  $datalist[$i]['daily_total']=0;
   $milage= ($datalist[$i]['daily_total']*.405)-($datalist[$i]['wage']/2);
   if($milage<0) $milage=0;   
   // if(!isset($datalist[$i]['pid'])||$datalist[$i]['emp_name']=='') continue;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i]['name']).(($datalist[$i]['status_check']==3)?' (Generated)':''))); 
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ex_cl_i,$datalist[$i]['prj_name']);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ex_cl_i,$datalist[$i]['due_date']);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ex_cl_i,str_replace('"','""',$datalist[$i]['m1first'].' '.$datalist[$i]['m1last']));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ex_cl_i,$datalist[$i]['client']);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ex_cl_i,$datalist[$i]['chain']);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ex_cl_i,$datalist[$i]['str2_sto_num']);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i]['start_time'])));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ex_cl_i,rtrim(str_replace('"','""',(($datalist[$i]['end_time_yr']>2000)?$datalist[$i]['end_time']:''))));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ex_cl_i,rtrim(str_replace('"','""',(isset($datalist[$i]['lunch_hrs'])&&$datalist[$i]['lunch_hrs']>0)?$datalist[$i]['lunch_hrs']:0)));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ex_cl_i,rtrim(str_replace('"','""',(isset($datalist[$i]['hours_worked'])&&$datalist[$i]['hours_worked']>0)?$datalist[$i]['hours_worked']:0)));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ex_cl_i,rtrim(str_replace('"','""',(isset($datalist[$i]['reg_hours'])&&$datalist[$i]['reg_hours']>0)?$datalist[$i]['reg_hours']:0)));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ex_cl_i,rtrim(str_replace('"','""',(isset($datalist[$i]['ot_hours'])&&$datalist[$i]['ot_hours']>0)?$datalist[$i]['ot_hours']:0)));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ex_cl_i,rtrim(str_replace('"','""',(isset($datalist[$i]['dt_hours'])&&$datalist[$i]['dt_hours']>0)?$datalist[$i]['dt_hours']:0)));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ex_cl_i,rtrim(str_replace('"','""',$milage)));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$ex_cl_i,rtrim(str_replace('"','""',$da_tot)));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$ex_cl_i,rtrim(str_replace('"','""',$cost)));
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i]['misc_exp'])));
         
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ex_cl_i,'$'.rtrim(str_replace('"','""',$datalist[$i]['grant_total'])));
        
 
$border_row[]=$ex_cl_i;
	
$ex_cl_i+=1;
}



        foreach($border_row as $row)
        {
     $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':Q'.$row)->applyFromArray($styleArray);       
        }
unset($styleArray);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ClockingReport_csvfile-'.date('m/d/Y').'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;		
?>