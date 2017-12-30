<?php
require('Application.php');
$return_arr = array();
$return_arr['fileName'] = "";

extract($_POST);


$datalist=array();




if(isset($_REQUEST['employee']))
{
	if($_REQUEST['employee'] > 0)
	{
		$search_sql .= " and t.emp_id=".$_REQUEST['employee'];
		
	}
}
if(isset($_REQUEST['store']))
{
	if($_REQUEST['store'] != '')
	{
		$search_sql .= " and store='".$_REQUEST['store']."'";
	
	}
}
if(isset($_REQUEST['start_dt']))
{
    $st=split(' ',$_REQUEST['start_dt']);
	$start = strtotime($st[0]);
	if($start)
	{
		$search_sql .= " and t.start_time>='".$start."'";
				
	}
       
}
if(isset($_REQUEST['end_dt']))
{
    $et=split(' ',$_REQUEST['end_dt']);
	$end = strtotime($et[0]);
	if($_REQUEST['start_dt'] != '' && $_REQUEST['start_dt'] == $_REQUEST['end_dt'])
		$end = strtotime('+1 day', $end);		
	if($end)
	{
		$search_sql .= " and t.start_time<='".$end."'";
		
	}
}
if(isset($_REQUEST['status']) && $_REQUEST['status'] != '')
{
	$search_sql .= " and t.status='".$_REQUEST['status']."'";
	
}


$sql = 'SELECT distinct e.*,reg.region as region,t.*,odo.* FROM dtbl_timesheet as t left join "employeeDB" as e on e."employeeID" = t.emp_id left join dtbl_odometer as odo on odo.time_id=t.time_id'
.' left join tbl_region as reg on reg.rid=e.region  ';  

  if($search_sql!="")  
$sql .= ' WHERE t.emp_id>0 '.$search_sql;

$sql .= ' order by e."employeeID"';
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("DB ERROR: " . pg_last_error($connection));
	exit;
}
while($row = pg_fetch_array($result))
{
	$datalist[]=$row;
}
pg_free_result($result);


//print_r($datalist[0]);








$content = '<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td class="grid001">S or H</td>
    <td class="grid001">EMPLOYEE</td>
    <td class="grid001">REGION</td>
	<td class="grid001">City / Location</td>
    <td class="grid001">PAY RATE</td>
    <td class="grid001">O/T RATE</td>
    <td class="grid001">REG Hours</td>
    <td class="grid001">OT Hours</td>
     <td class="grid001">DRIVE TIME MISC</td>
     <td class="grid001">MILEAGE</td>
      <td class="grid001">EXPENSES</td>
      <td class="grid001">GRAND TOTAL</td>
  </tr>';

$emp_ls=array();
//echo count($emp_lst);

