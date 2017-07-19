<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
$frito=array();
if(isset($form_id)&&$form_id!='')
{
    $query  ='select d.*,ch.sto_num  from frito_lay_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.number  where frito_id='.$form_id;
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
$proj_image=$frito['proj_image'];
$form_type='fritolay';
?>
 <script type="text/javascript">
var str_stat='<?php if(!isset($frito['store'])) echo 'yes'; ?>';     
 </script>  
<div id="demoWrapper">
<h3>FRITO LAY REST REPORT</h3>	
<form id="sign_off_form" method="post" action="./sign_off_submit1.php" class="bbq">
 <div id="fieldWrapper">   
 <input type="hidden" name="form_type" value="frito_lay" />  
   <input type="hidden" name="pid" value="<?php echo $pid;?>" />  
  <input type="hidden" name="form_id" value="<?php if(isset($frito['frito_id'])&&trim($frito['frito_id'])!='') echo $frito['frito_id'];?>" />  
<div id="form">
<span class="step" id="one">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
    <td colspan="4" align="center"></td>  
    <td><img alt="logo" src="<?php echo $mydirectory;?>/images/davis-wbg.png" /></td>
    </tr>
              <tr>
    <td colspan="5">&nbsp;</td>    
    </tr>
  <tr>
    <td width="621" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          
      <tr>
        <td width="114" height="30" align="left" valign="top">Store&nbsp;Name: </td>
        <td width="9" align="left" valign="top">&nbsp;</td>
        <td width="494" align="left" valign="top">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr>
                 <td><select class="required" name="store"  onchange="javascript:getstorenum(2);changeStoreValidation();" id="store_name_2" >
                         <option value="" ></option> 
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
                 <td><select class="required" name="number" id="store_num_2"   onchange="javascript:get_contact(2);">
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
        <input type="text" id="other_fld" name="other" value="<?php echo $frito['other']; ?> " />
        </td></tr>
         <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required" type="text" name="address" id="address_2" value="<?php echo $frito['address'];?>" /></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">City:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><input class="required" type="text" name="city" id="city_2" value="<?php echo $frito['city'];?>"/></td>
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
                    if($client[$i]['ID'] ==$frito['cid'])
                    echo ' selected="selected" ';
                                        echo '>' . $client[$i]['client'] . '</option>';
                                    
                                }
                                ?>
                            </select>         
        </td>
      </tr>
    </table></td>
    <td width="15" align="left" valign="top">&nbsp;</td>
    <td width="434" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="140" height="30" align="left" valign="top">Date: </td>
        <td width="16" align="left" valign="top">&nbsp;</td>
        <td width="278" align="left" valign="top"><input  type="text" name="date" class="date_field" value="<?php if($frito['date']!='')echo $frito['date'];else echo date('m/d/Y');?>"/></td>
      </tr>
      <tr>
        <td height="30" align="left" valign="top">Work&nbspType&nbsp:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top"><label>
          <select class="required" name="work_type">
              <option value="" ></option>  
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
</span> 
 <span class="step" id="two">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="30" colspan="11" class="current"><div align="center">CHIP AISLE FOOTAGE </div></td>
      </tr>    
        <tr>
          <td width="301" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="150" height="30" align="left" valign="top">Total Chip:</td>
              <td width="10" align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">
              <select class="required" name="tot_chp_foot">
                            <option value="" ></option>
                      <option value="N/A" <?php if(isset($frito['tot_chp_foot'])&&$frito['tot_chp_foot']=='N/A') echo ' selected'; ?>>N/A</option>       
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['tot_chp_foot']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select>
              </td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">Main Body Chip: </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">                 
   <select class="required" name="main_bdy_chp">
                            <option value="" ></option>
                      <option value="N/A" <?php if(isset($frito['main_bdy_chp'])&&$frito['main_bdy_chp']=='N/A') echo ' selected'; ?>>N/A</option>       
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['main_bdy_chp']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select>                
              </td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">On the Go: </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">           
  <select class="required" name="on_the_go">
                            <option value="" ></option>
                      <option value="N/A" <?php if(isset($frito['on_the_go'])&&$frito['on_the_go']=='N/A') echo ' selected'; ?>>N/A</option>       
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['on_the_go']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select>              
              </td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">Super Size: </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">
  <select class="required" name="sup_size">
                            <option value="" ></option>
                      <option value="N/A" <?php if(isset($frito['sup_size'])&&$frito['sup_size']=='N/A') echo ' selected'; ?>>N/A</option>       
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['sup_size']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select>              
              </td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">Cannister Potato: </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">
 <select class="required" name="can_pot">
                            <option value="" ></option>
                      <option value="N/A" <?php if(isset($frito['can_pot'])&&$frito['can_pot']=='N/A') echo ' selected'; ?>>N/A</option>       
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['can_pot']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select>                     
              </td>
            </tr>
            <tr>
              <td height="30" align="left" valign="top">Snack Mix: </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">
 <select class="required" name="snack_mix">
                            <option value="" ></option>
                      <option value="N/A" <?php if(isset($frito['snack_mix'])&&$frito['snack_mix']=='N/A') echo ' selected'; ?>>N/A</option>       
                      <?php for($i=1;$i<=100;$i++) {?>
                      <option <?php if($frito['snack_mix']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select>               
              </td>
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
                <input class="required"  name="set_cmplt" onchange="showNoOptMsg('1','Y');" type="radio" value="Y" <?php if($frito['set_cmplt']=='Y') echo 'checked' ;?>/>
                </label>
No
<input  class="required" name="set_cmplt" type="radio" onchange="showNoOptMsg('1','N');" value="N" <?php if($frito['set_cmplt']=='N') echo 'checked' ; else if($frito['set_cmplt']!='Y') echo 'checked' ;?>/></td>
              <td width="350" align="left" valign="top"><div <?php if(!isset($frito['set_cmplt'])||$frito['set_cmplt']=='Y') echo 'style="display:none;"' ;?>  id="1"><?php echo $showNoOptMsg; ?></div></td>
            </tr>
            <tr>
              <td height="30" align="left" >New Items Cut in? : </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">Yes
                <label>
                <input class="required"  name="cut_in" onchange="showNoOptMsg('2','Y');" type="radio" value="Y"  <?php if($frito['cut_in']=='Y') echo 'checked' ;?> />
                </label>
No
<input class="required"  name="cut_in" type="radio" onchange="showNoOptMsg('2','N');" value="N"  <?php if($frito['cut_in']=='N') echo 'checked' ;  else if($frito['cut_in']!='Y') echo 'checked' ;?>/></td>
              <td align="left" valign="top"><div <?php if(!isset($frito['cut_in'])||$frito['cut_in']=='Y') echo 'style="display:none;"' ;?>  id="2"><?php echo $showNoOptMsg; ?></div></td>
            </tr>
            <tr>
              <td height="30" align="left" >Section Cleaned? </td>
              <td align="left" valign="top">&nbsp;</td>
              <td align="left" valign="top">Yes
                <label>
                <input class="required" name="sec_clean" onchange="showNoOptMsg('3','Y');" type="radio" value="Y"  <?php if($frito['sec_clean']=='Y') echo 'checked' ;?>/>
                </label>
No
<input class="required"   name="sec_clean" type="radio" onchange="showNoOptMsg('3','N');" value="N"  <?php if($frito['sec_clean']=='N') echo 'checked' ;  else if($frito['sec_clean']!='Y') echo 'checked' ;?>/></td>
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
                <input class="required" name="dc_repack" onchange="showNoOptMsg('4','Y');" type="radio" value="Y"  <?php if($frito['dc_repack']=='Y') echo 'checked' ;?>/>
                </label>
No
<input class="required" name="dc_repack"  type="radio" onchange="showNoOptMsg('4','N');" value="N"  <?php if($frito['dc_repack']=='N') echo 'checked' ;  else if($frito['dc_repack']!='Y') echo 'checked' ;?> /></td>
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
 </span>  
     <span class="step" id="three">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="30" class="current"><div align="center">EQUIPMENT NEEDED </div></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table>
  <tr>                      
        <td width="80" height="30" align="left" valign="top">Shelving&nbsp;type</td>
              <td align="left" valign="top">  
 <select name="shelving_type_drop" class="required">
                  <option value="" ></option>     
              <option <?php if($frito['shelving_type_drop']=='Lozier') echo "selected";?>>Lozier</option>
              <option <?php if($frito['shelving_type_drop']=='Hussman') echo "selected";?>>Hussman</option>
              <option <?php if($frito['shelving_type_drop']=='Slater') echo "selected";?>>Slater</option>
              <option <?php if($frito['shelving_type_drop']=='Madix') echo "selected";?>>Madix</option>
              <option <?php if($frito['shelving_type_drop']=='Rails') echo "selected";?>>Rails</option>
            </select>              
              </td>  
           <td width="80">&nbsp;</td>           
                     <td width="60" height="30" align="left" valign="top">Shelf&nbsp;Color</td>
              <td align="left" valign="top">  
 <select name="shelf_color_drop" class="required">
                  <option value="" ></option>     
              <option <?php if($frito['shelf_color_drop']=='Gray') echo "selected";?>>Gray</option>
              <option <?php if($frito['shelf_color_drop']=='Chrome') echo "selected";?>>Chrome</option>
              <option <?php if($frito['shelf_color_drop']=='Beige') echo "selected";?>>Beige</option>
              <option <?php if($frito['shelf_color_drop']=='Slaters Beige') echo "selected";?>>Slaters Beige</option>
              <option <?php if($frito['shelf_color_drop']=='White') echo "selected";?>>White</option>
              <option <?php if($frito['shelf_color_drop']=='Chocolate') echo "selected";?>>Chocolate</option>
            </select>              
              </td> 
                     <td width="80">&nbsp;</td>       
                      <td width="30" height="30" align="left" valign="top">Depth</td>
              <td align="left" valign="top">  
 <select name="Depth_drop" class="required">
                  <option value="" ></option>     
              <option <?php if($frito['Depth_drop']=='16') echo "selected";?>>16</option>
              <option <?php if($frito['Depth_drop']=='18') echo "selected";?>>18</option>
              <option <?php if($frito['Depth_drop']=='19') echo "selected";?>>19</option>
              <option <?php if($frito['Depth_drop']=='22') echo "selected";?>>22</option>
            </select>              
              </td> 
  </tr>              
                    </table>             
                </td>            
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
                    <td><select class="required"  name="txt_1_5">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_1_5']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">2&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select class="required" name="txt_2">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_2']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">2.5                      </td>
                    <td>&nbsp;</td>
                    <td><select class="required"  name="txt_2_5">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_2_5']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">3&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select class="required" name="txt_3">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_3']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">3.5                      </td>
                    <td>&nbsp;</td>
                    <td><select class="required" name="txt_3_5">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_3_5']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">4&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select class="required" name="txt_4">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
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
                    <td><select class="required"  name="txt_1_5_s">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_1_5_s']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">2&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select class="required" name="txt_2_s">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_2_s']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">2.5                      </td>
                    <td>&nbsp;</td>
                    <td><select class="required" name="txt_2_5_s">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_2_5_s']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td height="30">3&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select class="required"  name="txt_2_s_sec">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_2_s_sec']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td>3.5                      </td>
                    <td>&nbsp;</td>
                    <td><select class="required" name="txt_3_5_s">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
                      <option <?php if($frito['txt_3_5_s']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td>4&nbsp;&nbsp;&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select class="required" name="txt_4_s">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=100;$i++) {?>
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
                    <td height="30"><select class="required" name="dp_brk_kit">
                            <option value="" ></option>  
                      <?php for($i=0;$i<=10;$i++) {?>
                      <option <?php if($frito['dp_brk_kit']==$i) echo "selected";?>><?php echo $i;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                </table></td>
              </tr>
            </table></table>
     </span>   
     <span class="step" id="four">
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10%" height="30" align="left" valign="top"><label></label>
                  <input class="required" type="text" name="print_name" value="<?php echo $frito['print_name'];?>"/></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                 <td width="17%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"><input class="required" type="text" name="store_mngr" value="<?php echo $frito['store_mngr'];?>"/></td>
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
    
                        

            </table>        
     </span> 
    <span id="five" class="step"> 
 <table width="90%"  id="glry_<?php echo $i; ?>">
    <tr><td height="5%">&nbsp;</td></tr>

    <tr><td align="left">Images:
         
  <input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="img_project" id="img_project" 
        <?php if(isset($form_id)&&$form_id>0){ ?>  onchange="javascript:signoffImgFileUpload('img_project','I','<?php echo $form_type;?>', 960,720,'<?php echo $form_id;?>');" 
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
 </div>
    <div id="demoNavigation"> 							
					<input class="navigation_button" id="back" value="Back" type="reset" />
					<input class="navigation_button" id="next" value="Next" type="submit" />
                                        <input class="navigation_button" id="Reset" value="Reset" type="button" onclick="javascript:resetSignOffForm();"/>
                                        <input class="navigation_button" id="sign_off_pdf_btn" value="Export to PDF" type="button" onclick="javascript:exportPDF('<?php echo $form_type;?>','<?php echo $form_id;?>');"/>
				</div>
    </form>
<?php require 'form_script.php'; ?>
