<?php
$page = 'project_grid';
require 'Application.php';
include $mydirectory.'/header.php';
?>

<div id="message" class="message_fixed"></div>
<div id="loading" class="message_fixed"><img src="<?php echo $mydirectory; ?>/images/loading.gif" alt="Loading..." /></div>
<div id="subpage" style="height: 700px;overflow-y: auto;"></div>


<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script>    
<script type="text/javascript" src="<?php echo $mydirectory;?>/js/flexigrid.pack.js"></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-timepicker-addon.js'></script>
<script type="text/javascript" src="<?php echo $mydirectory; ?>/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo $mydirectory;?>/js/PopupBox.js"></script>


<table>
 <tr ><td colspan="4"><font size="3">Search Projects</font></td></tr>
 <tr>
     <td>Date From:</td>
     <td><input size="15px" type="text" name="date_from" id="date_from"  value="<?php 
     if(isset($_GET['date_from'])&&$_GET['date_from']!="")
         echo $_GET['date_from'];
     ?>" /></td>
     <td>Date To:</td>
     <td><input size="15px" type="text" name="date_to" id="date_to"  value="<?php 
     if(isset($_GET['date_to'])&&$_GET['date_from']!="")
         echo $_GET['date_to'];
     ?>" /></td>
 </tr>
 <tr >
 <td colspan="2" align="right"><input type="button" value="Search" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all"
    onclick="javascript:searchPrjs();" /></td>   
 <td colspan="2" align="left"><input onclick="javascript:resetSearchPrjs();" type="button" value="Reset" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all"/>

 </td></tr>
</table>

<table class="prj_list" style="display: none"></table>
<script type="text/javascript">
   datastr="";
   d="";
   d="<?php if(isset($_GET['date_from'])&& $_GET['date_from']!="") echo $_GET['date_from']; else echo "";?>";
   if(d!="")
       {
       
   if( datastr=="")
       datastr+='?date_from='+d;
   else
       datastr+='&date_from='+d;
       }
   
   d="<?php if(isset($_GET['date_to'])&& $_GET['date_to']!="") echo $_GET['date_to']; else echo "";?>";
   if(d!="")
       {
   if( datastr=="")
       datastr+='?date_to='+d;
   else
       datastr+='&date_to='+d;
       }
    var loading = $("#loading");
    var msg = $("#message");
    var pid = $('#pid');
    $(".prj_list").flexigrid({
        url : 'get_projects.php'+datastr,
        dataType : 'json',
        colModel : [ {
                display : 'Project Name',
                name : 'prj.prj_name',
                width : 150,
                sortable : true,
                align : 'left'
            },{
                display : 'Edit',
                name : 'edit',
                width:30,
                sortable : false,
                align : 'center'
			},
                        
                {
                display : 'Copy',
                name : 'copy',
                width:30,
                sortable : false,
                align : 'center'
			},        
                        
                        {
                display : 'Completed',
                name : 'complete',
                width:50,
                sortable : false,
                align : 'center'
			},{
				display : 'Email',
				name : 'email',
				width :50,
				sortable : false,
				align : 'center'
            }],
        buttons : [ {
                name : 'Add Job',
                bclass : 'add',
                onpress : show_project
            }, {
                name : 'Open Jobs',
                bclass : 'open',
                onpress : open_projects
				
            },{
                name : 'Closed Jobs',
                bclass : 'close',
                onpress : closed_projects
				
            }, {
                name : 'Send Report',
                bclass : 'email',
                onpress : send_report
                
            }, 
            
            {
                name : 'Export Report',
                bclass : 'excel',
                onpress :spreadSheet
                
            },
            {
                separator : true
            } ],
        searchitems : [ {
                display : 'Project Name',
                name : 'prj.prj_name',
                isdefault : true
            }, {
                display : 'Location',
                name : 'prj.location'                
            } ],
        sortname : "prj.pid",
        sortorder : "desc",
        usepager : true,
        title : '<center>Projects</center>',
        useRp : true,
        rp : 15,
        showTableToggleBtn : false,
        width : 'auto',
        height : 'auto'
    });
	function open_projects(){
		$('.open').parent().parent().hide();
		$('.close').parent().parent().show();
		$('.prj_list').flexOptions({ url: 'get_projects.php'}).flexReload();
	}
	function closed_projects(com, grid){
		$('.close').parent().parent().hide();
		$('.open').parent().parent().show();		
		$('.prj_list').flexOptions({ url: 'get_projects.php?'+'close=1'}).flexReload();
	}
	function close_project(id)
	{
	showLoading();
        $.ajax({type: 'POST',url: 'close_project.php',data: {pid:id},dataType: 'json',success: function(data){if(data != '') show_msg('error',data);$('.prj_list').flexOptions({ url: 'get_projects.php'}).flexReload();
        hideLoading();
	}});
        }
    function delete_project(com, grid) {
        if (com == 'Delete Projects') {
            confirm('Delete ' + $('.trSelected', grid).length + ' items?');
			
        } 		
    }

