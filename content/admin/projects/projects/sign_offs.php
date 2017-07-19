<?php
require 'Application.php';
if(!isset($_GET['pid'])||$_GET['pid']=='')
{
echo '<br/><h3>Please save the project first...</h3>';
exit();
}
?>
<br/><br/>
<table width="80%" align="left">
    <tr>
 <td width="10%">Sign Off Form</td>   
 <td >
     <select onchange="javascript:loadForm();" id="sign_off_frm" >
         <option value="dmg_form.php">DMG Form</option>
         <option value="dmg_convenience_form.php">DMG Convenience Form </option>
         <option value="stater_bros_form.php">Stater Bros. Form</option>
         <option value="frito_lay_rest_form.php">Frito-Lay Form</option>
         <option value="cvs_form.php">CVS Form</option>
         <option value="nestle_form.php">Nestle Form</option>
         
     </select>   
 </td>
    </tr>    
    
    <tr>
        <td>&nbsp;</td>
        <td>
            <form id="sign_off_form" method="post" action="">
            <div id="sign_off_form_cnts"></div>
            </form>
            </td>
    </tr>
</table>