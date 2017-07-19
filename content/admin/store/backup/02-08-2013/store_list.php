<?php
require('Application.php');
require('../../header.php');
$paging = 'paging=';
$limit = "";
if (isset($_GET['paging']) && $_GET['paging'] != "") {
    $paging .= $_GET['paging'];
} else {
    $paging .= 1;
}

$_SESSION['page'] = $current_page;

if(isset($_GET['sid']))
{
	$sql="Delete from tbl_store where sid = ".$_GET['sid'];
	if(!($result=pg_query($connection,$sql)))
	{
		print("Failed delete_quote: " . pg_last_error($connection));
		exit;
	}
	
}

include('../../pagination.class.php');
$sql = 'SELECT str.*,chain.chain FROM "tbl_store" as str left join tbl_chain as chain on chain.ch_id=str.sto_name ORDER BY sid DESC';
//echo $sql;
if (!($resultp = pg_query($connection, $sql))) {
    print("Failed queryd: " . pg_last_error($connection));
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
if (!($resultp = pg_query($connection, $sql))) {
    print("Failed queryd: " . pg_last_error($connection));
    exit;
}
while ($rowd = pg_fetch_array($resultp)) {
    $datalist[] = $rowd;
}

echo "<font face=\"arial\">";
echo "<blockquote>";
echo "<center><font size=\"5\">Chain Management</font><br/><br/>";
echo "</blockquote>";
echo "</font>";
?>
<table width="100%"> 
    <tr>
        <td align="left" valign="top"><center>
        <table width="100%">
            <tr>
                <td align="center" valign="top"><font size="5"><br>
                    <table border="0" width="20%">
                        <tr>
                            <td align="center"><input type="button" onmouseover="this.style.cursor = 'pointer';" value="Add/Edit Chain Management" onclick="location.href='add_store.php';" /></td>
<!--<td align="center"><input type="button" value="Generate Spreadsheet" onmouseover="this.style.cursor = 'pointer';" onclick="javascript:spreadsheet();" style="cursor: pointer;"></td>-->   
                        </tr>
                    </table>
                    <br>
                    </font>
                    <table width="80%" border="0" cellspacing="1" cellpadding="1">
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td width="10">&nbsp;</td>
                            <td width="100">&nbsp;</td>
                            <td width="150">&nbsp;</td>
                        </tr>
                    </table>
                    <table width="70%" border="0" cellspacing="1" cellpadding="1">
                        <tr>
                              <td class="grid001">Chain Name</td> 
                        	<td class="grid001">Store#</td> 
                            <td class="grid001">Address</td> 
                            <td class="grid001">Phone</td>
                        	 <td width="5%" class="grid001">Edit</td> 
                                <td width="8%" class="grid001">Delete</td> 
                            
                          
                            </tr>

        <?php
			  if(count($datalist) > 0)
			  {
				  for($i = 0; $i < count($datalist); $i++)
				  {
			  ?>
                                <tr>
                                    <td class="gridVal"><?php echo $datalist[$i]['chain'];?></td>
                                    <td class="gridVal"><?php echo $datalist[$i]['sto_num'];?></td>
                                   <td class="gridVal"><?php echo $datalist[$i]['address'];?></td>
                                   <td class="gridVal"><?php echo $datalist[$i]['phone'];?></td>
                                    
                                    <td class="gridVal"><a href="add_store.php?sid=<?php echo $datalist[$i]['sid'];?>"><img src="<?php echo $mydirectory;?>/images/edit.png" width="24" height="24" alt="edit" /></a></td>
                                   <?php echo '<td align="center" class="gridVal"><a href="store_list.php?sid=' . $datalist[$i]['sid'] . '&' . $paging . '"><img src="' . $mydirectory . '/images/drop.png" width="28" height="28" alt="delete" /></a></td>'; ?>
                                     <!--<td align="center" class="gridVal"><a href="store_list.php?sid=<?php echo $datalist[$i]['sid'];?>"><img src="<?php echo $mydirectory;?>/images/drop.png" width="28" height="28" alt="delete" /></a></td>-->
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
                </td>
            </tr>
        </table>
        <p>
    </center></td>
</tr>
</table>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type="text/javascript">
function spreadsheet()
    {
        dataString ='';
	
                $.ajax({
                    type: "POST",
                    url: "spreadsheet.php",
                    data: dataString,
                    dataType: "json",
                    success:function(data)
                    {
                        if(data!=null)
                        {
                            if(data.name || data.error)
                            {
                                $("#message").html("<div class='errorMessage'><strong>Sorry, " + data.name + data.error +"</strong></div>");
                            } 
                            else
                            {	
                                $("#message").html("<div class='successMessage'><strong>Spread sheet generated successfully...</strong></div>");
                                location.href='download_sheet.php?file='+data.fileName;
                            }
                        }
                        else
                        {
                            $("#message").html("<div class='errorMessage'><strong>Sorry, Unable to process.Please try again later.</strong></div>");
                        }
				
                    }
                });
            }
			
			
			
			
</script>
<?php
require('../../trailer.php');
?>