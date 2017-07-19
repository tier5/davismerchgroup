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


$k=0;
$content = ',,,Project Reports       '."\n\n";
$file=fopen($fullPath,'w');
$content .= 'Start Date,Merchandiser,client,Chain,Store#,City,Start Time ,Notes'."\n";
fwrite($file, $content);
//echo $content;
$content ="";

header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=$filename");

do
{
    if(isset($datalist))
        unset($datalist);
     if(isset($prev))
        unset($prev);
    $datalist=array();
//echo $k.'<br/>';
$sql ='select prj.*,str.sto_name,c.client ,chain.chain,m.*,m1.firstname as m1first, m1.lastname as m1last,str2.sto_num as str2_sto_num  from projects as prj left join "prj_merchants_new" as m on  prj.pid=m.pid  ';
 $sql .= ' left join "employeeDB" as m1 on m1."employeeID"=m.merch';
 $sql.=' left join "clientDB" as c on c."ID"=m.cid ';
 $sql.=" left join tbl_chain as chain on chain.ch_id=m.location ";
  $sql .= " left join tbl_chainmanagement as str on str.chain_id=m.location";
  $sql .= " left join tbl_chainmanagement as str2 on str2.chain_id=m.store_num ";
 $sql .= '  '.$where.' order by prj.pid desc limit 10 offset '.$k;
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
 //print_r($out).'<br/>';
    $datalist[$i][] = $out;
    $prev=$out['pid'];

}
$num_row=pg_num_rows($result);
//echo 'num-'.$num_row;
pg_free_result($result);

//echo $sql;


for($i=0; $i <count($datalist); $i++)
{

$content = "\n".$datalist[$i][0][3].'                                  '."\n\n";  
//$content .= 'Merchandiser1,Start Date,Start Time ,Chain,Store#,Address,Phone,Notes,City,Zip'."\n";
   for($j=0;$j<count($datalist[$i]);$j++)
   {

       if($datalist[$i][$j]['due_date']!='')
       $content .= '"'.rtrim(str_replace('"','""',date('m/d/Y',$datalist[$i][$j]['due_date']))).'",'; 
   else 
       $content .= '"",'; 
  
	$content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j]["m1first"].' '.$datalist[$i][$j]["m1last"])).'",';
        $content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j]["client"])).'",';
	 $content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j]["chain"])).'",';
	
	
	$content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j]["str2_sto_num"])).'",';
        $content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j]["city"])).'",';
        
        $content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j]["st_time"])).'",'; 
        
	
         $content .= '"'.rtrim(str_replace('"','""',$datalist[$i][$j]["notes"])).'"'."\n";
	
        fwrite($file, $content);
	$content ="";
 
   }

}

$k+=10;
}while($num_row>0);
fclose($file);
$return_arr['fileName'] = $filename;
echo json_encode($return_arr);
exit; 		
?>