for($i=0; $i < count($datalist); $i++)
{
    $flag=0;
   
for($j=0;$j<count($emp_ls);$j++)
{
  /*  $emp_ls[$j]['reg_hours']+=$datalist[$i]['reg_hours'];
    $ot_hr+=$datalist[$i]['ot_hours'];
     $misc_exp+=$datalist[$i]['misc_exp'];
     $daily_tot+=$datalist[$i]['daily_total'];
      $r_mile+=$datalist[$i]['reimburse_mile'];*/
if($emp_ls[$j]['emp']==$datalist[$i]['employeeID'])
{$flag=1; break;}    
}
//echo $datalist[$i]['lastname'].'-'.$reg_hr;
if($flag==0)
{
    $cnt=count($emp_ls);
$emp_ls[$cnt]['emp']=$datalist[$i]['employeeID'];
if($datalist[$i]['salary']=='Yes')
 $emp_ls[$cnt]['soh']="S"  ; 
else 
  $emp_ls[$cnt]['soh']="H"  ; 

$emp_ls[$cnt]['emp_name']=$datalist[$i]['lastname'].' '.$datalist[$i]['firstname'];  
$emp_ls[$cnt]['region']=$datalist[$i]['region'];
$emp_ls[$cnt]['city']=$datalist[$i]['city'];
$emp_ls[$cnt]['wage']=intval($datalist[$i]['wage']);  
$emp_ls[$cnt]['ot_rate']= intval($datalist[$i]['wage'])*1.5;  
$emp_ls[$cnt]['reg_hours']=$datalist[$i]['reg_hours'];
$emp_ls[$cnt]['ot_hours']=$datalist[$i]['ot_hours'];
$emp_ls[$cnt]['r_mile']=$datalist[$i]['reimburse_mile'];
$emp_ls[$cnt]['milage']=($datalist[$i]['reimburse_mile']*.405)-(intval($datalist[$i]['wage'])/2);
if($emp_ls[$cnt]['milage']<0)$emp_ls[$cnt]['milage']=0;
$emp_ls[$cnt]['misc_exp']=$misc_exp;
$emp_ls[$cnt]['daily_tot']=$daily_tot;
$emp_ls[$cnt]['grant_total']= ($datalist[$i]['reg_hours']* intval($datalist[$i]['wage'])) + ($ot_hr * $emp_ls[$cnt]['ot_rate']) + ($emp_ls[$cnt]['milage']) + ($misc_exp);

}
else {
  $emp_ls[$j]['reg_hours']+=$datalist[$i]['reg_hours'];  
  $emp_ls[$j]['ot_hours']+=$datalist[$i]['ot_hours'];
  $emp_ls[$j]['misc_exp']+=$datalist[$i]['misc_exp'];
  $emp_ls[$j]['daily_tot']+=$datalist[$i]['daily_total'];
  $emp_ls[$j]['r_mile']+=$datalist[$i]['reimburse_mile'];
$emp_ls[$j]['grant_total']+= ($datalist[$i]['reg_hours']* intval($datalist[$i]['wage'])) + ($ot_hr * $emp_ls[$j]['ot_rate']) + ($emp_ls[$j]['milage']) + ($misc_exp);

}
}
//echo $emp_lst[2].'ff1';
//print_r($emp_ls);
//$content ="";
$tot_reg_hours=0;
$tot_ot_hours=0;
$tot_grant_total=0;
for($i=0; $i < count($emp_ls); $i++)
{
  $content .='<tr >'; 
  $content .= '<td class="gridVal" >'.$emp_ls[$i]['soh'].'</td>'; 
  $content .= '<td class="gridVal">'.$emp_ls[$i]['emp_name'].'</td>';
  $content .= '<td class="gridVal">'.$emp_ls[$i]['region'].'</td>';
  $content .= '<td class="gridVal">'.$emp_ls[$i]['city'].'</td>';
	$content .= '<td class="gridVal">'.$emp_ls[$i]['wage'].'</td>';
	$content .= '<td class="gridVal">'.$emp_ls[$i]['ot_rate'].'</td>';
	$content .= '<td class="gridVal">'.$emp_ls[$i]['reg_hours'].'</td>';
	$content .= '<td class="gridVal" >'.$emp_ls[$i]['ot_hours'].'</td>';
         $content .= '<td class="gridVal">'.$emp_ls[$i]['r_mile'].'</td>';
        
       // $content .= '<td class="gridVal">'.$emp_ls[$i]['daily_tot'].'</td>';
        $content .= '<td class="gridVal">$'.$emp_ls[$i]['milage'].'</td>';
       $content .= '<td align="left" valign="top" class="gridVal">'.$emp_ls[$i]['misc_exp'].'</td>';
	$content .= '<td class="gridVal">$'.$emp_ls[$i]['grant_total'].'</td>';
        $content .='</tr >'; 
 $tot_reg_hours+=$emp_ls[$i]['reg_hours'];    
 $tot_ot_hours+=$emp_ls[$i]['ot_hours'];  
 $tot_grant_total+=$emp_ls[$i]['grant_total'];  
}	
$content.='<tr><td colspan="6" align="center">TOTALS</td>';
$content.='<td >'.$tot_reg_hours.'</td>';
$content.='<td >'.$tot_ot_hours.'</td>';
$content.='<td colspan="3">&nbsp;</td>';
$content.='<td >$'.$tot_grant_total.'</td>';
$content.='</tr>'; ?>
<!--Rate of pay($):<input type="text" id="rate" value="<?php// echo $pay_rate;?>"/>
<input type="Button" value="Calculate" onclick="javascript:payRoll($('#rate').val());"/>-->
<br/><br/>

<?php echo $content;		
?>
<script type="text/javascript">
$( "#subpage" ).dialog({ title: "Pay Roll Report" });
$( "#subpage" ).dialog({ width: 950 });
$( "#subpage" ).dialog({ buttons: {"Download as Excel File": function(){dwldExcel();},"Print": function(){printSelection($(this));},"Close": function() {$(this).dialog( "close" );}}});
function printSelection(node){
  var content=node.html();
  var pwin=window.open('','print_content','width=900,height=400%');

  pwin.document.open();
  pwin.document.write('<html><body onload="window.print()"><head><style type="text/css"> .grid001{background-image:url(<?php echo $mydirectory; ?>/images/headerbg.jpg);font-family:Tahoma, Verdana, Arial, Helvetica;color:#000033;font-size:12px;font-weight:bold; padding-top:10px; padding-left:10px;}  .gridVal{background: url("<?php echo $mydirectory; ?>/css/images/ui-bg_highlight-hard_100_f2f5f7_1x100.png") repeat-x scroll 50% top #F2F5F7;height:25px;border: 1px solid #DDDDDD;font-family:Tahoma, Verdana, Arial, Helvetica;font-size:12px;color: #362B36;} </style></head><div><img src="<?php echo $mydirectory; ?>/images/logo.gif" alt="Davis Merchandising Group" />' +content+'</body></html>');
  pwin.document.close(); 
  setTimeout(function(){pwin.close();},1000);
}
</script>