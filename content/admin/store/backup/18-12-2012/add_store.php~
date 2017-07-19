<?php
require('Application.php');
require('../../header.php');
if (isset($_GET['sid'])) {
    $sid = $_GET['sid'];
    $sql = "select * from tbl_store where  sid = $sid";
    if (!($result = pg_query($connection, $sql))) {
        $return_arr['error'] = pg_last_error($connection);
        echo json_encode($return_arr);
        return;
    }
    while ($row = pg_fetch_array($result)) {
        $datalist = $row;
    }
    pg_free_result($result);
	//print_r($datalist);
}

if ($isEdit) {
    $query = ("SELECT * from tbl_store " .
            "WHERE sid = $sid ");
    if (!($result = pg_query($connection, $query))) {
        print("Failed query1: " . pg_last_error($connection));
        exit;
    }
    while ($row2 = pg_fetch_array($result)) {
        $datalist2 = $row2;
    }
    pg_free_result($result);
	 
}



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
            <input type="button" value="Back" onclick="location.href ='store_list.php'" />
        </td>  
        <td>&nbsp;</td>
    </tr>
</table>
<?php
echo "<font face=\"arial\">";
echo "<blockquote>";
echo "<center><font size=\"5\">Chain Management</font><br/><br/>";
echo "</blockquote>";
echo "</font>";

?>

<form action="store_submit.php" id="store_frm" method="post">
  <table align="center">
         
        <tr>
           <td align="right">Chain:</td>
           
            <td><select name="st_name">                 
                    <?php
			for ($i = 0; $i < count($data_chain); $i++) {
    			echo '<option value="'.$data_chain[$i]['ch_id'].'" ';
    				if (isset($datalist['sto_name']) && $datalist['sto_name'] == $data_chain[$i]['chain'])
        			echo 'selected="selected" ';
    				echo '>' . $data_chain[$i]['chain'] . '</option>';
				}
		?>
                </select>
            </td>
            
        </tr>
        <tr>
            <td align="right">Store#:</td>
            <td><input type="text" name="store" value="<?php echo stripslashes($datalist['sto_num']); ?>" size="20"> <input type="hidden" name="sid" value= "<?php 
			if(isset($_GET['sid']) && $_GET['sid']!='') echo $_GET['sid']; ?>"  /></td>
        </tr>
        <tr>
            <td align="right">Address:</td>
            <td><input type="text" name="address" value="<?php echo stripslashes($datalist['address']); ?>" size="20"></td>
        </tr>  
        <tr>
            <td align="right">City:</td>
            <td><input type="text" name="city" value="<?php echo stripslashes($datalist['city']); ?>" size="20"></td>
        </tr>
        <tr>
            <td align="right">State:</td>
            <td><input type="text" name="state" value="<?php echo stripslashes($datalist['state']); ?>" size="20"></td>
        </tr>
<tr>
            <td align="right">Zip:</td>
            <td><input type="text" name="zip" value="<?php echo stripslashes($datalist['zip']); ?>" size="20"></td>
        </tr>
        <tr>
            <td align="right">Phone:</td>
            <td><input type="text" name="phone" value="<?php echo stripslashes($datalist['phone']); ?>" size="20"></td>
        </tr>
        <tr>
        <td colspan=5 align="center"><br>
          <input type="button" value="Save" onclick="javascript:frmSubmit();" />
          <br></td>
        </tr>
        </table>
 </form>
    <script type="text/javascript" src="<?php echo $mydirectory; ?>/js/jquery.min.js"></script>
<script type="text/javascript">
 var loading = $("#loading");
 var msg = $("#message");
function frmSubmit()
{
	
dataString=$("#store_frm").serialize();
showLoading();
   $.ajax({
            type: "POST",
            url: "store_submit.php",
            data: dataString,
            dataType: "json",
            success:function(data)
            {
				hideLoading();
               if(data.error != ''){show_msg('error', data.error);}
				else if(data.msg != '') show_msg('success',data.msg);
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