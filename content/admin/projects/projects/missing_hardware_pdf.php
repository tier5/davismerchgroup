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
    $query  ='select d.*,ch.sto_num   from missing_hardware'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$res = pg_fetch_array($result);

pg_free_result($result);

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
$file_name='Missing hardware Survey.pdf';


$html='<h3>Missing Hardware Survey</h3>	';



 $html='.<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">Store#: &nbsp;</td>
      <td width="10">&nbsp;</td>
      <td>'.$res['store_num'].'</td>
      <td width="10">&nbsp;</td>
      <td>District#:</td>
      <td width="10">&nbsp;</td>
      <td>'.$res['district'].'</td>
      <td width="10">&nbsp;</td>
      <td>Date:</td>
      <td width="10">&nbsp;</td>
      <td>'.$res['date'].'</td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="30">1.       IQF Baskets: </td>
      <td width="10">&nbsp;</td>
      <td>'.$res['iqf_basket'].'</td>
      <td width="10">&nbsp;</td>
      <td>2.       POS Natural Signs:</td>
      <td width="10">&nbsp;</td>
      <td>'.$res['iqf_basket'].'</td>
    </tr>
    <tr>
      <td height="30">3.       SG Trays: </td>
      <td>&nbsp;</td>
      <td>'.$res['sg_tray'].'</td>
      <td>&nbsp;</td>
      <td>4.       Well Dividers:</td>
      <td>&nbsp;</td>
      <td>'.$res['well_div'].'</td>
    </tr>
    <tr>
      <td height="30">5.       White Freezer Shelves: </td>
      <td>&nbsp;</td>
      <td>'.$res['white_freez'].'</td>
      <td>&nbsp;</td>
      <td>6.       Black Freezer Shelves:</td>
      <td>&nbsp;</td>
      <td>'.$res['black_freez'].'</td>
    </tr>
    <tr>
      <td height="30">7.       White Meat Case Shelves: </td>
      <td>&nbsp;</td>
      <td>'.$res['white_meat'].'</td>
      <td>&nbsp;</td>
      <td>8.       Black Meat Case Shelves: </td>
      <td>&nbsp;</td>
      <td>'.$res['black_meat'].'</td>
    </tr>
    <tr>
      <td height="30">9.       DCI Trays: </td>
      <td>&nbsp;</td>
      <td>'.$res['dci_tray'].'</td>
      <td>&nbsp;</td>
      <td>10.   DCI Tray Size:</td>
      <td>&nbsp;</td>
      <td>'.$res['dci_tray_size'].'</td>
    </tr>
    <tr>
      <td height="30">11.   Fencing:</td>
      <td>&nbsp;</td>
      <td>'.$res['fencing'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>

  </table> ';         
            

require 'export_pdf.php';      
?>
  
  