<?php
require 'Application.php';

$error = '';
 $tbl_list=array(array('out_of_stock','rr'),array('prj_merchants_new','purchaseId'),array('prj_uploads','tbl_prjimage_file')
   ,array('img_glry_files','prj_style_id') , array('img_glry_main','tt')
  ,array('dmg_form','prj_style_id'),array('dmg_convnc_form','prj_style_id'),array('stater_bros_form','prj_style_id')
     ,array('pizza_form','prj_style_id'),array('frito_lay_form','prj_style_id')
 ,array('projects','prj_style_id')    
);
   $qName='';  
  $columns='';
  $values='';
if (isset($_POST['id'])) {
  $wh="";
  foreach($_POST['id'] as $pid)
  {  
  $qName='';  
  $columns='';
  $values='';      
  for($i=0;$i< count($tbl_list);$i++)
    {
if($qName!='')$qName.=';';   
if($tbl_list[$i][0]=='img_glry_files')
{
 $sql = 'SELECT fl.* from img_glry_files as fl left join img_glry_main as main on main.glry_id=fl.glry_id where main.pid='.$pid;      
}    
 else
{
 $sql = 'SELECT * from "'.$tbl_list[$i][0].'" where pid='.$pid;   
}
//echo $sql;
    unset($prj_data);unset($row2);
 if (($r = pg_query($connection, $sql))) {
            /*print("Failed query1: ". pg_last_error($connection));
            exit;     */   
    while($row2 = pg_fetch_array($r))
    { 
           unset($prj_data);
           $prj_data = $row2;
$columns='';$values='';
        /* if($tbl_list[$i][0]=='tbl_prjorder_track_no')
            print_r($prj_data);*/

          $j=0; 
 if($prj_data[0]!='')
 {
    
        foreach($prj_data as $key => $value) {
            
   if($tbl_list[$i][0]=='prj_merchants_new'&&$key=='m_id') continue;
            if($j%2!=0 && $value!="")
            {
                 
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
$qName.=';insert into "'.$tbl_list[$i][0].'_closed" ('.$columns.') values('.$values.')';  

    }  
  
 }
    }
   pg_free_result($r);
if($tbl_list[$i][0]=='img_glry_files')
{
  $qName.=';delete from img_glry_files where glry_id=(select glry_id from img_glry_main where pid='.$pid.' limit 1)';   
}
 else{  $qName.=';delete from '.$tbl_list[$i][0].' where pid='.$pid;   
      
 }
  $sql='';
        
    }     
    else
        echo pg_last_error($connection);
 }  
 //$qName.=';delete from tbl_newproject where pid='.$pid; 
 //echo 'ee->'.$qName;
 if (!($result1 = pg_query($connection, $qName))) {
      echo "Failed Closing project: " . pg_last_error($connection);
       // echo $error;
        return;
    }
 
  }

   
    
}


    pg_free_result($result1);
echo json_encode($error);
return;