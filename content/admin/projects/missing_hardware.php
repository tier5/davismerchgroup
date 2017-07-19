<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
$res=array();
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num   from missing_hardware'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$res = pg_fetch_array($result);

pg_free_result($result);

}

$query = ("SELECT ch_id, chain from tbl_chain order by chain asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$store=array();
while ($row = pg_fetch_array($result)) {
    $store[] = $row;
}

pg_free_result($result);

$query  = ("SELECT \"employeeID\", firstname, lastname FROM \"employeeDB\" where active='yes' and (emp_type = 0 OR emp_type is null)  ORDER BY firstname ASC;");
//echo $query;
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


$form_type="missing_hardware";
$proj_image=$res['proj_image'];
?>
<script type="text/javascript">
var str_stat='<?php if(!isset($res['store_name'])) echo 'yes'; ?>';     
 </script> 
<div id="demoWrapper">
<h3>Missing Hardware Survey</h3>	
<form id="sign_off_form" method="post" action="./sign_off_submit1.php" class="bbq">
 <div id="fieldWrapper">  
<input type="hidden" name="form_type" value="missing_hardware" />  
  <input type="hidden" name="form_id" value="<?php if(isset($res['r_id'])&&trim($res['r_id'])!='') echo $res['r_id'];?>" />  
  <input type="hidden" name="pid" value="<?php echo $pid;?>" /> 
  <div id="form">
      <span class="step" id="first">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="store_num" id="textfield12" value="<?php echo $res['store_num'];?>"/></td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="district" id="textfield8" value="<?php echo $res['district'];?>" /></td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="date" id="textfield" value="<?php echo $res['date'];?>" /></td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">1.       IQF Baskets: </td>
      <td width="10">&nbsp;</td>
      <td><input name="iqf_basket" type="text" id="textfield19" size="5" maxlength="5" value="<?php echo $res['iqf_basket'];?>"/></td>
      <td width="10">&nbsp;</td>
      <td>2.       POS Natural Signs:</td>
      <td width="10">&nbsp;</td>
      <td><input name="pos_nat_sign" type="text" id="textfield15" size="5" maxlength="5" value="<?php echo $res['iqf_basket'];?>" /></td>
    </tr>
    <tr>
      <td height="30">3.       SG Trays: </td>
      <td>&nbsp;</td>
      <td><input name="sg_tray" type="text" id="textfield20" size="5" maxlength="5" value="<?php echo $res['sg_tray'];?>" /></td>
      <td>&nbsp;</td>
      <td>4.       Well Dividers:</td>
      <td>&nbsp;</td>
      <td><input name="well_div" type="text" id="textfield16" size="5" maxlength="5" value="<?php echo $res['well_div'];?>"/></td>
    </tr>
    <tr>
      <td height="30">5.       White Freezer Shelves: </td>
      <td>&nbsp;</td>
      <td><input name="white_freez" type="text" id="textfield21" size="5" maxlength="5" value="<?php echo $res['white_freez'];?>"/></td>
      <td>&nbsp;</td>
      <td>6.       Black Freezer Shelves:</td>
      <td>&nbsp;</td>
      <td><input name="black_freez" type="text" id="textfield17" size="5" maxlength="5" value="<?php echo $res['black_freez'];?>"/></td>
    </tr>
    <tr>
      <td height="30">7.       White Meat Case Shelves: </td>
      <td>&nbsp;</td>
      <td><input name="white_meat" type="text" id="textfield22" size="5" maxlength="5" value="<?php echo $res['white_meat'];?>"/></td>
      <td>&nbsp;</td>
      <td>8.       Black Meat Case Shelves: </td>
      <td>&nbsp;</td>
      <td><input name="black_meat" type="text" id="textfield18" size="5" maxlength="5" value="<?php echo $res['black_meat'];?>"/></td>
    </tr>
    <tr>
      <td height="30">9.       DCI Trays: </td>
      <td>&nbsp;</td>
      <td><input name="dci_tray" type="text" id="textfield2" size="5" maxlength="5" value="<?php echo $res['dci_tray'];?>"/></td>
      <td>&nbsp;</td>
      <td>10.   DCI Tray Size:</td>
      <td>&nbsp;</td>
      <td><input name="dci_tray_size" type="text" id="textfield3" size="5" maxlength="5" value="<?php echo $res['dci_tray_size'];?>"/></td>
    </tr>
    <tr>
      <td height="30">11.   Fencing:</td>
      <td>&nbsp;</td>
      <td><input name="fencing" type="text" id="textfield4" size="5" maxlength="5" value="<?php echo $res['fencing'];?>"/></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>

  </table>          
        </span> 
          
  <tr>
      <td width="20%">&nbsp;</td>
      <td width="60%" ><div  align="left" id="img_cnt_signoff">
      <?php 
         // echo "ff".$temp.'gbg';

 
  
   require 'signoff_view_imgs.php';
    echo $html;
      ?>        
          </div>   
      </td>  
      <td>
  &nbsp;
      </td> 
  </tr>    
</table>           
        </span>       
  
  </div>
<div id="demoNavigation"> 							
	<input class="navigation_button" id="back" value="Back" type="reset" />
	<input class="navigation_button" id="next" value="Next" type="submit" />
        <input class="navigation_button" id="Reset" value="Reset" type="button" onclick="javascript:resetSignOffForm();"/>
        <input class="navigation_button" id="sign_off_pdf_btn" value="Export to PDF" type="button" onclick="javascript:exportPDF('<?php echo $form_type;?>');"/>
</div> 
</div> 
</form>
  </div>                        
<!--</body>
</html>-->

  
  