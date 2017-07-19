<?php
require('Application.php');
require('../../header.php');
$data_signoff=array();
if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];
    $sql = "select * from project_main where  m_pid = $pid";
    if (!($result = pg_query($connection, $sql))) {
        $return_arr['error'] = pg_last_error($connection);
        echo json_encode($return_arr);
        return;
    }
$datalist = pg_fetch_array($result);
 

    pg_free_result($result);
	//print_r($datalist);

    
    $sql = "select * from prj_signoff_clients where  pid = $pid";
    if (!($result = pg_query($connection, $sql)))
    {
        $return_arr['error'] = pg_last_error($connection);
        echo json_encode($return_arr);
        return;
    }
 
while ($row = pg_fetch_array($result))
{
    $data_signoff[] = $row;
}
    pg_free_result($result);
}

$query  = ("SELECT \"ID\", \"clientID\", \"client\", \"active\" " .
        "FROM \"clientDB\" " .
        "WHERE \"active\" = 'yes'  " .
        "ORDER BY \"client\" ASC");
//echo $query;
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



$sql3 = 'Select ch_id, chain from "tbl_chain" ORDER BY chain ASC ';
if (!($result = pg_query($connection, $sql3))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
while($row3 = pg_fetch_array($result)){
	$data_chain[]=$row3;
}
pg_free_result($result);
?>

<div id="message" class="message_fixed"></div>
<div id="loading" class="message_fixed"><img src="<?php echo $mydirectory; ?>/images/loading.gif" alt="Loading..." /></div>
<table width="90%">
    <tr>
        <td align="left">
            <input type="button" value="Back" onclick="location.href ='project_list.php'" />
        </td>  
        <td>&nbsp;</td>
    </tr>
</table>
<?php
echo "<font face=\"arial\">";
echo "<blockquote>";
echo "<center><font size=\"5\">Project Add</font><br/><br/>";
echo "</blockquote>";
echo "</font>";

?>

<form action="project_submit.php" id="store_frm" method="post">
  <table align="center">
         <tr>
            <td align="right">Project Name:</td>
            <td><input id="prj_name" type="text" name="proj[name]" value="<?php echo stripslashes($datalist['name']); ?>" size="20">
            <input type="hidden" name="pid" value="<?php echo $datalist['m_pid'];?>"/>
            </td>
        </tr>  
      
      

        
            <tr>
            <td valign="top" align="right">Sign Off Forms:</td>
            <td><input type="hidden" name="hdn_sign_id[0]" value="<?php echo $data_signoff[0]['sign_id'];?>"/>
              <?php $frm_arr=array("all","dmg_form","dmg_convenience_form","stater_bros_form","frito_lay_rest_form","pizza_form","ralphs_reset","ralphs_checklist"); 
         $frmname_arr=array("All Forms","DMG Chain Form","DMG Convenience Form","Grocery Form","Frito-Lay Form","Nestle Form","Ralphs Reset Form","Ralphs Daily Checklist Form"); ?>  
                <select class="sign_off_sel" id="sign_off_sel" name="sign_off[0]"  onchange="select_all_toggle();">
                  <?php  foreach($frm_arr as $key=>$frm){?>
         <option <?php if($data_signoff[0]['frm_name']==$frm) echo ' selected="selected" ';
?>value="<?php echo $frm;?>"><?php echo $frmname_arr[$key];?></option>
<?php }?>
                </select>&nbsp;<img id="add_btn" width="20px" height="20px" src="<?php echo $mydirectory;?>/images/add.png"
                          title="Add signoff" onclick="javascript:sign_off_add();"  <?php if($data_signoff[0]['frm_name']=='all'){ echo ' style="display:none;"';}?>/>
             
            </td></tr>
            <tr>
           <td align="right">client:</td>
           
            <td><select name="client[0]">                 
                    <?php
									
			for ($i = 0; $i < count($client); $i++) {
    			echo '<option value="'.$client[$i]['ID'].'" ';
    				if (isset($data_signoff[0]['client']) && $data_signoff[0]['client'] == $client[$i]['ID'])
        			echo 'selected="selected" ';
    				echo '>' . $client[$i]['client'] . '</option>';
				}
		?>
                </select>
            </td>
            
        </tr>  
            
            <tr><td colspan="2"><table width="100%" id="sign_off_container">
         <?php

       $cnt=1;        
         if(count($data_signoff>0))
         {
       
     foreach($data_signoff as $key=>$sign)
     { if($key==0) continue;?>
<tr class="frm_<?php echo $cnt;?>"><td colspan="2">&nbsp;</td></tr><tr class="frm_<?php echo $cnt;?>">
    <td><input type="hidden" name="hdn_sign_id[<?php echo $cnt;?>]" value="<?php echo $sign['sign_id'];?>"/>Sign Off Forms:</td><td><select class="sign_off_sel" name="sign_off[<?php echo $cnt;?>]" ><?php  foreach($frm_arr as $key=>$frm){
echo '<option ';
if($sign['frm_name']==$frm) echo ' selected ';
echo ' value="'.$frm.'">'.$frmname_arr[$key].'</option>';
 }?></select>
&nbsp;<img class="del_buttons" width="20px" height="20px" src="<?php echo $mydirectory;?>/images/delete.png"
      title="Add signoff" onclick="javascript:sign_off_delete('<?php echo $sign['sign_id'];?>','<?php echo $cnt;?>');"/></td></tr>
<tr class="frm_<?php echo $cnt;?>"><td>Client&nbsp;</td><td><select name="client[<?php echo $cnt;?>]"><?php
			for ($i = 0; $i < count($client); $i++) {
    			echo '<option ';
if($sign['client']==$client[$i]['ID']) echo ' selected ';
echo 'value="'.$client[$i]['ID'].'" >' . str_replace("'","\'",$client[$i]['client']) . '</option>';
				}?></select></td></tr>         
    <?php  $cnt+=1; }
             }
         
         ?>

                </table>
            </td>
        </tr>

        <tr>
        <td colspan=5 align="center"><br>
          <input type="button" onmouseover="this.style.cursor = 'pointer';" value="Save" onclick="javascript:frmSubmit();" />
          <input type="button" onmouseover="this.style.cursor = 'pointer';" value="Job Page" onclick="location.href='<?php echo $mydirectory;?>/admin/projects/index.php';" />
          <br></td>
        </tr>
        </table>
 </form>
 <script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script> 
<script type='text/javascript'>
    var signoff_num=<?php echo $cnt;?>;
   $('document').ready(function(){
 $("#date_complet").datepicker();
});

 var loading = $("#loading");
 var msg = $("#message");
 
 function select_all_toggle()
 {
     if($('#sign_off_sel').val()=='all')
    {
  $('#add_btn').hide();      
  $('.del_buttons').each(function(){
  $(this).click();    
  });
    }
    else{
   $('#add_btn').show();  
 }
 }
 
 function sign_off_add()
 {
     
 
 $('#'+$('#sign_off_sel').val()).val(1);
 var htm='<tr class="frm_'+signoff_num+'"><td colspan="2">&nbsp;</td></tr><tr class="frm_'+signoff_num+'">'+
     '<td>Sign Off Forms:</td><td><select class="sign_off_sel" name="sign_off[]" ><?php  foreach($frm_arr as $key=>$frm){
    if($key==0) continue;     
echo '<option value="'.$frm.'">'.$frmname_arr[$key].'</option>';
 }?></select>'+
     '&nbsp;<img class="del_buttons" width="20px" height="20px" src="<?php echo $mydirectory;?>/images/delete.png"'+
     ' title="Add signoff" onclick="$(\'.frm_\'+'+signoff_num+').remove();"/></td></tr>';
 htm+='<tr class="frm_'+signoff_num+'"><td>Client&nbsp;</td><td><select name="client[]"><?php
			for ($i = 0; $i < count($client); $i++) {
    			echo '<option value="'.$client[$i]['ID'].'" >' . str_replace("'","\'",$client[$i]['client']) . '</option>';
				}?></select></td></tr>';
 
 $('#sign_off_container').append(htm);
 signoff_num+=1;

 }
 
 function sign_off_delete(sign_id,row_id)
 {
   var data='sign_id='+sign_id;
   $.ajax({
    url:'delete_signoff.php'
    ,type:'post'
    ,data:data
    ,success:function(){
      $('.frm_'+row_id).remove();  
    }
    ,error:function(){
        alert('Erroir while remove signoff');
    }
   });
 
   
 }
