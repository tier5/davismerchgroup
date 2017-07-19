<table width="90%"  id="glry_<?php echo $i; ?>">
    <tr><td height="5%">&nbsp;</td></tr>

    <tr><td align="left">Images:
         
  <input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="img_project" id="img_project" 
         onchange="javascript:signoffImgFileUpload('img_project','I','<?php echo $form_type;?>', 960,720);" />          
        </td><td>&nbsp;</td><td>&nbsp;</td>
    </tr>
    
  <tr>
      <td width="20%">&nbsp;</td>
      <td width="60%" ><div  align="left" id="img_cnt_signoff">
      <?php 
         // echo "ff".$temp.'gbg';

 
  
   require 'signoff_view_imgs.php';
    echo $html;
      ?>        
          </div>   
      </td>  
      <td>
  &nbsp;
      </td> 
  </tr>    
</table>

<table>
<tr>
                <td height="30" align="left" valign="top"><label>
                  <input type="button"  value="Save" onclick="javascript:signOffSubmit();" />
                </label></td>
                <td height="30" align="left" valign="top">&nbsp;</td>
                <td height="30" align="left" valign="top"><input type="button" onclick="javascript:resetSignOffForm();" value="Reset" /></td>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><input type="button" id="sign_off_pdf_btn" value="Export to PDF" onclick="javascript:exportPDF('<?php echo $form_type;?>');" /></td>
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