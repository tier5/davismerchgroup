<?php 
require 'Application.php';
include $mydirectory.'/header.php';
$sql1 = 'Select ch_id, chain from "tbl_chain" ORDER BY chain ASC ';
if (!($result = pg_query($connection, $sql1))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row1= pg_fetch_array($result)){
	$data_chain[]=$row1;
}

/*$sql1 = 'Select * from "projects" ORDER BY pid ASC ';
if (!($result = pg_query($connection, $sql1))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row1= pg_fetch_array($result)){
	$data_proj[]=$row1;
}*/

$sql ='SELECT distinct ( main.name ), main.m_pid as pid from projects  as prj  left join project_main as main on main.m_pid=prj.m_pid ';

//echo $sql;

if (!($result = pg_query($connection, $sql)))
{
    print("Failed query1: " . pg_last_error($connection));
    exit;
}
while ($row2 = pg_fetch_array($result))
{
    $data_proj[] = $row2;
}

$sql4 = 'Select * from "tbl_chainmanagement" ORDER BY sto_num ASC ';
if (!($result = pg_query($connection, $sql4))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row4 = pg_fetch_array($result)){
	$data_store[]=$row4;
}
pg_free_result($result);



$query  = ("SELECT \"employeeID\", firstname, lastname FROM \"employeeDB\" where active='yes' and (emp_type = 0 OR emp_type is null)  ORDER BY firstname ASC;");
if (!($result = pg_query($connection, $query)))
{
    print("Failed employee query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result))
{
    $employee[] = $row;
}
pg_free_result($result); 

$query  = ("SELECT \"ID\", \"clientID\", \"client\", \"active\" " .
        "FROM \"clientDB\" " .
        "WHERE \"active\" = 'yes'  " .
        "ORDER BY \"client\" ASC");
//echo $query;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result))
{
    $client[] = $row;
}
pg_free_result($result);
include('../../pagination.class.php');

$join_type='left join';
$where = " and  prj.status =1";  //$client_sql";
//if ($query) $where .= " AND $qtype LIKE '%".  pg_escape_string($query)."%' ";
$cl_flag=0;

if(isset($_REQUEST['date_from'])&&$_REQUEST['date_from']!="")
{

     $date_arr = explode('/',$_REQUEST['date_from']);
    $from_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);

 $where.=' AND mer."due_date">='.$from_date;   
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['date_to'])&&$_REQUEST['date_to']!="")
{
      $date_arr = explode('/',$_REQUEST['date_to']);
    $to_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);
 $where.=' AND mer."due_date"<='.$to_date;  
 $join_type='inner join';
 $cl_flag=1;
}

$where_main="";
if(isset($_GET['project'])&&$_GET['project']!="")
{
      
 $where_main=" where main.m_pid =".$_GET['project']."";   
 //$join_type='inner join';
 $cl_flag=1;
}
if(isset($_GET['jobid_num'])&&$_GET['jobid_num']!="")
{
  if($where_main=='') $where_main=" where";
  else $where_main.=" and ";
 $where_main.="  prj.prj_name like '%".$_GET['jobid_num']."%'";   
 //$join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['st_name'])&&$_REQUEST['st_name']!="")
{
      
 $where.=" AND ch.ch_id =".$_REQUEST['st_name']."";   
 $join_type='inner join';
 $cl_flag=1;
}



if(isset($_REQUEST['sto_num'])&&$_REQUEST['sto_num']!="")
{
      
 $where.=" AND st.sto_num like '%".$_REQUEST['sto_num']."%' ";  
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['merch'])&&$_REQUEST['merch']!="")
{
      
 $where.=" AND mer.merch =".$_REQUEST['merch']."";  
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['client'])&&$_REQUEST['client']!="")
{
      
 $where.=" AND mer.cid =".$_REQUEST['client']."";  
 $join_type='inner join';
 $cl_flag=1;
}


