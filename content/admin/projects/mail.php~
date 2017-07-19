<?php
require 'Application.php';

extract($_POST);
?>
<div id="dialog-form" title="Submit By Email" class="popup_block">
    <div align="center" id="msg_email"></div>
    <p>All form fields are required.</p>  
    <fieldset>
        <form id="pop" name="pop" method="post" >
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="10">&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td width="10" height="30">&nbsp;</td>
                    <td colspan="3" class="emailBG">
                        <input type="button" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" value="Send" onclick="javascript:SendMail();"/>
            <input type="reset" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" onclick="javascript:$('#mail_dlg').dialog('close');" value="Discard" />
                    <td width="10">&nbsp;</td>
                </tr>
                <tr>
                    <td width="10" height="30">&nbsp;</td>
                    <td width="75" class="emailBG"><label for="email">Email :</label></td>
                    <td class="emailBG"><input name="email" type="text" class="emailTxtBox" id="email" value="" size="35px"  /></td>
                    <td width="10" class="emailBG">&nbsp;</td>
                    <td width="10">&nbsp;</td>
                </tr>
                <tr>
                    <td height="40">&nbsp;</td>
                    <td class="emailBG"> <label for="subject">Subject :</label></td>
                    <td class="emailBG"><input  name="subject" type="text" class="emailTxtBox" id="subject" value="Davis Merchandising: <?php if(isset($report)) echo 'Project Reports'; else echo 'Project Details'; ?>)" size="35px" />
<?php
 if(isset($report) && isset($pid))
 {
     for($i=0;$i < count($pid); $i++)
     {
         echo '<input type="hidden" name="email_pid[]" value="'.$pid[$i].'" />';
     }
      echo '<input type="hidden" id="report" name="report" value="1" />';
 }
 else {
  echo '<input type="hidden" id="email_pid" name="email_pid" value="'.$pid.'" />';   
}
?>
                        </td>
                    <td class="emailBG">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>


        </form>
        <p>

        </p>
    </fieldset>
</div>