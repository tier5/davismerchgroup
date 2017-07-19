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
    $query  ='select t.*,ch.chain,cl.client,chmg.sto_num from stater_bros_form'.$ext.' as t left join tbl_chain as ch on ch.ch_id::text=t.store_name'
  .' left join "clientDB" as cl on cl."ID"=t.cid '            
 .' left join tbl_chainmanagement as chmg on chmg.chain_id::text=t.store_num where stat_bros_id='.$form_id;

if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$stat = pg_fetch_array($result);

pg_free_result($result);
$proj_image=$stat['proj_image'];

$query = ("SELECT * from grocery_cat_items where pid=".$pid." order by id asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$cats=array();
while ($row = pg_fetch_array($result)) {
    $cats[] = $row;
}

pg_free_result($result);
}
//echo 'dd'.$pid;
//print_r($frito);
$file_name='Grocery_form.pdf';

$html='
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody>
          <tr>
    <td colspan="4" align="center"><h3>Grocery Store Form</h3></td>
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
<tr><td width="85" valign="top" align="left" height="30"><font size="12">Store Name:</font></td>
  <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="150"><font size="12">';
if($stat['store_name']==0)
$html.=$stat['other'];
else    
$html.=$stat['chain'];
          $html.='</font></td>
              
<td width="53" valign="top" align="left" height="30"><font size="12">Store #:</font></td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="60"><font size="12">';
          $html.=$stat['sto_num'] .'</font></td>
 <td width="82" valign="top" align="left" height="30"><font size="12">Work&nbsp;Type :</font></td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="100"><font size="12">';
          $html.=$stat['work_type'] .'</font></td>             
              </tr>
        
            </table>  
      
      </td>
      </tr>
      <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <td valign="top" align="left" height="30"  width="52">Address:</td>
        <td valign="top" align="left" height="30" width="60%">'.$stat['address'].'</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="28">City:</td>
        <td valign="top" align="left" height="30" width="60%">'.$stat['city'].'</td>
            <td valign="top" align="left" height="30" width="32">Blitz:</td>
        <td valign="top" align="left" height="30" width="60%">';
  if(isset($stat['category'])&&$stat['category']=='Other (Write In)*'){ $html.=$stat['cat_other'];}else{        
          $html.=$stat['category'];
  }
           $html.='</td>
      </tr>
         <tr>
        <td valign="top" align="left" height="30" width="38">Client:</td>
        <td valign="top" align="left" height="30" width="60%">'.$stat['client'].'</td>
      </tr>
    </tbody></table></td>
    <td width="10" valign="top" align="left">&nbsp;</td>
    <td valign="top" align="left" width="30%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td width="100" valign="top" align="left" height="30">&nbsp;</td>
        <td width="10" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="right" width="100">'.$stat['date'].'</td>
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


    
 <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
  <tr><td align="center" colspan="2"><h4>&nbsp;&nbsp;DC/Repack</h4></td><td>&nbsp;</td></tr>      

<tr>
         
          <td valign="top" align="left"  width="40%">Repack in boxes:&nbsp;'.$stat['repack_box'].'</td>
<td width="10%">&nbsp;</td>
              <td valign="top" align="left"  width="40%">Repack in carts:&nbsp;'.$stat['repack_in'].'</td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
         
          <td valign="top" align="left" width="40%">DC in boxes:&nbsp;'.$stat['dcin_box'].'</td>
         <td width="10%">&nbsp;</td>    
<td valign="top" align="left"  width="40%">DC in carts :&nbsp;'.$stat['dcin'].'</td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
         
          <td valign="top" align="left" width="40%">Out of code in boxes:&nbsp;'.$stat['out_code_box'].'</td>
              <td width="10%">&nbsp;</td>
              <td valign="top" align="left"  width="40%">Out of code in carts:&nbsp;'.$stat['out_of_code'].'</td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
          <td valign="top" align="left" height="30" colspan="3" width="210">Repack - Dc palatized in backroom:&nbsp;</td>
          <td valign="top" align="left" colspan="3">';
