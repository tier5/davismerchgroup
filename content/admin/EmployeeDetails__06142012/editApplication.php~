<?php 
require('Application.php');
require('../../header.php');
$page = "editApplication.php";
$app = 0;
if(isset($_GET['iid']))
{
	$app=$_GET['iid'];
}
extract($_POST);
?>
<?php 
$aryCol1=array(job_req, salary, "refferedid", "joiningDate", 
       "UID", "nameLast", "nameSuffix", "nameFirst", "nameMiddle", "nickName");

$v2=array('txtCurAdd','txtCurCity','txtCurState','txtCurZip','txtCurYrsLived',
'txtTelNo','txtEmail','rdAgeLmt','rdLegAut','rdWorDMG','txtPosition',
'rdFrndDMG','txtNameFrnd','rdTransWork','rdCrime','txtCrime');
$aryCol2=array(curr_address, "curr_City", "curr_State", "cur_zipCode", 
       "cur_YrLived", tel_no, emailid, age_lmt, leg_aut, "wokd_for_DMG", 
       wrk_det, "frnd_in_DMG", frnd_name, trnspot_req, crime_chrgd, 
       crime_det); 
 $v3=array('txtSch1Name','txtSch1City','txtSch1State','txtSch1Zip','txtSch1Country','rdSch1Grd','txtSch1Frm','txtSch1To','txtSch1GrdDt',
'txtSch1Title','txtSch1Main','txtSch1Extras','rdIsNameChng','txtNameDt','txtExp','rdIsCap');
$aryCol3=array(school_name1, "s1_City", "s1_State", "s1_zipCode", s1_country, 
       s1_is_graduate, s1_grd_prd_frm,is_nam_chgd, name_det, 
       experience_det, is_capable);
$v4=array('txtCurEmpName','txtCurEmpAdd','txtCurEmpCity','txtCurEmpState','txtCurEmpZip','txtCurEmpFrom','txtCurEmpTo','cmdCurEmpSSal',
'cmdCurEmpFSal','txtCurEmpTel','txtCurEmpPos','txtCurEmpDut','txtCurEmpSup','txtCurEmpLeave','rdCurEmpWorking','rdCurEmpMayCont');

$aryCol4=array(empr_name, empr_address, empr_city, empr_state, "empr_zipCode", 
       empr_from, empr_to, empr_start_salary, empr_end_salary, empr_tel_no, 
       empr_position, empr_duties_major, empr_name_supvr, empr_reason_leaving, 
       empr_is_currly_working, empr_may_contact);       
 $v5=array('rdCmtToOtr','txtOtrExpe','rdAskToRn','txtReasonRn','txtRef1Name','txtRef1Occ','txtRef1Add','txtRef1City','txtRef1State','txtRef1Zip','txtRef1TelNo','txtRef1YrKn');


$aryCol5=array(any_comm, exp_comm, ask_to_resign, exp_reason, ref1_name, 
       ref1_occ, ref1_address, "ref1_City", "ref1_State", "ref1_zipCode", 
       "ref1_Tel_No", "ref1_no_Yrs");
 

$v6=array('rdLic','rdLicSus','txtSusExp','rdLicVoil','txtOff1Off','txtOff1Dt','txtOff1Loc','txtOff1Com','rdAutIns','txtInsNo');
$aryCol6=array(have_dri_licence, ever_susp, exp_susp, have_traf_violations, 
       off1_det, off1_date, off1_location, off1_commts,have_per_aut_ins, ex_no_insurance);
       $success=0;
