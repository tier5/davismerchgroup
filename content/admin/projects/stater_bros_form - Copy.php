<?php
require 'Application.php';

extract($_POST);
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num from stater_bros_form  as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num where pid='.$pid;
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
$form_type='staterbros';
?>
<style>
td > input{ vertical-align:top;}
#form td { vertical-align:middle;}



</style>
  <form id="sign_off_form" method="post" action="">
<input type="hidden" name="form_type" value="stat_bros" />  
  <input type="hidden" name="form_id" value="<?php if(isset($stat['stat_bros_id'])&&trim($stat['stat_bros_id'])!='') echo $stat['stat_bros_id'];?>" /> 
<div id="form">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top">
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
    <td colspan="4" align="center"><h3>Grocery Store Form</h3></td>  
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
            <td width="20"><select name="store_name"  onchange="javascript:getstorenum(2);" id="store_name_2">
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
            <td><select name="store_num" id="store_num_2"   onchange="javascript:get_contact(2);">
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
        <input type="text" name="other" value="<?php echo $stat['other']; ?> " />
        </td></tr>
      <tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="address" id="address_2" value="<?php echo $stat['address'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" id="city_2" name="city" value="<?php echo $stat['city'];?>"/></td>
      </tr>
    </table></td>
    <td width="19" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" width="346"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100" height="30" align="left" valign="top">Date: </td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><input type="text" name="date" class="date_field" value="<?php echo $stat['date'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Work&nbsp;Type&nbsp;:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><label>
          <select name="work_type">
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
  <br/><br/>      
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>
        <td height="30" align="left" valign="top" width="8%">Blitz:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input type="text" name="category" value="<?php echo $stat['category'];?>"/></td>

      </tr>
    </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
         
          <td width="139" height="34" align="left" valign="top">Repack in boxes :</td>
          <td width="45" align="left" valign="top"><select name="repack_box">
                  <option>N/A</option>
                      <?php for($i=1;$i<=40;$i++) {?>
                      <option <?php if($stat['repack_box']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
          <td width="107" align="left" valign="top">&nbsp;</td>
          <td width="126" align="left" valign="top">Repack in carts :</td>
          <td width="500" align="left" valign="top"><select name="repack_in">
                  <option>N/A</option>
            <?php for($i=1;$i<=40;$i++) {?>
            <option <?php if($stat['repack_in']==$i) echo "selected";?>><?php echo $i;?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
        
          <td height="30" align="left" valign="top">DC in boxes :</td>
          <td align="left" valign="top"><select name="dcin_box">
                  <option>N/A</option>
                      <?php for($i=1;$i<=40;$i++) {?>
                      <option <?php if($stat['dcin_box']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
          <td align="left" valign="top">&nbsp;</td>
          <td align="left" valign="top">DC in carts :</td>
          <td align="left" valign="top"><select name="dcin">
                  <option>N/A</option>
            <?php for($i=1;$i<=40;$i++) {?>
            <option <?php if($stat['dcin']==$i) echo "selected";?>><?php echo $i;?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
         
          <td height="33" align="left" valign="top">Out of code in boxes: </td>
          <td align="left" valign="top"><select name="out_code_box">
                  <option>N/A</option>
                      <?php for($i=1;$i<=40;$i++) {?>
                      <option <?php if($stat['out_code_box']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
          <td align="left" valign="top">&nbsp;</td>
          <td align="left" valign="top">Out of code in carts: </td>
          <td align="left" valign="top"><select name="out_of_code">
                  <option>N/A</option>
            <?php for($i=1;$i<=40;$i++) {?>
            <option <?php if($stat['out_of_code']==$i) echo "selected";?>><?php echo $i;?></option>
            <?php } ?>
          </select></td>
        </tr>
        <tr>
          <td height="30" colspan="3" align="left" valign="top">Repack - Dc palatized in backroom : </td>
          <td align="left" valign="top" width="126">Yes
            <input checked="checked" name="repck_dc_back" onchange="showNoOptMsg('1','Y');" type="radio" value="Y" <?php if($stat['repck_dc_back']=='Y') echo 'checked' ;?>/>
No
<input name="repck_dc_back" onchange="showNoOptMsg('1','N');" type="radio" value="N" <?php if($stat['repck_dc_back']=='N') echo 'checked' ;?>/></td>
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
        <br/>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
    
        <tr><td colspan="7"><table width="100%">
        <tr>
             <td width="47" align="left" valign="top" >CAT :</td>
         
          <td width="151" align="left" valign="top"><input type="text" name="cat_1" value="<?php echo $stat['cat_1'];?>"/></td>
                <td width="53" align="left" valign="top">File ID# :</td>
          
          <td width="150" align="left" valign="top"><input type="text" name="file_id" value="<?php echo $stat['file_id'];?>"/></td>
          <td width="69" align="left" valign="top">&nbsp;Footage:&nbsp;  </td>
          <td align="left" valign="top" width="150"><input maxlength="3" type="text"  name="footage" value="<?php echo $stat['footage'];?>"/></td>
          <td width="6" align="left" valign="top">&nbsp;</td>
       <td width="123" height="30" align="left" valign="middle">Section Completed:</td>
          <td width="102" height="30" align="left" valign="top">
            Yes
            <input name="sec_comp" onchange="showNoOptMsg('3','Y');" type="radio" value="Y" <?php if($stat['sec_comp']=='Y') echo 'checked' ;?>/>
No
<input name="sec_comp" onchange="showNoOptMsg('3','N');" type="radio" value="N" <?php if($stat['sec_comp']=='N') echo 'checked' ;?>/></td>
          <td width="261" align="left" valign="top"><div <?php if(!isset($stat['sec_comp'])||$stat['sec_comp']=='Y') echo 'style="display:none;"' ;?>  id="3"><?php echo $showNoOptMsg; ?></div></td>
        </tr>
        <tr>
            <td align="left" valign="top" >CAT :</td>
         
          <td align="left" valign="top" ><input type="text" name="cat_2" value="<?php echo $stat['cat_2'];?>"/></td>
         <td align="left" valign="top">File ID# :</td>
      
          <td align="left" valign="top"><input type="text" name="file_id2" value="<?php echo $stat['file_id2'];?>"/></td>
         
          <td align="left" valign="top">&nbsp;Footage:&nbsp;  </td>
          <td align="left" valign="top"><input type="text" maxlength="3" name="footage2" value="<?php echo $stat['footage2'];?>"/></td>
          <td align="left" valign="top">&nbsp;</td>
        
           <td height="30" align="left" valign="middle">Section Completed: </td>
        
          <td height="30" align="left" valign="top">Yes
            <input name="sec_comp2" onchange="showNoOptMsg('4','Y');" type="radio" value="Y" <?php if($stat['sec_comp2']=='Y') echo 'checked' ;?>/>
            No
            <input name="sec_comp2" onchange="showNoOptMsg('4','N');" type="radio" value="N" <?php if($stat['sec_comp2']=='N') echo 'checked' ;?>/></td>
          <td align="left" valign="top"><div <?php if(!isset($stat['sec_comp2'])||$stat['sec_comp2']=='Y') echo 'style="display:none;"' ;?>  id="4"><?php echo $showNoOptMsg; ?></div></td>
          
        </tr>
		<tr>
                    <td align="left" valign="top" >CAT :</td>
         <td align="left" valign="top" ><input type="text" name="cat_3" value="<?php echo $stat['cat_3'];?>"/></td>
      
                    <td align="left" valign="top">File ID# :</td>
       
          <td align="left" valign="top"><input type="text" name="file_id3" value="<?php echo $stat['file_id3'];?>"/></td>
          <td align="left" valign="top">&nbsp;Footage:&nbsp; </td>
          <td align="left" valign="top"><input type="text" maxlength="3" name="footage3" value="<?php echo $stat['footage3'];?>"/></td>
          <td align="left" valign="top">&nbsp;</td>
          <td height="30" align="left" valign="middle">Section Completed: </td>
          <td height="30" align="left" valign="top">Yes
            <input name="sec_comp3" onchange="showNoOptMsg('5','Y');" type="radio" value="Y" <?php if($stat['sec_comp3']=='Y') echo 'checked' ;?>/>
            No
            <input name="sec_comp3" onchange="showNoOptMsg('5','N');" type="radio" value="N" <?php if($stat['sec_comp3']=='N') echo 'checked' ;?>/></td>
          <td align="left" valign="top"><div <?php if(!isset($stat['sec_comp3'])||$stat['sec_comp3']=='Y') echo 'style="display:none;"' ;?>  id="5"><?php echo $showNoOptMsg; ?></div></td>
        </tr>
		<tr>
          <td align="left" valign="top" >CAT :</td>
         <td align="left" valign="top" ><input type="text" name="cat_4" value="<?php echo $stat['cat_4'];?>"/></td>
                     <td align="left" valign="top">File ID# :</td>
          <td align="left" valign="top"><input type="text" name="file_id4" value="<?php echo $stat['file_id4'];?>"/></td>
          
    
          <td align="left" valign="top">&nbsp;Footage:&nbsp;  </td>
          <td align="left" valign="top"><input type="text" maxlength="3" name="footage4" value="<?php echo $stat['footage4'];?>"/></td>
          <td align="left" valign="top">&nbsp;</td>
         
         <td height="30" align="left" valign="middle">Section Completed: </td>
    
          <td height="30" align="left" valign="top">Yes
            <input name="sec_comp4" onchange="showNoOptMsg('6','Y');" type="radio" value="Y" <?php if($stat['sec_comp4']=='Y') echo 'checked' ;?>/>
            No
            <input name="sec_comp4" onchange="showNoOptMsg('6','N');" type="radio" value="N" <?php if($stat['sec_comp4']=='N') echo 'checked' ;?>/></td>
          <td align="left" valign="top"><div <?php if(!isset($stat['sec_comp4'])||$stat['sec_comp4']=='Y') echo 'style="display:none;"' ;?>  id="6"><?php echo $showNoOptMsg; ?></div></td>
        </tr>
		<tr>
              <td align="left" valign="top" >CAT :</td>
         <td align="left" valign="top" ><input type="text" name="cat_5" value="<?php echo $stat['cat_5'];?>"/></td>
                    <td align="left" valign="top">File ID# :</td>

          <td align="left" valign="top"><input type="text" name="file_id5" value="<?php echo $stat['file_id5'];?>"/></td>
     
          <td align="left" valign="top">&nbsp;Footage:&nbsp;  </td>
          <td align="left" valign="top"><input type="text" maxlength="3" name="footage5" value="<?php echo $stat['footage5'];?>"/></td>
          <td align="left" valign="top">&nbsp;</td>
     
           <td height="30" align="left" valign="middle">Section Completed:</td>
 
          <td height="30" align="left" valign="top">Yes
            <input name="sec_comp5" onchange="showNoOptMsg('7','Y');" type="radio" value="Y" <?php if($stat['sec_comp5']=='Y') echo 'checked' ;?>/>
            No
            <input name="sec_comp5" onchange="showNoOptMsg('7','N');" type="radio" value="N" <?php if($stat['sec_comp5']=='N') echo 'checked' ;?>/></td>
          <td align="left" valign="top"><div <?php if(!isset($stat['sec_comp5'])||$stat['sec_comp5']=='Y') echo 'style="display:none;"' ;?>  id="7"><?php echo $showNoOptMsg; ?></div></td>
        </tr>
		<tr>
         <td align="left" valign="top" >CAT :</td>
         <td align="left" valign="top" ><input type="text" name="cat_6" value="<?php echo $stat['cat_6'];?>"/></td>
                    <td align="left" valign="top">File ID# :</td>
          <td align="left" valign="top"><input type="text" name="file_id6" value="<?php echo $stat['file_id6'];?>"/></td>
        
          <td align="left" valign="top">&nbsp;Footage:&nbsp;  </td>
          <td align="left" valign="top"><input maxlength="3" type="text" name="footage6" value="<?php echo $stat['footage6'];?>"/></td>
          <td align="left" valign="top">&nbsp;</td>
           <td height="30" align="left" valign="middle">Section Completed:</td>
          <td height="30" align="left" valign="top">Yes
            <input name="sec_comp6" onchange="showNoOptMsg('8','Y');" type="radio" value="Y" <?php if($stat['sec_comp6']=='Y') echo 'checked' ;?>/>
            No
            <input name="sec_comp6" onchange="showNoOptMsg('8','N');" type="radio" value="N" <?php if($stat['sec_comp6']=='N') echo 'checked' ;?>/></td>
          <td align="left" valign="top"><div <?php if(!isset($stat['sec_comp6'])||$stat['sec_comp6']=='Y') echo 'style="display:none;"' ;?>  id="8"><?php echo $showNoOptMsg; ?></div></td>
        </tr>
		<tr>
                      <td align="left" valign="top" >CAT :</td>
         <td align="left" valign="top" ><input type="text" name="cat_7" value="<?php echo $stat['cat_7'];?>"/></td>
                     <td align="left" valign="top">File ID# :</td>
          <td align="left" valign="top"><input type="text" name="file_id7" value="<?php echo $stat['file_id7'];?>"/></td>
     
          <td align="left" valign="top">&nbsp;Footage:&nbsp;  </td>
          <td align="left" valign="top"><input type="text" maxlength="3" name="footage7" value="<?php echo $stat['footage7'];?>"/></td>
          <td align="left" valign="top">&nbsp;</td>
  
         <td height="30" align="left" valign="middle">Section Completed:</td>
          <td height="30" align="left" valign="top">Yes
            <input name="sec_comp7" onchange="showNoOptMsg('9','Y');" type="radio" value="Y" <?php if($stat['sec_comp7']=='Y') echo 'checked' ;?>/>
            No
            <input name="sec_comp7" onchange="showNoOptMsg('9','N');" type="radio" value="N" <?php if($stat['sec_comp7']=='N') echo 'checked' ;?>/></td>
          <td align="left" valign="top"><div <?php if(!isset($stat['sec_comp7'])||$stat['sec_comp7']=='Y') echo 'style="display:none;"' ;?>  id="9"><?php echo $showNoOptMsg; ?></div></td>
        </tr>
		<tr>
                    <td align="left" valign="top" >CAT :</td>
         <td align="left" valign="top" ><input type="text" name="cat_8" value="<?php echo $stat['cat_8'];?>"/></td>
      
          
                     <td align="left" valign="top">File ID# :</td>
   
          <td align="left" valign="top"><input type="text" name="file_id8" value="<?php echo $stat['file_id8'];?>"/></td>
         
          <td align="left" valign="top">&nbsp;Footage:&nbsp;  </td>
          <td align="left" valign="top"><input type="text" maxlength="3" name="footage8" value="<?php echo $stat['footage8'];?>"/></td>
          <td align="left" valign="top">&nbsp;</td>
  
         <td height="30" align="left" valign="middle">Section Completed:</td>
        
          <td height="30" align="left" valign="top">Yes
            <input name="sec_comp8" onchange="showNoOptMsg('10','Y');" type="radio" value="Y" <?php if($stat['sec_comp8']=='Y') echo 'checked' ;?>/>
            No
            <input name="sec_comp8" onchange="showNoOptMsg('10','N');" type="radio" value="N" <?php if($stat['sec_comp8']=='N') echo 'checked' ;?>/></td>
          <td align="left" valign="top"><div <?php if(!isset($stat['sec_comp8'])||$stat['sec_comp8']=='Y') echo 'style="display:none;"' ;?>  id="10"><?php echo $showNoOptMsg; ?></div></td>
        </tr>
          </table></td></tr>
     
       
        <tr>
          <td  colspan="10" align="left" valign="top">
              <br/><br/>
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10%" height="30" align="left" valign="top"><label></label>
                  <input type="text" name="name_title" value="<?php echo $stat['name_title'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                 <td width="17%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input type="text" name="mngr_writ_store" value="<?php echo $stat['mngr_writ_store'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="12%" align="left" valign="top">Manager Signature: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top">
                           <input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="proj_sign" id="proj_sign" 
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
              
              
          </td>
        </tr>
      </table>
      <br />
      <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
             
            
              <tr>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td width="75" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
        
            </table></td>
          </tr>
      </table></td>
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
        
          <?php if(!isset($stat['store_name']) || $stat['store_name']==''){  ?>
           $('#store_name_2 option:eq(1)').attr('selected','selected').trigger('change');
           <?php } ?>
        </script>
</form>
