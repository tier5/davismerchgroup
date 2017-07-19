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
     $query  ='select t.*,ch.chain,cl.client,chmg.sto_num from pizza_form'.$ext.' as t left join tbl_chain as ch on ch.ch_id::text=t.store_name '
 .' left join "clientDB" as cl on cl."ID"=t.cid '       
             .' left join tbl_chainmanagement as chmg on chmg.chain_id::text=t.store_num where pizza_id='.$form_id;   

if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$pizza = pg_fetch_array($result);

pg_free_result($result);
}
$proj_image=$pizza['proj_image'];
$file_name='nestle_form.pdf';
//echo 'dd'.$pid;
//print_r($pizza);

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
        <td width="85" valign="top" align="left" height="30"><font size="12">Store Name:</font></td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="150"><font size="12">';
if($pizza['store_name']==0)
$html.=$pizza['other'];
else    
$html.=$pizza['chain'];
          $html.='</font></td>
              
<td width="53" valign="top" align="left" height="30"><font size="12">Store #:</font></td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="60"><font size="12">';
          $html.=$pizza['sto_num'] .'</font></td>
              <td width="82" valign="top" align="left" height="30"><font size="12">Work&nbsp;Type:</font></td>
        <td valign="top" align="left" width="100"><font size="12">';
          $html.=$pizza['work_type'] .'</font></td>
              </tr>
            </table> 
    </td>
      </tr>
      


      <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <td valign="top" align="left" height="30" width="52">Address:</td>
        <td valign="top" align="left" height="30" width="60%">'.$pizza['address'].'</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="28">City:</td>
        <td valign="top" align="left" height="30" width="60%">'.$pizza['city'].'</td>
      </tr>
          <tr>
        <td valign="top" align="left" height="30" width="38">Client:</td>
        <td valign="top" align="left" height="30" width="60%">'.$pizza['client'].'</td>
      </tr>
    </tbody></table></td>
    <td width="10" valign="top" align="left">&nbsp;</td>
    <td valign="top" align="left" width="30%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td width="100" valign="top" align="left" height="30">&nbsp;</td>
        <td width="10" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="right" width="100" >'.$pizza['blit_date'].'</td>
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
$html.=($pizza['set_complete']=='Y')? 'Yes':'No';
$html.='</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30">NEW ITEMS CUT IN?&nbsp;&nbsp;';
$html.=($pizza['new_item_cut']=='Y')? 'Yes':'No';
$html.='</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30">DC MARKED?&nbsp;&nbsp;';
$html.=($pizza['dc_marked']=='Y')? 'Yes':'No';
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
            <td valign="top" align="left" height="30" width="85%" >CURRENT SIZE OF SET:&nbsp;&nbsp;'.$pizza['cur_sz_set'].'   
              (# of doors)</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30">Ice Cream Vault:&nbsp;&nbsp;'.$pizza['ice_cream_vault'].'     
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
$html.=($pizza['walk_in_vault_front']=='t')? 'Yes':'';
$html.=($pizza['walk_in_vault_front']=='f')? 'No':'';
$html.='</td></tr><tr><td width="70%">Load Cooler :&nbsp;&nbsp;';
$html.=($pizza['load_cooler']=='t')? 'Yes':'';
$html.=($pizza['load_cooler']=='f')? 'No':'';
$html.='</td></tr>
 <tr><td >&nbsp;</td></tr>   
<tr><td width="70%">Vendor Visi-coolers:&nbsp;&nbsp;';
$html.=($pizza['vendor_cool']=='t')? 'Yes':'';
$html.=($pizza['vendor_cool']=='f')? 'No':'';
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
            <td valign="top" align="left" height="30" >Frozen Food Vault:&nbsp;&nbsp;'.$pizza['froz_food_vault'].'  
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
$html.=($pizza['walk_in_vault']=='t')? 'Yes':'';
$html.=($pizza['walk_in_vault']=='f')? 'No':'';
$html.='</td></tr><tr><td width="70%">Front load cooler :&nbsp;&nbsp;';
$html.=($pizza['load_cooler']=='t')? 'Yes':'';
$html.=($pizza['load_cooler']=='f')? 'No':'';
$html.='</td></tr>
    
     <tr>
            <td valign="top" align="left" height="30" >PIZZA DOORS:&nbsp;&nbsp;'.$pizza['pizza_door'].'  
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
$html.=($pizza['walk_in_vault_p']=='t')? 'Yes':'';
$html.=($pizza['walk_in_vault_p']=='f')? 'No':'';
$html.='</td></tr><tr><td width="70%">Front load cooler :&nbsp;&nbsp;';
$html.=($pizza['front_load_cool_p']=='t')? 'Yes':'';
$html.=($pizza['front_load_cool_p']=='f')? 'No':'';
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
<label>'.$pizza['man_coldbox'].'
            </label></td>
            </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" width="40%">Model#:&nbsp;&nbsp;'.$pizza['model_num'].'</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30" colspan="3">Any icecream shelves missing?  &nbsp;&nbsp;'.$pizza['shell_miss_ice'].
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
            <td valign="top" align="left" height="30" colspan="7">How many:&nbsp;&nbsp;'.$pizza['ice_door'].' 
             </td>
            </tr>
          <tr>
            <td valign="top" align="left" colspan="7">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30" width="100%">Any Frozen Food shelves missing? &nbsp;&nbsp;'.
$pizza['shell_miss_froz'].
'</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          
   <tr>
            <td valign="top" align="left" height="30" colspan="7" width="100%">How many:&nbsp;&nbsp;'.$pizza['froz_door'].'
              </td>
          </tr>       
    <tr>
            <td valign="top" align="left" height="30" width="100%">Any Pizza shelves missing?  &nbsp;&nbsp;'.
$pizza['shell_miss_piz'].
'</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
      
          <tr>
            <td valign="top" align="left" height="30" colspan="7" width="100%">How many:&nbsp;&nbsp;'.$pizza['froz_door_piz'].'
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
  
$html.=($pizza['new_schema']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">DID THE TAGS GET REPLACED:&nbsp;&nbsp;';
$html.=($pizza['tag_replace']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">WAS A COPY OF SCHEMATIC LEFT N THE CASE:&nbsp;&nbsp;';
$html.=($pizza['copy_schema']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
     <tr><td>&nbsp;</td></tr> 
      <tr>
        <td valign="top" align="left" height="30" width="100%">DOES THE CASE GET ICED UP AT ALL?&nbsp;&nbsp;';
$html.=($pizza['case_ice']=='Y')? 'Yes':'No';
$html.='</td></tr>
     <tr><td>IS THE TEMP OF THE CASE COLD ENOUGH FOR THE PRODUCT?&nbsp;&nbsp;';
$html.=($pizza['case_cold']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">IS THERE A SECONDARY LOCATION OR DC/REPACK :&nbsp;&nbsp;';
$html.=($pizza['sec_loc']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
 
     
    
    </tbody></table>
	</fieldset>';
	

$html.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="17%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="30%" height="30" align="left" valign="top"><label></label>'.$pizza['name_title'].'</td>
       
                
                 <td width="30%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="15%" align="left" valign="top">'.$pizza['manager_storenum'].'</td>

                <td width="15%" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top">
                  &nbsp;</td>
     
              </tr>
           <tr><td colspan="11">Manager Signature:</td></tr>      
              <tr> <td colspan="11"  width="100%">';
if($pizza['mngr_sign']!='')
{
 if($is_client==1)
{
$html.='<a href="'.$base_url.'content/upload_files/images/'.$pizza['mngr_sign'].'"><img  width="100px" height="100px" id="proj_sign_img_field" src="'.$image_dir.$pizza['mngr_sign'].'"/></a>';     
 }   
 else{    
    
$size = getimagesize($image_dir.$pizza['mngr_sign']);

                    $html.='<img   ';
   if($size[0]>600) $html.=' width="600px" ';    
   if($size[1]>480) $html.=' height="480px" '; 
                    $html.='   id="proj_sign_img_field" src="'.$image_dir.$pizza['mngr_sign'].'"/>';
 }
}             
   $html.=' </td></tr>
              
              <tr><td colspan="11">Comments:</td></tr>
              <tr> <td colspan="11">'.$pizza['comments'].'</td></tr>
         
          
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
require 'export_pdf.php';


?>