$html.=($stat['repck_dc_back']=='Y')? 'Yes':'No';
$html.='</td>
         
        </tr>
        </table>
 <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
          <td valign="top" align="left" height="30" colspan="10">If no explain Why? </td>
          </tr>
        <tr>
          <td valign="top" align="left" height="30" colspan="10">'.$stat['exp_why'] .'</td>
          </tr>
      </tbody></table>
  <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
        <tr><td colspan="10"><table>
        <tbody><tr>
             <td width="6%" valign="top" align="left" colspan="1">CAT :</td>
         
          <td width="12%" valign="top" align="left">'.$stat['cat_1'] .'</td>
                <td width="14%" valign="top" align="right">File ID# :&nbsp;&nbsp;&nbsp;</td>
          
          <td width="12%" valign="top" align="left">'.$stat['file_id'] .'</td>
          <td width="14%" valign="top" align="right">&nbsp;Footage:&nbsp;&nbsp;&nbsp;</td>
          <td width="12%" valign="top" align="left">'.$stat['footage'] .'</td>
       <td width="22%" valign="top" align="right" height="30">Section Completed :&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td  valign="top" align="left" height="30">';
$html.=($stat['sec_comp']=='Y')? 'Yes':'No';
$html.='</td>
        </tr>';
if(count($cats)>0){
   foreach($cats as $cat){    
$html.='<tr>
             <td width="6%" valign="top" align="left" colspan="1">CAT :</td>
         
          <td width="12%" valign="top" align="left">'.$cat['cat'] .'</td>
                <td width="14%" valign="top" align="right">File ID# :&nbsp;&nbsp;&nbsp;</td>
          
          <td width="12%" valign="top" align="left">'.$cat['file_id'] .'</td>
          <td width="14%" valign="top" align="right">&nbsp;Footage:&nbsp;&nbsp;&nbsp;</td>
          <td width="12%" valign="top" align="left">'.$cat['footage'] .'</td>
       <td width="22%" valign="top" align="right" height="30">Section Completed :&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td  valign="top" align="left" height="30">';
$html.=($cat['sec_comp']=='Y')? 'Yes':'No';
$html.='</td>
        </tr>';        

   }
}

               $html.=' </tbody></table>


   
                
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr><td colspan="5">&nbsp;</td></tr>
     <tr><td colspan="5">&nbsp;</td></tr>
              <tr>
                <td width="16%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="30%" height="30" align="left" valign="top"><label></label>'.$stat['name_title'].'</td>
       
                
                 <td width="30%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="15%" align="left" valign="top">'.$stat['mngr_writ_store'].'</td>

                <td width="15%" align="left" valign="top">&nbsp; </td>
                <td width="10%" align="left" valign="top">
                  &nbsp;</td>
     
              </tr>
              
<tr><td colspan="11">Manager Signature:</td></tr>
              <tr> <td colspan="13"width="100%">';

if($stat['mngr_sign']!='')
{
 if($is_client==1)
{
$html.='<a href="'.$base_url.'content/upload_files/images/'.$stat['mngr_sign'].'"><img  width="100px" height="100px" id="proj_sign_img_field" src="'.$image_dir.$stat['mngr_sign'].'"/></a>';     
 }   
 else{
$size = getimagesize($image_dir.$stat['mngr_sign']);

                    $html.='<img   ';

   if($size[0]>600) $html.=' width="600px" ';    
   if($size[1]>480) $html.=' height="480px" '; 

                    $html.='  id="proj_sign_img_field" src="'.$image_dir.$stat['mngr_sign'].'"/>';

}
}
    $html.='</td></tr>
              
              <tr><td colspan="11">Comments:</td></tr>
              <tr> <td colspan="11">'.$stat['comments'].'</td></tr>
         
          
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


      <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
          <td valign="top" align="left"><table width="100%" cellspacing="0" cellpadding="0" border="0">
             <tbody><tr><td>&nbsp;</td></tr>';
    require 'sign_off_imgs_pdf.php';
    $html.='</tbody></table></td>
          </tr>
      </tbody></table></td>
    </tr>
</tbody></table>';
//echo $html;
require 'export_pdf.php'


?>