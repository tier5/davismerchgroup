<?php
require 'Application.php';
include '../../header.php';

if(isset($_GET['del'])&&$_GET['del']>0)
{

$query='delete  from announcement where anc_id='.$_GET['del'];    
$result=pg_query($query);
}
if(isset($_POST['region']))
{
extract($_POST);    
if(isset($hdn_anc_id)&&$hdn_anc_id>0)
{
$sql='update announcement set anc_id='.$hdn_anc_id;   
 if(isset($title)) $sql.=',title=\''.pg_escape_string ($title).'\'';
 else $sql.=',null';
 if(isset($msg)) $sql.=',msg=\''.pg_escape_string ($msg).'\'';
 else $sql.=',null';
  if(isset($region)) $sql.=',region='.$region;
 else $sql.=',null';
$sql.=' where anc_id='.$hdn_anc_id;
}
else{
 $sql='insert into announcement (emp_id,title,msg,region) values('.$_SESSION['employeeID'];   
 if(isset($title)) $sql.=',\''.pg_escape_string ($title).'\'';
 else $sql.=',null';
  if(isset($msg)) $sql.=',\''.pg_escape_string ($msg).'\'';
 else $sql.=',null';
  if(isset($region)) $sql.=','.$region;
 else $sql.=',null';
 $sql.=')';
}
// echo $sql;
 pg_query($sql);
}


$query='select * from tbl_region  order by region asc';
$result=pg_query($query);
$reg=array();
while($row=pg_fetch_array($result))
{
 $reg[]=$row;   
}
pg_free_result($result);
unset($row);
include('../../pagination.class.php');
$query='select anc.*,reg.region from announcement as anc left join tbl_region as reg on anc.region=reg.rid';
if(isset($_GET['region'])&&$_GET['region']!='') $query.=' where anc.region='.$_GET['region'];
$query.=' order by anc_id desc';
if (!($resultp = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$ancmnt=array();
if(isset($_GET['edit'])&&$_GET['edit']>0)
{
 $sql='select * from announcement where anc_id='.$_GET['edit'].' limit 1';    
 //echo $sql;
 $re=pg_query($sql);
 $ancmnt=pg_fetch_array($re);
 pg_free_result($re);
 //print_r($ancmnt);
}
$items = pg_num_rows($resultp);
if ($items > 0) {
    $p = new pagination;
    $p->items($items);
    $p->limit(10); // Limit entries per page
    $uri = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '&paging'));
    if (!$uri) {
        $uri = $_SERVER['REQUEST_URI'] . $search_uri;
    }
    //echo "uri==>".$uri;
    $p->target($uri);
    $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
    $p->calculate(); // Calculates what to show
    $p->parameterName('paging');
    $p->adjacents(1); //No. of page away from the current page

    if (!isset($_GET['paging'])) {
        $p->page = 1;
    } else {
        $p->page = $_GET['paging'];
    }
    //Query for limit paging
    $limit = "LIMIT " . $p->limit . " OFFSET " . ($p->page - 1) * $p->limit;
}
$query = $query . " " . $limit;

//echo $sql;

if (!($resultp = pg_query($connection, $query))) {
    print("Failed queryd: " . pg_last_error($connection));
    exit;
}

$anc_list=array();
while($row=pg_fetch_array($resultp))
{
 $anc_list[]=$row;   
}
pg_free_result($resultp);

?>
<h1 align="center">Add/Edit Announcements</h1>
<form action="" method="post">
<fieldset width="100px">
    <legend><strong>Add Announcement</strong></legend> 
<table width="100px">
    <tr>
 <td width="50px">Region:</td>
 <td><select id="region" name="region">
   <?php 
foreach($reg as $r)
{?>
         <option  <?php if(isset($ancmnt['region'])&&$ancmnt['region']==$r['rid']) echo ' selected ';?> value="<?php echo $r['rid'];?>"><?php echo $r['region'];?></option>    
<?php }
   ?>
     </select></td>
    </tr>
<tr>
    <td>Title:</td><td>
<input type="text"  name="title" id="title" value="<?php echo $ancmnt['title']; ?>"/></td></tr>
<tr>
    <td>Message:</td><td>
<textarea   name="msg" cols="70" rows="10" id="msg"><?php echo $ancmnt['msg']; ?></textarea></td></tr>
<tr>
<td colspan="2" align="center">
 <?php if(isset($ancmnt['anc_id'])){?>   
<input type="hidden" name="hdn_anc_id" value="<?php echo $ancmnt['anc_id'];?>"/>    
 <?php } ?>   
<input type="submit"   value="Save"/>&nbsp;<input type="button" value="Cancel" onclick="location.href='./index.php';"/></td></tr>
</table></fieldset>
</form>    
<br/><br>
    <form action="" method="GET">    
<table width="450px">
<tr><td colspan="3"><h2>Announcements</h2></td></tr>   
<tr>
    <td>
    Select region :    
    </td> 
    <td>
   <select name="region" id="cat_id_srch" onchange="">
       <option value="">--Select--</option>    
   <?php 
foreach($reg as $r)
{?>
         <option <?php if($r['rid']==$_GET['region']) echo ' selected '; ?> value="<?php echo $r['rid'];?>"><?php echo $r['region'];?></option>    
<?php }
   ?>
     </select>    
    </td>
    <td><input type="submit" value="Search"/></td>
    <td><input type="button" value="Cancel" onclick="location.href='./index.php';"/></td>
</tr>
</table>
    </form>  
<br/><br/>
<div >     
<table width="700px" cellspacing="1" cellpadding="1" border="0">    
<tr ><td style="font-size:15px;" width="20%" class="grid001">Title</td><td style="font-size:15px;" width="60%" class="grid001">Message&nbsp;&nbsp;</td><td width="4%" style="font-size:15px;" class="grid001">&nbsp;&nbsp;Region&nbsp;&nbsp;</td><td class="grid001" width="4%" style="font-size:15px;">Edit</td><td class="grid001" width="4%" style="font-size:15px;">Remove</td></tr>    
 <?php
 if(count($anc_list)>0){
 foreach($anc_list as $anc)
 {
 ?>    
<tr><td><?php echo $anc['title'];?></td>
<td width="60%"><?php echo $anc['msg'];?></td>
<td><?php echo $anc['region'];?></td>
 <td><a href="./index.php?edit=<?php 
echo $anc['anc_id'];?>"><img src="../../../content/images/edit.png" width="30px" height="30px" style="cursor:hand;"/></a></td>
 <td><a href="./index.php?del=<?php 
echo $anc['anc_id'];?>"><img src="../../../content/images/close.png" width="30px" height="30px" style="cursor:hand;"/></a></td></tr>
<?php $td_num+=1; }
echo '<tr><td colspan="6">'.$p->show().'</td></tr>';
 }
 else echo '<tr><td colspan="6">No announcements found...</td></tr>';
 ?>
</table>    
</div>   
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type="text/javascript" >

</script>          
<?php
include '../../trailer.php';
?>