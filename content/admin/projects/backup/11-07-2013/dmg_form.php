<?php
require 'Application.php';

extract($_POST);
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num from dmg_form as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$dmg = pg_fetch_array($result);
//print_r($dmg);
pg_free_result($result);
}
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
$form_type='dmg';
?>
  <form id="sign_off_form" method="post" action="">
<input type="hidden" name="form_type" value="dmg_form" />  
  <input type="hidden" name="form_id" value="<?php if(isset($dmg['dmg_id'])&&trim($dmg['dmg_id'])!='') echo $dmg['dmg_id'];?>" /> 
<div id="form">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
    <td colspan="4" align="center"><h3>DMG Chain Form</h3></td> 
    <td><img alt="logo" style="float: left" src="<?php echo $mydirectory;?>/images/davis-wbg.png" /></td>
    </tr>
              <tr>
    <td colspan="5">&nbsp;</td>    
    </tr>
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          
      <tr>
        <td width="79" height="30" align="left" valign="top">Store&nbsp;Name: </td>
        <td width="31" align="left" valign="top">&nbsp;</td>
        <td width="599" align="left" valign="top"><!--          <input type="text" name="store_num" style="width:95px;"  value="<?php //echo $dmg['store_num'];?>"/>-->
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><select name="store_name" onchange="javascript:getstorenum(2);" id="store_name_2" >
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
            <td width="100">Store&nbsp;#:</td>
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
        </table></td>
      </tr>
      <tr id="other_tr" <?php if($dmg['store_name']!=0||$dmg['store_name']=='') echo ' style="display:none;"'?>>
        <td>Other:</td><td>&nbsp;</td><td>
        <input type="text" name="other" value="<?php echo $dmg['other']; ?> " />
        </td></tr>
      <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" id="address_2" name="address" value="<?php echo $dmg['address'];?>" /></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" id="city_2"  name="city" value="<?php echo $dmg['city'];?>"/></td>
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
    <td width="10" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"></td>
  </tr>
</table>


