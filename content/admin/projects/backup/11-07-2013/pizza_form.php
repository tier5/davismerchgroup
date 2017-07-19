<?php
require 'Application.php';
extract($_POST);
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num   from pizza_form as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$pizza = pg_fetch_array($result);

pg_free_result($result);
if(isset($pizza['store_name'])&&$pizza['store_name']!='')
{

$query = ("SELECT * from tbl_chainmanagement where sto_name=".$pizza['store_name']);
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
$form_type="pizza";
$proj_image=$pizza['proj_image'];
?>

  <form id="sign_off_form" method="post" action="">
<input type="hidden" name="form_type" value="pizza" />  
  <input type="hidden" name="form_id" value="<?php if(isset($pizza['nest_id'])&&trim($pizza['nest_id'])!='') echo $pizza['nest_id'];?>" />  

  <div id="form">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
    <td colspan="4" align="center"><h3>Nestle DSD Sign Off</h3></td> 
    <td><img alt="logo" src="<?php echo $mydirectory;?>/images/davis-wbg.png" /></td>
    </tr>
              <tr>
    <td colspan="5">&nbsp;</td>    
    </tr>
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          
      <tr>
        <td width="100" height="30" align="left" valign="top">Store&nbsp;Name: </td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><select name="store_name" onchange="javascript:getstorenum(2);" id="store_name_2" >
                      <option value="0" <?php
					 if(isset($pizza['store_name']) && $pizza['store_name']==0)
					 echo ' selected="selected" ';
                     ?>>Other</option>
                      <?php
			for ($i = 0; $i < count($store); $i++) {
    			echo '<option value="'.$store[$i]['ch_id'].'" ';
    				if (isset($pizza['store_name']) && $pizza['store_name'] == $store[$i]['ch_id'])
        			echo 'selected="selected" ';
    				echo '>' . $store[$i]['chain'] . '</option>';
				}
		?>
                    </select></td>
                    <td width="20">&nbsp;</td>
                    <td width="100">Store&nbsp;#:</td>
                    <td><select name="store_num" id="store_num_2"   onchange="javascript:get_contact(2);">
                      <option value="<?php echo $pizza['store_num'];?>" selected="selected"><?php echo $pizza['store_num'];?></option>
                      <?php
			for ($i = 0; $i < count($store_num); $i++) {
    			echo '<option value="'.$store_num[$i]['chain_id'].'" ';
    				if (isset($pizza['store_num']) && $pizza['store_num'] == $store_num[$i]['chain_id'])
        			echo 'selected="selected" ';
    				echo '>' . $store_num[$i]['sto_num'] . '</option>';
				}
		?>
                    </select></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
      </tr>
       <tr id="other_tr" <?php if($pizza['store_name']!=0||$pizza['store_name']=='') echo ' style="display:none;"'?>>
        <td>Other:</td><td>&nbsp;</td><td>
        <input type="text" name="other" value="<?php echo $pizza['other']; ?> " />
        </td></tr>
         <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="address" id="address_2" value="<?php echo $pizza['address'];?>" /></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="city" id="city_2" value="<?php echo $pizza['city'];?>"/></td>
      </tr>
    </table></td>
    <td width="10" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100" height="30" align="left" valign="top">Date: </td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><input type="text" name="blit_date" class="date_field" value="<?php echo $pizza['blit_date'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Work&nbsp;Type&nbsp;:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><label>
          <select name="work_type">
            <option <?php if($pizza['work_type']=='Remodel') echo "selected";?>>Remodel</option>
            <option <?php if($pizza['work_type']=='Reset') echo "selected";?>>Reset</option>
            <option <?php if($pizza['work_type']=='New Store') echo "selected";?>>New Store</option>
            <option <?php if($pizza['work_type']=='Survey') echo "selected";?>>Survey</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
    </table></td>
    <td width="10" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td height="30"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    
  
      <tr>
        <td height="30" align="left" valign="top"><table width="1200" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="489" height="30" align="left" valign="top">SET COMPLETED?</td>
            <td width="10" align="left" valign="top">&nbsp;</td>
            <td width="136" align="left" valign="top">Yes
              <input checked="checked" name="set_complete" onchange="showNoOptMsg('1','Y');" type="radio" value="Y" <?php if($pizza['set_complete']=='Y') echo 'checked' ;?> />
              No
              <input name="set_complete" type="radio" onchange="showNoOptMsg('1','N');" value="N" <?php if($pizza['set_complete']=='N') echo 'checked' ;?>/></td>
            <td width="502" align="left" valign="top"><div <?php if(!isset($pizza['set_complete'])||$pizza['set_complete']=='Y') echo 'style="display:none;"' ;?>  id="1"><?php echo $showNoOptMsg; ?></div></td>
            <td width="24" align="left" valign="top">&nbsp;</td>
            <td width="10" align="left" valign="top">&nbsp;</td>
            <td width="29" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top">NEW ITEMS CUT IN?</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">Yes
              <input checked="checked"  name="new_item_cut" onchange="showNoOptMsg('2','Y');" type="radio" value="Y" <?php if($pizza['new_item_cut']=='Y') echo 'checked' ;?> />
              No
              <input name="new_item_cut" onchange="showNoOptMsg('2','N');" type="radio" value="N" <?php if($pizza['new_item_cut']=='N') echo 'checked' ;?> /></td>
            <td align="left" valign="top"><div <?php if(!isset($pizza['new_item_cut'])||$pizza['new_item_cut']=='Y') echo 'style="display:none;"' ;?>  id="2"><?php echo $showNoOptMsg; ?></div></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top">DC MARKED?</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top" >Yes
              <input checked="checked"  name="dc_marked" type="radio" onchange="showNoOptMsg('3','Y');" value="Y" <?php if($pizza['dc_marked']=='Y') echo 'checked' ;?> />
              No
              <input name="dc_marked" type="radio" onchange="showNoOptMsg('3','N');" value="N" <?php if($pizza['dc_marked']=='N') echo 'checked' ;?> /></td>
            <td align="left" valign="top"><div <?php if(!isset($pizza['dc_marked'])||$pizza['dc_marked']=='Y') echo 'style="display:none;"' ;?> id="3"><?php echo $showNoOptMsg; ?></div></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">IF NO TO ANY QUESTIONS PLEASE WRITE WHY IN COMMENTS:</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="500" height="30" align="left" valign="top">CURRENT SIZE OF SET              </td>
            <td align="left" valign="top"><input type="text" name="cur_sz_set" value="<?php echo $pizza['cur_sz_set'];?>" />
              (# of doors)&nbsp;</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top">Ice Cream Vault              </td>
            <td align="left" valign="top"><input type="text" name="ice_cream_vault"  value="<?php echo $pizza['ice_cream_vault'];?>"/>
              (# of doors)&nbsp;</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top"><input type="checkbox" name="walk_in_vault_front" value="checkbox" <?php 
            
            if($pizza['walk_in_vault_front']=='t') echo 'checked' ;?>/>
              Walk in Vault   Front
              <input type="checkbox" name="load_cooler" value="checkbox" <?php 
            if($pizza['load_cooler']=='t') echo 'checked' ;?>/>
              Load cooler&nbsp;
              <input type="checkbox" name="vendor_cool" value="checkbox" <?php 
            if($pizza['vendor_cool']=='t') echo 'checked' ;?>/>
              Vendor Visi-coolers</td>
            <td align="left" valign="top">&nbsp;</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top">Frozen Food Vault             </td>
            <td align="left" valign="top"> <input type="text" name="froz_food_vault" value="<?php echo $pizza['froz_food_vault'];?>"/>
              (# of doors)&nbsp;</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top"><input type="checkbox" name="walk_in_vault" value="checkbox" <?php 
            if($pizza['walk_in_vault']=='t') echo 'checked' ;?> />
              Walk in vault
              <input type="checkbox" name="front_load_cool" value="checkbox" <?php 
            if($pizza['front_load_cool']=='t') echo 'checked' ;?>/>
              Front load cooler</td>
            <td align="left" valign="top">&nbsp;</td>
            </tr>
<tr>
            <td height="30" align="left" valign="top">PIZZA DOORS</td>
            <td align="left" valign="top"><input type="text" name="pizza_door" value="<?php echo $pizza['pizza_door'];?>"/>
              (# of doors)&nbsp;</td>
          </tr>
<tr>
  <td height="30" align="left" valign="top"><input type="checkbox" name="walk_in_vault_p" value="checkbox" <?php 
            if($pizza['walk_in_vault_p']=='t') echo 'checked' ;?> />
    Walk in vault
    <input type="checkbox" name="front_load_cool_p" value="checkbox" <?php 
            if($pizza['front_load_cool_p']=='t') echo 'checked' ;?>/>
    Front load cooler</td>
  <td align="left" valign="top">&nbsp;</td>
</tr>
          <tr>
            <td height="30" align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
        </table>
         
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="30" align="left" valign="top"><div align="center"><strong>NEED SECTION INFORMATION </strong><br />
              </div></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="500" height="30" align="left" valign="top">Manufacture of Cold box: </td>
                  <td align="left" valign="top"><label>
                    <select name="man_coldbox">
                      <option <?php if($pizza['man_coldbox']=='Hill/Phoenix') echo 'selected';?>>Hill/Phoenix</option>
                      <option <?php if($pizza['man_coldbox']=='Hussmann') echo 'selected';?>>Hussmann</option>
                      <option <?php if($pizza['man_coldbox']=='Masterbilt') echo 'selected';?>>Masterbilt</option>
                      <option <?php if($pizza['man_coldbox']=='Kysor/Warren') echo 'selected';?>>Kysor/Warren</option>
                      <option <?php if($pizza['man_coldbox']=='Other') echo 'selected';?>>Other</option>
                    </select>
                  </label></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="500" height="30" align="left" valign="top">Model#:</td>
                  <td align="left" valign="top"><input type="text" name="model_num" value="<?php echo $pizza['model_num'];?>"/></td>
                  </tr>
                <tr>
                  <td height="30" align="left" valign="top">Any icecream shelves missing? &nbsp;</td>
                  <td height="30" align="left" valign="top">
                      <select name="shell_miss_ice">
                         <option <?php if($pizza['shell_miss_ice']=='Yes') echo 'selected';?>>Yes</option>
                      <option <?php if($pizza['shell_miss_ice']=='No') echo 'selected';?>>No</option> 
                      </select>       
            &nbsp;How many
                  
                  <input type="text" maxlength="2" style="width:40px;" name="ice_door" value="<?php echo $pizza['ice_door'];?>"/></td>
                  </tr>
                  
                  <tr>
                  <td height="30" align="left" valign="top">Any Frozen Food shelves missing? &nbsp;</td>
                  <td height="30" align="left" valign="top">
                      <select name="shell_miss_froz">
                         <option <?php if($pizza['shell_miss_froz']=='Yes') echo 'selected';?>>Yes</option>
                      <option <?php if($pizza['shell_miss_froz']=='No') echo 'selected';?>>No</option> 
                      </select>       
            &nbsp;How many
                  
                  <input type="text" maxlength="2" style="width:40px;" name="froz_door" value="<?php echo $pizza['froz_door'];?>"/></td>
                  </tr>
                  <tr>
                    <td height="30" align="left" valign="top">Any Pizza  shelves missing? &nbsp;</td>
                    <td height="30" align="left" valign="top"><select name="shell_miss_piz">
                      <option <?php if($pizza['shell_miss_piz']=='Yes') echo 'selected';?>>Yes</option>
                      <option <?php if($pizza['shell_miss_piz']=='No') echo 'selected';?>>No</option>
                    </select>
                      &nbsp;How many
                      <input type="text" maxlength="2" style="width:40px;" name="froz_door_piz" value="<?php echo $pizza['froz_door_piz'];?>"/></td>
                  </tr>
              
              </table></td>
            </tr>
            
          
            </table></td>
          </tr>
           
          </table>





          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="30" colspan="2" align="left" valign="top"><div align="center"><strong>STORE MANAGER SECTION </strong><br />
              </div></td>
              </tr>
            <tr>
              <td height="30" colspan="2" align="left" valign="top"><table width="1200" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="502" height="30" align="left" valign="top">ARE ALL THE SETS TO THE NEW SCHEMATIC?</td>
                  <td width="112" align="left" valign="top">Yes
                    <input checked="checked" name="new_schema" onchange="showNoOptMsg('8','Y');" type="radio" value="Y" <?php if($pizza['new_schema']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="new_schema" onchange="showNoOptMsg('8','N');" type="radio" value="N" <?php if($pizza['new_schema']=='N') echo 'checked' ;?>/></td>
                  <td width="586" align="left" valign="top"><div <?php if(!isset($pizza['new_schema'])||$pizza['new_schema']=='Y') echo 'style="display:none;"' ;?> id="8"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td width="502" height="30" align="left" valign="top">DID THE TAGS GET REPLACED</td>
                  <td align="left" valign="top">Yes
                    <input checked="checked" name="tag_replace" onchange="showNoOptMsg('9','Y');" type="radio" value="Y" <?php if($pizza['tag_replace']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="tag_replace" onchange="showNoOptMsg('9','N');" type="radio" value="N" <?php if($pizza['tag_replace']=='N') echo 'checked' ;?>/></td>
                  <td align="left" valign="top"><div <?php if(!isset($pizza['tag_replace'])||$pizza['tag_replace']=='Y') echo 'style="display:none;"' ;?> id="9"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td width="502" height="30" align="left" valign="top">WAS A COPY OF SCHEMATIC LEFT N THE CASE&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input checked="checked" name="copy_schema" onchange="showNoOptMsg('10','Y');" type="radio" value="Y" <?php if($pizza['copy_schema']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="copy_schema" onchange="showNoOptMsg('10','N');" type="radio" value="N" <?php if($pizza['copy_schema']=='N') echo 'checked' ;?>/></td>
                  <td align="left" valign="top"><div <?php if(!isset($pizza['copy_schema'])||$pizza['copy_schema']=='Y') echo 'style="display:none;"' ;?> id="10"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td height="30" align="left" valign="top">DOES THE CASE GET ICED UP AT ALL?&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input checked="checked" name="case_ice" onchange="showNoOptMsg('11','Y');" type="radio" value="Y" <?php if($pizza['case_ice']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="case_ice" onchange="showNoOptMsg('11','N');" type="radio" value="N" <?php if($pizza['case_ice']=='N') echo 'checked' ;?>/></td>
                  <td align="left" valign="top"><div <?php if(!isset($pizza['case_ice'])||$pizza['case_ice']=='Y') echo 'style="display:none;"' ;?> id="11"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td width="502" height="30" align="left" valign="top">IS THE TEMP OF THE CASE COLD ENOUGH FOR THE PRODUCT?&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input checked="checked" name="case_cold" onchange="showNoOptMsg('12','Y');" type="radio" value="Y" <?php if($pizza['case_cold']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="case_cold" onchange="showNoOptMsg('12','N');" type="radio" value="N" <?php if($pizza['case_cold']=='N') echo 'checked' ;?>/></td>
                  <td align="left" valign="top"><div <?php if(!isset($pizza['case_cold'])||$pizza['case_cold']=='Y') echo 'style="display:none;"' ;?> id="12"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td height="30" align="left" valign="top">IS THERE A SECONDARY LOCATION OR DC/REPACK&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input checked="checked" name="sec_loc" onchange="showNoOptMsg('13','Y');" type="radio" value="Y" <?php if($pizza['sec_loc']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="sec_loc" onchange="showNoOptMsg('13','N');" type="radio" value="no" <?php if($pizza['sec_loc']=='N') echo 'checked' ;?>/></td>
                  <td align="left" valign="top"><div <?php if(!isset($pizza['sec_loc'])||$pizza['sec_loc']=='Y') echo 'style="display:none;"' ;?> id="13"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td width="472" height="30" align="left" valign="top">IF NO TO ANY QUESTIONS PLEASE WRITE WHY IN COMMENTS:</td>
              <td width="684" align="left" valign="top">&nbsp;</td>
            </tr>
           
            <tr>
              <td colspan="2">
               
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10%" height="30" align="left" valign="top"><label></label>
                  <input type="text" name="name_title" value="<?php echo $pizza['name_title'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                 <td width="17%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input type="text" name="manager_storenum" value="<?php echo $pizza['manager_storenum'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="12%" align="left" valign="top">Manager Signature: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="proj_sign" id="proj_sign" 
              onchange="javascript:formFileUpload('proj_sign','I','proj_sign_img', 960,720);"/></td>
                
        
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr> <td colspan="11" align="right">
                      <img width="100px" height="100px"  id="proj_sign_img_field" src="<?php echo  $image_dir.$pizza['mngr_sign'];?>"
                            onclick="PopEx(this, null,  null, 0, 0, 50,'PopBoxImageLarge');"/>
                      <input type="hidden" id="proj_sign_img" name="mngr_sign" value="<?php echo $pizza['mngr_sign'];?>"/></td></tr>
              <tr>
                 <td colspan="11">Comments:</td> 
              </tr>
              <tr> <td colspan="11"><textarea name="comments" cols="50" rows="10"><?php echo $pizza['comments'];?></textarea></td></tr>
         
          
              <tr>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
                         

            </table>       
              </td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
            </tr>
        
          </table></td>
      </tr>
    </table></td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <?php require'footer_form.php'; ?> 
</table>

  </div>

<script type='text/javascript'>
$("#sign_off_form").validate({
		rules: {
			cur_sz_set: "required",
			ice_cream_vault: "required",
                        froz_food_vault: "required",
                        pizza_door: "required",
                        ice_door: "required",
                        froz_door: "required",
                        froz_door_piz: "required"
			
		},
		messages: {
			cur_sz_set: "*Required",
			ice_cream_vault: "*Required",
                        froz_food_vault: "*Required",
                        pizza_door: "*Required",
                        ice_door: "*Required",
                        froz_door: "*Required",
                        froz_door_piz: "*Required"
			
		}
	});
        
          <?php if(!isset($pizza['store_name']) || $pizza['store_name']==''){  ?>
           $('#store_name_2 option:eq(1)').attr('selected','selected').trigger('change');
           <?php } ?>    
        </script>
           </form>