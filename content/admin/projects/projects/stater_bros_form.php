<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
if(isset($form_id)&&$form_id!='')
{
    $query  ='select d.*,ch.sto_num from stater_bros_form'.$ext.'  as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num where stat_bros_id='.$form_id;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$stat = pg_fetch_array($result);

pg_free_result($result);

$proj_image=$stat['proj_image'];

if(isset($stat['store_name'])&&$stat['store_name']!='')
{
$query = ("SELECT * from tbl_chainmanagement where sto_name=".$stat['store_name']);
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

$query = ("SELECT * from grocery_cat_items where pid=".$pid." order by id asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$cats=array();
while ($row = pg_fetch_array($result)) {
    $cats[] = $row;
}

pg_free_result($result);



$cl_list=array();
$query="select cl.client from projects as prj left join project_main as main on main.m_pid=prj.m_pid".
 " left join prj_signoff_clients as cl on cl.pid=main.m_pid where prj.pid=".$pid;
$result=pg_query($connection, $query);
while ($row = pg_fetch_array($result))
{
    $cl_list[] = $row;
}
pg_free_result($result);
$client_sql='';
foreach($cl_list as $cl)
{
if($cl['client']>0){}else continue;    
if($client_sql!='') $client_sql.=' OR ';    
  $client_sql .= ' "ID"='.$cl['client'];  
}
$client_sql=' AND ('.$client_sql.') ';
$client_sql='';

if($is_client){
    $client_sql = ' AND "ID"='.$client_id;
}
$query  = ("SELECT \"ID\", \"clientID\", \"client\", \"active\" " .
        "FROM \"clientDB\" " .
        "WHERE \"active\" = 'yes' $client_sql " .
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
$form_type='staterbros';
$cat_array=array('Detergent','Candy','Jerky','Cookie','Cracker','Shampoo','Deodorant','Fabric Softener','Essentials','Bar Soap',
'Lunchables','Frozen Hand Held','Ice Cream','Produce','Dog Food','Cat Food','Oral Care','Shave','Cheese','Gift Cards',
'Energy','CSD','Chips','New Age','Hair Color','On the Go','Broom Rack','Batteries','Frozen Food','Incontinence'
 ,'Scunci','Vitamins','Toothpaste','Bleach','Chocolate Chips','Diapers','Battery Rack','K-Cups','Deli Pushers','Milk'
  ,'Orange Juice','Yogurt','Stationary','Check Stands','Cold Medicine Rack','Hispanic Cookie','Air Freshener','Little Debbie','Feminine Hygiene','Popcorn'
 ,'Spices','Bread','Shasta','Baby Food','Baby Formula','Bath Tissue','Paper Towel','Cake Mix','Canned Cat','Canned Dog'
  ,'Canned Nuts','Canned Soup','Energy Cold Doors','Dog Treats','Enhanced Water','Flavored Tea','Foot Care','Frozen Dinners','Gatorade','Granola Bars'
  ,'Hot Cereal','Light Bulb','Liquid Dish','Lotion','Ethnic Hair Care','MC Rice','MC Seafood','Market Centre','Mixers','Monster'
  ,'Nail Care','Peanut Butter & Jelly','Pill & Tab','Powder Drinks','Prepared Dinners','QFI Pusher','Rice & Beans','Sparkling Water','Swiffer','Tortilla Racks'
  ,'Water','Candy','Diet & Health','Frozen Pizza','Juice','Premium Soda','Other (Write In)*'   
    );
?>
<style>
td > input{ vertical-align:top;}
#form td { vertical-align:middle;}



</style>
<script type="text/javascript">
var str_stat='<?php if(!isset($stat['store_name'])) echo 'yes'; ?>';     
 </script> 
<div id="demoWrapper">
<h3>Grocery Store Form</h3>	
<form id="sign_off_form" method="post" action="./sign_off_submit1.php" class="bbq">
 <div id="fieldWrapper">   
<input type="hidden" name="form_type" value="stat_bros" />  
<input type="hidden" name="form_id" value="<?php if(isset($stat['stat_bros_id'])&&trim($stat['stat_bros_id'])!='') echo $stat['stat_bros_id'];?>" /> 
<input type="hidden" name="pid" value="<?php echo $pid;?>" /> 
<div id="form">
    <span class="step" id="first">
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
    <td colspan="4" align="center"></td>  
    <td width="149"><img alt="logo" src="<?php echo $mydirectory;?>/images/davis-wbg.png" /></td>
    </tr>
              <tr>
    <td colspan="5">&nbsp;</td>    
    </tr>
  <tr>
    <td align="left" valign="top" width="543"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr >
        <td width="100" height="30" align="left" valign="top">Store&nbsp;Name:</td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td width="416" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="20"><select class="required" name="store_name"  onchange="javascript:getstorenum(2);changeStoreValidation();" id="store_name_2">
                    <option value="" ></option>  
              <option value="0" <?php
					 if(isset($stat['store_name']) && $stat['store_name']==0)
					 echo ' selected="selected" ';
                     ?>>Other</option>
              <?php
			for ($i = 0; $i < count($store); $i++) {
    			echo '<option value="'.$store[$i]['ch_id'].'" ';
    				if (isset($stat['store_name']) && $stat['store_name'] == $store[$i]['ch_id'])
        			echo 'selected="selected" ';
    				echo '>' . $store[$i]['chain'] . '</option>';
				}
		?>
              </select></td>
            <td width="100">&nbsp;Store&nbsp;#: </td>
            <td><select class="required" name="store_num" id="store_num_2"   onchange="javascript:get_contact(2);">
              <?php
			for ($i = 0; $i < count($store_num); $i++) {
    			echo '<option value="'.$store_num[$i]['chain_id'].'" ';
    				if (isset($stat['store_num']) && $stat['store_num'] == $store_num[$i]['chain_id'])
        			echo 'selected="selected" ';
    				echo '>' . $store_num[$i]['sto_num'] . '</option>';
				}
		?>
            </select></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
        </table></td>
      </tr>
      <tr id="other_tr" <?php if($stat['store_name']!=0||$stat['store_name']=='') echo ' style="display:none;"'?>>
        <td>Other:</td><td>&nbsp;</td><td>
        <input type="text" id="other_fld" name="other" value="<?php echo $stat['other']; ?> " />
        </td></tr>
      <tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required" type="text" name="address" id="address_2" value="<?php echo $stat['address'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required" type="text" id="city_2" name="city" value="<?php echo $stat['city'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Client:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">
   <select name="cid" id="cid" style="width: 200px;" class="required" >
       <option value="" ></option> 
                                <?php
                                $c_index = -1;
                                if ($pid == 0)
                                    $c_index = 0;
                                for ($i = 0; $i < count($client); $i++)
                                {
                                   
                                        echo '<option value="' . $client[$i]['ID'] . '"';
                    if($client[$i]['ID'] ==$stat['cid'])
                    echo ' selected="selected" ';
                                        echo '>' . $client[$i]['client'] . '</option>';
                                    
                                }
                                ?>
                            </select>         
        </td>
      </tr>
    </table></td>
    <td width="19" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" width="346"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100" height="30" align="left" valign="top">Date: </td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><input  type="text" name="date" class="date_field" value="<?php if($stat['date']!='')echo $stat['date'];else echo date('m/d/Y');?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Work&nbsp;Type&nbsp;:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><label>
          <select class="required" name="work_type">
               <option value="" ></option>    
            <option <?php if($stat['work_type']=='Remodel') echo "selected";?>>Remodel</option>
            <option <?php if($stat['work_type']=='Reset') echo "selected";?>>Reset</option>
            <option <?php if($stat['work_type']=='New Store') echo "selected";?>>New Store</option>
            <option <?php if($stat['work_type']=='Survey') echo "selected";?>>Survey</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
    </table></td>
    <td width="99" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
</table>              
    </span>
    <span class="step" id="two">
          
        <table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>
        <td height="30" align="left" valign="top" width="8%">Blitz:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">
        <select name="category" id="category" onchange="change_cat_other();" class="required">
                   <option value="" ></option>    
            <?php for($i=1;$i<=count($cat_array);$i++) { if(trim($cat_array[$i])=='') continue;?>
            <option <?php if($stat['category']==$cat_array[$i]) echo "selected";?>><?php echo $cat_array[$i];?></option>
            <?php } ?>
          </select>&nbsp;<input type="text" name="cat_other" id="cat_other" value="<?php echo $stat['cat_other'];?>" style="<?php if(isset($stat['category'])&&$stat['category']=='Other (Write In)*'){}else{?>display:none;<?php }?>width:225px;" maxlength="50"/>        
        </td>

      </tr>
    </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
         
          <td width="139" height="34" align="left" valign="top">Repack in boxes :</td>
          <td width="45" align="left" valign="top"><select class="required" name="repack_box">
                   <option value="" ></option>    
                  <option>N/A</option>
                      <?php for($i=1;$i<=40;$i++) {?>
                      <option <?php if($stat['repack_box']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
          <td width="107" align="left" valign="top">&nbsp;</td>
          <td width="126" align="left" valign="top">Repack in carts :</td>
          <td width="500" align="left" valign="top"><select name="repack_in">
                   <option value="" ></option>    
                  <option>N/A</option>
            <?php for($i=1;$i<=40;$i++) {?>
            <option <?php if($stat['repack_in']==$i) echo "selected";?>><?php echo $i;?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
        
          <td height="30" align="left" valign="top">DC in boxes :</td>
          <td align="left" valign="top"><select class="required" name="dcin_box">
                   <option value="" ></option>    
                  <option>N/A</option>
                      <?php for($i=1;$i<=40;$i++) {?>
                      <option <?php if($stat['dcin_box']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
          <td align="left" valign="top">&nbsp;</td>
          <td align="left" valign="top">DC in carts :</td>
          <td align="left" valign="top"><select class="required" name="dcin">
                   <option value="" ></option>    
                  <option>N/A</option>
            <?php for($i=1;$i<=40;$i++) {?>
            <option <?php if($stat['dcin']==$i) echo "selected";?>><?php echo $i;?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
         
          <td height="33" align="left" valign="top">Out of code in boxes: </td>
          <td align="left" valign="top"><select class="required" name="out_code_box">
                   <option value="" ></option>    
                  <option>N/A</option>
                      <?php for($i=1;$i<=40;$i++) {?>
                      <option <?php if($stat['out_code_box']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
          <td align="left" valign="top">&nbsp;</td>
          <td align="left" valign="top">Out of code in carts: </td>
          <td align="left" valign="top"><select class="required" name="out_of_code">
                   <option value="" ></option>    
                  <option>N/A</option>
            <?php for($i=1;$i<=40;$i++) {?>
            <option <?php if($stat['out_of_code']==$i) echo "selected";?>><?php echo $i;?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td height="30" colspan="3" align="left" valign="top">Repack - Dc palatized in backroom : </td>
          <td align="left" valign="top" width="126">Yes
            <input class="required" checked="checked" name="repck_dc_back" onchange="showNoOptMsg('1','Y');" type="radio" value="Y" <?php if($stat['repck_dc_back']=='Y') echo 'checked' ;?>/>
No
<input class="required" name="repck_dc_back" onchange="showNoOptMsg('1','N');" type="radio" value="N" <?php if($stat['repck_dc_back']=='N') echo 'checked' ;?>/></td>
          <td align="left" valign="top"><div <?php if(!isset($stat['repck_dc_back'])||$stat['repck_dc_back']=='Y') echo 'style="display:none;"' ;?>  id="1"><?php echo $showNoOptMsg; ?></div></td>
          <td width="162" align="left" valign="top">&nbsp;</td>
          <td width="14" align="left" valign="top">&nbsp;</td>
          <td width="14" align="left" valign="top">&nbsp;</td>
          <td width="14" align="left" valign="top">&nbsp;</td>
          <td width="14" align="left" valign="top">&nbsp;</td>
          <td width="21" align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" colspan="11" align="left" valign="top">If no explain Why? </td>
          </tr>
        <tr>
          <td height="30" colspan="11" align="left" valign="top"><textarea name="exp_why" cols="50" rows="10">
<?php echo $stat['exp_why'];?>
              </textarea></td>
          </tr>
      </table>   
    </span>   
    <span class="step" id="three">
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
    
        <tr><td colspan="7"><table width="100%" id="cats_div">
        <tr>
             <td width="47" align="left" valign="top" >CAT :</td>
         
          <td width="151" align="left" valign="top"><input class="required" type="text" name="cat_1" value="<?php echo $stat['cat_1'];?>"/></td>
                <td width="53" align="left" valign="top">File ID# :</td>
          
          <td width="150" align="left" valign="top"><input class="required" type="text" name="file_id" value="<?php echo $stat['file_id'];?>"/></td>
          <td width="69" align="left" valign="top">&nbsp;Footage:&nbsp;  </td>
          <td align="left" valign="top" width="150"><input class="required" maxlength="3" type="text"  name="footage" value="<?php echo $stat['footage'];?>"/></td>
          <td width="6" align="left" valign="top">&nbsp;</td>
       <td width="123" height="30" align="left" valign="middle">Section Completed:</td>
          <td width="102" height="30" align="left" valign="top">
            Yes
            <input class="required" name="sec_comp" onchange="showNoOptMsg('1','Y');" type="radio" value="Y" <?php if($stat['sec_comp']=='Y') echo 'checked' ;?>/>
No
<input class="required" name="sec_comp" onchange="showNoOptMsg('1','N');" type="radio" value="N" <?php if($stat['sec_comp']=='N') echo 'checked' ;?>/></td>
          <td width="261" align="left" valign="top"><img onclick="javascript:signoff_addNewCat();" src="<?php echo $mydirectory;?>/images/add2.png" width="32" alt="add" /><div <?php if(!isset($stat['sec_comp'])||$stat['sec_comp']=='Y') echo 'style="display:none;"' ;?>  id="1"><?php echo $showNoOptMsg; ?></div></td>
        </tr>       	
      
  
         
   <?php $cat_cnt=2; foreach($cats as $cat){?>                   
        <tr>
             <td width="47" align="left" valign="top" >CAT :</td>
         
          <td width="151" align="left" valign="top"><input  type="text" name="cat[<?php echo $cat_cnt;?>][cat]" value="<?php echo $cat['cat'];?>"/></td>
                <td width="53" align="left" valign="top">File ID# :</td>
          
          <td width="150" align="left" valign="top"><input type="text" name="cat[<?php echo $cat_cnt;?>][file_id]" value="<?php echo $cat['file_id'];?>"/>
          <input type="hidden" name="cat[<?php echo $cat_cnt;?>][id]" value="<?php echo $cat['id'];?>"/>
          </td>
          <td width="69" align="left" valign="top">&nbsp;Footage:&nbsp;  </td>
          <td align="left" valign="top" width="150"><input  maxlength="3" type="text"  name="cat[<?php echo $cat_cnt;?>][footage]" value="<?php echo $cat['footage'];?>"/></td>
          <td width="6" align="left" valign="top">&nbsp;</td>
       <td width="123" height="30" align="left" valign="middle">Section Completed:</td>
          <td width="102" height="30" align="left" valign="top">
            Yes
            <input  name="cat[<?php echo $cat_cnt;?>][sec_comp]" onchange="showNoOptMsg('<?php echo $cat_cnt;?>','Y');" type="radio" value="Y" <?php if($cat['sec_comp']=='Y') echo 'checked' ;?>/>
No
<input  name="cat[<?php echo $cat_cnt;?>][sec_comp]" onchange="showNoOptMsg('<?php echo $cat_cnt;?>','N');" type="radio" value="N" <?php if($cat['sec_comp']=='N') echo 'checked' ;?>/></td>
          <td width="261" align="left" valign="top"><div <?php if(!isset($cat['sec_comp'])||$cat['sec_comp']=='Y') echo 'style="display:none;"' ;?>  id="<?php echo $cat_cnt;?>"><?php echo $showNoOptMsg; ?></div></td>
        </tr> 
   <?php $cat_cnt+=1; }?>   
            </table></td></tr>
        <tr>
          <td  colspan="10" align="left" valign="top">
              <br/><br/>
          
              
              
          </td>
        </tr>
      </table>       
    </span>   
     <span class="step" id="four">
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10%" height="30" align="left" valign="top"><label></label>
                  <input class="required" type="text" name="name_title" value="<?php echo $stat['name_title'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                 <td width="17%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input class="required" type="text" name="mngr_writ_store" value="<?php echo $stat['mngr_writ_store'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="12%" align="left" valign="top">Manager Signature: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top">
                           <input   type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="proj_sign" id="proj_sign" 
              onchange="javascript:formFileUpload('proj_sign','I','proj_sign_img', 960,720);"/>
                  </td>
                
        
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr> <td colspan="11" align="right">
                      <img width="100px" height="100px"  id="proj_sign_img_field" src="<?php echo  $image_dir.$stat['mngr_sign'];?>"
                             onclick="PopEx(this, null,  null, 0, 0, 50,'PopBoxImageLarge');"/>
                      <input type="hidden" id="proj_sign_img" name="mngr_sign" value="<?php echo $stat['mngr_sign'];?>"/>
                  </td></tr>
              <tr>
                <td colspan="11"><br/>Comments:</td>  
              </tr>
              <tr> <td colspan="11"><textarea name="comments" cols="50" rows="10"><?php echo $stat['comments'];?></textarea></td></tr>
    
                         

            </table>             
       </span>   
    <span id="five" class="step"> 
 <table width="90%"  id="glry_<?php echo $i; ?>">
    <tr><td height="5%">&nbsp;</td></tr>

    <tr><td align="left">Images:
         
  <input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="img_project" id="img_project" 
         <?php if(isset($form_id)&&$form_id>0){ ?>   onchange="javascript:signoffImgFileUpload('img_project','I','<?php echo $form_type;?>', 960,720,'<?php echo $form_id;?>');" 
         <?php }?> />          
        </td><td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    
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
     <?php if(isset($form_id)&&$form_id>0)
 {}else{ ?>
  <tr><td>Please save the signoff form then upload images...</td></tr>
  <?php }?>
</table>           
        </span>  

</div>
 <div id="demoNavigation"> 							
	<input class="navigation_button" id="back" value="Back" type="reset" />
	<input class="navigation_button" id="next" value="Next" type="submit" />
        <input class="navigation_button" id="Reset" value="Reset" type="button" onclick="javascript:resetSignOffForm();"/>
        <input class="navigation_button" id="sign_off_pdf_btn" value="Export to PDF" type="button" onclick="javascript:exportPDF('<?php echo $form_type;?>','<?php echo $form_id;?>');"/>
</div> 
</div> 
</form>
  </div>  
  <?php require 'form_script.php'; ?>
<!--</body>
</html>-->

  
  
   

