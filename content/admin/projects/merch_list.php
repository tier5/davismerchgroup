<?php
require_once 'Application.php';
$pid=$_POST['pid'];
   $merch_list=array();
   if ($pid > 0)
{
   $sql = "SELECT reg.region,merch.location,merch.store_num,merch.email,merch.confirm,  str2.sto_num as str2_sto_num ,chain.chain,merch.m_id,str.sto_name,str.sto_num,merch.store_num,merch.st_time,merch.cid,merch.merch,merch.notes, merch.address, merch.phone, merch.city, merch.zip, merch.due_date,prj.prj_name as prjname, c.client, c.\"clientID\", m1.firstname as m1first, m1.lastname as m1last ";
        $sql .= " FROM projects$ext as prj left join prj_merchants_new$ext as merch on merch.pid=prj.pid  left join \"clientDB\" as c on c.\"ID\" = merch.cid";
        $sql .= " left join tbl_chainmanagement as str on str.chain_id=merch.location";
		$sql.=" left join tbl_region as reg on reg.rid=merch.region ";
          $sql .= " left join tbl_chainmanagement as str2 on str2.chain_id=merch.store_num ";            
		$sql.=" left join tbl_chain as chain on chain.ch_id=merch.location ";
        $sql .= " left join \"employeeDB\" as m1 on m1.\"employeeID\"=merch.merch  where prj.pid=" . $pid." order by merch.m_id asc";

        //echo $sql;

        if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
        while ($row = pg_fetch_array($result)) {
            $merch_list[] = $row;
        }
        pg_free_result($result);
}
?>

