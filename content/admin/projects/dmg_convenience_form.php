<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
$dmg=array();
if(isset($form_id)&&$form_id!='')
{
    $query  ='select d.*,ch.sto_num from dmg_convnc_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num where dmg_id='.$form_id;
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
$proj_image=$dmg['proj_image'];
}
$form_type='dmgconv';
?>
 <script type="text/javascript">
var str_stat='<?php if(!isset($dmg['store_name'])) echo 'yes'; ?>';     
 </script>  
<div id="demoWrapper">
			<h3>DMG Convenience/Drug</h3>		
<form id="sign_off_form" method="post" action="./sign_off_submit1.php" class="bbq">
<div id="fieldWrapper">
  <input type="hidden" name="form_type" value="dmg_conv" />   
   <input type="hidden" name="form_id" value="<?php echo $form_id;?>" />  
   <input type="hidden" name="pid" value="<?php echo $pid;?>" /> 
                    <div id="form">        
				<span class="step" id="first">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
    <td colspan="4" align="center"></td>  
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
                     <td><select  class="required" name="store_name"  onchange="javascript:getstorenum(2);changeStoreValidation();" id="store_name_2"  >
                        <option value="" ></option>        
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
                     <td><select class="required" name="store_num" id="store_num_2"   onchange="javascript:get_contact(2);">
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
        <input type="text" id="other_fld" name="other" value="<?php echo $dmg['other']; ?> " />
        </td></tr>
      <tr>
      <tr><td colspan="3">&nbsp;</td></tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required" type="text" name="address" id="address_2" value="<?php echo $dmg['address'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required" type="text" name="city" id="city_2" value="<?php echo $dmg['city'];?>"/></td>
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
                    if($client[$i]['ID'] ==$dmg['cid'])
                    echo ' selected="selected" ';
                                        echo '>' . $client[$i]['client'] . '</option>';
                                    
                                }
                                ?>
                            </select>         
        </td>
      </tr>
    </table></td>
    <td width="10" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100" height="30" align="left" valign="top">Date: </td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top"><input  type="text" name="date" class="date_field" value="<?php if($dmg['date']!='')echo $dmg['date'];else echo date('m/d/Y');?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Work&nbsp;Type&nbsp;:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><label>
          <select class="required" name="work_type">
               <option value="" ></option>   
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
				</span>
				<span id="finland" class="step">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="30" align="left" valign="top">Total Cold Vault Doors:
   <select name="tot_cld_door" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=25;$i++) {?>      
              <option <?php if($dmg['tot_cld_door']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>          
            
          </td>
        </tr>
      <tr>
        <td height="30" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="50" height="30" align="left" valign="top">CSD:</td>
            <td align="left" valign="top">
     <select name="csd" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['csd']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>             
               </td>
            <td width="75" align="left" valign="top">New Age:</td>
            <td align="left" valign="top">  
 <select name="new_age" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['new_age']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>            
                </td>
            <td width="75" align="left" valign="top">&nbsp;Energy:</td>
            <td align="left" valign="top">
   <select name="energy" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['energy']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>             
               </td>
            <td width="75" align="left" valign="top">Water:</td>
            <td align="left" valign="top">   
      <select name="water" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['water']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>         
               </td>
            <td width="75" align="left" valign="top">Dairy/Dell:</td>
            <td align="left" valign="top">
           <select name="dairy_dell" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['dairy_dell']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select> 
                </td>
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
            <td align="left" valign="top"> 
                <select name="csd_2" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['csd_2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select> 
              </td>
            <td width="75" align="left" valign="top">New Age:</td>
            <td align="left" valign="top">
    <select name="new_age2" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['new_age2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>       
                </td>
            <td width="75" align="left" valign="top">&nbsp;Energy:</td>
            <td align="left" valign="top">
       <select name="energy_2" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['energy_2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>       
               </td>
            <td width="75" align="left" valign="top">Water:</td>
            <td align="left" valign="top">
        <select name="water_2" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['water_2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>           
                </td>
            <td width="75" align="left" valign="top">Dairy/Dell:</td>
            <td align="left" valign="top">
           <select name="dairy_dell2" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['dairy_dell2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>       
                </td>
          </tr>
        </table></td>
        </tr>
        </table>
</table>
				</span>
				<span id="confirmation" class="step">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
            <td height="30" width="25%" align="left" >Width of CSD Doors Glide : </td>
            <td  align="left"><select class="required" name="csd_door_width">
                     <option value="" ></option>   
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
                <input  type="checkbox" name="check[dr_hnd_left]" value="checkbox" <?php 
            if($dmg['dr_hnd_left']=='t') echo 'checked' ;?>/>
                </label> 
                Right 
                <input  type="checkbox" name="check[dr_hnd_right]" value="checkbox" <?php 
            if($dmg['dr_hnd_right']=='t') echo 'checked' ;?>/></td>
              </tr>
              <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
              <td height="30" align="left" valign="top">Did the back of the glides get stickers </td>
              <td height="30" colspan="2" align="left" valign="top">Yes
                <label>
                <input class="required" name="sticker" type="radio" value="Y" <?php if($dmg['sticker']=='Y') echo 'checked' ;?>/>
                </label>
No
<input class="required" name="sticker" type="radio" value="N" <?php if($dmg['sticker']=='N') echo 'checked' ;?>/></td>
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
				</span>
   <span id="four" class="step">
     <table width="100%" border="0" cellspacing="0" cellpadding="0">     
  <tr>
      <td height="30" width="300">Were any new glide equipment installed: </td>
      <td><label>
        <input class="required" type="radio" onchange="miss_change('wa_qleq');" name="wa_qleq" id="wa_qleq_radio" value="Y" <?php if($dmg['wa_qleq']=='Y') echo 'checked' ;?>/>
      </label>
        Yes
  <input class="required" type="radio"  onchange="miss_change('wa_qleq');" name="wa_qleq" id="radio2" value="N" <?php if($dmg['wa_qleq']=='N') echo 'checked' ;?>/>
        No</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr> 
     </table>
   <table id="wa_qleq_sec" <?php if($dmg['wa_qleq']=='Y'){}else echo ' style="display:none;" '; ?>  width="100%" border="0" cellspacing="0" cellpadding="0">

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
                  <input  type="text" name="oz_10_12_txt" value="<?php echo $dmg['oz_10_12_txt'];?>" maxLength="3" style="width:60px;"/> 
                 </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top" width="75">32 0z:<br/>
                  <input  type="text" name="oz_32_txt" value="<?php echo $dmg['oz_32_txt'];?>" maxLength="3" style="width:60px;"/> 
             </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top" width="75">2L:
  <br/>
                  <input  type="text" name="ltr_2_txt" value="<?php echo $dmg['ltr_2_txt'];?>" maxLength="3" style="width:60px;"/> 
                 </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top" width="75">Red Bull:
       <br/>
                  <input  type="text" name="red_bull_txt" value="<?php echo $dmg['red_bull_txt'];?>" maxLength="3" style="width:60px;"/> 
                </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
            </table>        
   
   </span>  
                        
<span id="seven" class="step">
    <br/>
    <h4>Section Information</h4>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
              <td width="150" height="30" align="left" valign="top">CSD&nbsp;Section&nbsp;size&nbsp;(in&nbsp;feet):&nbsp;&nbsp;</td>
              <td align="left" valign="top">
              <input class="required" type="text"  maxLength="4" name="csd_sec_size" id="csd_sec_size" value="<?php echo $dmg['csd_sec_size'];?>"/>   
              </td>
            </tr>
            <tr>
               <td width="150" height="30" align="left" valign="top">Gondolas:&nbsp;&nbsp;</td>
              <td align="left" valign="top">
 <input class="required" type="radio" name="gondolas" id="gondolas_radio" value="H" <?php if($dmg['gondolas']=='H') echo 'checked' ;?>/>
High Profile
 <input class="required" type="radio"   name="gondolas" id="radio2" value="L" <?php if($dmg['gondolas']=='L') echo 'checked' ;?>/>
Low Profile
              </td>
              </tr> 
   <tr>
              <td width="150" height="30" align="left" valign="top">Number&nbsp;of&nbsp;Shelves:&nbsp;&nbsp;</td>
              <td align="left" valign="top">            
   <select name="csd_numshelf" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['csd_numshelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>            
              </td>
            </tr>
   <tr>
              <td width="150" height="30" align="left" valign="top">Mixer&nbsp;Section&nbsp;size&nbsp;(in&nbsp;feet):&nbsp;&nbsp;</td>
              <td align="left" valign="top">  
 <select name="mixer_sec_size" class="required">
                  <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if($dmg['mixer_sec_size']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>              
              </td>
            </tr>   
 <tr>
               <td width="150" height="30" align="left" valign="top">Gondolas:&nbsp;&nbsp;</td>
              <td align="left" valign="top">
 <input class="required" type="radio" name="gondolas2" id="gondolas2_radio" value="H" <?php if($dmg['gondolas2']=='H') echo 'checked' ;?>/>
High Profile
 <input class="required" type="radio"   name="gondolas2" id="radio2" value="L" <?php if($dmg['gondolas2']=='L') echo 'checked' ;?>/>
Low Profile
              </td>
              </tr>             
  </table>            
</span>       
                        <span id="five" class="step">
            
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10%" height="30" align="left" valign="top"><label></label>
                  <input class="required" type="text" name="mngr_name" value="<?php echo $dmg['mngr_name'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                 <td width="17%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input class="required" type="text" name="mngr_storenum" value="<?php echo $dmg['mngr_storenum'];?>"/></td>
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
                             </span>   
        <span id="six" class="step"> 
          <table width="90%"  id="glry_<?php echo $i; ?>">
    <tr><td height="5%">&nbsp;</td></tr>

    <tr><td align="left">Images:
         
  <input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="img_project" id="img_project" 
        <?php if(isset($form_id)&&$form_id>0){ ?>   onchange="javascript:signoffImgFileUpload('img_project','I','<?php echo $form_type;?>', 960,720,'<?php echo $form_id;?>');" 
         <?php }?>/>          
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
			</form>
			<hr />
			
			<p id="data"></p>
		</div>
    </div>
 <?php require 'form_script.php'; ?>