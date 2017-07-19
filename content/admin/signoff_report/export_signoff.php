<?php
require('Application.php');
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/New_York');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once $mydirectory.'/PHPExcel/Classes/PHPExcel.php';
$datalist=array();
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
if(isset($_REQUEST['date_from'])&&$_REQUEST['date_from']!="")
{

     $date_arr = explode('/',$_REQUEST['date_from']);
    $from_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);

 $where.=' AND mer."due_date">='.$from_date;   
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['date_to'])&&$_REQUEST['date_to']!="")
{
      $date_arr = explode('/',$_REQUEST['date_to']);
    $to_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);
 $where.=' AND mer."due_date"<='.$to_date;  
 $join_type='inner join';
 $cl_flag=1;
}

$where_main="";
if(isset($_REQUEST['project'])&&$_REQUEST['project']!="")
{
      
 $where_main=" where main.m_pid =".$_REQUEST['project']."";   
 //$join_type='inner join';
 $cl_flag=1;
}
if(isset($_REQUEST['jobid_num'])&&$_REQUEST['jobid_num']!="")
{
  if($where_main=='') $where_main=" where";
  else $where_main.=" and ";
 $where_main.="  prj.prj_name ilike '%".$_REQUEST['jobid_num']."%'";   
 //$join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['st_name'])&&$_REQUEST['st_name']!="")
{
      
 $where.=" AND ch.ch_id =".$_REQUEST['st_name']."";   
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
      
 $where.=" AND mer.merch =".$_REQUEST['merch']."";  
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['client'])&&$_REQUEST['client']!="")
{
      
 $where.=" AND mer.cid =".$_REQUEST['client']."";  
 $join_type='inner join';
 $cl_flag=1;
}


$sql ='SELECT distinct ( prj.prj_name ),main.name,frm.*, prj.pid, chain.chain,m1.firstname, m1.lastname, reg.region, st.sto_num, m.st_time,m.due_date 
from projects  as prj  
 '.$join_type.' prj_merchants_new as m on m.m_id = (select mer.m_id from prj_merchants_new as mer left join tbl_region as reg on reg.rid=mer.region '
.' left join project_main as main on main.m_pid=prj.m_pid left join tbl_chain as ch on ch.ch_id=mer.location left join tbl_chainmanagement as st on st.chain_id = mer.store_num '
.'where mer.pid = prj.pid '.$where.' limit 1 )
    left join "dmg_convnc_form" as frm on frm.pid=prj.pid
left join "employeeDB" as m1 on m1."employeeID"= m.merch left join tbl_chainmanagement as st on st.chain_id = m.store_num  '.
   ' left join project_main as main on main.m_pid=prj.m_pid '.
 '  left join tbl_chain as chain on chain.ch_id=m.location  left join tbl_region as reg on reg.rid=m1.region '.$where_main ;

//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$datalist['dmg_convnc'][]=$row;
}
pg_free_result($result);


$sql ='SELECT distinct ( prj.prj_name ),main.name,frm.*, prj.pid, chain.chain,m1.firstname, m1.lastname, reg.region, st.sto_num, m.st_time,m.due_date 
from projects  as prj  
 '.$join_type.' prj_merchants_new as m on m.m_id = (select mer.m_id from prj_merchants_new as mer left join tbl_region as reg on reg.rid=mer.region '
.' left join project_main as main on main.m_pid=prj.m_pid  left join tbl_chain as ch on ch.ch_id=mer.location left join tbl_chainmanagement as st on st.chain_id = mer.store_num '
.'where mer.pid = prj.pid '.$where.' limit 1 )
    left join "stater_bros_form" as frm on frm.pid=prj.pid
left join "employeeDB" as m1 on m1."employeeID"= m.merch left join tbl_chainmanagement as st on st.chain_id = m.store_num  '.
   ' left join project_main as main on main.m_pid=prj.m_pid '.
 '  left join tbl_chain as chain on chain.ch_id=m.location  left join tbl_region as reg on reg.rid=m1.region '.$where_main ;

//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$datalist['grocery'][]=$row;
}
pg_free_result($result);