if(isset($_POST['SubFrm']) && $app >0)
{	
	$aryValue1=array("'".$_POST['txtJob']."'",$_POST['txtSalary'],$_POST['cmdReff'],
	 		"'".$_POST['cmdStDtday']."/". $_POST['cmdStDtmonth']."/". $_POST['cmdStDtyear']."'",
			"'".$_POST['txtSSN1'].$_POST['txtSSN2'].$_POST['txtSSN3']."'",
			"'".$_POST['txtLegName']."'","'".$_POST['txtSrName']."'","'".$_POST['txtFrstName']."'",
			"'".$_POST['txtMdlName']."'","'".$_POST['txtNickName']."'");
	
	$wh=array("\"appID\"=$app");
	$success1=$objDB-> updateData($connection,"dtbl_jobapplicant",$aryCol1,$aryValue1,$wh);
	
	$aryValue2=$objFrm->ChangeToPost($v2);
	$success2=$objDB-> updateData($connection,"dtbl_jobapplicantaddress",$aryCol2,$aryValue2,$wh);	
			
	$aryValue3=$objFrm->ChangeToPost($v3);
	$success3=$objDB-> updateData($connection,"dtbl_jobapplicanteducation",$aryCol3,$aryValue3,$wh);
	
	$aryValue4=$objFrm->ChangeToPost($v4);
	$success4=$objDB-> updateData($connection,"dtbl_jobapplicantemployer",$aryCol4,$aryValue4,$wh);
	
	$aryValue5=$objFrm->ChangeToPost($v5);
	$success5=$objDB-> updateData($connection,"dtbl_jobapplicantemp_hist",$aryCol5,$aryValue5,$wh);
	
	$aryValue6=$objFrm->ChangeToPost($v6);
	$success6=$objDB->updateData($connection,"dtbl_jobapplicantdri_det",$aryCol6,$aryValue6,$wh);
	
	if(isset($previous_address))
	{
		for($i=0; $i<count($previous_address); $i++)
		{
			$sql = "Update dtbl_applicant_previous_address set status = 1";
			if($previous_address[$i]!="")$sql .=",address = '".$previous_address[$i]."'";
			if($city[$i]!="")$sql .=",city = '".$city[$i]."'";
			if($state[$i]!="")$sql .=",state = '".$state[$i]."'";
			if($zip[$i]!="")$sql .=",zip = '".$zip[$i]."'";
			if($lived_here[$i]!="")$sql .=",years_lived = '".$lived_here[$i]."'";
			if($app_id>0)$sql .=",app_id = ".$app_id;
			$sql .=" where id = ".$previous_address_id[$i];
			//echo $sql;
			if(!($result= pg_query($connection,$sql)))
			{
				$return_arr['error'] = "Error while inserting address information to database!";	
				echo json_encode($return_arr);
				return;
			}
			pg_free_result($result);
		}
	}
	if(isset($school_name))
	{
		$sql = "";
		for($i=0; $i<count($school_name); $i++)
		{
			$sql = "Update dtbl_jobapplicant_education set status = 1";
			if($school_name[$i]!="")$sql .=",school_name = '".$school_name[$i]."'";
			if($school_city[$i]!="")$sql .=",school_city = '".$school_city[$i]."'";
			if($school_state[$i]!="")$sql .=",school_state = '".$school_state[$i]."'";
			if($school_zip[$i]!="")$sql .=",school_zip = '".$school_zip[$i]."'";
			if($school_country[$i]!="")$sql .=",school_country = '".$school_country[$i]."'";
			if($did_graduate[$i]!="")$sql .=",did_graduate = '".$did_graduate[$i]."'";
			if($from_date[$i]!="")$sql .=",from_date = '".$from_date[$i]."'";
			if($to_date[$i]!="")$sql .=",to_date = '".$to_date[$i]."'";
			if($graduation_date[$i]!="")$sql .=",graduation_date = '".$graduation_date[$i]."'";
			if($diploma[$i]!="")$sql .=",diploma = '".$diploma[$i]."'";
			if($course[$i]!="")$sql .=",major_course = '".$course[$i]."'";
			if($speacilization[$i]!="")$sql .=",describe_specialization = '".$speacilization[$i]."'";
			if($app_id>0)$sql .=",app_id = ".$app_id;
			$sql .="where education_id = ".$education_id[$i];
			if(!($result= pg_query($connection,$sql)))
			{
				$return_arr['error'] = "Error while inserting education information to database!";	
				echo json_encode($return_arr);
				return;
			}
		}
	}
	if(isset($employer_name))
	{
		$sql = "";
		for($i=0; $i<count($employer_name); $i++)
		{
			$sql = "Update dtbl_previous_employer set status = 1";
			if($employer_name[$i]!="")$sql .=",employer_name = '".$employer_name[$i]."'";
			if($employer_address[$i]!="")$sql .=",employer_address = '".$employer_address[$i]."'";
			if($employer_city[$i]!="")$sql .=",employer_city = '".$employer_city[$i]."'";
			if($employer_state[$i]!="")$sql .=",employer_state = '".$employer_state[$i]."'";
			if($employer_zip[$i]!="")$sql .=",employer_zip = '".$employer_zip[$i]."'";
			if($from_date[$i]!="")$sql .=",from_date = '".$from_date[$i]."'";
			if($starting_salary[$i]!="")$sql .=",starting_salary = '".$starting_salary[$i]."'";
			if($to_date[$i]!="")$sql .=",to_date = '".$to_date[$i]."'";
			if($final_salary[$i]!="")$sql .=",final_salary = '".$final_salary[$i]."'";
			if($employer_telephone[$i]!="")$sql .=",telephone = '".$employer_telephone[$i]."'";
			if($title[$i]!="")$sql .=",title = '".$title[$i]."'";
			if($last_supervisor[$i]!="")$sql .=",last_supervisor = '".$last_supervisor[$i]."'";
			if($reason_leaving[$i]!="")$sql .=",reason_leaving = '".$reason_leaving[$i]."'";
			if($working_employer[$i]!="")$sql .=",working_employer = '".$working_employer[$i]."'";
			if($contact[$i]!="")$sql .=",contact = '".$contact[$i]."'";
			if($app_id>0)$sql .=",app_id = ".$app_id;
			$sql .="where id = ".$employer_id[$i];
			if(!($result= pg_query($connection,$sql)))
			{
				$return_arr['error'] = "Error while inserting employer information to database!";	
				echo json_encode($return_arr);
				return;
			}
		}
	}
	if(isset($reference_name))
	{
		$sql = "";
		for($i=0; $i<count($reference_name); $i++)
		{
			$sql = "Update dtbl_jobapplicant_references set status = 1";
			if($reference_name[$i]!="")$sql .=",reference_name = '".$reference_name[$i]."'";
			if($occupation[$i]!="")$sql .=",occupation = '".$occupation[$i]."'";
			if($reference_address[$i]!="")$sql .=",address = '".$reference_address[$i]."'";
			if($reference_city[$i]!="")$sql .=",city = '".$reference_city[$i]."'";
			if($reference_state[$i]!="")$sql .=",state = '".$reference_state[$i]."'";
			if($reference_zip[$i]!="")$sql .=",zip = '".$reference_zip[$i]."'";
			if($reference_telephone[$i]!="")$sql .=",telephone = '".$reference_telephone[$i]."'";
			if($years_known[$i]!="")$sql .=",years_known = '".$years_known[$i]."'";
			if($app_id>0)$sql .=",app_id = ".$app_id;
			$sql .="where reference_id = ".$reference_id[$i];
			if(!($result= pg_query($connection,$sql)))
			{
				$return_arr['error'] = "Error while inserting reference information to database!";	
				echo json_encode($return_arr);
				return;
			}
		}
	}
	if(isset($offense))
	{
		$sql = "";
		for($i=0; $i<count($offense); $i++)
		{
			$sql = "Update dtbl_jobapplicant_offences set status = 1";
			if($offense[$i]!="")$sql .=",offense = '".$offense[$i]."'";
			if($offense_date[$i]!="")$sql .=",offense_date = '".$offense_date[$i]."'";
			if($offense_location[$i]!="")$sql .=",offense_location = '".$offense_location[$i]."'";
			if($offense_comments[$i]!="")$sql .=",offense_comments = '".$offense_comments[$i]."'";
			if($app_id>0)$sql .=",app_id = ".$app_id;
			$sql .="where offense_id = ".$offense_id[$i];
			if(!($result= pg_query($connection,$sql)))
			{
				$return_arr['error'] = "Error while inserting offense information to database!";	
				echo json_encode($return_arr);
				return;
			}
		}
	}
	if($success1!=false && $success2!=false && $success3!=false && $success4!=false && $success5!=false && $success6==false)
	{
		echo"<script>alert('successfully submited')</script>";
			
	}
}
?>
<link rel="stylesheet" type="text/css" href="../../formwizard.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="../../js/formwizard.js" ></script>
<script type="text/javascript">

var myform=new formtowizard({
	formid: 'feedbackform',
	persistsection: true,
	revealfx: ['slide', 500]
});



</script>

<script type="text/javascript">
var myform3=new formtowizard({
 formid: 'staff_feedbackform',
 validate: ['staff_username', 'staff_sex', 'staff_addr1'],
 revealfx: ['slide', 500] //<--no comma after last setting
});
</script>

