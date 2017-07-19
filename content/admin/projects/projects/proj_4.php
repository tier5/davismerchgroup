<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
if(!isset($_REQUEST['pid'])||$_REQUEST['pid']=='')
{
echo '<br/><h3>Please save the project first...</h3>';
exit();
}
$pid=$_REQUEST['pid'];
//print_r($store);
$query='select cl.* from "prj_signoff_clients" as cl left join projects'.$ext.' as prj on prj.m_pid=cl.pid where prj.pid='.$_REQUEST['pid'];
//echo $query;
$result = pg_query($connection, $query);
$frm_lst=array();
while($frm_lst[]=pg_fetch_array($result));
//print_r($frm_lst);
//echo 'cnt--'.count($frm_lst);
if(count($frm_lst)<=0||$frm_lst[0]['frm_name']=='')
{
 echo '<br/><h3>No signoff form allocated for this project...</h3>';
exit();   
}
else if($frm_lst[0]['frm_name']=='all')
{
  $frm_lst[]=array('frm_name'=>'dmg_form');  
  $frm_lst[]=array('frm_name'=>'dmg_convenience_form');  
  $frm_lst[]=array('frm_name'=>'stater_bros_form');  
  $frm_lst[]=array('frm_name'=>'frito_lay_rest_form');  
  $frm_lst[]=array('frm_name'=>'pizza_form');  
  $frm_lst[]=array('frm_name'=>'ralphs_reset');  
  $frm_lst[]=array('frm_name'=>'ralphs_checklist');  
}
$frmname_lst=array('dmg_form'=>'DMG Chain Form','dmg_convenience_form'=>'DMG Convenience Form','stater_bros_form'=>'Grocery Form',
    'frito_lay_rest_form'=>'Frito-Lay Form','pizza_form'=>'Nestle Form','ralphs_reset'=>'Ralphs Reset Form','ralphs_checklist'=>'Ralphs Daily Checklist Form');

$dmg_array=array();
$dmgconv_array=array();
$frito_array=array();
$goc_array=array();
$pizz_array=array();
$dmg_form=array();
$ralph_array=array();
$ralphreset_array=array();
//print_r($frm_lst);
foreach($frm_lst as $frm){ if($frm=='') continue;
switch($frm['frm_name'])
{
case 'dmg_convenience_form':
$query  ='select t.*,ch.chain,cl.client,chmg.sto_num from dmg_convnc_form'.$ext.' as t left join tbl_chain as ch on ch.ch_id::text=t.store_name'
      .' left join "clientDB" as cl on cl."ID"=t.cid '  
      .' left join tbl_chainmanagement as chmg on chmg.chain_id::text=t.store_num where pid='.$pid.' order by dmg_id desc';  
   // echo $query ;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$dmgconv_array[]=$row;    
}
break;   

case 'frito_lay_rest_form':
 $query  ='select t.*,ch.chain,cl.client,chmg.sto_num from frito_lay_form'.$ext.' as t left join tbl_chain as ch on ch.ch_id::text=t.store'
        .' left join "clientDB" as cl on cl."ID"=t.cid '   
       .' left join tbl_chainmanagement as chmg on chmg.chain_id::text=t.number where pid='.$pid.' order by frito_id desc'; 
   // echo $query ;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$frito_array[]=$row;    
}
break; 

case 'stater_bros_form':
 $query  ='select t.*,ch.chain,cl.client,chmg.sto_num from stater_bros_form'.$ext.' as t left join tbl_chain as ch on ch.ch_id::text=t.store_name'
 .' left join "clientDB" as cl on cl."ID"=t.cid '       
 .' left join tbl_chainmanagement as chmg on chmg.chain_id::text=t.store_num where pid='.$pid.' order by stat_bros_id desc';
   // echo $query ;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$goc_array[]=$row;    
}
break; 

