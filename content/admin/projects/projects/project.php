<?php
require 'Application.php';
function build_http_query( $query ){

    $query_array = array();

    foreach( $query as $key => $key_value ){

        $query_array[] = urlencode( $key ) . '=' . urlencode( $key_value );

    }

    return implode( '&', $query_array );

}
if($_SESSION['emp_type']!=0&&isset($_GET['pid'])) 
{
  $where='';  
 if($_SESSION['emp_type']!=0) 
{
  $where.=" AND mer.cid='".$_SESSION['client_id']."'";      
}   
$query ='SELECT distinct ( prj.prj_name ), prj.pid, chain.chain,m1.firstname, m1.lastname, reg.region, st.sto_num, m.st_time 
from projects  as prj  
inner join prj_merchants_new as m on m.m_id = (select mer.m_id from prj_merchants_new as mer left join tbl_region as reg on reg.rid=mer.region '
.' left join tbl_chain as ch on ch.ch_id=mer.location left join tbl_chainmanagement as st on st.chain_id = mer.store_num '
.'where mer.pid = prj.pid '.$where.' limit 1 )
inner join "employeeDB" as m1 on m1."employeeID"= m.merch left join tbl_chainmanagement as st on st.chain_id = m.store_num  '.
 '  left join tbl_chain as chain on chain.ch_id=m.location  left join tbl_region as reg on reg.rid=m1.region where prj.pid='.$_GET['pid'];
//echo $query;

if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
if(pg_num_rows($result)<=0)
    return;

pg_free_result($result);

}
$query  = ("SELECT \"ID\", \"clientID\", \"client\", \"active\" " .
        "FROM \"clientDB\" " .
        "WHERE \"active\" = 'yes' $client_sql " .
        "ORDER BY \"client\" ASC");
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result))
{
    $client[] = $row;
}
pg_free_result($result);
$edit_signoff=0;
if(isset($_GET['pid']))
{
$query='select * from signmerch_list as l left join "employeeDB" as e on e."employeeID"=l.emp_id where pid='.$_GET['pid'].' and l.emp_id='.$_SESSION['employeeID'];
 if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$sign_merch_list[]=$row;   
}
if(count($sign_merch_list)>0)
{
$edit_signoff=1;    
}
 else
{
$edit_signoff=0;    
}

}

include $mydirectory.'/header.php';
//extract($_POST);

?>
<div id="message" class="message_fixed"></div>
<div id="loading" class="message_fixed"><img src="<?php echo $mydirectory; ?>/images/loading.gif" alt="Loading..." /></div>
<div id="dialogue">
<div id='sub_content'></div>
</div>
<div id='sub_content_confirm' style="display:none;">
<form id='sub_content_confirm_form'>    
  <table width="100%">  
  <tr><td>State your reason for the denying the job.</td></tr>    
   <tr><td><textarea id="deny_reason" name="deny_reason" cols="75" rows="10"></textarea></td></tr> 
    <tr><td><input class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" type="button" value="Submit" onclick="javascript:submit_merch_deny();"/></td></tr> 
  </table></form>  
</div>
</div>
<input type="hidden" value="<?php echo trim($_GET['pid']);?>" id="pid" />
<a href="index.php?dd12=1<?php if(isset($_GET['close'])&&$_GET['close']==1) echo '&close=1'; echo '&'.build_http_query($_GET);?>" style="text-decoration:none;"><input type="button" value="back"/></a>
<br/>
<h3 align="center">Add/Edit Job Details</h3>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1" onclick="load_tab(1);">Project Details</a></li>
                 <li><a href="#tabs-2" onclick="load_tab(2);">Merchandiser</a></li>
		<li><a href="#tabs-3" onclick="load_tab(3);">Image Gallery</a></li>
                   <?php  if($_SESSION['perm_admin'] == "on" || $is_client==1 || $edit_signoff==1){ ?>
		<li><a href="#tabs-4" onclick="load_div(4);">Sign Offs</a></li>  
                <?php }?>
                <li><a href="#tabs-5" onclick="load_tab(5);">Out of Stock</a></li>
         <!--       <li><a href="#tabs-6" onclick="load_tab(6);">Low Stock</a></li>-->
               
	</ul>
	<div id="tabs-1"></div>
	<div id="tabs-2"></div>
	<div id="tabs-3"></div>
        <div id="tabs-4"></div>
        <div id="tabs-5"></div>
    <!--    <div id="tabs-6"></div>-->
</div>

<div id="app_content" style="border:0px solid; padding: 5px"></div>

<link href="<?php echo $mydirectory; ?>/css/screen.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo $mydirectory;?>/css/jquery-ui_tabs.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $mydirectory;?>/css/jquery-ui-1.8.19.custom.css" />

<script type="text/javascript">
 var from_proj=1;    
 var from_report=0;
    </script>

<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<!--<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script>  -->
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui_tabs.js'></script>
<!--<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script>-->
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-timepicker-addon.js'></script>
<script type="text/javascript" src="<?php echo $mydirectory; ?>/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo $mydirectory;?>/js/PopupBox.js"></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery.validate.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory;?>/js/timesheet.js'></script>
  <script type="text/javascript" src="<?php echo $mydirectory;?>/js/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo $mydirectory;?>/js/jquery.validate_frm.js"></script>
    <script type="text/javascript" src="<?php echo $mydirectory;?>/js/bbq.js"></script>
   <!-- <script type="text/javascript" src="<?php echo $mydirectory;?>/js/jquery-ui-1.8.19.custom.min.js"></script>-->
    <script type="text/javascript" src="<?php echo $mydirectory;?>/js/jquery.form.wizard.js"></script>
<style>
.ui-dialog-titlebar-close{
    display: none;
}
.step {
  display: block;
}
</style>

<script type="text/javascript">
var PopBoxImageLarge ='PopBoxImageLarge';
   var loading = $("#loading");
    var msg = $("#message");   
   
$(document).ready(function() {
  
//$( "#tabs" ).tabs();
$( "#tabs" ).tabs({
  <?php if(isset($_GET['signtype'])){?> 
    active: 3            
 <?php }else if($_GET['m_id']>0||$_GET['pid']>0){ ?>   
  active: 1
     <?php }?>
      
});
//$( "#tabs" ).tabs("load",0);
//alert('<?php echo $_GET['m_id']; ?>');
<?php if(isset($_GET['signtype'])){ ?>
 load_tab(4);   
  <?php }  if($_GET['m_id']>0||$_GET['pid']>0){ ?>
load_tab(2);
<?php } else{ ?>
 load_tab(1);   
    <?php } ?>
 
   //$( '#due_date' ).datepicker(); 
   //$('#st_time').timepicker({ ampm: true, minuteGrid: 15});
   
$('#sub_content_confirm').dialog({
width:'650',
height:'400',
autoOpen: false,
modal: true
});

});