<?php 
if($app > 0)
{
  $aryRest= $objDB->getResultMulti($connection,"SELECT 
  dtbl_jobapplicant.\"appID\", 
  dtbl_jobapplicant.job_req, 
  dtbl_jobapplicant.salary, 
  dtbl_jobapplicant.refferedid, 
  dtbl_jobapplicant.\"joiningDate\", 
  dtbl_jobapplicant.\"UID\", 
  dtbl_jobapplicant.\"nameLast\", 
  dtbl_jobapplicant.\"nameSuffix\", 
  dtbl_jobapplicant.\"nameFirst\", 
  dtbl_jobapplicant.\"nameMiddle\", 
  dtbl_jobapplicant.\"nickName\", 
  dtbl_jobapplicantaddress.curr_address, 
  dtbl_jobapplicantaddress.\"curr_City\", 
  dtbl_jobapplicantaddress.\"curr_State\", 
  dtbl_jobapplicantaddress.\"cur_zipCode\", 
  dtbl_jobapplicantaddress.\"cur_YrLived\", 
  dtbl_jobapplicantaddress.pre_address1, 
  dtbl_jobapplicantaddress.\"pre_City1\", 
  dtbl_jobapplicantaddress.\"pre_State1\", 
  dtbl_jobapplicantaddress.\"pre_zipCode1\", 
  dtbl_jobapplicantaddress.\"pre_YrLived1\", 
  dtbl_jobapplicantaddress.pre_address2, 
  dtbl_jobapplicantaddress.\"pre_City2\", 
  dtbl_jobapplicantaddress.\"pre_State2\", 
  dtbl_jobapplicantaddress.\"pre_zipCode2\", 
  dtbl_jobapplicantaddress.\"pre_YrLived2\", 
  dtbl_jobapplicantaddress.pre_address3, 
  dtbl_jobapplicantaddress.\"pre_City3\", 
  dtbl_jobapplicantaddress.\"pre_State3\", 
  dtbl_jobapplicantaddress.\"pre_zipCode3\", 
  dtbl_jobapplicantaddress.\"pre_YrLived3\", 
  dtbl_jobapplicantaddress.tel_no, 
  dtbl_jobapplicantaddress.emailid, 
  dtbl_jobapplicantaddress.age_lmt, 
  dtbl_jobapplicantaddress.leg_aut, 
  dtbl_jobapplicantaddress.\"wokd_for_DMG\", 
  dtbl_jobapplicantaddress.wrk_det, 
  dtbl_jobapplicantaddress.\"frnd_in_DMG\", 
  dtbl_jobapplicantaddress.frnd_name, 
  dtbl_jobapplicantaddress.trnspot_req, 
  dtbl_jobapplicantaddress.crime_chrgd, 
  dtbl_jobapplicantaddress.crime_det, 
  dtbl_jobapplicanteducation.school_name1, 
  dtbl_jobapplicanteducation.\"s1_City\", 
  dtbl_jobapplicanteducation.\"s1_State\", 
  dtbl_jobapplicanteducation.\"s1_zipCode\", 
  dtbl_jobapplicanteducation.s1_country, 
  dtbl_jobapplicanteducation.s1_is_graduate, 
  dtbl_jobapplicanteducation.s1_grd_prd_frm, 
  dtbl_jobapplicanteducation.s1_grd_prd_to, 
  dtbl_jobapplicanteducation.s1_grd_dt, 
  dtbl_jobapplicanteducation.s1_grd_title, 
  dtbl_jobapplicanteducation.s1_grd_main, 
  dtbl_jobapplicanteducation.s1_desc_others, 
  dtbl_jobapplicanteducation.school_name2, 
  dtbl_jobapplicanteducation.\"s2_City\", 
  dtbl_jobapplicanteducation.\"s2_State\", 
  dtbl_jobapplicanteducation.\"s2_zipCode\", 
  dtbl_jobapplicanteducation.s2_country, 
  dtbl_jobapplicanteducation.s2_is_graduate, 
  dtbl_jobapplicanteducation.s2_grd_prd_frm, 
  dtbl_jobapplicanteducation.s2_grd_prd_to, 
  dtbl_jobapplicanteducation.s2_grd_dt, 
  dtbl_jobapplicanteducation.s2_grd_title, 
  dtbl_jobapplicanteducation.s2_grd_main, 
  dtbl_jobapplicanteducation.s2_desc_others, 
  dtbl_jobapplicanteducation.school_name3, 
  dtbl_jobapplicanteducation.\"s3_City\", 
  dtbl_jobapplicanteducation.\"s3_State\", 
  dtbl_jobapplicanteducation.\"s3_zipCode\", 
  dtbl_jobapplicanteducation.s3_country, 
  dtbl_jobapplicanteducation.s3_is_graduate, 
  dtbl_jobapplicanteducation.s3_grd_prd_frm, 
  dtbl_jobapplicanteducation.s3_grd_prd_to, 
  dtbl_jobapplicanteducation.s3_grd_dt, 
  dtbl_jobapplicanteducation.s3_grd_title, 
  dtbl_jobapplicanteducation.s3_grd_main, 
  dtbl_jobapplicanteducation.s3_desc_others, 
  dtbl_jobapplicanteducation.school_name4, 
  dtbl_jobapplicanteducation.\"s4_City\", 
  dtbl_jobapplicanteducation.\"s4_State\", 
  dtbl_jobapplicanteducation.\"s4_zipCode\", 
  dtbl_jobapplicanteducation.s4_country, 
  dtbl_jobapplicanteducation.s4_is_graduate, 
  dtbl_jobapplicanteducation.s4_grd_prd_frm, 
  dtbl_jobapplicanteducation.s4_grd_prd_to, 
  dtbl_jobapplicanteducation.s4_grd_dt, 
  dtbl_jobapplicanteducation.s4_grd_title, 
  dtbl_jobapplicanteducation.s4_grd_main, 
  dtbl_jobapplicanteducation.s4_desc_others, 
  dtbl_jobapplicanteducation.is_nam_chgd, 
  dtbl_jobapplicanteducation.name_det, 
  dtbl_jobapplicanteducation.experience_det, 
  dtbl_jobapplicanteducation.is_capable, 
  dtbl_jobapplicantemployer.empr_name, 
  dtbl_jobapplicantemployer.empr_address, 
  dtbl_jobapplicantemployer.empr_city, 
  dtbl_jobapplicantemployer.empr_state, 
  dtbl_jobapplicantemployer.\"empr_zipCode\", 
  dtbl_jobapplicantemployer.empr_from, 
  dtbl_jobapplicantemployer.empr_to, 
  dtbl_jobapplicantemployer.empr_start_salary, 
  dtbl_jobapplicantemployer.empr_end_salary, 
  dtbl_jobapplicantemployer.empr_tel_no, 
  dtbl_jobapplicantemployer.empr_position, 
  dtbl_jobapplicantemployer.empr_duties_major, 
  dtbl_jobapplicantemployer.empr_name_supvr, 
  dtbl_jobapplicantemployer.empr_reason_leaving, 
  dtbl_jobapplicantemployer.empr_is_currly_working, 
  dtbl_jobapplicantemployer.empr_may_contact, 
  dtbl_jobapplicantemployer.p1_name, 
  dtbl_jobapplicantemployer.p1_empr_address, 
  dtbl_jobapplicantemployer.p1_empr_city, 
  dtbl_jobapplicantemployer.p1_empr_state, 
  dtbl_jobapplicantemployer.p1_empr_zipcode, 
  dtbl_jobapplicantemployer.p1_from, 
  dtbl_jobapplicantemployer.p1_to, 
  dtbl_jobapplicantemployer.p1_empr_start_salary, 
  dtbl_jobapplicantemployer.p1_empr_end_salary, 
  dtbl_jobapplicantemployer.p1_empr_tel_no, 
  dtbl_jobapplicantemployer.p1_position, 
  dtbl_jobapplicantemployer.p1_empr_duties_major, 
  dtbl_jobapplicantemployer.p1_empr_name_supvr, 
  dtbl_jobapplicantemployer.p1_empr_reason_leaving, 
  dtbl_jobapplicantemployer.p2_name, 
  dtbl_jobapplicantemployer.p2_empr_address, 
  dtbl_jobapplicantemployer.p2_empr_city, 
  dtbl_jobapplicantemployer.p2_empr_state, 
  dtbl_jobapplicantemployer.p2_empr_zipcode, 
  dtbl_jobapplicantemployer.p2_from, 
  dtbl_jobapplicantemployer.p2_to, 
  dtbl_jobapplicantemployer.p2_empr_start_salary, 
  dtbl_jobapplicantemployer.p2_empr_end_salary, 
  dtbl_jobapplicantemployer.p2_empr_tel_no, 
  dtbl_jobapplicantemployer.p2_position, 
  dtbl_jobapplicantemployer.p2_empr_duties_major, 
  dtbl_jobapplicantemployer.p2_empr_name_supvr, 
  dtbl_jobapplicantemployer.p2_empr_reason_leaving, 
  dtbl_jobapplicantemployer.p3_name, 
  dtbl_jobapplicantemployer.p3_empr_address, 
  dtbl_jobapplicantemployer.p3_empr_city, 
  dtbl_jobapplicantemployer.p3_empr_state, 
  dtbl_jobapplicantemployer.p3_empr_zipcode, 
  dtbl_jobapplicantemployer.p3_from, 
  dtbl_jobapplicantemployer.p3_to, 
  dtbl_jobapplicantemployer.p3_empr_start_salary, 
  dtbl_jobapplicantemployer.p3_empr_end_salary, 
  dtbl_jobapplicantemployer.p3_empr_tel_no, 
  dtbl_jobapplicantemployer.p3_position, 
  dtbl_jobapplicantemployer.p3_empr_duties_major, 
  dtbl_jobapplicantemployer.p3_empr_name_supvr, 
  dtbl_jobapplicantemployer.p3_empr_reason_leaving, 
  dtbl_jobapplicantemp_hist.any_comm, 
  dtbl_jobapplicantemp_hist.exp_comm, 
  dtbl_jobapplicantemp_hist.ask_to_resign, 
  dtbl_jobapplicantemp_hist.exp_reason, 
  dtbl_jobapplicantemp_hist.ref1_name, 
  dtbl_jobapplicantemp_hist.ref1_occ, 
  dtbl_jobapplicantemp_hist.ref1_address, 
  dtbl_jobapplicantemp_hist.\"ref1_City\", 
  dtbl_jobapplicantemp_hist.\"ref1_State\", 
  dtbl_jobapplicantemp_hist.\"ref1_zipCode\", 
  dtbl_jobapplicantemp_hist.\"ref1_Tel_No\", 
  dtbl_jobapplicantemp_hist.\"ref1_no_Yrs\", 
  dtbl_jobapplicantemp_hist.ref2_name, 
  dtbl_jobapplicantemp_hist.ref2_occ, 
  dtbl_jobapplicantemp_hist.ref2_address, 
  dtbl_jobapplicantemp_hist.\"ref2_City\", 
  dtbl_jobapplicantemp_hist.\"ref2_State\", 
  dtbl_jobapplicantemp_hist.\"ref2_zipCode\", 
  dtbl_jobapplicantemp_hist.\"ref2_Tel_No\", 
  dtbl_jobapplicantemp_hist.\"ref2_no_Yrs\", 
  dtbl_jobapplicantemp_hist.ref3_name, 
  dtbl_jobapplicantemp_hist.ref3_occ, 
  dtbl_jobapplicantemp_hist.ref3_address, 
  dtbl_jobapplicantemp_hist.\"ref3_City\", 
  dtbl_jobapplicantemp_hist.\"ref3_State\", 
  dtbl_jobapplicantemp_hist.\"ref3_zipCode\", 
  dtbl_jobapplicantemp_hist.\"ref3_Tel_No\", 
  dtbl_jobapplicantemp_hist.\"ref3_no_Yrs\", 
  dtbl_jobapplicantemp_hist.ref4_name, 
  dtbl_jobapplicantemp_hist.ref4_occ, 
  dtbl_jobapplicantemp_hist.ref4_address, 
  dtbl_jobapplicantemp_hist.\"ref4_City\", 
  dtbl_jobapplicantemp_hist.\"ref4_State\", 
  dtbl_jobapplicantemp_hist.\"ref4_zipCode\", 
  dtbl_jobapplicantemp_hist.\"ref4_Tel_No\", 
  dtbl_jobapplicantemp_hist.\"ref4_no_Yrs\", 
  dtbl_jobapplicantdri_det.have_dri_licence, 
  dtbl_jobapplicantdri_det.ever_susp, 
  dtbl_jobapplicantdri_det.exp_susp, 
  dtbl_jobapplicantdri_det.have_traf_violations, 
  dtbl_jobapplicantdri_det.off1_det, 
  dtbl_jobapplicantdri_det.off1_date, 
  dtbl_jobapplicantdri_det.off1_location, 
  dtbl_jobapplicantdri_det.off1_commts, 
  dtbl_jobapplicantdri_det.off2_det, 
  dtbl_jobapplicantdri_det.off2_date, 
  dtbl_jobapplicantdri_det.off2_location, 
  dtbl_jobapplicantdri_det.off2_commts, 
  dtbl_jobapplicantdri_det.off3_det, 
  dtbl_jobapplicantdri_det.off3_date, 
  dtbl_jobapplicantdri_det.off3_location, 
  dtbl_jobapplicantdri_det.off3_commts, 
  dtbl_jobapplicantdri_det.off4_det, 
  dtbl_jobapplicantdri_det.off4_date, 
  dtbl_jobapplicantdri_det.off4_location, 
  dtbl_jobapplicantdri_det.off4_commts, 
  dtbl_jobapplicantdri_det.have_per_aut_ins, 
  dtbl_jobapplicantdri_det.ex_no_insurance
FROM public.dtbl_jobapplicant left join public.dtbl_jobapplicantaddress on public.dtbl_jobapplicant.\"appID\"=public.dtbl_jobapplicantaddress.\"appID\" 
         left join public.dtbl_jobapplicanteducation on public.dtbl_jobapplicant.\"appID\"=public.dtbl_jobapplicanteducation.\"appID\"  
         left join public.dtbl_jobapplicantemployer on public.dtbl_jobapplicant.\"appID\"=public.dtbl_jobapplicantemployer.\"appID\" 
         left join public.dtbl_jobapplicantemp_hist on public.dtbl_jobapplicant.\"appID\"=public.dtbl_jobapplicantemp_hist.\"appID\" 
         left join public.dtbl_jobapplicantdri_det on public.dtbl_jobapplicant.\"appID\"=public.dtbl_jobapplicantdri_det.\"appID\"  WHERE 
  dtbl_jobapplicant.\"appID\" = $app
ORDER BY
  dtbl_jobapplicant.\"appID\" ASC;
");
	$sql = "select * from dtbl_applicant_previous_address where app_id = $app";
	if(!($result=pg_query($connection,$sql))){
		print("Failed query1: " . pg_last_error($connection));
		exit;
	}
	while($row = pg_fetch_array($result)){
		$data_previous_address[]=$row;
	}
	pg_free_result($result);
	
	$sql = "select * from dtbl_jobapplicant_education where app_id = $app";
	if(!($result=pg_query($connection,$sql))){
		print("Failed query1: " . pg_last_error($connection));
		exit;
	}
	while($row = pg_fetch_array($result)){
		$data_education[]=$row;
	}
	pg_free_result($result);
	
	$sql = "select * from dtbl_jobapplicant_offences where app_id = $app";
	if(!($result=pg_query($connection,$sql))){
		print("Failed query1: " . pg_last_error($connection));
		exit;
	}
	while($row = pg_fetch_array($result)){
		$data_offences[]=$row;
	}
	pg_free_result($result);
	
	$sql = "select * from dtbl_jobapplicant_references where app_id = $app";
	if(!($result=pg_query($connection,$sql))){
		print("Failed query1: " . pg_last_error($connection));
		exit;
	}
	while($row = pg_fetch_array($result)){
		$data_references[]=$row;
	}
	pg_free_result($result);
	
	$sql = "select * from dtbl_previous_employer where app_id = $app";
	if(!($result=pg_query($connection,$sql))){
		print("Failed query1: " . pg_last_error($connection));
		exit;
	}
	while($row = pg_fetch_array($result)){
		$data_employer[]=$row;
	}
	pg_free_result($result);

}
else
{
if(count($aryRest)==0)
	{
		for($c=0;$c<209;$c++)
		{
			$aryRest[0][$c]="";
		}
	}
}
//echo $aryRest[0][1];
?>

<center>
              <h3><b>Applications List Page </b></h3>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center" valign="top"><table width="95%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="35" align="center" valign="top">
     <form id="feedbackform" name="feedbackform" method="post" >

<fieldset class="sectionwrap">
<legend>Basic Information</legend>



  
  <br />
  <fieldset>
  <div class="contentB">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><img src="../../images/trans.gif" alt="spacer" width="750" height="25"></td>
    </tr>
  </table>
<fieldset>

<table width="100%" border="0" cellpadding="0" cellspacing="0" id="phase001">
  <tr>
    <td align="left" valign="top"><strong> EACH INQUIRY ON THIS APPLICATION MUST BE COMPLETED FULLY.</strong><br />
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="25" align="left" valign="top">What position are you applying for?</td>
  </tr>
  <tr>
    <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtJob","txtJob",$aryRest[0][1],"txtFieldForm");?></td>
  </tr>
  <tr>
    <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="25">What is your desired salary?</td>
        <td width="10">&nbsp;</td>
        <td>How were you referred to us?</td>
      </tr>
      <tr>
        <td height="25"><?php echo $objFrm->textTag("txtSalary","txtSalary",$aryRest[0][2],"txtFieldForm");?></td>
        <td>&nbsp;</td>
        <td><?php 
        $ary= $objDB->getResultMulti($connection,"SELECT refferedid,refferedname FROM dtbl_reffered_mst;");
        echo $objFrm->comboTag("cmdReff","cmdReff",$aryRest[0][3],$ary,'');?>
       
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="25" align="left" valign="top">If offered the position when would you be available to start?</td>
  </tr>
  <tr>
    <td height="25" align="left" valign="top">
     <?php  
     $aryMonth=array(array(1,'January'),array(2,'February'),array(3,'March'),array(4,'April'),array(5,'May'),
    array(6,'June'),array(7,'July'),array(8,'August'), array(9,'September'),array(10,'October'),array(11,'November'),array(12,'December'));
   $s=2011;
    for($i=1;$i<=31;$i++)
    {
      $aryDt[$i][0]=$i;
       $aryDt[$i][1]=$i;
       
        $aryYr[$i][0]=$s;
       $aryYr[$i][1]=$s;
       $s++;
   
    }
    
         
      $aryYr[0][0]=2011;
       $aryYr[0][1]=2011;
    $ar=explode("-", $aryRest[0][4]);
   
     echo $objFrm->comboTag("cmdStDtyear","cmdStDtyear",$ar[0],$aryYr,'').$objFrm->comboTag("cmdStDtmonth","cmdStDtmonth",$ar[1],$aryMonth,'').$objFrm->comboTag("cmdStDtday","cmdStDtday",$ar[2],$aryDt,'');?>
    </td>
  </tr>
  <tr>
    <td height="25" align="left" valign="top"><strong><br />
      PERSONAL DATA **YOU MUST USE YOUR LEGAL NAME AS LISTED ON YOUR SOCIAL SECURITY CARD**</strong><br />
      <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="25" align="left" valign="top">Social Security Number:</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSSN1","txtSSN1",substr($aryRest[0][5],0,-6),"txtFieldFormSmall").
          $objFrm->textTag("txtSSN2","txtSSN2",substr($aryRest[0][5],3,-4),"txtFieldFormSmall").$objFrm->textTag("txtSSN3","txtSSN3",substr($aryRest[0][5],5),"txtFieldFormSmall");?></td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="25">Legal First Name</td>
                <td width="10">&nbsp;</td>
                <td>Middle Name</td>
                <td width="10">&nbsp;</td>
                <td>Legal Last Name</td>
                <td width="10">&nbsp;</td>
                <td>Suffix (Sr., II, etc.)</td>
              </tr>
              <tr>
                <td height="25"><?php echo $objFrm->textTag("txtFrstName","txtFrstName",$aryRest[0][8],"txtFieldForm");?></td>
                <td>&nbsp;</td>
                <td><?php echo $objFrm->textTag("txtMdlName","txtMdlName",$aryRest[0][9],"txtFieldForm");?></td>
                <td>&nbsp;</td>
                <td><?php echo $objFrm->textTag("txtLegName","txtLegName",$aryRest[0][6],"txtFieldForm");?></td>
                <td>&nbsp;</td>
                <td><?php echo $objFrm->textTag("txtSrName","txtSrName",$aryRest[0][7],"txtFieldForm");?></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">Preferred Name (Nickname)</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtNickName","txtNickName",$aryRest[0][10],"txtFieldForm");?></td>
        </tr>
        
      </table></td>
  </tr>
