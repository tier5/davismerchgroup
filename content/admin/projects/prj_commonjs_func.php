<script type="text/javascript">
function merch_confirm(m_id,obj,pid)
{
    
    
    var data='m_id='+m_id+'&conf=';
    if(obj.attr('checked')=='checked') 
    {    data+='TRUE';
         merch_confirm_sendmail(data);
    }
    else { 
if($('#merch_cfrm_sm_div').length>0)$('#merch_cfrm_sm_div').remove();
  var htm_cnt='<div id="merch_cfrm_sm_div"><input type="hidden" name="m_id" value="'+m_id+'"/>';    
  htm_cnt+='<input type="hidden" name="conf" value="FALSE"/></div>';  
     $('#sub_content_confirm_form').append(htm_cnt);             
     $('#sub_content_confirm').dialog('open');             
          
    }
    

    
}

function submit_merch_deny()
{
    
 if($.trim($('#deny_reason').val())=='')
                {
          alert('Please state your reason and continue...');  
          return;
                }
merch_confirm_sendmail($('#sub_content_confirm_form').serialize());
$('#sub_content_confirm').dialog('close');    
}

function merch_confirm_sendmail(data)
{
       $.ajax({
		 type: 'POST',
		 url: 'merch_confirm.php',
		 data: data,
                 datatype:'json',
                 success:function(res){
                     
        <?php if($merch_frm_prj==1){ ?>         
        load_div(2);             
        <?php } else{?>
            $('#subpage').dialog('close');
             if(obj.attr('checked')=='checked'){}
            else view_merchandiser(pid);
            <?php }?>
    if(res.merch=='<?php echo $_SESSION['employeeID'];?>') {           
   //if(obj.attr('checked')=='checked')              
  // loadTimesheet('dt='+res.due_date+'&from_prject=1&m_id='+m_id);             
                 }
                 }
          
		 });     
}


 function loadTimesheet(dataString){
     
 dataString+='&from_proj=1';
showLoading();
$.ajax({type: 'POST',url: '<?php echo $mydirectory;?>/timesheet/addtime.php',data: dataString,dataType: 'html',success: function(html){
sub_content.html(html);
//initTime();
sub_content.dialog( "open" );
hideLoading();
//var sp=dataString.split('=');
//initDatetimepicker(sp[1]);
}});
} 
function send_merch_email(m_id,pid)
{
       showLoading();
        data='m_id='+m_id;
        $.ajax({
         type: 'POST',
         url: 'merch_mail_submit.php',
         data: data,
         dataType: 'json',
         success: function(data){
      hideLoading();             
          if(data!=null)
            {	
                    if(data.error!="")
                    {
                        show_msg('error',data.error);
                    } 
                    else if(data.email != "")
                    {
                            show_msg('error','Unable to send email to : '+data.email);
                    }
                    else 
                    {
                            show_msg('success','Email Send Successfully.');
                           <?php if($merch_frm_prj==1){ ?>         
        load_div(2);             
        <?php } else{?>
            $('#subpage').dialog('close');
            view_merchandiser(pid);
            <?php }?>
                    }	
            }
            else
            {
                    show_msg('error','Sorry, Unable to process.Please try again later.'); 
            }
          
      
  }
  ,error:function(data){
   hideLoading();  
   show_msg('error',"Unable to send Email...");   
}
}); 
}


</script>  