$sql ='SELECT distinct ( prj.prj_name ),main.name,frm.*, prj.pid, chain.chain,m1.firstname, m1.lastname, reg.region, st.sto_num, m.st_time,m.due_date 
from projects  as prj  
 '.$join_type.' prj_merchants_new as m on m.m_id = (select mer.m_id from prj_merchants_new as mer left join tbl_region as reg on reg.rid=mer.region '
.'left join project_main as main on main.m_pid=prj.m_pid  left join tbl_chain as ch on ch.ch_id=mer.location left join tbl_chainmanagement as st on st.chain_id = mer.store_num '
.'where mer.pid = prj.pid '.$where.' limit 1 )
    left join "frito_lay_form" as frm on frm.pid=prj.pid
left join "employeeDB" as m1 on m1."employeeID"= m.merch left join tbl_chainmanagement as st on st.chain_id = m.store_num  '.
   ' left join project_main as main on main.m_pid=prj.m_pid '.
 '  left join tbl_chain as chain on chain.ch_id=m.location  left join tbl_region as reg on reg.rid=m1.region '.$where_main ;

//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$datalist['fritolay'][]=$row;
}
pg_free_result($result);


$sql ='SELECT distinct ( prj.prj_name ),main.name,frm.*, prj.pid, chain.chain,m1.firstname, m1.lastname, reg.region, st.sto_num, m.st_time,m.due_date 
from projects  as prj  
 '.$join_type.' prj_merchants_new as m on m.m_id = (select mer.m_id from prj_merchants_new as mer left join tbl_region as reg on reg.rid=mer.region '
.' left join project_main as main on main.m_pid=prj.m_pid left join tbl_chain as ch on ch.ch_id=mer.location left join tbl_chainmanagement as st on st.chain_id = mer.store_num '
.'where mer.pid = prj.pid '.$where.' limit 1 )
    left join "pizza_form" as frm on frm.pid=prj.pid
left join "employeeDB" as m1 on m1."employeeID"= m.merch left join tbl_chainmanagement as st on st.chain_id = m.store_num  '.
   ' left join project_main as main on main.m_pid=prj.m_pid '.
 '  left join tbl_chain as chain on chain.ch_id=m.location  left join tbl_region as reg on reg.rid=m1.region '.$where_main ;

//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$datalist['nestle'][]=$row;
}
pg_free_result($result);


$sql ='SELECT distinct ( prj.prj_name ),main.name,frm.*, prj.pid, chain.chain,m1.firstname, m1.lastname, reg.region, st.sto_num, m.st_time,m.due_date 
from projects  as prj  
 '.$join_type.' prj_merchants_new as m on m.m_id = (select mer.m_id from prj_merchants_new as mer left join tbl_region as reg on reg.rid=mer.region '
.' left join project_main as main on main.m_pid=prj.m_pid left join tbl_chain as ch on ch.ch_id=mer.location left join tbl_chainmanagement as st on st.chain_id = mer.store_num '
.'where mer.pid = prj.pid '.$where.' limit 1 )
    left join "dmg_form" as frm on frm.pid=prj.pid
left join "employeeDB" as m1 on m1."employeeID"= m.merch left join tbl_chainmanagement as st on st.chain_id = m.store_num  '.
   ' left join project_main as main on main.m_pid=prj.m_pid '.
 '  left join tbl_chain as chain on chain.ch_id=m.location  left join tbl_region as reg on reg.rid=m1.region '.$where_main ;

//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$datalist['dmg'][]=$row;
}
pg_free_result($result);

//print_r($datalist['dmg_convnc']);
//echo 'vvv'.ord('AA');

