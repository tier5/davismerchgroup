<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
$summ=array();
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num   from reset_store_summary'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$summ = pg_fetch_array($result);

pg_free_result($result);
if(isset($summ['store_name'])&&$summ['store_name']!='')
{

$query = ("SELECT * from tbl_chainmanagement where sto_name=".$summ['store_name']);
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$store_num=array();
while ($row = pg_fetch_array($result)) {
    $store_num[] = $row;
}
pg_free_result($result);

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


$form_type="reset_store_summ";
$proj_image=$summ['proj_image'];
?>
<script type="text/javascript">
var str_stat='<?php if(!isset($summ['store_name'])) echo 'yes'; ?>';     
 </script> 
<div id="demoWrapper">
<h3>Reset Store Summery Report</h3>	
<form id="sign_off_form" method="post" action="./sign_off_submit1.php" class="bbq">
 <div id="fieldWrapper">  
<input type="hidden" name="form_type" value="reset_store_summ" />  
  <input type="hidden" name="form_id" value="<?php if(isset($summ['r_id'])&&trim($summ['r_id'])!='') echo $summ['r_id'];?>" />  
  <input type="hidden" name="pid" value="<?php echo $pid;?>" /> 
  <div id="form">
      <span class="step" id="first">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="store_num" id="textfield12" value="<?php echo $summ['store_num'];?>" /></td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="district" id="textfield8" value="<?php echo $summ['district'];?>"/></td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="date" id="textfield" value="<?php echo $summ['date'];?>"/></td>
    </tr>
  </table>
          <br/>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="350" height="30">1.Total Missing Item Count (Total Items Listed on Form): 




&nbsp;</td>
      <td width="10">&nbsp;</td>
      <td><input name="miss_count" type="text" id="textfield2" size="5" maxlength="5" value="<?php echo $summ['miss_count'];?>"/></td>
      </tr>
    <tr>
      <td height="30">2.       Total Missing New Item Count (Total Items Listed as New): </td>
      <td>&nbsp;</td>
      <td><input name="new_miss_count" type="text" id="textfield3" size="5" maxlength="5" value="<?php echo $summ['new_miss_count'];?>"/></td>
    </tr>
    <tr>
      <td height="30">3.       Total UA Item Count (Total Items Listed as UA): &nbsp;</td>
      <td>&nbsp;</td>
      <td><input name="ua_cnt" type="text" id="textfield4" size="5" maxlength="5" value="<?php echo $summ['ua_cnt'];?>"/></td>
    </tr>
    <tr>
      <td height="30">4.       DC’D Items in Set # (Total DC’D Items Found in Set):&nbsp;</td>
      <td>&nbsp;</td>
      <td><input name="dcd_item" type="text" id="textfield5" size="5" maxlength="5" value="<?php echo $summ['dcd_item'];?>"/></td>
    </tr>
    <tr>
      <td height="30">5.       New Items were staged (Did the Store pull the New Items and Put Them on a U Boat or Stage in a Separate Area): &nbsp;</td>
      <td>&nbsp;</td>
      <td><label>
        <input type="radio" name="stag_item" id="radio3" value="Y" <?php if($summ['stag_item']=='Y') echo 'checked' ;?>/>
      </label>
        Yes
  <input type="radio" name="stag_item" id="radio4" value="N" <?php if($summ['stag_item']=='N') echo 'checked' ;?>/>
        No</td>
      </tr>
    <tr>
      <td height="30">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  
    <tr>
      <td height="30">&nbsp;</td>
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

  
  