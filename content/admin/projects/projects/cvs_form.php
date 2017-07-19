<?php
require 'Application.php';

extract($_POST);
if(isset($pid)&&$pid!='')
{
    $query  ='select * from cvs_form where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$cvs = pg_fetch_array($result);

pg_free_result($result);
}
$form_type="cvs";
$proj_image=$cvs['proj_image'];
$form_type="cvs";
?>
<input type="hidden" name="form_type" value="cvs" />  
  <input type="hidden" name="form_id" value="<?php if(isset($cvs['cvs_id'])&&trim($cvs['cvs_id'])!='') echo $cvs['cvs_id'];?>" /> 
<div id="form">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30"><h2 align="center">CVS Pharmacy -- Area 18 CVS Stores</h2> </td>
  </tr>
  <tr>
    <td height="30"><strong><br />
      BLITZ SECTION: ICE CREAM FROZEN FOOD<br />
      <br />
      STARTING DATE: JUNE 17TH, 2013</strong></td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top">
	<fieldset class="fsd" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="30" align="left" valign="top"><div align="center"><strong>BLITZ COMPANY SECTION </strong><br />
          must be filled out by Blitz company to be recorded </div></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="30" align="left" valign="top">STORE#    </td>
            <td width="10" align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input type="text" name="store_num" value="<?php echo $cvs['store_num'];?>"/></td>
            <td width="10" align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">DATE </td>
            <td width="10" align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input type="text" name="date" class="date_field" value="<?php echo $cvs['date'];?>"/></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="30" align="left" valign="top">SET COMPLETED?</td>
            <td width="10" align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">Yes
              <input name="set_cmp" type="radio" value="Y" <?php if($cvs['set_cmp']=='Y') echo 'checked' ;?>/>
No
<input name="set_cmp" type="radio" value="N" <?php if($cvs['set_cmp']=='N') echo 'checked' ;?>/></td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top">NEW ITEMS CUT IN?</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">Yes
              <input name="new_it_cut" type="radio" value="Y" <?php if($cvs['new_it_cut']=='Y') echo 'checked' ;?>/>
No
<input name="new_it_cut" type="radio" value="N" <?php if($cvs['new_it_cut']=='N') echo 'checked' ;?>/></td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top">DC MARKED?</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">Yes
              <input name="dc_mark" type="radio" value="Y" <?php if($cvs['dc_mark']=='Y') echo 'checked' ;?>/>
