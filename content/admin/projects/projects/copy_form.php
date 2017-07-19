<?php
require('Application.php');
$form_id=$_POST['form_id'];
$form_type=$_POST['form_type'];
 $qName='';
  
 switch($form_type)
 {
 case 'dmgconv':
 $first_tbl=array('dmg_convnc_form','dmg_id');    
 $tbl_list=array(array('sign_off_files','fid'));
break; 
 case 'fritolay':
 $first_tbl=array('frito_lay_form','frito_id');    
 $tbl_list=array(array('sign_off_files','fid'));
break; 
 case 'staterbros':
 $first_tbl=array('stater_bros_form','stat_bros_id');    
 $tbl_list=array(array('sign_off_files','fid'));
break; 
 case 'pizza':
 $first_tbl=array('pizza_form','pizza_id');    
 $tbl_list=array(array('sign_off_files','fid'));
break; 
 case 'dmg':
 $first_tbl=array('dmg_form','dmg_id');    
 $tbl_list=array(array('sign_off_files','fid'));
break; 
 case 'ralphchkl':
 $first_tbl=array('ralph_checklist_form','r_id');    
 $tbl_list=array(array('sign_off_files','fid'),array('missing_hardware','mh_id','type','ralph_chk'),array('ssr_form','ss_id','type','ralph_chk')
 ,array('ssr_form_item','ss_it_id','type','ralph_chk'));
break;
 case 'ralphreset':
 $first_tbl=array('ralphs_reset_form','r_id');    
 $tbl_list=array(array('sign_off_files','fid'),array('missing_hardware','mh_id','type','ral_reset'),array('ssr_form','ss_id','type','ral_reset')
  ,array('reset_store_summary','r_id','type','ral_reset'),array('item_mes_form','ss_id','type','ral_reset')   
 ,array('ssr_form_item','ss_it_id','type','ral_reset'),array('item_mes_form_item','ss_it_id','type','ral_reset'));
break;
 }
    
  $qName='';  
  $columns='';
  $values='';  
   
      $sql = 'SELECT * from "'.$first_tbl[0].'" where "'.$first_tbl[1].'"='.$form_id;
//echo $sql;
    unset($prj_data);unset($row2);
 if (($r = pg_query($connection, $sql))) {
            
    while($row2 = pg_fetch_array($r))
    { 
           unset($prj_data);
           $prj_data = $row2;
$columns='';$values='';


          $j=0; 
 if($prj_data[0]!='')
 {
    
        foreach($prj_data as $key => $value) {
   
            if($j%2!=0 && $value!="")
            {
                if($key==$first_tbl[1]){ $j+=1; continue;}
                 
        if($columns=='')  
         {
                $columns.='"'.$key.'"';
                $values.="'". pg_escape_string($value)."'";
         }
            else  
                { 
            $columns.=',"'.$key.'"';
            $values.=",'".pg_escape_string($value)."'";
            }
            }
$j+=1;            
}      
 
  if($columns!=""&&$values!="")
  {      
$qName.=';insert into "'.$first_tbl[0].'" ('.$columns.') values('.$values.')';  

    }  
  
 }
    }
   // echo $qName;
   $result = pg_query($connection, $qName);
   pg_free_result($r);
    $sql = 'SELECT "'.$first_tbl[1].'"  as form_id from "'.$first_tbl[0].'" order by  "'.$first_tbl[1].'" desc limit 1';
   $r = pg_query($connection, $sql);
   $row2 = pg_fetch_array($r);
     pg_free_result($r);
    // print_r( $row2);
     $new_form_id= $row2['form_id'];
 }
  

  
 $qName='';  
  $columns='';
  $values='';
    for($i=0;$i< count($tbl_list);$i++)
    {
if($qName!='')$qName.=';';   

    $sql = 'SELECT * from "'.$tbl_list[$i][0].'" where form_id='.$form_id;
    if(!isset($tbl_list[$i][2]) || $tbl_list[$i][2]=='yes')
    { $sql .=' and form_type=\''.$form_type.'\''; }
    else{
  if($tbl_list[$i][2]=='no'){}
  else{
  $sql .=' and "'.$tbl_list[$i][2].'"=\''.$tbl_list[$i][3].'\'';     
  }
    }
//echo $sql;
    unset($prj_data);unset($row2);
 if (($r = pg_query($connection, $sql))) {
            
    while($row2 = pg_fetch_array($r))
    { 
           unset($prj_data);
           $prj_data = $row2;
$columns='form_id';$values=''.$new_form_id;


          $j=0; 
 if($prj_data[0]!='')
 {
    
        foreach($prj_data as $key => $value) {
    if($key==$tbl_list[$i][1]||$key=='form_id'){ $j+=1; continue;}
            if($j%2!=0 && $value!="")
            {                                  
            $columns.=',"'.$key.'"';
            $values.=",'".pg_escape_string($value)."'";
            
            }
$j+=1;            
}      
 
  if($columns!=""&&$values!="")
  {      
$qName.=';insert into "'.$tbl_list[$i][0].'" ('.$columns.') values('.$values.')';  

    }  
  
 }
    }



        
    }     
    
 }  

//echo 'qrrr'.$qName;
if($qName!='')
{
   if (!($result = pg_query($connection, $qName)))
        {
       echo pg_last_error($connection);
        exit(); 
        }  
}
     $ret= array('form_id'=>$new_form_id,'status'=>'1');  
     switch($form_type)
     {
      case 'dmgconv':
          $ret['form_type']='dmg_convenience_form';
          break;
      case 'fritolay':
          $ret['form_type']='frito_lay_rest_form';
          break;
      case 'staterbros':
          $ret['form_type']='stater_bros_form';
          break;
        case 'pizza':
          $ret['form_type']='pizza_form';
          break;
      case 'dmg':
          $ret['form_type']='dmg_form';
          break;
      case 'ralphchkl':
          $ret['form_type']='ralphs_checklist';
          break;
        case 'ralphreset':
          $ret['form_type']='ralphs_reset';
          break;
     }
header('content-type:application/json');
echo json_encode($ret);
?>