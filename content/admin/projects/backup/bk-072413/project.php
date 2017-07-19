<?php
require 'Application.php';


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
<input type="hidden" value="<?php echo trim($_GET['pid']);?>" id="pid"/>
<a href="index.php" style="text-decoration:none;"><input type="button" value="back"/></a>
<div id="app_note" >
</div>
<div id="app_note2" >
</div>
<div id="app_note3" >
</div>

<div id="app_tabs" class="modernbricksmenu2">
<ul>
<li><a href="proj_details.php?pid=<?php echo $_GET['pid'];?>" rel="app_content" rev="app_note">Project Details</a></li>
<li><a href="image_gallery.php?pid=<?php echo $_GET['pid'];?>" rel="app_content" rev="app_note3">Image Gallery</a></li>
<li><a href="sign_offs.php?pid=<?php echo $_GET['pid'];?>" rel="app_content" rev="app_note3">Sign Offs</a></li>
<li><a href="CALVfees.php" rel="app_content" rev="app_note,app_note2">Out of Stock</a></li>
</ul>
    
</div>

<div id="app_content" style="border:0px solid; padding: 5px"></div>

<link rel="stylesheet" type="text/css" href="<?php echo $mydirectory;?>/css/ajaxtabs.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $mydirectory;?>/css/jquery-ui-1.8.19.custom.css" />


<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script>    
<script type="text/javascript" src="<?php echo $mydirectory; ?>/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo $mydirectory;?>/js/PopupBox.js"></script>

<script type="text/javascript" src="<?php echo $mydirectory;?>/js/ajaxtabs/ajaxtabs.js"></script>
<script type="text/javascript">

   var loading = $("#loading");
    var msg = $("#message");   
    var app=new ddajaxtabs("app_tabs", "app_content");
$(document).ready(function() {
  

app.setpersist(true);
app.setselectedClassTarget("link"); //"link" or "linkparent"
 

app.init();


   //$( '#due_date' ).datepicker(); 
   //$('#st_time').timepicker({ ampm: true, minuteGrid: 15});
   
 <?php if(isset($_GET['pid'])&&trim($_GET['pid'])!=''){ ?>

 show_files('<?php echo trim($_GET['pid']); ?>');
<?php } ?>

});



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
    		 $('#phone').val(data.phone);
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
         $('#address').val('');
          $('#phone').val('');
           $('#city').val('');
            $('#zip').val('');
             
                 }	
		 });
	
}

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
                else{
               
                    
                    show_msg('success','Successfully Saved The Project Details');
                    if(data.pid > 0){
                        $('#pid').val(data.pid);$("#u_file").css({display:""});}}
                hideLoading();
               
              <?php if($_GET['pid']!=''){?>
              app.expandit(0); 
              <?php } else {?>
           location.href='project.php?pid='+data.pid;
           <?php }?>

      
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
              
               app.expandit(0); 
                
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
 $('#cid').val(data.client);
 $('#store_num').val(data.store_num);
  $('#store_num').val(data.store_num);
  $('#address').val(data.address);
        $('#phone').val(data.phone);
        $('#city').val(data.city);
        $('#zip').val(data.zip);
        $('#notes').val(data.notes);
   $('#st_time').val(data.st_time); 
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




function formFileUpload(file_cat, type,row_count, width, height){


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
                    $("#proj_image").val(data.fil_name);
                    $("#proj_img_field").attr("src","<?php echo $image_dir;?>"+data.fil_name);
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
         app.expandit(1); 
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


</script>  
    
    <?php 


include $mydirectory.'/trailer.php';
   ?>
