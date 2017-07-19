<?php
$page = 'project_grid';
require 'Application.php';
include $mydirectory.'/header.php';
$sql1 = 'Select ch_id, chain from "tbl_chain" ORDER BY chain ASC ';
if (!($result = pg_query($connection, $sql1))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row1= pg_fetch_array($result)){
	$data_chain[]=$row1;
}

$sql2 = 'select pid, prj_name from projects where status=1';
if(!($result = pg_query($connection, $sql2))) {
	print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row2= pg_fetch_array($result)){
	$data_project[]=$row2;
}

$sql4 = 'Select * from "tbl_chainmanagement" ORDER BY sto_num ASC ';
if (!($result = pg_query($connection, $sql4))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row4 = pg_fetch_array($result)){
	$data_store[]=$row4;
}
pg_free_result($result);



$query  = ("SELECT \"employeeID\", firstname, lastname FROM \"employeeDB\" where active='yes' and (emp_type = 0 OR emp_type is null)  ORDER BY firstname ASC;");
if (!($result = pg_query($connection, $query)))
{
    print("Failed employee query: " . pg_last_error($connection));
    exit;
}
while ($row = pg_fetch_array($result))
{
    $employee[] = $row;
}
pg_free_result($result);

$sql3 = 'Select rid, region from "tbl_region" ORDER BY region ASC ';
if (!($result = pg_query($connection, $sql3))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row3 = pg_fetch_array($result)){
	$data_region[]=$row3;
}
pg_free_result($result);

 $script = "<script type='text/javascript'>\n$( '#due_date' ).datepicker(); $('#time').timepicker({ ampm: true, minuteGrid: 15});</script>";
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
 <tr><td colspan="4"><font size="3">Search Projects</font></td></tr>
 <tr>
 <td>Project Name:</td>
 <td><select name="project" id="project">
 <option value="">--------Select--------</option>
 		 <?php
                    for ($i = 0; $i < count($data_project); $i++) {
                        echo '<option value="' . $data_project[$i]['pid'].'"' ;
						if( $data_project[$i]['pid']==$_GET['project'])
						echo ' selected="selected" ';
						echo '>' . $data_project[$i]['prj_name'] . '</option>';
                    }
                    ?>
     </select> 
           
        </td>
 
 
 <td>Store Name:</td>
 <td><select name="st_name" id="st_name" onchange="javascript:get_store();"> 
 <option value="">--------Select--------</option>                   
                    <?php
			for ($i = 0; $i < count($data_chain); $i++) {
    			echo '<option value="'.$data_chain[$i]['ch_id'].'" ';
				if( $data_chain[$i]['ch_id']==$_GET['st_name'])
    				
        			echo 'selected="selected" ';
    				echo '>' . $data_chain[$i]['chain'] . '</option>';
				}
		?>
                </select>
            </td>
             
     <td>Store Number:</td>
     <td><select name="sto_num" id="sto_num">
     <option value="">-------SELECT--------</option>
     <?php
			for ($i = 0; $i < count($data_store); $i++) {
    			echo '<option value="'.$data_store[$i]['chain_id'].'" ';
				if( $data_store[$i]['chain_id']==$_GET['sto_num'])
    				
        			echo 'selected="selected" ';
    				echo '>' . $data_store[$i]['sto_num'] . '</option>';
				}
		?>
     </select>
     </td>
            </tr>
            <tr>
            <td>Merchandiser:</td>
            <td><select name="merch" id="merch">
            <option value="">-----------SELECT-----------</option>
            <?php
                                for ($i = 0; $i < count($employee); $i++)
                                {
                                   
                   echo '<option value="' . $employee[$i]['employeeID'] . '"';
                   if($employee[$i]['employeeID']==$_GET['merch'])
                   echo ' selected="selected" ';
                       
                   
                  echo '>' . $employee[$i]['firstname'] . ' ' . $employee[$i]['lastname'] . '</option>';
                                   
                                }
								
								
                                ?>
            </select>
            </td>
            <td>Start Time:</td>
            <td><input type="text" size="15px" name="time" id="time" value="<?php echo $_GET['time']; ?>" /></td>
            <td align="right">Region:</td>
            <td><select name="region" id="region">
            <option value="">--------Select--------</option>                   
                    <?php
			for ($i = 0; $i < count($data_region); $i++) {
    			echo '<option value="'.$data_region[$i]['rid'].'" ';
				if( $data_region[$i]['rid']==$_GET['region'])
    				
        			echo 'selected="selected" ';
    				echo '>' . $data_region[$i]['region'] . '</option>';
				}
		?>
            </select></td>
            </tr>
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
	   
	      d="<?php if(isset($_GET['project'])&& $_GET['project']!="") echo $_GET['project']; else echo "";?>";
   if(d!="")
       {
   if( datastr=="")
       datastr+='?project='+d;
   else
       datastr+='&project='+d;
       }
	   
	       d="<?php if(isset($_GET['sto_num'])&& $_GET['sto_num']!="") echo $_GET['sto_num']; else echo "";?>";
   if(d!="")
       {
   if( datastr=="")
       datastr+='?sto_num='+d;
   else
       datastr+='&sto_num='+d;
       }
	   
	   d="<?php if(isset($_GET['st_name'])&& $_GET['st_name']!="") echo $_GET['st_name']; else echo "";?>";
   if(d!="")
       {
   if( datastr=="")
       datastr+='?st_name='+d;
   else
       datastr+='&st_name='+d;
       }
	   
	   d="<?php if(isset($_GET['merch'])&& $_GET['merch']!="") echo $_GET['merch']; else echo "";?>";
   if(d!="")
       {
   if( datastr=="")
       datastr+='?merch='+d;
   else
       datastr+='&merch='+d;
       }
	   
	   d="<?php if(isset($_GET['time'])&& $_GET['time']!="") echo $_GET['time']; else echo "";?>";
   if(d!="")
       {
   if( datastr=="")
       datastr+='?time='+d;
   else
       datastr+='&time='+d;
       }
	   
	   d="<?php if(isset($_GET['region'])&& $_GET['region']!="") echo $_GET['region']; else echo "";?>";
   if(d!="")
       {
   if( datastr=="")
       datastr+='?region='+d;
   else
       datastr+='&region='+d;
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
				display : 'Start Time',	
				name : 'm.st_time',
				width : 150,
				sortable : true,
			},{
				display : 'Store Name',
				name : 'ch.chain',
				width : 150,
				sortable : true,
			},{
				display : 'Store Number',
				name : 'prj.location',
				width : 150,
				sortable : true,
			},{
				
				display : 'Merchandiser',	
				name : 'm1.firstname',
				width : 150,
				sortable : true,
			},{
				display : 'Region',	
				name : 'reg.region',
				width : 150,
				sortable : true,
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
            }
        ,{
				display : 'Delete',
				name : 'delete',
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
        height : 'auto',
		    });
	function open_projects(){
		$('.open').parent().parent().hide();
		$('.close').parent().parent().show();
                //$('.delete_prjs').hide();
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
                            show_msg('success','Email Send Successfully.');
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
   location.href='project.php';
    }
    function showLoading(){loading .css({visibility:"visible"}) .css({opacity:"1"}) .css({display:"block"});msg .css({visibility:"hidden"})}
