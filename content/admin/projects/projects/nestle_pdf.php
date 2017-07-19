<?php
require 'Application.php';
$pid=$_GET['pid'];

if(isset($pid)&&$pid!='')
{
     $query  ='select t.*,ch.chain,chmg.sto_num from nestle_form as t left join tbl_chain as ch on ch.ch_id::text=t.store_name '.
  ' '           
             .' left join tbl_chainmanagement as chmg on chmg.chain_id::text=t.store_num where pid='.$pid;   

if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$nestle = pg_fetch_array($result);

pg_free_result($result);
}
$proj_image=$nestle['proj_image'];
$file_name='nestle_form.pdf';
//echo 'dd'.$pid;
//print_r($nestle);

$html='<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody>
          <tr>
    <td colspan="4" align="center"><h3>Nestle DSD Sign Off</h3></td>
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
        <td width="72" valign="top" align="left" height="30">Store Name:</td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="150">';
if($nestle['store_name']==0)
$html.=$nestle['other'];
else    
$html.=$nestle['chain'];
          $html.='</td>
              
<td width="50" valign="top" align="left" height="30">Store #:</td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="100">';
          $html.=$nestle['sto_num'] .'</td>
              </tr>
        <tr>
 <td width="72" valign="top" align="left" height="30">Work&nbsp;Type :</td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="150">'.$nestle['work_type'];             
              $html.='</td>
              </tr>        
            </table> 
    </td>
      </tr>
      <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <td valign="top" align="left" height="30" width="50">Address:</td>
        <td valign="top" align="left" height="30" width="5">&nbsp;</td>
        <td valign="top" align="left" height="30" width="60%">'.$nestle['address'].'</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="30">City:</td>
        <td valign="top" align="left" height="30" width="5">&nbsp;</td>
        <td valign="top" align="left" height="30" width="60%">'.$nestle['city'].'</td>
      </tr>
    </tbody></table></td>
    <td width="10" valign="top" align="left">&nbsp;</td>
    <td valign="top" align="left" width="30%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td width="100" valign="top" align="left" height="30">&nbsp;</td>
        <td width="10" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="right" width="100" >'.$nestle['blit_date'].'</td>
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
</tbody></table>';

