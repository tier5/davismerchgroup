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
    $query  ='select d.*,ch.sto_num   from ssr_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$ssr_form = pg_fetch_array($result);

pg_free_result($result);

}

$query = ("SELECT * from ssr_form_item order by ss_it_id asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$ssr_item=array();
while ($row = pg_fetch_array($result)) {
    $ssr_item[] = $row;
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


$form_type="ssr_form";
$proj_image=$res['proj_image'];
?>
<script type="text/javascript">
var str_stat='<?php if(!isset($res['store_name'])) echo 'yes'; ?>';     
 </script> 
<div id="demoWrapper">
<h3>SSR Form</h3>	
<form id="sign_off_form" method="post" action="./sign_off_submit1.php" class="bbq">
 <div id="fieldWrapper">  
<input type="hidden" name="form_type" value="ssr_form" />  
  <input type="hidden" name="form_id" value="<?php if(isset($res['r_id'])&&trim($res['r_id'])!='') echo $res['r_id'];?>" />  
  <input type="hidden" name="pid" value="<?php echo $pid;?>" /> 
  <div id="form">
      <span class="step" id="first">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="store_num" id="textfield12" value="<?php echo $ssr_form['store_num'];?>"/></td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="dist" id="textfield8" value="<?php echo $ssr_form['dist'];?>"/></td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="date" id="textfield" value="<?php echo $ssr_form['date'];?>"/></td>
    </tr>
  </table>
  <table id="grid-new" width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
      <td height="30" class="grey-bg">STORE</td>
      <td class="grey-bg">Commodity Code</td>
      <td class="grey-bg">Kroger Category Update</td>
      <td class="grey-bg">&nbsp;</td>
      </tr>
 <?php if(count($ssr_item)>0){  
  foreach($ssr_item as $i=>$ssr) {?>   
      <tr>
      <td height="30" class="white-bg">
        <input type="hidden" name="hdn_ssid[<?php echo $i;?>]" value="<?php echo $ssr['ss_it_id'];?>"/>  
        <input name="store[<?php echo $i;?>]" type="text" id="textfield2" size="8" maxlength="8" value="<?php echo $ssr['store'];?>" />
      </td>
      <td class="white-bg">
        <input name="comm_code[<?php echo $i;?>]" type="test" id="textfield3" size="10" maxlength="10" value="<?php echo $ssr['comm_code'];?>"/>
      </td>
      <td class="white-bg">
        <input name="krog_cat[<?php echo $i;?>]" type="text" id="textfield4" size="100" maxlength="100" value="<?php echo $ssr['krog_cat'];?>"/>
      </td>
      <td class="white-bg">
          <?php if($i==0){?>
          <img onclick="javascript:signoff_addNewStore();" src="<?php echo $mydirectory;?>/images/add2.png" width="32" alt="add" />
          <?php }else{?>
  <img onclick="javascript:signoff_DeleteStore(<?php echo $ssr['ss_it_id'];?>);" src="<?php echo $mydirectory;?>/images/delete.png" width="32" alt="add" />        
          <?php }?>
      </td>
    </tr>    
  <?php }}else{?> 
    <tr>
      <td height="30" class="white-bg">
        <input name="store[]" type="text" id="textfield2" size="8" maxlength="8" />
      </td>
      <td class="white-bg">
        <input name="comm_code[]" type="test" id="textfield3" size="10" maxlength="10" />
      </td>
      <td class="white-bg">
        <input name="krog_cat[]" type="text" id="textfield4" size="100" maxlength="100" />
      </td>
      <td class="white-bg"><img onclick="javascript:signoff_addNewStore();" src="<?php echo $mydirectory;?>/images/add2.png" width="32" alt="add" /></td>
    </tr>
<?php }?>
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

  
  