$sql ='SELECT distinct ( prj.prj_name ),main.name, prj.pid, chain.chain,m1.firstname, m1.lastname, reg.region, st.sto_num, m.st_time,m.due_date 
from projects  as prj  
 '.$join_type.' prj_merchants_new as m on m.m_id = (select mer.m_id from prj_merchants_new as mer left join tbl_region as reg on reg.rid=mer.region '
.' left join tbl_chain as ch on ch.ch_id=mer.location left join tbl_chainmanagement as st on st.chain_id = mer.store_num '
.'where mer.pid = prj.pid '.$where.' limit 1 )
left join "employeeDB" as m1 on m1."employeeID"= m.merch left join tbl_chainmanagement as st on st.chain_id = m.store_num  '.
   ' left join project_main as main on main.m_pid=prj.m_pid '.
 '  left join tbl_chain as chain on chain.ch_id=m.location  left join tbl_region as reg on reg.rid=m1.region '.$where_main ;




if (!($resultp = pg_query($connection, $sql))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$items = pg_num_rows($resultp);
if ($items > 0) {
    $p = new pagination;
    $p->items($items);
    $p->limit(15); // Limit entries per page
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
$sql = $sql . " " . $limit;

//echo $sql;

if (!($resultp = pg_query($connection, $sql))) {
    print("Failed queryd: " . pg_last_error($connection));
    exit;
}
while ($rowd = pg_fetch_array($resultp)) {
    $datalist[] = $rowd;
}

?>
<div id="message" class="message_fixed"></div>
<div id="loading" class="message_fixed"><img src="<?php echo $mydirectory; ?>/images/loading.gif" alt="Loading..." /></div>
<form id="srch_frm" action="export_signoff.php" method="post" target="_blank">
<table width="100%" cellspacing="1" cellpadding="1" border="0">
  <tr><td colspan="4"><h2>Sign Off Reports</h2></td></tr>  

 <tr><td colspan="4"><font size="3">Search Projects</font></td></tr>
      <tr class="grid001">
     <td style="color:white;" >From Date:</td>
     <td><input class="srch_field" size="15px" type="text" name="date_from" id="date_from"  value="<?php 
     if(isset($_GET['date_from'])&&$_GET['date_from']!="")
         echo $_GET['date_from'];
     ?>" /></td><td>&nbsp;</td>    
     <td style="color:white;">To Date:</td>
     <td><input class="srch_field" size="15px" type="text" name="date_to" id="date_to"  value="<?php 
     if(isset($_GET['date_to'])&&$_GET['date_from']!="")
         echo $_GET['date_to'];
     ?>" /></td>
 </tr>
 <tr class="grid001">
  <td style="color:white;">Client:</td>    
  <td>
  <select class="srch_field" name="client" id="client">		
      <option value="" selected>--------Select--------</option>
    <?php  for ($i = 0; $i < count($client); $i++) {
    			echo '<option value="'.$client[$i]['ID'].'" ';
                        if($client[$i]['ID']==$_GET['client']) echo ' selected';
    				echo '>' . $client[$i]['client'] . '</option>';
				}   ?> </select>     
  </td><td>&nbsp;</td>
 <td style="color:white;">Project Name:</td>
 <td><select class="srch_field" name="project" id="project">		
         <option value="" selected>--------Select--------</option>
    <?php  for ($i = 0; $i < count($data_proj); $i++) {
        if($data_proj[$i]['pid']=='') continue;
    			echo '<option value="'.$data_proj[$i]['pid'].'" ';
                   if($data_proj[$i]['pid']==$_GET['project']) echo ' selected';     
    				echo '>' . $data_proj[$i]['name'] . '</option>';
				}   ?> </select>  
        </td>
 
 

             

            </tr>
      
            <tr class="grid001" >
            <td style="color:white;">Store Name:</td>
 <td><select class="srch_field" name="st_name" id="st_name" onchange="javascript:get_store();"> 
 <option value="" selected>--------Select--------</option>                   
                    <?php
			for ($i = 0; $i < count($data_chain); $i++) {
    			echo '<option value="'.$data_chain[$i]['ch_id'].'" ';
                          if($data_chain[$i]['ch_id']==$_GET['st_name']) echo ' selected';  
    				echo '>' . $data_chain[$i]['chain'] . '</option>';
				}
		?>
                </select>
            </td><td>&nbsp;</td>     
            <td style="color:white;">Merchandiser:</td>
            <td><select class="srch_field" name="merch" id="merch">
            <option value="" selected>All Merchandisers</option>
            <?php
                                for ($i = 0; $i < count($employee); $i++)
                                {
                                   
                   echo '<option value="' . $employee[$i]['employeeID'] . '"';
                       
                   if($employee[$i]['employeeID']==$_GET['merch']) echo ' selected'; 
                  echo '>' . $employee[$i]['firstname'] . ' ' . $employee[$i]['lastname'] . '</option>';
                                   
                                }
								
								
                                ?>
            </select>
            </td>
           
         
            </tr> 
            <tr class="grid001">
                     <td style="color:white;">Store Number:</td>
     <td><input type="text"  class="srch_field" name="sto_num" id="sto_num" value="<?php echo $_GET['sto_num'];?>">

     </td>
     <td>&nbsp;</td>
              <td style="color:white;">Job ID#:</td>
     <td><input type="text"  class="srch_field" name="jobid_num"  value="<?php echo $_GET['jobid_num'];?>">

     </td>
            </tr>
      <tr><td colspan="3">&nbsp;</td></tr>      
    <tr>
      <td colspan="5" align="center"><input  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" type="button" value="Generate spreadsheet" onclick="expReport();"/>&nbsp;<input  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" onclick="list_report();" type="button" value="Search"/>
     <input  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" onclick="cancel_search();" type="button" value="Cancel"/> 
      </td>

  </tr>
  
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr><td colspan="2">&nbsp;</td></tr>

</table>
</form>

              <table width="100%" border="0" cellspacing="1" cellpadding="1">
                        <tr>
                              <td class="grid001">Project Name</td> 
                        	<td class="grid001">Job ID#</td> 
                            <td class="grid001">Start Date</td> 
                            <td class="grid001">Start Time</td>
                        	 <td width="5%" class="grid001">Store Name</td> 
                                <td width="8%" class="grid001">Store Number</td> 
                                <td width="8%" class="grid001">Merchandiser</td> 
                                <td width="8%" class="grid001">Region</td> 
                            
                          
                            </tr>

        <?php
			  if(count($datalist) > 0)
			  {
				  for($i = 0; $i < count($datalist); $i++)
				  {
			  ?>
                                <tr>
                                    <td class="gridVal"><?php echo $datalist[$i]['name'];?></td>
                                    <td class="gridVal"><?php echo $datalist[$i]['prj_name'];?></td>
                                   <td class="gridVal"><?php if($datalist[$i]['due_date']>0)echo date('m/d/Y',$datalist[$i]['due_date']);?></td>
                                   <td class="gridVal"><?php echo $datalist[$i]['st_time'];?></td>
                               <td class="gridVal"><?php echo $datalist[$i]['chain'];?></td>  
                                 <td class="gridVal"><?php echo $datalist[$i]['sto_num'];?></td>
                                   <td class="gridVal"><?php echo $datalist[$i]['firstname'].' '.$datalist[$i]['lastname'];?></td>
                                   <td class="gridVal"><?php echo $datalist[$i]['region'];?></td>
      
                                </tr>              
        <?php
				  }
				  echo 	'<tr>
			<tr><td width="100%" class="grid001" colspan="13">' . $p->show() . '</td></tr>			
		  </tr>';
			  }
			  
			  else
			  {
				  echo '<tr><td colspan="7" class="gridVal">No Store found</td><tr>';
			  }
			 ?>       
                        <tr>
                          <td colspan="5">&nbsp;</td>
                        </tr>
                    </table>




<link rel='stylesheet' type='text/css' href='<?php echo $mydirectory; ?>/css/jquery-ui-1.8.19.custom.css' />
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script>  

<script type='text/javascript' >
$('document').ready(function(){
 $("#date_from").datepicker();
 $("#date_to").datepicker();
});
var loading = $("#loading");
var msg = $("#message");
window.message_display = null;
function show_msg(cl,ms)
{
    //alert('k');
    msg.addClass(cl).html(ms).fadeIn();
    window.message_display = setInterval(function() {msg.fadeOut(1600,remove_msg);}, 6000);
}
function remove_msg()
{
    msg.removeClass('success').removeClass('error').html('');
    clearInterval(window.message_display);window.message_display = null;
}

  function showLoading(){loading .css({visibility:"visible"}) .css({opacity:"1"}) .css({display:"block"});msg .css({visibility:"hidden"})}
//hide loading bar
function hideLoading(){loading.fadeTo(1000, 0, function(){loading .css({display:"none"});msg .css({visibility:"visible"});});};
   function expReport()
{
$('#srch_frm').submit();
}  

function list_report()
{
 var data=$('#srch_frm').serialize();
 location.href='<?php echo $mydirectory;?>/admin/signoff_report/index.php?'+data;
}

function cancel_search()
{
 location.href='<?php echo $mydirectory;?>/admin/signoff_report/index.php';
}

</script>  