$html.='<fieldset class="fsd"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody>
     
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" width="75%">SET COMPLETED?&nbsp;&nbsp;';
$html.=($nestle['set_complete']=='Y')? 'Yes':'No';
$html.='</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30">NEW ITEMS CUT IN?&nbsp;&nbsp;';
$html.=($nestle['new_item_cut']=='Y')? 'Yes':'No';
$html.='</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30">DC MARKED?&nbsp;&nbsp;';
$html.=($nestle['dc_marked']=='Y')? 'Yes':'No';
$html.='</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30">IF NO TO ANY QUESTIONS PLEASE WRITE WHY IN COMMENTS:</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" width="85%" >CURRENT SIZE OF SET:&nbsp;&nbsp;'.$nestle['cur_sz_set'].'   
              (# of doors)</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30">Ice Cream Vault:&nbsp;&nbsp;'.$nestle['ice_cream_vault'].'     
              (# of doors)</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30" width="70%">Walk in Vault   Front :&nbsp;&nbsp;';
$html.=($nestle['walk_in_vault_front']=='t')? 'Yes':'';
$html.=($nestle['walk_in_vault_front']=='f')? 'No':'';
$html.='</td></tr><tr><td width="70%">Load Cooler :&nbsp;&nbsp;';
$html.=($nestle['load_cooler']=='t')? 'Yes':'';
$html.=($nestle['load_cooler']=='f')? 'No':'';
$html.='</td></tr>
 <tr><td >&nbsp;</td></tr>   
<tr><td width="70%">Vendor Visi-coolers:&nbsp;&nbsp;';
$html.=($nestle['vendor_cool']=='t')? 'Yes':'';
$html.=($nestle['vendor_cool']=='f')? 'No':'';
$html.='</td></tr>';

$html.='<tr>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30" >Frozen Food Vault:&nbsp;&nbsp;'.$nestle['froz_food_vault'].'  
              (# of doors)</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30" width="70%">Walk in vault :&nbsp;&nbsp;';
$html.=($nestle['walk_in_vault']=='t')? 'Yes':'';
$html.=($nestle['walk_in_vault']=='f')? 'No':'';
$html.='</td></tr><tr><td width="70%">Front load cooler :&nbsp;&nbsp;';
$html.=($nestle['load_cooler']=='t')? 'Yes':'';
$html.=($nestle['load_cooler']=='f')? 'No':'';
$html.='</td></tr>
        </tbody></table></td>
      </tr>
    </tbody></table>
	</fieldset>';


$html.='<fieldset class="fsd"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td valign="top" align="left" height="30"><div align="center"><strong>NEED SECTION INFORMATION </strong><br>
        </div></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" width="60%">Manufacture of Cold box:&nbsp;&nbsp;
<label>'.$nestle['man_coldbox'].'
            </label></td>
            </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" width="40%">Model#:&nbsp;&nbsp;'.$nestle['model_num'].'</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30" colspan="3">Any icecream shelves missing?  &nbsp;&nbsp;'.$nestle['shell_miss_ice'].
'</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
        
        </tbody></table></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" colspan="7">How many:&nbsp;&nbsp;'.$nestle['ice_door'].' 
             </td>
            </tr>
          <tr>
            <td valign="top" align="left" colspan="7">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30" width="100%">Any frozen food shelves missing? &nbsp;&nbsp;'.
$nestle['shell_miss_froz'].
'</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
      
          <tr>
            <td valign="top" align="left" height="30" colspan="7" width="100%">How many:&nbsp;&nbsp;'.$nestle['froz_door'].'
              </td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
        </tbody></table></td>
      </tr>
    </tbody></table>
	</fieldset>';

$html.='<fieldset class="fsd"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td valign="top" align="left" height="30"><div align="center"><strong>STORE MANAGER SECTION </strong><br>
        </div></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">ARE ALL THE SETS TO THE NEW SCHEMATIC?&nbsp;&nbsp;';
  
$html.=($nestle['new_schema']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">DID THE TAGS GET REPLACED:&nbsp;&nbsp;';
$html.=($nestle['tag_replace']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">WAS A COPY OF SCHEMATIC LEFT N THE CASE:&nbsp;&nbsp;';
$html.=($nestle['copy_schema']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
     <tr><td>&nbsp;</td></tr> 
      <tr>
        <td valign="top" align="left" height="30" width="100%">DOES THE CASE GET ICED UP AT ALL?&nbsp;&nbsp;';
$html.=($nestle['case_ice']=='Y')? 'Yes':'No';
$html.='</td></tr>
     <tr><td>IS THE TEMP OF THE CASE COLD ENOUGH FOR THE PRODUCT?&nbsp;&nbsp;';
$html.=($nestle['case_cold']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">IS THERE A SECONDARY LOCATION OR DC/REPACK :&nbsp;&nbsp;';
$html.=($nestle['sec_loc']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
 
     
    
    </tbody></table>
	</fieldset>';
	

$html.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="17%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="30%" height="30" align="left" valign="top"><label></label>'.$nestle['name_title'].'</td>
       
                
                 <td width="30%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="15%" align="left" valign="top">'.$nestle['manager_storenum'].'</td>

                <td width="15%" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top">
                  &nbsp;</td>
     
              </tr>
           <tr><td colspan="11">Manager Signature:</td></tr>      
              <tr> <td colspan="11"  width="100%">';
if($nestle['mngr_sign']!='')
{
$size = getimagesize($image_dir.$nestle['mngr_sign']);

                    $html.='<img   ';
   if($size[0]>600) $html.=' width="600px" ';    
   if($size[1]>480) $html.=' height="480px" '; 
                    $html.='   id="proj_sign_img_field" src="'.$image_dir.$nestle['mngr_sign'].'"/>';
}             
   $html.=' </td></tr>
              
              <tr><td colspan="11">Comments:</td></tr>
              <tr> <td colspan="11">'.$nestle['comments'].'</td></tr>
         
          
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
                         

            </table>    ';

$html.=' <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
          <td valign="top" align="left"><table width="100%" cellspacing="0" cellpadding="0" border="0">
             
            
              <tbody><tr><td>&nbsp;</td></tr>';
    require 'sign_off_imgs_pdf.php';
    $html.='</tbody></table></td>
          </tr>
      </tbody></table>';

//echo $html;
require 'export_pdf.php'


?>