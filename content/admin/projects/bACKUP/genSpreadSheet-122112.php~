<?php
require('Application.php');
$return_arr = array();
$return_arr['fileName'] = "";

extract($_POST);

$path = $mydirectory."/upload_files/reports/";
$filename = "Project_Reports_csvfile.csv";
$fullPath = $path.$filename;
$return_arr = array();

$return_arr['fileName'] = "";
if(file_exists($fullPath))
{
	@ chmod($fullPath,0777);
	@ unlink($fullPath);
}


$where=' where prj.status=1 ';
if(isset($date_from)&&$date_from!="")
{
     $date_arr = explode('/',$date_from);
    $from_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);

 $where.=' AND m.due_date>='.$from_date;   
}
if(isset($date_to)&&$date_to!="")
{
      $date_arr = explode('/',$date_to);
    $to_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);

 $where.=' AND m.due_date<='.$to_date;   

}


$k=1;
$content = ',,,Project Reports       '."\n\n";
$file=fopen($fullPath,'w');
$content .= 'Start Date,Merchandiser,Chain,Store#,City,client,Start Time ,Notes'."\n";
fwrite($file, $content);
//echo $content;
$content ="";

do
{
    if(isset($datalist))
        unset($datalist);
    $datalist=array();
//echo $k.'<br/>';
$sql ='select prj.*,str.sto_name,c.client,chain.chain,m.*,m1.firstname as m1first, m1.lastname as m1last  from projects as prj left join "prj_merchants" as m on  prj.pid=m.pid  ';
 $sql .= ' left join "employeeDB" as m1 on m1."employeeID"=m.merch';
 $sql.=' left join "clientDB" as c on c."ID"=m.cid ';
 $sql.=" left join tbl_chain as chain on chain.ch_id=m.location ";
  $sql .= " left join tbl_store as str on str.sid=m.location";
 $sql .= '  '.$where.' limit 10 offset '.$k;
 //echo $sql;
if (!($result = pg_query($connection, $sql)))
    {
        $ret['error'] = "Failed to add / edit project: " . pg_last_error($connection);
        echo json_encode($ret);
        return;
    }
 $i=-1;  

while($out = pg_fetch_array($result)){
 if(!isset($prev)||($out['pid']!=$prev))
 {
     $i+=1;
 $datalist[$i]=array();

 }
    $datalist[$i][] = $out;
    $prev=$out['pid'];

}
$num_row=pg_num_rows($result);
//echo 'num-'.$num_row;
pg_free_result($result);

//echo $sql;
//print_r($datalist);

header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=$filename");




for($i=0; $i <count($datalist); $i++)
{
   
$content = "\n".$datalist[$i][0][3].'                                  '."\n\n";  
//$content .= 'Merchandiser,Start Date,Start Time ,Chain,Store#,Address,Phone,Notes,City,Zip'."\n";
   for($j=0;($datalist[$i][$j][14])!='';$j++)
   {
       if($datalist[$i][$j]['due_date']!='')
       $content .= '"'.rtrim(str_replace('"','""',date('m/d/Y',$datalist[$i][$j]['due_date']))).'",'; 
   else 
       $content .= '"",'; 
  
	$content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j][30].' '.$datalist[$i][$j][31])).'",';
	 $content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j][13])).'",';
	
	
	$content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j][11])).'",';
        $content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j][28])).'",';
        $content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j][12])).'",';
        $content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j][23])).'",'; 
        
	
         $content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j]['notes'])).'"'."\n";
	
        fwrite($file, $content);
	$content ="";
   }
   //echo $content;
	/*fwrite($file, $content);
	$content ="";*/
}
$k+=10;
}while($num_row>0);
fclose($file);
$return_arr['fileName'] = $filename;
echo json_encode($return_arr);
exit; 		
?>