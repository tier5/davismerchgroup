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
        $columns="prj_name";
        $values="'".$prj_data['prj_name']."-copy'";
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
        
        
$sql = 'SELECT * from prj_merchants where pid='.$pid;
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
            if($i%2!=0 && $value!="" && $key!='m_id' &&$key!='pid')
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
$sql.='insert into prj_merchants ('.$columns.') values('.$values.')';      
    else
$sql.=';insert into prj_merchants ('.$columns.') values('.$values.')';           
        
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