</table>
</td>
  </tr>
</table>
</fieldset>
</div>
  </fieldset>

</fieldset>



<fieldset class="sectionwrap">


<legend>Address Information<br>
</legend>
<fieldset>
<div class="contentB">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<fieldset><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><img src="../../images/trans.gif" alt="spacer" width="750" height="25"></td>
    </tr>
  </table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="phase002">
  <tr>
    <td align="left" valign="top"><strong>LIST ALL ADDRESSES YOU HAVE RESIDED IN WITHIN THE LAST 10 YEARS.</strong><br />
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="25" align="left" valign="top"><strong>Present Address</strong></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtCurAdd","txtCurAdd",$aryRest[0][11],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">City:</td>
                  <td width="10">&nbsp;</td>
                  <td>State:</td>
                  <td width="10">&nbsp;</td>
                  <td>Zip/Postal Code</td>
                </tr>
                <tr>
                  <td height="25"><?php echo $objFrm->textTag("txtCurCity","txtCurCity",$aryRest[0][12],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtCurState","txtCurState",$aryRest[0][13],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtCurZip","txtCurZip",$aryRest[0][14],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">How long have you lived there? (Years/Months)</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtCurYrsLived","txtCurYrsLived",$aryRest[0][15],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">&nbsp;</td>
          </tr>
          
<?php
          for($i=0; $i<count($data_previous_address); $i++)
          {
?>
          	<tr>
            <td height="25" align="left" valign="top"><strong>Previous Address</strong></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><input name="previous_address[]" type="text" value="<?php echo $data_previous_address[$i]['address'];?>" /></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">City:</td>
                  <td width="10">&nbsp;</td>
                  <td>State:</td>
                  <td width="10">&nbsp;</td>
                  <td>Zip/Postal Code</td>
                </tr>
                <tr>
                  <td height="25"><input name="city[]" type="text" value="<?php echo $data_previous_address[$i]['city'];?>" /></td>
                  <td>&nbsp;</td>
                  <td><input name="state[]" type="text" value="<?php echo $data_previous_address[$i]['state'];?>" /></td>
                  <td>&nbsp;</td>
                  <td><input name="zip[]" type="text" value="<?php echo $data_previous_address[$i]['zip'];?>" /></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">How long did you live  there? (Years/Months) </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><input name="lived_here[]" type="text" value="<?php echo $data_previous_address[$i]['years_lived'];?>" /><input name="previous_address_id[]" type="hidden" value="<?php echo $data_previous_address[$i]['id'];?>" /></td>
          </tr>
           <tr>
            <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
          </tr>
         
          <tr>
            <td height="25" align="left" valign="top">&nbsp;</td>
          </tr>
<?php
          }
?>   
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">Telephone Number :</td>
                  <td width="10">&nbsp;</td>
                  <td>Email Address :</td>
                </tr>
                <tr>
                  <td height="25"><?php echo $objFrm->textTag("txtTelNo","txtTelNo",$aryRest[0][31],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtEmail","txtEmail",$aryRest[0][32],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Are you 18 years of age or older? </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdAgeLmt","rdAgeLmt",array(1,0),array("Yes","No"),$aryRest[0][33],0)?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Are you legally authorized to work in the United States? </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdLegAut","rdLegAut",array(1,0),array("Yes","No"),$aryRest[0][34],0)?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Have you ever worked for Davis Merchandising Group before? </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdWorDMG","rdWorDMG",array(1,0),array("Yes","No"),$aryRest[0][35],0)?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">If Yes, give dates and position: </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtPosition","txtPosition",$aryRest[0][36],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Do you have any friends or relatives who work for Davis Merchandising Group? </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdFrndDMG","rdFrndDMG",array(1,0),array("Yes","No"),$aryRest[0][37],0)?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">If Yes, Name: </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtNameFrnd","txtNameFrnd",$aryRest[0][38],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Do you have adequate transportation to and from work? </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdTransWork","rdTransWork",array(1,0),array("Yes","No"),$aryRest[0][39],0)?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Have you ever been charged with a crime? </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdCrime","rdCrime",array(1,0),array("Yes","No"),$aryRest[0][40],2)?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top" class="astericks">Note: Answering yes to questions above does not automatically disqualify you from employment. </td>
          </tr>

          <tr>
            <td height="25" align="left" valign="top">If Yes, you must  complete this section. Please give dates and details of each: </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtCrime","txtCrime",$aryRest[0][41],"txtFieldForm");?></td>
          </tr>
      
      </table></td>
  </tr>