function load_tab(num)
{

    if($('#tab_hidden_'+num).length<=0)
        {     
        load_div(num);
        }
        
}

var m_id_key=1;
	function load_div(num)
	{
            
       showLoading();
       $('#tabs-'+num).html('');     
       var data='pid='+$('#pid').val()+'&m_pid=<?php echo $_GET['m_pid']; if(isset($_GET['close']) && $_GET['close'] == 1) echo '&close=1';?>';
 $.ajax({
		 type: "POST",
		 datatype:'html',
		 url: "proj_"+num+".php",
		 data: data,
		  success: function(data) 
		 {

$('#tabs-'+num).html(data);
$('#tabs-'+num).append('<input type="hidden" id="tab_hidden_'+num+'"/>');
$( '.date_field' ).datepicker();
if(num==4)
    {
<?php if(isset($_GET['signtype'])){?>
 loadForm('<?php echo $_GET['fid']; ?>','<?php echo $_GET['signtype']; ?>'); 
 <?php }?>
    }
if(num==1)
    {
 $('#tab_hidden_2').remove();       
    }
if(num==2)
    {
//$('#st_time').timepicker({ ampm: true, minuteGrid: 15});
 $('#st_time').customTimePicker(); 
 $('#st_time').val($('#st_time_hdn').val()).trigger('change'); 
 if(m_id_key==1)
     {
 <?php if($_GET['m_id']>0){ ?>   
    editMerchants(<?php echo $_GET['m_id'];?>);    
<?php } ?>  
 m_id_key=0;   
     }
    }
//if(num==4) //loadForm();
<?php if($_SESSION['emp_type']!=0) 
{ ?>
disableEverything(num);
<?php } ?>

if(num==1)
 {show_files('<?php echo trim($_GET['pid']); ?>');
     }

    
		 		},
                complete:function(){
                      hideLoading();
                }                
		 });
	}


function disableEverything(num)
{
  $('#tabs-'+num+' input').each(function(){
      
  if($(this).attr("id")!='sign_off_pdf_btn'){
$(this).attr('disabled','disabled') ;
  }
});  


$('#tabs-'+num+' select').each(function(){
    
if($(this).attr("id")!='sign_off_frm'){
$(this).attr('disabled','disabled') ;  
}
});  

$('#tabs-'+num+' textarea').each(function(){
$(this).attr('disabled','disabled') ;  
}); 
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

  function showLoading(){
    //  alert('k');
      loading .css({visibility:"visible"}) .css({opacity:"1"}) .css({display:"block"});msg .css({visibility:"hidden"})}
//hide loading bar
function hideLoading(){loading.fadeTo(1000, 0, function(){loading .css({display:"none"});msg .css({visibility:"visible"});});};

function get_region() {
	data ='merch_1='+$("#merch_1").val();
	    $.ajax({
		 type: "POST",
		 datatype:'json',
		 url: "get_region.php",
		 data: data,
		  success: function(data) 
		 {
			
			 //if(data.reg!='')
         $('#region1').val(data.rid);
         $('#region_text').val(data.reg);
		 		}	
		 });
	
}


function get_projectcontact(){

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
    		 $('#phone').val(data.phone);
             }	
		 });
	
}


function get_contact(num){

	data ='store_num='+$("#store_num_"+num).val();
	    $.ajax({
		 type: "POST",
		 datatype:'json',
		 url: "project_contact.php",
		 data: data,

		 success: function(data) 
		 {
			 if(data.add!='')
         $('#address_'+num).val(data.add);
		 	if(data.phone!='')
		 $('#phone_'+num).val(data.phone);
		 	if(data.city!='')
		 $('#city_'+num).val(data.city);
		 	if(data.zip!='')
		 $('#zip_'+num).val(data.zip);
    		// $('#phone_'+num).val(data.phone);
        if($('#store_name_'+num).val()!=0)
            {
        $('#address_'+num).attr('READONLY','READONLY');  
        $('#phone_'+num).attr('READONLY','READONLY');
        $('#city_'+num).attr('READONLY','READONLY');
        $('#zip_'+num).attr('READONLY','READONLY');
            }
            else{
        $('#address_'+num).removeAttr('READONLY');  
        $('#phone_'+num).removeAttr('READONLY');
        $('#city_'+num).removeAttr('READONLY');
        $('#zip_'+num).removeAttr('READONLY');
                 }
             }	
		 });
	
}

function getProjectstorenum(){
	
	
	data ='chain='+$("#location").val();
	    $.ajax({
		 type: "POST",
		 url: "project_storenum.php",
		 data: data,

		 success: function(data) 
		 {
         $('#store_num').html(data);
         $('#store_num').trigger('change');
         $('#address').val('');
          $('#phone').val('');
           $('#city').val('');
            $('#zip').val('');
             
                 }	
		 });
	
}
function getProjectEditstorenum(store_num){
	
	
	data ='chain='+$("#location").val();
	    $.ajax({
		 type: "POST",
		 url: "project_storenum.php",
		 data: data,

		 success: function(data) 
		 {
         $('#store_num').html(data);
         $('#store_num').val(store_num).trigger('change');
         //$('#address').val('');
         // $('#phone').val('');
           //$('#city').val('');
           // $('#zip').val('');
             
                 }	
		 });
	
}


function getstorenum(num){
	 if($('#store_name_'+num).val()==''){$('#other_tr').hide(); } 
 else if($('#store_name_'+num).val()==0)   
{
    $('#store_num_'+num).html('');
    $('#other_tr').show();
    
     $('#address_2').removeAttr('READONLY');  
        $('#phone_2').removeAttr('READONLY');  
        $('#city_2').removeAttr('READONLY');  
        $('#zip_2').removeAttr('READONLY');
    
    return;}
else
   $('#other_tr').hide();
	
	data ='chain='+$("#store_name_"+num).val();
	    $.ajax({
		 type: "POST",
		 url: "project_storenum.php",
		 data: data,

		 success: function(data) 
		 {
         $('#store_num_'+num).html(data);
          $('#store_num_'+num+' option:first').attr('selected','selected').trigger('change');

    
             
                 }	
		 });
	
}

