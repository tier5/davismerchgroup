<?php
require 'Application.php';
$pid=$_GET['pid'];
$form_id=$_GET['form_id'];
$ext='';
if(isset($_GET['close']) && $_GET['close'] == 1)
{
$ext='_closed';    
}
if(isset($pid)&&$pid!='')
{
    $query  ='select t.*,ch.chain,cl.client,chmg.sto_num from frito_lay_form'.$ext.' as t left join tbl_chain as ch on ch.ch_id::text=t.store'
            .' left join "clientDB" as cl on cl."ID"=t.cid ' 
       .' left join tbl_chainmanagement as chmg on chmg.chain_id::text=t.number where frito_id='.$form_id; 
    //$query  ='select * from frito_lay_form where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$frito = pg_fetch_array($result);

pg_free_result($result);
//echo 'dd';
//print_r($frito);
}
$proj_image=$frito['proj_image'];
$file_name='fritolay_form.pdf';
//echo 'dd'.$pid;
//print_r($frito);
$html='<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody><tr>
    <td valign="top" align="left">
     
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody>
          <tr>
    <td colspan="4" align="center"><h3>FRITO LAY REST REPORT</h3></td>
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
if($frito['store']==0)
$html.=$frito['other'];
else    
$html.=$frito['chain'];
          $html.='</font></td>
              
<td width="53" valign="top" align="left" height="30"><font size="12">Store #:</font></td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="60"><font size="12">';
          $html.=$frito['sto_num'] .'</font></td>
              <td width="82" valign="top" align="left" height="30"><font size="12">Work&nbsp;Type :</font></td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="100"><font size="12">';
          $html.=$frito['work_type'] .'</font></td>
              </tr>
         
            </table> 
  </td>
      </tr>
      <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <td valign="top" align="left" height="30" width="52">Address:</td>
        <td valign="top" align="left" height="30" width="60%">'.$frito['address'].'</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="28">City:</td>
        <td valign="top" align="left" height="30" width="60%">'.$frito['city'].'</td>
      </tr>
          <tr>
        <td valign="top" align="left" height="30" width="38">Client:</td>
        <td valign="top" align="left" height="30" width="60%">'.$frito['client'].'</td>
      </tr>
    </tbody></table></td>
    <td width="10" valign="top" align="left">&nbsp;</td>
    <td valign="top" align="left" width="30%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td width="100" valign="top" align="left" height="30">&nbsp;</td>
        <td width="30" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="100">'.$frito['date'].'</td>
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




 <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="120" height="30" align="left" valign="top">Repack in carts: </td>
          <td width="5" height="30" align="left" valign="top">&nbsp;</td>
          <td width="100" height="30" align="left" valign="top"><label>'.$frito['repack_in'].'</label></td>
          <td width="10" align="left" valign="top">&nbsp;</td>
          <td width="70" align="left" valign="top">How Many: </td>
          <td width="100" align="left" valign="top">'.$frito['how_many1'].'</td>
          <td width="100" align="left" valign="top">Repack in boxes :</td>
          <td width="100" align="left" valign="top">'.$frito['repack_box'].'</td>
          <td align="left" valign="top">&nbsp;</td>
          <td  align="left" valign="top">&nbsp;</td>
        </tr>
        
      <tr>
          <td height="30" align="left" valign="top">DC in carts: </td>
          <td height="30" align="left" valign="top">&nbsp;</td>
          <td height="30" align="left" valign="top">'.$frito['dcin'].'</td>
          <td align="left" valign="top">&nbsp;</td>
          <td align="left" valign="top">How Many: </td>
          <td align="left" valign="top">'.$frito['how_many2'].'</td>
          <td align="left" valign="top">DC in boxes :</td>
          <td align="left" valign="top">'.$frito['dcin_box'].'</td>
          <td align="left" valign="top">&nbsp;</td>
          <td align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="top">Out of code in carts: </td>
          <td height="30" align="left" valign="top">&nbsp;</td>
          <td height="30" align="left" valign="top">'.$frito['out_of_code'].'</td>
          <td align="left" valign="top">&nbsp;</td>
          <td align="left" valign="top">How Many: </td>
          <td align="left" valign="top">'.$frito['how_many3'].'</td>
          <td align="left" valign="top">Out of code : </td>
          <td align="left" valign="top">'.$frito['out_code_box'].'</td>
          <td align="left" valign="top">&nbsp;</td>
          <td align="left" valign="top">&nbsp;</td>
        </tr>  
        
        </table>

      
      <table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody>
      
