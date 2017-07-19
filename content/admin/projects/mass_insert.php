<?php
require 'Application.php';
?>
<script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
<script type='text/javascript'>
/* var arr_id=Array('582','575','584','586','588','569','661','674','675','578','579','572','573','574','577','581'
,'696','591','590','593','592','691','497','480','492','494','495','496','498','499','500','501','502','503','504','505','506'
);   
       var id='e=1';
        var i=0;
      for(i=0;i<arr_id.length;i++)
      
    {        
        id+='&id['+i+']='+arr_id[i];   
    }*/
    <?php 
  $sql1 = 'Select pid from "projects" where status=0 limit 100 ';
if (!($result = pg_query($connection, $sql1))) {
    print("Failed query1: " . pg_last_error($connection));
    exit;
 }
 $id1='e=1';
 $i=0;
while($row1= pg_fetch_array($result)){
	$id1=$id1.'&id['.$i.']='.$row1['pid'];
        $i+=1;
} ?> 
var id="<?php echo $id1;?>";
   
  alert(id);  
 $.ajax({
     type: 'POST',
     url: 'close_project.php',
     data:id,dataType: 'json',
     success: function(data){
         if(data != '') 
             show_msg('error',data);
         //$('.prj_list').flexOptions({ url: 'get_projects.php'}).flexReload();
       // hideLoading();
	}});
    </script>