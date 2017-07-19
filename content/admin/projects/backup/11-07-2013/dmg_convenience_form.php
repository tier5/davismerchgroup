<?php
require 'Application.php';

extract($_POST);
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num from dmg_convnc_form as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$dmg = pg_fetch_array($result);

pg_free_result($result);

if(isset($dmg['store_name'])&&$dmg['store_name']!='')
{
$query = ("SELECT * from tbl_chainmanagement where sto_name=".$dmg['store_name']);
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
$proj_image=$dmg['proj_image'];
}
$form_type='dmgconv';
?>
  <form id="sign_off_form" method="post" action="">
<input type="hidden" name="form_type" value="dmg_conv" />  
  <input type="hidden" name="form_id" value="<?php if(isset($dmg['dmg_id'])&&trim($dmg['dmg_id'])!='') echo $dmg['dmg_id'];?>" /> 
<div id="form">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
    <td colspan="4" align="center"><h3>DMG Convenience/Drug</h3></td>  
    <td><img alt="logo" src="<?php echo $mydirectory;?>/images/davis-wbg.png" /></td>
    </tr>
              <tr>
    <td colspan="5">&nbsp;</td>    
    </tr>
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100" height="30" align="left" valign="top">Store&nbsp;Name:</td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">
          
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                   <tr>
                     <td><select name="store_name"  onchange="javascript:getstorenum(2);" id="store_name_2"  >
                       <option value="0" <?php
					 if(isset($dmg['store_name']) && $dmg['store_name']==0)
					 echo ' selected="selected" ';
                     ?>>Other</option>
                       <?php
			for ($i = 0; $i < count($store); $i++) {
    			echo '<option value="'.$store[$i]['ch_id'].'" ';
    				if (isset($dmg['store_name']) && $dmg['store_name'] == $store[$i]['ch_id'])
        			echo 'selected="selected" ';
    				echo '>' . $store[$i]['chain'] . '</option>';
				}
		?>
                     </select></td>
                     <td width="20">&nbsp;</td>
                     <td width="100">Store#: </td>
                     <td><select name="store_num" id="store_num_2"   onchange="javascript:get_contact(2);">
                       <?php
			for ($i = 0; $i < count($store_num); $i++) {
    			echo '<option value="'.$store_num[$i]['chain_id'].'" ';
    				if (isset($dmg['store_num']) && $dmg['store_num'] == $store_num[$i]['chain_id'])
        			echo 'selected="selected" ';
    				echo '>' . $store_num[$i]['sto_num'] . '</option>';
				}
		?>
                     </select></td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                   </tr>
                 </table>
            </td>
      </tr>
      <tr id="other_tr" <?php if($dmg['store_name']!=0||$dmg['store_name']=='') echo ' style="display:none;"'?>>
        <td>Other:</td><td>&nbsp;</td><td>
        <input type="text" name="other" value="<?php echo $dmg['other']; ?> " />
        </td></tr>
      <tr>
      <tr><td colspan="3">&nbsp;</td></tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="address" id="address_2" value="<?php echo $dmg['address'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="city" id="city_2" value="<?php echo $dmg['city'];?>"/></td>
      </tr>
    </table></td>
    <td width="10" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100" height="30" align="left" valign="top">Date: </td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><input type="text" name="date" class="date_field" value="<?php echo $dmg['date'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Work&nbsp;Type&nbsp;:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><label>
          <select name="work_type">
            <option <?php if($dmg['work_type']=='Remodel') echo "selected";?>>Remodel</option>
            <option <?php if($dmg['work_type']=='Reset') echo "selected";?>>Reset</option>
            <option <?php if($dmg['work_type']=='New Store') echo "selected";?>>New Store</option>
            <option <?php if($dmg['work_type']=='Survey') echo "selected";?>>Survey</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
    </table></td>

    <td align="left" valign="top">&nbsp;</td>
  </tr>
</table>


<br />
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="30" align="left" valign="top">Total Cold Vault Doors:
            <select name="tot_cld_door">
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['tot_cld_door']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                        </select>
          </td>
        </tr>
      <tr>
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50" height="30" align="left" valign="top">CSD:</td>
            <td align="left" valign="top"><select name="csd">
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['csd']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                        </select></td>
            <td width="75" align="left" valign="top">New Age:</td>
            <td align="left" valign="top"><select name="new_age">
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['new_age']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                        </select></td>
            <td width="75" align="left" valign="top">&nbsp;Energy:</td>
            <td align="left" valign="top"><select name="energy">
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['energy']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                        </select></td>
            <td width="75" align="left" valign="top">Water:</td>
            <td align="left" valign="top"><select name="water">
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['water']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                        </select></td>
            <td width="75" align="left" valign="top">Dairy/Dell:</td>
            <td align="left" valign="top"><select name="dairy_dell">
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['dairy_dell']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                        </select></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="30" colspan="10" align="left" valign="top"># Shelves in Doors: </td>
            </tr>
          <tr>
            <td width="50" height="30" align="left" valign="top">CSD:</td>
            <td align="left" valign="top"><select name="csd_2">
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['csd_2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                        </select></td>
            <td width="75" align="left" valign="top">New Age:</td>
            <td align="left" valign="top"><select name="new_age2">
           <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['new_age2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select></td>
            <td width="75" align="left" valign="top">&nbsp;Energy:</td>
            <td align="left" valign="top"><select name="energy_2">
             <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['energy_2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select></td>
            <td width="75" align="left" valign="top">Water:</td>
            <td align="left" valign="top"><select name="water_2">
             <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['water_2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select></td>
            <td width="75" align="left" valign="top">Dairy/Dell:</td>
            <td align="left" valign="top"><select name="dairy_dell2">
             <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['dairy_dell2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td colspan="2">&nbsp;</td></tr>
          <tr>
            <td height="30" width="25%" align="left" >Width of CSD Doors Glide : </td>
            <td  align="left"><select name="csd_door_width">
                <option <?php if($dmg['csd_door_width']=='7w') echo 'selected';?>>7w</option>
                <option <?php if($dmg['csd_door_width']=='8w') echo 'selected';?>>8w</option>
                <option <?php if($dmg['csd_door_width']=='9w') echo 'selected';?>>9w</option>
          
              </select></td>
            </tr>
       
        </table>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
              <td width="150" height="30" align="left" valign="top">Door Handles as you face them  : </td>
              <td align="left" valign="top">Left
                <label>
                <input type="checkbox" name="check[dr_hnd_left]" value="checkbox" <?php 
            if($dmg['dr_hnd_left']=='t') echo 'checked' ;?>/>
                </label> 
                Right 
                <input type="checkbox" name="check[dr_hnd_right]" value="checkbox" <?php 
            if($dmg['dr_hnd_right']=='t') echo 'checked' ;?>/></td>
              </tr>
              <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
              <td height="30" align="left" valign="top">Did the back of the glides get stickers </td>
              <td height="30" colspan="2" align="left" valign="top">Yes
                <label>
                <input name="sticker" type="radio" value="Y" <?php if($dmg['sticker']=='Y') echo 'checked' ;?>/>
                </label>
No
<input name="sticker" type="radio" value="N" <?php if($dmg['sticker']=='N') echo 'checked' ;?>/></td>
              </tr>
              <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
              <td height="30" align="left" valign="top">If No Please explain in comments: </td>
              <td height="30" colspan="2" align="left" valign="top">
                  <textarea name="glide_comment" cols="50" rows="10"><?php echo $dmg['glide_comment'];?></textarea></td>
            </tr>
          </table></td>
        </tr>
    </table>
      <br />
      <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">

              <tr>
                <td width="150" height="30" align="left" valign="top"># of New Glide Sheets Installed: </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="75" height="30" align="left" valign="top">20 0z: 
               <br/>
            <input type="text" name="oz_20_txt" value="<?php echo $dmg['oz_20_txt'];?>" maxLength="3" style="width:60px;"/>         
                </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="75">1L: 
     <br/>
             <input type="text" name="ltr_1_txt" value="<?php echo $dmg['ltr_1_txt'];?>" maxLength="3" style="width:60px;"/>      
                </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top" width="75">10-12 0z :<br/>
                  <input type="text" name="oz_10_12_txt" value="<?php echo $dmg['oz_10_12_txt'];?>" maxLength="3" style="width:60px;"/> 
                 </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top" width="75">32 0z:<br/>
                  <input type="text" name="oz_32_txt" value="<?php echo $dmg['oz_32_txt'];?>" maxLength="3" style="width:60px;"/> 
             </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top" width="75">2L:
  <br/>
                  <input type="text" name="ltr_2_txt" value="<?php echo $dmg['ltr_2_txt'];?>" maxLength="3" style="width:60px;"/> 
                 </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top" width="75">Red Bull:
       <br/>
                  <input type="text" name="red_bull_txt" value="<?php echo $dmg['red_bull_txt'];?>" maxLength="3" style="width:60px;"/> 
                </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
            </table>
            <br />
            <br />
           
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10%" height="30" align="left" valign="top"><label></label>
                  <input type="text" name="mngr_name" value="<?php echo $dmg['mngr_name'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                 <td width="17%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input type="text" name="mngr_storenum" value="<?php echo $dmg['mngr_storenum'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="12%" align="left" valign="top">Manager Signature: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top">
                    <input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="proj_sign" id="proj_sign" 
              onchange="javascript:formFileUpload('proj_sign','I','proj_sign_img', 960,720);"/></td>
                
        
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr> <td colspan="11" align="right">
                      <img width="100px" height="100px"  id="proj_sign_img_field" src="<?php echo  $image_dir.$dmg['mngr_sign'];?>"
                             onclick="PopEx(this, null,  null, 0, 0, 50,'PopBoxImageLarge');"/>
                      <input type="hidden" id="proj_sign_img" name="mngr_sign" value="<?php echo $dmg['mngr_sign'];?>"/></td></tr>
              
              <tr><td colspan="11">Comments:</td></tr>
              <tr> <td colspan="11"><textarea name="comments" cols="50" rows="10"><?php echo $dmg['comments'];?></textarea></td></tr>
         
          
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
      </table></td>
  </tr>
</table>
   <?php require'footer_form.php'; ?>
</div>

<script type='text/javascript'>
   $("#sign_off_form").validate({
		rules: {
			date: "required"
			
		},
		messages: {
			date: "*Required"
			
		}
	});
       <?php if(!isset($dmg['store_name']) || $dmg['store_name']==''){  ?>
           $('#store_name_2 option:eq(1)').attr('selected','selected').trigger('change');
           <?php } ?>
           
        
        </script>
</form>