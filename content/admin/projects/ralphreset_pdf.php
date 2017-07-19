<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
$pid=$_GET['pid'];
$form_id=$_GET['form_id'];
$ralph=array();
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num,chain.chain,cl.client,e.firstname,e.lastname   from ralphs_reset_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num '
   .' left join "clientDB" as cl on cl."ID"=d.cid '  
            .' left join "employeeDB" as e on e."employeeID"=d.merch '         
   . ' left join tbl_chain as chain on chain.ch_id::text=d.store_name '        
            .' where r_id='.$form_id;
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
$res=array();
$query  ='select d.*,ch.sto_num   from missing_hardware'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where form_id='.$form_id.' and type=\'ral_reset\'';
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$res = pg_fetch_array($result);

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

 $query  ='select d.*,ch.sto_num   from ssr_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where form_id='.$form_id.' and type=\'ral_reset\'';
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$ssr_form = pg_fetch_array($result);

pg_free_result($result);


$query = ("SELECT * from ssr_form_item  where form_id=".$form_id." and type='ral_reset'  order by ss_it_id asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$ss_frm=array();
while ($row = pg_fetch_array($result)) {
    $ss_frm[] = $row;
}


 $query  ='select d.*,ch.sto_num   from item_mes_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where form_id='.$form_id;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$item_mes = pg_fetch_array($result);

pg_free_result($result);

$query = ("SELECT * from item_mes_form_item where form_id=".$form_id." order by ss_it_id asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$ssr_item=array();
while ($row = pg_fetch_array($result)) {
    $ssr_item[] = $row;
}

$s_summ=array();
 $query  ='select d.*,ch.sto_num   from reset_store_summary'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where form_id='.$form_id;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$s_summ = pg_fetch_array($result);

$proj_image=$ssr_form['proj_image'];
$file_name='Ralphs reset signoff.pdf';





$html='<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody>
          <tr>
    <td colspan="4" align="center"><h3>Ralphs Reset Sign Off</h3></td>
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
if($ralph['store_name']==0)
$html.=$ralph['other'];
else    
$html.=$ralph['chain'];
          $html.='</font></td>
              
<td width="53" valign="top" align="left" height="30"><font size="12">Store #:</font></td>
        <td width="5" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="left" width="60"><font size="12">';
          $html.=$ralph['sto_num'] .'</font></td>';
    $html.='<td width="20" valign="top" align="left" height="30">'.$ralph['merch_type'].'</td>';      
              $html.='<td width="82" valign="top" align="left" height="30"><font size="12">&nbsp;Work&nbsp;Type:</font></td>
        <td valign="top" align="left" width="100"><font size="12">';
          $html.=$ralph['work_type'] .'</font></td>
              </tr>
            </table> 
    </td>
      </tr>
      


      <tr><td colspan="3">&nbsp;</td></tr>
      <tr>
        <td valign="top" align="left" height="30" width="52">Address:</td>
        <td valign="top" align="left" height="30" width="60%">'.$ralph['address'].'</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="28">City:</td>
        <td valign="top" align="left" height="30" width="60%">'.$ralph['city'].'</td>
      </tr>
         <tr>
        <td valign="top" align="left" height="30" width="38">Client:</td>
        <td valign="top" align="left" height="30" width="60%">'.$ralph['client'].'</td>
      </tr>
    </tbody></table></td>
    <td width="10" valign="top" align="left">&nbsp;</td>
    <td valign="top" align="left" width="30%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td width="100" valign="top" align="left" height="30">&nbsp;</td>
        <td width="10" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="right" width="100" >'.$ralph['date'].'</td>
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
$html.='<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="150" height="30">Merchandiser Name:&nbsp;</td>
    <td width="250">'.$ralph['firstname'].' '.$ralph['lastname'].'</td>
    <td width="10">&nbsp;</td>
    <td></td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>';   

$html.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="30">Repack in: &nbsp;</td>
    <td width="10">&nbsp;</td>
    <td>'.$ralph['repack_in'].'</td>
    <td width="10">&nbsp;</td>
    <td>How Many&nbsp;</td>
    <td width="10">&nbsp;</td>
    <td>'.$ralph['repack_how'].'</td>
  </tr>
  <tr>
    <td height="30">Dc in: </td>
    <td>&nbsp;</td>
    <td>'.$ralph['dcin'].'</td>
    <td>&nbsp;</td>
    <td>How Many: </td>
    <td>&nbsp;</td>
    <td>'.$ralph['merch'].'</td>
  </tr>
  <tr>
    <td height="30">Out of Code:</td>
    <td>&nbsp;</td>
    <td>'.$ralph['out_code'].'</td>
    <td>&nbsp;</td>
    <td>How Many:</td>
    <td>&nbsp;</td>
    <td>'.$ralph['out_code_how'].'</td>
  </tr>
  <tr>
    <td height="30">Not in Set in:</td>
    <td>&nbsp;</td>
    <td>'.$ralph['not_set'].'</td>
    <td>&nbsp;</td>
    <td>How Many:</td>
    <td>&nbsp;</td>
    <td>'.$ralph['not_set_how'].'</td>
  </tr>
  <tr>
    <td height="30" width="300px">Are all of the above marked and put in meat cooler?</td>
    <td width="5px">&nbsp;</td>
    <td>';
 $html.=($ralph['mark_put_cooler']=='Y')? 'Yes':'No';
      $html.='</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="30">If no explain why:</td>
    <td>&nbsp;</td>
    <td colspan="5"><label>'.$ralph['if_no_exp'].'
    </label></td>
    </tr>
  <tr>
    <td height="30">Is there any section you were not able to set?</td>
    <td>&nbsp;</td>
    <td>';
  $html.=($ralph['sect_not_set']=='Y')? 'Yes':'No';    
     $html.='</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>';
     if($ralph['sect_not_set']=='Y')
     {
    $html.='<tr>
    <td height="30">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="5">'.$ralph['anomaly_store'].'</td>
    </tr>';
     }
    $html.='<tr>
    <td height="30">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td height="30">Why: </td>
    <td>&nbsp;</td>
    <td colspan="5">'.$ralph['why'].'</td>
    </tr>';
       $html.='<tr>
    <td height="30">Was this an anomaly store?</td>
    <td>&nbsp;</td>
    <td>';
  $html.=($ralph['anm_store']=='Y')? 'Yes':'No';    
     $html.='</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>';
  
 if($ralph['anm_store']=='Y'){    
$html.='<h3>Reset Store Summery Report</h3>';	

 $html.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td>'.$s_summ['store_num'].'</td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td>'.$s_summ['district'].'</td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td>'.$s_summ['date'].'</td>
    </tr>
  </table>
          <br/>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="350" height="30">1.Total Missing Item Count (Total Items Listed on Form): 




&nbsp;</td>
      <td width="10">&nbsp;</td>
      <td>'.$s_summ['miss_count'].'</td>
      </tr>
    <tr>
      <td height="30">2.       Total Missing New Item Count (Total Items Listed as New): </td>
      <td>&nbsp;</td>
      <td>'.$s_summ['new_miss_count'].'</td>
    </tr>
    <tr>
      <td height="30">3.       Total UA Item Count (Total Items Listed as UA): &nbsp;</td>
      <td>&nbsp;</td>
      <td>'.$s_summ['ua_cnt'].'</td>
    </tr>
    <tr>
      <td height="30">4.       DC’D Items in Set # (Total DC’D Items Found in Set):&nbsp;</td>
      <td>&nbsp;</td>
      <td>'.$s_summ['dcd_item'].'</td>
    </tr>
    <tr>
      <td height="30">5.       New Items were staged (Did the Store pull the New Items and Put Them on a U Boat or Stage in a Separate Area): &nbsp;</td>
      <td>&nbsp;</td>
      <td>';
 $html.=($s_summ['stag_item']=='Y')? 'Yes':'No';
        $html.='</td>
      </tr>

  </table>';   
 }
 $html.='<br/><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30" width="200px">Smoke Fish Section: &nbsp;</td>
      <td>&nbsp;</td>
      <td>';
  $html.=($ralph['smoke_fish']=='Y')? 'Yes':'No';        
       $html.='</td>
      <td width="10">&nbsp;</td>
      <td>Location:</td>
      <td width="10">&nbsp;</td>
      <td>'.$ralph['not_set_how'].'</td>
    </tr>
    <tr>
      <td height="30" colspan="7">Did you reset this section to the new schematic : '.$ralph['line_wall_dely'].'</td>
      </tr>
    <tr>
      <td height="30">Missing or damaged hardware: </td>
      <td>&nbsp;</td>
      <td>';
 $html.=($ralph['mis_hardware']=='Y')? 'Yes':'No';      
      $html.='</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>';
if($ralph['mis_hardware']=='Y'){
$html.='<h3>Missing Hardware Survey</h3>';
 $html.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td>'.$res['store_num'].'</td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td>'.$res['district'].'</td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td>'.$res['date'].'</td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30" width="150px;">1.       IQF Baskets: </td>
      <td width="10">&nbsp;</td>
      <td>'.$res['iqf_basket'].'</td>
      <td width="10">&nbsp;</td>
      <td>2.       POS Natural Signs:</td>
      <td width="10">&nbsp;</td>
      <td>'.$res['iqf_basket'].'</td>
    </tr>
    <tr>
      <td height="30">3.       SG Trays: </td>
      <td>&nbsp;</td>
      <td>'.$res['sg_tray'].'</td>
      <td>&nbsp;</td>
      <td>4.       Well Dividers:</td>
      <td>&nbsp;</td>
      <td>'.$res['well_div'].'</td>
    </tr>
    <tr>
      <td height="30" width="300px;">5.       White Freezer Shelves: </td>
      <td>&nbsp;</td>
      <td>'.$res['white_freez'].'</td>
      <td>&nbsp;</td>
      <td>6.       Black Freezer Shelves:</td>
      <td>&nbsp;</td>
      <td>'.$res['black_freez'].'</td>
    </tr>
    <tr>
      <td height="30">7.       White Meat Case Shelves: </td>
      <td>&nbsp;</td>
      <td>'.$res['white_meat'].'</td>
      <td>&nbsp;</td>
      <td>8.       Black Meat Case Shelves: </td>
      <td>&nbsp;</td>
      <td>'.$res['black_meat'].'</td>
    </tr>
    <tr>
      <td height="30">9.       DCI Trays: </td>
      <td>&nbsp;</td>
      <td>'.$res['dci_tray'].'</td>
      <td>&nbsp;</td>
      <td>10.   DCI Tray Size:</td>
      <td>&nbsp;</td>
      <td>'.$res['dci_tray_size'].'</td>
    </tr>
    <tr>
      <td height="30">11.   Fencing:</td>
      <td>&nbsp;</td>
      <td>'.$res['fencing'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>

  </table> ';   
}
    $html.='<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
      <td height="30">Mapping Form: </td>
      <td>&nbsp;</td>
      <td>';
$html.=($ralph['map_form']=='Y')? 'Yes':'No';      
       $html.='</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="30">SSR Form: </td>
      <td>&nbsp;</td>
      <td>';
 $html.=($ralph['ssr_form']=='Y')? 'Yes':'No';       
       $html.='</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr></table>';
$html.='<h3>SSR Form</h3>	

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td>'.$ssr_form['store_num'].'</td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td>'.$ssr_form['dist'].'</td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td>'.$ssr_form['date'].'</td>
    </tr>
  </table>
  <table id="grid-new" width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
      <td height="30" class="grey-bg">STORE</td>
      <td class="grey-bg">Commodity Code</td>
      <td class="grey-bg">Kroger Category Update</td>
      <td class="grey-bg">&nbsp;</td>
      </tr>';
  if(count($ss_frm)>0){  
  foreach($ss_frm as $i=>$ssr) {   
      $html.='<tr>
      <td height="30" class="white-bg">'. $ssr['store'].'</td><td class="white-bg">'.$ssr['comm_code'].'</td>
      <td class="white-bg">'
       . $ssr['krog_cat']
      .'</td></tr>';    
   }
  }
 $html.='</table>';          
       
    $html.='<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
      <td height="30" width="200px">Item Measurement Update Form:  </td>
      <td width="5px;">&nbsp;</td>
      <td>';
 $html.=($ralph['item_mes_update']=='Y')? 'Yes':'No';       
       $html.='</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>'; 
  if($ralph['item_mes_update']=='Y'){
 $html.='<h3>Item Measurement Update Form</h3>';	

$html.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td>'.$item_mes['store_num'].'</td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td>'.$item_mes['dist'].'</td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td>'.$item_mes['date'].'</td>
    </tr>
  </table>';
  $html.='<table id="grid-new" width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
      <td height="30" class="grey-bg">Schematic Version</td>
      <td class="grey-bg">UPC</td>
      <td class="grey-bg">Product Name</td>
      <td class="grey-bg">Height</td>
      <td class="grey-bg">Width</td>
      <td class="grey-bg">Depth</td>
      <td class="grey-bg">Shelf</td>
      <td class="grey-bg">&nbsp;</td>
      </tr>';
 if(count($ssr_item)>0){  
  foreach($ssr_item as $i=>$ssr) { 
    $html.='<tr>
      <td height="30" class="white-bg">'.$ssr['schem_v'].'</td>
      <td class="white-bg">'.$ssr['upc'].'</td>
      <td class="white-bg">'.$ssr['prod_name'].
      '</td>
      <td class="white-bg">'.$ssr['height'].'</td>
      <td class="white-bg">'.$ssr['width'].'</td>
      <td class="white-bg">'.$ssr['depth'].'</td>
      <td class="white-bg">'.$ssr['shelf'].'</td>
    </tr> ';    
    }} 
    
  $html.='</table>';        
  } 
      
$html.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" height="30" align="left" valign="top">Manager Name : </td>
                <td width="10" height="30" align="left" valign="top">&nbsp;</td>
                <td width="10%" height="30" align="left" valign="top">'.
                 $ralph['name_title'].'</td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                
                 <td width="17%" align="left" valign="top">Manager Write in Store Number: <br /></td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top">'.$ralph['manager_storenum'].'</td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="12%" align="left" valign="top">Manager Signature: </td>
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td width="10%" align="left" valign="top"></td>
                
        
                <td width="10" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              
<tr><td colspan="11">Manager Signature:</td></tr>      
              <tr> <td colspan="11"  width="100%">';
if($ralph['mngr_sign']!='')
{
 if($is_client==1)
{
$html.='<a href="'.$base_url.'content/upload_files/images/'.$ralph['mngr_sign'].'"><img  width="100px" height="100px" id="proj_sign_img_field" src="'.$image_dir.$ralph['mngr_sign'].'"/></a>';     
 }   
 else{       
$size = getimagesize($image_dir.$ralph['mngr_sign']);

                    $html.='<img   ';
   if($size[0]>600) $html.=' width="600px" ';    
   if($size[1]>480) $html.=' height="480px" '; 
                    $html.='   id="proj_sign_img_field" src="'.$image_dir.$ralph['mngr_sign'].'"/>';
}
}
   $html.=' </td></tr>
              
              <tr>
                 <td colspan="11">Comments:</td> 
              </tr>
              <tr> <td colspan="11">'.$ralph['comments'].'</td></tr>                                                     
            </table>'; 
$html.=' <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
          <td valign="top" align="left"><table width="100%" cellspacing="0" cellpadding="0" border="0">
             
            
              <tbody><tr><td>&nbsp;</td></tr>';
    require 'sign_off_imgs_pdf.php';
    $html.='</tbody></table></td>
          </tr>
      </tbody></table>';
require 'export_pdf.php';      
?>


  
  