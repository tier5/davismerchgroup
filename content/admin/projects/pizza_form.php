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
    $query  ='select d.*,ch.sto_num   from pizza_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pizza_id='.$form_id;
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

$form_type="pizza";
$proj_image=$pizza['proj_image'];
?>
<script type="text/javascript">
var str_stat='<?php if(!isset($pizza['store_name'])) echo 'yes'; ?>';     
 </script> 
<div id="demoWrapper">
<h3>Nestle DSD Sign Off</h3>	
<form id="sign_off_form" method="post" action="./sign_off_submit1.php" class="bbq">
 <div id="fieldWrapper">  
<input type="hidden" name="form_type" value="pizza" />  
  <input type="hidden" name="form_id" value="<?php if(isset($pizza['pizza_id'])&&trim($pizza['pizza_id'])!='') echo $pizza['pizza_id'];?>" />  
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
        <td width="100" height="30" align="left" valign="top">Store&nbsp;Name: </td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><select class="required" name="store_name" onchange="javascript:getstorenum(2);changeStoreValidation();" id="store_name_2" >
                            <option value="" ></option> 
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
                    <td><select class="required" name="store_num" id="store_num_2"   onchange="javascript:get_contact(2);">
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
        <input type="text" id="other_fld" name="other" value="<?php echo $pizza['other']; ?> " />
        </td></tr>
         <tr><td colspan="3"></td></tr>
      <tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required" type="text" name="address" id="address_2" value="<?php echo $pizza['address'];?>" /></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required" type="text" name="city" id="city_2" value="<?php echo $pizza['city'];?>"/></td>
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
                    if($client[$i]['ID'] ==$pizza['cid'])
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
        <td align="left" valign="top"><input  type="text" name="blit_date" class="date_field" value="<?php if($pizza['blit_date']!='')echo $pizza['blit_date'];else echo date('m/d/Y');?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Work&nbsp;Type&nbsp;:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><label>
          <select class="required" name="work_type">
               <option value="" ></option> 
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
</table>         
      </span>     
      <span class="step" id="two">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    
  
      <tr>
        <td height="30" align="left" valign="top"><table width="1200" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="489" height="30" align="left" valign="top">SET COMPLETED?</td>
            <td width="10" align="left" valign="top">&nbsp;</td>
            <td width="136" align="left" valign="top">Yes
              <input class="required" checked="checked" name="set_complete" onchange="showNoOptMsg('1','Y');" type="radio" value="Y" <?php if($pizza['set_complete']=='Y') echo 'checked' ;?> />
              No
              <input class="required" name="set_complete" type="radio" onchange="showNoOptMsg('1','N');" value="N" <?php if($pizza['set_complete']=='N') echo 'checked' ;?>/></td>
            <td width="502" align="left" valign="top"><div <?php if(!isset($pizza['set_complete'])||$pizza['set_complete']=='Y') echo 'style="display:none;"' ;?>  id="1"><?php echo $showNoOptMsg; ?></div></td>
            <td width="24" align="left" valign="top">&nbsp;</td>
            <td width="10" align="left" valign="top">&nbsp;</td>
            <td width="29" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top">NEW ITEMS CUT IN?</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">Yes
              <input class="required" checked="checked"  name="new_item_cut" onchange="showNoOptMsg('2','Y');" type="radio" value="Y" <?php if($pizza['new_item_cut']=='Y') echo 'checked' ;?> />
              No
              <input class="required" name="new_item_cut" onchange="showNoOptMsg('2','N');" type="radio" value="N" <?php if($pizza['new_item_cut']=='N') echo 'checked' ;?> /></td>
            <td align="left" valign="top"><div <?php if(!isset($pizza['new_item_cut'])||$pizza['new_item_cut']=='Y') echo 'style="display:none;"' ;?>  id="2"><?php echo $showNoOptMsg; ?></div></td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="left" valign="top">DC MARKED?</td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top" >Yes
              <input class="required" checked="checked"  name="dc_marked" type="radio" onchange="showNoOptMsg('3','Y');" value="Y" <?php if($pizza['dc_marked']=='Y') echo 'checked' ;?> />
              No
              <input class="required" name="dc_marked" type="radio" onchange="showNoOptMsg('3','N');" value="N" <?php if($pizza['dc_marked']=='N') echo 'checked' ;?> /></td>
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
            <td align="left" valign="top">
     <select class="required" name="cur_sz_set" id="cur_sz_set" class="required">
        <option value="">--select--</option>
        <option <?php if(isset($pizza['cur_sz_set'])&&$ralph['cur_sz_set']=='NA') echo ' selected ';?> value="NA">NA</option>
        <?php for($i=1;$i<=20;$i++){?>
         <option <?php if(isset($pizza['cur_sz_set'])&&$pizza['cur_sz_set']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>               
              (# of doors)&nbsp;</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top">Ice Cream Vault              </td>
            <td align="left" valign="top">
      <select class="required" name="ice_cream_vault" id="ice_cream_vault" class="required">
        <option value="">--select--</option>
        <option <?php if(isset($pizza['ice_cream_vault'])&&$ralph['ice_cream_vault']=='NA') echo ' selected ';?> value="NA">NA</option>
        <?php for($i=1;$i<=20;$i++){?>
         <option <?php if(isset($pizza['ice_cream_vault'])&&$pizza['ice_cream_vault']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>             
              (# of doors)&nbsp;</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top"><input  type="checkbox" name="walk_in_vault_front" value="checkbox" <?php 
            
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
            <td align="left" valign="top">
    <select class="required" name="froz_food_vault" id="froz_food_vault" class="required">
        <option value="">--select--</option>
        <option <?php if(isset($pizza['froz_food_vault'])&&$ralph['froz_food_vault']=='NA') echo ' selected ';?> value="NA">NA</option>
        <?php for($i=1;$i<=20;$i++){?>
         <option <?php if(isset($pizza['froz_food_vault'])&&$pizza['froz_food_vault']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>             
              (# of doors)&nbsp;</td>
            </tr>
          <tr>
            <td height="30" align="left" valign="top"><input  type="checkbox" name="walk_in_vault" value="checkbox" <?php 
            if($pizza['walk_in_vault']=='t') echo 'checked' ;?> />
              Walk in vault
              <input type="checkbox" name="front_load_cool" value="checkbox" <?php 
            if($pizza['front_load_cool']=='t') echo 'checked' ;?>/>
              Front load cooler</td>
            <td align="left" valign="top">&nbsp;</td>
            </tr>
<tr>
            <td height="30" align="left" valign="top">PIZZA DOORS</td>
            <td align="left" valign="top">
       <select class="required" name="pizza_door" id="pizza_door" class="required">
        <option value="">--select--</option>
        <option <?php if(isset($pizza['pizza_door'])&&$ralph['pizza_door']=='NA') echo ' selected ';?> value="NA">NA</option>
        <?php for($i=1;$i<=20;$i++){?>
         <option <?php if(isset($pizza['pizza_door'])&&$pizza['pizza_door']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>            
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
         </td>
          </tr>
           
          </table>       
      </span>    
  <span class="step" id="three"> 
  
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
                    <select class="required" name="man_coldbox">
                         <option value="" ></option> 
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
                  <td align="left" valign="top"><input class="required" type="text" name="model_num" value="<?php echo $pizza['model_num'];?>"/></td>
                  </tr>
                <tr>
                  <td height="30" align="left" valign="top">Any icecream shelves missing? &nbsp;</td>
                  <td height="30" align="left" valign="top">
                      <select class="required" name="shell_miss_ice">
                           <option value="" ></option> 
                         <option <?php if($pizza['shell_miss_ice']=='Yes') echo 'selected';?>>Yes</option>
                      <option <?php if($pizza['shell_miss_ice']=='No') echo 'selected';?>>No</option> 
                      </select>       
            &nbsp;How many
             <select class="required" name="ice_door" id="ice_door" class="required">
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($pizza['ice_door'])&&$pizza['ice_door']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>          
                 </td>
                  </tr>
                  
                  <tr>
                  <td height="30" align="left" valign="top">Any Frozen Food shelves missing? &nbsp;</td>
                  <td height="30" align="left" valign="top">
                      <select class="required" name="shell_miss_froz">
                           <option value="" ></option> 
                         <option <?php if($pizza['shell_miss_froz']=='Yes') echo 'selected';?>>Yes</option>
                      <option <?php if($pizza['shell_miss_froz']=='No') echo 'selected';?>>No</option> 
                      </select>       
            &nbsp;How many
      <select class="required" name="froz_door" id="froz_door" class="required">
        <option value="">--select--</option>      
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($pizza['froz_door'])&&$pizza['froz_door']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>               
 </td>
                  </tr>
                  <tr>
                    <td height="30" align="left" valign="top">Any Pizza  shelves missing? &nbsp;</td>
                    <td height="30" align="left" valign="top"><select class="required"  name="shell_miss_piz">
                             <option value="" ></option> 
                      <option <?php if($pizza['shell_miss_piz']=='Yes') echo 'selected';?>>Yes</option>
                      <option <?php if($pizza['shell_miss_piz']=='No ') echo 'selected';?>>No</option>
                    </select>
                      &nbsp;How many
        <select class="required" name="froz_door_piz" id="froz_door_piz" class="required">
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($pizza['froz_door_piz'])&&$pizza['froz_door_piz']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>                 
              </td>
                  </tr>
              
              </table></td>
            </tr>
            
          
            </table>    
    </span>  
 <span class="step" id="four">
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
                    <input class="required" checked="checked" name="new_schema" onchange="showNoOptMsg('8','Y');" type="radio" value="Y" <?php if($pizza['new_schema']=='Y') echo 'checked' ;?>/>
                    No
                    <input class="required" name="new_schema" onchange="showNoOptMsg('8','N');" type="radio" value="N" <?php if($pizza['new_schema']=='N') echo 'checked' ;?>/></td>
                  <td width="586" align="left" valign="top"><div <?php if(!isset($pizza['new_schema'])||$pizza['new_schema']=='Y') echo 'style="display:none;"' ;?> id="8"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td width="502" height="30" align="left" valign="top">DID THE TAGS GET REPLACED</td>
                  <td align="left" valign="top">Yes
                    <input class="required" checked="checked" name="tag_replace" onchange="showNoOptMsg('9','Y');" type="radio" value="Y" <?php if($pizza['tag_replace']=='Y') echo 'checked' ;?>/>
                    No
                    <input class="required" name="tag_replace" onchange="showNoOptMsg('9','N');" type="radio" value="N" <?php if($pizza['tag_replace']=='N') echo 'checked' ;?>/></td>
                  <td align="left" valign="top"><div <?php if(!isset($pizza['tag_replace'])||$pizza['tag_replace']=='Y') echo 'style="display:none;"' ;?> id="9"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td width="502" height="30" align="left" valign="top">WAS A COPY OF SCHEMATIC LEFT N THE CASE&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input class="required" checked="checked" name="copy_schema" onchange="showNoOptMsg('10','Y');" type="radio" value="Y" <?php if($pizza['copy_schema']=='Y') echo 'checked' ;?>/>
                    No
                    <input class="required" name="copy_schema" onchange="showNoOptMsg('10','N');" type="radio" value="N" <?php if($pizza['copy_schema']=='N') echo 'checked' ;?>/></td>
                  <td align="left" valign="top"><div <?php if(!isset($pizza['copy_schema'])||$pizza['copy_schema']=='Y') echo 'style="display:none;"' ;?> id="10"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td height="30" align="left" valign="top">DOES THE CASE GET ICED UP AT ALL?&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input class="required" checked="checked" name="case_ice" onchange="showNoOptMsg('11','Y');" type="radio" value="Y" <?php if($pizza['case_ice']=='Y') echo 'checked' ;?>/>
                    No
                    <input class="required" name="case_ice" onchange="showNoOptMsg('11','N');" type="radio" value="N" <?php if($pizza['case_ice']=='') echo 'checked' ; else if($pizza['case_ice']=='N') echo 'checked' ;?>/></td>
                  <td align="left" valign="top"><div <?php if(!isset($pizza['case_ice'])||$pizza['case_ice']=='Y') echo 'style="display:none;"' ;?> id="11"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td width="502" height="30" align="left" valign="top">IS THE TEMP OF THE CASE COLD ENOUGH FOR THE PRODUCT?&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input class="required" checked="checked" name="case_cold" onchange="showNoOptMsg('12','Y');" type="radio" value="Y" <?php if($pizza['case_cold']=='Y') echo 'checked' ;?>/>
                    No
                    <input class="required" name="case_cold" onchange="showNoOptMsg('12','N');" type="radio" value="N" <?php if($pizza['case_cold']=='N') echo 'checked' ;?>/></td>
                  <td align="left" valign="top"><div <?php if(!isset($pizza['case_cold'])||$pizza['case_cold']=='Y') echo 'style="display:none;"' ;?> id="12"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
                <tr>
                  <td height="30" align="left" valign="top">IS THERE A SECONDARY LOCATION OR DC/REPACK&nbsp;</td>
                  <td align="left" valign="top">Yes
                    <input class="required" checked="checked" name="sec_loc" onchange="showNoOptMsg('13','Y');" type="radio" value="Y" <?php if($pizza['sec_loc']=='Y') echo 'checked' ;?>/>
                    No
                    <input class="required" name="sec_loc" onchange="showNoOptMsg('13','N');" type="radio" value="no" <?php if($pizza['sec_loc']=='N') echo 'checked' ;?>/></td>
                  <td align="left" valign="top"><div <?php if(!isset($pizza['sec_loc'])||$pizza['sec_loc']=='Y') echo 'style="display:none;"' ;?> id="13"><?php echo $showNoOptMsg; ?></div></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td width="472" height="30" align="left" valign="top">IF NO TO ANY QUESTIONS PLEASE WRITE WHY IN COMMENTS:</td>
              <td width="684" align="left" valign="top">&nbsp;</td>
            </tr>
 </table>   
 </span>  
 <span class="step" id="five">   
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10%" height="30" align="left" valign="top"><label></label>
                  <input class="required" type="text" name="name_title" value="<?php echo $pizza['name_title'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                 <td width="17%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input class="required" type="text" name="manager_storenum" value="<?php echo $pizza['manager_storenum'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="12%" align="left" valign="top">Manager Signature: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input  type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="proj_sign" id="proj_sign" 
              onchange="javascript:formFileUpload('proj_sign','I','proj_sign_img', 960,720);"/></td>
                
        
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr> <td colspan="11" align="right">
                      <img width="100px" height="100px"  id="proj_sign_img_field" src="<?php echo  $image_dir.$pizza['mngr_sign'];?>"
                            onclick="PopEx(this, null,  null, 0, 0, 50,'PopBoxImageLarge');"/>
                      <input  type="hidden" id="proj_sign_img" name="mngr_sign" value="<?php echo $pizza['mngr_sign'];?>"/></td></tr>
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
  </span>   
   <span id="six" class="step"> 
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

  
  