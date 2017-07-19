<?php
require 'Application.php';
//extract($_POST);

$ret=array();
$ret['status']=0;
$ret['reload_form']='';
if(trim($_POST['form_type'])=='nestle')
{
    
$elm=array('store_num','blit_date','set_complete','new_item_cut','dc_marked','cur_sz_set','ice_cream_vault',
 'froz_food_vault','man_coldbox','model_num','shell_miss_ice','ice_door','shell_miss_froz','froz_door',
 'new_schema','tag_replace','copy_schema','case_ice','case_cold','sec_loc','store_sign','comments','name_title','manager_storenum','proj_image',
 'store_name','address','work_type','city','mngr_sign','other');    
 
$elm_checkbox=array('walk_in_vault_front','load_cooler','vendor_cool','walk_in_vault','front_load_cool');  
$col='("pid"';
$val=" values('".$_POST['pid']."'";
$up_qr=' "pid"='.$_POST['pid'];
  for($i=0;$i<count($elm);$i++)
  {
  
  $col.=',"'.$elm[$i].'"';  
  $up_qr.=',"'.$elm[$i].'"=';
  if($_POST[$elm[$i]]=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($_POST[$elm[$i]])."'"; 
  $up_qr.="'".pg_escape_string($_POST[$elm[$i]])."'";
  }
  
 
        
    
  }
  
  
  for($i=0;$i<count($elm_checkbox);$i++)
  {
  
  $col.=',"'.$elm_checkbox[$i].'"';  
  $up_qr.=',"'.$elm_checkbox[$i].'"=';
  
  
  if(isset($_POST[$elm_checkbox[$i]]))
  {
  $val.=",TRUE";
  $up_qr.="TRUE";
  }
  else 
  {
    $val.=",FALSE"; 
    $up_qr.="FALSE";
  }
   
  
  }
  
 $col.=')';
 $val.=')';
 
 $qr='select * from "nestle_form" where pid='.$_POST['pid'];
 $r = pg_query($connection, $qr);

  

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
 
  if(pg_num_rows($r)>0)
 $query='update nestle_form set '.$up_qr.' where pid='.$_POST['pid'];  
 else
$query='insert into nestle_form '. $col.$val;
//echo $query;


 if (!($result = pg_query($connection, $query)))
    {
        print("Failed nestle submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
    $ret['status']=1;

}

else if(trim($_POST['form_type'])=='pizza')
{
    
$elm=array('store_num','blit_date','set_complete','new_item_cut','dc_marked','cur_sz_set','ice_cream_vault',
 'froz_food_vault','man_coldbox','model_num','shell_miss_ice','ice_door','shell_miss_froz','froz_door',
 'new_schema','tag_replace','copy_schema','case_ice','case_cold','sec_loc','store_sign','comments','name_title','manager_storenum','proj_image',
 'store_name','address','work_type','city','mngr_sign','other','froz_door_piz','shell_miss_piz','pizza_door','cid');    
 
$elm_checkbox=array('walk_in_vault_front','load_cooler','vendor_cool','walk_in_vault','front_load_cool','walk_in_vault_p','front_load_cool_p');  
$col='("pid"';
$val=" values('".$_POST['pid']."'";
$up_qr=' "pid"='.$_POST['pid'];
  for($i=0;$i<count($elm);$i++)
  {
  
  $col.=',"'.$elm[$i].'"';  
  $up_qr.=',"'.$elm[$i].'"=';
  if($_POST[$elm[$i]]=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($_POST[$elm[$i]])."'"; 
  $up_qr.="'".pg_escape_string($_POST[$elm[$i]])."'";
  }
  
 
        
    
  }
  
  
  for($i=0;$i<count($elm_checkbox);$i++)
  {
  
  $col.=',"'.$elm_checkbox[$i].'"';  
  $up_qr.=',"'.$elm_checkbox[$i].'"=';
  
  
  if(isset($_POST[$elm_checkbox[$i]]))
  {
  $val.=",TRUE";
  $up_qr.="TRUE";
  }
  else 
  {
    $val.=",FALSE"; 
    $up_qr.="FALSE";
  }
   
  
  }
  
 $col.=')';
 $val.=')';
 
 //$qr='select * from "pizza_form" where pid='.$_POST['pid'];
 //$r = pg_query($connection, $qr);

  

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
  if(isset($_POST['form_id'])&&$_POST['form_id']>0)
  {
  $ret['form_id']=$_POST['form_id'];
 $query='update pizza_form set '.$up_qr.' where pizza_id='.$_POST['form_id'];  
  }else{
$query='insert into pizza_form '. $col.$val;
//echo $query;
$qr='select pizza_id from "pizza_form"  order by pizza_id desc limit 1';
 $r = pg_query($connection, $qr);
 $row=pg_fetch_array($r);
 $ret['form_id']=$row['pizza_id'];
  }

 if (!($result = pg_query($connection, $query)))
    {
        print("Failed pizza submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
    $ret['status']=1;

}

else if(trim($_POST['form_type'])=='frito_lay')
{
    

    
    
$col='("pid"';
$val=" values('".$_POST['pid']."'";
$up_qr=' "pid"='.$_POST['pid'];
  foreach($_POST as $key=> $value)
  {
  if($key=='pid'||$key=='form_type'||$key=='form_id'||$key=='proj_sign'||$key=='img_project') continue;
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'"; 
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  
 
 
  
 $col.=')';
 $val.=')';
 //$qr='select * from "frito_lay_form" where frito_id='.$_POST['form_id'];
// $r = pg_query($connection, $qr);

  

// if(isset($_POST['form_id'])&&$_POST['form_id']!='')
  if(isset($_POST['form_id'])&&$_POST['form_id']>0)
  {
 $query='update frito_lay_form set '.$up_qr.' where frito_id='.$_POST['form_id'];
  $ret['form_id']=$_POST['form_id'];
  }
 else
 {
$query='insert into frito_lay_form '. $col.$val;
$qr='select frito_id from "frito_lay_form"  order by frito_id desc limit 1';
 $r = pg_query($connection, $qr);
 $row=pg_fetch_array($r);
 $ret['form_id']=$row['frito_id'];
 }
//echo $query;


 if (!($result = pg_query($connection, $query)))
    {
        print("Failed nestle submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
    $ret['status']=1;
    $ret['reload_form']='yes';
    $ret['form_type']='frito_lay_rest_form';
}


else if(trim($_POST['form_type'])=='dmg_conv')
{
    
    

$col='("pid"';
$val=" values('".$_POST['pid']."'";
$up_qr=' "pid"='.$_POST['pid'];
  foreach($_POST as $key=> $value)
  {
  if($key=='check'||$key=='txt'||$key=='pid'||$key=='form_type'||$key=='form_id'||$key=='proj_sign'||$key=='img_project'||$key=='form_id') continue;
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'"; 
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  
  $elm_checkbox=array('dr_hnd_left','dr_hnd_right');  
    for($i=0;$i<count($elm_checkbox);$i++)
  {
  
  $col.=',"'.$elm_checkbox[$i].'"';  
  $up_qr.=',"'.$elm_checkbox[$i].'"=';
  
  
  if(isset($_POST[check][$elm_checkbox[$i]]))
  {
  $val.=",TRUE";
  $up_qr.="TRUE";
  }
  else 
  {
    $val.=",FALSE"; 
    $up_qr.="FALSE";
  }
   
  
  }
  

  
  
  
  
  


  
 $col.=')';
 $val.=')';
  // $qr='select * from "dmg_convnc_form" where dmg_id='.$_POST['form_id'];
// $r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(isset($_POST['form_id'])&&$_POST['form_id']>0)
   {
        $ret['form_id']=$_POST['form_id'];
 $query='update dmg_convnc_form set '.$up_qr.' where dmg_id='.$_POST['form_id'];  
   }
 else
 {
$query='insert into dmg_convnc_form '. $col.$val;
//echo $query;
$qr='select dmg_id from "dmg_convnc_form"  order by dmg_id desc limit 1';
 $r = pg_query($connection, $qr);
 $row=pg_fetch_array($r);
 $ret['form_id']=$row['dmg_id'];
 }


 if (!($result = pg_query($connection, $query)))
    {
        print("Failed dmg conv submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
    $ret['status']=1;
    $ret['reload_form']='yes';
    $ret['form_type']='dmg_convenience_form';

}


else if(trim($_POST['form_type'])=='stat_bros')
{
    
    

$col='("pid"';
$val=" values('".$_POST['pid']."'";
$up_qr=' "pid"='.$_POST['pid'];
  foreach($_POST as $key=> $value)
  {
  if($key=='pid'||$key=='form_type'||$key=='form_id'||$key=='proj_sign'||$key=='img_project'||$key=='form_id'||$key=='cat') continue;
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'"; 
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  
 
 
  
 $col.=')';
 $val.=')';
 
 
 // $qr='select * from "stater_bros_form" where pid='.$_POST['pid'];
 //$r = pg_query($connection, $qr);



 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(isset($_POST['form_id'])&&$_POST['form_id']>0)
   {
 $ret['form_id']=$_POST['form_id'];
 $query='update stater_bros_form set '.$up_qr.' where stat_bros_id='.$_POST['form_id']; 
   }
 else
 {
$query='insert into stater_bros_form '. $col.$val;
//echo $query;
$qr='select stat_bros_id from "stater_bros_form"  order by stat_bros_id desc limit 1';
 $r = pg_query($connection, $qr);
 $row=pg_fetch_array($r);
 $ret['form_id']=$row['stat_bros_id'];
 }

 if (!($result = pg_query($connection, $query)))
    {
        print("Failed nestle submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
    
if(isset($_POST['cat']) && count($_POST['cat'])>0)
{
     $query=''; 
  foreach($_POST['cat'] as $cvalue)
  {
    //  print_r($cvalue);
  $col.=',"'.$key.'"';    
$col='("pid"';
$val=" values('".$_POST['pid']."'";
$up_qr=' "pid"='.$_POST['pid'];
  foreach($cvalue as $key=> $value)
  {
 if($key=='id') continue;     
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'"; 
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  
 $col.=')';
 $val.=')';
 if(isset($cvalue['id']) && $cvalue['id']>0)
 {
  $query.='update grocery_cat_items set '.$up_qr.' where id='.$cvalue['id'].';';     

 }
 else{
   $query.='insert into grocery_cat_items '. $col.$val.';';    
 }
}

//echo  $query;

 if (!($result = pg_query($connection, $query)))
    {
        print("Failed nestle submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    
 

}
   $ret['status']=1;
    $ret['reload_form']='yes';
    $ret['form_type']='stater_bros_form';
}

else if(trim($_POST['form_type'])=='dmg_form')
{
    
    

$col='("pid"';
$val=" values('".$_POST['pid']."'";
$up_qr=' "pid"='.$_POST['pid'];
  foreach($_POST as $key=> $value)
  {
  if($key=='check'||$key=='pid'||$key=='form_type'||$key=='form_id'||$key=='proj_sign'||$key=='img_project'||$key=='form_id') continue;
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'"; 
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  

  
 $col.=')';
 $val.=')';
 
 
 //$qr='select * from "dmg_form" where pid='.$_POST['pid'];
 //$r = pg_query($connection, $qr);


 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
 if(isset($_POST['form_id'])&&$_POST['form_id']>0)
 {
 $ret['form_id']=$_POST['form_id'];    
 $query='update "dmg_form" set '.$up_qr.' where dmg_id='.$_POST['form_id'];  
 }
 else
 {
$query='insert into "dmg_form" '. $col.$val;
//echo $query;
$qr='select dmg_id from "dmg_form"  order by dmg_id desc limit 1';
 $r = pg_query($connection, $qr);
 $row=pg_fetch_array($r);
 $ret['form_id']=$row['dmg_id'];
 }

 if (!($result = pg_query($connection, $query)))
    {
        print("Failed dmg submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
    $ret['status']=1;
    $ret['reload_form']='yes';
    $ret['form_type']='dmg_form';
}
else if(trim($_POST['form_type'])=='ralph_reset')
{        
$col='("pid"';
$val=" values('".$_POST['pid']."'";
$up_qr=' "pid"='.$_POST['pid'];
  foreach($_POST as $key=> $value)
  {
  if($key=='check'||$key=='txt'||$key=='pid'||$key=='form_type'||$key=='form_id'||$key=='proj_sign'||$key=='img_project'
     ||$key=='re'||$key=='mh'||$key=='ssr'||$key=='itm') continue;
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'";  
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  

 $col.=')';
 $val.=')';
   //$qr='select * from "ralphs_reset_form" where pid='.$_POST['pid'];
 //$r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
if(isset($_POST['form_id'])&&$_POST['form_id']>0)
 {
 $ret['form_id']=$_POST['form_id'];     
 $query='update ralphs_reset_form set '.$up_qr.' where r_id='.$_POST['form_id']; 
 $form_id=$_POST['form_id'];
  if (!($result = pg_query($connection, $query)))
    {
        print("Failed dmg conv submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
 }
 else
 {
$query='insert into ralphs_reset_form '. $col.$val;
//echo $query;
 if (!($result = pg_query($connection, $query)))
    {
        print("Failed dmg conv submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
$qr='select r_id from "ralphs_reset_form"  order by r_id desc limit 1';
 $r = pg_query($connection, $qr);
 $row=pg_fetch_array($r);
 $ret['form_id']=$row['r_id'];
  $form_id=$row['r_id'];
 // echo 'form--'. $form_id;
 }


    $query = '';
 $ret['reload_form']='yes';
 $ret['form_type']='ralphs_reset';
    
if(trim($_POST['re']['store_num'])!='')
{        
$col='("pid",type,form_id';
$val=" values('".$_POST['pid']."','ral_reset',".$form_id;
$up_qr=' "pid"='.$_POST['pid'];
  foreach($_POST['re'] as $key=> $value)
  {
  if($key=='check'||$key=='txt'||$key=='pid'||$key=='form_type'||$key=='form_id'||$key=='proj_sign'||$key=='img_project') continue;
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'"; 
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  

 $col.=')';
 $val.=')';
   $qr='select * from "reset_store_summary" where form_id='.$form_id.' and type=\'ral_reset\'';
 $r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(pg_num_rows($r)>0)
 $query='update reset_store_summary set '.$up_qr.' where form_id='.$form_id.' and type=\'ral_reset\'';  
 else
$query='insert into reset_store_summary '. $col.$val;
//echo $query;


 if (!($result = pg_query($connection, $query)))
    {
        print("Failed ralph reset summary submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
    $ret['status']=1;

}

if(trim($_POST['mh']['store_num'])!='')
{        
$col='("pid","type","form_id"';
$val=" values('".$_POST['pid']."','ral_reset',".$form_id;
$up_qr=' "pid"='.$_POST['pid'];
  foreach($_POST['mh'] as $key=> $value)
  {
  if($key=='check'||$key=='txt'||$key=='pid'||$key=='form_type'||$key=='form_id'||$key=='proj_sign'||$key=='img_project') continue;
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'"; 
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  

 $col.=')';
 $val.=')';
   $qr='select * from "missing_hardware" where form_id='.$form_id.' and type=\'ral_reset\'';
 $r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(pg_num_rows($r)>0)
 $query='update missing_hardware set '.$up_qr.' where form_id='.$form_id.' and type=\'ral_reset\'';  
 else
$query='insert into missing_hardware '. $col.$val;
//echo $query;


 if (!($result = pg_query($connection, $query)))
    {
        print("Failed ralph reset summary submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';


}


if(trim($_POST['ssr']['store'][0])!='')
{
    extract($_POST['ssr']);
$col='"pid","type","form_id"';
$val=" '".$_POST['pid']."','ral_reset',".$form_id;
$up_qr='pid='.$_POST['pid'];
if(isset($store_num))
{
  $col.=',store_num';  
  $val.=',\''.$store_num.'\'';
  $up_qr.=',store_num='.'\''.$store_num.'\'';
}
if(isset($dist))
{
  $col.=',dist';  
  $val.=',\''.$dist.'\'';
  $up_qr.=',dist='.'\''.$dist.'\'';
}
if(isset($date))
{
  $col.=',date';  
  $val.=',\''.$date.'\'';
  $up_qr.=',date='.'\''.$date.'\'';
}

 $qr='select * from "ssr_form" where form_id='.$form_id.' and type=\'ral_reset\'';
 $r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(pg_num_rows($r)>0)
 {
   $qr='update  ssr_form  set '.$up_qr.' where form_id='.$form_id.' and type=\'ral_reset\''; 
   //echo $qr;
pg_query($connection, $qr); 
 }
 else
 {
 $qr='insert into ssr_form ('.$col.') values('.$val.')';  
 //echo $qr;
 pg_query($connection, $qr); 
 }
 $qr='';
 foreach($store as $i=>$st)
 {
$col='pid,type,form_id';
$val=''.$_POST['pid'].',\'ral_reset\','.$form_id;
$up_qr='pid='.$_POST['pid'];

if(isset($store[$i]))
{
  $col.=',store';  
  $val.=',\''.$store[$i].'\'';
  $up_qr.=',store='.'\''.$store[$i].'\'';
}
if(isset($comm_code[$i]))
{
  $col.=',comm_code';  
  $val.=',\''.$comm_code[$i].'\'';
  $up_qr.=',comm_code='.'\''.$comm_code[$i].'\'';
}
if(isset($krog_cat[$i]))
{
  $col.=',krog_cat';  
  $val.=',\''.$krog_cat[$i].'\'';
  $up_qr.=',krog_cat='.'\''.$krog_cat[$i].'\'';
}

if(isset($hdn_ssid[$i])&&$hdn_ssid[$i]>0)
{
 $qr.='update ssr_form_item set '.$up_qr.' where ss_it_id='.$hdn_ssid[$i].';';
// echo 'qry-'.$qr; 
}
 else
{
  $qr.='insert into  ssr_form_item ('.$col.') values('.$val.');';  
}
 }
 pg_query($connection, $qr); 
 $query = '';
    $ret['status']=1;
        $ret['reload_form']='yes';

}
    
if(trim($_POST['itm']['store_num'])!='')
{
    extract($_POST['itm']);
$col='pid,type,form_id';
$val=''.$_POST['pid'].',\'ral_reset\','.$form_id;
$up_qr='pid='.$_POST['pid'];
if(isset($store_num))
{
  $col.=',store_num';  
  $val.=',\''.$store_num.'\'';
  $up_qr.=',store_num='.'\''.$store_num.'\'';
}
if(isset($dist))
{
  $col.=',dist';  
  $val.=',\''.$dist.'\'';
  $up_qr.=',dist='.'\''.$dist.'\'';
}
if(isset($date))
{
  $col.=',date';  
  $val.=',\''.$date.'\'';
  $up_qr.=',date='.'\''.$date.'\'';
}

 $qr='select * from "item_mes_form" where form_id='.$form_id.'  and type=\'ral_reset\'';
 $r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(pg_num_rows($r)>0)
 {
   $qr='update  item_mes_form  set '.$up_qr.' where form_id='.$form_id.'  and type=\'ral_reset\''; 
   //echo $qr;
pg_query($connection, $qr); 
 }
 else
 {
 $qr='insert into item_mes_form ('.$col.') values('.$val.')';  
 //echo 'ttt'.$qr;
 pg_query($connection, $qr); 
 }
$qr='';
 foreach($schem_v as $i=>$st)
 {
$col='pid,type,form_id';
$val=''.$_POST['pid'].',\'ral_reset\','.$form_id;
$up_qr='pid='.$_POST['pid'];

 if(isset($schem_v)&&count($schem_v)>0)
 {
$col='pid,type,form_id';
$val=''.$_POST['pid'].',\'ral_reset\','.$form_id;
$up_qr='pid='.$_POST['pid'];

if(isset($schem_v[$i]))
{
  $col.=',schem_v';  
  $val.=',\''.$schem_v[$i].'\'';
  $up_qr.=',schem_v='.'\''.$schem_v[$i].'\'';
}
if(isset($upc[$i]))
{
  $col.=',upc';  
  $val.=',\''.$upc[$i].'\'';
  $up_qr.=',upc='.'\''.$upc[$i].'\'';
}
if(isset($prod_name[$i]))
{
  $col.=',prod_name';  
  $val.=',\''.$prod_name[$i].'\'';
  $up_qr.=',prod_name='.'\''.$prod_name[$i].'\'';
}
if(isset($height[$i]))
{
  $col.=',height';  
  $val.=',\''.$height[$i].'\'';
  $up_qr.=',height='.'\''.$height[$i].'\'';
}
if(isset($width[$i]))
{
  $col.=',width';  
  $val.=',\''.$width[$i].'\'';
  $up_qr.=',width='.'\''.$width[$i].'\'';
}
if(isset($depth[$i]))
{
  $col.=',depth';  
  $val.=',\''.$depth[$i].'\'';
  $up_qr.=',depth='.'\''.$depth[$i].'\'';
}
if(isset($shelf[$i]))
{
  $col.=',shelf';  
  $val.=',\''.$shelf[$i].'\'';
  $up_qr.=',shelf='.'\''.$shelf[$i].'\'';
}


if(isset($hdn_ssid[$i])&&$hdn_ssid[$i]>0)
{
 $qr.='update item_mes_form_item set '.$up_qr.' where ss_it_id='.$hdn_ssid[$i].';';  
}
 else
{
  $qr.='insert into  item_mes_form_item ('.$col.') values('.$val.');';  
}
 }
}
//echo 'qr-'.$qr;
 if($qr!='')
 pg_query($connection, $qr); 
 $query = '';
    $ret['status']=1;
   $ret['reload_form']='yes';

}
 $ret['reload_form']='yes';
    $ret['status']=1;
    

}
else if(trim($_POST['form_type'])=='ralph_checklist')
{        
$col='("pid"';
$val=" values('".$_POST['pid']."'";
$up_qr=' "pid"='.$_POST['pid'];
  foreach($_POST as $key=> $value)
  {
  if($key=='check'||$key=='txt'||$key=='pid'||$key=='form_type'||$key=='form_id'||$key=='proj_sign'||$key=='img_project'||$key=='mh'
          ||$key=='ssr'||$key=='pop_imgs') continue;
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'"; 
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  

 $col.=')';
 $val.=')';
   //$qr='select * from "ralph_checklist_form" where pid='.$_POST['pid'];
 //$r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
 if(isset($_POST['form_id'])&&$_POST['form_id']>0)
 {
 $ret['form_id']=$_POST['form_id'];
 $form_id=$_POST['form_id'];
 $query='update ralph_checklist_form set '.$up_qr.' where r_id='.$_POST['form_id']; 
 
 if (!($result = pg_query($connection, $query)))
    {
        print("Failed ralph checklist submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
 }
 else
 {
     $query='insert into ralph_checklist_form '. $col.$val;
//echo $query;
  
 if (!($result = pg_query($connection, $query)))
    {
        print("Failed ralph checklist submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';   
     $qr='select r_id from "ralph_checklist_form"  order by r_id desc limit 1';
 $r = pg_query($connection, $qr);
 $row=pg_fetch_array($r);
 $ret['form_id']=$row['r_id'];
  $form_id=$row['r_id'];   
 }
    $ret['reload_form']='yes';
    $ret['form_type']='ralphs_checklist';

    
 if(trim($_POST['mh']['store_num'])!='')
{        
$col='("pid","type","form_id"';
$val=" values('".$_POST['pid']."','ralph_chk',".$form_id;
$up_qr=' "pid"='.$_POST['pid'];
  foreach($_POST['mh'] as $key=> $value)
  {
  if($key=='check'||$key=='txt'||$key=='pid'||$key=='form_type'||$key=='form_id'||$key=='proj_sign'||$key=='img_project') continue;
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'"; 
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  

 $col.=')';
 $val.=')';
   $qr='select * from "missing_hardware" where form_id='.$form_id.' and type=\'ralph_chk\'';
 $r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(pg_num_rows($r)>0)
 $query='update missing_hardware set '.$up_qr.' where form_id='.$form_id.' and type=\'ralph_chk\'';  
 else
$query='insert into missing_hardware '. $col.$val;
//echo $query;


 if (!($result = pg_query($connection, $query)))
    {
        print("Failed ralph reset summary submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';


} 

if(trim($_POST['ssr']['store'][0])!='')
{
    extract($_POST['ssr']);
$col='"pid","type","form_id"';
$val=" '".$_POST['pid']."','ralph_chk',".$form_id;
$up_qr='pid='.$_POST['pid'];
if(isset($store_num))
{
  $col.=',store_num';  
  $val.=',\''.$store_num.'\'';
  $up_qr.=',store_num='.'\''.$store_num.'\'';
}
if(isset($dist))
{
  $col.=',dist';  
  $val.=',\''.$dist.'\'';
  $up_qr.=',dist='.'\''.$dist.'\'';
}
if(isset($date))
{
  $col.=',date';  
  $val.=',\''.$date.'\'';
  $up_qr.=',date='.'\''.$date.'\'';
}

 $qr='select * from "ssr_form" where form_id='.$form_id.' and type=\'ralph_chk\'';
 $r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(pg_num_rows($r)>0)
 {
   $qr='update  ssr_form  set '.$up_qr.' where form_id='.$form_id.' and type=\'ralph_chk\''; 
 //  echo $qr;
pg_query($connection, $qr); 
 }
 else
 {
 $qr='insert into ssr_form ('.$col.') values('.$val.')';  
 //echo $qr;
 pg_query($connection, $qr); 
 }
 $qr='';
 foreach($store as $i=>$st)
 {
$col='pid,type,form_id';
$val=''.$_POST['pid'].',\'ralph_chk\','.$form_id;
$up_qr='pid='.$_POST['pid'];

if(isset($store[$i]))
{
  $col.=',store';  
  $val.=',\''.$store[$i].'\'';
  $up_qr.=',store='.'\''.$store[$i].'\'';
}
if(isset($comm_code[$i]))
{
  $col.=',comm_code';  
  $val.=',\''.$comm_code[$i].'\'';
  $up_qr.=',comm_code='.'\''.$comm_code[$i].'\'';
}
if(isset($krog_cat[$i]))
{
  $col.=',krog_cat';  
  $val.=',\''.$krog_cat[$i].'\'';
  $up_qr.=',krog_cat='.'\''.$krog_cat[$i].'\'';
}

if(isset($hdn_ssid[$i])&&$hdn_ssid[$i]>0)
{
 $qr.='update ssr_form_item set '.$up_qr.' where ss_it_id='.$hdn_ssid[$i].';';
// echo 'qry-'.$qr; 
}
 else
{
  $qr.='insert into  ssr_form_item ('.$col.') values('.$val.');';  
}
 }
 pg_query($connection, $qr); 
 $query = '';
    $ret['status']=1;
      

}

    
    
    $ret['reload_form']='yes';
    $ret['status']=1;

}
else if(trim($_POST['form_type'])=='reset_store_summ')
{        
$col='("pid","form_id"';
$val=" values('".$_POST['pid']."',".$form_id;
$up_qr=' "pid"='.$_POST['pid'];
  foreach($_POST as $key=> $value)
  {
  if($key=='check'||$key=='txt'||$key=='pid'||$key=='form_type'||$key=='form_id'||$key=='proj_sign'||$key=='img_project') continue;
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'"; 
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  

 $col.=')';
 $val.=')';
   $qr='select * from "reset_store_summary" where form_id='.$form_id;
 $r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(pg_num_rows($r)>0)
 $query='update reset_store_summary set '.$up_qr.' where form_id='.$form_id;  
 else
$query='insert into reset_store_summary '. $col.$val;
//echo $query;


 if (!($result = pg_query($connection, $query)))
    {
        print("Failed ralph reset summary submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
    $ret['status']=1;

}
else if(trim($_POST['form_type'])=='missing_hardware')
{        
$col='("pid"';
$val=" values('".$_POST['pid']."'";
$up_qr=' "pid"='.$_POST['pid'];
  foreach($_POST as $key=> $value)
  {
  if($key=='check'||$key=='txt'||$key=='pid'||$key=='form_type'||$key=='form_id'||$key=='proj_sign'||$key=='img_project') continue;
  $col.=',"'.$key.'"';  
  $up_qr.=',"'.$key.'"=';
  if($value=='')
  {
    $val.=",null";  
    $up_qr.='null';
  }
  else
  {
  $val.=",'".pg_escape_string($value)."'"; 
  $up_qr.="'".pg_escape_string($value)."'";
  }
         
  }
  

 $col.=')';
 $val.=')';
   $qr='select * from "missing_hardware" where pid='.$_POST['pid'];
 $r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(pg_num_rows($r)>0)
 $query='update missing_hardware set '.$up_qr.' where pid='.$_POST['pid'];  
 else
$query='insert into missing_hardware '. $col.$val;
//echo $query;


 if (!($result = pg_query($connection, $query)))
    {
        print("Failed ralph reset summary submit: " . pg_last_error($connection));
        exit;
    }
    pg_freeresult($result);
    $query = '';
    $ret['status']=1;

}
else if(trim($_POST['form_type'])=='ssr_form')
{
    extract($_POST);
$col='pid';
$val=''.$_POST['pid'];
$up_qr='pid='.$pid;
if(isset($store_num))
{
  $col.=',store_num';  
  $val.=',\''.$store_num.'\'';
  $up_qr.=',store_num='.'\''.$store_num.'\'';
}
if(isset($dist))
{
  $col.=',dist';  
  $val.=',\''.$dist.'\'';
  $up_qr.=',dist='.'\''.$dist.'\'';
}
if(isset($date))
{
  $col.=',date';  
  $val.=',\''.$date.'\'';
  $up_qr.=',date='.'\''.$date.'\'';
}

 $qr='select * from "ssr_form" where pid='.$_POST['pid'];
 $r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(pg_num_rows($r)>0)
 {
   $qr='update  ssr_form  set '.$up_qr.' where pid='.$_POST['pid']; 
  // echo $qr;
pg_query($connection, $qr); 
 }
 else
 {
 $qr='insert into ssr_form ('.$col.') values('.$val.')';  
 //echo $qr;
 pg_query($connection, $qr); 
 }
 $qr='';
 foreach($store as $i=>$st)
 {
$col='pid';
$val=''.$_POST['pid'];
$up_qr='pid='.$pid;

if(isset($store[$i]))
{
  $col.=',store';  
  $val.=',\''.$store[$i].'\'';
  $up_qr.=',store='.'\''.$store[$i].'\'';
}
if(isset($comm_code[$i]))
{
  $col.=',comm_code';  
  $val.=',\''.$comm_code[$i].'\'';
  $up_qr.=',comm_code='.'\''.$comm_code[$i].'\'';
}
if(isset($krog_cat[$i]))
{
  $col.=',krog_cat';  
  $val.=',\''.$krog_cat[$i].'\'';
  $up_qr.=',krog_cat='.'\''.$krog_cat[$i].'\'';
}

if(isset($hdn_ssid[$i])&&$hdn_ssid[$i]>0)
{
 $qr.='update ssr_form_item set '.$up_qr.' where ss_it_id='.$hdn_ssid[$i].';';  
}
 else
{
  $qr.='insert into  ssr_form_item ('.$col.') values('.$val.');';  
}
 }
 pg_query($connection, $qr); 
 $query = '';
    $ret['status']=1;
        $ret['reload_form']='yes';

}
else if(trim($_POST['form_type'])=='item_mes')
{
    extract($_POST);
$col='pid';
$val=''.$_POST['pid'];
$up_qr='pid='.$pid;
if(isset($store_num))
{
  $col.=',store_num';  
  $val.=',\''.$store_num.'\'';
  $up_qr.=',store_num='.'\''.$store_num.'\'';
}
if(isset($dist))
{
  $col.=',dist';  
  $val.=',\''.$dist.'\'';
  $up_qr.=',dist='.'\''.$dist.'\'';
}
if(isset($date))
{
  $col.=',date';  
  $val.=',\''.$date.'\'';
  $up_qr.=',date='.'\''.$date.'\'';
}

 $qr='select * from "item_mes_form" where pid='.$_POST['pid'];
 $r = pg_query($connection, $qr);

 //if(isset($_POST['form_id'])&&$_POST['form_id']!='')
   if(pg_num_rows($r)>0)
 {
   $qr='update  item_mes_form  set '.$up_qr.' where pid='.$_POST['pid']; 
  // echo $qr;
pg_query($connection, $qr); 
 }
 else
 {
 $qr='insert into item_mes_form ('.$col.') values('.$val.')';  
 //echo $qr;
 pg_query($connection, $qr); 
 }
$qr='';
 foreach($schem_v as $i=>$st)
 {
$col='pid';
$val=''.$_POST['pid'];
$up_qr='pid='.$pid;

 if(isset($schem_v)&&count($schem_v)>0)
 {
$col='pid';
$val=''.$_POST['pid'];
$up_qr='pid='.$pid;

if(isset($schem_v[$i]))
{
  $col.=',schem_v';  
  $val.=',\''.$schem_v[$i].'\'';
  $up_qr.=',schem_v='.'\''.$schem_v[$i].'\'';
}
if(isset($upc[$i]))
{
  $col.=',upc';  
  $val.=',\''.$upc[$i].'\'';
  $up_qr.=',upc='.'\''.$upc[$i].'\'';
}
if(isset($prod_name[$i]))
{
  $col.=',prod_name';  
  $val.=',\''.$prod_name[$i].'\'';
  $up_qr.=',prod_name='.'\''.$prod_name[$i].'\'';
}
if(isset($height[$i]))
{
  $col.=',height';  
  $val.=',\''.$height[$i].'\'';
  $up_qr.=',height='.'\''.$height[$i].'\'';
}
if(isset($width[$i]))
{
  $col.=',width';  
  $val.=',\''.$width[$i].'\'';
  $up_qr.=',width='.'\''.$width[$i].'\'';
}
if(isset($depth[$i]))
{
  $col.=',depth';  
  $val.=',\''.$depth[$i].'\'';
  $up_qr.=',depth='.'\''.$depth[$i].'\'';
}
if(isset($shelf[$i]))
{
  $col.=',shelf';  
  $val.=',\''.$shelf[$i].'\'';
  $up_qr.=',shelf='.'\''.$shelf[$i].'\'';
}


if(isset($hdn_ssid[$i])&&$hdn_ssid[$i]>0)
{
 $qr.='update item_mes_form_item set '.$up_qr.' where ss_it_id='.$hdn_ssid[$i].';';  
}
 else
{
  $qr.='insert into  item_mes_form_item ('.$col.') values('.$val.');';  
}
 }
}

 if($qr!='')
 pg_query($connection, $qr); 
 $query = '';
    $ret['status']=1;
   $ret['reload_form']='yes';

}

extract($_POST);
//echo 'title->'.$name_title.'H'.$mngr_writ_store.'H';
if(($mngr_name!=''||$mngr_name!=''||$print_name!=''||$name_title!='')&&
  ($mngr_storenum!=''||$mngr_writ_store!=''||$store_mngr!=''||$manager_storenum!='')){
$query="select bulb_stat from projects where pid=".$_POST['pid'];
 $r = pg_query($connection, $query);
$row=  pg_fetch_array($r);
$bulb_stat='sign';
if($row['bulb_stat']=='both') $bulb_stat='both';
else if($row['bulb_stat']=='img') $bulb_stat='both';

if($_POST['mngr_sign']!='') $bulb_stat='both';

$query="update projects set bulb_stat='".$bulb_stat."' where pid=".$_POST['pid'];
//echo $query;
pg_query($connection, $query);
}
header('content-type:application/json');
//echo json_encode($ret);

header('content-type:application/json');
echo json_encode($ret);
?>