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
    $query  ='select t.*,ch.chain,cl.client,chmg.sto_num from dmg_form'.$ext.' as t left join tbl_chain as ch on ch.ch_id::text=t.store_name '
             .' left join "clientDB" as cl on cl."ID"=t.cid '   
             .' left join tbl_chainmanagement as chmg on chmg.chain_id::text=t.store_num where dmg_id='.$form_id;   
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$dmg = pg_fetch_array($result);

pg_free_result($result);
$proj_image=$dmg['proj_image'];
}
$file_name='dmg_form.pdf';
//echo 'dd'.$pid;
//print_r($frito);


$html='<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody>
          <tr>
    <td colspan="4" align="center"><h3>DMG Chain Form</h3></td>
   <td> <img valign="top" src="../../images/davis-wbg.png" alt="logo">
    </td>    
    </tr>
              <tr>
    <td colspan="5">&nbsp;</td>    
    </tr>
  
<tr>
    <td width="60%" valign="top" align="left" ><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
      
<td colspan="3">
<table width="100%">
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
        <td valign="top" align="left" height="30" width="50">Address:</td>
        <td valign="top" align="left" height="30" width="5">&nbsp;</td>
        <td valign="top" align="left" height="30" width="60%">'.$dmg['address'].'</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="30">City:</td>
        <td valign="top" align="left" height="30" width="5">&nbsp;</td>
        <td valign="top" align="left" height="30" width="60%">'.$dmg['city'].'</td>
      </tr>
       <tr>
        <td valign="top" align="left" height="30" width="38">Client:</td>
        <td valign="top" align="left" height="30" width="60%">'.$dmg['client'].'</td>
      </tr>
    </tbody></table></td>
    <td width="10" valign="top" align="left">&nbsp;</td>
    <td valign="top" align="left" width="30%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td width="100" valign="top" align="left" height="30">&nbsp;</td>
        <td width="10" valign="top" align="left">&nbsp;</td>
        <td valign="top" align="right" width="100">'.$dmg['date'].'</td>
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
      <tr>
        <td valign="top" align="left" height="30">CSD/SPL: ';
$html.=($dmg['csd_spl_chk']=='CSD')? 'CSD':'';
$html.=($dmg['csd_spl_chk']=='CSD_SPL')? 'CSD&nbsp;SPLIT':'';
$html.='</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
      </tr>
</tbody></table>';

$html.='<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody><tr>
    <td valign="top" align="left"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody>';
if($dmg['csd_spl_chk']=='CSD')
{
$html.='<tr>
        <td width="100" valign="top" align="left" height="30" width="30">CSD: </td>
        <td width="10" valign="top" align="left" width="5">&nbsp;</td>
        <td valign="top" align="left">'.$dmg['csd'].'</td>
      </tr>';
}
if($dmg['csd_spl_chk']=='CSD_SPL')
{
        $html.='<tr>
        <td valign="top" align="left" height="30" width="100">CSD Split Table :</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30">'.$dmg['csd_split1'].'
          +
          '.$dmg['csd_split2'].'</td>
      </tr>';
}      
          $html.='<tr>
        <td valign="top" align="left" height="30">';
$html.=($dmg['h_l']=='H')? 'High':'';
$html.=($dmg['h_l']=='L')? 'Low':'';
$html.='</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="28%">Number of shelves per section : </td>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30" width="60%">'.$dmg['num_shelf'].'</td>
      </tr>
      

<tr><td colspan="3" align="center"><strong>Shelving</strong></td></tr>
 <tr>
        <td valign="top" align="left" height="30" width="80%">Type:&nbsp;&nbsp;'.$dmg['shell_type'].

     '
 &nbsp;&nbsp;&nbsp;&nbsp;Length:&nbsp;&nbsp;'.$dmg['shell_foot'].'   
     &nbsp;&nbsp;&nbsp;&nbsp;Depth:&nbsp;&nbsp;'.$dmg['shell_depth'].' Inches&nbsp;

          &nbsp;&nbsp;&nbsp;&nbsp;Color:&nbsp;&nbsp;'.$dmg['shell_col'].'
          &nbsp;&nbsp;&nbsp;&nbsp;Molding&nbsp;Color:&nbsp;&nbsp;'.$dmg['shell_mld_col'].'
