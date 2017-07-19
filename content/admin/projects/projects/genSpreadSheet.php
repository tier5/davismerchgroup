<?php
require('Application.php');
$return_arr = array();
$return_arr['fileName'] = "";

extract($_REQUEST);

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


//$where=' where prj.status=1 and m.region=(select region from "employeeDB" where "employeeID"='.$_SESSION['employeeID'].') ';
$where=' where prj.pid>0 ';
if(isset($date_from)&&$date_from!="")
{
     $date_arr = explode('/',$date_from);
    $from_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);

 $where.=' AND m.due_date>='.$from_date;   
}
if(isset($date_to)&&$date_to!="")
{
      $date_arr = explode('/',$date_to);
    $to_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);

 $where.=' AND m.due_date<='.$to_date;   

}
$where_main="";
if(isset($_GET['project'])&&$_GET['project']!="")
{     
 $where.=" and prj.m_pid=".$_GET['project'];   
}
if(isset($_GET['jobid_num'])&&$_GET['jobid_num']!="")
{
 $where.=" and prj.prj_name like '%".$_GET['jobid_num']."%'";   
}



if(isset($_GET['st_name'])&&$_GET['st_name']!="")
{     
 $where.=" AND m.location =".$_GET['st_name']."";   
}



if(isset($_GET['sto_num'])&&$_GET['sto_num']!="")
{     
 $where.=" AND m.store_num =".$_GET['sto_num']."";  
}

if(isset($_GET['merch'])&&$_GET['merch']!="")
{     
 $where.=" AND m.merch =".$_GET['merch']."";  
}

if(isset($_GET['time'])&&$_GET['time']!="")
{
 $where.="  AND m.st_time like'%".$_GET['time']."%'";
}

if(isset($_GET['region'])&&$_GET['region']!="")
{     
 $where.=" AND m.region =".$_GET['region']."";   
}
if($_SESSION['emp_type']!=0) 
{
  $where.=" AND m.cid='".$_SESSION['client_id']."'";   
}
if($_SESSION['perm_admin'] != "on" && $_SESSION['emp_type']==0)
{
    
if($_SESSION['perm_manager'] == "on")
     $where.=" AND m.region=(select region from \"employeeDB\" where \"employeeID\"=".$_SESSION['employeeID']." limit 1) OR prj.created_by ='".$_SESSION['employeeID']."'"; 
 else   
  $where.=" AND m.merch='".$_SESSION['employeeID']."' OR prj.created_by ='".$_SESSION['employeeID']."'";   
  $join_type='inner join';

}
if(isset($_GET['close']) && $_GET['close'] == 1)
{

$where.=" and prj.status =0 "; 
}  else
{
$where.=" and prj.status =1 ";     
}



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
//$where = " and  prj.status =1";  //$client_sql";
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


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$ex_cl_i.':H'.$ex_cl_i);
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'Project Reports');
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
;


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
$sql ='select prj.*,str.sto_name,c.client ,main.name,chain.chain,m.*,TO_CHAR(TO_TIMESTAMP(m.due_date), \'MM/DD/YYYY\') as due_date,m1.firstname as m1first, m1.lastname as m1last,str2.sto_num as str2_sto_num  from projects as prj left join "prj_merchants_new" as m on  prj.pid=m.pid  ';
 $sql .= ' left join "employeeDB" as m1 on m1."employeeID"=m.merch';
 $sql.=' left join "clientDB" as c on c."ID"=m.cid ';
 $sql.=" left join tbl_chain as chain on chain.ch_id=m.location left join project_main as main on main.m_pid=prj.m_pid ";
  $sql .= " left join tbl_chainmanagement as str on str.chain_id=m.location  left join tbl_region as reg on reg.rid=m1.region ";
  $sql .= " left join tbl_chainmanagement as str2 on str2.chain_id=m.store_num ";
 $sql .= '  '.$where.' order by prj.pid desc limit 10 offset '.$k;
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




for($i=0; $i <count($datalist); $i++)
{

//$content = "\n".$datalist[$i][0][3].'                                  '."\n\n";  

//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$ex_cl_i.':B'.$ex_cl_i);
//$border_row[]=$ex_cl_i;
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,$datalist[$i][0][3]);
 //$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':J'.$ex_cl_i)->getFont()->setBold('true');
//$ex_cl_i+=1;
//$content .= 'Merchandiser1,Start Date,Start Time ,Chain,Store#,Address,Phone,Notes,City,Zip'."\n";
   for($j=0;$j<count($datalist[$i]);$j++)
   {
       
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,$datalist[$i][$j]["name"]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ex_cl_i,$datalist[$i][0][3]);
       if($datalist[$i][$j]['due_date']!='')
       $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ex_cl_i,$datalist[$i][$j]['due_date']); 
  /* else 
       $content .= '"",'; */
  
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ex_cl_i,$datalist[$i][$j]["m1first"].' '.$datalist[$i][$j]["m1last"]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ex_cl_i,$datalist[$i][$j]["client"]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]["chain"])));
	
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]["str2_sto_num"])));
     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,$datalist[$i][$j]["city"]);
        
     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ex_cl_i,$datalist[$i][$j]["st_time"]); 
        
	
       $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ex_cl_i,rtrim(str_replace('"','""',$datalist[$i][$j]["notes"])));
	
    $border_row[]=$ex_cl_i;
	
$ex_cl_i+=1;
 
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
header('Content-Disposition: attachment;filename="Project_report-'.date('m/d/Y').'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;		
?>