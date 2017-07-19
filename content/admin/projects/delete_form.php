<?php
require('Application.php');
$form_id=$_POST['form_id'];
$form_type=$_POST['form_type'];
 $qName='';
  
 switch($form_type)
 {
 case 'dmgconv':   
 $tbl_list=array(array('sign_off_files','form_id'),array('dmg_convnc_form','dmg_id','no'));
break;  
 case 'fritolay':   
 $tbl_list=array(array('sign_off_files','form_id'),array('frito_lay_form','frito_id','no'));
break;  
 case 'staterbros':   
 $tbl_list=array(array('sign_off_files','form_id'),array('stater_bros_form','stat_bros_id','no'));
break;
 case 'pizza':   
 $tbl_list=array(array('sign_off_files','form_id'),array('pizza_form','pizza_id','no'));
break;  
 case 'dmg':   
 $tbl_list=array(array('sign_off_files','fid'),array('dmg_form','dmg_id','no'));
break;
 case 'ralphchkl':   
 $tbl_list=array(array('sign_off_files','form_id','form_type','ralphchkl'),array('missing_hardware','form_id','type','ralph_chk'),array('ssr_form','form_id','type','ralph_chk')
 ,array('ssr_form_item','form_id','type','ralph_chk'),array('ralph_checklist_form','r_id','no'));
break; 

 case 'ralphreset':   
 $tbl_list=array(array('sign_off_files','form_id','form_type','ralphreset'),array('missing_hardware','form_id','type','ral_reset'),array('ssr_form','form_id','type','ral_reset')
  ,array('reset_store_summary','form_id','type','ral_reset'),array('item_mes_form','form_id','type','ral_reset')   
 ,array('ssr_form_item','form_id','type','ral_reset'),array('item_mes_form_item','form_id','type','ral_reset'),array('ralphs_reset_form','r_id','no'));
break; 
 }
    
$cnt=count( $tbl_list)-1;
  
 $qName='';  
  $columns='';
  $values='';
    for($i=0;$i< count($tbl_list);$i++)
    {

 $qName.='delete from "'.$tbl_list[$i][0].'" where "'.$tbl_list[$i][1].'"='.$form_id;
 if($i<$cnt&&(!isset($tbl_list[$i][2]) || $tbl_list[$i][2]=='yes'))
 {
  $qName.=' and form_type=\''.$_POST['form_type'].'\'';    
 }
  else{
  if($tbl_list[$i][2]=='no'){}
  else
      {
  $qName .=' and "'.$tbl_list[$i][2].'"=\''.$tbl_list[$i][3].'\'';     
  }
    }
 $qName.=';';
    
 }  

//echo $qName;
   if (!($result = pg_query($connection, $qName)))
        {
       echo pg_last_error($connection);
        exit(); 
        }   
     $ret= array('status'=>'1');  

header('content-type:application/json');
echo json_encode($ret);
?>