No
<input name="dc_mark" type="radio" value="N" <?php if($cvs['dc_mark']=='N') echo 'checked' ;?>/></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="30" align="left" valign="top">CURRENT SIZE OF SET   
              <input type="text" name="curr_size" value="<?php echo $cvs['curr_size'];?>"/>
              (# of doors)</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top">Ice Cream Vault     
              <label>
              <input type="text" name="icecream_vault" value="<?php echo $cvs['icecream_vault'];?>"/>
              </label>
              (# of doors)</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top"><select name="walk_vlt1">
              <option>Walk in Vault</option>
              <option>Front Load Cooler</option>
              <option>Vendor Visi Coolers</option>
            </select></td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top">Frozen Food Vault   
              <input type="text" name="froz_fd_vlt" value="<?php echo $cvs['froz_fd_vlt'];?>"/>
              (# of doors)</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top"><select name="walk_vlt2">
              <option>Walk in Vault</option>
              <option>Front Load Cooler</option>
              <option>Vendor Visi Coolers</option>
            </select></td>
            </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top">IF NOT COMPLETE EXPLANATION : </td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top"><textarea name="cmp_exp"><?php echo $cvs['cmp_exp'];?></textarea></td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top">COMMENTS:</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top"><textarea name="comments"><?php echo $cvs['comments'];?></textarea></td>
          </tr>
        </table></td>
      </tr>
    </table>
	</fieldset>
	
	</td>
    <td width="10" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><fieldset class="fsd" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="30" align="left" valign="top"><div align="center"><strong>NEED SECTION INFORMATION </strong><br />
        </div></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="30" align="left" valign="top">Manufacture of Cold box: </td>
            <td width="10" align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top"><label>
              <select name="man_coldbox">
                <option>Hill/Phoenix</option>
                <option>Hussmann</option>
                <option>Masterbilt</option>
                <option>Kysor/Warren</option>
                <option>Other</option>
              </select>
            </label></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="30" align="left" valign="top">Model#:</td>
            <td width="10" align="left" valign="top">&nbsp;</td>
            <td colspan="5" align="left" valign="top"><input type="text" name="model_num" value="<?php echo $cvs['model_num'];?>"/></td>
            </tr>
          <tr>
            <td height="30" colspan="7" align="left" valign="top">ANY SHELVES MISSING IN ICECREAM? </td>
            </tr>
          <tr>
            <td height="30" colspan="7" align="left" valign="top">Yes
              <input name="icecream_misshel" type="radio" value="Y" <?php if($cvs['icecream_misshel']=='Y') echo 'checked' ;?>/>
No
<input name="icecream_misshel" type="radio" value="N" <?php if($cvs['icecream_misshel']=='N') echo 'checked' ;?>/></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="30" align="left" valign="top">If yes how many and what door#: 
              <input type="text" name="how_many_doors" value="<?php echo $cvs['how_many_doors'];?>"/></td>
            </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top">ANY SHELVES MISSING IN FROZEN FOOD? </td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top">Yes
              <input name="froz_misshel" type="radio" value="Y" <?php if($cvs['froz_misshel']=='Y') echo 'checked' ;?>/>
No
<input name="froz_misshel" type="radio" value="N" <?php if($cvs['froz_misshel']=='N') echo 'checked' ;?>/></td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top">If yes how many and what door#:
              <input type="text" name="frz_ml_drnum" value="<?php echo $cvs['frz_ml_drnum'];?>"/></td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top">&nbsp;</td>
            </tr>
        </table></td>
      </tr>
    </table>
	</fieldset></td>
    <td width="10" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><fieldset class="fsd" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="30" align="left" valign="top"><div align="center"><strong>STORE MANAGER SECTION </strong><br />
        </div></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">ARE ALL THE SETS TO THE NEW SCHEMATIC?</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Yes
          <input name="new_schema" type="radio" value="Y" <?php if($cvs['new_schema']=='Y') echo 'checked' ;?>/>
No
<input name="new_schema" type="radio" value="N" <?php if($cvs['new_schema']=='N') echo 'checked' ;?>/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><br />
          DID THE TAGS GET REPLACED</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Yes
          <input name="tag_replace" type="radio" value="Y" <?php if($cvs['tag_replace']=='Y') echo 'checked' ;?>/>
No
<input name="tag_replace" type="radio" value="N" <?php if($cvs['tag_replace']=='N') echo 'checked' ;?>/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">WAS A COPY OF SCHEMATIC LEFT N THE CASE</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Yes
          <input name="copy_schema" type="radio" value="Y" <?php if($cvs['copy_schema']=='Y') echo 'checked' ;?>/>
No
<input name="copy_schema" type="radio" value="N" <?php if($cvs['copy_schema']=='N') echo 'checked' ;?>/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><br />
          DOES THE CASE GET ICED UP AT ALL?</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Yes
          <input name="iced_up" type="radio" value="Y" <?php if($cvs['iced_up']=='Y') echo 'checked' ;?>/>
No
<input name="iced_up" type="radio" value="N" <?php if($cvs['iced_up']=='N') echo 'checked' ;?>/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">IS THE TEMP OF THE CASE COLD ENOUGH FOR THE PRODUCT?</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Yes
          <input name="case_temp" type="radio" value="Y" <?php if($cvs['case_temp']=='Y') echo 'checked' ;?>/>
No
<input name="case_temp" type="radio" value="N" <?php if($cvs['case_temp']=='N') echo 'checked' ;?>/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><br />
          IS THERE A SECONDARY LOCATION OR DC/REPACK</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Yes
          <input name="dc_repack" type="radio" value="Y" <?php if($cvs['dc_repack']=='Y') echo 'checked' ;?>/>
No
<input name="dc_repack" type="radio" value="N" <?php if($cvs['dc_repack']=='N') echo 'checked' ;?> /></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">IF NO TO ANY QUESTIONS PLEASE WRITE WHY IN COMMENTS:</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><textarea name="quest"><?php echo $cvs['quest'];?></textarea></td>
      </tr>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">STORE AUTHORIZED SIGNATURE: </td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><input type="text" name="store_sign" value="<?php echo $cvs['store_sign'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">DATE: 
          <input type="text" name="store_date" class="date_field" value="<?php echo $cvs['store_date'];?>"/></td>
      </tr>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">PLEASE PRINT YOUR NAME AND TITLE:</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><input type="text" name="name_title" value="<?php echo $cvs['name_title'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"><br />
          STORE STAMP </td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">
            
      
       <input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="proj_img" id="proj_img" 
              onchange="javascript:formFileUpload('proj_img','I', 960,720);"/>
       <input type="hidden" name="proj_image" id="proj_image" value="<?php echo $proj_image;?>"/> 
       <img width="100px" height="100px" id="proj_img_field" src="<?php echo $image_dir.$proj_image;?>"/>
                
            
        </td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
    </table>
	</fieldset></td>
  </tr>
  
</table>

</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <?php require'footer_form.php'; ?> 
</table>