</table>
</fieldset>
</div>
</fieldset>

</fieldset>

<fieldset class="sectionwrap">

<legend>Education</legend><br>


<fieldset>
<div class="contentB">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<fieldset><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><img src="../../images/trans.gif" alt="spacer" width="750" height="25"></td>
    </tr>
  </table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="phase003">
  <tr>
    <td><strong>EDUCATION AND EXPERIENCE</strong> <br />
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="25" align="left" valign="top"><strong>HIGH SCHOOL / GED</strong></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">School Name: </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch1Name","txtSch1Name",$aryRest[0][42],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">City</td>
                  <td width="10">&nbsp;</td>
                  <td>State</td>
                  <td width="10">&nbsp;</td>
                  <td>Zip Code </td>
                  <td width="10">&nbsp;</td>
                  <td>Country</td>
                </tr>
                <tr>
                  <td height="25"><?php echo $objFrm->textTag("txtSch1City","txtSch1City",$aryRest[0][43],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch1State","txtSch1State",$aryRest[0][44],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch1Zip","txtSch1Zip",$aryRest[0][45],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch1Country","txtSch1Country",$aryRest[0][46],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Did You Graduate?</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdSch1Grd","rdSch1Grd",array(1,0,2),array("Yes","No","Currently Enrolled "),$aryRest[0][47],0)?>
              </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Dates of Attendance: </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">From</td>
                  <td width="10">&nbsp;</td>
                  <td>To</td>
                </tr>
                <tr>
                  <td height="25"><?php echo $objFrm->textTag("txtSch1Frm","txtSch1Frm",$aryRest[0][48],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch1To","txtSch1To",$aryRest[0][49],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Graduation Date (mm/yyyy) </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch1GrdDt","txtSch1GrdDt",$aryRest[0][50],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">Diploma/Degree Earned<br /></td>
                  <td width="10">&nbsp;</td>
                  <td>Major/Course of Study</td>
                </tr>
                <tr>
                  <td height="25"><?php echo $objFrm->textTag("txtSch1Title","txtSch1Title",$aryRest[0][51],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch1Main","txtSch1Main",$aryRest[0][52],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Describe Specialized Training, Military Experience, Skills, and  Extra-Curricular Activities </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch1Extras","txtSch1Extras",$aryRest[0][53],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
          </tr>
        </table>
<?php
		if(count($data_education)>0)
		{
?>
			  <br />
        <strong>POST SECONDARY EDUCATION (College, University, Graduate School, etc.)
          List ALL schools you have attended and / or obtained a degree from below.</strong> <br />
      <br />
<?php
        }
?>
<?php
for($i=0; $i<count($data_education); $i++)
{
?>
     <table width="100%" border="0" cellpadding="0" cellspacing="0" id="phase003">
            <tr>
            <td height="25" align="left" valign="top"><strong>School Name:</strong> </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><input name="school_name[]" type="text" value="<?php echo $data_education[$i]['school_name'];?>"/></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">City</td>
                  <td width="10">&nbsp;</td>
                  <td>State</td>
                  <td width="10">&nbsp;</td>
                  <td>Zip Code </td>
                  <td width="10">&nbsp;</td>
                  <td>Country</td>
                </tr>
                <tr>
                  <td height="25"><input name="school_city[]" type="text" value="<?php echo $data_education[$i]['school_city'];?>" /></td>
                  <td>&nbsp;</td>
                  <td><input name="school_state[]" type="text" value="<?php echo $data_education[$i]['school_state'];?>" /></td>
                  <td>&nbsp;</td>
                  <td><input name="school_zip[]" type="text" value="<?php echo $data_education[$i]['school_zip'];?>" /></td>
                  <td>&nbsp;</td>
                  <td><input name="school_country[]" type="text" value="<?php echo $data_education[$i]['school_country'];?>" /></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Did You Graduate?</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">
            <?php 
			if($data_education[$i]['did_graduate'] == 1)
			{
			?>
            Yes<input name="did_graduate[]" type="radio" value="1" checked="checked" />No<input name="did_graduate[]" type="radio" value="0" />
            <?php
			}
			else 
			{
			?>
             Yes<input name="did_graduate[]" type="radio" value="1" />No<input name="did_graduate[]" type="radio" value="0" checked="checked" />
             <?php
			}
			 ?>
              </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Dates of Attendance: </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">From</td>
                  <td width="10">&nbsp;</td>
                  <td>To</td>
                </tr>
                <tr>
                  <td height="25"><input name="from_date[]" type="text" value="<?php echo $data_education[$i]['from_date'];?>" /></td>
                  <td>&nbsp;</td>
                  <td><input name="to_date[]" type="text" value="<?php echo $data_education[$i]['to_date'];?>" /></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Graduation Date (mm/yyyy) </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><input name="graduation_date[]" type="text" value="<?php echo $data_education[$i]['graduation_date'];?>" /></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">Diploma/Degree Earned<br /></td>
                  <td width="10">&nbsp;</td>
                  <td>Major/Course of Study</td>
                </tr>
                <tr>
                  <td height="25"><input name="diploma[]" type="text" value="<?php echo $data_education[$i]['diploma'];?>" /></td>
                  <td>&nbsp;</td>
                  <td><input name="course[]" type="text" value="<?php echo $data_education[$i]['major_course'];?>" /></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Describe Specialized Training, Military Experience, Skills, and  Extra-Curricular Activities </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><input name="speacilization[]" type="text" value="<?php echo $data_education[$i]['describe_specialization'];?>" /><input name="education_id[]" type="hidden" value="<?php echo $data_education[$i]['education_id'];?>" /></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
          </tr>
        </table>
<?php
          }
?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">In order to permit a check of your work and educational records, should we be made aware of any change in name? </td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdIsNameChng","rdIsNameChng",array(1,0),array("Yes","No"),$aryRest[0][90],0)?></td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">If Yes, identify names and relevant dates: </td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtNameDt","txtNameDt",$aryRest[0][91],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">Please describe any experience that you have which would be relevant to the job for which you are applying. </td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtExp","txtExp",$aryRest[0][92],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">Are you capable of performing the essential job duties required of the position for which you are applying?</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdIsCap","rdIsCap",array(1,0),array("Yes","No"),$aryRest[0][93],0)?></td>
        </tr>
    
      </table></td>
  </tr>