function send_report(com, grid) {
if (com=='Send Report')
{
    var datastring = 'report=1';
    $('.trSelected', grid).each(function() {
        var id = $(this).attr('id');
        id = id.substring(id.lastIndexOf('row')+3);
        datastring += '&email_pid[]='+id;
    });
    //alert(datastring);    
    showLoading();
    $.ajax({type: 'POST',
        url: 'mail_submit.php',
        data: datastring,
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
                            $show_msg('success','Email Send Successfully.');
                    }	
            }
            else
            {
                    show_msg('error','Sorry, Unable to process.Please try again later.'); 
            }
        }
      ,error:function(data){
   hideLoading();  
   show_msg('error',data.error);  
      }
});       
} else {
    return false;
} 
}
    function send_email(id) {
        showLoading();
        data='email_pid='+id;
        $.ajax({
         type: 'POST',
         url: 'mail_submit.php',
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
                            $show_msg('success','Email Send Successfully.');
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
    
    
   function spreadSheet()
{
 
if($.trim($("#date_from").val())!="" && $.trim($("#date_to").val())!="")
{



  showLoading();  
     data='';
 if($.trim($("#date_from").val())!="")
     {
    if(data=="")     
   data+='date_from='+$("#date_from").val();  
else
data+='&date_from='+$("#date_from").val();    
     }
     
     if($.trim($("#date_to").val())!="")
     {
    if(data=="")     
   data+='?date_to='+$("#date_to").val();  
else
data+='&date_to='+$("#date_to").val();    
     }   
    

  $.ajax({type: 'POST',
  url: 'genSpreadSheet.php',
  data:data,
  dataType: 'json',
  
  success: function(data){
  hideLoading();    
  location.href='download_csv.php?file='+data.fileName;
}
  });
} 
else
{
 show_msg('error','Plese select a date range and try again..');
}
}  
    
    
    
    function show_project(id){
        if(id == 'undefined' || id == '') 
            id = 0;
        showLoading();
        data="pid="+id;
        data+="&curr_merch="+$("#current_merch").val();
        $.ajax({type: 'POST',url: 'project.php',data: data,dataType: 'html',success: function(html){
        $('#subpage').html(html);if($('#pid').val() > 0)show_files($('#pid').val());
        $('#subpage').dialog( "open" );
        hideLoading();
  }});
    }
    function showLoading(){loading .css({visibility:"visible"}) .css({opacity:"1"}) .css({display:"block"});msg .css({visibility:"hidden"})}
//hide loading bar
function hideLoading(){loading.fadeTo(1000, 0, function(){loading .css({display:"none"});msg .css({visibility:"visible"});});};
$('#subpage').dialog({
	autoOpen: false,
	width: 990,
        height: 700,
	modal: true,
	show: "blind",
	hide: "fade",
        close: function() {$(".prj_list").flexReload();}
});


function submit_form()
{
    if($("#prj_name").val()=="")
        {
         show_msg('error',"Please Enter a project Name and fill the merchandiser Fields");   
            return;
        }
        
        
flag=0;
$('input[name="merch[]"]').each(function(){
if($(this).val()==$("#merch_1").val())
{	
flag=1;
}
});

if(flag==1)
{
show_msg('error',"This Merchandiser is already selected...");
return;
}    
       
        showLoading();
        
        data=$("#project_form").serialize();
        //  data+="&curr_merch="+$("#current_merch").val();
        data+="&pid="+$("#pid").val();
        data+="&prj_name="+$("#prj_name").val();
        
        $.ajax({type: 'POST',
            url: 'project_submit.php',
            data: data,
            dataType: 'json',
            success: function(data){
                if(data.error != '') {show_msg('error',data.error);}
                else{ show_msg('success','Successfully Saved The Project Details');if(data.pid > 0){
                        $('#pid').val(data.pid);$("#u_file").css({display:""});}}
                hideLoading();
                
                show_project(data.pid);
                

      
  }});
}
window.message_display = null;
function show_msg(cl,ms)
{
    msg.addClass(cl).html(ms).fadeIn();
    window.message_display = setInterval(function() {msg.fadeOut(1600,remove_msg);}, 6000);
}
function remove_msg()
{
    msg.removeClass('success').removeClass('error').html('');
    clearInterval(window.message_display);window.message_display = null;
}

function show_files(id)
{
     if(typeof(id) == 'undefined' || id == '') 
            id = 0;
        showLoading();
        $.ajax({type: 'POST',url: 'prj_files.php',data: {pid:id},dataType: 'html',success: function(html){
        $('#file_view').html(html);
        hideLoading();
  }});
}
function delete_file(id){
    if(typeof(id) == 'undefined' || id == '') 
            id = 0;
    if(id > 0){
        showLoading();
        $.ajax({type: 'POST',url: 'delete_file.php',data: {pid:$('#pid').val(),upid:id},dataType: 'html',success: function(html){
        $('#file_view').html(html);
        hideLoading();
  }});}
}
/*function u_cid(obj) {
    $("#client_id").val(client[$(obj).val()]);
}*/
function popbox(obj){
    PopEx(obj, null,  null, 0, 0, 50, 'PopBoxImageLarge');
}
$('document').ready(function(){
 $('.open').parent().parent().hide();
 $("#date_from").datepicker();
 $("#date_to").datepicker();
});
</script>

<script type="text/javascript">

function deleteMerchants(merch_id,pid)
{
    data="merch_id="+merch_id;
        $.ajax({
            type: "POST",
            url: "project_submit_merch.php?opt=delete",
            data:data,
            
            success:
                function(data)
            {
                show_project(pid);
                
            }
        }); 
}

function showDate(obj)
{
	$(obj).datepicker({
            changeMonth: true,
            changeYear: true
        }).click(function() { $(obj).datepicker('show'); });
	$(obj).datepicker('show');
}

function resetForm()
{
 $("#merch_1").val(0);
 $("#due_date").val("<?php echo date('m/d/Y');?>");
 $("#st_time").val("");
 $("#location").val(0);
 $("#cid").val(1);
 $("#store_num").val("");
 $("#notes").val("");
}

function searchPrjs()
{
    data='';
 if($.trim($("#date_from").val())!="")
     {
    if(data=="")     
   data+='?date_from='+$("#date_from").val();  
else
data+='&date_from='+$("#date_from").val();    
     }
     
     if($.trim($("#date_to").val())!="")
     {
    if(data=="")     
   data+='?date_to='+$("#date_to").val();  
else
data+='&date_to='+$("#date_to").val();    
     }
     
     location.href="index.php"+data;
}

function resetSearchPrjs()
{
     
     location.href="index.php";
}

function copy_project(id)
{
   if(id == 'undefined' || id == '') 
            id = 0;
        showLoading();
        data="pid="+id;
        
          $.ajax({
		 type: "POST",
		 url: "project_copy.php",
		 data: data,
		 dataType: "json",
		 success: function(data) 
		 {
   location.href="index.php";         
                 }	
		 });
}
function getstorenum(){
	
	
	data ='chain='+$("#location").val();
	    $.ajax({
		 type: "POST",
		 url: "project_storenum.php",
		 data: data,

		 success: function(data) 
		 {
         $('#store_num').html(data);
                 }	
		 });
	
}

function get_contact(){

	data ='store_num='+$("#store_num").val();
	    $.ajax({
		 type: "POST",
		 datatype:'json',
		 url: "project_contact.php",
		 data: data,

		 success: function(data) 
		 {
			 if(data.add!='')
         $('#address').val(data.add);
		 	if(data.phone!='')
		 $('#phone').val(data.phone);
		 	if(data.city!='')
		 $('#city').val(data.city);
		 	if(data.zip!='')
		 $('#zip').val(data.zip);
                 }	
		 });
	
}

function ajaxFileUpload(fid)
{
    showLoading();
    $.ajaxFileUpload({url:'file_upload.php',secureuri:false,fileElementId:fid,
        dataType: 'json',data:{pid:$('#pid').val(),fid:fid},timeout:60000,
        success: function (data)
        {
           
            if(typeof(data.error) != 'undefined')
            {
                if(data.error != ''){
                    show_msg('error',data.error);
                }else{
                    show_msg('success',data.msg);if(typeof(data.html) != 'undefined' && data.html != ''){$('#file_view').html(data.html);}
                }
            }else {show_msg('error','Server processing error. Please try uploading again!');}
            hideLoading();
        },
        error: function (data, status, e)
        {
              alert('error');
            show_msg('error',e);
            hideLoading();
        }
    });
    return false;
}
          
          

</script>

   <?php 
   include $mydirectory.'/trailer.php';
   ?>
   