<tr>
        <td width="100" valign="top" align="left" height="30">&nbsp;</td>
        <td width="10" valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
      </tr>
      <tr>
        <td height="30" class="current" colspan="3"><div align="center">CHIP AISLE FOOTAGE </div></td>
      </tr>
    </tbody></table>
      <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
          <td valign="top" align="left"><table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td width="65" valign="top" align="left" height="30">Total Chip :</td>
              <td width="5" valign="top" align="left">&nbsp;</td>
              <td width="80" valign="top" align="left">'.
          $frito['tot_chp_foot'].'&nbsp;';
              if(isset($frito['tot_chp_foot'])&&$frito['tot_chp_foot']>0) $html.='Footage';
          $html.='</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30" width="95">Main Body Chip:</td>
              <td valign="top" align="left" width="5">&nbsp;</td>
              <td valign="top" align="left">'.$frito['main_bdy_chp'].
                  '&nbsp;';
              if(isset($frito['main_bdy_chp'])&&$frito['main_bdy_chp']>0) $html.='Footage';
          $html.='</td>
            </tr>
            <tr>
              <td width="65" valign="top" align="left" height="30">On the Go:</td>
              <td width="5" valign="top" align="left">&nbsp;</td>
              <td valign="top" align="left">'.$frito['on_the_go'].'&nbsp;';
              if(isset($frito['on_the_go'])&&$frito['on_the_go']>0) $html.='Footage';
          $html.='</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30">Super Size: </td>
              <td valign="top" align="left">&nbsp;</td>
              <td valign="top" align="left">'.$frito['sup_size'].'&nbsp;';
              if(isset($frito['sup_size'])&&$frito['sup_size']>0) $html.='Footage';
          $html.='</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30" width="100">Cannister Potato: </td>
              <td valign="top" align="left" width="5">&nbsp;</td>
              <td valign="top" align="left">'.$frito['can_pot'].'&nbsp;';
              if(isset($frito['can_pot'])&&$frito['can_pot']>0) $html.='Footage';
          $html.='</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30" width="65">Snack Mix: </td>
              <td valign="top" align="left" width="5">&nbsp;</td>
              <td valign="top" align="left">'.$frito['snack_mix'].'&nbsp;';
              if(isset($frito['snack_mix'])&&$frito['snack_mix']>0) $html.='Footage';
          $html.='</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30">&nbsp;</td>
              <td valign="top" align="left">&nbsp;</td>
              <td valign="top" align="left">&nbsp;</td>
            </tr>
          </tbody></table></td>
          <td width="100">&nbsp;</td>
          <td valign="top" align="left" width="200" ><table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td width="120" height="10" valign="top" align="left" height="30">Was Set Completed?</td>
              <td width="5" valign="top" align="left">&nbsp;</td>
              <td valign="top" align="left"><label>';
$html.=($frito['set_cmplt']=='Y')? 'Yes':'No';
$html.='</label></td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30" width="110">New Items Cut in? :</td>
              <td valign="top" align="left" width="5">&nbsp;</td>
              <td valign="top" align="left">
                <label>';
$html.=($frito['cut_in']=='Y')? 'Yes':'No';
$html.='</label>
</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30" width="100">Section Cleaned?</td>
              <td valign="top" align="left" width="5">&nbsp;</td>
              <td valign="top" align="left"><label>';
$html.=($frito['sec_clean']=='Y')? 'Yes':'No';
$html.='</label></td>
            </tr>
          
            <tr>
              <td valign="top" align="left"  width="148">D/C Repack put in Boxes?</td>
              <td valign="top" align="left" width="5">&nbsp;</td>
              <td valign="top" align="left"><label>';
$html.=($frito['dc_repack']=='Y')? 'Yes':'No';
$html.='</label></td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30">&nbsp;</td>
              <td valign="top" align="left">&nbsp;</td>
              <td valign="top" align="left">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30">&nbsp;</td>
              <td valign="top" align="left">&nbsp;</td>
              <td valign="top" align="left">&nbsp;</td>
            </tr>
          </tbody></table></td>
        </tr>
      </tbody></table>
      <br>
      <br>
      



      <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
          <td valign="top" align="left"><table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td height="30" class="current"><div align="center">EQUIPMENT NEEDED </div></td>
            </tr>
    <tr>   
    <td>
