<?php
require 'Application.php';

if(!isset($_GET['pid'])||$_GET['pid']=='')
{
echo '<br/><h3>Please save the project first...</h3>';
exit();
}
$row_count=0;
$query  = ("SELECT \"ID\", \"clientID\", \"client\", \"active\" " .
        "FROM \"clientDB\" " .
        "WHERE \"active\" = 'yes' $client_sql " .
        "ORDER BY \"client\" ASC");
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


$query  = 'select * from img_glry_main where pid='.$_GET['pid'];
//echo $query;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result))
{
    $glry[] = $row;
}
pg_free_result($result);

?>
<div id="glry_cnts">
<?php
for($i=0;$i<count($glry);$i++)
{
 //echo "jj-";
    
   $glry_id=$glry[$i]['glry_id'];   
   $rc=$i;
?>

<table width="90%" align="left" id="glry_<?php echo $i; ?>">
    <tr><td height="5%">&nbsp;</td></tr>
    <tr><td align="left" width="25%">Client   : <input type="hidden"  id="glry_id_<?php echo $i;?>" value="<?php echo $glry[$i]['glry_id'];?>" />
        <select align="left" name="cid[<?php echo $i;?>]" class="cl_list" style="width: 200px;" 
                onchange="javascript:updateGlrClient('<?php echo $glry_id;?>',$(this));">
           <?php for ($j = 0; $j < count($client); $j++)
                               {
                                   
     echo '<option value="' . $client[$j]['ID'] . '" ';
      if($client[$j]['ID']==$glry[$i]['client_id']) echo ' selected="selected"';
     echo '>' . $client[$j]['client'] . '</option>';
                                    
                               } 
            ?>
            </select>     
        </td><td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    
    <tr><td align="left">Images:
         
  <input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="img_<?php echo $i;?>" id="img_<?php echo $i;?>" 
         onchange="javascript:imgGlryFileUpload('img_<?php echo $i;?>','I','<?php echo $i;?>', 960,720);" />          
        </td><td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    
  <tr>
      <td width="20%">&nbsp;</td>
      <td width="60%" ><div  align="left" id="img_cnt_<?php echo $i;?>">
      <?php 
         // echo "ff".$temp.'gbg';

 
  
    require 'glry_view_imgs.php';
    echo $html;
      ?>        
          </div>   
      </td>  
      <td>
       <img src="<?php echo $mydirectory.'/images/drop.png'?>" onclick="javascript:deleteGallery('<?php echo $i;?>','<?php echo $glry_id;?>');"/>    
      </td> 
  </tr>    
</table>
    


<?php
} ?>
</div>
<input type="hidden" id="row_count" value="<?php if((count($glry)-1)>0) echo (count($glry)-1); else echo '0'; ?>"/>


<table width="90%" align="left">
    <tr><td align="right">Client: </td>
        <td><select align="left" id="cid_addnew" style="width: 200px;" >
           <?php for ($j = 0; $j < count($client); $j++)
                               {
                                   
     echo '<option value="' . $client[$j]['ID'] . '" ';
     echo'>' . $client[$j]['client'] . '</option>';
                                    
                               } 
            ?>
            </select> 
         <img src="<?php echo $mydirectory.'/images/add.png'?>" onclick="javascript:addGallery();"/>   
        </td>
    </tr>
</table>