function submit_merch_num(pid)
{
var data='pid='+pid+'&num_merch='+$('#num_merch').val();
 $.ajax({type: 'POST',
            url: 'submit_merch_num.php',
            data: data,
            dataType: 'json',
            success: function(){
     
               
                    hideLoading();
                    show_msg('success','Successfully Saved The Project Details');
                 
              	load_div(2);


      
  },
  error:function(){
      hideLoading();
      alert("error");
  }

});

}

function submit_form(type)
{


    if($("#prj_name").val()=="")
        {
          
         show_msg('error',"Please Enter a project Name and fill the merchandiser Fields");   
           return;
        }
        

       
        showLoading();
        data='';
     if(type=='merch'){
        data+=$("#project_form").serialize();
     }else{

         data+="&m_pid="+$("#m_pid").val()+'&num_merch='+$('#num_merch').val(); 
     }
        
        //  data+="&curr_merch="+$("#current_merch").val();
                 data+="&prj_name="+$("#prj_name").val();
        data+="&pid="+$("#pid").val();
       data+='&type='+type;
        
//alert(data);
        $.ajax({type: 'POST',
            url: 'project_submit.php',
            data: data,
            dataType: 'json',
            success: function(data){
                if(data.error != '') {show_msg('error',data.error); hideLoading();return;}
                else{
               
                    
                    show_msg('success','Successfully Saved The Project Details');
                    if(data.pid > 0){
                        $('#pid').val(data.pid);$("#u_file").css({display:""});}}
               // hideLoading();
             
             if(type=='proj'){
              <?php if($_GET['pid']!=''){?>
              	load_div(1);
              <?php } else {?>
           location.href='project.php?pid='+data.pid;
           <?php }?>
}
else load_div(2);
      
  },
  error:function(){
      alert("error");
  }

});
}







 function ajaxFileUpload(file_cat, type, width, height){

           var index=101;
              var fileId = file_cat;
              // document.getElementById('processing').style.display= '';
              $.ajaxFileUpload(
              {
                  url:'fileUpload.php',
                  secureuri:false,
                  fileElementId:fileId,
                  dataType: 'json',
                  async:false,
                  data:{fileId:fileId, type:type, index:index, width:width, height:height,pid:$('#pid').val(),cat:file_cat},
                  timeout:60000,
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
		  
		  
		  function get_store(){	
	data ='chain='+$("#st_name").val();
	    $.ajax({
		 type: "POST",
		 url: "project_storenum.php",
		 data: data,

		 success: function(data) 
		 {
         $('#sto_num').html(data);
         
             
                 }	
		 });
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



function deleteMerchants(merch_id,pid)
{
    data="merch_id="+merch_id+'&pid='+pid;
        $.ajax({
            type: "POST",
            url: "project_submit_merch.php",
            data:data,
            
            success:
                function(data)
            {
              
              load_div(2);
                
            }
        }); 
}
function editMerchants(merch_id)
{
    data="merch_id="+merch_id;
        $.ajax({
            type: "POST",
            url: "project_merch_edit.php",
            data:data,
            datatype:"json",
            async:"false",
            success:
                function(data)
            {
 $('#merch_1').val(data.merch);
  $('#merch_id_hdn').val(data.m_id);
 $('#due_date').val(data.due_date);
 $('#location').val(data.location);
 $('#cid').val(data.cid);
 $('#store_num').val(data.store_num);
 // $('#store_num').val(data.store_num);
  $('#address').val(data.address);
        $('#phone').val(data.phone);
        $('#city').val(data.city);
        $('#zip').val(data.zip);
        $('#notes').val(data.notes);
   getProjectEditstorenum(data.store_num);      
   $('#st_time').val(data.st_time).trigger('change'); 
    $('#region1').val(data.rid); 
    $('#sign_off_add_block').html(data.sign_off); 
    $('#signmerch_add_block').html(data.sign_merch_list); 
    //$('#region_text').val(data.region); 
            }
        }); 
}





 function imgGlryFileUpload(file_cat, type,row_count, width, height){

           var index=101;
              var fileId = file_cat;
              // document.getElementById('processing').style.display= '';
              $.ajaxFileUpload(
              {
                  url:'imgGlryfileUpload.php',
                  secureuri:false,
                  fileElementId:fileId,
                  dataType: 'json',
                  async:false,
                  data:{fileId:fileId, type:type, index:index, width:width, height:height,pid:$('#pid').val(),cat:file_cat,glry_id:$("#glry_id_"+row_count).val(),rc:row_count},
                  timeout:60000,
              success: function (data)
        {
           
            if(typeof(data.error) != 'undefined')
            {
                if(data.error != ''){
                    show_msg('error',data.error);
                }else{
                    show_msg('success',data.msg);if(typeof(data.html) != 'undefined' && data.html != ''){$('#img_cnt_'+row_count).html(data.html);}
                }
            }else {show_msg('error','Server processing error. Please try uploading again!');}
            hideLoading();
        },
        error: function (data, status, e)
        {
         
            show_msg('error',"error");
            hideLoading();
        }
              });
  
              return false;
          }
          
          
          
          function signoffImgFileUpload(file_cat, type,form_type,width, height,form_id){

           var index=101;
              var fileId = file_cat;
              // document.getElementById('processing').style.display= '';
              $.ajaxFileUpload(
              {
                  url:'signoffImgFileUpload.php',
                  secureuri:false,
                  fileElementId:fileId,
                  dataType: 'json',
                  async:false,
                  data:{fileId:fileId, type:type, index:index, width:width, height:height,pid:$('#pid').val(),cat:file_cat,form_type:form_type,form_id:form_id},
                  timeout:60000,
              success: function (data)
        {
           
            if(typeof(data.error) != 'undefined')
            {
                if(data.error != ''){
                    show_msg('error',data.error);
                }else{
                    show_msg('success',data.msg);if(typeof(data.html) != 'undefined' && data.html != ''){$('#img_cnt_signoff').html(data.html);}
                }
            }else {show_msg('error','Server processing error. Please try uploading again!');}
            hideLoading();
        },
        error: function (data, status, e)
        {
         
            show_msg('error',"error");
            hideLoading();
        }
              });
  
              return false;
          }
            function signoffImgFileUpload_pop(file_cat, type,form_type,width, height,form_id){

           var index=101;
              var fileId = file_cat;
              // document.getElementById('processing').style.display= '';
              $.ajaxFileUpload(
              {
                  url:'signoffImgFileUpload.php',
                  secureuri:false,
                  fileElementId:fileId,
                  dataType: 'json',
                  async:false,
                  data:{fileId:fileId, type:type, index:index, width:width, height:height,pid:$('#pid').val(),cat:file_cat,form_type:form_type,form_id:form_id},
                  timeout:60000,
              success: function (data)
        {
           
            if(typeof(data.error) != 'undefined')
            {
                if(data.error != ''){
                    show_msg('error',data.error);
                }else{
                    show_msg('success',data.msg);if(typeof(data.html) != 'undefined' && data.html != ''){$('#img_cnt_signoff_pop').html(data.html);}
                }
            }else {show_msg('error','Server processing error. Please try uploading again!');}
            hideLoading();
        },
        error: function (data, status, e)
        {
         
            show_msg('error',"error");
            hideLoading();
        }
              });
  
              return false;
          }
		  
		  
		  function get_store(){	
	data ='chain='+$("#st_name").val();
	    $.ajax({
		 type: "POST",
		 url: "project_storenum.php",
		 data: data,

		 success: function(data) 
		 {
         $('#sto_num').html(data);
         
             
                 }	
		 });
	}
        


function addBarcode(row_count)
{
    if($.trim($('#barcode_'+row_count).val())=='') return;
    $('#barcode_cnt_'+row_count).append('<div><input type="text" class="barcode_val'+row_count+'" value="'+$('#barcode_'+row_count).val()+'" name="barcode[]"/>'+
  '<img width="20px" height="20px" src="<?php echo $mydirectory;?>/images/delete.png" onclick="javascript:delbarcode(\'new\','+row_count+',$(this));"/></div><br/>');    
$('#barcode_'+row_count).val('');
   saveBarcode(row_count);
}

function saveBarcode(row_count)
{
    showLoading();
    var data=  $('#stockform_'+row_count).serialize();
data+='&stock_id='+$('#stock_id_'+row_count).val(); 
data+='&cl_id='+$('#stock_cid_'+row_count).val(); 
 //alert(data);
$.ajax({
		 type: "POST",
		 url: "addBarcode.php",
		 data: data,
                 datatype:'json',   
		 success: function(data) 
		 {
                     
           hideLoading();       
         
             
                 },
                 error:function(){
                hideLoading();      
                 }
		 });  
}

function delGlryFile(id,row_count,gl_id)
{
    if(typeof(id) == 'undefined' || id == '') 
            id = 0;
    if(id > 0){
        showLoading();
        $.ajax({type: 'POST',url: 'delete_glry_file.php',data: {fid:id,glry_id:gl_id,rc:row_count},dataType: 'html',success: function(html){
        $('#img_cnt_'+row_count).html(html);
        hideLoading();
  }});} 
}

function delSignOffFile(fid,form_type,form_id)
{
    if(typeof(fid) == 'undefined' || fid == '') 
            fid = 0;
    if(fid > 0){
        showLoading();
        $.ajax({type: 'POST',url: 'delete_signoff_file.php',data: {fid:fid,pid:$('#pid').val(),form_type:form_type,form_id:form_id},dataType: 'html',success: function(html){
        $('#img_cnt_signoff').html(html);
        hideLoading();
  }});} 
}


function addGallery()
{
var flag=0;    
$(".cl_list").each(function(){
if($(this).val()==$("#cid_addnew").val())    
 flag=1;   
});

if(flag==1)
    {
        alert("This client is already selected...");
        return;
    }
showLoading();
var data='pid='+$('#pid').val()+'&cl_id='+$("#cid_addnew").val();  
  $.ajax({
		 type: "POST",
		 url: "addGallery.php",
		 data: data,
                 datatype:'json',   
		 success: function(data) 
		 {
                     
           hideLoading();       
        addGallery2(data.glry_id);
         
             
                 },
                 error:function(){
                hideLoading();      
                 }
		 });  
}

function addGallery2(glry_id)
{
var row_count=Number($('#row_count').val())+1;
$('#row_count').val(Number($('#row_count').val())+1);
var htm='<table width="90%" align="left" id="glry_'+row_count+'"><tr><td height="5%">&nbsp;</td></tr>';
htm+=' <tr><td align="left" width="25%">Client: <input type="hidden"  id="glry_id_'+row_count+'" value="'+glry_id+'" />'+
        '<select align="left" class="cl_list" name="cid['+row_count+']" id="cid_'+row_count+'" style="width: 200px;" onchange="javascript:updateGlrClient('+glry_id+',$(this));">';
htm+='<?php for ($j = 0; $j < count($client); $j++)
                               {
                                   
     echo '<option value="' . $client[$j]['ID'] . '">' . str_replace("'","\'",$client[$j]['client']) . '</option>';
                                    
                               } 
            ?>';    
                    
 htm+=' </select></td><td>&nbsp;</td><td>&nbsp;</td></tr>';
 
 htm+='<tr><td align="right">Images:'+
  '<input type="file" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" name="img_'+row_count+'" id="img_'+row_count+'"'+ 
         'onchange=\'javascript:imgGlryFileUpload("img_'+row_count +'","I","'+row_count+'", 960,720);\' />'+          
        '</td>'+
    '</tr>';
htm+='<tr><td width="20%">&nbsp;</td><td width="60%" ><div width="60%" align="left" id="img_cnt_'+row_count+'">'+
       
          '</div> </td>  <td>'+
      ' <img src="<?php echo $mydirectory.'/images/drop.png'?>" onclick="javascript:deleteGallery('+row_count+','+glry_id+');"/> '+   
      '</td>  </tr>  '+  
'</table>';




$("#glry_cnts").append(htm);
$('#cid_'+row_count).val($("#cid_addnew").val());
}



function addStock()
{
//var flag=0;    
//$(".cl_list").each(function(){
//if($(this).val()==$("#stock_cid_addnew").val())    
// flag=1;   
//});
//
//if(flag==1)
//    {
//        alert("This client is already selected...");
//        return;
//    }
showLoading();
var data='pid='+$('#pid').val()+'&cl_id='+$("#stock_cid_addnew").val();  
  $.ajax({
		 type: "POST",
		 url: "addStock.php",
		 data: data,
                 datatype:'json',   
		 success: function(data) 
		 {
                     
           hideLoading();       
        addStock2(data.stock_id);
         
             
                 },
                 error:function(){
                hideLoading();      
                 }
		 });  
}


 function addStock2(stock_id)
{
var row_count=Number($('#stock_row_count').val());
$('#stock_row_count').val(Number($('#stock_row_count').val())+1);
var htm='<form id="stockform_'+row_count+'"><table width="90%" align="left" id="stock_'+row_count+'"><tr><td height="5%">&nbsp;</td></tr>';
htm+=' <tr><td align="left" width="25%">Client: <input type="hidden"  id="stock_id_'+row_count+'" value="'+stock_id+'" />'+
        '<select align="left" class="stock_cl_list" name="cid['+row_count+']" id="stock_cid_'+row_count+'" style="width: 200px;" onchange="javascript:updateGlrClient('+stock_id+',$(this));">';
htm+='<?php for ($j = 0; $j < count($client); $j++)
                               {
                                   
     echo '<option value="' . $client[$j]['ID'] . '">' . str_replace("'","-",$client[$j]['client']) . '</option>';
                                    
                               } 
            ?>';    
                    
 htm+=' </select></td><td>&nbsp;</td><td>&nbsp;</td></tr>';
 
 htm+='<tr><td align="left">Barcode:'+
  '<input type="text"  name="barcode_'+row_count+'" id="barcode_'+row_count+'"/>'+ 
         '<input type="button" value="Add" onclick=\'javascript:addBarcode('+row_count+');\' />'+          
        '</td>'+
    '</tr>';
htm+='<tr><td width="60%" ><div width="60%" align="left" id="barcode_cnt_'+row_count+'">'+
       
          '</div> </td>  <td>'+
      ' <img src="<?php echo $mydirectory.'/images/drop.png'?>" onclick="javascript:deleteStock('+row_count+','+stock_id+');"/> '+   
      '</td>  </tr>  '+  
'</table></form>';




$("#stock_cnts").append(htm);
$('#stock_cid_'+row_count).val($("#stock_cid_addnew").val());
}


function deleteGallery(id,glry_id)
{
    
    showLoading();
    var data='pid='+$('#pid').val()+'&glry_id='+glry_id;
   $.ajax({
		 type: "POST",
		 url: "delGallery.php",
		 data: data,
                 datatype:'json',   
		 success: function(data) 
		 {
               hideLoading();         
          if(data.status=='1')
              {
                  
          if(Number($('#row_count').val())>0)
          $('#row_count').val(Number($('#row_count').val())-1);           
               
        show_msg('success','Deleted successfully');
        $("#glry_"+id).remove();
              }
              else show_msg('error','Could not delete.Try again...');
                 }	
		 });   
}

function deleteStock(id,stock_id)
{
    
    showLoading();
    var data='pid='+$('#pid').val()+'&stock_id='+stock_id;
   $.ajax({
		 type: "POST",
		 url: "delStock.php",
		 data: data,
                 datatype:'json',   
		 success: function(data) 
		 {
               hideLoading();         
          if(data.status=='1')
              {
                  
          if(Number($('#stock_row_count').val())>0)
          $('#stock_row_count').val(Number($('#stock_row_count').val())-1);           
               
        show_msg('success','Deleted successfully');
        $("#stock_"+id).remove();
              }
              else show_msg('error','Could not delete.Try again...');
                 }	
		 });   
}



function loadForm(form_id,type)
{
    if($('#sign_off_frm').length>0){
    
    var data='pid='+$('#pid').val()+'<?php if(isset($_GET['close']) && $_GET['close'] == 1) echo '&close=1';?>&form_id='+form_id;
    if(type!='new')
   var url=''+type+'.php';
else
    var url=$('#sign_off_frm').val();
     showLoading();
   $.ajax({
		 type: "POST",
		 url: url,
		 data: data,
                 datatype:'html',   
		 success: function(res) 
		 {
               hideLoading();         
         $('#sign_off_form_cnts').html(res);
         $('html, body').animate({
    scrollTop: $("#sign_off_form_cnts").offset().top
}, 500);
          $('.date_field').datepicker();  
            if($('#store_name_2').val()!=0)
            {
        $('#address_2').attr('READONLY','READONLY');  
        $('#phone_2').attr('READONLY','READONLY');
        $('#city_2').attr('READONLY','READONLY');
        $('#zip_2').attr('READONLY','READONLY');
            }
            changeStoreValidation();
            if($('#sign_off_frm').val()=='ralphs_reset.php')
                {
             miss_change('anm_store');    
             miss_change('mhrd'); 
             miss_change('ssr_frm'); 
             miss_change('itemM_frm'); 
                }
                else  if($('#sign_off_frm').val()=='dmg_convenience_form.php')
                {
             miss_change('wa_qleq');       
                }
                 else  if($('#sign_off_frm').val()=='ralphs_checklist.php')
                {
             miss_change('ssr_frm');     
             miss_change('misrodamage'); 
                }
                     else  if($('#sign_off_frm').val()=='dmg_form.php')
                {
             csdCheckFunc(); 
             dmgSelHideFields('shasta');dmgSelHideFields('blk24'); dmgSelHideFields('prem24');
             dmgSelHideFields('newage');    dmgSelHideFields('bottlej');    dmgSelHideFields('isonic');
             dmgSelHideFields('mix');    dmgSelHideFields('pet');    dmgSelHideFields('bulkw');
             dmgSelHideFields('case_pk_numshelf');    dmgSelHideFields('sparkw');    dmgSelHideFields('coldbox');
                }
          
          <?php if($_SESSION['emp_type']!=0) 
{ ?>
disableEverything(4);
<?php } ?>
             
               }
               ,error:function()
               {
            hideLoading(); 
            show_msg('error','Sorry an error occurs while processing.Please try again...');
               }
		 });   

}
}


function signOffSubmit(data)
{
   
 //alert(data);
  $.ajax({
		 type: 'POST',
		 url: 'sign_off_submit.php',
		 data: data,
                 datatype:'json',   
		 success: function(res) 
		 {
               hideLoading();  
           if(res.status=='1')
               {
          show_msg('success','Updated successfully');  
          load_div('4');  
          //if(res.reload_form=='yes')
              {
                  
            // alert('k');
          // loadForm(res.form_id,res.form_type);       
              }
               }
               else
                   {
          show_msg('error','Sorry an error occurs while processing.Please try again...');            
                   }
             
               }
               ,error:function()
               {
            hideLoading(); 
            show_msg('error','Sorry an error occurs while processing.Please try again...');
               }
		 });   


}

function copyForm(form_id,form_type)
{
    showLoading();
    var data='form_id='+form_id+'&form_type='+form_type;
   $.ajax({
		 type: 'POST',
		 url: 'copy_form.php',
		 data: data,
                 datatype:'json', 
		 success: function(res) 
		 {
               hideLoading();  
           if(res.status=='1')
               {
          show_msg('success','Copied successfully');  
         
                  
            // alert('k');
           loadForm(res.form_id,res.form_type);       
         
               }
               else
                   {
          show_msg('error','Sorry an error occurs while processing.Please try again11...');            
                   }
             
               }
               ,error:function()
               {
            hideLoading(); 
            show_msg('error','Sorry an error occurs while processing.Please try again...');
               }
		 });      
    
}

function deleteForm(form_id,form_type)
{
    showLoading();
    var data='form_id='+form_id+'&form_type='+form_type;
   $.ajax({
		 type: 'POST',
		 url: 'delete_form.php',
		 data: data,
                 datatype:'json', 
		 success: function(res) 
		 {
               hideLoading();  
           if(res.status=='1')
               {
          show_msg('success','Deleted successfully');  
         
load_div('4');    
         
               }
               else
                   {
          show_msg('error','Sorry an error occurs while processing.Please try again11...');            
                   }
             
               }
               ,error:function()
               {
            hideLoading(); 
            show_msg('error','Sorry an error occurs while processing.Please try again...');
               }
		 });      
    
}


function exportPDF(url,form_id)
{
    window.open(url+'_pdf.php?pid='+$('#pid').val()+'&form_type='+url+'<?php if(isset($_GET['close']) && $_GET['close'] == 1) echo '&close=1';?>&form_id='+form_id);
    //location.href=url+'_pdf.php?pid='+;
   
}


function formFileUpload(file_cat, type,target,row_count, width, height){

           var index=101;
              var fileId = file_cat;
              // document.getElementById('processing').style.display= '';
              $.ajaxFileUpload(
              {
                  url:'formFileUpload.php',
                  secureuri:false,
                  fileElementId:fileId,
                  dataType: 'json',
                  async:false,
                  data:{fileId:fileId, type:type, index:index, width:width, height:height,pid:$('#pid').val(),cat:file_cat,glry_id:$("#glry_id_"+row_count).val(),rc:row_count},
                  timeout:60000,
              success: function (data)
        {
           
            if(typeof(data.error) != 'undefined')
            {
                if(data.error != ''){
                    show_msg('error',data.error);
                }else{
                    show_msg('success',data.msg);
                    $("#"+target).val(data.fil_name);
                   // alert("#"+target+"_field");
                    $("#"+target+"_field").attr("src","<?php echo $image_dir;?>"+data.fil_name);
                }
            }else {show_msg('error','Server processing error. Please try uploading again!');}
            hideLoading();
        },
        error: function (data, status, e)
        {
         
            show_msg('error',"error");
            hideLoading();
        }
              });
  
              return false;
          }
		  
		  

function updateGlrClient(glry_id,obj)
{
  
var flag=0;    
$(".cl_list").each(function(){
if($(this).val()==obj.val())    
 flag+=1;   
});

if(flag==2)
    {
        alert("This client is already selected...");
        load_div(2); 
         return;
    }  
var data='glry_id='+glry_id+'&cl_id='+obj.val()+'&type=updt';  
  $.ajax({
		 type: "POST",
		 url: "addGallery.php",
		 data: data,
                 datatype:'json',   
		 success: function(data) 
		 {
                     
         // alert('k');
         
             
                 },
                 error:function(){
               
                 }
		 });  
    
}


function delbarcode(id,form_id,obj){
    if(id=='new') obj.parent().remove();
    else
    $('#barcode_'+id).remove();
     saveBarcode(form_id);
}


function resetSignOffForm()
{
$('#form input').each(function(){
  if($(this).attr('type')=='text') $(this).val('');  
  if($(this).attr('type')=='radio') $(this).removeAttr('checked');  
  if($(this).attr('type')=='checkbox') $(this).removeAttr('checked'); 
}); 

$('#form select').each(function(){
 $(this)[0].selectedIndex=0;   
});  

$('#form textarea').each(function(){
 $(this).val('');   
}); 

$('#proj_sign_img_field').attr('src','');
$('#proj_image_field').attr('src','');
$('#proj_sign_img').val('');
$('#proj_image').val('');

    
}

(function($){
jQuery.fn.customTimePicker = function(){
      return this.each(function() {
 // alert($('#city').attr('name'));        
  var i;
  var htm_cnt='<div class="custom_time_picker"><table><tr>';
  htm_cnt+='<td>Hr</td><td>Min</td><td>A/P</td></tr>';        
   htm_cnt+='<tr><td><select class="cust_time_hr" onchange="changeTime($(this));">';
   for(i=1;i<=12;i++) htm_cnt+='<option>'+minTwoDigits(i)+'</option>';
   htm_cnt+='</select></td>';
      htm_cnt+='<td><select class="cust_time_min" onchange="changeTime($(this));">';
   for(i=0;i<60;i++) htm_cnt+='<option>'+minTwoDigits(i)+'</option>';
   htm_cnt+='</select></td>';
      htm_cnt+='<td><select class="cust_time_am" onchange="changeTime($(this));">';
htm_cnt+='<option>AM</option>';
htm_cnt+='<option>PM</option>';
   htm_cnt+='</select>';  
   htm_cnt+='</td></tr><table><input  type="text" style="display:none;" class="cust_time_text" name="'+$(this).attr('name')+'" id="'+$(this).attr('id')+'"  value="'+$(this).attr('value')+'"/></div>';
 $(this).replaceWith(htm_cnt);
chng($(this));
 });
 

};

})(jQuery);

function minTwoDigits(n) {
  return (n < 10 ? '0' : '') + n;
}


function chng(obj)
{
$( ".cust_time_text" ).change(function() {
if($('.custom_time_picker').children('.cust_time_text').val()=='') return;
var time=$('.cust_time_text').val();
if(time.indexOf(" ")==-1)
    {
 var t=time.split(':');
$('.cust_time_hr').val(t[0]);
$('.cust_time_min').val(t[1]);
$('.cust_time_am').val(t[2]);       
    }
    else{
var t=time.split(' ');
var t2=t[0].split(':');
$('.cust_time_hr').val(t2[0]);
$('.cust_time_min').val(t2[1]);
$('.cust_time_am').val(t[1]);
    }
});
}

  function changeTime(obj) {
var time=obj.parents('.custom_time_picker').find('.cust_time_hr').val()+':'+obj.parents('.custom_time_picker').find('.cust_time_min').val()+' '+
obj.parents('.custom_time_picker').find('.cust_time_am').val();
obj.parents('.custom_time_picker').children('.cust_time_text').val(time);

      return false;
    }
    
    
function showNoOptMsg(id,type)
{
 if(type=='N')
     $('#'+id).show();
 else
     $('#'+id).hide();
}
    
function newJob()
{
var data='pid='+$('#pid').val()+'&from_merchtab=1';

$.ajax({
url:'project_copy.php',
data:data,
datatype:'json',
type:'post',
success:function(res)
{
  // window.open('<?php //echo $mydirectory;?>/admin/projects/project.php?pid='+res.pid,'_blank'); 
location.href='<?php echo $mydirectory;?>/admin/projects/project.php?pid='+res.pid;
}
});
   // window.open('<?php echo $mydirectory;?>/admin/projects/project.php?m_pid='+$('#m_pid').val(),'_blank');
}
    
    
  
    
 function loadTimesheet_new(dt)
{
$( "#sub_content" ).dialog('close');
loadTimesheet(dt+'&add_new=1'); 
}   


function resetForm()
{
 $("#merch_1").val(0);
 $("#due_date").val("<?php echo date('m/d/Y');?>");
 $("#st_time").val("");
 $("#location").val(0);
 //$("#cid").val(1);
 $("#store_num").html('<option selected="" value="0">--Select--</option>');
 $("#notes").val("");
 $("#merch_id_hdn").val(0);
  $("#region1").val("");
   $('#region_text').val("");
  $('#address').val("");
  $('#phone').val("");
    $('#city').val("");
  $('#zip').val("");
    $('#due_date').val("");
    $('#sign_off_add_block').html("");
  $('#cid option:first').attr('selected','selected');
  $('.cust_time_hr option:first').attr('selected','selected');
  $('.cust_time_min option:first').attr('selected','selected');
  $('.cust_time_am option:first').attr('selected','selected');
         
}

function loadTimesheet_edit(time_id)
{
$( "#sub_content" ).dialog('close');
loadTimesheet('id='+time_id); 
}

function signoff_addNewStore()
{
 var htm='<tr><td height="30" class="white-bg"><input class="required ui-wizard-content ui-helper-reset ui-state-default" name="ssr[store][]" type="text"  size="8" maxlength="8" /></td>'
+'<td class="white-bg"><input class="required ui-wizard-content ui-helper-reset ui-state-default" name="ssr[comm_code][]" type="test"  size="10" maxlength="10" /></td>'
+'<td class="white-bg"><input class="required ui-wizard-content ui-helper-reset ui-state-default" name="ssr[krog_cat][]" type="text"  size="100" maxlength="100" /></td></tr>';
$('#grid-new').append(htm);   
}

function signoff_DeleteStore(ss_it_id,type)
{
    var data='ss_it_id='+ss_it_id;
   $.ajax({
		 type: "POST",
		 url: "signoff_DeleteStore.php",
		 data: data,
                 datatype:'json',   
		 success: function(data) 
		 {  
        $('#'+type+'_'+ss_it_id).remove();                
      // loadForm();
                 },
                 error:function(){
               
                 }
		 });   
}
function signoff_DeleteitemM(ss_it_id,type)
{
    var data='ss_it_id='+ss_it_id;
   $.ajax({
		 type: "POST",
		 url: "signoff_DeleteItemM.php",
		 data: data,
                 datatype:'json',   
		 success: function(data) 
		 {  
         $('#'+type+'_'+ss_it_id).remove();               
      // loadForm();
                 },
                 error:function(){
               
                 }
		 });   
}
function signoff_addNewitemM()
{
var htm='<tr><td height="30" ><input class="required ui-wizard-content ui-helper-reset ui-state-default" name="itm[schem_v][]" type="text" size="5" /></td>'
      +'<td class="white-bg"><input class="required ui-wizard-content ui-helper-reset ui-state-default" name="itm[upc][]" type="text"  size="15" /></td>'
      +'<td class="white-bg"><input class="required ui-wizard-content ui-helper-reset ui-state-default" name="itm[prod_name][]" type="text"  size="50" /></td>'
      +'<td class="white-bg"><select class="required ui-wizard-content ui-helper-reset ui-state-default" name="itm[height][]"><option value="">--select--</option>';
  for(i=0;i<=100;i++)
      {
    htm+='<option value="'+i+'">'+i+'</option>';      
      }
       htm+='</select></td>'
        +'<td class="white-bg"><select class="required ui-wizard-content ui-helper-reset ui-state-default" name="itm[width][]"><option value="">--select--</option>';
  for(i=0;i<=100;i++)
      {
    htm+='<option value="'+i+'">'+i+'</option>';      
      }
       htm+='</select></td>'
      +'<td class="white-bg"><select class="required ui-wizard-content ui-helper-reset ui-state-default" name="itm[depth][]"><option value="">--select--</option>';
  for(i=0;i<=100;i++)
      {
    htm+='<option value="'+i+'">'+i+'</option>';      
      }
       htm+='</select></td>'   
   +'<td class="white-bg"><select class="required ui-wizard-content ui-helper-reset ui-state-default" name="itm[shelf][]" ><option value="">--select--</option>';
  for(i=0;i<=100;i++)
      {
    htm+='<option value="'+i+'">'+i+'</option>';      
      }
       htm+='</select></td>'      
      +'<td class="white-bg"></td></tr>';
$('#grid-new-item').append(htm);   
}

function miss_change(obj)
{
  
if($('#'+obj+'_radio').attr('checked')=='checked')   
    { 
   $('#'+obj+'_sec').show();    
   if(obj=='anm_store')
       {
   $('#anm_store_sec').find('input').addClass('required ');      
       }
         else if(obj=='wa_qleq')
       {
   $('#wa_qleq_sec').find('input').addClass('required ');      
       }
           else if(obj=='mhrd')
       {
   $('#mhrd_sec').find('input').addClass('required ');      
       }
             else if(obj=='ssr_frm')
       {
   $('#ssr_frm_sec').find('input').addClass('required ');      
       }
       else if(obj=='itemM_frm')
       {
   $('#itemM_frm_sec').find('input').addClass('required ');      
       } 
          else if(obj=='misrodamage')
       {
   $('#misrodamage_sec').find('input').addClass('required ');      
       }   
    }
    else
        {
    $('#'+obj+'_sec').hide();  
      if(obj=='anm_store')
       {
   $('#anm_store_sec').find('input').removeClass('required ');      
       }
        else if(obj=='wa_qleq')
       {
   $('#wa_qleq_sec').find('input').removeClass('required ');      
       } 
           else if(obj=='mhrd')
       {
   $('#mhrd_sec').find('input').removeClass('required ');      
       } 
          else if(obj=='ssr_frm')
       {
   $('#ssr_frm_sec').find('input').removeClass('required ');      
       } 
             else if(obj=='itemM_frm')
       {
   $('#itemM_frm_sec').find('input').removeClass('required ');      
       } 
    else if(obj=='misrodamage')
       {
   $('#misrodamage_sec').find('input').removeClass('required ');      
       }      
        }
}

function duplicate_merch(merch_id)
{
   data="merch_id="+merch_id;
        $.ajax({
            type: "POST",
            url: "project_merch_edit.php",
            data:data,
            datatype:"json",
            async:"false",
            success:
                function(data)
            {
 $('#merch_1').val(data.merch);
  $('#merch_id_hdn').val('');
 $('#due_date').val(data.due_date);
 $('#location').val(data.location);
 $('#cid').val(data.cid);
 $('#store_num').val(data.store_num);
 // $('#store_num').val(data.store_num);
  $('#address').val(data.address);
        $('#phone').val(data.phone);
        $('#city').val(data.city);
        $('#zip').val(data.zip);
        $('#notes').val(data.notes);
   getProjectEditstorenum(data.store_num);      
   $('#st_time').val(data.st_time).trigger('change'); 
    $('#region1').val(data.rid); 
    //$('#region_text').val(data.region); 
            }
        });   
}

function chng_glide_used_default()
{
 if($('#coldbox_sel').val()=='none')  
     {
 $('#oz_20').val(0);  
 $('#ltr_1').val(0);  
 $('#oz_10_12').val(0);  
 $('#oz_32').val(0);  
 $('#ltr_2').val(0);  
 $('#red_bull').val(0);  
 
     }
}

function csdCheckFunc()
{
    
 if($('#csd_chk').attr('checked')=='checked')
     {
   $('#csd_split_div').hide();      
     $('#csd_div').show(); 
     $('#csd_div').find('input').addClass('required ');  
     $('#csd_split_div').find('input').removeClass('required ');  
     }
     else  if($('#csd_chk2').attr('checked')=='checked')
     {
   $('#csd_div').hide();      
     $('#csd_split_div').show(); 
     $('#csd_split_div').find('input').addClass('required ');  
     $('#csd_div').find('input').removeClass('required ');
     }
}

function dmgSelHideFields(type)
{ //alert($('#'+type+'_sel').val());
    if($('#'+type+'_sel').val()=='none')
        {
$('#'+type+'_rd1').removeClass('required');  
$('#'+type+'_rd2').removeClass('required');   
$('#'+type+'_txt').removeClass('required'); 
        }
        else{
$('#'+type+'_rd1').addClass('required');  
$('#'+type+'_rd2').addClass('required');   
$('#'+type+'_txt').addClass('required'); 
        }           
            
}


function changeStoreValidation()
{
 if($('#store_name_2').val()==0)  
     {
  $('#store_num_2').removeClass('required'); 
   $('#other_fld').addClass('required');    
     }
     else
         {
 $('#store_num_2').addClass('required'); 
 $('#other_fld').removeClass('required');  
         }
}


function signoff_addNewCat()
{
var cnt=Number($('#cats_div tr').length)+1;
     var htm='<tr><td width="47" align="left" valign="top" >CAT :</td>'+
'<td width="151" align="left" valign="top"><input class="ui-wizard-content ui-helper-reset ui-state-default" type="text" name="cat['+cnt+'][cat]"/></td>'
+'<td width="53" align="left" valign="top">File ID# :</td>'
+'<td width="150" align="left" valign="top"><input class="ui-wizard-content ui-helper-reset ui-state-default" type="text" name="cat['+cnt+'][file_id]"/></td>'
+'<td width="69" align="left" valign="top">&nbsp;Footage:&nbsp;  </td>'
+'<td align="left" valign="top" width="150"><input class="ui-wizard-content ui-helper-reset ui-state-default"  maxlength="3" type="text"  name="cat['+cnt+'][footage]"/></td>'
+'<td width="6" align="left" valign="top">&nbsp;</td>'
+'<td width="123" height="30" align="left" valign="middle">Section Completed:</td>'
+'<td width="102" height="30" align="left" valign="top">'
+'Yes<input  name="cat['+cnt+'][sec_comp]" onchange="showNoOptMsg(\''+cnt+'\',\'Y\');" type="radio" value="Y"/>'
+'No<input  name="cat['+cnt+'][sec_comp]" onchange="showNoOptMsg(\''+cnt+'\',\'N\');" type="radio" value="N"/></td>'
+'<td width="261" align="left" valign="top"><div <?php if(!isset($stat['sec_comp'])||$stat['sec_comp']=='Y') echo 'style="display:none;"' ;?>  id="'+cnt+'"><?php echo $showNoOptMsg; ?></div></td></tr>'; 
$('#cats_div').append(htm);   
}

function addnewSignForm()
{
if($('#sign_off_add_sel').val()==''){ alert('Please select a signoff form');return;}
var htm='</tr><tr><td style="height:30px;"><input type="hidden" name="sign_off_arr[]"  value="'+$('#sign_off_add_sel').val()+'"/>'
    +$('#sign_off_add_sel option:selected').text()
    +'<img width="20" height="20" src="<?php echo $mydirectory;?>/images/delete.png" style="cursor:pointer;cursor:hand;" onclick="$(this).parent().parent().remove();" /></td></tr>';    
$('#sign_off_add_block').append(htm);    
}

function addnewsignmerch()
{
if($('#signmerch_add_sel').val()==''){ alert('Please select a signoff form');return;}
var htm='</tr><tr><td style="height:30px;"><input type="hidden" name="signmerch_off_arr[]"  value="'+$('#signmerch_add_sel').val()+'"/>'
    +$('#signmerch_add_sel option:selected').text()
    +'<img width="20" height="20" src="<?php echo $mydirectory;?>/images/delete.png" style="cursor:pointer;cursor:hand;" onclick="$(this).parent().parent().remove();" /></td></tr>';    
$('#signmerch_add_block').append(htm);    
}

function change_cat_other()
{
  if($('#category').val()=='Other (Write In)*')
{
$('#cat_other').show(); 
$('#cat_other').addClass('required');
}
else{
$('#cat_other').hide();
$('#cat_other').removeClass('required');
}
}

function deletesignmerch(id)
{
 var data='id='+id;   
  $.ajax({
 url:'deletesignmerch.php',
 data:data,
 type:'post',
 success:function(){}
  });  
}
</script>  
    
    <?php 
 $merch_frm_prj=1;   
require 'prj_commonjs_func.php';

include $mydirectory.'/trailer.php';
   ?>