/*$column=array('store_name','store_num','date','work_type','address','city','tot_cld_door','csd','new_age','energy','water','dairy_dell','csd_2','new_age2',
'energy_2','water_2','dairy_dell2','csd_door_width','dr_hnd_left','dr_hnd_right','sticker','glide_comment','oz_20_txt','ltr_1_txt','oz_10_12_txt',
'oz_32_txt','ltr_2_txt','red_bull_txt','mngr_name','mngr_storenum','comments');*/




// Create new PHPExcel object
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

if(isset($datalist['dmg_convnc'])&&count($datalist['dmg_convnc'])>0){
$column=array('name','prj_name','chain','sto_num','date','work_type','address','city','tot_cld_door','csd','new_age','energy','water','dairy_dell','csd_2',
 'new_age2','energy_2','water_2','dairy_dell2','csd_door_width','dr_hnd_left','dr_hnd_right','sticker','glide_comment','oz_20_txt','ltr_1_txt',
 'oz_10_12_txt','oz_32_txt','ltr_2_txt','red_bull_txt','mngr_name','mngr_storenum','comments');
$column_name=array('Project Name','Job Name','Store Name','Store #','Date','Work Type','Address','City','Total Cold Vault Doors','CSD','New Age',' Energy',' Water',
'Dairy/Dell','CSD','New Age','Energy','Water','Dairy/Dell','Width of CSD Doors Glide','Left','Right','Did the back of the glides get stickers','comments',
    '20 0z','1L','10-12 0z','32 0z','2L','Red Bull','Manager Name','Manager Write in Store Number','Comments');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$ex_cl_i.':BS'.$ex_cl_i);
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'DMG Convenience Form');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setSize(16);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.$ex_cl_i.':N'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,'Total Cold Vault Doors');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('O'.$ex_cl_i.':R'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ex_cl_i ,'# Shelves in Doors');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('T'.$ex_cl_i.':U'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$ex_cl_i,'Door Handles as you face them');

 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':BS'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
foreach($column_name as $key=>$name){
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i, $name) ;
 $objPHPExcel->setActiveSheetIndex(0)->getStyle($ex_clm[$key].$ex_cl_i)->getFont()->setSize(13); 
 $objPHPExcel->setActiveSheetIndex(0)->getStyle($ex_clm[$key].$ex_cl_i)->getFont()->setBold('true');
}
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;


