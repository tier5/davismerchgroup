<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
$pid=$_GET['pid'];
$item_mes=array();
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num   from item_mes_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$item_mes = pg_fetch_array($result);

pg_free_result($result);

$query = ("SELECT * from item_mes_form_item order by ss_it_id asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$ssr_item=array();
while ($row = pg_fetch_array($result)) {
    $ssr_item[] = $row;
}


}

$query = ("SELECT ch_id, chain from tbl_chain order by chain asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$store=array();
while ($row = pg_fetch_array($result)) {
    $store[] = $row;
}

pg_free_result($result);

$query  = ("SELECT \"employeeID\", firstname, lastname FROM \"employeeDB\" where active='yes' and (emp_type = 0 OR emp_type is null)  ORDER BY firstname ASC;");
//echo $query;
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

$proj_image=$item_mes['proj_image'];
$file_name='item_mes_update_form.pdf';


$html='<h3>Item Measurement Update Form</h3>';	

$html.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td>'.$item_mes['store_num'].'</td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td>'.$item_mes['dist'].'</td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td>'.$item_mes['date'].'</td>
    </tr>
  </table>';
  $html.='<table id="grid-new" width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
      <td height="30" class="grey-bg">Schematic Version</td>
      <td class="grey-bg">UPC</td>
      <td class="grey-bg">Product Name</td>
      <td class="grey-bg">Height</td>
      <td class="grey-bg">Width</td>
      <td class="grey-bg">Depth</td>
      <td class="grey-bg">Shelf</td>
      <td class="grey-bg">&nbsp;</td>
      </tr>';
 if(count($ssr_item)>0){  
  foreach($ssr_item as $i=>$ssr) { 
    $html.='<tr>
      <td height="30" class="white-bg">'.$ssr['schem_v'].'</td>
      <td class="white-bg">'.$ssr['upc'].'</td>
      <td class="white-bg">'.$ssr['prod_name'].
      '</td>
      <td class="white-bg">'.$ssr['height'].'</td>
      <td class="white-bg">'.$ssr['width'].'</td>
      <td class="white-bg">'.$ssr['depth'].'</td>
      <td class="white-bg">'.$ssr['shelf'].'</td>
    </tr> ';    
    }} 
    
  $html.='</table>';       
   require 'export_pdf.php';       ?>
          
         





  
  