function frmSubmit()
{
    if($('#prj_name').val()==''){ alert('Enter project name.');return;}
	var cnt=0,val=0,flag=0;
$('.sign_off_sel').each(function(){
val=$(this).val();
cnt=0;
$('.sign_off_sel').each(function(){
   // alert(val+'--'+$(this).val());
 if(val==$(this).val()) cnt+=1;   
});
if(cnt>=2){
    flag=1;return;
}
});
if(flag==1){
        alert('Signoff selects repeatedly...');
        return;
}
dataString=$("#store_frm").serialize();
showLoading();
   $.ajax({
            type: "POST",
            url: "project_submit.php",
            data: dataString,
            dataType: "json",
            success:function(data)
            {
				hideLoading();
               if(data.error != ''){show_msg('error', data.error);}
				else if(data.msg != '') {show_msg('success',data.msg);location.href="<?php echo $mydirectory;?>/admin/project_add/add_project.php?pid="+data.pid;}
            }
          
        });
}
 function showLoading(){loading .css({visibility:"visible"}) .css({opacity:"1"}) .css({display:"block"});msg .css({visibility:"hidden"})}
//hide loading bar
function hideLoading(){loading.fadeTo(1000, 0, function(){loading .css({display:"none"});msg .css({visibility:"visible"});});};
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
</script>
<?php
require('../../trailer.php');
?>