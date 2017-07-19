<?php 
require('Application.php');
require('../../header.php');
$page ="listApplication.php";
$limit = 0; //Link per page   
$intlimit = 10; //How much record to fetch   
if (isset($_GET["Start"]) & $_GET["Start"]!=1) {
$intFrom = $intlimit*($_GET["Start"]-1);   
 }else {                                        
$intFrom = 0; //From Record  
}       

$aryCnt=$aryRest= $objDB->getResultMulti($connection,"SELECT 
  count(*)
FROM 
  public.dtbl_jobapplicant, 
  public.dtbl_jobapplicantaddress
WHERE 
  dtbl_jobapplicant.\"appID\" = dtbl_jobapplicantaddress.\"appID\"  ");
$aryRest= $objDB->getResultMulti($connection,"SELECT 
  dtbl_jobapplicant.\"appID\", 
  dtbl_jobapplicant.\"nameFirst\",
   dtbl_jobapplicant.\"nameMiddle\",
   dtbl_jobapplicant.\"nameLast\",
  dtbl_jobapplicant.\"nameSuffix\", 
  dtbl_jobapplicantaddress.emailid, 
  dtbl_jobapplicantaddress.\"curr_City\",dtbl_jobapplicant.\"joiningDate\"
FROM 
  public.dtbl_jobapplicant, 
  public.dtbl_jobapplicantaddress 
  WHERE 
  dtbl_jobapplicant.\"appID\" = dtbl_jobapplicantaddress.\"appID\"   ORDER BY
  dtbl_jobapplicant.\"joiningDate\" DESC,
  dtbl_jobapplicant.\"nameSuffix\" ASC, 
  dtbl_jobapplicant.\"nameFirst\" ASC, 
  dtbl_jobapplicant.\"nameMiddle\" ASC, 
  dtbl_jobapplicant.\"nameLast\" ASC  LIMIT ".$intlimit ." OFFSET ".$intFrom.";");
$ary2Value=array();
for($i=0;$i<count($aryRest);$i++)

{
	$ary2Value[$i][0]=$aryRest[$i][0];
	$ary2Value[$i][1]=$aryRest[$i]['nameFirst'].' '.$aryRest[$i]['nameMiddle'].' '.$aryRest[$i]['nameLast'].' '.$aryRest[$i]['nameSuffix'];
        $d=split('-',$aryRest[$i]['joiningDate']);
	$ary2Value[$i][4]=$d[1].'/'.$d[2].'/'.$d[0];
        $ary2Value[$i][2]=$aryRest[$i][6];
	$ary2Value[$i][3]=$aryRest[$i][5];
}

?>
<center>
              <h3><b>Applications List Page </b></h3>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="35" align="left" valign="top">
                      <?php  
                      
                      $aryField=array("Name of Applicant","Location","Application Date","Email","Edit","Delete");
                      echo $objFrm->getEDTable($aryField,$ary2Value);
                      $str=$_REQUEST;
                      echo"<div align=center>".$getPage->Pagination('listApplication.php',$aryCnt[0][0],$str,10)."</div>";?>
                      </td>
                    </tr>
                  </table></td>
                </tr>
              </table>
          </center>
<?php 
require('../../trailer.php');
?>