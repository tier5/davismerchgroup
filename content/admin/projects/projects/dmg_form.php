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
    $query  ='select d.*,ch.sto_num from dmg_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num where dmg_id='.$form_id;
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
$form_type='dmg';
?>
 <script type="text/javascript">
var str_stat='<?php if(!isset($dmg['store_name'])) echo 'yes'; ?>';     
 </script>    
<div id="demoWrapper">
			<h3>DMG Chain Form</h3>		
<form id="sign_off_form" method="post" action="./sign_off_submit1.php" class="bbq">
<div id="fieldWrapper">
<input type="hidden" name="form_type" value="dmg_form" />  
  <input type="hidden" name="form_id" value="<?php if(isset($dmg['dmg_id'])&&trim($dmg['dmg_id'])!='') echo $dmg['dmg_id'];?>" /> 
    <input type="hidden" name="pid" value="<?php echo $pid;?>" /> 
<div id="form">
    <span class="step" id="first">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
    <td colspan="4" align="center"></td> 
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
            <td><select name="store_name" class="required" onchange="javascript:getstorenum(2);changeStoreValidation();" id="store_name_2" >
                    <option value="" ></option>      
              <option value="0" <?php
					 if(isset($dmg['store_name']) && $dmg['store_name']==0)
					 echo ' selected="selected" ';
                     ?>>Other</option>
              <?php
			for ($i = 0; $i < count($store); $i++) {
    			echo '<option value="'.$store[$i]['ch_id'].'" ';
    				if ((isset($dmg['store_name'])) && $dmg['store_name'] == $store[$i]['ch_id'])
        			echo 'selected="selected" ';
    				echo '>' . $store[$i]['chain'] . '</option>';
				}
		?>
            </select></td>
            <td width="20">&nbsp;</td>
            <td width="100">Store&nbsp;#:</td>
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
        </table></td>
      </tr>
      <tr id="other_tr" <?php if($dmg['store_name']!=0||$dmg['store_name']=='') echo ' style="display:none;"'?>>
        <td>Other:</td><td>&nbsp;</td><td>
        <input id="other_fld" type="text" name="other" value="<?php echo $dmg['other']; ?> " />
        </td></tr>
      <tr><td colspan="3"></td></tr>
      <tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required"  type="text" id="address_2" name="address" value="<?php echo $dmg['address'];?>" /></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required"  type="text" id="city_2"  name="city" value="<?php echo $dmg['city'];?>"/></td>
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
        <td align="left" valign="top"><input type="text"   name="date" class="date_field" value="<?php if($dmg['date']!='')echo $dmg['date'];else echo date('m/d/Y');?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Work&nbsp;Type&nbsp;:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><label>
          <select class="required"  name="work_type">
              <option value=""></option>  
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
    </span>    
 <span class="step" id="two">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td width="220" height="30" align="left" valign="top">CSD:<input class="required" name="csd_spl_chk" onchange="csdCheckFunc('CSD');"  id="csd_chk" type="radio" value="CSD"  <?php if($dmg['csd_spl_chk']=='CSD') echo 'checked' ;?> />
  CSD Split Table:
  <input id="csd_chk2" class="required" name="csd_spl_chk" type="radio" onchange="csdCheckFunc('CSD_SPL');" value="CSD_SPL"  <?php if($dmg['csd_spl_chk']=='CSD_SPL') echo 'checked' ;?> />
    </td>
