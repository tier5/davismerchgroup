<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
$pid=$_GET['pid'];
$res=array();
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num   from ssr_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$ssr_form = pg_fetch_array($result);

pg_free_result($result);

}

$query = ("SELECT * from ssr_form_item order by ss_it_id asc ");
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$ss_frm=array();
while ($row = pg_fetch_array($result)) {
    $ss_frm[] = $row;
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

$proj_image=$ssr_form['proj_image'];
$file_name='SSR Form.pdf';



$html='<h3>SSR Form</h3>	

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td>'.$ssr_form['store_num'].'</td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td>'.$ssr_form['dist'].'</td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td>'.$ssr_form['date'].'</td>
    </tr>
  </table>
  <table id="grid-new" width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
      <td height="30" class="grey-bg">STORE</td>
      <td class="grey-bg">Commodity Code</td>
      <td class="grey-bg">Kroger Category Update</td>
      <td class="grey-bg">&nbsp;</td>
      </tr>';
  if(count($ss_frm)>0){  
  foreach($ss_frm as $i=>$ssr) {   
      $html.='<tr>
      <td height="30" class="white-bg">'. $ssr['store'].'</td><td class="white-bg">'.$ssr['comm_code'].'</td>
      <td class="white-bg">'
       . $ssr['krog_cat']
      .'</td></tr>';    
   }
  }
 $html.='</table>';         
    
  
require 'export_pdf.php';      
?>
  
  