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

$data_store=array();
if((isset($_GET['st_name'])&&$_GET['st_name']>0))
{
$sql4 = 'Select * from "tbl_chainmanagement" where sto_name='.$_GET['st_name'].' ORDER BY sto_num ASC ';
if (!($result = pg_query($connection, $sql4))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row4 = pg_fetch_array($result)){
	$data_store[]=$row4;
}
pg_free_result($result);
}



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

$sql = 'Select * from "flexgrid_storage" where emp_id='.$_SESSION['employeeID'];
if (!($result = pg_query($connection, $sql))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
// echo $sql;
$flexgrid_storage = pg_fetch_array($result);
pg_free_result($result);
//print_r($flexgrid_storage);


 $script = "<script type='text/javascript'>\n$( '#due_date' ).datepicker(); $('#time').timepicker({ ampm: true, minuteGrid: 15});</script>";
?>

<div id="message" class="message_fixed"></div>
<div id="loading" class="message_fixed"><img src="<?php echo $mydirectory; ?>/images/loading.gif" alt="Loading..." /></div>
<div id="subpage" style="height: 700px;overflow-y: auto;"></div>
<div id='sub_content'></div>

<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script>    
<script type="text/javascript" src="<?php echo $mydirectory;?>/js/flexigrid.pack.js"></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-timepicker-addon.js'></script>
<script type="text/javascript" src="<?php echo $mydirectory; ?>/js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo $mydirectory;?>/js/PopupBox.js"></script>
<script type="text/javascript" src="<?php echo $mydirectory;?>/js/timesheet.js"></script>
<table>
 <tr><td colspan="4"><font size="3">Search Projects</font></td></tr>
 <tr>
 <td>Project Name:</td>
 <td><select class="srch_field" name="project" id="project">	
         <option value="">--Select--</option>
     </select> 
       <!--  <input type="text"   name="project"  id="project" value="<?php echo $_GET['project'];?>"/>-->
        </td>
  <td>Job ID#:</td>
     <td><input class="srch_field" size="15px" type="text" name="jobid_num" id="jobid_num"  value="<?php 
     if(isset($_GET['jobid_num'])&&$_GET['jobid_num']!="")
         echo $_GET['jobid_num'];
     ?>" /></td>
 
 <td>Store Name:</td>
 <td><select class="srch_field" name="st_name" id="st_name" onchange="javascript:get_store();"> 
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
     <td><select class="srch_field" name="sto_num" id="sto_num">
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
      
            <tr   <?php if($is_client==1){  echo ' style="display:none;"';}?>    >
            <td>Merchandiser:</td>
            <td><select class="srch_field" name="merch" id="merch">
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
            <td><input class="srch_field" type="text" size="15px" name="time" id="time" value="<?php echo $_GET['time']; ?>" /></td>
            <td align="right" <?php if($_SESSION['perm_admin'] != "on" && $_SESSION['emp_type']==0) echo ' style="display:none;" ';?>>Region:</td>
            <td <?php if($_SESSION['perm_admin'] != "on" && $_SESSION['emp_type']==0) echo ' style="display:none;" ';?> ><select class="srch_field" name="region" id="region">
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
      <td align="right" <?php if($is_client==1){}else{ echo ' style="display:none;" ';}?>>Region:</td>
            <td <?php if($is_client==1){}else{  echo ' style="display:none;" ';}?> ><select class="srch_field" name="region" id="region2">
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
     
     
     <td>Date From:</td>
     <td><input class="srch_field" size="15px" type="text" name="date_from" id="date_from"  value="<?php 
     if(isset($_GET['date_from'])&&$_GET['date_from']!="")
         echo $_GET['date_from'];
     ?>" /></td>     
     <td>Date To:</td>
     <td><input class="srch_field" size="15px" type="text" name="date_to" id="date_to"  value="<?php 
     if(isset($_GET['date_to'])&&$_GET['date_from']!="")
         echo $_GET['date_to'];
     ?>" /></td>
      <td></td><td></td>
 </tr>
 <tr >
 <td colspan="2" align="right"><input type="button" value="Search" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all"
    onclick="javascript:searchPrjs();" /></td>   
 <td colspan="2" align="left"><input onclick="javascript:resetSearchPrjs();" type="button" value="Reset" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all"/>

 </td></tr>
   <tr ><td colspan="4">&nbsp;</td></tr>
  <tr ><td>
 <input type="button" value="Select all" class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all"
    onclick="javascript:selectAllPrjs();" />
</td>
</tr>
</table>

<table class="prj_list" style="display: none"></table>
<div id="f_page"></div>
<div id="clent_error_msg" style="display:none;" align="center" >
    <font size="4" color="red">   
 Welcome. Please Select from the pull down menus above for Detailed reporting. You can sort by project name,<br/>
Store name and/or number, or a date range. Call our offices for technical support.</font>
</div>
<div id='sub_content_confirm' style="display:none;">
<form id='sub_content_confirm_form'>    
  <table width="100%">  
  <tr><td>State your reason for the denying the job.</td></tr>    
   <tr><td><textarea id="deny_reason" name="deny_reason" cols="75" rows="10"></textarea></td></tr> 
    <tr><td><input type="button" value="Submit" onclick="javascript:submit_merch_deny();"/></td></tr> 
  </table></form>  
</div>
<script type="text/javascript">
var flx_close_job="<?php if(isset($_GET['close'])&&$_GET['close']==1) echo "yes";?>";
    var close_cnt=0;
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
       
       	   d="<?php if(isset($_GET['jobid_num'])&& $_GET['jobid_num']!="") echo $_GET['jobid_num']; else echo "";?>";
   if(d!="")
       {
   if( datastr=="")
       datastr+='?jobid_num='+d;
   else
       datastr+='&jobid_num='+d;
       }
    <?php if(isset($_GET['close'])&&$_GET['close']==1) {?> 
        
         if( datastr=="")
       datastr+='?close=1';
   else
    datastr+='&close=1';
     <?php }   ?>
    var loading = $("#loading");
    var msg = $("#message");
    var pid = $('#pid');
    $(".prj_list").flexigrid({
        url : 'get_projects.php'+datastr,
        dataType : 'json',
        colModel : [ 
             <?php   if($_SESSION['emp_type']==0) {?>
            {            
                display : 'Select',
                name : 'select',
                width : 35,
                sortable : false,
                align : 'left'
                 <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col0']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
            },<?php }?>
            {
                display : 'Project Name',
                name : 'main.name',
                width : 150,
                sortable : true,
                align : 'left'
                 <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col1']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
            },
            {
                display : 'Job ID#',
                name : 'prj.prj_name',
                width : 80,
                sortable : true,
                align : 'left'
                <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col2']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
            },
            {
                display : 'Start Date',
                name : 'm.due_date',
                width : 80,
                sortable : true,
                align : 'left'
                 <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col3']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
            },
            {
				display : 'Start Time',	
				name : 'm.st_time',
				width : 70,
				sortable : true
                                 <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col4']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
			},{
				display : 'Store Name',
				name : 'chain.chain',
				width : 120,
				sortable : true
                                 <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col5']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
			},{
				display : 'Store Number',
				name : 'st.sto_num',
				width : 70,
				sortable : true
                                 <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col6']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
			},{
				
				display : 'Merchandiser',	
				name : 'm1.firstname',
				width : 150,
				sortable : true
                                 <?php 
              
                            if(($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col7']=='hide')||($is_client==1) ){ ?>
                 ,hide:true   
               <?php }?>
                               
			},
                        
             {
				
				display : 'No. assign merch',	
				name : 'prj.num_merch',
				width : 150,
				sortable : true
                                 <?php 
              
                            if(($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col8']=='hide')||($is_client==1) ){ ?>
                 ,hide:true   
               <?php }?>
                               
			},           
                        {
				display : 'Region',	
				name : 'reg.region',
				width : 100,
				sortable : true
                                 <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col9']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
			},{
                display : '<?php echo (($_SESSION['perm_admin']) == "on"||($_SESSION['perm_manager'] == "on"))?'Edit':'View'; ?>',
                name : 'edit',
                width:40,
                sortable : false,
                align : 'center'
                 <?php if(($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col10']=='hide')){ ?>
                 ,hide:true   
               <?php }?>
			},
                        
        <?php if($_SESSION['emp_type']==0) { ?>                
                {
                display : 'Copy',
                name : 'copy',
                width:40,
                sortable : false,
                align : 'center'
                 <?php if((isset($_GET['close']) && $_GET['close'] == 1)||$_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col11']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
			},        
                   
                      /*  {
                display : 'Completed',
                name : 'complete',
                width:50,
                sortable : false,
                align : 'center'
			},*/
                <?php } ?>     
                    
                    
                        {
				display : 'Email',
				name : 'email',
				width :40,
				sortable : false,
				align : 'center'
                                 <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col12']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
            }
        , <?php if($_SESSION['emp_type']==0) { ?> 
        {
				display : 'Delete',
				name : 'delete',
                                hide:'true',
				width :50,
                                //bclass : 'open',
				sortable : false,
				align : 'center'
                                 <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col13']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
            },  <?php } ?>
        
        {
				display : 'Details',
				name : 'view',
                         
				width :50,
                                //bclass : 'open',
				sortable : false,
				align : 'center'
                                 <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col14']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
            },
         {
				display : 'Status',
				name : 'bulb_stat',
                         
				width :50,
                                //bclass : 'open',
				sortable : false,
				align : 'center'
                                 <?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['col15']=='hide'){ ?>
                 ,hide:true   
               <?php }?>
            }]
            ,
        buttons : [ /*{
				name : 'Add Job',
                bclass : 'add',
                onpress : show_project
            },*/ {
				name : 'Add Project',
                bclass : 'add',
                onpress : gotoadmin_project
            },
             {
				name : 'Add Job',
                bclass : 'add',
                onpress : show_project
            },
            
            {
                name : 'Open Jobs',
                bclass : 'open',
                onpress : open_projects
				
            },{
                name : 'Completed Jobs',
                bclass : 'close',
                
                //onpress : closed_projects
                onpress : closed_projects_srch
				
            },  {
                name : 'Close Jobs',
                bclass : 'close',
                onpress : close_project_bulk
				
            },{
                name : 'Delete Jobs',
                bclass : 'open',
                onpress : del_proj_bulk
				
            },
            {
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
        sortname : "<?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['sortname']!=''){ echo trim($flexgrid_storage['sortname']);}else if($is_client==1)echo 'prj.prj_name'; else echo'm.due_date'; ?>",
        sortorder : "<?php if($_SESSION['perm_admin'] == "on"&&$flexgrid_storage['sortorder']!=''){ echo trim($flexgrid_storage['sortorder']);}else if($is_client==1)echo 'asc'; else echo'asc'; ?>",
        usepager : true,
        <?php if(isset($_GET['page'])&&$_GET['page']>0)
        echo 'newp:'.$_GET['page'].',';
         ?>       
        title : '<center>Projects</center>',
        useRp : true,
        rp : 50,
        showTableToggleBtn : false,
        width : 'auto',
        height : 'auto'
       
      <?php if(isset($_GET['close'])&&$_GET['close']==1) {  ?>
            
        ,onSuccess:function(){
           hideOpenBtn(); 
            getProjectNames('open');  
               
        }
            <?php }else{ ?>
                  ,onSuccess:function(){ 
            getProjectNames('open');  
               
                    
        }
            <?php } ?>
      	
            });
              
              
         
              
              
              
              function hideOpenBtn(){
               if(close_cnt>0) return;
            $('.close').parent().parent().hide();
		$('.open').parent().parent().show();
                getProjectNames('close');
                close_cnt=1;
             }
	function open_projects(){
            var url=location.href;
             url = url.replace("&close=1","");
             url = url.replace("close=1","");
            location.href=url;
          //  getProjectNames('open'); 
		//$('.open').parent().parent().hide();
		//$('.close').parent().parent().show();           
		//$('.prj_list').flexOptions({ url: 'get_projects.php'}).flexReload();
                
	}
        function hideDelColumn()
        {
            $('th').each(function(){
              if($(this).attr('axis')=='col10') $(this).hide();
            });
        }
           function showDelColumn()
        {
            $('th').each(function(){
              if($(this).attr('axis')=='col10') $(this).show();
            });
        }
	function closed_projects(com, grid){
            getProjectNames('close'); 
		$('.close').parent().parent().hide();
		$('.open').parent().parent().show();	
               // showDelColumn();
		$('.prj_list').flexOptions({ url: 'get_projects.php?'+'close=1'}).flexReload();
	}
   function closed_projects_srch(com, grid)
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
     
     	 if($("#jobid_num").val()!="")
     {
    if(data=="")     
   data+='?jobid_num='+$("#jobid_num").val();  
else
data+='&jobid_num='+$("#jobid_num").val();    
     }
          
if(data=="")  
    data+='?close=1';  
else
data+='&close=1';         
 
     location.href="index.php"+data;
}     
	function close_project_bulk()
	{
            
               var id='e=1';
        var i=0;
        $('.complete').each(function(){
        if($(this).attr('checked')=='checked')
    {        
        id+='&id['+i+']='+$(this).val();   
        i+=1;
    }
        });
      if(i==0) { alert('Select projects to close.');return;}  
        
      if(!confirm('Are you sure want to close the projects'))
        { return false; }             
	showLoading();

        $.ajax({type: 'POST',url: 'close_project.php',data:id,dataType: 'json',success: function(data){if(data != '') show_msg('error',data);$('.prj_list').flexOptions({ url: 'get_projects.php'}).flexReload();
        hideLoading();
	}});
        }
    	function close_project(id)
	{
	showLoading();
        $.ajax({type: 'POST',url: 'close_project.php',data: {pid:id},dataType: 'json',success: function(data){if(data != '') show_msg('error',data);$('.prj_list').flexOptions({ url: 'get_projects.php'}).flexReload();
        hideLoading();
	}});
        }    
        
        	function delete_project()
	{
            
               var id='e=1';
        var i=0;
        $('.complete').each(function(){
        if($(this).attr('checked')=='checked')
    {        
        id+='&id['+i+']='+$(this).val();   
        i+=1;
    }
        });
      if(i==0) { alert('Select projects to delete.');return;}  
        
      if(!confirm('Are you sure want to close the projects'))
        { return false; }             
	showLoading();

        $.ajax({type: 'POST',url: 'close_project.php',data:id,dataType: 'json',success: function(data){if(data != '') show_msg('error',data);$('.prj_list').flexOptions({ url: 'get_projects.php'}).flexReload();
        hideLoading();
	}});
        }
    function delete_project(com, grid) {
        if (com == 'Delete Projects') {
            confirm('Delete ' + $('.trSelected', grid).length + ' items?');
			
        } 		
    }
    
    function gotoadmin_project()
    {
    location.href="<?php echo $mydirectory;?>/admin/project_add/project_list.php";    
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
{     data='dd=1<?php if(isset($_GET['close'])&&$_GET['close']==1) echo '&close=1';?>';
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
     
     if($.trim($("#project").val())!="")
     {
   data+='&project='+$("#project").val();        
     }   
          if($.trim($("#jobid_num").val())!="")
     {
   data+='&jobid_num='+$("#jobid_num").val();        
     }  
          if($.trim($("#st_name").val())!="")
     {
   data+='&st_name='+$("#st_name").val();        
     }  
          if($.trim($("#sto_num").val())!="")
     {
   data+='&sto_num='+$("#sto_num").val();        
     }  
             if($.trim($("#merch").val())!="")
     {
   data+='&merch='+$("#merch").val();        
     } 
             if($.trim($("#time").val())!="")
     {
   data+='&time='+$("#time").val();        
     } 
             if($.trim($("#region").val())!="")
     {
   data+='&region='+$("#region").val();        
     } 
     
    // alert(data);
 window.open("genSpreadSheet.php?"+data,"_blank")  

 /* $.ajax({type: 'POST',
  url: 'genSpreadSheet.php',
  data:data,
  dataType: 'json',
  
  success: function(data){
  hideLoading();    
  location.href='download_csv.php?file='+data.fileName;
}
  });*/
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
	width: 800,
  title:'Merchandiser List',
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
   
 //getProjectNames('open');   
 $('.open').parent().parent().hide();
 $("#date_from").datepicker();
 $("#date_to").datepicker();
 $('#sub_content_confirm').dialog({
width:'600',
height:'400',
autoOpen: false,
modal: true
});
});

function getClientStatus()
{
    

   var flag=0;
   $('.srch_field').each(function(){
   //alert($(this).attr('name')+'--'+$(this).val());
  if($(this).val()!=""&&$(this).val()!=null)
  {
      flag=1;
    }   
   });

   if(flag==0)
       {
$('#clent_error_msg').show();     
       }
}
function  getProjectNames(status,emp_type)
{
    var data='status='+status;
   $.ajax({
       url:'getProjectNames.php',
       type:'post',
       data:data,
       success:function(res){
   $('#project').html(res); 
   $('#project').val('<?php echo $_GET['project'];?>');
    <?php   if($_SESSION['emp_type']!=0) {?>  
     getClientStatus();
     <?php }?>
       }
   }); 
}


function del_proj(id)
{
if(!confirm("Do you really want to permanently delete this project...")) return;

if(typeof(id) == 'undefined' || id == '') 
            id = 0;
    if(id > 0){
        var data="id[0]="+id;
        showLoading();
        $.ajax({type: 'POST',url: 'delete_project.php',data: data,dataType: 'json',success: function(res){
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
	function del_proj_bulk()
	{
            
               var id='e=1';
        var i=0;
        $('.complete').each(function(){
        if($(this).attr('checked')=='checked')
    {        
        id+='&id['+i+']='+$(this).val();   
        i+=1;
    }
        });
      if(i==0) { alert('Select projects to delete.');return;}  
        
      if(!confirm('Do you really want to permanently delete this projects?'))
        { return false; }             
	showLoading();

        $.ajax({type: 'POST',url: 'delete_project.php',data:id,dataType: 'json',
     success: function(res){
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
    });
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
    
    data='<?php if(isset($_GET['close'])&&$_GET['close']==1) echo '?close=1';?>';   
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
  else if($("#region2").val()!="")
     {
    if(data=="")     
   data+='?region='+$("#region2").val();  
else
data+='&region='+$("#region2").val();    
     } 
     
     	 if($("#jobid_num").val()!="")
     {
    if(data=="")     
   data+='?jobid_num='+$("#jobid_num").val();  
else
data+='&jobid_num='+$("#jobid_num").val();    
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
          
          
function view_merchandiser(pid)
{
    showLoading();
    var data='pid='+pid;
       $.ajax({
		 type: "POST",
		 url: "merch_list.php",
		 data: data,

		 success: function(res){
     //   alert(res);
     hideLoading();
        $('#subpage').html(res);
         $('#subpage').dialog('open');
         
             
                 }	
		 });
}


function selectAllPrjs()
{
    $('.complete').each(function(){
       // alert('k');
  $(this).attr('checked','checked');      
    });
}
</script>
<?php 
require 'prj_commonjs_func.php';
echo $script; ?>
   <?php 
   include $mydirectory.'/trailer.php';
   ?>
   