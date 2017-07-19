<?php
require('Application.php');
$return_arr = array();
$return_arr['fileName'] = "";

extract($_POST);

$path = $mydirectory."/upload_files/reports/";
$filename = "Project_Reports_csvfile.csv";
$fullPath = $path.$filename;
$return_arr = array();

$return_arr['fileName'] = "";
if(file_exists($fullPath))
{
	@ chmod($fullPath,0777);
	@ unlink($fullPath);
}


//$join_type='left join';
//$where = ' where  prj.status =1 and m.region=(select rid from "employeeDB" where "employeeID"='.$_SESSION['employeeID'].') ';  //$client_sql";
//if ($query) $where .= " AND $qtype LIKE '%".  pg_escape_string($query)."%' ";
//$cl_flag=0;
$where='';
if(isset($_REQUEST['date_from'])&&$_REQUEST['date_from']!="")
{

     $date_arr = explode('/',$_REQUEST['date_from']);
    $from_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);

 $where.=' AND m."due_date">='.$from_date;   
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['date_to'])&&$_REQUEST['date_to']!="")
{
      $date_arr = explode('/',$_REQUEST['date_to']);
    $to_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);
 $where.=' AND m."due_date"<='.$to_date;  
 $join_type='inner join';
 $cl_flag=1;
}

$where_main="";
if(isset($_REQUEST['project'])&&$_REQUEST['project']!="")
{
      
 $where.=" and main.m_pid =".$_REQUEST['project']."";   
 //$join_type='inner join';
 $cl_flag=1;
}
if(isset($_REQUEST['jobid_num'])&&$_REQUEST['jobid_num']!="")
{

 $where.="  and prj.prj_name ilike '%".$_REQUEST['jobid_num']."%'";   
 //$join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['st_name'])&&$_REQUEST['st_name']!="")
{
      
 $where.=" AND chain.ch_id =".$_REQUEST['st_name']."";   
 $join_type='inner join';
 $cl_flag=1;
}



if(isset($_REQUEST['sto_num'])&&$_REQUEST['sto_num']!="")
{
      
 $where.=" AND st.sto_num like '%".$_REQUEST['sto_num']."%' ";  
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['merch'])&&$_REQUEST['merch']!="")
{
      
 $where.=" AND m.merch =".$_REQUEST['merch']."";  
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['client'])&&$_REQUEST['client']!="")
{
      
 $where.=" AND m.cid =".$_REQUEST['client']."";  
 $join_type='inner join';
 $cl_flag=1;
}


$where = ' where  prj.status =1 and m.region=(select region from "employeeDB" where "employeeID"='.$_SESSION['employeeID'].') '.$where;

$k=0;
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
//$where .= " and  prj.status =1";  //$client_sql";
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


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$ex_cl_i.':J'.$ex_cl_i);
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'Email Schedule Reports');
 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':H'.$ex_cl_i)->getFont()->setBold('true');
 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setSize(16);
$ex_cl_i+=1;
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'Project Name');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ex_cl_i,'Job ID#');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ex_cl_i,'Start Date');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ex_cl_i ,'Merchandiser');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ex_cl_i,'Client');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ex_cl_i,'Chain');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ex_cl_i,'Store#');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,'City');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ex_cl_i,'Start Time');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ex_cl_i,'Notes');
//$ex_cl_i+=1;


 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':J'.$ex_cl_i)->getFont()->setBold('true');
 $ex_cl_i+=1;


do
{
    if(isset($datalist))
        unset($datalist);
     if(isset($prev))
        unset($prev);
    $datalist=array();
//echo $k.'<br/>';
$sql ='SELECT  ( prj.prj_name ),main.name, prj.pid,clnt.client, chain.chain,m1.firstname, m1.lastname,m1."employeeID", reg_name.region, st.sto_num, m.st_time,TO_CHAR(TO_TIMESTAMP(m.due_date), \'MM/DD/YYYY\') as due_date
,m.city,m.notes,m1.email,m.notes,m.phone,m.zip,m.address from projects  as prj  
left join prj_merchants_new as m on m.pid =prj.pid 
left join "employeeDB" as m1 on m1."employeeID"= m.merch left join tbl_chainmanagement as st on st.chain_id = m.store_num  '.
   ' left join project_main as main on main.m_pid=prj.m_pid '.
         ' left join "clientDB" as clnt on clnt."ID"=m.cid  left join tbl_region as reg_name on reg_name.rid=m.region  '.
 '  left join tbl_chain as chain on chain.ch_id=m.location  left join tbl_region as reg on reg.rid=m1.region '.$where .
' order by prj.m_pid desc limit 10 offset '.$k;
 //echo $sql;
if (!($result = pg_query($connection, $sql)))
    {
        $ret['error'] = "Failed to add / edit project: " . pg_last_error($connection);
        echo json_encode($ret);
        return;
    }
 
$i=-1;  
while($out = pg_fetch_array($result)){
 if(!isset($prev)||($out['pid']!=$prev))
 {
     $i+=1;
 $datalist[$i]=array();

 }
 //print_r($out).'<br/>';
    $datalist[$i][] = $out;
    $prev=$out['pid'];

}
$num_row=pg_num_rows($result);
//echo 'num-'.$num_row;
pg_free_result($result);

//echo $sql;


for($i=0; $i <count($datalist); $i++)
{
/*if($datalist[$i][0]['name']!='')
{
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][0]['name'])));  
$ex_cl_i+=1;
}*/
if(count($datalist[$i])>0)
{
   for($j=0;$j<count($datalist[$i]);$j++)
   {
  if($datalist[$i][$j]["employeeID"]>0)
  {
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]["name"])));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]["prj_name"])));
       
       if($datalist[$i][$j]['due_date']!='')
       $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]['due_date']))); 
  /* else 
       $content .= '"",'; */
  
	 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]["firstname"].' '.$datalist[$i][$j]["lastname"])));
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]["client"])));
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]["chain"])));
	
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]["sto_num"])));
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,$datalist[$i][$j]["city"]);
        
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]["st_time"]))); 
        
	
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]["notes"])));
    $border_row[]=$ex_cl_i;
	
$ex_cl_i+=1;
 
   }
   }
}


}

$k+=10;
}while($num_row>0);

 foreach($border_row as $row)
        {
     $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':J'.$row)->applyFromArray($styleArray);       
        }
unset($styleArray);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="email_schedule_report-'.date('m/d/Y').'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;			
?>