<table>
   <tr>                    
        <td width="90" height="30" align="left" valign="top">Shelving&nbsp;type: </td>
              <td align="left" valign="top">'.$frito['shelving_type_drop'].'</td>
  <td width="60">&nbsp;</td>
<td width="80" height="30" align="left" valign="top">Shelf&nbsp;Color:</td>
<td align="left" valign="top">'.$frito['shelf_color_drop'].'</td>
      <td width="60">&nbsp;</td>
 <td width="50" height="30" align="left" valign="top">Depth:</td>
<td align="left" valign="top">'.$frito['Depth_drop'].'</td>
</tr>          
                    </table>
                    </td>
                    </tr>
                    
            
          </tbody></table>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td valign="top" align="left" height="30" width="30%">Any Racks Used in the set?<br>
       <table width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td height="30">1.5&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;'.
        $frito['txt_1_5'].'</td></tr><tr><td height="30">2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;'.$frito['txt_2'].'</td></tr>
                    <tr>
                      <td height="30">2.5&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;'.$frito['txt_2_5'].'</td>
                      
                    </tr>
                    <tr>
                      <td height="30">3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;'.$frito['txt_3'].'</td>
         
                    </tr>
                    <tr>
                      <td height="30">&nbsp;</td>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                  </tbody></table>
     				  </td>
                <td width="30%" valign="top" align="left">
Dip Shelves Used ?<br>
                  
                      <table width="100%" cellspacing="0" cellpadding="0" border="0">
                      <tbody><tr>
                        <td height="30">1.5&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;'.
        $frito['txt_1_5_s'].
                          '</td>
                        <td>&nbsp;</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td height="30">2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;'.$frito['txt_2_s'].                          
                        '</td>
                        <td>&nbsp;</td>
                        <td></td>
                      </tr>
                      <tr>
                        <td height="30">2.5&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;'.$frito['txt_2_5_s'].                          
                        '</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30">3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;'.$frito['txt_2_s_sec'].
                          
                        '</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                      <td>3.5&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;'. $frito['txt_3_5_s'].                         
                        '</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      
                      <tr>
                      <td><br/>4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;'.$frito['txt_4_s'].                        
                       '</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td height="30">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </tbody></table>                

</td>
                <td width="50%" valign="top" align="left">
                
 Dip Breaker Kits :<br/>&nbsp;&nbsp;&nbsp;&nbsp;'.$frito['dp_brk_kit'] .'

</td>
              </tr>
            </tbody></table>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td valign="top" align="left" height="30">&nbsp;
                   </td>
                <td width="10" valign="top" align="left">&nbsp;</td>
                <td width="50%" valign="top" align="left">&nbsp;</td>
              </tr>
            </tbody></table>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
			 <tbody>
  <tr>
  <td width="100%">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="17%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="30%" height="30" align="left" valign="top"><label></label>'.$frito['print_name'].'</td>
       
                
                 <td width="30%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="15%" align="left" valign="top">'.$frito['store_mngr'].'</td>

                <td width="15%" align="left" valign="top">&nbsp; </td>
                <td width="10%" align="left" valign="top">
                  &nbsp;</td>
     
              </tr>
          <tr><td colspan="11">Manager Signature:</td></tr>    
              <tr> <td colspan="11"  width="100%">';

if($frito['mngr_sign']!='')
{
    if($is_client==1)
{
$html.='<a href="'.$base_url.'content/upload_files/images/'.$frito['mngr_sign'].'"><img  width="100px" height="100px" id="proj_sign_img_field" src="'.$image_dir.$frito['mngr_sign'].'"/></a>';     
 }   
 else {    
$size = getimagesize($image_dir.$frito['mngr_sign']);

                    $html.='<img   ';
   if($size[0]>600) $html.=' width="600px" ';    
   if($size[1]>480) $html.=' height="480px" '; 
                    $html.='   id="proj_sign_img_field" src="'.$image_dir.$frito['mngr_sign'].'"/>';
} 
}
 $html.=' </td></tr>
              
              <tr><td colspan="11">Comments:</td></tr>
              <tr> <td colspan="11">'.$frito['comments'].'</td></tr>
         
          
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
  </tr>
             
      

			 <tr>
			   <td valign="top" align="left" height="30" colspan="13">&nbsp;</td>
			   </tr>
       </tbody></table></td>
          </tr>
      </tbody></table></td>
    </tr>';
    require 'sign_off_imgs_pdf.php';
    $html.='
</tbody></table>';
//echo $html;
require 'export_pdf.php'


?>