<?php
require 'Application.php';

$pid=$_POST['pid'];
$ret_arr=array();
$ret_arr["status"]="";

 $query  = "SELECT file_name, file_type FROM prj_uploads WHERE pid=$pid;";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed File query: " . pg_last_error($connection));
        exit;
    }
 while($row=pg_fetch_array($result))
 {
    // print_r($row);
 
        @unlink ($image_dir.$row['file_name']);

 }
     pg_free_result($result);

$table_list=array("prj_merchants_new","prj_uploads","projects");
$query="";
foreach($table_list as $table)
{
 if($query!="") $query.=";";
 $query .= "DELETE FROM $table WHERE pid=$pid";
    
}
//echo $query;


  if (!($result = pg_query($connection, $query)))
    {
     $ret_arr["status"]="0";
    }
    else{
     $ret_arr["status"]="1";    
    pg_freeresult($result);
    }
    $query = '';
    
  header('Content-type: application/json');  
  echo json_encode($ret_arr);

?>