<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
$pid=$_GET['pid'];
$s_summ=array();
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num   from reset_store_summary'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$s_summ = pg_fetch_array($result);

pg_free_result($result);
if(isset($s_summ['store_name'])&&$s_summ['store_name']!='')
{

$query = ("SELECT * from tbl_chainmanagement where sto_name=".$s_summ['store_name']);
if (!($result = pg_query($connection, $query))) {
    print("Failed query: " . pg_last_error($connection));
    exit;
}
$store_num=array();
while ($row = pg_fetch_array($result)) {
    $store_num[] = $row;
}
pg_free_result($result);

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


$proj_image=$ssr_form['proj_image'];
$file_name='Reset store summary.pdf';


$html='<h3>Reset Store Summery Report</h3>';	

 $html.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td>'.$s_summ['store_num'].'</td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td>'.$s_summ['district'].'</td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td>'.$s_summ['date'].'</td>
    </tr>
  </table>
          <br/>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="350" height="30">1.Total Missing Item Count (Total Items Listed on Form): 




&nbsp;</td>
      <td width="10">&nbsp;</td>
      <td>'.$s_summ['miss_count'].'</td>
      </tr>
    <tr>
      <td height="30">2.       Total Missing New Item Count (Total Items Listed as New): </td>
      <td>&nbsp;</td>
      <td>'.$s_summ['new_miss_count'].'</td>
    </tr>
    <tr>
      <td height="30">3.       Total UA Item Count (Total Items Listed as UA): &nbsp;</td>
      <td>&nbsp;</td>
      <td>'.$s_summ['ua_cnt'].'</td>
    </tr>
    <tr>
      <td height="30">4.       DC’D Items in Set # (Total DC’D Items Found in Set):&nbsp;</td>
      <td>&nbsp;</td>
      <td>'.$s_summ['dcd_item'].'</td>
    </tr>
    <tr>
      <td height="30">5.       New Items were staged (Did the Store pull the New Items and Put Them on a U Boat or Stage in a Separate Area): &nbsp;</td>
      <td>&nbsp;</td>
      <td>';
 $html.=($s_summ['stag_item']=='Y')? 'Yes':'No';
        $html.='</td>
      </tr>

  </table>';
         
require 'export_pdf.php';      
?>
  
  