//$ex_cl_i=3;
$ex_cl_j=0;
for($i=0;$i<count($datalist['dmg_convnc']);$i++)
{
if($datalist['dmg_convnc'][$i]['dmg_id']==''||$datalist['dmg_convnc'][$i]['dmg_id']==null) continue;    
$ex_cl_j=0;   
foreach($column as $key=>$name){
 if($datalist['dmg_convnc'][$i]['chain']=='') break;  
  if($name=='chain'&&$datalist['dmg_convnc'][$i]['store_name']=='0')
  {
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Other') ;     
  }
 else if($name=='sto_num'&&$datalist['dmg_convnc'][$i]['store_name']=='0')
  {
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,$datalist['dmg_convnc'][$i]['other']) ;     
  }
   else if($name=='sticker')
  {
 if($datalist['dmg_convnc'][$i]['sticker']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
    else if($name=='dr_hnd_left')
  {
 if($datalist['dmg_convnc'][$i]['dr_hnd_left']=='t')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
    else if($name=='dr_hnd_right')
  {
 if($datalist['dmg_convnc'][$i]['dr_hnd_right']=='t')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
  else{
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,$datalist['dmg_convnc'][$i][$name]) ;   
  }
 
}
$ex_cl_i+=1;
}
}

if(isset($datalist['grocery'])&&count($datalist['grocery'])>0){
    unset($column);
    unset($column_name);
$column=array('name','prj_name','chain','sto_num','date','work_type','city','category','repack_box','dcin_box','out_code_box','repack_in',
  'dcin','out_of_code','repck_dc_back','exp_why','cat_1','file_id','footage','sec_comp','cat_2','file_id2','footage2','sec_comp2'
    ,'cat_3','file_id3','footage3','sec_comp3','cat_4','file_id4','footage4','sec_comp4','cat_5','file_id5','footage5','sec_comp5'
    ,'cat_6','file_id6','footage6','sec_comp6','cat_7','file_id7','footage7','sec_comp7','cat_8','file_id8','footage8','sec_comp8',
    'name_title','mngr_writ_store','comments');
$column_name=array('Project Name','Job Name','Store Name','Store #','Date','Work Type','City','Blitz','Repack in boxes','DC in boxes','Out of code in boxes',
'Repack in carts','DC in carts','Out of code in carts','Repack - Dc palatized in backroom','If no explain Why?'
 ,'CAT','File ID#','Footage','Section Completed' ,'CAT','File ID#','Footage','Section Completed' ,'CAT','File ID#','Footage','Section Completed'
   ,'CAT','File ID#','Footage','Section Completed','CAT','File ID#','Footage','Section Completed','CAT','File ID#','Footage','Section Completed'
    ,'CAT','File ID#','Footage','Section Completed','CAT','File ID#','Footage','Section Completed','Manager Name','Manager Write in Store Number'
    ,'Comments');
$ex_cl_i+=3;
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$ex_cl_i.':BS'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'Grocery Form');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setSize(16);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=1;

//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.$ex_cl_i.':N'.$ex_cl_i);
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ex_cl_i,'Total Cold Vault Doors');
//
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('O'.$ex_cl_i.':R'.$ex_cl_i);
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ex_cl_i ,'# Shelves in Doors');
//
//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('T'.$ex_cl_i.':U'.$ex_cl_i);
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$ex_cl_i,'Door Handles as you face them');
//$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
foreach($column_name as $key=>$name){
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i, $name) ;
 $objPHPExcel->setActiveSheetIndex(0)->getStyle($ex_clm[$key].$ex_cl_i)->getFont()->setSize(13); 
  $objPHPExcel->setActiveSheetIndex(0)->getStyle($ex_clm[$key].$ex_cl_i)->getFont()->setBold('true');
}
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;


