<?php
require 'Application.php';
$ext='';
if(isset($_GET['close']) && $_GET['close'] == 1)
{
$ext='_closed';    
}
$pid=$_GET['pid'];
$form_id=$_GET['form_id'];

if(isset($pid)&&$pid!='')
{
  $query  ='select t.*,ch.chain,cl.client,chmg.sto_num from dmg_convnc_form'.$ext.' as t left join tbl_chain as ch on ch.ch_id::text=t.store_name'
       .' left join "clientDB" as cl on cl."ID"=t.cid '      
      .' left join tbl_chainmanagement as chmg on chmg.chain_id::text=t.store_num where t.dmg_id='.$form_id;   
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$dmg = pg_fetch_array($result);

pg_free_result($result);
$proj_image=$dmg['proj_image'];
}
$file_name='dmgconvc_form.pdf';
//echo 'dd'.$pid;
//print_r($dmg);



$html='<div id="form">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody>
          <tr>
    <td colspan="4" align="center"><h3>DMG Convenience/Drug</h3></td>
   <td> <img valign="top" src="../../images/davis-wbg.png" alt="logo">
    </td>    
    </tr>
              <tr>
    <td colspan="5">&nbsp;</td>    
    </tr>
  
<tr>
    <td width="60%" valign="top" align="left" ><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
    <td colspan="3"><table width="100%">
<tr>
        <td width="85" valign="top" align="left" height="30"><font size="12">Store Name:</font></td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="150"><font size="12">';
if($dmg['store_name']==0)
$html.=$dmg['other'];
else    
$html.=$dmg['chain'];
          $html.='</font></td>
              
<td width="53" valign="top" align="left" height="30"><font size="12">Store #:</font></td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="60"><font size="12">';
          $html.=$dmg['sto_num'] .'</font></td>
              <td width="82" valign="top" align="left" height="30"><font size="12">Work&nbsp;Type:</font></td>
        <td valign="top" align="left" width="100"><font size="12">';
          $html.=$dmg['work_type'] .'</font></td>
              </tr>
         
            </table> 

</td>
      </tr>
      <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <td valign="top" align="left" height="30" width="52">Address:</td>
        <td valign="top" align="left" height="30" width="60%">'.$dmg['address'].'</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="28">City:</td>
        <td valign="top" align="left" height="30" width="60%">'.$dmg['city'].'</td>
      </tr>
        <tr>
        <td valign="top" align="left" height="30" width="38">Client:</td>
        <td valign="top" align="left" height="30" width="60%">'.$dmg['client'].'</td>
      </tr>
    </tbody></table></td>
    <td width="20" valign="top" align="left">&nbsp;</td>
    <td valign="top" align="left" width="30%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td width="100" valign="top" align="left" height="30">&nbsp;</td>
        <td width="10" valign="top" align="left" width="5">&nbsp;</td>
        <td valign="top" align="right">'.$dmg['date'].'</td>
      </tr>
  
      <tr>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
      </tr>
    </tbody></table></td>
   <!-- <td width="10" valign="top" align="right">&nbsp;</td>-->
    <td valign="top" align="left">&nbsp;</td>
  </tr>
</tbody></table>


<br>
<br>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody><tr>
    <td valign="top" align="left" colspan="2"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td valign="top" align="left" height="30">Total Cold Vault Doors:&nbsp;&nbsp;'.$dmg['tot_cld_door'].           
          '</td>
        </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" width="40">CSD:&nbsp;</td>
            <td valign="top" align="left">'.$dmg['csd'].'</td>
            <td valign="top" align="left">New Age:</td>
            <td valign="top" align="left">'.$dmg['new_age'].'</td>
            <td valign="top" align="left" width="52">&nbsp;Energy:&nbsp;</td>
            <td valign="top" align="left">'.$dmg['energy'].'</td>
            <td valign="top" align="left" width="42">Water:&nbsp;</td>
            <td valign="top" align="left">'.$dmg['water'].'</td>
            <td valign="top" align="left">Dairy/Dell:</td>
            <td valign="top" align="left">'.$dmg['dairy_dell'].'</td>
          </tr>
        </tbody></table></td>
        </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" colspan="2"># Shelves in Doors: </td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30" width="40">CSD:&nbsp;</td>
            <td valign="top" align="left">'.$dmg['csd_2'].'</td>
            <td valign="top" align="left">New Age:</td>
            <td valign="top" align="left">'.$dmg['new_age2'].'</td>
            <td valign="top" align="left" width="52">&nbsp;Energy:&nbsp;</td>
            <td valign="top" align="left">'.$dmg['energy_2'].'</td>
            <td valign="top" align="left" width="42">Water:&nbsp;</td>
            <td valign="top" align="left">'.$dmg['water_2'].'</td>
            <td valign="top" align="left">Dairy/Dell:</td>
            <td valign="top" align="left">'.$dmg['dairy_dell2'].'</td>
          </tr>
        </tbody></table></td>
        </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td width="25%" valign="top" align="left" height="30">Width of CSD Doors Glide : </td>
            <td align="left">'.$dmg['csd_door_width'].'</td>
            </tr>
       
        </tbody></table>
          <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td width="185" valign="top" align="left" height="30">Door Handles as you face them:&nbsp;</td>
              <td valign="top" align="left"><label>';
$html.=($dmg['dr_hnd_left']=='t')? 'Left':'';
$html.='</label>';
$html.=($dmg['dr_hnd_right']=='t')? ' , Right':'';
$html.='</td>
              </tr>
            <tr>
              <td valign="top" width="215" align="left" height="30">Did the back of the glides get stickers:&nbsp;</td>
              <td valign="top" align="left" height="30" colspan="2"><label>';