//hide loading bar
function hideLoading(){loading.fadeTo(1000, 0, function(){loading .css({display:"none"});msg .css({visibility:"visible"});});};
$('#subpage').dialog({
	autoOpen: false,
	width: 990,
        height: 760,
	modal: true,
	show: "blind",
	hide: "fade",
        close: function() {$(".prj_list").flexReload();}
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


function del_proj(id)
{
if(!confirm("Do you really want to permanently delete this project...")) return;

if(typeof(id) == 'undefined' || id == '') 
            id = 0;
    if(id > 0){
        showLoading();
        $.ajax({type: 'POST',url: 'delete_project.php',data: {pid:id},dataType: 'json',success: function(res){
        hideLoading();
        if(res.status=="1")
            {
        show_msg('success',"Poject deleted successfully...");
        $(".prj_list").flexReload();
            }
            else
        show_msg('error',"Sorry,some errors occured while processing.Please try again...");
                
  }
  ,error:function(){
       show_msg('error',"Sorry,some errors occured while processing.Please try again...");
  }
});}

}

</script>

<script type="text/javascript">



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
 $("#merch_id_hdn").val(0);
  $("#region1").val("");
   $('#region_text').val("");
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
	    if($("#project").val()!="")
     {
    if(data=="")     
   data+='?project='+$("#project").val();  
else
data+='&project='+$("#project").val();    
     }
     
	 
	  if($("#st_name").val()!="")
     {
    if(data=="")     
   data+='?st_name='+$("#st_name").val();  
else
data+='&st_name='+$("#st_name").val();    
     }
	 
	 if($("#sto_num").val()!="")
     {
    if(data=="")     
   data+='?sto_num='+$("#sto_num").val();  
else
data+='&sto_num='+$("#sto_num").val();    
     }
	 
	 if($("#merch").val()!="")
     {
    if(data=="")     
   data+='?merch='+$("#merch").val();  
else
data+='&merch='+$("#merch").val();    
     }
	 
	 if($("#time").val()!="")
     {
    if(data=="")     
   data+='?time='+$("#time").val();  
else
data+='&time='+$("#time").val();    
     }
     
	 if($("#region").val()!="")
     {
    if(data=="")     
   data+='?region='+$("#region").val();  
else
data+='&region='+$("#region").val();    
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






  
/*function ajaxFileUpload(fid)
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
}*/
          
          

</script>
<?php echo $script; ?>
   <?php 
   include $mydirectory.'/trailer.php';
   ?>
   