case 'pizza_form':
$query  ='select t.*,ch.chain,cl.client,chmg.sto_num from pizza_form'.$ext.' as t left join tbl_chain as ch on ch.ch_id::text=t.store_name '
        .' left join "clientDB" as cl on cl."ID"=t.cid ' 
        .' left join tbl_chainmanagement as chmg on chmg.chain_id::text=t.store_num where pid='.$pid.' order by pizza_id desc';  
   // echo $query ;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$pizz_array[]=$row;    
}
break; 

case 'dmg_form':
 $query  ='select t.*,ch.chain,cl.client,chmg.sto_num from dmg_form'.$ext.' as t left join tbl_chain as ch on ch.ch_id::text=t.store_name '
        .' left join "clientDB" as cl on cl."ID"=t.cid ' 
             .' left join tbl_chainmanagement as chmg on chmg.chain_id::text=t.store_num where pid='.$pid.' order by dmg_id desc'; 
   // echo $query ;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$dmg_array[]=$row;    
}
break; 

case 'ralphs_checklist':
$query  ='select d.*,ch.sto_num,chain.chain,cl.client,e.firstname,e.lastname   from ralph_checklist_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  '
    .' left join "employeeDB" as e on e."employeeID"=d.merch '  
        .' left join "clientDB" as cl on cl."ID"=d.cid ' 
   . ' left join tbl_chain as chain on chain.ch_id::text=d.store_name '          
            .' where pid='.$pid.' order by r_id desc';
   // echo $query ;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$ralph_array[]=$row;    
}
break; 

case 'ralphs_reset':
$query  ='select d.*,ch.sto_num,chain.chain,cl.client,e.firstname,e.lastname   from ralphs_reset_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num '
   .' left join "employeeDB" as e on e."employeeID"=d.merch '  
      .' left join "clientDB" as cl on cl."ID"=d.cid '      
   . ' left join tbl_chain as chain on chain.ch_id::text=d.store_name '        
            .' where pid='.$pid.' order by r_id desc';
   // echo $query ;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
while($row = pg_fetch_array($result))
{
$ralphreset_array[]=$row;    
}
break; 
}
}
//print_r($dmgconv_array);
?>
<br/><br/>