$html.=($dmg['sticker']=='Y')? 'Yes':'No';
$html.='</label></td>
              </tr>
            <tr>
              <td valign="top" align="left" height="30">If No Please explain in comments: </td>
              <td valign="top" align="left" height="30" colspan="2">&nbsp;</td>
            </tr>
            
<tr>

              <td valign="top" align="left" height="30" colspan="3">'.$dmg['glide_comment'].'</td>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr><td colspan="2">&nbsp;</td></tr>
          </tbody></table></td>
        </tr>
     <tr>
              <td valign="top" width="230" align="left" height="30">Were any new glide equipment&nbsp;installed:&nbsp;</td>
              <td valign="top" align="left" height="30" colspan="2"><label>';
$html.=($dmg['wa_qleq']=='Y')? 'Yes':'No';
$html.='</label></td>
              </tr>      
    </tbody></table>
  <table width="100%" cellspacing="0" cellpadding="0" border="0" >
        <tbody><tr>
          <td valign="top" align="left">';
    if($dmg['wa_qleq']=='Y'){       
 $html.='<table width="100%" cellspacing="0" cellpadding="0" border="0">

              <tbody>
              
<tr><td colspan="6"># of New Glide Sheets Installed: </td></tr>
<tr>

                <td width="13%" valign="top" align="left" height="30">20 0z: ';

$html.='&nbsp;&nbsp;'.$dmg['oz_20_txt'].'</td>
                <td width="10" valign="top" align="left">&nbsp;</td>
                <td valign="top" align="left" width="13%">1L:';
$html.='&nbsp;&nbsp;'.$dmg['ltr_1_txt'].'</td>
                <td width="10" valign="top" align="left">&nbsp;</td>
                <td valign="top" align="left" width="15%">10-12 0z :';

$html.='&nbsp;&nbsp;'.$dmg['oz_10_12_txt'].'</td>
                <td width="10" valign="top" align="left">&nbsp;</td>
                <td valign="top" align="left" width="13%">32 0z:';
$html.='&nbsp;&nbsp;'.$dmg['oz_32_txt'].'</td>
                <td width="10" valign="top" align="left">&nbsp;</td>
                <td valign="top" align="left" width="13%">2L:';
$html.='&nbsp;&nbsp;'.$dmg['ltr_2_txt'].'</td>
                <td width="10" valign="top" align="left">&nbsp;</td>
                <td valign="top" align="left" width="13%">Red Bull:';
$html.='&nbsp;&nbsp;'.$dmg['red_bull_txt'].'</td>
                <td width="10" valign="top" align="left">&nbsp;</td>
                <td valign="top" align="left">&nbsp;</td>
              </tr>
              

            </tbody></table>';
    }
    
    
    $html.='<br/><h4>Section Information</h4><table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td width="25%" valign="top" align="left" height="30">CSD Section size : </td>
            <td align="left">'.$dmg['csd_sec_size'].'</td>
            </tr>
            <tr>
              <td valign="top" width="25%" align="left" height="30">Gondolas: </td>
              <td valign="top" align="left" height="30"><label>';
$html.=($dmg['gondolas']=='H')? 'High&nbsp;Profile':'Low&nbsp;Profile';
$html.='</label></td>
              </tr>
              <tr>
            <td width="25%" valign="top" align="left" height="30">Number of Shelves : </td>
            <td align="left">'.$dmg['csd_numshelf'].'</td>
            </tr>
             <tr>
            <td width="25%" valign="top" align="left" height="30">Mixer Section size : </td>
            <td align="left">'.$dmg['mixer_sec_size'].'</td>
            </tr>
            <tr>
              <td valign="top" width="25%" align="left" height="30">Gondolas: </td>
              <td valign="top" align="left" height="30"><label>';
$html.=($dmg['gondolas2']=='H')? 'High&nbsp;Profile':'Low&nbsp;Profile';
$html.='</label></td>
              </tr>
        </table>';
           $html.='<br/><table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody>
              <tr>
                <td valign="top" align="left" height="30" width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="17%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="30%" height="30" align="left" valign="top"><label></label>'.$dmg['mngr_name'].'</td>
       
                
                 <td width="30%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="15%" align="left" valign="top">'.$dmg['mngr_storenum'].'</td>

                <td width="15%" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top">
                  &nbsp;</td>
     
              </tr>
                <tr><td colspan="11">Manager Signature:</td></tr> 
              <tr> <td colspan="11"  >';
if($dmg['mngr_sign']!='')
{
        if($is_client==1)
{
$html.='<a href="'.$base_url.'content/upload_files/images/'.$dmg['mngr_sign'].'"><img  width="100px" height="100px" id="proj_sign_img_field" src="'.$image_dir.$dmg['mngr_sign'].'"/></a>';     
 }   
 else { 
$size = getimagesize($image_dir.$dmg['mngr_sign']);

                    $html.='<img   ';
   if($size[0]>600) $html.=' width="600px" ';    
   if($size[1]>480) $html.=' height="480px" '; 
                    $html.=' id="proj_sign_img_field" src="'.$image_dir.$dmg['mngr_sign'].'"/>';
}
}
    $html.='</td></tr>
              
              <tr><td colspan="11">Comments:</td></tr>
              <tr> <td colspan="11">'.$dmg['comments'].'</td></tr>
         
          
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
               
              </tr>';
    require 'sign_off_imgs_pdf.php';
    $html.='</tbody></table></td>
          
          </tr>
      </tbody></table></td>
  </tr>
</tbody></table>
</div>';
//echo $html;
require 'export_pdf.php'


?>