<table width="100%" >

    <tr><td colspan="11">
            <div  style="height:300px;overflow:auto;">
     <table width="100%" >    
     <tr>
       <?php  if(!isset($is_client)||$is_client!=1){ ?>
        <td style="width:150px;" class="grid001">Merchandiser </td>
        <?php } ?>
          <?php if($f_prj_2==1){?>
        <td style="width:150px;" class="grid001">Region </td>
          <?php } ?>
        <td style="width:60px;" class="grid001">Start Date</td>
        <td style="width:60px;" class="grid001">Start Time</td>
        <td style="width:60px;" class="grid001">Chain</td>
        <td style="width:60px;" class="grid001">Store#</td>
        <td style="width:150px;" class="grid001">Client</td>
        <?php if($f_prj_2==1){?>
        <td style="width:150px;" class="grid001">Notes</td>
          <td style="width:150px;" class="grid001">Time entry</td>
          <?php } ?>
         <?php if($_SESSION['emp_type']==0&&($_SESSION['perm_admin'] == "on"||$_SESSION['perm_manager'] == "on")) { ?> 
        <td style="width:30px;"class="grid001">Edit</td>
           <?php if($f_prj_2==1){?>
        <td style="width:30px;" class="grid001">Delete</td>
         <?php } ?>
        <?php } ?>
        <td style="width:30px;" class="grid001">Duplicate</td>
         <td style="width:30px;" class="grid001">Email</td>
         <?php if($_SESSION['emp_type']==0||($_SESSION['perm_admin'] == "on"||$_SESSION['perm_manager'] == "on")||
          $merch_list[$i]['merch']==$_SESSION['employeeID'] )  { ?> 
          <td style="width:30px;" class="grid001">Confirm</td>
            <?php } ?>
    </tr>        
  <?php 
  //echo 'cnt'.count($merch_list);
  if(count($merch_list)>0)
  {
  for($i=0;$i<count($merch_list);$i++)
  {
    echo '<tr >';
    if(!isset($is_client)||$is_client!=1){
    echo '<td style="width:170px;padding-left: 10px;"  class="gridVal">' . $merch_list[$i]['m1first'] . ' ' . $merch_list[$i]['m1last'] .
         '<input type="hidden" name="merch[]" value="'.$merch_list[$i]['merch'].'"/></td>';
    }
      if($f_prj_2==1){
  echo '  <td style="width:170px;padding-left: 10px;" class="gridVal">' . $merch_list[$i]['region'] . '</td>';
      }
    echo '   <td style="width:60px;padding-left: 10px;"  class="gridVal">';
             if(isset($merch_list[$i]['due_date'])&&$merch_list[$i]['due_date']!="") 
 echo date('m/d/Y',$merch_list[$i]['due_date']);

    echo '</td>';
    echo '  <td style="width:60px;padding-left: 10px;" class="gridVal">' . $merch_list[$i]['st_time'] . '</td>';
    echo ' <td style="width:60px;padding-left: 10px;"  class="gridVal">' . $merch_list[$i]['chain'] . '</td>';
     echo ' <td style="width:60px;padding-left: 10px;"  class="gridVal">' . $merch_list[$i]['str2_sto_num'] . '</td>';
    echo ' <td style="width:150px;padding-left: 10px;" width="150px" class="gridVal">' . $merch_list[$i]['client'] . '</td>';
     if($f_prj_2==1){
    echo ' <td style="width:150px;padding-left: 10px;" width="150px" class="gridVal">' . $merch_list[$i]['notes'] . '</td>';
echo '<td style="width:30px;padding-left: 10px;" width="30px" class="gridVal" >';
if($merch_list[$i]['merch']==$_SESSION['employeeID']){
 $m_prev_f=0;       
for($j=0;$j<$i;$j++)
{
 if($merch_list[$j]['merch']==$merch_list[$i]['merch']&&$merch_list[$j]['due_date']==$merch_list[$i]['due_date']&&$merch_list[$j]['st_time']==$merch_list[$i]['st_time'])
 {
$m_prev_f=1;     
 }    
}
if($m_prev_f==0)
{   
echo '<img width="20" height="20" src="' . $mydirectory . '/images/clock.jpg" style="cursor:pointer;cursor:hand;" onclick="javascript:loadTimesheet(\'dt='.$merch_list[$i]['due_date'].'&from_prject=1&m_id='.$merch_list[$i]['m_id'].'\')"/>';
}
}
echo '</td> ';   
     }
if($_SESSION['emp_type']==0&&($_SESSION['perm_admin'] == "on"||$_SESSION['perm_manager'] == "on")) { 
  if($f_prj_2==1){  
    echo '<td style="width:30px;padding-left: 10px;" width="30px" class="gridVal" ><img width="20" height="20" src="' . $mydirectory . '/images/edit.png" style="cursor:pointer;cursor:hand;"  onclick="javascript:editMerchants(' . $merch_list[$i]['m_id'] . ')"/></td> ';
  }
  else{
echo '<td style="width:30px;padding-left: 10px;" width="30px" class="gridVal" ><a target="_blank" href="./project.php?pid='.$pid.'&m_id='.$merch_list[$i]['m_id'].'"><img width="20" height="20" src="' . $mydirectory . '/images/edit.png" style="cursor:pointer;cursor:hand;"  /></a></td> ';      
  }
    if($f_prj_2==1){
    echo '<td style="width:30px;padding-left: 10px;" width="60px" class="gridVal" ><img width="20" height="20" src="' . $mydirectory . '/images/1277880471_close.png" style="cursor:pointer;cursor:hand;" onclick="javascript:deleteMerchants(' . $merch_list[$i]['m_id'] . ',' . $pid . ')"/></td> ';
}}
echo '<td style="width:30px;padding-left: 10px;" width="60px" class="gridVal" ><img width="20" height="20" src="' . $mydirectory . '/images/add2.png" style="cursor:pointer;cursor:hand;" onclick="javascript:duplicate_merch(' . $merch_list[$i]['m_id'] . ',' . $pid . ')"/></td> ';
echo '<td style="width:30px;padding-left: 10px;" width="60px" class="gridVal" >';
if($merch_list[$i]['merch']!=$_SESSION['employeeID']){
echo '<img width="20" height="20" src="' . $mydirectory . '/images/';
if($merch_list[$i]['email']=='t') echo 'email-tick.png';
else echo 'email.png';
echo '" style="cursor:pointer;cursor:hand;" onclick="javascript:send_merch_email(' . $merch_list[$i]['m_id'] . ',' . $pid . ')"/>';
}
echo '</td> ';
if($_SESSION['emp_type']==0)   echo '<td style="width:30px;padding-left: 10px;" width="60px" class="gridVal" >';
if(($_SESSION['perm_admin'] == "on"||$_SESSION['perm_manager'] == "on")||
          $merch_list[$i]['merch']==$_SESSION['employeeID'] ) {

$m_prev_f=0;       
for($j=0;$j<$i;$j++)
{
 if($merch_list[$j]['merch']==$merch_list[$i]['merch']&&$merch_list[$j]['due_date']==$merch_list[$i]['due_date']&&$merch_list[$j]['st_time']==$merch_list[$i]['st_time'])
 {
$m_prev_f=1;     
 }    
}
if($m_prev_f==0)
{    
echo '<input type="checkbox" '; 
if($merch_list[$i]['confirm']=='t') echo ' checked';
echo ' onchange="merch_confirm('.$merch_list[$i]['m_id'].',$(this),'.$pid.');"/>';
}
if($_SESSION['emp_type']==0)
  echo '</td> ';
}
echo '</tr>';
  }
  }
  
  else
{ ?>
 <tr><td colspan="13"><h3 align="center">No merchandiser found...</h3></td></tr>     
<?php }
  ?>
     </table> 
        </div>    
        </td></tr>           
</table>