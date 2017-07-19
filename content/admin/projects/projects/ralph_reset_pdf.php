<?php
require 'Application.php';
$ext='';
if(isset($_POST['close']) && $_POST['close'] == 1)
{
$ext='_closed';    
}
extract($_POST);
$ralph=array();
if(isset($pid)&&$pid!='')
{
    $query  ='select d.*,ch.sto_num   from ralphs_reset_form'.$ext.' as d left join tbl_chainmanagement as ch on ch.chain_id::text=d.store_num  where pid='.$pid;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
$ralph = pg_fetch_array($result);

pg_free_result($result);
if(isset($ralph['store_name'])&&$ralph['store_name']!='')
{

$query = ("SELECT * from tbl_chainmanagement where sto_name=".$ralph['store_name']);
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

$proj_image=$ralph['proj_image'];
$file_name='ralph_reset_form.pdf';


$html='<h3>Ralphs Reset Sign Off</h3>
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
    <td  align="center"></td>  
    <td><img alt="logo" src="'.$mydirectory.'/images/davis-wbg.png" /></td>
    </tr>';
   $html.='<tr>
    <td colspan="5">&nbsp;</td>    
    </tr>
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100" height="30" align="left" valign="top">Store&nbsp;Name:</td>
        <td width="10" align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">
          
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                   <tr>
                    
<td><font size="12">';
if($ralph['store_name']==0)
$html.=$ralph['other'];
else    
$html.=$ralph['chain'];
          $html.='</font>
</td>
                     <td width="20">&nbsp;</td>
                     <td width="100">Store#: </td>
                     <td><font size="12">';
          $html.=$ralph['sto_num'] .'</font></td>
              <td width="82" valign="top" align="left" height="30"><font size="12">Work&nbsp;Type:</font></td>
        <td valign="top" align="left" width="100"><font size="12">';
          $html.=$ralph['work_type'] .'</font><select class="required" name="store_num" id="store_num_2"   onchange="javascript:get_contact(2);">
                       </td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                   </tr>
                 </table>
            </td>
      </tr>';
     $html.='
        <tr>
        <td height="30" align="left" valign="top">Address:</td>
        <td height="30" align="left" valign="top">&nbsp;</td>
        <td height="30" align="left" valign="top">'.$ralph['address'].'</td>
      </tr>';       
          
$html.=' </table>';
 require 'export_pdf.php'; ?>