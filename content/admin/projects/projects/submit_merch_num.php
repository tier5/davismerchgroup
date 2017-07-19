<?php
require 'Application.php';
extract($_POST);
$ret = array();
$ret['merch_id'] = '';
$ret['status'] = -1;
if(isset($num_merch)&&$num_merch>0)
    $sql='update projects  set num_merch='.$num_merch.' where pid='.$pid;
    else $sql='update projects  set num_merch=0 where pid='.$pid;

pg_query($connection, $sql);      

if(isset($num_merch)&&$num_merch>0){
  $sql='select * from prj_merchants_new where pid='.$pid;
  $result = pg_query($connection, $sql);
   $row=pg_fetch_array($result);
   $dif=$num_merch-pg_num_rows($result);
  // echo 'dif->'.pg_num_rows($result);
   if($dif>0)
   {
       $sql='';
       for($i=1;$i<=$dif;$i++)
       {
     $sql.='insert into  prj_merchants_new (pid) values('.$pid.');';         
       }
  //echo $sql;     
  pg_query($connection, $sql);     
   }
}

?>
