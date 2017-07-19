<?php
require 'Application.php';
$pid=$_GET['pid'];

if(isset($pid)&&$pid!='')
{
     $query  ='select * from cvs_form where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$cvs = pg_fetch_array($result);

pg_free_result($result);
}
$proj_image=$cvs['proj_image'];
$file_name='cvs_form.pdf';
//echo 'dd'.$pid;
//print_r($frito);
$html='
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody><tr>
    <td height="30"><h2 align="center">CVS Pharmacy -- Area 18 CVS Stores</h2> </td>
  </tr>
  <tr>
    <td height="30" width="100%"><strong><br>
      BLITZ SECTION: ICE CREAM FROZEN FOOD<br>
      <br>
      STARTING DATE: JUNE 17TH, 2013</strong></td>
  </tr>

</tbody></table>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tbody><tr>
    <td valign="top" align="left" width="30%" >
	<fieldset class="fsd"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td valign="top" align="left" height="30" width="100%"><div align="center"><strong>BLITZ COMPANY SECTION </strong><br>
          must be filled out by Blitz company to be recorded </div></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" width="40%">STORE#  :  </td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left" width="60%">'.$cvs['store_num'].'</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
         
          </tr>
          <tr>
          <td valign="top" align="left">DATE :</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">'.$cvs['date'].'</td>
          </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" width="70%">SET COMPLETED?</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left" width="30%">';
$html.=($cvs['set_cmp']=='Y')? 'Yes':'No';
$html.='</td>
            </tr>
          <tr>
            <td valign="top" align="left" height="30">NEW ITEMS CUT IN?</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">';
$html.=($cvs['new_it_cut']=='Y')? 'Yes':'No';
$html.='</td>
            </tr>
          <tr>
            <td valign="top" align="left" height="30">DC MARKED?</td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left">';
$html.=($cvs['dc_mark']=='Y')? 'Yes':'No';
$html.='</td>
            </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30">CURRENT SIZE OF SET: '.$cvs['curr_size'].'   
              (# of doors)</td>
            </tr>
          <tr>
            <td valign="top" align="left" height="30">Ice Cream Vault :     
              <label>'.$cvs['icecream_vault'].'
              </label>
              (# of doors)</td>
            </tr>
          <tr>
            <td valign="top" align="left" height="30">'.$cvs['walk_vlt1'].'</td>
            </tr>
          <tr>
            <td valign="top" align="left" height="30">Frozen Food Vault :'.$cvs['froz_fd_vlt'].'  
              (# of doors)</td>
            </tr>
          <tr>
            <td valign="top" align="left" height="30">'.$cvs['walk_vlt2'].'</td>
            </tr>
          <tr>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30" width="100%">IF NOT COMPLETE EXPLANATION : </td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30">'.$cvs['cmp_exp'].'</td>
          </tr>
          <tr>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30" width="100%">COMMENTS:</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30">'.$cvs['comments'].'</td>
          </tr>
        </tbody></table></td>
      </tr>
    </tbody></table>
	</fieldset>
	
	</td>
    <td width="10" valign="top" align="left">&nbsp;</td>
    <td valign="top" align="left" width="30%"><fieldset class="fsd"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td valign="top" align="left" height="30"  width="30%" width="100%"><div align="center"><strong>NEED SECTION INFORMATION </strong><br>
        </div></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" width="60%">Manufacture of Cold box: </td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left" width="40%"><label>'.$cvs['man_coldbox'].'
            </label></td>
            </tr>
        </tbody></table></td>
      </tr>
      
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30" width="50%">Model#:</td>
            <td width="10" valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left" colspan="5" width="50%">'.$cvs['model_num'].'</td>
            </tr>
          <tr>
            <td valign="top" align="left" height="30" colspan="7" width="100%">ANY SHELVES MISSING IN ICECREAM? </td>
            </tr>
          <tr>
            <td valign="top" align="left" height="30" colspan="7">';
$html.=($cvs['icecream_misshel']=='Y')? 'Yes':'No';
$html.='</td>
            </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30"><table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody><tr>
            <td valign="top" align="left" height="30">If yes how many and what door#: '.$cvs['how_many_doors'].'
              </td>
            </tr>
          <tr>
            <td valign="top" align="left">&nbsp;</td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30">ANY SHELVES MISSING IN FROZEN FOOD? </td>
            </tr>
          <tr>
            <td valign="top" align="left" height="30">';
$html.=($cvs['froz_misshel']=='Y')? 'Yes':'No';
$html.='</td>
            </tr>
          <tr>
            <td valign="top" align="left" height="30">If yes how many and what door#: '.$cvs['frz_ml_drnum'].'
              </td>
          </tr>
          <tr>
            <td valign="top" align="left" height="30">&nbsp;</td>
            </tr>
        </tbody></table></td>
      </tr>
    </tbody></table>
	</fieldset></td>
    <td width="10" valign="top" align="left">&nbsp;</td>
    <td valign="top" align="left" width="30%"><fieldset class="fsd"><table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody><tr>
        <td valign="top" align="left" height="30"  width="100%"><div align="center"><strong>STORE MANAGER SECTION </strong><br>
        </div></td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">ARE ALL THE SETS TO THE NEW SCHEMATIC?</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30">';
$html.=($cvs['new_schema']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%"><br>
          DID THE TAGS GET REPLACED :</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30">';
$html.=($cvs['tag_replace']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">WAS A COPY OF SCHEMATIC LEFT N THE CASE</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30">';
$html.=($cvs['copy_schema']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%"><br>
          DOES THE CASE GET ICED UP AT ALL?</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30">';
$html.=($cvs['iced_up']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">IS THE TEMP OF THE CASE COLD ENOUGH FOR THE PRODUCT?</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30">';
$html.=($cvs['case_temp']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%"><br>
          IS THERE A SECONDARY LOCATION OR DC/REPACK</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30">';
$html.=($cvs['dc_repack']=='Y')? 'Yes':'No';
$html.='</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">IF NO TO ANY QUESTIONS PLEASE WRITE WHY IN COMMENTS:</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">'.$cvs['quest'].'</td>
      </tr>
      <tr>
        <td valign="top" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">STORE AUTHORIZED SIGNATURE: </td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">'.$cvs['store_sign'].'</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">DATE:'.$cvs['store_date'].' 
</td>
      </tr>
      <tr>
        <td valign="top" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">PLEASE PRINT YOUR NAME AND TITLE:</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30" width="100%">'.$cvs['name_title'].'</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30"><br>
          STORE STAMP </td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30">

       <img width="100px" height="100px" src="../../upload_files/images/'.$proj_image.'" id="proj_img_field">
                
            
        </td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" align="left" height="30">&nbsp;</td>
      </tr>
    </tbody></table>
	</fieldset></td>
  </tr>
  
</tbody></table>
';
//echo $html;
require 'export_pdf.php'


?>