</tr>
      <tr id="csd_div" <?php  if($dmg['csd_spl_chk']=='CSD'){}else{ echo ' style="display:none;"';}?>>
        <td width="220" height="30" align="left" valign="top">CSD: </td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">
       <select  name="csd" >
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['csd'])&&$dmg['csd']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>     
</td>
      </tr>
      <tr id="csd_split_div" <?php  if($dmg['csd_spl_chk']=='CSD_SPL'){}else{ echo ' style="display:none;"';}?>>
        <td height="30" align="left" valign="top">CSD Split Table :</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">
    <select  name="csd_split1" >
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['csd_split1'])&&$dmg['csd_split1']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>           
          +
   <select  name="csd_split2" >
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['csd_split2'])&&$dmg['csd_split2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>           
</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top" class="required" >High
          <label></label>
            <label>
            <input class="required" name="h_l" type="radio" value="H" <?php if($dmg['h_l']=='H') echo 'checked' ;?>/>
            </label>
          Low:
  <input class="required" name="h_l" type="radio" value="L"  <?php if($dmg['h_l']=='L') echo 'checked' ;?>/></td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Number of shelves per section : </td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">
        <select name="num_shelf" class="required">
               <option value="" ></option>  
              <?php for($i=0;$i<=12;$i++) {?>      
              <option <?php if($dmg['num_shelf']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>
        </td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Shelving:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">Type:
          <select name="shell_type" class="required">
               <option value="" ></option>  
                <option <?php if($dmg['shell_type']=='Lozier') echo "selected";?>>Lozier</option>
                <option <?php if($dmg['shell_type']=='Hussman') echo "selected";?>>Hussman</option>
                <option <?php if($dmg['shell_type']=='Slater') echo "selected";?>>Slater</option>
                <option <?php if($dmg['shell_type']=='Madix') echo "selected";?>>Madix</option>
                <option <?php if($dmg['shell_type']=='Rails') echo "selected";?>>Rails</option>
            </select>
             &nbsp;Length:
             <select name="shell_foot" class="required">
                  <option value="" ></option>  
              <?php for($i=3;$i<=8;$i++) {
              if($i%2==0||$i==3)
              {?>      
              <option <?php if($dmg['shell_foot']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php }} ?>
            </select>
          
          
          &nbsp;Depth:
          <select name="shell_depth" class="required">
               <option value="" ></option>  
              <?php for($i=12;$i<=36;$i++) {?>      
              <option <?php if($dmg['shell_depth']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
            </select>Inches
         
          &nbsp;Color:
          <select name="shell_col" class="required">
               <option value="" ></option>  
            <option <option <?php if($dmg['shell_col']=='White') echo "selected";?>>White</option>>White</option>
            <option <?php if($dmg['shell_col']=='Chocolate') echo "selected";?>>Chocolate</option>
            <option <?php if($dmg['shell_col']=='Tan') echo "selected";?>>Tan</option>
            <option <?php if($dmg['shell_col']=='Grey') echo "selected";?>>Grey</option>
          </select>
          &nbsp;Molding Color:
          <select name="shell_mld_col" class="required">
               <option value="" ></option>  
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
     <select  name="sl_3" class="required">
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['sl_3'])&&$dmg['sl_3']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>          
          &nbsp;4'
    <select  name="sl_4" class="required">
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['sl_4'])&&$dmg['sl_4']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>          
          &nbsp;6'
     <select  name="sl_6" class="required">
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['sl_6'])&&$dmg['sl_6']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>           
          &nbsp;8'
    <select  name="sl_8" class="required">
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['sl_8'])&&$dmg['sl_8']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>           
       </td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Current Glide Equipment in Store: </td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">Type: 
          <select name="gl_type" class="required"> <option value="" ></option>  
   <option <?php if($dmg['gl_type']=='Top Shelf') echo "selected";?>>Top Shelf</option>
            <option <?php if($dmg['gl_type']=='RFC') echo "selected";?>>RFC</option>
            <option <?php if($dmg['gl_type']=='9 Deep') echo "selected";?>>9 Deep</option>
            <option <?php if($dmg['gl_type']=='None') echo "selected";?>>None</option>
            </select>
          &nbsp;Depth: 
          <select name="gl_depth" class="required">
               <option value="" ></option>  
            <option <?php if($dmg['gl_depth']=='4 Deep') echo "selected";?>>4 Deep</option>
            <option <?php if($dmg['gl_depth']=='5 Deep') echo "selected";?>>5 Deep</option>
            <option <?php if($dmg['gl_depth']=='9 Deep') echo "selected";?>>9 Deep</option>
            </select>
          &nbsp;Molding Color: 
          <select name="gl_mld_clr" class="required">
               <option value="" ></option>  
            <option <?php if($dmg['gl_mld_clr']=='Silver') echo "selected";?>>Silver</option>
            <option <?php if($dmg['gl_mld_clr']=='Tan') echo "selected";?>>Tan</option>
            <option <?php if($dmg['gl_mld_clr']=='Chocolate') echo "selected";?>>Chocolate</option>
            </select></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top"># Glide Equipment Used: </td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">
         <select class="required" name="gld_eqp_used" id="gld_eqp_used" class="required">
        <option value="">--select--</option>
        <option <?php if(isset($dmg['gld_eqp_used'])&&$dmg['gld_eqp_used']=='NA') echo ' selected ';?> value="NA">NA</option>
        <?php for($i=1;$i<=20;$i++){?>
         <option <?php if(isset($dmg['gld_eqp_used'])&&$dmg['gld_eqp_used']==$i) echo ' selected ';?> value="<?php echo $i;?>"><?php echo $i;?></option> 
         <?php }?>
    </select>   
        </td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
      </tr>
    </table>   
  </span>      
    <span class="step" id="three">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="260" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="100" height="30" align="left" valign="top">Shasta/Whse: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input id="shasta_txt" type="text" placeholder="Footage Information" maxlength="5" name="shasta_whse" value="<?php echo $dmg['shasta_whse'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input id="shasta_rd1" name="sh_wh_hl" type="radio" value="H"  <?php if($dmg['sh_wh_hl']=='H') echo 'checked' ;?> />
                    </label>
                    <br />
                    Low:
  <input  name="sh_wh_hl" id="shasta_rd2" type="radio" value="L"  <?php if($dmg['sh_wh_hl']=='L') echo 'checked' ;?> />
       <br />
                    N/A:
  <input  name="sh_wh_hl" id="shasta_rd2" type="radio" value="na"  <?php if($dmg['sh_wh_hl']=='na') echo 'checked' ; else if($dmg['sh_wh_hl']!='H' && $dmg['sh_wh_hl']!='L') echo 'checked' ; ?> />
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
                    <select id="shasta_sel" class="required" name="sh_wh_num_shelf" onchange="dmgSelHideFields('shasta');">
                         <option value="" ></option>  
                        <option value="none" <?php if($dmg['sh_wh_num_shelf']=='none') echo "selected";?>>None</option>   
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
              <td align="left" valign="top"><input  id="blk24_txt" type="text" placeholder="Footage Information" maxlength="5"  name="bulk_24pk" value="<?php echo $dmg['bulk_24pk'];?>"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">High
                <label></label>
                  <label>
                  <input  name="blk_24_hl" type="radio" id="blk24_rd1" value="H"  <?php if($dmg['blk_24_hl']=='H') echo 'checked' ;?>/>
                  </label>
                  <br />
                  Low:
  <input  id="blk24_rd2" name="blk_24_hl" type="radio" value="L"  <?php if($dmg['blk_24_hl']=='L') echo 'checked' ;?>/>
  <br />
                    N/A:
  <input  name="blk_24_hl" id="blk_24_hl" type="radio" value="na"  <?php if($dmg['blk_24_hl']=='na') echo 'checked' ; else if($dmg['blk_24_hl']!='H' && $dmg['blk_24_hl']!='L') echo 'checked' ; ?> />
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
                  <select id="blk24_sel" class="required" name="blk_24_numshelf" onchange="dmgSelHideFields('blk24');">
                       <option value="" ></option>  
                         <option value="none" <?php if($dmg['blk_24_numshelf']=='none') echo "selected";?>>None</option>    
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
              <td align="left" valign="top"><input id="prem24_txt"  type="text" name="prem_24_pack" placeholder="Footage Information" maxlength="5"  value="<?php echo $dmg['prem_24_pack'];?>"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">High
                <label></label>
                  <label>
                  <input  id="prem24_rd1" name="prem_24_pack_hl" type="radio" value="H"  <?php if($dmg['prem_24_pack_hl']=='H') echo 'checked' ;?>/>
                  </label>
                  <br />
                  Low:
  <input  name="prem_24_pack_hl" id="prem24_rd2" type="radio" value="L"  <?php if($dmg['prem_24_pack_hl']=='L') echo 'checked' ;?>/>
  <br />
                    N/A:
  <input  name="prem_24_pack_hl" id="prem_24_pack_hl" type="radio" value="na"  <?php if($dmg['prem_24_pack_hl']=='na') echo 'checked' ; else if($dmg['prem_24_pack_hl']!='H' && $dmg['prem_24_pack_hl']!='L') echo 'checked' ; ?> />
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
   
                    <select class="required" id="prem24_sel" name="prem_24_pack_numshelf" onchange="dmgSelHideFields('prem24');">
                         <option value="" ></option>  
                         <option value="none" <?php if($dmg['prem_24_pack_numshelf']=='none') echo "selected";?>>None</option>   
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
                <td align="left" valign="top"><input  id="newage_txt"  type="text"  placeholder="Footage Information" maxlength="5" name="new_age" value="<?php echo $dmg['new_age'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input  name="new_age_hl" id="newage_rd1" type="radio" value="H"  <?php if($dmg['new_age_hl']=='H') echo 'checked' ;?>/>
                    <br />
                    </label>
                  Low:
  <input  name="new_age_hl" type="radio" id="newage_rd2" value="L"  <?php if($dmg['new_age_hl']=='L') echo 'checked' ;?>/>
  <br />
                    N/A:
  <input  name="new_age_hl" id="new_age_hl" type="radio" value="na"  <?php if($dmg['new_age_hl']=='na') echo 'checked' ; else if($dmg['new_age_hl']!='H' && $dmg['new_age_hl']!='L') echo 'checked' ; ?> />
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
                      <select class="required" id="newage_sel" name="new_age_nushelf" onchange="dmgSelHideFields('newage');">
                           <option value="" ></option>  
                         <option value="none" <?php if($dmg['new_age_nushelf']=='none') echo "selected";?>>None</option>    
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
                <td align="left" valign="top"><input id="bottlej_txt" type="text"  placeholder="Footage Information" maxlength="5" name="botle_jc" value="<?php echo $dmg['botle_jc'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input  name="botle_jc_hl" id="bottlej_rd1" type="radio" value="H"  <?php if($dmg['botle_jc_hl']=='H') echo 'checked' ;?>/>
                    </label>
                    <br />
                    
                    Low:
  <input  name="botle_jc_hl" type="radio" id="bottlej_rd2" value="L"  <?php if($dmg['botle_jc_hl']=='L') echo 'checked' ;?>/>
   <br />
                    N/A:
  <input  name="botle_jc_hl" id="botle_jc_hl" type="radio" value="na"  <?php if($dmg['botle_jc_hl']=='na') echo 'checked' ; else if($dmg['botle_jc_hl']!='H' && $dmg['botle_jc_hl']!='L') echo 'checked' ; ?> /> 
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
                   <select class="required" id="bottlej_sel" name="botle_jc_numshelf" onchange="dmgSelHideFields('bottlej');">
                        <option value="" ></option>  
                         <option value="none" <?php if($dmg['botle_jc_numshelf']=='none') echo "selected";?>>None</option>     
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
                <td align="left" valign="top"><input id="isonic_txt"  type="text"  placeholder="Footage Information" maxlength="5" name="isionic" value="<?php echo $dmg['isionic'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input  id="isonic_rd1" name="isionic_hl" type="radio" value="H"  <?php if($dmg['isionic_hl']=='H') echo 'checked' ;?>/>
                    </label>
                    <br />
                    Low:
  <input  name="isionic_hl" id="isonic_rd2" type="radio" value="L"  <?php if($dmg['isionic_hl']=='L') echo 'checked' ;?>/>
   <br />
                    N/A:
  <input  name="isionic_hl" id="isionic_hl" type="radio" value="na"  <?php if($dmg['isionic_hl']=='na') echo 'checked' ; else if($dmg['isionic_hl']!='H' && $dmg['isionic_hl']!='L') echo 'checked' ; ?> />   
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
                    <select class="required" id="isonic_sel" name="isionic_numshelf" onchange="dmgSelHideFields('isonic');">
                        <option value="none">None</option>  
                      <option value="none" <?php if($dmg['isionic_numshelf']=='none') echo "selected";?>>None</option>     
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
              <td align="left" valign="top"><input id="mix_txt"  type="text"  placeholder="Footage Information" maxlength="5" name="mix" value="<?php echo $dmg['mix'];?>"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">High
                <label></label>
                  <label>
                  <input  id="mix_rd1" name="mix_hl" type="radio" value="H"  <?php if($dmg['mix_hl']=='H') echo 'checked' ;?>/>
                  </label>
                  <br />
                  Low:
  <input name="mix_hl" type="radio" id="mix_rd2" value="L"  <?php if($dmg['mix_hl']=='L') echo 'checked' ;?>/>
     <br />
                    N/A:
  <input  name="mix_hl" id="mix_hl" type="radio" value="na"  <?php if($dmg['mix_hl']=='na') echo 'checked' ; else if($dmg['mix_hl']!='H' && $dmg['mix_hl']!='L') echo 'checked' ; ?> />  
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
                        <select id="mix_sel" class="required" name="mix_numshelf" onchange="dmgSelHideFields('mix');">
                             <option value="" ></option>  
                       <option value="none" <?php if($dmg['mix_numshelf']=='none') echo "selected";?>>None</option>   
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
                <td align="left" valign="top"><input id="pet_txt" type="text"  placeholder="Footage Information" maxlength="5" name="pet_water" value="<?php echo $dmg['pet_water'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label> 
                  <label>
                  <input  id="pet_rd1" name="pet_water_hl" type="radio" value="H"  <?php if($dmg['pet_water_hl']=='H') echo 'checked' ;?>/>
                  </label>
                  <br />
                  Low:                  
                  <input  id="pet_rd2" name="pet_water_hl" type="radio" value="L"  <?php if($dmg['pet_water_hl']=='L') echo 'checked' ;?>/>
      <br />
                    N/A:
  <input  name="pet_water_hl" id="pet_water_hl" type="radio" value="na"  <?php if($dmg['pet_water_hl']=='na') echo 'checked' ; else if($dmg['pet_water_hl']!='H' && $dmg['pet_water_hl']!='L') echo 'checked' ; ?> />                   
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
                      <select id="pet_sel" class="required" name="pet_water_numshelf" onchange="dmgSelHideFields('pet');">
                           <option value="" ></option>  
                       <option value="none" <?php if($dmg['pet_water_numshelf']=='none') echo "selected";?>>None</option>     
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
                <td align="left" valign="top"><input id="bulkw_txt" class="required" type="text"  placeholder="Footage Information" maxlength="5" name="bulk_water" value="<?php echo $dmg['bulk_water'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input  id="bulkw_rd1" name="bulk_water_hl" type="radio" value="H"  <?php if($dmg['bulk_water_hl']=='H') echo 'checked' ;?>/>
                    </label>
                    <br />
                    Low:
  <input  name="bulk_water_hl" id="bulkw_rd2" type="radio" value="L"  <?php if($dmg['bulk_water_hl']=='L') echo 'checked' ;?>/>
        <br />
                    N/A:
  <input  name="bulk_water_hl" id="bulk_water_hl" type="radio" value="na"  <?php if($dmg['bulk_water_hl']=='na') echo 'checked' ; else if($dmg['bulk_water_hl']!='H' && $dmg['bulk_water_hl']!='L') echo 'checked' ; ?> />  
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
                     <select id="bulkw_sel" class="required" name="bulk_water_numshelf" onchange="dmgSelHideFields('bulkw');">
                          <option value="" ></option>  
                         <option value="none" <?php if($dmg['bulk_water_numshelf']=='none') echo "selected";?>>None</option>     
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
                <td align="left" valign="top"><input id="casepk_txt" class="required" type="text"  placeholder="Footage Information" maxlength="5" name="case_pk" value="<?php echo $dmg['case_pk'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input id="casepk_rd1" name="case_pk_hl" type="radio" value="H"  <?php if($dmg['case_pk_hl']=='H') echo 'checked' ;?>/>
                    </label>
                    <br />
                    Low:
  <input id="casepk_rd2" name="case_pk_hl" type="radio" value="L"  <?php if($dmg['case_pk_hl']=='L') echo 'checked' ;?>/>
          <br />
                    N/A:
  <input  name="case_pk_hl" id="case_pk_hl" type="radio" value="na"  <?php if($dmg['case_pk_hl']=='na') echo 'checked' ; else if($dmg['case_pk_hl']!='H' && $dmg['case_pk_hl']!='L') echo 'checked' ; ?> /> 
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
                    <select id="casepk_sel" class="required" name="case_pk_numshelf" onchange="dmgSelHideFields('casepk');">
                         <option value="" ></option>  
                       <option value="none" <?php if($dmg['case_pk_numshelf']=='none') echo "selected";?>>None</option>   
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
                <td align="left" valign="top"><input id="sparkw_txt" type="text"  placeholder="Footage Information" maxlength="5" name="spark_w" value="<?php echo $dmg['spark_w'];?>"/></td>
              </tr>
              <tr>
                <td height="30" align="left" valign="top">High
                  <label></label>
                    <label>
                    <input id="sparkw_rd1" name="spark_w_hl" type="radio" value="H"  <?php if($dmg['spark_w_hl']=='H') echo 'checked' ;?>/>
                    </label>
                    <br />
                    Low:
  <input id="sparkw_rd2" name="spark_w_hl" type="radio" value="L"  <?php if($dmg['spark_w_hl']=='L') echo 'checked' ;?>/>
            <br />
                    N/A:
  <input  name="spark_w_hl" id="spark_w_hl" type="radio" value="na"  <?php if($dmg['spark_w_hl']=='na') echo 'checked' ; else if($dmg['spark_w_hl']!='H' && $dmg['spark_w_hl']!='L') echo 'checked' ; ?> /> 
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
                 
                      <select id="sparkw_sel" class="required" name="spark_w_numshelf" onchange="dmgSelHideFields('sparkw');">
                           <option value="" ></option>  
                      <option value="none" <?php if($dmg['spark_w_numshelf']=='none') echo "selected";?>>None</option>   
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
              <td align="left" valign="top"><input id="coldbox_txt" type="text"  placeholder="Footage Information" maxlength="5" name="cold_box" value="<?php echo $dmg['cold_box'];?>"/></td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">High
                <label></label>
                <label>
                <input id="coldbox_rd1" name="cold_box_hl" type="radio" value="H"  <?php if($dmg['cold_box_hl']=='H') echo 'checked' ;?>/>
                </label>
                <br />
Low:
<input id="coldbox_rd2" name="cold_box_hl" type="radio" value="L"  <?php if($dmg['cold_box_hl']=='L') echo 'checked' ;?>/>
            <br />
                    N/A:
  <input  name="cold_box_hl" id="cold_box_hl" type="radio" value="na"  <?php if($dmg['cold_box_hl']=='na') echo 'checked' ; else if($dmg['cold_box_hl']!='H' && $dmg['cold_box_hl']!='L') echo 'checked' ; ?> /> 

                <br />
                <br /></td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top" colspan="3"># of new glide sheets installed:</td>
              <td height="30" align="left" valign="top">&nbsp;</td>
              <td height="30" align="left" valign="top"><select id="coldbox_sel" onchange="chng_glide_used_default();dmgSelHideFields('coldbox');" class="required" name="cold_box_numshelf" id="cold_box_numshelf">
                       <option value="" ></option>  
                        <option value="none" <?php if($dmg['cold_box_numshelf']=='none') echo "selected";?>>None</option>      
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
    <table width="101%" border="0" cellspacing="0" cellpadding="0">

              <tr>
                <td width="92" height="30" align="left" valign="top" ># Gliders Used: </td>
                <td width="4" height="30" align="left" valign="top">&nbsp;</td>
                <td width="161" height="30" align="left" valign="top">20 0z: 
                  <label>
 <select  name="oz_20" id="oz_20" class="required">
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['oz_20'])&&$dmg['oz_20']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>                        
                  </label></td>
                <td width="4" align="left" valign="top">&nbsp;</td>
                <td width="154" align="left" valign="top">1L:
   <select  name="ltr_1" id="ltr_1" class="required">
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['ltr_1'])&&$dmg['ltr_1']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>                  
</td>
                <td width="4" align="left" valign="top">&nbsp;</td>
                <td width="189" align="left" valign="top">10-12 0z :
    <select  name="oz_10_12" id="oz_10_12" class="required">
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['oz_10_12'])&&$dmg['oz_10_12']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>                  
                </td>
                <td width="5" align="left" valign="top">&nbsp;</td>
                <td width="175" align="left" valign="top">32 0z:
     <select  name="oz_32" id="oz_32" class="required">
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['oz_32'])&&$dmg['oz_32']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>                     
             </td>
                <td width="9" align="left" valign="top">&nbsp;</td>
                <td width="170" align="left" valign="top">2L:
      <select  name="ltr_2" id="ltr_2" class="required">
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['ltr_2'])&&$dmg['ltr_2']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>                  
                </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="169" align="left" valign="top">Red Bull:
     <select  name="red_bull" id="red_bull" class="required">
                         <option value="" ></option>  
              <?php for($i=0;$i<=100;$i++) {?>      
              <option <?php if(isset($dmg['red_bull'])&&$dmg['red_bull']==$i) echo "selected";?>><?php echo $i;?></option>
             <?php } ?>
                  </select>                 
</td>
                </tr>
            </table>
    </span>
     <span class="step" id="four">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top"><label></label>
                  <input class="required" type="text" name="mngr_name" value="<?php echo $dmg['mngr_name'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                <td align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input type="text" class="required" name="mngr_storenum" value="<?php echo $dmg['mngr_storenum'];?>"/></td>
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
                         

            </table>       
      </span>
   <span id="five" class="step"> 
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