</table>
</fieldset>
</div>
</fieldset>

</fieldset>

<fieldset class="sectionwrap">

<legend>Experience</legend><br>

<fieldset>
<div class="contentB">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<fieldset><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><img src="../../images/trans.gif" alt="spacer" width="750" height="25"></td>
    </tr>
  </table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="phase004">
  <tr>
    <td><strong>RECORD OF PREVIOUS EMPLOYMENT<br />
          <br />
      Please list the names of your present or previous employers for the last 10 years in chronological order with present or last employer first.<br />
      Be sure to account for all periods of time including military service and any period of unemployment.
      If self-employed, give firm name and supply business references.</strong> <br />
      <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="25"><strong>Name of Present or Last Employer</strong></td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtCurEmpName","txtCurEmpName",$aryRest[0][94],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Address</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtCurEmpAdd","txtCurEmpAdd",$aryRest[0][95],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="25">City</td>
              <td width="10">&nbsp;</td>
              <td>State</td>
              <td width="10">&nbsp;</td>
              <td>Zip Code </td>
            </tr>
            <tr>
              <td height="25"><?php echo $objFrm->textTag("txtCurEmpCity","txtCurEmpCity",$aryRest[0][96],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtCurEmpState","txtCurEmpState",$aryRest[0][97],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtCurEmpZip","txtCurEmpZip",$aryRest[0][98],"txtFieldForm");?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td height="25"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="25">From (mo/yr) </td>
              <td width="10">&nbsp;</td>
              <td>To(mo/yr)</td>
            </tr>
            <tr>
              <td height="25"><?php echo $objFrm->textTag("txtCurEmpFrom","txtCurEmpFrom",$aryRest[0][99],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtCurEmpTo","txtCurEmpTo",$aryRest[0][100],"txtFieldForm");?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td height="25"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="25">Starting Salary</td>
              <td width="10">&nbsp;</td>
              <td>Final Salary</td>
            </tr>
            <tr>
              <td height="25"><?php 
               $ary= $objDB->getResultMulti($connection,"SELECT salranid, rangevalue FROM dtbl_salaryrange_mst;");
              
       
        echo $objFrm->comboTag("cmdCurEmpSSal","cmdCurEmpSSal",$aryRest[0][101],$ary,'');?></td>
              <td>&nbsp;</td>
              <td height="25"><?php 
 				
              echo $objFrm->comboTag("cmdCurEmpFSal","cmdCurEmpFSal",$aryRest[0][102],$ary,'');?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td height="25">Telephone:</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtCurEmpTel","txtCurEmpTel",$aryRest[0][103],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Your Title or Position</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtCurEmpPos","txtCurEmpPos",$aryRest[0][104],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Major Job Duties</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtCurEmpDut","txtCurEmpDut",$aryRest[0][105],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Name of Last Supervisor</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtCurEmpSup","txtCurEmpSup",$aryRest[0][106],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Reason for Leaving</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtCurEmpLeave","txtCurEmpLeave",$aryRest[0][107],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Are you currently working for this employer?</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdCurEmpWorking","rdCurEmpWorking",array(1,0),array("Yes","No"),$aryRest[0][108],0)?></td>
        </tr>
        <tr>
          <td height="25">May we contact?</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdCurEmpMayCont","rdCurEmpMayCont",array(1,0),array("Yes","No"),$aryRest[0][109],0)?></td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">&nbsp;</td>
        </tr>
      </table>