<br />
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="220" height="30" align="left" valign="top">CSD: </td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><input type="text" name="csd" value="<?php echo $dmg['csd'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">CSD Split Table :</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="csd_split1" value="<?php echo $dmg['csd_split1'];?>"/>
          +
          <input type="text" name="csd_split2" value="<?php echo $dmg['csd_split2'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">High
          <label></label>
            <label>
            <input name="h_l" type="radio" value="H" <?php if($dmg['h_l']=='H') echo 'checked' ;?>/>
            </label>
          Low:
  <input name="h_l" type="radio" value="L"  <?php if($dmg['h_l']=='L') echo 'checked' ;?>/></td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Number of shelves per section : </td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="num_shelf" value="<?php echo $dmg['num_shelf'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Shelving:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">Type:
          <select name="shell_type">
                <option <?php if($dmg['shell_type']=='Lozier') echo "selected";?>>Lozier</option>
                <option <?php if($dmg['shell_type']=='Hussman') echo "selected";?>>Hussman</option>
                <option <?php if($dmg['shell_type']=='Slater') echo "selected";?>>Slater</option>
                <option <?php if($dmg['shell_type']=='Madix') echo "selected";?>>Madix</option>
                <option <?php if($dmg['shell_type']=='Rails') echo "selected";?>>Rails</option>
            </select>
             &nbsp;Length:
             <select name="shell_foot">
              <?php for($i=3;$i<=8;$i++) {
              if($i%2==0||$i==3)
              {?>      
              <option <?php if($dmg['shell_foot']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php }} ?>
            </select>
          
          
          &nbsp;Depth:
          <select name="shell_depth">
              <?php for($i=12;$i<=36;$i++) {?>      
              <option <?php if($dmg['shell_depth']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>Inches
         
          &nbsp;Color:
          <select name="shell_col">
            <option <option <?php if($dmg['shell_col']=='White') echo "selected";?>>White</option>>White</option>
            <option <?php if($dmg['shell_col']=='Chocolate') echo "selected";?>>Chocolate</option>
            <option <?php if($dmg['shell_col']=='Tan') echo "selected";?>>Tan</option>
            <option <?php if($dmg['shell_col']=='Grey') echo "selected";?>>Grey</option>
          </select>
          &nbsp;Molding Color:
          <select name="shell_mld_col">
            <option <?php if($dmg['shell_mld_col']=='White') echo "selected";?>>White</option>
            <option <?php if($dmg['shell_mld_col']=='Chocolate') echo "selected";?>>Chocolate</option>
            <option <?php if($dmg['shell_mld_col']=='Silver') echo "selected";?>>Silver</option>
            <option <?php if($dmg['shell_mld_col']=='Tan') echo "selected";?>>Tan</option>
            <option <?php if($dmg['shell_mld_col']=='Grey') echo "selected";?>>Grey</option>
          </select></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top" >Number of base decks by size: </td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">3'
          <input type="text" name="sl_3" value="<?php echo $dmg['sl_3'];?>" maxLength="3"/>
          &nbsp;4'
          <input type="text" name="sl_4" value="<?php echo $dmg['sl_4'];?>" maxLength="3"/>
          &nbsp;6'
          <input type="text" name="sl_6" value="<?php echo $dmg['sl_6'];?>" maxLength="3"/>
          &nbsp;8'
          <input type="text" name="sl_8" value="<?php echo $dmg['sl_8'];?>" maxLength="3"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Current Glide Equipment in Store: </td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">Type: 
          <select name="gl_type">Top Shelf
   <option <?php if($dmg['gl_type']=='Top Shelf') echo "selected";?>>Top Shelf</option>
            <option <?php if($dmg['gl_type']=='RFC') echo "selected";?>>RFC</option>
            <option <?php if($dmg['gl_type']=='9 Deep') echo "selected";?>>9 Deep</option>
            <option <?php if($dmg['gl_type']=='None') echo "selected";?>>None</option>
            </select>
          &nbsp;Depth: 
          <select name="gl_depth">
            <option <?php if($dmg['gl_depth']=='4 Deep') echo "selected";?>>4 Deep</option>
            <option <?php if($dmg['gl_depth']=='5 Deep') echo "selected";?>>5 Deep</option>
            <option <?php if($dmg['gl_depth']=='9 Deep') echo "selected";?>>9 Deep</option>
            </select>
          &nbsp;Molding Color: 
          <select name="gl_mld_clr">
            <option <?php if($dmg['gl_mld_clr']=='Silver') echo "selected";?>>Silver</option>
            <option <?php if($dmg['gl_mld_clr']=='Tan') echo "selected";?>>Tan</option>
            <option <?php if($dmg['gl_mld_clr']=='Chocolate') echo "selected";?>>Chocolate</option>
            </select></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"># Glide Equipment Used: </td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><select name="gld_eqp_used">
           <option <?php if($dmg['gld_eqp_used']=='Top Shelf') echo "selected";?>>Top Shelf</option>     
          <option <?php if($dmg['gld_eqp_used']=='RFC') echo "selected";?>>RFC</option>
          <option <?php if($dmg['gld_eqp_used']=='9 Deep') echo "selected";?>>9 Deep</option>
          <option <?php if($dmg['gld_eqp_used']=='None') echo "selected";?>>None</option>
                </select></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
    </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="260" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100" height="30" align="left" valign="top">Shasta/Whse: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input type="text" placeholder="Footage Information" maxlength="5" name="shasta_whse" value="<?php echo $dmg['shasta_whse'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input name="sh_wh_hl" type="radio" value="H"  <?php if($dmg['sh_wh_hl']=='H') echo 'checked' ;?> />
                    </label>
                    <br />
                    Low:
  <input name="sh_wh_hl" type="radio" value="L"  <?php if($dmg['sh_wh_hl']=='L') echo 'checked' ;?> />
  <br />
  <br />
  <br /></td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"># of Shelves </td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">
                    <select name="sh_wh_num_shelf">
                        <option valu="">None</option>   
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['sh_wh_num_shelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
              </select>              </tr>
          </table></td>
          <td width="10" align="left" valign="top">&nbsp;</td>
          <td width="350" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="150" height="30" align="left" valign="top">Bulk/24 Pack CSD : </td>
              <td width="10" align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top"><input type="text" placeholder="Footage Information" maxlength="5"  name="bulk_24pk" value="<?php echo $dmg['bulk_24pk'];?>"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">High
                <label></label>
                  <label>
                  <input name="blk_24_hl" type="radio" value="H"  <?php if($dmg['blk_24_hl']=='H') echo 'checked' ;?>/>
                  </label>
                  <br />
                  Low:
  <input name="blk_24_hl" type="radio" value="L"  <?php if($dmg['blk_24_hl']=='L') echo 'checked' ;?>/>
  <br />
  <br />
  <br /></td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top"># of Shelves </td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top">
                  <select name="blk_24_numshelf">
                        <option value="">None</option>   
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['blk_24_numshelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
              </select>            </tr>
          </table></td>
          <td width="10" align="left" valign="top">&nbsp;</td>
          <td width="350" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="150" height="30" align="left" valign="top">Premium 24 Pack CSD: </td>
              <td width="10" align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top"><input type="text" name="prem_24_pack" placeholder="Footage Information" maxlength="5"  value="<?php echo $dmg['prem_24_pack'];?>"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">High
                <label></label>
                  <label>
                  <input name="prem_24_pack_hl" type="radio" value="H"  <?php if($dmg['prem_24_pack_hl']=='H') echo 'checked' ;?>/>
                  </label>
                  <br />
                  Low:
  <input name="prem_24_pack_hl" type="radio" value="L"  <?php if($dmg['prem_24_pack_hl']=='L') echo 'checked' ;?>/>
  <br />
  <br />
  <br /></td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top"># of Shelves </td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top">
   
                    <select name="prem_24_pack_numshelf">
                        <option value="">None</option>   
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['prem_24_pack_numshelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>              </td>
            </tr>
          </table></td>
          <td width="10" align="left" valign="top">&nbsp;</td>
          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="150" height="30" align="left" valign="top">&nbsp;</td>
              <td width="10" align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top"><br />
                <br />
                <br /></td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table>
      <br />
      <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="260" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100" height="30" align="left" valign="top">New&nbsp;Age:</td>
                <td width="10"  align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input type="text"  placeholder="Footage Information" maxlength="5" name="new_age" value="<?php echo $dmg['new_age'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input name="new_age_hl" type="radio" value="H"  <?php if($dmg['new_age_hl']=='H') echo 'checked' ;?>/>
                    <br />
                    </label>
                  Low:
  <input name="new_age_hl" type="radio" value="L"  <?php if($dmg['new_age_hl']=='L') echo 'checked' ;?>/>
  <br />
  <br />
  <br /></td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">#&nbsp;of&nbsp;Shelves: </td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">
                      <select name="new_age_nushelf">
                        <option value="">None</option>   
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['new_age_nushelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                        </select>                    </td>
              </tr>
          </table></td>
          <td width="10" align="left" valign="top">&nbsp;</td>
          <td width="350" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="150" height="30" align="left" valign="top">Bottled&nbsp;Juice: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input type="text"  placeholder="Footage Information" maxlength="5" name="botle_jc" value="<?php echo $dmg['botle_jc'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input name="botle_jc_hl" type="radio" value="H"  <?php if($dmg['botle_jc_hl']=='H') echo 'checked' ;?>/>
                    </label>
                    <br />
                    
                    Low:
  <input name="botle_jc_hl" type="radio" value="L"  <?php if($dmg['botle_jc_hl']=='L') echo 'checked' ;?>/>
  <br />
  <br />
  <br /></td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"># of Shelves: </td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">
                   <select name="botle_jc_numshelf">
                        <option value="">None</option>   
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['botle_jc_numshelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                        </select>            </td>
              </tr>
          </table></td>
          <td width="10" align="left" valign="top">&nbsp;</td>
          <td width="350" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="150" height="30" align="left" valign="top">Isoionics: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input type="text"  placeholder="Footage Information" maxlength="5" name="isionic" value="<?php echo $dmg['isionic'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input name="isionic_hl" type="radio" value="H"  <?php if($dmg['isionic_hl']=='H') echo 'checked' ;?>/>
                    </label>
                    <br />
                    Low:
  <input name="isionic_hl" type="radio" value="L"  <?php if($dmg['isionic_hl']=='L') echo 'checked' ;?>/>
  <br />
  <br />
  <br /></td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">#&nbsp;of&nbsp;Shelves: </td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">
                    <select name="isionic_numshelf">
                        <option value="">None</option>   
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['isionic_numshelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                        </select>               </td>
              </tr>
          </table></td>
          <td width="10" align="left" valign="top">&nbsp;</td>
          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="150" height="30" align="left" valign="top">Mix&nbsp;&nbsp;: </td>
              <td width="10" align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top"><input type="text"  placeholder="Footage Information" maxlength="5" name="mix" value="<?php echo $dmg['mix'];?>"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">High
                <label></label>
                  <label>
                  <input name="mix_hl" type="radio" value="H"  <?php if($dmg['mix_hl']=='H') echo 'checked' ;?>/>
                  </label>
                  <br />
                  Low:
  <input name="mix_hl" type="radio" value="L"  <?php if($dmg['mix_hl']=='L') echo 'checked' ;?>/>
  <br />
  <br />
  <br /></td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">#&nbsp;of&nbsp;Shelves: </td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top">
                        <select name="mix_numshelf">
                        <option value="">None</option>   
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['mix_numshelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                        </select>                 </td>
            </tr>
          </table></td>
        </tr>
      </table>
      <br />
      <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="260" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100" height="30" align="left" valign="top">P.E.T Water  : </td>

                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input type="text"  placeholder="Footage Information" maxlength="5" name="pet_water" value="<?php echo $dmg['pet_water'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label> 
                  <label>
                  <input name="pet_water_hl" type="radio" value="H"  <?php if($dmg['pet_water_hl']=='H') echo 'checked' ;?>/>
                  </label>
                  <br />
                  Low:                  
                  <input name="pet_water_hl" type="radio" value="L"  <?php if($dmg['pet_water_hl']=='L') echo 'checked' ;?>/>
                  <br />
                  <br />
                  <br /></td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"># of Shelves </td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">
                      <select name="pet_water_numshelf">
                        <option value="">None</option>   
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['pet_water_numshelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>
                </td>
              </tr>
          </table></td>
          <td width="10" align="left" valign="top">&nbsp;</td>
          <td width="350" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="150" height="30" align="left" valign="top">Bulk Water : </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input type="text"  placeholder="Footage Information" maxlength="5" name="bulk_water" value="<?php echo $dmg['bulk_water'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input name="bulk_water_hl" type="radio" value="H"  <?php if($dmg['bulk_water_hl']=='H') echo 'checked' ;?>/>
                    </label>
                    <br />
                    Low:
  <input name="bulk_water_hl" type="radio" value="L"  <?php if($dmg['bulk_water_hl']=='L') echo 'checked' ;?>/>
  <br />
  <br />
  <br /></td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"># of Shelves </td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">
                     <select name="bulk_water_numshelf">
                        <option value="">None</option>   
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['bulk_water_numshelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>
                </td>
              </tr>
          </table></td>
          <td width="10" align="left" valign="top">&nbsp;</td>
          <td width="350" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="150" height="30" align="left" valign="top">Case PK Water : </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input type="text"  placeholder="Footage Information" maxlength="5" name="case_pk" value="<?php echo $dmg['case_pk'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input name="case_pk_hl" type="radio" value="H"  <?php if($dmg['case_pk_hl']=='H') echo 'checked' ;?>/>
                    </label>
                    <br />
                    Low:
  <input name="case_pk_hl" type="radio" value="L"  <?php if($dmg['case_pk_hl']=='L') echo 'checked' ;?>/>
  <br />
  <br />
  <br /></td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"># of Shelves </td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">
                    <select name="case_pk_numshelf">
                        <option value="">None</option>   
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['case_pk_numshelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>
                </td>
              </tr>
          </table></td>
          <td width="10" align="left" valign="top">&nbsp;</td>
          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="150" height="30" align="left" valign="top">Sparkling Water: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input type="text"  placeholder="Footage Information" maxlength="5" name="spark_w" value="<?php echo $dmg['spark_w'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input name="spark_w_hl" type="radio" value="H"  <?php if($dmg['spark_w_hl']=='H') echo 'checked' ;?>/>
                    </label>
                    <br />
                    Low:
  <input name="spark_w_hl" type="radio" value="L"  <?php if($dmg['spark_w_hl']=='L') echo 'checked' ;?>/>
  <br />
  <br />
  <br /></td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top"># of Shelves </td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">
                 
                      <select name="spark_w_numshelf">
                        <option value="">None</option>   
              <?php for($i=1;$i<=10;$i++) {?>      
              <option <?php if($dmg['spark_w_numshelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>
                </td>
              </tr>
          </table></td>
        </tr>
      </table>
      <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="260"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="100" height="30" align="left" valign="top">Cold&nbsp;box : </td>
              <td width="10" align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top"><input type="text"  placeholder="Footage Information" maxlength="5" name="cold_box" value="<?php echo $dmg['cold_box'];?>"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">High
                <label></label>
                <label>
                <input name="cold_box_hl" type="radio" value="H"  <?php if($dmg['cold_box_hl']=='H') echo 'checked' ;?>/>
                </label>
                <br />
Low:
<input name="cold_box_hl" type="radio" value="L"  <?php if($dmg['cold_box_hl']=='L') echo 'checked' ;?>/>

                <br />
                <br /></td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top" colspan="3"># of new glide sheets installed:</td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top"><select name="cold_box_numshelf">
                        <option value="">None</option>   
              <?php for($i=1;$i<=50;$i++) {?>      
              <option <?php if($dmg['cold_box_numshelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>
              </td>
            </tr>
          </table></td>
          <td width="10">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top"><table width="101%" border="0" cellspacing="0" cellpadding="0">

              <tr>
                <td width="92" height="30" align="left" valign="top" ># Gliders Used: </td>
                <td width="4" height="30" align="left" valign="top">&nbsp;</td>
                <td width="161" height="30" align="left" valign="top">20 0z: 
                  <label>
                  <input type="text" name="oz_20" maxLength="3" value="<?php 
        echo $dmg['oz_20'] ; ?>"/>
                  </label></td>
                <td width="4" align="left" valign="top">&nbsp;</td>
                <td width="154" align="left" valign="top">1L:
                  <input type="text" name="ltr_1" maxLength="3" value="<?php 
        echo $dmg['ltr_1'] ; ?>"/></td>
                <td width="4" align="left" valign="top">&nbsp;</td>
                <td width="189" align="left" valign="top">10-12 0z :
                  <input type="text" name="oz_10_12" maxLength="3" value="<?php 
        echo $dmg['oz_10_12'] ; ?>" />
                </td>
                <td width="5" align="left" valign="top">&nbsp;</td>
                <td width="175" align="left" valign="top">32 0z:
                  <input type="text" name="oz_32" maxLength="3" value="<?php 
        echo $dmg['oz_32'] ; ?>" />
             </td>
                <td width="9" align="left" valign="top">&nbsp;</td>
                <td width="170" align="left" valign="top">2L:
                  <input type="text" name="ltr_2" maxLength="3" value="<?php 
        echo $dmg['ltr_2'] ; ?>" />
                </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="169" align="left" valign="top">Red Bull:
                  <input type="text" name="red_bull" maxLength="3" value="<?php 
        echo $dmg['red_bull'] ; ?>"/></td>
                </tr>
            </table>
            <br />
            <br />
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top"><label></label>
                  <input type="text" name="mngr_name" value="<?php echo $dmg['mngr_name'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                <td align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input type="text" name="mngr_storenum" value="<?php echo $dmg['mngr_storenum'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">Manager Signature: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">
                     <input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="proj_sign" id="proj_sign" 
              onchange="javascript:formFileUpload('proj_sign','I','proj_sign_img', 960,720);"/>
                </td>
                
        
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr> <td colspan="11" align="right">
                      <img width="100px" height="100px"  id="proj_sign_img_field" src="<?php echo  $image_dir.$dmg['mngr_sign'];?>"
                             onclick="PopEx(this, null,  null, 0, 0, 50,'PopBoxImageLarge');"/>
                      <input type="hidden" id="proj_sign_img" name="mngr_sign" value="<?php echo $dmg['mngr_sign'];?>"/>
                  </td></tr>
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
                         

            </table></td>
          <td width="10" align="left" valign="top">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>

 <?php require'footer_form.php'; ?>
</div>
<!--</body>
</html>-->
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