//$ex_cl_i=3;
$ex_cl_j=0;
for($i=0;$i<count($datalist['grocery']);$i++)
{
  if($datalist['grocery'][$i]['stat_bros_id']==''||$datalist['grocery'][$i]['stat_bros_id']==null) continue;      
$ex_cl_j=0;   
foreach($column as $key=>$name){
    
  if($name=='chain'&&$datalist['grocery'][$i]['store_name']=='0')
  {
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Other') ;     
  }
 else if($name=='sto_num'&&$datalist['grocery'][$i]['store_name']=='0')
  {
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,$datalist['grocery'][$i]['other']) ;     
  }
   else if($name=='repck_dc_back')
  {
 if($datalist['grocery'][$i]['repck_dc_back']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
  
     else if($name=='sec_comp')
  {
 if($datalist['grocery'][$i]['sec_comp']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='sec_comp1')
  {
 if($datalist['grocery'][$i]['sec_comp1']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='sec_comp2')
  {
 if($datalist['grocery'][$i]['sec_comp2']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='sec_comp3')
  {
 if($datalist['grocery'][$i]['sec_comp3']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='sec_comp4')
  {
 if($datalist['grocery'][$i]['sec_comp4']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='sec_comp5')
  {
 if($datalist['grocery'][$i]['sec_comp5']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='sec_comp6')
  {
 if($datalist['grocery'][$i]['sec_comp6']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='sec_comp7')
  {
 if($datalist['grocery'][$i]['sec_comp7']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='sec_comp8')
  {
 if($datalist['grocery'][$i]['sec_comp8']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
  
  else{
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,$datalist['grocery'][$i][$name]) ;   
  }
}
$ex_cl_i+=1;
}
}


if(isset($datalist['fritolay'])&&count($datalist['fritolay'])>0){
    unset($column);
    unset($column_name);
$column=array('name','prj_name','chain','sto_num','date','work_type','city','repack_in','how_many1','repack_box','dcin','how_many2','dcin_box',
 'out_of_code','how_many3','out_code_box','tot_chp_foot','main_bdy_chp','on_the_go','sup_size','can_pot','snack_mix','set_cmplt','cut_in',
 'sec_clean','dc_repack','txt_1_5','txt_2','txt_2_5','txt_3','txt_3_5','txt_4','txt_1_5_s','txt_2_s','txt_2_5_s','txt_2_s_sec','txt_3_5_s',
  'txt_4_s','dp_brk_kit','dp_brk_kit','store_mngr','comments');
$column_name=array('Project Name','Job Name','Store Name','Store #','Date','Work Type','City','Repack in carts','How Many','Repack in boxes','DC in carts',
 'How Many','DC in boxes','Out of code in carts','How Many','Out of code','Total Chip','Main Body Chip','On the Go','Super Size','Cannister Potato',
 'Snack Mix','Was Set Completed?','New Items Cut in?','Section Cleaned?','D/C Repack put in Boxes?','1.5','2','2.5','3','3.5','4','1.5','2','2.5','3','3.5','4',
 'Dip Breaker Kits ','Manager Name','Manager Write in Store Number','Comments');
$ex_cl_i+=3;
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$ex_cl_i.':BS'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'FRITO LAY REST REPORT');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setSize(16);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('P'.$ex_cl_i.':Y'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$ex_cl_i,'CHIP AISLE FOOTAGE');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Z'.$ex_cl_i.':AL'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$ex_cl_i,'EQUIPMENT NEEDED');
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Z'.$ex_cl_i.':AE'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$ex_cl_i,'List racks installed in set');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AF'.$ex_cl_i.':AJ'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$ex_cl_i,'Dip Shelves Used ?');
 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':BS'.$ex_cl_i)->getFont()->setBold('true');
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$ex_cl_i,'Dip Breaker Kits');
  $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':BS'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
foreach($column_name as $key=>$name){
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i, $name) ;
 $objPHPExcel->setActiveSheetIndex(0)->getStyle($ex_clm[$key].$ex_cl_i)->getFont()->setSize(13); 
  $objPHPExcel->setActiveSheetIndex(0)->getStyle($ex_clm[$key].$ex_cl_i)->getFont()->setBold('true');
}
$ex_cl_i+=1;

$border_row[]=$ex_cl_i;

//$ex_cl_i=3;
$ex_cl_j=0;
for($i=0;$i<count($datalist['fritolay']);$i++)
{
 if($datalist['fritolay'][$i]['frito_id']==''||$datalist['fritolay'][$i]['frito_id']==null) continue;    
 $border_row[]=$ex_cl_i;
$ex_cl_j=0;   
foreach($column as $key=>$name){
    
  if($name=='chain'&&$datalist['fritolay'][$i]['store']=='0')
  {
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Other') ;     
  }
 else if($name=='sto_num'&&$datalist['fritolay'][$i]['store']=='0')
  {
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,$datalist['fritolay'][$i]['other']) ;     
  }
       else if($name=='set_cmplt')
  {
 if($datalist['fritolay'][$i]['set_cmplt']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
         else if($name=='cut_in')
  {
 if($datalist['fritolay'][$i]['cut_in']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
           else if($name=='sec_clean')
  {
 if($datalist['fritolay'][$i]['sec_clean']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
             else if($name=='dc_repack')
  {
 if($datalist['fritolay'][$i]['dc_repack']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }

  else{
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,$datalist['fritolay'][$i][$name]) ;   
  }
}
$ex_cl_i+=1;
}
}
$ex_cl_i+=3;
$ex_cl_j=0;
if(isset($datalist['nestle'])&&count($datalist['nestle'])>0){
   unset($column);
    unset($column_name);   
$column=array('name','prj_name','chain','sto_num','blit_date','work_type','address','city','set_complete','new_item_cut','dc_marked','cur_sz_set','ice_cream_vault',
 'walk_in_vault_front','load_cooler','vendor_cool','froz_food_vault','walk_in_vault','front_load_cool','walk_in_vault_p','front_load_cool_p','man_coldbox',
 'model_num','shell_miss_ice','ice_door','shell_miss_froz','froz_door','shell_miss_piz','froz_door_piz','new_schema','tag_replace','copy_schema',
    'case_ice','case_cold','sec_loc','name_title','manager_storenum','comments');
$column_name=array('Project Name','Job Name','Store Name','Store #','Date','Work Type','Address','City','SET COMPLETED?','NEW ITEMS CUT IN?','DC MARKED?',
'CURRENT SIZE OF SET','Ice Cream Vault','Walk in Vault Front','Load cooler','Vendor Visi-coolers','Frozen Food Vault','Walk in vault','Front load cooler','Walk in vault',
'Front load cooler','Manufacture of Cold box','Model#','Any icecream shelves missing?','How many','Any Frozen Food shelves missing?','How many','Any Pizza shelves missing?',
 'How many','ARE ALL THE SETS TO THE NEW SCHEMATIC?','DID THE TAGS GET REPLACED','WAS A COPY OF SCHEMATIC LEFT N THE CASE','DOES THE CASE GET ICED UP AT ALL?',
 'IS THE TEMP OF THE CASE COLD ENOUGH FOR THE PRODUCT?','IS THERE A SECONDARY LOCATION OR DC/REPACK','Manager Name','Manager Write in Store Number','Comments');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$ex_cl_i.':BS'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'Nestle DSD Sign Off');
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setSize(16);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=3;
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('U'.$ex_cl_i.':AA'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$ex_cl_i,'NEED SECTION INFORMATION ');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('AC'.$ex_cl_i.':AH'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC'.$ex_cl_i ,'STORE MANAGER SECTION');
 
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':BS'.$ex_cl_i)->getFont()->setBold('true');

$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
foreach($column_name as $key=>$name){
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i, $name) ;
 $objPHPExcel->setActiveSheetIndex(0)->getStyle($ex_clm[$key].$ex_cl_i)->getFont()->setSize(13); 
  $objPHPExcel->setActiveSheetIndex(0)->getStyle($ex_clm[$key].$ex_cl_i)->getFont()->setBold('true');
}
$ex_cl_i+=1;
$ex_cl_j=0;
for($i=0;$i<count($datalist['nestle']);$i++)
{
if($datalist['nestle'][$i]['pizza_id']==''||$datalist['nestle'][$i]['pizza_id']==null) continue;   
$border_row[]=$ex_cl_i;
$ex_cl_j=0;   
foreach($column as $key=>$name){
    
  if($name=='chain'&&$datalist['nestle'][$i]['store_name']=='0')
  {
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Other') ;     
  }
 else if($name=='sto_num'&&$datalist['nestle'][$i]['store_name']=='0')
  {
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,$datalist['dmg_convnc'][$i]['other']) ;     
  }
   else if($name=='set_complete')
  {
 if($datalist['nestle'][$i]['set_complete']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='new_item_cut')
  {
 if($datalist['nestle'][$i]['new_item_cut']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
      else if($name=='dc_marked')
  {
 if($datalist['nestle'][$i]['dc_marked']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
       else if($name=='walk_in_vault_front')
  {
 if($datalist['nestle'][$i]['walk_in_vault_front']=='t')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
         else if($name=='load_cooler')
  {
 if($datalist['nestle'][$i]['load_cooler']=='t')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
  
  
          else if($name=='vendor_cool')
  {
 if($datalist['nestle'][$i]['vendor_cool']=='t')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
          else if($name=='walk_in_vault')
  {
 if($datalist['nestle'][$i]['walk_in_vault']=='t')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
          else if($name=='front_load_cool')
  {
 if($datalist['nestle'][$i]['front_load_cool']=='t')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
          else if($name=='walk_in_vault_p')
  {
 if($datalist['nestle'][$i]['walk_in_vault_p']=='t')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
          else if($name=='front_load_cool_p')
  {
 if($datalist['nestle'][$i]['front_load_cool_p']=='t')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
    else if($name=='new_schema')
  {
 if($datalist['nestle'][$i]['new_schema']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='tag_replace')
  {
 if($datalist['nestle'][$i]['tag_replace']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
  
     else if($name=='copy_schema')
  {
 if($datalist['nestle'][$i]['copy_schema']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='case_ice')
  {
 if($datalist['nestle'][$i]['case_ice']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='case_cold')
  {
 if($datalist['nestle'][$i]['case_cold']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
     else if($name=='sec_loc')
  {
 if($datalist['nestle'][$i]['sec_loc']=='Y')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Yes') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'No') ;  
  }
  
  else{
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,$datalist['nestle'][$i][$name]) ;   
  }
}
$ex_cl_i+=1;
}
}


if(isset($datalist['grocery'])&&count($datalist['dmg'])>0){
    unset($column);
    unset($column_name);
$column=array('name','prj_name','chain','sto_num','date','work_type','city','csd','csd_split1','csd_split2','h_l','num_shelf','shell_type','shell_foot',
 'shell_depth','shell_col','shell_mld_col','sl_3','sl_4','sl_6','sl_8','gl_type','gl_depth','gl_mld_clr','gld_eqp_used','shasta_whse','sh_wh_hl','sh_wh_num_shelf',
'bulk_24pk','blk_24_hl','blk_24_numshelf','prem_24_pack','prem_24_pack_hl','prem_24_pack_numshelf','new_age','new_age_hl','new_age_nushelf','botle_jc',
'botle_jc_hl','botle_jc_numshelf','isionic','isionic_hl','isionic_numshelf','mix','mix_hl','mix_numshelf','pet_water','pet_water_hl','pet_water_numshelf',
 'bulk_water','bulk_water_hl','bulk_water_numshelf','bulk_water_numshelf','case_pk_hl','case_pk_numshelf','spark_w','spark_w_hl','spark_w_numshelf',
 'cold_box','cold_box_hl','cold_box_numshelf','oz_20','ltr_1','oz_10_12','oz_32','ltr_2','red_bull','mngr_name','mngr_storenum','comments');
$column_name=array('Project Name','Job Name','Store Name','Store #','Date','Work Type','City','CSD','CSD Split Table','CSD Split Table','High/Low','Number of shelves per section',
'Type','Length','Depth','Inches  Color','Molding Color','3\'','4\'','6\'','8\'','Type','Depth','Molding Color','# Glide Equipment Used','Shasta/Whse','High/Low','# of Shelves',	
'Bulk/24 Pack CSD','High/Low','# of Shelves','Premium 24 Pack CSD','High/Low','# of Shelves','New Age','High/Low','# of Shelves',
  'Bottled Juice','High/Low','# of Shelves','Isoionics','High/Low','# of Shelves','Mix','High/Low','# of Shelves',
  'P.E.T Water','High/Low','# of Shelves','Bulk Wate','High/Low','# of Shelves','Case PK Water','High/Low','# of Shelves','Sparkling Water','High/Low','# of Shelves',
'Cold box','High/Low','# of new glide sheets installed','20 0z','1L','10-12 0z','32 0z','2L','Red Bull','Manager Name','Manager Write in Store Number','Comments');
$ex_cl_i+=3;
$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$ex_cl_i.':BS'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ex_cl_i,'DMG Chain Form');
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setSize(16);
$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=1;

$border_row[]=$ex_cl_i;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('L'.$ex_cl_i.':P'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ex_cl_i,'Shelving');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('Q'.$ex_cl_i.':T'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$ex_cl_i,'Number of base decks by size');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('U'.$ex_cl_i.':W'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$ex_cl_i,'Current Glide Equipment in Store');

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('BI'.$ex_cl_i.':BN'.$ex_cl_i);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('BI'.$ex_cl_i,'# Gliders Used');
 $objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$ex_cl_i.':BS'.$ex_cl_i)->getFont()->setBold('true');
$ex_cl_i+=1;
$border_row[]=$ex_cl_i;
foreach($column_name as $key=>$name){
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i, $name) ;
 $objPHPExcel->setActiveSheetIndex(0)->getStyle($ex_clm[$key].$ex_cl_i)->getFont()->setSize(13); 
  $objPHPExcel->setActiveSheetIndex(0)->getStyle($ex_clm[$key].$ex_cl_i)->getFont()->setBold('true');
}
$ex_cl_i+=1;



//$ex_cl_i=3;
$ex_cl_j=0;
for($i=0;$i<count($datalist['dmg']);$i++)
{
if($datalist['dmg'][$i]['dmg_id']==''||$datalist['dmg'][$i]['dmg_id']==null) continue;   
    //$objPHPExcel->getActiveSheet()->getStyle('A'.$ex_cl_i.':BR'.$ex_cl_i)->applyFromArray($styleArray);
$border_row[]=$ex_cl_i;
$ex_cl_j=0;   
foreach($column as $key=>$name){

  if($name=='chain'&&$datalist['dmg'][$i]['store_name']=='0')
  {
$objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Other') ;     
  }
 else if($name=='sto_num'&&$datalist['dmg'][$i]['store_name']=='0')
  {
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,$datalist['grocery'][$i]['other']) ;     
  }
     else if($name=='h_l')
  {
 if($datalist['dmg'][$i]['h_l']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
      else if($name=='sh_wh_hl')
  {
 if($datalist['dmg'][$i]['sh_wh_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
      else if($name=='blk_24_hl')
  {
 if($datalist['dmg'][$i]['blk_24_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
        else if($name=='prem_24_pack_hl')
  {
 if($datalist['dmg'][$i]['prem_24_pack_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
          else if($name=='new_age_hl')
  {
 if($datalist['dmg'][$i]['new_age_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
          else if($name=='botle_jc_hl')
  {
 if($datalist['dmg'][$i]['botle_jc_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
           else if($name=='isionic_hl')
  {
 if($datalist['dmg'][$i]['isionic_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
             else if($name=='mix_hl')
  {
 if($datalist['dmg'][$i]['mix_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
              else if($name=='pet_water_hl')
  {
 if($datalist['dmg'][$i]['pet_water_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
              else if($name=='bulk_water_hl')
  {
 if($datalist['dmg'][$i]['bulk_water_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
                else if($name=='case_pk_hl')
  {
 if($datalist['dmg'][$i]['case_pk_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
                  else if($name=='spark_w_hl')
  {
 if($datalist['dmg'][$i]['spark_w_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
                   else if($name=='cold_box_hl')
  {
 if($datalist['dmg'][$i]['cold_box_hl']=='H')      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'High') ;   
 else   $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,'Low') ;  
  }
 
  
  else{
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue($ex_clm[$key].$ex_cl_i,$datalist['dmg'][$i][$name]) ;   
  }
}
$ex_cl_i+=1;
}
}
            




//$objPHPExcel->getActiveSheet()->getStyle('A1:BS'.$ex_cl_i)->applyFromArray($styleArray);
   // $objPHPExcel->getActiveSheet()->getStyle('A1:B10')->applyFromArray($styleArray);
     //   $objPHPExcel->getActiveSheet()->getStyle('D1:E10')->applyFromArray($styleArray);
        foreach($border_row as $row)
        {
     $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':BS'.$row)->applyFromArray($styleArray);       
        }
unset($styleArray);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Signoff_report-'.date('m/d/Y').'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>