<?php
for($i=0; $i<count($data_employer); $i++)
{
?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="25"><strong>Name of Previous Employer </strong></td>
        </tr>
        <tr>
          <td height="25"><input name="employer_name[]" type="text" value="<?php echo $data_employer[$i]['employer_name'];?>" /><input name="employer_id[]" type="hidden" value="<?php echo $data_employer[$i]['id'];?>" /></td>
        </tr>
        <tr>
          <td height="25">Address</td>
        </tr>
        <tr>
          <td height="25"><input name="employer_address[]" type="text" value="<?php echo $data_employer[$i]['employer_address'];?>" /></td>
        </tr>
        <tr>
          <td height="25"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="25">City</td>
              <td width="10">&nbsp;</td>
              <td>State</td>
              <td width="10">&nbsp;</td>
              <td>Zip Code </td>
            </tr>
            <tr>
              <td height="25"><input name="employer_city[]" type="text" value="<?php echo $data_employer[$i]['employer_city'];?>" /></td>
              <td>&nbsp;</td>
              <td><input name="employer_state[]" type="text" value="<?php echo $data_employer[$i]['employer_state'];?>" /></td>
              <td>&nbsp;</td>
              <td><input name="employer_zip[]" type="text" value="<?php echo $data_employer[$i]['employer_zip'];?>" /></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td height="25"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="25">From (mo/yr) </td>
              <td width="10">&nbsp;</td>
              <td>To(mo/yr)</td>
            </tr>
            <tr>
              <td height="25"><input name="from_date[]" type="text" value="<?php echo $data_employer[$i]['from_date'];?>" /></td>
              <td>&nbsp;</td>
              <td><input name="to_date[]" type="text" value="<?php echo $data_employer[$i]['to_date'];?>" /></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td height="25"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="25">Starting Salary</td>
              <td width="10">&nbsp;</td>
              <td>Final Salary</td>
            </tr>
            <tr>
              <td height="25"><select name="start_salary[]">
               <option value="1">Yes</option>
              <option value="0">No</option></select></td>
              <td>&nbsp;</td>
              <td height="25"><select name="final_salary[]">
               <option value="1">Yes</option>
              <option value="0">No</option></select></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td height="25">Telephone:</td>
        </tr>
        <tr>
          <td height="25"><input name="employer_telephone[]" type="text" value="<?php echo $data_employer[$i]['telephone'];?>" /></td>
        </tr>
        <tr>
          <td height="25">Your Title or Position</td>
        </tr>
        <tr>
          <td height="25"><input name="title[]" type="text" value="<?php echo $data_employer[$i]['title'];?>" /></td>
        </tr>
        <tr>
          <td height="25">Major Job Duties</td>
        </tr>
        <tr>
          <td height="25"><input name="job_duties[]" type="text" value="<?php echo $data_employer[$i]['job_duties'];?>" /></td>
        </tr>
        <tr>
          <td height="25">Name of Last Supervisor</td>
        </tr>
        <tr>
          <td height="25"><input name="last_supervisor[]" type="text" value="<?php echo $data_employer[$i]['last_supervisor'];?>" /></td>
        </tr>
        <tr>
          <td height="25">Reason for Leaving</td>
        </tr>
        <tr>
          <td height="25"><input name="reason_leaving[]" type="text" value="<?php echo $data_employer[$i]['reason_leaving'];?>"/></td>
        </tr>
         <tr>
          <td height="25">Are you currently working for this employer?</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">
		  <?php 
			if($data_employer[$i]['working_employer'] == 1)
			{
			?>
            Yes<input name="working_employer[]" type="radio" value="1"  checked="checked" />No<input name="working_employer[]" type="radio" value="0" />
            <?php
			}
			else if($data_employer[$i]['working_employer'] == 0) 
			{
			?>
             Yes<input name="working_employer[]" type="radio" value="1" />No<input name="working_employer[]" type="radio" value="0" checked="checked" />
             <?php
			}
			 ?></td>
        </tr>
        <tr>
          <td height="25">May we contact?</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">
          <?php 
          if($data_employer[$i]['contact'] == 1)
			{
			?>
            Yes<input name="contact[]" type="radio" value="1" checked="checked" />No<input name="contact[]" type="radio" value="0" />
            <?php
			}
			else if($data_employer[$i]['contact'] == 0)
			{
			?>
             Yes<input name="contact[]" type="radio" value="1" />No<input name="contact[]" type="radio" value="0" checked="checked" />
             <?php
			}
			 ?>
          </td>
        </tr>
        <tr>
          <td height="25" class="bttmLine">&nbsp;</td>
        </tr>
        <tr>
          <td height="25">&nbsp;</td>
        </tr>
      </table>
      <?php
}
	  ?>
    </td>
  </tr>
</table>
</fieldset>
</div>
</fieldset>

</fieldset>

<fieldset class="sectionwrap">

<legend>Employment History</legend><br>

