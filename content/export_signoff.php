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
$query='select p.prj_name,frm.* from "projects" as p inner join "dmg_convnc_form" as frm on frm.pid=p.pid';
if(!($result=pg_query($connection,$query))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$datalist['dmg_convnc']=$row;
}
pg_free_result($result);

//print_r($datalist['dmg_convnc']);
//echo 'vvv'.ord('AA');

$column=array('store_name','store_num','date','work_type','address','city','tot_cld_door','csd','new_age','energy','water','dairy_dell','csd_2','new_age2',
'energy_2','water_2','dairy_dell2','csd_door_width','dr_hnd_left','dr_hnd_right','sticker','glide_comment','oz_20_txt','ltr_1_txt','oz_10_12_txt',
'oz_32_txt','ltr_2_txt','red_bull_txt','mngr_name','mngr_storenum','comments');

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


// Add some data

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Store Name')
         ->setCellValue('B1', 'Store #')
            ->setCellValue('C1', 'Date')
            ->setCellValue('D1', 'Work Type')
            ->setCellValue('E1', 'Address')
             ->setCellValue('F1', 'City')
          ->setCellValue('G1', 'Total Cold Vault Doors')
          ->setCellValue('H1', 'CSD')
  ->setCellValue('I1', 'New Age')
 ->setCellValue('J1', 'Energy')
         ->setCellValue('K1', 'Water')
         ->setCellValue('L1', 'Dairy/Del')
         ->setCellValue('M1', 'New Age')
         ->setCellValue('N1', 'Energy')
         ->setCellValue('O1', 'Dairy/Del')
         ->setCellValue('P1', 'Width of CSD Doors Glide')
         ->setCellValue('Q1', 'Door Handles as you face them')
         ->setCellValue('R1', 'world!')
         ->setCellValue('S1', 'world!')
         ->setCellValue('T1', 'world!')
         ->setCellValue('U1', 'world!')
        ;

// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', 'Miscellaneous glyphs')
            ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Signoff Reports');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Signoff_report-'.date('m/d/Y').'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>