<table width="100%" >
  <?php if($is_client==1){}else{ ?>  
      <tr>
 
 <td >
     Add Sign Off Form&nbsp;
    <!-- <select onchange="javascript:loadForm();" id="sign_off_frm" >-->
      <select  id="sign_off_frm" >
         <?php foreach($frm_lst as $frm){ if($frm=='' || $frmname_lst[$frm['frm_name']]=='') continue;?>
         <option value="<?php echo $frm['frm_name'];?>.php"><?php echo $frmname_lst[$frm['frm_name']];?></option>    
       <?php }?>
         
     </select>&nbsp;<img width="20" height="20" src="<?php echo $mydirectory;?>/images/add.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:loadForm('0','new');"/>  
 </td>
    </tr> <?php }?>
       <tr><td>&nbsp;</td></tr>
        <tr>
 
            <td>
                <table>
  <tr><td><strong>Store Name</strong></td><td>&nbsp;</td><td><strong>Store#</strong></td><td>&nbsp;</td><td><strong>Client</strong></td>
      <td>&nbsp;</td><td><strong>Signoff Type</strong></td><td>&nbsp;</td><td><strong><?php if($is_client==1){ ?>View<?php }else{?>Edit<?php }?></strong></td>
       <?php if($is_client==1){}else{ ?>
      <td>&nbsp;</td><td><strong>Duplicate</strong></td><td>&nbsp;</td><td><strong>Delete</strong></td>
  <?php }?>
  </tr>                  
          <?php foreach($dmgconv_array as $dmg){?> 
     <tr><td><?php echo $dmg['chain'];?></td><td>&nbsp;</td><td><?php echo $dmg['sto_num'];?></td>
        <td>&nbsp;</td><td><?php echo $dmg['client'];?></td> 
         <td>&nbsp;</td><td>Dmg Convienience</td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/<?php if($is_client==1){echo 'view.png';}else{echo 'edit.png';} ?>" style="cursor:pointer;cursor:hand;" 
        onclick="<?php if($is_client==1){echo 'exportPDF(\'dmgconv\',\''.$dmg['dmg_id'].'\')';}else{?>javascript:loadForm('<?php echo $dmg['dmg_id']; ?>','dmg_convenience_form')<?php }?>;"/></td>
    <?php if($is_client==1){}else{ ?>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/save.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:copyForm('<?php echo $dmg['dmg_id']; ?>','dmgconv');"/></td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/delete.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:deleteForm('<?php echo $dmg['dmg_id']; ?>','dmgconv');"/></td>
      <?php }?>
     </tr>               
          <?php }?>   
       <?php foreach($frito_array as $dmg){?> 
     <tr><td><?php echo $dmg['chain'];?></td><td>&nbsp;</td><td><?php echo $dmg['sto_num'];?></td>
         <td>&nbsp;</td><td><?php echo $dmg['client'];?></td> 
         <td>&nbsp;</td><td>Frito lay</td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/<?php if($is_client==1){echo 'view.png';}else{echo 'edit.png';} ?>" style="cursor:pointer;cursor:hand;" 
        onclick="<?php if($is_client==1){echo 'exportPDF(\'fritolay\',\''.$dmg['frito_id'].'\')';}else{?>javascript:loadForm('<?php echo $dmg['frito_id']; ?>','frito_lay_rest_form')<?php }?>;"/></td>
     <?php if($is_client==1){}else{ ?>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/save.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:copyForm('<?php echo $dmg['frito_id']; ?>','fritolay');"/></td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/delete.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:deleteForm('<?php echo $dmg['frito_id']; ?>','fritolay');"/></td>
     <?php }?>
     </tr>               
          <?php }?> 
       <?php foreach($goc_array as $dmg){?> 
     <tr><td><?php echo $dmg['chain'];?></td><td>&nbsp;</td><td><?php echo $dmg['sto_num'];?></td>
         <td>&nbsp;</td><td><?php echo $dmg['client'];?></td> 
         <td>&nbsp;</td><td>Grocery</td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/<?php if($is_client==1){echo 'view.png';}else{echo 'edit.png';} ?>" style="cursor:pointer;cursor:hand;" 
        onclick="<?php if($is_client==1){echo 'exportPDF(\'staterbros\',\''.$dmg['stat_bros_id'].'\')';}else{?>javascript:loadForm('<?php echo $dmg['stat_bros_id']; ?>','stater_bros_form')<?php }?>;"/></td>
     <?php if($is_client==1){}else{ ?>
      <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/save.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:copyForm('<?php echo $dmg['stat_bros_id']; ?>','staterbros');"/></td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/delete.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:deleteForm('<?php echo $dmg['stat_bros_id']; ?>','staterbros');"/></td>
     <?php }?>
     </tr>               
          <?php }?> 
       <?php foreach($pizz_array as $dmg){?> 
     <tr><td><?php echo $dmg['chain'];?></td><td>&nbsp;</td><td><?php echo $dmg['sto_num'];?></td>
         <td>&nbsp;</td><td><?php echo $dmg['client'];?></td> 
         <td>&nbsp;</td><td>Nestle</td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/<?php if($is_client==1){echo 'view.png';}else{echo 'edit.png';} ?>" style="cursor:pointer;cursor:hand;" 
        onclick="<?php if($is_client==1){echo 'exportPDF(\'pizza\',\''.$dmg['pizza_id'].'\')';}else{?>javascript:loadForm('<?php echo $dmg['pizza_id']; ?>','pizza_form')<?php }?>;"/></td>
     <?php if($is_client==1){}else{ ?>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/save.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:copyForm('<?php echo $dmg['pizza_id']; ?>','pizza');"/></td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/delete.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:deleteForm('<?php echo $dmg['pizza_id']; ?>','pizza');"/></td>
     <?php }?>
     </tr>               
          <?php }?> 
     
          <?php foreach($dmg_array as $dmg){?> 
     <tr><td><?php echo $dmg['chain'];?></td><td>&nbsp;</td><td><?php echo $dmg['sto_num'];?></td>
         <td>&nbsp;</td><td><?php echo $dmg['client'];?></td> 
         <td>&nbsp;</td><td>DMG Chain</td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/<?php if($is_client==1){echo 'view.png';}else{echo 'edit.png';} ?>" style="cursor:pointer;cursor:hand;" 
        onclick="<?php if($is_client==1){echo 'exportPDF(\'dmg\',\''.$dmg['dmg_id'].'\')';}else{?>javascript:loadForm('<?php echo $dmg['dmg_id']; ?>','dmg_form')<?php }?>;"/></td>
     <?php if($is_client==1){}else{ ?>
      <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/save.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:copyForm('<?php echo $dmg['dmg_id']; ?>','dmg');"/></td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/delete.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:deleteForm('<?php echo $dmg['dmg_id']; ?>','dmg');"/></td>
     <?php }?>
     </tr>               
          <?php }?> 
     
          <?php foreach($ralph_array as $dmg){?> 
     <tr><td><?php echo $dmg['chain'];?></td><td>&nbsp;</td><td><?php echo $dmg['sto_num'];?></td>
         <td>&nbsp;</td><td><?php echo $dmg['client'];?></td> 
         <td>&nbsp;</td><td>Ralph Checklist</td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/<?php if($is_client==1){echo 'view.png';}else{echo 'edit.png';} ?>" style="cursor:pointer;cursor:hand;" 
        onclick="<?php if($is_client==1){echo 'exportPDF(\'ralphchkl\',\''.$dmg['r_id'].'\')';}else{?>javascript:loadForm('<?php echo $dmg['r_id']; ?>','ralphs_checklist')<?php }?>;"/></td>
     <?php if($is_client==1){}else{ ?>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/save.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:copyForm('<?php echo $dmg['r_id']; ?>','ralphchkl');"/></td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/delete.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:deleteForm('<?php echo $dmg['r_id']; ?>','ralphchkl');"/></td>
     <?php }?>
     </tr>               
          <?php }?> 
     
      <?php foreach($ralphreset_array as $dmg){?> 
     <tr><td><?php echo $dmg['chain'];?></td><td>&nbsp;</td><td><?php echo $dmg['sto_num'];?></td>
        <td>&nbsp;</td><td><?php echo $dmg['client'];?></td>  
         <td>&nbsp;</td><td>Ralph Reset</td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/<?php if($is_client==1){echo 'view.png';}else{echo 'edit.png';} ?>" style="cursor:pointer;cursor:hand;" 
        onclick="<?php if($is_client==1){echo 'exportPDF(\'ralphreset\',\''.$dmg['r_id'].'\')';}else{?>javascript:loadForm('<?php echo $dmg['r_id']; ?>','ralphs_reset')<?php }?>;"/></td>
     <?php if($is_client==1){}else{ ?>
    <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/save.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:copyForm('<?php echo $dmg['r_id']; ?>','ralphreset');"/></td>
     <td>&nbsp;</td><td><img width="20" height="20" src="<?php echo $mydirectory;?>/images/delete.png" style="cursor:pointer;cursor:hand;" 
        onclick="javascript:deleteForm('<?php echo $dmg['r_id']; ?>','ralphreset');"/></td> 
     <?php }?>
     </tr>               
          <?php }?> 
                </table>                
            </td>
        </tr>     
    <tr>
 
        <td>
          
            <div id="sign_off_form_cnts"></div>
         
            </td>
    </tr>
</table>
