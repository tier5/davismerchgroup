<?php
require 'Application.php';

if($_SESSION['emp_type']!=0) 
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

include $mydirectory.'/header.php';
//extract($_POST);

?>
<div id="message" class="message_fixed"></div>
<div id="loading" class="message_fixed"><img src="<?php echo $mydirectory; ?>/images/loading.gif" alt="Loading..." /></div>
<input type="hidden" value="<?php echo trim($_GET['pid']);?>" id="pid" />
<a href="index.php" style="text-decoration:none;"><input type="button" value="back"/></a>
<br/>
<h3 align="center">Add/Edit Project Details</h3>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1" onclick="load_tab(1);">Project Details</a></li>
                 <li><a href="#tabs-2" onclick="load_tab(2);">Merchandiser</a></li>
		<li><a href="#tabs-3" onclick="load_tab(3);">Image Gallery</a></li>
		<li><a href="#tabs-4" onclick="load_tab(4);">Sign Offs</a></li>
                <li><a href="#tabs-5" onclick="load_tab(5);">Out of Stock</a></li>
               
	</ul>
	<div id="tabs-1"></div>
	<div id="tabs-2"></div>
	<div id="tabs-3"></div>
        <div id="tabs-4"></div>
        <div id="tabs-5"></div>
</div>

<div id="app_content" style="border:0px solid; padding: 5px"></div>


<link rel="stylesheet" type="text/css" href="<?php echo $mydirectory;?>/css/jquery-ui-1.8.19.custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $mydirectory;?>/css/jquery-ui_tabs.css" />

<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<!--<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script>  -->
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui_tabs.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-timepicker-addon.js'></script>
<script type="text/javascript" src="<?php echo $mydirectory; ?>/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo $mydirectory;?>/js/PopupBox.js"></script>




<script type="text/javascript">
var PopBoxImageLarge ='PopBoxImageLarge';
   var loading = $("#loading");
    var msg = $("#message");   
   
$(document).ready(function() {
  
$( "#tabs" ).tabs();
//$( "#tabs" ).tabs("load",0);
load_tab(1);
 
   //$( '#due_date' ).datepicker(); 
   //$('#st_time').timepicker({ ampm: true, minuteGrid: 15});
   


});



function load_tab(num)
{

    if($('#tab_hidden_'+num).length<=0)
        {     
        load_div(num);
        }
        
}

	function load_div(num)
	{
            
       showLoading();
       $('#tabs-'+num).html('');     
       var data='pid='+$('#pid').val();
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
if(num==2)
    {
//$('#st_time').timepicker({ ampm: true, minuteGrid: 15});

 $('#st_time').customTimePicker(); 

    }
if(num==4) loadForm();
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

  function showLoading(){loading .css({visibility:"visible"}) .css({opacity:"1"}) .css({display:"block"});msg .css({visibility:"hidden"})}
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
         $('#address').val('');
          $('#phone').val('');
           $('#city').val('');
            $('#zip').val('');
             
                 }	
		 });
	
}


function getstorenum(num){
	 if($('#store_name_'+num).val()==''){$('#other_tr').hide(); } 
 else if($('#store_name_'+num).val()==0)   
{
    $('#store_num_'+num).val('');
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
         $('#address_2').val('');
          $('#phone_2').val('');
           $('#city_2').val('');
            $('#zip_2').val('');
    
             
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
     if(type=='merch')
        data+=$("#project_form").serialize();
        data+="&prj_name="+$("#prj_name").val();
        //  data+="&curr_merch="+$("#current_merch").val();
        data+="&pid="+$("#pid").val();
        
//alert(data);
        $.ajax({type: 'POST',
            url: 'project_submit.php',
            data: data,
            dataType: 'json',
            success: function(data){
                if(data.error != '') {show_msg('error',data.error);}
                else{
               
                    
                    show_msg('success','Successfully Saved The Project Details');
                    if(data.pid > 0){
                        $('#pid').val(data.pid);$("#u_file").css({display:""});}}
                hideLoading();
             
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
    data="merch_id="+merch_id;
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
  $('#store_num').val(data.store_num);
  $('#address').val(data.address);
        $('#phone').val(data.phone);
        $('#city').val(data.city);
        $('#zip').val(data.zip);
        $('#notes').val(data.notes);
   $('#st_time').val(data.st_time).trigger('change'); 
    $('#region1').val(data.rid); 
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
                                   
     echo '<option value="' . $client[$j]['ID'] . '">' . $client[$j]['client'] . '</option>';
                                    
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
                                   
     echo '<option value="' . $client[$j]['ID'] . '">' . $client[$j]['client'] . '</option>';
                                    
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



function loadForm()
{
    if($('#sign_off_frm').length>0){
    
    var data='pid='+$('#pid').val();
     showLoading();
   $.ajax({
		 type: "POST",
		 url: $('#sign_off_frm').val(),
		 data: data,
                 datatype:'html',   
		 success: function(res) 
		 {
               hideLoading();         
         $('#sign_off_form_cnts').html(res);
          $('.date_field').datepicker();  
            if($('#store_name_2').val()!=0)
            {
        $('#address_2').attr('READONLY','READONLY');  
        $('#phone_2').attr('READONLY','READONLY');
        $('#city_2').attr('READONLY','READONLY');
        $('#zip_2').attr('READONLY','READONLY');
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


function signOffSubmit()
{
 var data=$('#sign_off_form').serialize();
 data+='&pid=<?php echo $_GET['pid'];?>';
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

function exportPDF(url)
{
    window.open(url+'_pdf.php?pid='+$('#pid').val());
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
   for(i=1;i<=12;i++) htm_cnt+='<option>'+i+'</option>';
   htm_cnt+='</select></td>';
      htm_cnt+='<td><select class="cust_time_min" onchange="changeTime($(this));">';
   for(i=5;i<60;i+=5) htm_cnt+='<option>'+i+'</option>';
   htm_cnt+='</select></td>';
      htm_cnt+='<td><select class="cust_time_am" onchange="changeTime($(this));">';
htm_cnt+='<option>AM</option>';
htm_cnt+='<option>PM</option>';
   htm_cnt+='</select>';  
   htm_cnt+='</td></tr><table><input onchange="chng_cust_time_val($(this))"  type="text" style="display:none;" class="cust_time_text" name="'+$(this).attr('name')+'" id="'+$(this).attr('id')+'"  value="'+$(this).attr('value')+'"/></div>';  
 $(this).replaceWith(htm_cnt);
chng_cust_time_val($(this));
 });
 

};
})(jQuery);



function chng_cust_time_val(obj)
{

if(obj.parents('.custom_time_picker').children('.cust_time_text').val()=='') return;
var time=obj.parents('.custom_time_picker').children('.cust_time_text').val();
var t=time.split(':');
obj.parents('.custom_time_picker').find('.cust_time_hr').val(t[0]);
obj.parents('.custom_time_picker').find('.cust_time_min').val(t[1]);
obj.parents('.custom_time_picker').find('.cust_time_am').val(t[2]);


}

  function changeTime(obj) {
var time=obj.parents('.custom_time_picker').find('.cust_time_hr').val()+':'+obj.parents('.custom_time_picker').find('.cust_time_min').val()+':'+
obj.parents('.custom_time_picker').find('.cust_time_am').val();
obj.parents('.custom_time_picker').children('.cust_time_text').val(time);

      return false;
    }
</script>  
    
    <?php 


include $mydirectory.'/trailer.php';
   ?>
