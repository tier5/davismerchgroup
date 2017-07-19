<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
$ralph=array();
if(isset($form_id)&&$form_id!='')
{
    $query  ='select d.*,ch.sto_num   from ralph_checklist_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where r_id='.$form_id;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$ralph = pg_fetch_array($result);

pg_free_result($result);
if(isset($ralph['store_name'])&&$ralph['store_name']!='')
{

$query = ("SELECT * from tbl_chainmanagement where sto_name=".$ralph['store_name']);
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
$query  ='select d.*,ch.sto_num   from missing_hardware'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where form_id='.$form_id.' and type=\'ralph_chk\'';
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$res = pg_fetch_array($result);

pg_free_result($result);

$query  ='select d.*,ch.sto_num   from ssr_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where form_id='.$form_id.' and d.type=\'ralph_chk\'';
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$ssr_form = pg_fetch_array($result);

pg_free_result($result);

$query = ("SELECT * from ssr_form_item where type='ralph_chk' and form_id=".$form_id." order by ss_it_id asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$ssr_item=array();
while ($row = pg_fetch_array($result)) {
    $ssr_item[] = $row;
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

$query  = ("SELECT \"employeeID\", firstname, lastname FROM \"employeeDB\" where active='yes' and (emp_type = 0 OR emp_type is null)  ORDER BY firstname ASC;");
//echo $query;
if (!($result = pg_query($connection, $query)))
{
    print("Failed employee query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result))
{
    $employee[] = $row;
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

$form_type="ralphchkl";
$proj_image=$ralph['proj_image'];
?>
<script type="text/javascript">
var str_stat='<?php if(!isset($ralph['store_name'])) echo 'yes'; ?>';     
 </script> 
<div id="demoWrapper">
<h3>Ralphs Daily Checklist</h3>	
<form id="sign_off_form" method="post" action="./sign_off_submit1.php" class="bbq">
 <div id="fieldWrapper">  
<input type="hidden" name="form_type" value="ralph_checklist" />  
  <input type="hidden" name="form_id" value="<?php if(isset($ralph['r_id'])&&trim($ralph['r_id'])!='') echo $ralph['r_id'];?>" />  
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
					 if(isset($ralph['store_name']) && $ralph['store_name']==0)
					 echo ' selected="selected" ';
                     ?>>Other</option>
                       <?php
			for ($i = 0; $i < count($store); $i++) {
    			echo '<option value="'.$store[$i]['ch_id'].'" ';
    				if (isset($ralph['store_name']) && $ralph['store_name'] == $store[$i]['ch_id'])
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
    				if (isset($ralph['store_num']) && $ralph['store_num'] == $store_num[$i]['chain_id'])
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
      <tr id="other_tr" <?php if($ralph['store_name']!=0||$ralph['store_name']=='') echo ' style="display:none;"'?>>
        <td>Other:</td><td>&nbsp;</td><td>
        <input id="other_fld" type="text" name="other" value="<?php echo $ralph['other']; ?> " />
        </td></tr>
      <tr>
      <tr><td colspan="3">&nbsp;</td></tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required" type="text" name="address" id="address_2" value="<?php echo $ralph['address'];?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required" type="text" name="city" id="city_2" value="<?php echo $ralph['city'];?>"/></td>
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
                    if($client[$i]['ID'] ==$ralph['cid'])
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
        <td align="left" valign="top"><input  type="text" name="date" class="date_field" value="<?php if($ralph['date']!='')echo $ralph['date'];else echo date('m/d/Y');?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Work&nbsp;Type&nbsp;:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><label>
          <select class="required" name="work_type">
               <option value="" ></option>   
            <option <?php if($ralph['work_type']=='Remodel') echo "selected";?>>Remodel</option>
            <option <?php if($ralph['work_type']=='Reset') echo "selected";?>>Reset</option>
            <option <?php if($ralph['work_type']=='New Store') echo "selected";?>>New Store</option>
            <option <?php if($ralph['work_type']=='Survey') echo "selected";?>>Survey</option>
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

 
  
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
    <td width="100" height="30">Merchandiser Name:&nbsp;</td>
    <td width="10px">&nbsp;</td>
    <td width="250">
  <select class="required" name="merch" id="merch" style="width: 200px;">
      <option value="" ></option>
                                <?php
                                
                                for ($i = 0; $i < count($employee); $i++)
                                {
                                   
                   echo '<option value="' . $employee[$i]['employeeID'] . '"';
                   if($employee[$i]['employeeID']==$ralph['merch'])
                   { echo ' selected="selected" ';
                       }
                   
                  echo '>' . $employee[$i]['firstname'] . ' ' . $employee[$i]['lastname'] . '</option>';
                                   
                                }
                                ?>
                            </select>  
    </td>
  </tr>
      <tr>
        <td height="30">Missing or damaged hardware: </td>
        <td>&nbsp;</td>
        <td><label>
          <input type="radio" class="required" name="miss_hard" onchange="miss_change('misrodamage');"  id="misrodamage_radio" value="Y" <?php if($ralph['miss_hard']=='Y') echo 'checked' ;?>/>
          </label>
          Yes
          <input type="radio" class="required" onchange="miss_change('misrodamage');" name="miss_hard" id="radio2" value="N" <?php if($ralph['miss_hard']=='N') echo 'checked' ;?>/>
          No</td>
        </tr>
  </table>
  <div id="misrodamage_sec" <?php if($ralph['miss_hard']=='Y'){}else echo ' style="display:none;" '; ?>>        
  <h3>Missing Hardware Survey</h3>        
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="mh[store_num]" id="textfield12" value="<?php echo $res['store_num'];?>"/></td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="mh[district]" id="textfield8" value="<?php echo $res['district'];?>" /></td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="mh[date]" id="textfield" value="<?php echo $res['date'];?>" /></td>
    </tr>
  </table>       
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">1.       IQF Baskets: </td>
      <td width="10">&nbsp;</td>
      <td>
      <select name="mh[iqf_basket]" >
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($res['iqf_basket'])&&$res['iqf_basket']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>      
</td>
      <td width="10">&nbsp;</td>
      <td>2.       POS Natural Signs:</td>
      <td width="10">&nbsp;</td>
      <td>
  <select name="mh[pos_nat_sign]" >
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($res['iqf_basket'])&&$res['iqf_basket']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>        
</td>
    </tr>
    <tr>
      <td height="30">3.       SG Trays: </td>
      <td>&nbsp;</td>
      <td>
     <select name="mh[sg_tray]" >
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($res['sg_tray'])&&$res['sg_tray']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>       
</td>
      <td>&nbsp;</td>
      <td>4.       Well Dividers:</td>
      <td>&nbsp;</td>
      <td>
  <select name="mh[well_div]" >
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($res['well_div'])&&$res['well_div']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>         
</td>
    </tr>
    <tr>
      <td height="30">5.       White Freezer Shelves: </td>
      <td>&nbsp;</td>
      <td>
       <select name="mh[white_freez]" >
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($res['white_freez'])&&$res['white_freez']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>        
</td>
      <td>&nbsp;</td>
      <td>6.       Black Freezer Shelves:</td>
      <td>&nbsp;</td>
      <td>
       <select name="mh[black_freez]" >
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($res['black_freez'])&&$res['black_freez']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>         
</td>
    </tr>
    <tr>
      <td height="30">7.       White Meat Case Shelves: </td>
      <td>&nbsp;</td>
      <td>
    <select name="mh[white_meat]" >
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($res['white_meat'])&&$res['white_meat']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>         
</td>
      <td>&nbsp;</td>
      <td>8.       Black Meat Case Shelves: </td>
      <td>&nbsp;</td>
      <td>
       <select name="mh[black_meat]" >
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($res['black_meat'])&&$res['black_meat']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>     
</td>
    </tr>
    <tr>
      <td height="30">9.       DCI Trays: </td>
      <td>&nbsp;</td>
      <td>
    <select name="mh[dci_tray]" >
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($res['dci_tray'])&&$res['dci_tray']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>        
</td>
      <td>&nbsp;</td>
      <td>10.   DCI Tray Size:</td>
      <td>&nbsp;</td>
      <td>
     <select name="mh[dci_tray_size]" >
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($res['dci_tray_size'])&&$res['dci_tray_size']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>       
</td>
    </tr>
    <tr>
      <td height="30">11.   Fencing:</td>
      <td>&nbsp;</td>
      <td>
     <select name="mh[fencing]" >
        <option value="">--select--</option>
        <?php for($i=0;$i<=100;$i++){?>
         <option <?php if(isset($res['fencing'])&&$res['fencing']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>       
</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>

  </table>         
          </div>         
 <table width="100%" border="0" cellspacing="0" cellpadding="0">         
      <tr>
        <td height="30" width="420px">Mapping Form: </td>
        <td>&nbsp;</td>
        <td><label>
          <input type="radio" class="required" name="map_form" id="radio" value="Y" <?php if($ralph['map_form']=='Y') echo 'checked' ;?>/>
          </label>
          Yes
          <input type="radio" class="required" name="map_form" id="radio2" value="N" <?php if($ralph['map_form']=='N') echo 'checked' ;?>/>
          No</td>
        </tr>
      <tr>
        <td height="30">SSR Form: </td>
        <td>&nbsp;</td>
        <td><label>
          <input type="radio" class="required" name="ssr_form"  onchange="miss_change('ssr_frm');" id="ssr_frm_radio" value="Y" <?php if($ralph['ssr_form']=='Y') echo 'checked' ;?>/>
          </label>
          Yes
          <input type="radio" class="required" name="ssr_form" onchange="miss_change('ssr_frm');" id="radio2" value="N" <?php if($ralph['ssr_form']=='N') echo 'checked' ;?>/>
          No</td>
        </tr>
 </table>
  <div id="ssr_frm_sec" <?php if($ralph['ssr_form']=='Y'){}else echo ' style="display:none;" '; ?>>
  <h3>SSR Form</h3>             
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="ssr[store_num]"  value="<?php echo $ssr_form['store_num'];?>"/></td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="ssr[dist]"  value="<?php echo $ssr_form['dist'];?>"/></td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" name="ssr[date]" value="<?php echo $ssr_form['date'];?>"/></td>
    </tr>
  </table>
                         
  <table id="grid-new" width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
      <td height="30" class="grey-bg">STORE</td>
      <td class="grey-bg">Commodity Code</td>
      <td class="grey-bg">Kroger Category Update</td>
      <td class="grey-bg">&nbsp;</td>
      </tr>
 <?php if(count($ssr_item)>0){  
  foreach($ssr_item as $i=>$ssr) {?>   
      <tr id="<?php echo 'ssr_'.$ssr['ss_it_id'];?>">
      <td height="30" class="white-bg">
        <input type="hidden" name="ssr[hdn_ssid][<?php echo $i;?>]" value="<?php echo $ssr['ss_it_id'];?>"/>  
        <input name="ssr[store][<?php echo $i;?>]" type="text"  size="8" maxlength="8" value="<?php echo $ssr['store'];?>" />
      </td>
      <td class="white-bg">
        <input name="ssr[comm_code][<?php echo $i;?>]" type="test" size="10" maxlength="10" value="<?php echo $ssr['comm_code'];?>"/>
      </td>
      <td class="white-bg">
        <input name="ssr[krog_cat][<?php echo $i;?>]" type="text"  size="100" maxlength="100" value="<?php echo $ssr['krog_cat'];?>"/>
      </td>
      <td class="white-bg">
          <?php if($i==0){?>
          <img onclick="javascript:signoff_addNewStore();" src="<?php echo $mydirectory;?>/images/add2.png" width="32" alt="add" />
          <?php }else{?>
  <img onclick="javascript:signoff_DeleteStore(<?php echo $ssr['ss_it_id'];?>,'ssr');"  src="<?php echo $mydirectory;?>/images/delete.png" width="32" alt="add" />        
          <?php }?>
      </td>
    </tr>    
  <?php }}else{?> 
    <tr>
      <td height="30" class="white-bg">
        <input name="ssr[store][]" type="text" id="textfield2" size="8" maxlength="8" />
      </td>
      <td class="white-bg">
        <input name="ssr[comm_code][]" type="test" id="textfield3" size="10" maxlength="10" />
      </td>
      <td class="white-bg">
        <input name="ssr[krog_cat][]" type="text" id="textfield4" size="100" maxlength="100" />
      </td>
      <td class="white-bg"><img onclick="javascript:signoff_addNewStore();" src="<?php echo $mydirectory;?>/images/add2.png" width="32" alt="add" /></td>
    </tr>
<?php }?>
  </table>              
          </div>         
  <table width="100%" border="0" cellspacing="0" cellpadding="0">           
      <tr>
        <td height="30" width="420px">New IQF Baskets (5 shelf): &nbsp;</td>
        <td>&nbsp;</td>
        <td><label>
          <input type="radio" class="required" name="iqf_basket" id="radio" value="Y" <?php if($ralph['iqf_basket']=='Y') echo 'checked' ;?>/>
          </label>
          Yes
          <input type="radio" class="required" name="iqf_basket" id="radio2" value="N" <?php if($ralph['iqf_basket']=='N') echo 'checked' ;?>/>
          No</td>
        </tr>
      <tr>
        <td height="30">POS Signs: (selection yes/no)</td>
        <td>&nbsp;</td>
        <td><label>
          <input type="radio" class="required" name="pos_sign" onchange="miss_change('pop_sign');" id="pop_sign_radio"  value="Y" <?php if($ralph['pos_sign']=='Y') echo 'checked' ;?>/>
          </label>
          Yes
          <input type="radio" class="required" name="pos_sign" onchange="miss_change('pop_sign');" id="radio2" value="N" <?php if($ralph['pos_sign']=='N') echo 'checked' ;?>/>
          No</td>
        </tr>
    </table>
 <div id="pop_sign_sec" <?php if($ralph['pos_sign']=='Y'){}else echo ' style="display:none;" '; ?>>         
  <table width="90%"  >
    <tr><td height="5%">&nbsp;</td></tr>

    <tr><td align="left">Images:
         
  <input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="pop_imgs" id="pop_imgs" 
         <?php if(isset($form_id)&&$form_id>0){ ?>   
         
         onchange="javascript:signoffImgFileUpload_pop('pop_imgs','I','chk_p_imgs', 960,720,'<?php echo $form_id;?>');"
         <?php }?> />          
        </td><td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    
  <tr>
      <td width="20%">&nbsp;</td>
      <td width="60%" ><div  align="left" id="img_cnt_signoff_pop">
      <?php 
         // echo "ff".$temp.'gbg';
$tmp=$form_type;
 
  $form_type='chk_p_imgs';
   require 'signoff_view_imgs.php';
    echo $html;
    $form_type=$tmp;
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
 </div>      
      </span>     
   
      <span class="step" id="four"> 
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="300" height="30">Sections Reset to Schematic: </td>
      <td>&nbsp;</td>
      <td><label>
        <input class="required" type="radio" name="reset_schem" id="radio3" value="Y" <?php if($ralph['reset_schem']=='Y') echo 'checked' ;?>/>
        </label>
        Yes
        <input class="required" type="radio" name="reset_schem" id="radio4" value="N" <?php if($ralph['reset_schem']=='N') echo 'checked' ;?>/>
        No</td>
      </tr>
    <tr>
      <td height="30">If “No” Please explain why: </td>
      <td>&nbsp;</td>
      <td><textarea name="exp_why" id="textarea4" cols="45" rows="5"><?php echo $ralph['exp_why'];?></textarea></td>
      </tr>
    <tr>
      <td height="30">Smoke Fish set: </td>
      <td>&nbsp;</td>
      <td><label>
        <input class="required" type="radio" name="smoke_fish" id="radio3" value="Y" <?php if($ralph['smoke_fish']=='Y') echo 'checked' ;?>/>
        </label>
        Yes
        <input class="required" type="radio" name="smoke_fish" id="radio4" value="N" <?php if($ralph['smoke_fish']=='N') echo 'checked' ;?>/>
        No</td>
      </tr>
    <tr>
      <td height="30">What Kind?  &nbsp;</td>
      <td>&nbsp;</td>
      <td><textarea class="required" name="what_kind" id="textarea" cols="45" rows="5"><?php echo $ralph['what_kind'];?></textarea></td>
      </tr>

    
  </table>
  <table width="90%"  >
    <tr><td height="5%">&nbsp;</td></tr>

    <tr><td align="left">Picture (only if the store is not on the smoke fish list):
         
  <input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="smoke_imgs" id="smoke_imgs" 
         <?php if(isset($form_id)&&$form_id>0){ ?>   
         
         onchange="javascript:signoffImgFileUpload_pop('smoke_imgs','I','smoke_imgs', 960,720,'<?php echo $form_id;?>');"
         <?php }?> />          
        </td><td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    
  <tr>
      <td width="20%">&nbsp;</td>
      <td width="60%" ><div  align="left" id="img_cnt_signoff_smoke">
      <?php 
         // echo "ff".$temp.'gbg';
$tmp=$form_type;
 
  $form_type='smoke_imgs';
   require 'signoff_view_imgs.php';
    echo $html;
    $form_type=$tmp;
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
  
 <span class="step" id="five">  
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Ralphs#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" class="required" name="ralphs" value="<?php echo $ralph['ralphs'];?>"/></td>
      <td width="10">&nbsp;</td>
      <td>District:</td>
      <td width="10">&nbsp;</td>
      <td><input type="text" class="required" name="district"  value="<?php echo $ralph['district'];?>"/></td>
    </tr>
  </table>     
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10%" height="30" align="left" valign="top"><label></label>
                  <input class="required" type="text" name="name_title" value="<?php echo $ralph['name_title'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                 <td width="17%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input class="required" type="text" name="manager_storenum" value="<?php echo $ralph['manager_storenum'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="12%" align="left" valign="top">Manager Signature: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input  type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="proj_sign" id="proj_sign" 
              onchange="javascript:formFileUpload('proj_sign','I','proj_sign_img', 960,720);"/></td>
                
        
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr> <td colspan="11" align="right">
                      <img width="100px" height="100px"  id="proj_sign_img_field" src="<?php echo  $image_dir.$ralph['mngr_sign'];?>"
                            onclick="PopEx(this, null,  null, 0, 0, 50,'PopBoxImageLarge');"/>
                      <input  type="hidden" id="proj_sign_img" name="mngr_sign" value="<?php echo $ralph['mngr_sign'];?>"/></td></tr>
              <tr>
                 <td colspan="11">Comments:</td> 
              </tr>
              <tr> <td colspan="11"><textarea name="comments" cols="50" rows="10"><?php echo $ralph['comments'];?></textarea></td></tr>
         
          
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
         <?php }?>  />          
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

  
  