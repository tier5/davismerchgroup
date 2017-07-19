<?php
$page = 'project_grid';
require 'Application.php';
include $mydirectory.'/header.php';
?>

<div id="message" class="message_fixed"></div>
<div id="loading" class="message_fixed"><img src="<?php echo $mydirectory; ?>/images/loading.gif" alt="Loading..." /></div>
<div id="subpage" style="height: 700px;overflow-y: auto;"></div>
<div id="mail_dlg" ></div>

<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script>    
<script type="text/javascript" src="<?php echo $mydirectory;?>/js/flexigrid.pack.js"></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-timepicker-addon.js'></script>
<script type="text/javascript" src="<?php echo $mydirectory; ?>/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo $mydirectory;?>/js/PopupBox.js"></script>

<table class="prj_list" style="display: none"></table>
<script type="text/javascript">
    var loading = $("#loading");
    var msg = $("#message");
    var pid = $('#pid');
    $(".prj_list").flexigrid({
        url : 'get_projects.php',
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
			},{
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
                
            }, {
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
        sortname : "pid",
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
        datastring += '&pid[]='+id;
    });
    //alert(datastring);    
    showLoading();
    $.ajax({type: 'POST',url: 'mail.php',data: datastring,dataType: 'html',success: function(html){
             $('#mail_dlg').html(html);
        $('#mail_dlg').dialog( "open" );
            hideLoading();
        }});       
} else {
    return false;
} 
}
    function send_email(id) {
        showLoading();
        data='pid='+id;
        $.ajax({type: 'POST',url: 'mail.php',data: data,dataType: 'html',success: function(html){
        $('#mail_dlg').html(html);if($('#pid').val() > 0);
        $('#mail_dlg').dialog( "open");
        hideLoading();
  }});
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

$('#mail_dlg').dialog({
	autoOpen: false,
	width: 900,   
	modal: true,
	show: "blind",
	hide: "fade"
        //close: function() {$(".prj_list").flexReload();}
});
function submit_form()
{
    if($("#prj_name").val()=="")
        {
         show_msg('error',"Please Enter a project Name and fill the merchandiser Fields");   
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
function ajaxFileUpload(fid)
{
    showLoading();
    $.ajaxFileUpload({url:'file_upload.php',secureuri:false,fileElementId:fid,
        dataType: 'json',data:{pid:$('#pid').val(),fid:fid},
        success: function (data, status)
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
            show_msg('error',e);
            hideLoading();
        }
    })
    return false;
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
});
</script>

<script type="text/javascript">
function SendMail()
{
  var email = '';
  var subject = '';
  var mailBody = '';
  dataString = $("#pop").serialize();
  //mailBody = document.getElementById('divBody').innerHTML;
  //dataString += "&email="+email+"&subject="+subject;
  $.ajax({
		 type: "POST",
		 url: "mail_submit.php",
		 data: dataString,
		 dataType: "json",
		 success: function(data) 
		 {	
			 if(data!=null)
			 {	
				 if(data.error)
				 {
					 $("#msg_email").html("<div class='errorMessage'><strong>Sorry, " + data.error +"</strong></div>"); 
				 } 
				 else if(data.email != "")
				 {
					 $("#msg_email").html("<div class='errorMessage'><strong>Email were not send to following email Id's "+ data.email +" </strong></div>"); 
				 }
				 else 
				 {
					 $("#message").html("<div class='successMessage'><strong>Email Send Successfully.</strong></div>");
					 Fade();
				 }	
			 }
			 else
			 {
				 $("#msg_email").html("<div class='errorMessage'><strong>Sorry, Unable to process.Please try again later.</strong></div>"); 
			 }
		 }
		 });
  return false;
}









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
</script>

   <?php 
   include $mydirectory.'/trailer.php';
   ?>
   