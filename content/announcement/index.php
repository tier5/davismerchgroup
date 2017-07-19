<?php
require 'Application.php';
include '../header.php';




$query='select * from tbl_region  order by region asc';
$result=pg_query($query);
$reg=array();
while($row=pg_fetch_array($result))
{
 $reg[]=$row;   
}
pg_free_result($result);
unset($row);
include('../pagination.class.php');
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
 $sql='select ans.*,reg.region from announcement as ans left join tbl_region as reg on ans.region=reg.rid where ans.anc_id='.$_GET['edit'].' limit 1';    
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
    $p->limit(4); // Limit entries per page
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
<style type="text/css">
.content{
	width:970px;
	border:1px solid #999;
        text-align: left;
        padding:10px;
	}
.twocolumn{
	width:480px;
	float:left;
	
}
.announce
{
    line-height: 20px;
    padding-left: 10px;
}

</style>

<br/><br>  
<div align="center"><h2>Announcements</h2></div>


<table style="border:1px solid #000;" width="990px" cellspacing="1" cellpadding="1" border="0px">
<tr>
<td class="grid001" >Title</td>
</tr> 
 <?php
 if(count($anc_list)>0){
 foreach($anc_list as $anc)
 {?>
<tr  >
<td class="announce" style="font-weight:bold;"><a href="index.php?edit=<?php echo $anc['anc_id'];?>"><?php echo $anc['title'];?></a></td>
</tr>
<?php $td_num+=1; } ?>
<tr>
<td><div class="pagination"><?php echo $p->show(); ?></div></td>
</tr>
<?php }
 else echo '<tr><td>No announcements found...</td></tr>';
 ?>

</table>

<br /><br />
<div class="content" >
<div class="twocolumn" ><strong>Title: </strong>
<span id="title"><?php echo $ancmnt['title']; ?></span>
</div>
<div class="twocolumn"><strong>Region: </strong>
<span id="region" ><?php echo $ancmnt['region'];?></span>
</div><br/>
<br/>
<div class="fullwidth">
<strong>Description:</strong><br/>
<div id="desc">
<?php echo $ancmnt['msg']; ?>
  </div>
</div>
</div>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type="text/javascript" >

</script>          
<?php
include '../trailer.php';
?>