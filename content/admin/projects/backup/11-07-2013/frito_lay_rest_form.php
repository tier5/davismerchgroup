<?php
require 'Application.php';

extract($_POST);
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num  from frito_lay_form as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.number  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$frito = pg_fetch_array($result);

pg_free_result($result);
if(isset($frito['store'])&&$frito['store']!='')
{

$query = ("SELECT * from tbl_chainmanagement where sto_name=".$frito['store']);

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
$proj_image=$frito['proj_image'];
$form_type='fritolay';
?>
  <form id="sign_off_form" method="post" action="">
 <input type="hidden" name="form_type" value="frito_lay" />  
  <input type="hidden" name="form_id" value="<?php if(isset($frito['frito_id'])&&trim($frito['frito_id'])!='') echo $frito['frito_id'];?>" />  
<div id="form">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
    <td colspan="4" align="center"><h3>FRITO LAY REST REPORT</h3></td>  
    <td><img alt="logo" src="<?php echo $mydirectory;?>/images/davis-wbg.png" /></td>
    </tr>
              <tr>
    <td colspan="5">&nbsp;</td>    
    </tr>
  <tr>
    <td width="621" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          
      <tr>
        <td width="114" height="30" align="left" valign="top">Store&nbspName: </td>
        <td width="9" align="left" valign="top">&nbsp;</td>
        <td width="494" align="left" valign="top">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                 <td><select name="store"  onchange="javascript:getstorenum(2);" id="store_name_2" >
                   <option value="0" <?php
					 if(isset($frito['store']) && $frito['store']==0)
					 echo ' selected="selected" ';
                     ?>>Other</option>
                   <?php
			for ($i = 0; $i < count($store); $i++) {
    			echo '<option value="'.$store[$i]['ch_id'].'" ';
    				if (isset($frito['store']) && $frito['store'] == $store[$i]['ch_id'])
        			echo 'selected="selected" ';
    				echo '>' . $store[$i]['chain'] . '</option>';
				}
		?>
                 </select></td>
                 <td width="20">&nbsp;</td>
                 <td width="100">Store&nbsp;#: </td>
                 <td><select name="number" id="store_num_2"   onchange="javascript:get_contact(2);">
                   <?php
			for ($i = 0; $i < count($store_num); $i++) {
    			echo '<option value="'.$store_num[$i]['chain_id'].'" ';
    				if (isset($frito['number']) && $frito['number'] == $store_num[$i]['chain_id'])
        			echo 'selected="selected" ';
    				echo '>' . $store_num[$i]['sto_num'] . '</option>';
				}
		?>
                 </select></td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
               </tr>
             </table>
            <br/></td>
      </tr>
       <tr id="other_tr" <?php if($frito['store']!=0||$frito['store']=='') echo ' style="display:none;"'?>>
        <td>Other:</td><td>&nbsp;</td><td>
        <input type="text" name="other" value="<?php echo $frito['other']; ?> " />
        </td></tr>
         <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="address" id="address_2" value="<?php echo $frito['address'];?>" /></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="city" id="city_2" value="<?php echo $frito['city'];?>"/></td>
      </tr>
    </table></td>
    <td width="15" align="left" valign="top">&nbsp;</td>
    <td width="434" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="140" height="30" align="left" valign="top">Date: </td>
        <td width="16" align="left" valign="top">&nbsp;</td>
        <td width="278" align="left" valign="top"><input type="text" name="date" class="date_field" value="<?php echo $frito['date'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Work&nbspType&nbsp:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><label>
          <select name="work_type">
            <option <?php if($frito['work_type']=='Remodel') echo "selected";?>>Remodel</option>
            <option <?php if($frito['work_type']=='Reset') echo "selected";?>>Reset</option>
            <option <?php if($frito['work_type']=='New Store') echo "selected";?>>New Store</option>
            <option <?php if($frito['work_type']=='Survey') echo "selected";?>>Survey</option>
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
    <td width="76" align="left" valign="top">&nbsp;</td>
  </tr>
</table>


  <table><tr><td></td></tr></table> 
    

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="13%" height="30" align="left" valign="top">Repack in carts: </td>
        <td width="1%" height="30" align="left" valign="top">&nbsp;</td>
        <td width="13%" height="30" align="left" valign="top"><label>
          <input type="text"  name="repack_in" value="<?php echo $frito['repack_in'];?>"/>
        </label></td>
        <td width="1%" align="left" valign="top">&nbsp;</td>
        <td width="7%" align="left" valign="top">How Many: </td>
        <td width="13%" align="left" valign="top"><input type="text"  maxlength="3" name="how_many1" value="<?php echo $frito['how_many1'];?>"/></td>
        <td width="10%" align="left" valign="top">Repack in boxes :</td>
        <td width="4%" align="left" valign="top"><select name="repack_box">
          <?php for($i=1;$i<=40;$i++) {?>
          <option <?php if($frito['repack_box']==$i) echo "selected";?>><?php echo $i;?></option>
          <?php } ?>
        </select></td>
        <td width="35%" align="left" valign="top">&nbsp;</td>
        <td width="1%" align="left" valign="top">&nbsp;</td>
        <td width="2%" height="30" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">DC in carts: </td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text"  name="dcin" value="<?php echo $frito['dcin'];?>"/></td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">How Many: </td>
        <td align="left" valign="top"><input type="text"  maxlength="3" name="how_many2" value="<?php echo $frito['how_many2'];?>"/></td>
        <td align="left" valign="top">DC in boxes :</td>
        <td align="left" valign="top"><select name="dcin_box">
          <?php for($i=1;$i<=40;$i++) {?>
          <option <?php if($frito['dcin_box']==$i) echo "selected";?>><?php echo $i;?></option>
          <?php } ?>
        </select></td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Out of code in carts: </td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text"  name="out_of_code" value="<?php echo $frito['out_of_code'];?>"/></td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">How Many: </td>
        <td align="left" valign="top"><input type="text" maxlength="3" name="how_many3" value="<?php echo $frito['how_many3'];?>"/></td>
        <td align="left" valign="top">Out of code : </td>
        <td align="left" valign="top"><select name="out_code_box">
          <?php for($i=1;$i<=40;$i++) {?>
          <option <?php if($frito['out_code_box']==$i) echo "selected";?>><?php echo $i;?></option>
          <?php } ?>
        </select></td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
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
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" colspan="11" class="current"><div align="center">CHIP AISLE FOOTAGE </div></td>
      </tr>
    </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="301" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="150" height="30" align="left" valign="top">Total Chip:</td>
              <td width="10" align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top"><input type="text" maxlength="3" style="width:65px;" name="tot_chp_foot" value="<?php echo $frito['tot_chp_foot'];?>" placeholder="Footage" /></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">Main Body Chip: </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">
                  <input type="text" maxlength="3" style="width:65px;" name="main_bdy_chp" value="<?php echo $frito['main_bdy_chp'];?>" placeholder="Footage"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">On the Go: </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top"><input type="text" maxlength="3" style="width:65px;" name="on_the_go" value="<?php echo $frito['on_the_go'];?>" placeholder="Footage"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">Super Size: </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top"><input type="text" maxlength="3" style="width:65px;" name="sup_size" value="<?php echo $frito['sup_size'];?>" placeholder="Footage"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">Cannister Potato: </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top"><input type="text" maxlength="3" style="width:65px;" name="can_pot" value="<?php echo $frito['can_pot'];?>" placeholder="Footage"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">Snack Mix: </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top"><input type="text" maxlength="3" style="width:65px;" name="snack_mix" value="<?php echo $frito['snack_mix'];?>" placeholder="Footage"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
            </tr>
          </table></td>
          <td width="212">&nbsp;</td>
          <td width="643" align="left" valign="top"><table width="600" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="167" height="30" align="left" >Was Set Completed?</td>
              <td width="10" align="left" valign="top">&nbsp;</td>
              <td width="102" align="left" valign="top">Yes
                <label>
                <input checked="checked" name="set_cmplt" onchange="showNoOptMsg('1','Y');" type="radio" value="Y" <?php if($frito['set_cmplt']=='Y') echo 'checked' ;?>/>
                </label>
No
<input name="set_cmplt" type="radio" onchange="showNoOptMsg('1','N');" value="N" <?php if($frito['set_cmplt']=='N') echo 'checked' ;?>/></td>
              <td width="350" align="left" valign="top"><div <?php if(!isset($frito['set_cmplt'])||$frito['set_cmplt']=='Y') echo 'style="display:none;"' ;?>  id="1"><?php echo $showNoOptMsg; ?></div></td>
            </tr>
            <tr>
              <td height="30" align="left" >New Items Cut in? : </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">Yes
                <label>
                <input checked="checked" name="cut_in" onchange="showNoOptMsg('2','Y');" type="radio" value="Y"  <?php if($frito['cut_in']=='Y') echo 'checked' ;?> />
                </label>
No
<input name="cut_in" type="radio" onchange="showNoOptMsg('2','N');" value="N"  <?php if($frito['cut_in']=='N') echo 'checked' ;?>/></td>
              <td align="left" valign="top"><div <?php if(!isset($frito['cut_in'])||$frito['cut_in']=='Y') echo 'style="display:none;"' ;?>  id="2"><?php echo $showNoOptMsg; ?></div></td>
            </tr>
            <tr>
              <td height="30" align="left" >Section Cleaned? </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">Yes
                <label>
                <input checked="checked" name="sec_clean" onchange="showNoOptMsg('3','Y');" type="radio" value="Y"  <?php if($frito['sec_clean']=='Y') echo 'checked' ;?>/>
                </label>
No
<input name="sec_clean" type="radio" onchange="showNoOptMsg('3','N');" value="N"  <?php if($frito['sec_clean']=='N') echo 'checked' ;?>/></td>
              <td align="left" valign="top"><div <?php if(!isset($frito['sec_clean'])||$frito['sec_clean']=='Y') echo 'style="display:none;"' ;?>  id="3"><?php echo $showNoOptMsg; ?></div></td>
            </tr>
            <tr>
              <td height="30" align="left" >&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="left" >D/C Repack put in Boxes? </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">Yes
                <label>
                <input checked="checked" name="dc_repack" onchange="showNoOptMsg('4','Y');" type="radio" value="Y"  <?php if($frito['dc_repack']=='Y') echo 'checked' ;?>/>
                </label>
No
<input name="dc_repack" type="radio" onchange="showNoOptMsg('4','N');" value="N"  <?php if($frito['dc_repack']=='N') echo 'checked' ;?> /></td>
              <td align="left" valign="top"><div <?php if(!isset($frito['dc_repack'])||$frito['dc_repack']=='Y') echo 'style="display:none;"' ;?>  id="4"><?php echo $showNoOptMsg; ?></div></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
      </table>
      <p><br />
      </p>
      <p><br />
      </p>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="30" class="current"><div align="center">EQUIPMENT NEEDED </div></td>
            </tr>
            
          </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top">List racks installed in set:<br />
                  <br />
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="50" height="30">1.5                      </td>
                    <td width="10">&nbsp;</td>
                    <td><select name="txt_1_5">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_1_5']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">2&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select name="txt_2">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_2']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">2.5                      </td>
                    <td>&nbsp;</td>
                    <td><select name="txt_2_5">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_2_5']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">3&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select name="txt_3">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_3']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">3.5                      </td>
                    <td>&nbsp;</td>
                    <td><select name="txt_3_5">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_3_5']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">4&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select name="txt_4">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_4']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
           
                </table></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">Dip Shelves Used ?<br />
                  <br />
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="50" height="30">1.5                      </td>
                    <td width="10">&nbsp;</td>
                    <td><select name="txt_1_5_s">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_1_5_s']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">2&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select name="txt_2_s">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_2_s']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">2.5                      </td>
                    <td>&nbsp;</td>
                    <td><select name="txt_2_5_s">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_2_5_s']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">3&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select name="txt_2_s_sec">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_2_s_sec']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td>3.5                      </td>
                    <td>&nbsp;</td>
                    <td><select name="txt_3_5_s">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_3_5_s']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td>4&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select name="txt_4_s">
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_4_s']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                </table></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">Dip Breaker Kits :<br />
                  <br />
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30"><select name="dp_brk_kit">
                      <?php for($i=1;$i<=10;$i++) {?>
                      <option <?php if($frito['dp_brk_kit']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                </table></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30" align="left" valign="top"><br />                  <br /></td>
              </tr>
            </table>
            
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10%" height="30" align="left" valign="top"><label></label>
                  <input type="text" name="print_name" value="<?php echo $frito['print_name'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                 <td width="17%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input type="text" name="store_mngr" value="<?php echo $frito['store_mngr'];?>"/></td>
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
               <img width="100px" height="100px"  id="proj_sign_img_field" src="<?php echo  $image_dir.$frito['mngr_sign'];?>"
                      onclick="PopEx(this, null,  null, 0, 0, 50,'PopBoxImageLarge');"/>
                      <input type="hidden" id="proj_sign_img" name="mngr_sign" value="<?php echo $frito['mngr_sign'];?>"/>       
                      
                  </td></tr>
              <tr>
               <td colspan="11"><br/>Comments:</td>   
              </tr> 
              <tr> <td colspan="11"><textarea name="comments" cols="50" rows="10"><?php echo $frito['comments'];?></textarea></td></tr>
    
                        

            </table>     </td>
          </tr>
      </table>

<?php require'footer_form.php'; ?> 
<br />
<br />
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
        
        <?php if(!isset($frito['store']) || $frito['store']==''){  ?>
           $('#store_name_2 option:eq(1)').attr('selected','selected').trigger('change');
           <?php } ?>
        </script>
        
  </form>