<?php
require 'Application.php';
extract($_POST);
$sql = 'SELECT * from projects where pid='.$pid;

 if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
       $row = pg_fetch_array($result);
            $prj_data = $row;
        pg_free_result($result);
      // print_r($prj_data); 
        
 $sp_arr=split("-",$prj_data['prj_name']);

  $pn="";
 if(is_numeric($sp_arr[count($sp_arr)-1])) 
 {
  for($k=0;$k<(count($sp_arr)-1);$k++)
 {
 if($pn!="")
 $pn.="-";
 $pn.=$sp_arr[$k];
 }   

 }
 else {
 $pn=$prj_data['prj_name'];   
}

   $sql = "SELECT prj_name from projects where prj_name like '%".$pn."%' order by pid desc limit 1";  
 
    if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
 $r1=pg_fetch_array($result);
 pg_free_result($result);
 $n1=split("-",$r1['prj_name']);    
 if (is_numeric($n1[count($n1)-1]))
 $prj_name=$pn."-".(intval($n1[count($n1)-1])+1);
else 
  $prj_name=$pn."-1";

        $columns="prj_name";
        $values="'".$prj_name."'";
     $i=0;
        foreach($prj_data as $key => $value) {
            if($i%2!=0&&$value!=""&&$key!='pid' &&$key!='prj_name')
            {
      /*   if($columns=='')  
         {
                $columns.=$key;
                $values.="'". $value."'";
         }
            else*/   
                { 
            $columns.=','.$key;
            $values.=",'".$value."'";
            }
            }
$i+=1;            
}

if($columns!=""&&$values!="")
$sql='insert into projects ('.$columns.') values('.$values.')';
//echo $sql;
if (!(pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
        
    


  $sql    = "select max(pid) as max_pid from projects ";
        if (!($result = pg_query($connection, $sql)))
        {
            $ret['error'] = "Failed check project name: " . pg_last_error($connection);
            echo json_encode($ret);
            return;
        }
        $row = pg_fetch_array($result);
        pg_free_result($result);
        $sql = '';

$new_pid=$row['max_pid'];
        
        
$sql = 'SELECT * from prj_merchants_new where pid='.$pid;
//echo $sql;
 if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
        
        while ($row = pg_fetch_array($result)) {
            $prj_merch[] = $row;
        }
      
        pg_free_result($result);        
 // print_r($prj_merch);     
        
        
        $sql='';
        for($j=0;$j<count($prj_merch);$j++)
        {
     
           $ar=$prj_merch[$j]; 
            
    $columns="pid";
        $values=$new_pid;
     $i=0;
        foreach($ar as $key => $value) {
            if($i%2!=0 && $value!="" && $key!='m_id' &&$key!='pid'&& $key!='due_date'&& $key!='st_time')
            {
         if($columns=='')  
         {
                $columns.=$key;
                $values.="'". $value."'";
         }
            else   { 
            $columns.=','.$key;
            $values.=",'".$value."'";
            }
            }
$i+=1;            
}          
            
 if($columns!=""&&$values!="")
 {
if($sql=="")
$sql.='insert into prj_merchants_new ('.$columns.') values('.$values.')';      
    else
$sql.=';insert into prj_merchants_new ('.$columns.') values('.$values.')';           
        
 }        
        }
        
  //echo $sql;  
        
 if($sql!='')
 {
     
if (!(pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }     
     
     
 }

?>