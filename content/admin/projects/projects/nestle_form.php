<?php
require 'Application.php';
extract($_POST);
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num   from nestle_form as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$nestle = pg_fetch_array($result);

pg_free_result($result);
if(isset($nestle['store_name'])&&$nestle['store_name']!='')
{

$query = ("SELECT * from tbl_chainmanagement where sto_name=".$nestle['store_name']);
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
$form_type="nestle";
$proj_image=$nestle['proj_image'];
?>


<input type="hidden" name="form_type" value="nestle" />  
  <input type="hidden" name="form_id" value="<?php if(isset($nestle['nest_id'])&&trim($nestle['nest_id'])!='') echo $nestle['nest_id'];?>" />  

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
                      <option value="" selected="selected">------SELECT------</option>
                      <option value="0" <?php
					 if(isset($nestle['store_name']) && $nestle['store_name']==0)
					 echo ' selected="selected" ';
                     ?>>Other</option>
                      <?php
			for ($i = 0; $i < count($store); $i++) {
    			echo '<option value="'.$store[$i]['ch_id'].'" ';
    				if (isset($nestle['store_name']) && $nestle['store_name'] == $store[$i]['ch_id'])
        			echo 'selected="selected" ';
    				echo '>' . $store[$i]['chain'] . '</option>';
				}
		?>
                    </select></td>
                    <td width="20">&nbsp;</td>
                    <td width="100">Store&nbsp;#:</td>
                    <td><select name="store_num" id="store_num_2"   onchange="javascript:get_contact(2);">
                      <option value="">---Select---</option>
                      <option value="<?php echo $nestle['store_num'];?>" selected="selected"><?php echo $nestle['store_num'];?></option>
                      <?php
			for ($i = 0; $i < count($store_num); $i++) {
    			echo '<option value="'.$store_num[$i]['chain_id'].'" ';
    				if (isset($nestle['store_num']) && $nestle['store_num'] == $store_num[$i]['chain_id'])
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
       <tr id="other_tr" <?php if($nestle['store_name']!=0||$nestle['store_name']=='') echo ' style="display:none;"'?>>
        <td>Other:</td><td>&nbsp;</td><td>
        <input type="text" name="other" value="<?php echo $nestle['other']; ?> " />
        </td></tr>
         <tr><td colspan="3"></td></tr>
      <tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="address" id="address_2" value="<?php echo $nestle['address'];?>" /></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="city" id="city_2" value="<?php echo $nestle['city'];?>"/></td>
      </tr>
    </table></td>
    <td width="10" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100" height="30" align="left" valign="top">Date: </td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><input type="text" name="blit_date" class="date_field" value="<?php echo $nestle['blit_date'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Work&nbsp;Type&nbsp;:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><label>
          <select name="work_type">
            <option <?php if($nestle['work_type']=='Remodel') echo "selected";?>>Remodel</option>
            <option <?php if($nestle['work_type']=='Reset') echo "selected";?>>Reset</option>
            <option <?php if($nestle['work_type']=='New Store') echo "selected";?>>New Store</option>
            <option <?php if($nestle['work_type']=='Survey') echo "selected";?>>Survey</option>
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
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="494" height="30" align="left" valign="top">SET COMPLETED?</td>
            <td width="7" align="left" valign="top">&nbsp;</td>
            <td width="98" align="left" valign="top">Yes
              <input name="set_complete" type="radio" onchange="showNoOptMsg('1','Y');" value="Y" <?php if($nestle['set_complete']=='Y') echo 'checked' ;?> />
              No
              <input name="set_complete" type="radio" onchange="showNoOptMsg('1','N');" value="N" <?php if($nestle['set_complete']=='N') echo 'checked' ;?>/></td>
            <td width="517" align="left" valign="top"><div <?php if(!isset($nestle['set_complete'])||$nestle['set_complete']=='Y') echo 'style="display:none;"' ;?>  id="1"><?php echo $showNoOptMsg; ?></div></td>
            <td width="11" align="left" valign="top">&nbsp;</td>
            <td width="9" align="left" valign="top">&nbsp;</td>
            <td width="20" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top">NEW ITEMS CUT IN?</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">Yes
              <input name="new_item_cut" type="radio" onchange="showNoOptMsg('2','Y');" value="Y" <?php if($nestle['new_item_cut']=='Y') echo 'checked' ;?> />
              No
              <input name="new_item_cut" type="radio" onchange="showNoOptMsg('2','N');" value="N" <?php if($nestle['new_item_cut']=='N') echo 'checked' ;?> /></td>
            <td align="left" valign="top"><div <?php if(!isset($nestle['new_item_cut'])||$nestle['new_item_cut']=='Y') echo 'style="display:none;"' ;?>  id="2"><?php echo $showNoOptMsg; ?></div></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top">DC MARKED?</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top" >Yes
              <input name="dc_marked" type="radio" onchange="showNoOptMsg('3','Y');" value="Y" <?php if($nestle['dc_marked']=='Y') echo 'checked' ;?> />
              No
              <input name="dc_marked" type="radio" onchange="showNoOptMsg('3','N');" value="N" <?php if($nestle['dc_marked']=='N') echo 'checked' ;?> /></td>
            <td align="left" valign="top"><div <?php if(!isset($nestle['dc_marked'])||$nestle['dc_marked']=='Y') echo 'style="display:none;"' ;?>  id="3"><?php echo $showNoOptMsg; ?></div></td>
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
            <td align="left" valign="top"><input type="text" name="cur_sz_set" value="<?php echo $nestle['cur_sz_set'];?>" />
              (# of doors)&nbsp;</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top">Ice Cream Vault              </td>
            <td align="left" valign="top"><input type="text" name="ice_cream_vault"  value="<?php echo $nestle['ice_cream_vault'];?>"/>
              (# of doors)&nbsp;</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top"><input type="checkbox" name="walk_in_vault_front" value="checkbox" <?php 
            
            if($nestle['walk_in_vault_front']=='t') echo 'checked' ;?>/>
              Walk in Vault   Front
              <input type="checkbox" name="load_cooler" value="checkbox" <?php 
            if($nestle['load_cooler']=='t') echo 'checked' ;?>/>
              Load cooler&nbsp;
              <input type="checkbox" name="vendor_cool" value="checkbox" <?php 
            if($nestle['vendor_cool']=='t') echo 'checked' ;?>/>
              Vendor Visi-coolers</td>
            <td align="left" valign="top">&nbsp;</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top">Frozen Food Vault             </td>
            <td align="left" valign="top"> <input type="text" name="froz_food_vault" value="<?php echo $nestle['froz_food_vault'];?>"/>
              (# of doors)&nbsp;</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top"><input type="checkbox" name="walk_in_vault" value="checkbox" <?php 
            if($nestle['walk_in_vault']=='t') echo 'checked' ;?> />
              Walk in vault
              <input type="checkbox" name="front_load_cool" value="checkbox" <?php 
            if($nestle['front_load_cool']=='t') echo 'checked' ;?>/>
              Front load cooler</td>
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
                      <option <?php if($nestle['man_coldbox']=='Hill/Phoenix') echo 'selected';?>>Hill/Phoenix</option>
                      <option <?php if($nestle['man_coldbox']=='Hussmann') echo 'selected';?>>Hussmann</option>
                      <option <?php if($nestle['man_coldbox']=='Masterbilt') echo 'selected';?>>Masterbilt</option>
                      <option <?php if($nestle['man_coldbox']=='Kysor/Warren') echo 'selected';?>>Kysor/Warren</option>
                      <option <?php if($nestle['man_coldbox']=='Other') echo 'selected';?>>Other</option>
                    </select>
                  </label></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="500" height="30" align="left" valign="top">Model#:</td>
                  <td align="left" valign="top"><input type="text" name="model_num" value="<?php echo $nestle['model_num'];?>"/></td>
                  </tr>
                <tr>
                  <td height="30" align="left" valign="top">Any icecream shelves missing? &nbsp;</td>
                  <td height="30" align="left" valign="top">
                      <select name="shell_miss_ice">
                         <option <?php if($nestle['shell_miss_ice']=='Yes') echo 'selected';?>>Yes</option>
                      <option <?php if($nestle['shell_miss_ice']=='No') echo 'selected';?>>No</option> 
                      </select>       
            &nbsp;How many
                  
                  <input type="text" maxlength="2" style="width:40px;" name="ice_door" value="<?php echo $nestle['ice_door'];?>"/></td>
                  </tr>
                  
                  <tr>
                  <td height="30" align="left" valign="top">Any frozen food shelves missing? &nbsp;</td>
                  <td height="30" align="left" valign="top">
                      <select name="shell_miss_froz">
                         <option <?php if($nestle['shell_miss_froz']=='Yes') echo 'selected';?>>Yes</option>
                      <option <?php if($nestle['shell_miss_froz']=='No') echo 'selected';?>>No</option> 
                      </select>       
            &nbsp;How many
                  
                  <input type="text" maxlength="2" style="width:40px;" name="froz_door" value="<?php echo $nestle['froz_door'];?>"/></td>
                  </tr>
              </table></td>
            </tr>
            
          
              </table></td>
            </tr>
           
          </table>
          <br />
          <br />
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="30" colspan="2" align="left" valign="top"><div align="center"><strong>STORE MANAGER SECTION </strong><br />
              </div></td>
              </tr>
            <tr>
              <td height="30" colspan="2" align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="478" height="30" align="left" valign="top">ARE ALL THE SETS TO THE NEW SCHEMATIC?</td>
                  <td width="102" align="left" valign="top">Yes
                    <input name="new_schema"  onchange="showNoOptMsg('4','Y');" type="radio" value="Y" <?php if($nestle['new_schema']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="new_schema"  onchange="showNoOptMsg('4','N');" type="radio" value="N" <?php if($nestle['new_schema']=='N') echo 'checked' ;?>/></td>
                  <td width="576"><div <?php if(!isset($nestle['new_schema'])||$nestle['new_schema']=='Y') echo 'style="display:none;"' ;?> id="4"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td height="30" align="left" valign="top">DID THE TAGS GET REPLACED</td>
                  <td align="left" valign="top">Yes
                    <input name="tag_replace" onchange="showNoOptMsg('5','Y');" type="radio" value="Y" <?php if($nestle['tag_replace']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="tag_replace" onchange="showNoOptMsg('5','N');" type="radio" value="N" <?php if($nestle['tag_replace']=='N') echo 'checked' ;?>/></td>
                  <td><div <?php if(!isset($nestle['tag_replace'])||$nestle['tag_replace']=='Y') echo 'style="display:none;"' ;?> id="5"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td height="30" align="left" valign="top">WAS A COPY OF SCHEMATIC LEFT N THE CASE&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input name="copy_schema" onchange="showNoOptMsg('6','Y');" type="radio" value="Y" <?php if($nestle['copy_schema']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="copy_schema" onchange="showNoOptMsg('6','N');" type="radio" value="N" <?php if($nestle['copy_schema']=='N') echo 'checked' ;?>/></td>
                  <td><div <?php if(!isset($nestle['copy_schema'])||$nestle['copy_schema']=='Y') echo 'style="display:none;"' ;?> id="6"><?php echo $showNoOptMsg; ?></div></td>
                  td&gt; </tr>
                <tr>
                  <td height="30" align="left" valign="top">DOES THE CASE GET ICED UP AT ALL?&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input name="case_ice" type="radio" onchange="showNoOptMsg('7','Y');" value="Y" <?php if($nestle['case_ice']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="case_ice" onchange="showNoOptMsg('7','N');" type="radio" value="N" <?php if($nestle['case_ice']=='N') echo 'checked' ;?>/></td>
                  <td><div <?php if(!isset($nestle['case_ice'])||$nestle['case_ice']=='Y') echo 'style="display:none;"' ;?> id="7"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td height="30" align="left" valign="top">IS THE TEMP OF THE CASE COLD ENOUGH FOR THE PRODUCT?&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input name="case_cold" onchange="showNoOptMsg('8','Y');" type="radio" value="Y" <?php if($nestle['case_cold']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="case_cold"  onchange="showNoOptMsg('8','N');" type="radio" value="N" <?php if($nestle['case_cold']=='N') echo 'checked' ;?>/></td>
                  <td><div <?php if(!isset($nestle['case_cold'])||$nestle['case_cold']=='Y') echo 'style="display:none;"' ;?> id="8"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td height="30" align="left" valign="top">IS THERE A SECONDARY LOCATION OR DC/REPACK&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input name="sec_loc" type="radio" onchange="showNoOptMsg('9','Y');" value="Y" <?php if($nestle['sec_loc']=='Y') echo 'checked' ;?>/>
                    No
                    <input name="sec_loc" type="radio" onchange="showNoOptMsg('9','N');" value="no" <?php if($nestle['sec_loc']=='N') echo 'checked' ;?>/></td>
                  <td><div <?php if(!isset($nestle['sec_loc'])||$nestle['sec_loc']=='Y') echo 'style="display:none;"' ;?> id="9"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td width="478" height="30" align="left" valign="top">IF NO TO ANY QUESTIONS PLEASE WRITE WHY IN COMMENTS:</td>
              <td width="121" align="left" valign="top">&nbsp;</td>
            </tr>
           
            <tr>
              <td colspan="2">
               
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10%" height="30" align="left" valign="top"><label></label>
                  <input type="text" name="name_title" value="<?php echo $nestle['name_title'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                 <td width="17%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input type="text" name="manager_storenum" value="<?php echo $nestle['manager_storenum'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="12%" align="left" valign="top">Manager Signature: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="proj_sign" id="proj_sign" 
              onchange="javascript:formFileUpload('proj_sign','I','proj_sign_img', 960,720);"/></td>
                
        
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr> <td colspan="11" align="right">
                      <img width="100px" height="100px"  id="proj_sign_img_field" src="<?php echo  $image_dir.$nestle['mngr_sign'];?>"
                            onclick="PopEx(this, null,  null, 0, 0, 50,'PopBoxImageLarge');"/>
                      <input type="hidden" id="proj_sign_img" name="mngr_sign" value="<?php echo $nestle['mngr_sign'];?>"/></td></tr>
              <tr>
                 <td colspan="11">Comments:</td> 
              </tr>
              <tr> <td colspan="11"><textarea name="comments" cols="50" rows="10"><?php echo $nestle['comments'];?></textarea></td></tr>
         
          
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