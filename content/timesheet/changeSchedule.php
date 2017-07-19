<?php
require('Application.php');

extract($_POST);
if($pid>0)
{

$sql="select * from prj_merchants_new  where pid =".$pid." and merch=".$_SESSION['employeeID']." order by m_id asc";
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("Failed query: " . pg_last_error($connection));
	exit;
}
//	echo '<option value="">----SELECT-----</option>';
$sch=array();
	while($row=pg_fetch_array($result))
	{
  $sch[]=$row;         
        }
        print_r($sch);
 pg_free_result($result);       
 $i=0;     
 for($i=0;$i<count($sch);$i++)
 {
 $m_prev_f=0;       
for($j=0;$j<$i;$j++)
{
 if($sch[$j]['merch']==$sch[$i]['merch']&&$sch[$j]['due_date']==$sch[$i]['due_date']&&$sch[$j]['st_time']==$sch[$i]['st_time'])
 {
$m_prev_f=1;     
 }    
}
if($m_prev_f==0)
{           
		
echo '<option value="'.$sch[$i]['m_id'].'">'.date('m/d/Y',$sch[$i]['due_date']).' '.$sch[$i]['st_time'].'</option>';
	}
     
        
	

}
}
?>