</td> </tr>

 <tr>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="28%">Number of base decks by size: </td>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30" width="60%">3\'&nbsp;&nbsp;:&nbsp;&nbsp;'.
        $dmg['sl_3'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4\'&nbsp;&nbsp;:&nbsp;&nbsp;'.
        $dmg['sl_4'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6\'&nbsp;&nbsp;:&nbsp;&nbsp;'.
        $dmg['sl_6'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8\'&nbsp;&nbsp;:&nbsp;&nbsp;'.
        $dmg['sl_8'].'</td>
      </tr>
      
<tr>
<td colspan="3" align="center"><strong>Current Glide Equipment in Store</strong></td>
</tr>
      
 <tr>
        <td valign="top" align="left" height="30" colspan="3">Type&nbsp;&nbsp;:&nbsp;&nbsp;'.$dmg['gl_type'].
        '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Depth&nbsp;&nbsp;:&nbsp;&nbsp;'.$dmg['gl_depth'].
        '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Molding Color&nbsp;&nbsp;:&nbsp;&nbsp;'.$dmg['gl_mld_clr'].'</td>
      </tr>
      
      <tr>
        <td valign="top" align="left" height="30" width="22%"># Glide Equipment Used: </td>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30">'.$dmg['gld_eqp_used'].'</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
        <td valign="top" align="left" height="30">&nbsp;</td>
      </tr>
</tbody></table>


  </td>
          
  </tr>
</tbody></table>';

$html.='<br/>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
          <td valign="top" align="left" width="30%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td width="78" valign="top" align="left" height="30">Shasta/Whse:</td>
                <td width="5" valign="top" align="left">&nbsp;</td>
                <td valign="top" align="left">'.$dmg['shasta_whse'].'</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30">';
$html.=($dmg['sh_wh_hl']=='H')? 'High':'';
$html.=($dmg['sh_wh_hl']=='L')? 'Low':'';
$html.=($dmg['sh_wh_hl']=='na')? 'N/A':'';
$html.='</td>
                <td valign="top" align="left" height="30">&nbsp;</td>
                <td valign="top" align="left" height="30">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30"># of Shelves : </td>
                <td valign="top" align="left" height="30">&nbsp;</td>
                <td valign="top" align="left" height="30">'.$dmg['sh_wh_num_shelf'].'                   
                   
              </td></tr>
          </tbody></table></td>
          <td width="10" valign="top" align="left">&nbsp;</td>
          <td valign="top" align="left" width="30%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td width="110" valign="top" align="left" height="30">Bulk/24 Pack CSD:</td>
              <td width="4" valign="top" align="left">&nbsp;</td>
              <td valign="top" align="left">'.$dmg['bulk_24pk'].'</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30">';
$html.=($dmg['blk_24_hl']=='H')? 'High':'';
$html.=($dmg['blk_24_hl']=='L')? 'Low':'';
$html.=($dmg['blk_24_hl']=='na')? 'N/A':'';
$html.='
</td>
              <td valign="top" align="left" height="30">&nbsp;</td>
              <td valign="top" align="left" height="30">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30" width="80"># of Shelves :</td>
              <td valign="top" align="left" height="30">&nbsp;</td>
              <td valign="top" align="left" height="30">'.$dmg['blk_24_numshelf'].'
                 
            </td></tr>
          </tbody></table></td>
          <td width="10" valign="top" align="left">&nbsp;</td>
          <td valign="top" align="left"><table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td width="135" valign="top" align="left" height="30">Premium 24 Pack CSD:</td>
              <td width="5" valign="top" align="left">&nbsp;</td>
              <td valign="top" align="left">'.$dmg['prem_24_pack'].'</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30">';
$html.=($dmg['prem_24_pack_hl']=='H')? 'High':'';
$html.=($dmg['prem_24_pack_hl']=='L')? 'Low':'';
$html.=($dmg['prem_24_pack_hl']=='na')? 'N/A':'';
$html.='
</td>
              <td valign="top" align="left" height="30">&nbsp;</td>
              <td valign="top" align="left" height="30">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30" width="80"># of Shelves :</td>
              <td valign="top" align="left" height="30">&nbsp;</td>
              <td valign="top" align="left" height="30">'.$dmg['prem_24_pack_numshelf'].'
                               
              </td>
            </tr>
          </tbody></table></td>
        </tr>
      </tbody></table>
';

$html.='<br/></br><br/></br><br/></br><table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
         

 <td valign="top" align="left" width="20%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td width="60" valign="top" align="left" height="30">New&nbsp;Age:&nbsp;</td>
                <td valign="top" align="left">'.$dmg['new_age'].'</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30">';
$html.=($dmg['new_age_hl']=='H')? 'High':'';
$html.=($dmg['new_age_hl']=='L')? 'Low':'';
$html.=($dmg['new_age_hl']=='na')? 'N/A':'';
$html.='
</td>
<td valign="top" align="left" height="30">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30" width="80"># of Shelves : </td>
                <td valign="top" align="left" height="30">'.$dmg['new_age_nushelf'].'
                  
            </td>
            
              </tr>
          </tbody></table></td>
          

<td width="10%" valign="top" align="left">&nbsp;</td>
        



  <td valign="top" align="left" width="15%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td width="80" valign="top" align="left" height="30">Bottled&nbsp;Juice:&nbsp;&nbsp;</td>
       
                <td valign="top" align="left">'.$dmg['botle_jc'].'</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30">';
$html.=($dmg['botle_jc_hl']=='H')? 'High':'';
$html.=($dmg['botle_jc_hl']=='L')? 'Low':'';
$html.=($dmg['botle_jc_hl']=='na')? 'N/A':'';
$html.='
</td>
                <td valign="top" align="left" height="30">&nbsp;</td>
 
              </tr>
              <tr>
                <td valign="top" align="left" height="30"># of Shelves : </td>

                <td valign="top" align="left" height="30">'.$dmg['botle_jc_numshelf'].'
                  
            </td>
              </tr>
          </tbody></table></td>
<td width="10%" valign="top" align="left">&nbsp;</td>


 <td valign="top" align="left" width="20%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td width="60" valign="top" align="left" height="30">Isoionics:&nbsp;</td>
                <td valign="top" align="left">'.$dmg['isionic'].'</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30">';
$html.=($dmg['isionic_hl']=='H')? 'High':'';
$html.=($dmg['isionic_hl']=='L')? 'Low':'';
$html.=($dmg['isionic_hl']=='na')? 'N/A':'';
$html.='
</td>
                <td valign="top" align="left" height="30">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30" width="80">#&nbsp;of&nbsp;Shelves: </td>
                <td valign="top" align="left" height="30">'.$dmg['isionic_numshelf'].'                   
               </td>
              </tr>
          </tbody></table></td>
<td width="10%" valign="top" align="left">&nbsp;</td>

<td valign="top" align="left" width="20%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td width="28" valign="top" align="left" height="30">Mix:</td>
              <td valign="top" align="left">'.$dmg['mix'].'</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30">';
$html.=($dmg['mix_hl']=='H')? 'High':'';
$html.=($dmg['mix_hl']=='L')? 'Low':'';
$html.=($dmg['mix_hl']=='na')? 'N/A':'';
$html.='
</td>
              <td valign="top" align="left" height="30">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30" width="80">#&nbsp;of&nbsp;Shelves: </td>
              <td valign="top" align="left" height="30">'.$dmg['mix_numshelf'].'                      
                 </td>
            </tr>
          </tbody></table></td>
          

        </tr>
      </tbody></table>';





$html.='<br/></br><br/></br><br/></br><table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
         

 <td valign="top" align="left" width="20%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td width="80" valign="top" align="left" height="30">P.E.T Water:&nbsp;</td>
                <td valign="top" align="left">'.$dmg['pet_water'].'</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30">';
$html.=($dmg['pet_water_hl']=='H')? 'High':'';
$html.=($dmg['pet_water_hl']=='L')? 'Low':'';
$html.=($dmg['pet_water_hl']=='na')? 'N/A':'';
$html.='
</td>
<td valign="top" align="left" height="30">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30" width="80"># of Shelves :&nbsp;</td>
                <td valign="top" align="left" height="30">'.$dmg['pet_water_numshelf'].'
                  
            </td>
            
              </tr>
          </tbody></table></td>
          

<td width="10%" valign="top" align="left">&nbsp;</td>
        



  <td valign="top" align="left" width="15%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td width="75" valign="top" align="left" height="30">Bulk&nbsp;Water:&nbsp;</td>
       
                <td valign="top" align="left">'.$dmg['bulk_water'].'</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30">';
$html.=($dmg['bulk_water_hl']=='H')? 'High':'';
$html.=($dmg['bulk_water_hl']=='L')? 'Low':'';
$html.=($dmg['bulk_water_hl']=='na')? 'N/A':'';
$html.='
</td>
                <td valign="top" align="left" height="30">&nbsp;</td>
 
              </tr>
              <tr>
                <td width="85" valign="top" align="left" height="30"># of Shelves : </td>

                <td  valign="top" align="left" height="30">'.$dmg['bulk_water_numshelf'].'
                  
            </td>
              </tr>
          </tbody></table></td>
<td width="10%" valign="top" align="left">&nbsp;</td>


 <td valign="top" align="left" width="20%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr>
                <td width="100" valign="top" align="left" height="30">Case PK Water:</td>
                <td valign="top" align="left">'.$dmg['case_pk'].'</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30">';
$html.=($dmg['case_pk_hl']=='H')? 'High':'';
$html.=($dmg['case_pk_hl']=='L')? 'Low':'';
$html.=($dmg['case_pk_hl']=='na')? 'N/A':'';
$html.='
</td>
                <td valign="top" align="left" height="30">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top" align="left" height="30" width="80">#&nbsp;of&nbsp;Shelves: </td>
                <td valign="top" align="left" height="30">'.$dmg['case_pk_numshelf'].'                   
               </td>
              </tr>
          </tbody></table></td>
<td width="10%" valign="top" align="left">&nbsp;</td>

<td valign="top" align="left" width="20%"><table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td width="100" valign="top" align="left" height="30">Sparkling Water:</td>
              <td valign="top" align="left">'.$dmg['spark_w'].'</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30">';
$html.=($dmg['spark_w_hl']=='H')? 'High':'';
$html.=($dmg['spark_w_hl']=='L')? 'Low':'';
$html.=($dmg['spark_w_hl']=='na')? 'N/A':'';
$html.='
</td>
              <td valign="top" align="left" height="30">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30" width="80">#&nbsp;of&nbsp;Shelves: </td>
              <td valign="top" align="left" height="30">'.$dmg['spark_w_numshelf'].'                      
                 </td>
            </tr>
          </tbody></table></td>
          

        </tr>
      </tbody></table>';






$html.='<br/></br><br/></br><br/></br><table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody><tr>
          <td valign="top" align="left">
          

<table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody><tr>
              <td width="60" valign="top" align="left" height="30">Cold&nbsp;box : </td>
              <td width="5" valign="top" align="left">&nbsp;</td>
              <td valign="top" align="left">'.$dmg['cold_box'].'</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30">';
$html.=($dmg['cold_box_hl']=='H')? 'High':'';
$html.=($dmg['cold_box_hl']=='L')? 'Low':'';
$html.=($dmg['cold_box_hl']=='na')? 'N/A':'';
$html.='
</td>
              <td valign="top" align="left" height="30">&nbsp;</td>
              <td valign="top" align="left" height="30">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top" align="left" height="30" width="27%">#&nbsp;of&nbsp;new&nbsp;glide&nbsp;sheets&nbsp;installed: </td>
              <td valign="top" align="left" height="30">&nbsp;</td>
              <td valign="top" align="left" height="30" width="50%">'.$dmg['cold_box_numshelf'].'
                 
                  </td>
            </tr>
          </tbody></table>
          
            <table width="100%" cellspacing="0" cellpadding="0" border="0">

              <tbody><tr>
                <td width="15%" valign="top" align="left" height="30"># Gliders Used: </td>
                <td  valign="top" align="left" height="30">20 0z&nbsp;:&nbsp; <label> '.$dmg['oz_20'].'</label></td>
                <td valign="top" align="left" width="13%">1L&nbsp;:&nbsp;'.$dmg['ltr_1'].'</td>
                <td valign="top" align="left" width="13%">10-12 0z&nbsp;:&nbsp;'.$dmg['oz_10_12'].'</td>
                <td valign="top" align="left" width="13%">&nbsp;&nbsp;&nbsp;&nbsp;32 0z&nbsp;:&nbsp;'.$dmg['oz_32'].'</td>
                <td valign="top" align="left" width="13%">&nbsp;&nbsp;&nbsp;&nbsp;2L&nbsp;:&nbsp;'.$dmg['ltr_2'].'</td>
                <td valign="top" align="left" width="13%">Red&nbsp;Bull&nbsp;:&nbsp;'.$dmg['red_bull'].'</td>
              </tr>
            </tbody></table>
            <br>
            <br>
            
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
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
              <tr> <td colspan="11"  width="100%">';
if($dmg['mngr_sign']!='')
{
    if($is_client==1)
{
$html.='<a href="'.$base_url.'content/upload_files/images/'.$dmg['mngr_sign'].'"><img  width="100px" height="100px" id="proj_sign_img_field" src="'.$image_dir.$dmg['mngr_sign'].'"/></a>';     
 }   
 else{
$size = getimagesize($image_dir.$dmg['mngr_sign']);

                    $html.='<img   ';
   if($size[0]>600) $html.=' width="600px" ';    
   if($size[1]>480) $html.=' height="480px" '; 
                    $html.='
                        id="proj_sign_img_field" src="'.$image_dir.$dmg['mngr_sign'].'"/>';
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
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
              <tbody><tr><td>&nbsp;</td></tr>';
            require 'sign_off_imgs_pdf.php';
    $html.='</tbody></table></td>
   
          </tr>
      </tbody></table>';
//echo $html;
require 'export_pdf.php'


?>