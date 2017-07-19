<?php
require 'Application.php';

if(!isset($_REQUEST['pid'])||$_REQUEST['pid']=='')
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


$query  = 'select * from out_of_stock where pid='.$_REQUEST['pid'].' and type=\'low\' order by stock_id asc';
//echo $query;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result))
{
    $stock[] = $row;
}
pg_free_result($result);

?>
<div id="stock_cnts">
<?php
for($i=0;$i<count($stock);$i++)
{
 //echo "jj-";
    
   $stock_id=$stock[$i]['stock_id'];   
   $rc=$i;
   $stock[$i]['bar_code']=unserialize(str_replace("~~NULL_BYTE~~", "\0", $stock[$i]['bar_code']));
   //print_r($stock[$i]['bar_code']);
?>
<form id="stockform_<?php echo $i; ?>">
<table width="90%"  id="stock_<?php echo $i; ?>">
    <tr><td height="5%">&nbsp;</td></tr>
    <tr><td align="left" width="25%">Client   : <input type="hidden"  id="stock_id_<?php echo $i;?>" value="<?php echo $stock[$i]['stock_id'];?>" />
        <select align="left" name="cid[<?php echo $i;?>]" class="stock_cl_list" style="width: 200px;" 
                onchange="javascript:saveBarcode(<?php echo $i;?>);" id="stock_cid_<?php echo $i;?>">
           <?php for ($j = 0; $j < count($client); $j++)
                               {
                                   
     echo '<option value="' . $client[$j]['ID'] . '" ';
      if($client[$j]['ID']==$stock[$i]['client_id']) echo ' selected="selected"';
     echo '>' . $client[$j]['client'] . '</option>';
                                    
                               } 
            ?>
            </select>     
        </td><td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    
    <tr><td align="left">Barcode:
          <input type="text"  name="barcode_<?php echo $i;?>" id="barcode_<?php echo $i;?>"/>
         <input type="button" value="Add" onclick='javascript:addBarcode(<?php echo $i;?>);' />
        
        </td><td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    
    <?php
    for($l=0;$l<count($stock[$i]['bar_code']);$l++)
    {
   echo '<tr id="barcode_'.$i.'_'.$l.'"><td><input type="text" class="barcode_val'.$i
           .'" value="'.$stock[$i]['bar_code'][$l].'" name="barcode[]" onchange="javascript:saveBarcode('.$i.');"/>';
  if($_SESSION['emp_type']==0) { 
echo '<img width="20px" height="20px" src="'.$mydirectory.'/images/delete.png" onclick="javascript:delbarcode(\''.$i.'_'.$l.'\','.$i.',$(this));"/> ';
  }
echo '</td></tr>';     
    }
       ?>
    
    <tr><td colspan="2">
     <textarea id="note" name="note"><?php echo $stock[$i]['notes'];?></textarea>      
        </td></tr>   
    
  <tr>
      <td width="60%" ><div  align="left" id="barcode_cnt_<?php echo $i;?>">
      <?php 
         // echo "ff".$temp.'gbg';

 
  
   // require 'glry_view_imgs.php';
  //  echo $html;
      ?>        
          </div>   
      </td>  
      <td>
      <?php  if($_SESSION['emp_type']==0) { ?>    
       <img src="<?php echo $mydirectory.'/images/drop.png'?>" onclick="javascript:deleteStock('<?php echo $i;?>','<?php echo $stock_id;?>');"/>    
       <?php } else echo'&nbsp;';?>
      </td> 
  </tr>    
</table>
</form>    


<?php
} ?>
</div>
<input type="hidden" id="stock_row_count" value="<?php if((count($stock))>0) echo (count($stock)); else echo '0'; ?>"/>

 <?php  if($_SESSION['emp_type']==0) { ?>  
<table width="90%" >
    <tr><td align="right">Client: </td>
        <td><select align="left" id="stock_cid_addnew" style="width: 200px;" >
           <?php for ($j = 0; $j < count($client); $j++)
                               {
                                   
     echo '<option value="' . $client[$j]['ID'] . '" ';
     echo'>' . $client[$j]['client'] . '</option>';
                                    
                               } 
            ?>
            </select> 
         <img src="<?php echo $mydirectory.'/images/add.png'?>" onclick="javascript:addStock();"/>   
        </td>
    </tr>
</table>
<?php } ?>