<fieldset>
<div class="contentB">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<fieldset><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><img src="../../images/trans.gif" alt="spacer" width="750" height="25"></td>
    </tr>
  </table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="phase005">
  <tr>
    <td><strong><br />
      EMPLOYMENT HISTORY</strong> <br />
      <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="25" align="left" valign="top">Do you have any commitments to any other employer which may affect your employment?</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdCmtToOtr","rdCmtToOtr",array(1,0),array("Yes","No"),$aryRest[0][152],2)?></td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">If Yes, please explain:</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtOtrExpe","txtOtrExpe",$aryRest[0][153],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">Have you ever been terminated or asked to resign from any job?</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdAskToRn","rdAskToRn",array(1,0),array("Yes","No"),$aryRest[0][154],2)?></td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top">If Yes, please explain circumstances:</td>
        </tr>
        <tr>
          <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtReasonRn","txtReasonRn",$aryRest[0][155],"txtFieldForm");?></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><p><strong><br />
            PROFESSIONAL REFERENCES</strong> <br />
            <strong><br />
              List persons you have had a working relationship with, such as a supervisor or  vendor you have supported</strong>. </p>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25" align="left" valign="top"><strong>Name</strong></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef1Name","txtRef1Name",$aryRest[0][156],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Occupation</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef1Occ","txtRef1Occ",$aryRest[0][157],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Address</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef1Add","txtRef1Add",$aryRest[0][158],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="25">City</td>
                        <td width="10">&nbsp;</td>
                        <td>State</td>
                        <td width="10">&nbsp;</td>
                        <td>Zip Code </td>
                      </tr>
                      <tr>
                        <td height="25"><?php echo $objFrm->textTag("txtRef1City","txtRef1City",$aryRest[0][159],"txtFieldForm");?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $objFrm->textTag("txtRef1State","txtRef1State",$aryRest[0][160],"txtFieldForm");?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $objFrm->textTag("txtRef1Zip","txtRef1Zip",$aryRest[0][161],"txtFieldForm");?></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Telephone Number</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef1TelNo","txtRef1TelNo",$aryRest[0][162],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Years Known</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef1YrKn","txtRef1YrKn",$aryRest[0][163],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">&nbsp;</td>
                </tr>
              </table>
              <?php 
for($i=0; $i<count($data_references); $i++)
{
			  ?>
              
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25" align="left" valign="top"><strong>Name</strong></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><input name="reference_name[]" type="text" value="<?php echo $data_references[$i]['reference_name'];?>" /></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Occupation</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><input name="occupation[]" type="text" value="<?php echo $data_references[$i]['occupation'];?>" /></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Address</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><input name="reference_address[]" type="text" value="<?php echo $data_references[$i]['address'];?>" /></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="25">City</td>
                        <td width="10">&nbsp;</td>
                        <td>State</td>
                        <td width="10">&nbsp;</td>
                        <td>Zip Code </td>
                      </tr>
                      <tr>
                        <td height="25"><input name="reference_city[]" type="text" value="<?php echo $data_references[$i]['city'];?>" /></td>
                        <td>&nbsp;</td>
                        <td><input name="reference_state[]" type="text" value="<?php echo $data_references[$i]['state'];?>" /></td>
                        <td>&nbsp;</td>
                        <td><input name="reference_zip[]" type="text" value="<?php echo $data_references[$i]['zip'];?>" /></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Telephone Number</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><input name="reference_telephone[]" type="text" value="<?php echo $data_references[$i]['telephone'];?>" /></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Years Known</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><input name="years_known[]" type="text" value="<?php echo $data_references[$i]['years_known'];?>" />
                  <input name="reference_id[]" type="hidden" value="<?php echo $data_references[$i]['reference_id'];?>" />
                  </td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">&nbsp;</td>
                </tr>
              </table>
              <?php
}
			  ?>
            
            </td>
        </tr>
      </table></td>
  </tr>
</table>
</fieldset>
</div>
</fieldset>

</fieldset>

<fieldset class="sectionwrap">

<legend>Driving Information</legend><br>

<fieldset><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><img src="../../images/trans.gif" alt="spacer" width="750" height="25"></td>
    </tr>
  </table>
<div class="contentB">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
  <fieldset>
  <table width="100%" border="0" cellpadding="0" cellspacing="0" id="phase006">
  <tr>
    <td><strong><br />
      DRIVING INFORMATION<br />
      This information is being collected in the event you are being considered for a position which requires driving as a condition of employment.</strong>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="25" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Do you have a current valid driver's license?</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdLic","rdLic",array(1,0),array("Yes","No"),$aryRest[0][188],0)?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Has your license ever been suspended or revoked?</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdLicSus","rdLicSus",array(1,0,2),array("Yes","No","N/A"),$aryRest[0][189],0)?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">If Yes, please explain:</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSusExp","txtSusExp",$aryRest[0][190],"txtAreaForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Have you had any moving traffic violations within the previous three (3) years?</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdLicVoil","rdLicVoil",array(1,0),array("Yes","No"),$aryRest[0][191],0)?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">If Yes, you must complete this section. Please give dates and details of each:</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">&nbsp;</td>
          </tr>
        </table>
      
            
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="25"><strong>Offense</strong></td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff1Off","txtOff1Off",$aryRest[0][192],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Date</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff1Dt","txtOff1Dt",$aryRest[0][193],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Location</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff1Loc","txtOff1Loc",$aryRest[0][194],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Comments</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff1Com","txtOff1Com",$aryRest[0][195],"txtAreaForm");?></td>
          </tr>
          <tr>
            <td height="25" class="bttmLine">&nbsp;</td>
          </tr>
          <tr>
            <td height="25">&nbsp;</td>
          </tr>
        </table>
        

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <?php
for($i=0; $i<count($data_offences); $i++)
{
?>

          <tr>
            <td height="25"><strong>Offense</strong></td>
          </tr>
          <tr>
            <td height="25"><input name="offense[]" type="text" value="<?php echo $data_offences[$i]['offense'];?>" /></td>
          </tr>
          <tr>
            <td height="25">Date</td>
          </tr>
          <tr>
            <td height="25"><input name="offense_date[]" type="text" value="<?php echo $data_offences[$i]['offense_date'];?>" /></td>
          </tr>
          <tr>
            <td height="25">Location</td>
          </tr>
          <tr>
            <td height="25"><input name="offense_location[]" type="text" value="<?php echo $data_offences[$i]['offense_location'];?>" /></td>
          </tr>
          <tr>
            <td height="25">Comments</td>
          </tr>
          <tr>
            <td height="25"><textarea name="offense_comments[]"  cols="42" rows="5"> <?php echo $data_offences[$i]['offense_comments'];?></textarea>
            <input name="offense_id[]" type="hidden" value="<?php echo $data_offences[$i]['offense_id'];?>" /></td>
          </tr>
          <?php
}
		  ?>
          <tr>
            <td height="25">Do you have personal automobile insurance?</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdAutIns","rdAutIns",array(1,0),array("Yes","No"),$aryRest[0][208],0)?></td>
          </tr>
          <tr>
            <td height="25">If No, please explain:</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtInsNo","txtInsNo",$aryRest[0][209],"txtAreaForm");?></td>
          </tr>
          <tr>
            <td height="25">&nbsp;</td>
          </tr>
        </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><strong>NOTICE TO ALL APPLICANTS: IF YOU WISH TO BE  CONSIDERED FOR EMPLOYMENT OTHER THEN THE POSITION YOU HAVE APPLIED TO, YOU MAY  BE ASKED TO REAPPLY. NOTICE TO MASSACHUSETTS APPLICANTS: IT IS UNLAWFUL IN  MASSACHUSETTS TO REQUIRE OR ADMINISTER LIE DETECTOR TESTS AS A CONDITION OF  EMPLOYMENT OR CONTINUED EMPLOYMENT.&nbsp; ANY EMPLOYER WHO VIOLATES THIS LAW  WILL BE SUBJECT TO CRIMINAL PENALTIES AND CIVIL LIABILITY. WE ARE AN EQUAL  OPPORTUNITY EMPLOYER </strong></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><?php echo $objFrm->submitButton("SubFrm","Submit&nbsp;Form").$objFrm->submitButton("SubCancel","Cancel");?>
            </td><td align="left"><div id="results"></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
      </table></td>
  </tr>
</table>
  </fieldset>
</div>
</fieldset>

</fieldset>

</form></td>
                    </tr>
                  </table></td>
                </tr>
              </table>
          </center>
<?php 
require('../../trailer.php');
?>