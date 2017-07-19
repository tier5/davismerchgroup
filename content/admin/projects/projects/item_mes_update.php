<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
$item_mes=array();
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num   from item_mes_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$item_mes = pg_fetch_array($result);

pg_free_result($result);

$query = ("SELECT * from item_mes_form_item order by ss_it_id asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$it_m_item=array();
while ($row = pg_fetch_array($result)) {
    $it_m_item[] = $row;
}


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


$form_type="item_mes";
$proj_image=$item_mes['proj_image'];
?>
<script type="text/javascript">
var str_stat='<?php if(!isset($item_mes['store_name'])) echo 'yes'; ?>';     
 </script> 
<div id="demoWrapper">
<h3>Item Measurement Update Form</h3>	
<form id="sign_off_form" method="post" action="./sign_off_submit1.php" class="bbq">
 <div id="fieldWrapper">  
<input type="hidden" name="form_type" value="item_mes" />  
  <input type="hidden" name="form_id" value="<?php if(isset($item_mes['r_id'])&&trim($item_mes['r_id'])!='') echo $item_mes['r_id'];?>" />  
  <input type="hidden" name="pid" value="<?php echo $pid;?>" /> 
  <div id="form">
      <span class="step" id="first">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="store_num" id="textfield12" value="<?php echo $item_mes['store_num'];?>"/></td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="dist" id="textfield8" value="<?php echo $item_mes['dist'];?>"/></td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="date" id="textfield" value="<?php echo $item_mes['date'];?>"/></td>
    </tr>
  </table>
  <table id="grid-new" width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
      <td height="30" class="grey-bg">Schematic Version</td>
      <td class="grey-bg">UPC</td>
      <td class="grey-bg">Product Name</td>
      <td class="grey-bg">Height</td>
      <td class="grey-bg">Width</td>
      <td class="grey-bg">Depth</td>
      <td class="grey-bg">Shelf</td>
      <td class="grey-bg">&nbsp;</td>
      </tr>
    <?php if(count($it_m_item)>0){  
  foreach($it_m_item as $i=>$ssr) {?>  
    <tr>
      <td height="30" class="white-bg">
          <input type="hidden" name="hdn_ssid[<?php echo $i;?>]" value="<?php echo $ssr['ss_it_id'];?>"/>    
        <input name="schem_v[<?php echo $i;?>]" type="text" id="textfield2" size="5" value="<?php echo $ssr['schem_v'];?>"/>
      </td>
      <td class="white-bg">
        <input name="upc[<?php echo $i;?>]" type="text" id="textfield3" size="15" value="<?php echo $ssr['upc'];?>"/>
      </td>
      <td class="white-bg">
        <input name="prod_name[<?php echo $i;?>]" type="text" id="textfield4" size="50" value="<?php echo $ssr['prod_name'];?>"/>
      </td>
      <td class="white-bg"><input name="height[<?php echo $i;?>]" type="text" id="textfield5" size="5" value="<?php echo $ssr['height'];?>"/></td>
      <td class="white-bg"><input name="width[<?php echo $i;?>]" type="text" id="textfield6" size="5" value="<?php echo $ssr['width'];?>"/></td>
      <td class="white-bg"><input name="depth[<?php echo $i;?>]" type="text" id="textfield7" size="5" value="<?php echo $ssr['depth'];?>"/></td>
      <td class="white-bg"><input name="shelf[<?php echo $i;?>]" type="text" id="textfield9" size="5" maxlength="5" value="<?php echo $ssr['shelf'];?>"/></td>
      <td class="white-bg">
   <?php if($i==0){?>
          <img onclick="javascript:signoff_addNewitemM();" src="<?php echo $mydirectory;?>/images/add2.png" width="32" alt="add" />
          <?php }else{?>
  <img onclick="javascript:signoff_DeleteitemM(<?php echo $ssr['ss_it_id'];?>);" src="<?php echo $mydirectory;?>/images/delete.png" width="32" alt="add" />        
          <?php }?>       
      </td>
    </tr>     
      
    <?php }}else{?>     
    <tr>
      <td height="30" class="white-bg">
        <input name="schem_v[]" type="text" id="textfield2" size="5" />
      </td>
      <td class="white-bg">
        <input name="upc[]" type="text" id="textfield3" size="15" />
      </td>
      <td class="white-bg">
        <input name="prod_name[]" type="text" id="textfield4" size="50" />
      </td>
      <td class="white-bg"><input name="height[]" type="text" id="textfield5" size="5" /></td>
      <td class="white-bg"><input name="width[]" type="text" id="textfield6" size="5" /></td>
      <td class="white-bg"><input name="depth[]" type="text" id="textfield7" size="5" /></td>
      <td class="white-bg"><input name="shelf[]" type="text" id="textfield9" size="5" maxlength="5" /></td>
      <td class="white-bg"><img onclick="javascript:signoff_addNewitemM();" src="<?php echo $mydirectory;?>/images/add2.png" width="32" alt="add" /></td>

    </tr>
    <?php }?>
    <tr>
      <td height="30">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
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

  
  