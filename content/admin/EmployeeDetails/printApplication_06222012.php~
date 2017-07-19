<?php 
require('Application.php');

$page = "printtApplication.php";
$app = 0;
if(isset($_GET['iid']))
{
	$app=$_GET['iid'];
}

?>
<?php 
$aryCol1=array(job_req, salary, "refferedid", "joiningDate", 
       "UID", "nameLast", "nameSuffix", "nameFirst", "nameMiddle", "nickName");

$v2=array('txtCurAdd','txtCurCity','txtCurState','txtCurZip','txtCurYrsLived',
'txtPre1Add','txtPre1City','txtPre1State','txtPre1Zip','txtPre1YrsLived',
'txtPre2Add','txtPre2City','txtPre2State','txtPre2Zip','txtPre2YrsLived',
'txtPre3Add','txtPre3City','txtPre3State','txtPre3Zip','txtPre3YrsLived',
'txtTelNo','txtEmail','rdAgeLmt','rdLegAut','rdWorDMG','txtPosition',
'rdFrndDMG','txtNameFrnd','rdTransWork','rdCrime','txtCrime');
$aryCol2=array(curr_address, "curr_City", "curr_State", "cur_zipCode", 
       "cur_YrLived", pre_address1, "pre_City1", "pre_State1", "pre_zipCode1", 
       "pre_YrLived1", pre_address2, "pre_City2", "pre_State2", "pre_zipCode2", 
       "pre_YrLived2", pre_address3, "pre_City3", "pre_State3", "pre_zipCode3", 
       "pre_YrLived3", tel_no, emailid, age_lmt, leg_aut, "wokd_for_DMG", 
       wrk_det, "frnd_in_DMG", frnd_name, trnspot_req, crime_chrgd, 
       crime_det);
       
 $v3=array('txtSch1Name','txtSch1City','txtSch1State','txtSch1Zip','txtSch1Country','rdSch1Grd','txtSch1Frm','txtSch1To','txtSch1GrdDt',
'txtSch1Title','txtSch1Main','txtSch1Extras','txtSch2Name','txtSch2City','txtSch2State','txtSch2Zip','txtSch2Country','rdSch2Grd',
'txtSch2Frm','txtSch2To','txtSch2GrdDt','txtSch2Title','txtSch2Main','txtSch2Extras','txtSch3Name','txtSch3City','txtSch3State','txtSch3Zip',
'txtSch3Country','rdSch3Grd','txtSch3Frm','txtSch3To','txtSch3GrdDt','txtSch3Title','txtSch3Main','txtSch3Extras',
'txtSch4Name','txtSch4City','txtSch4State','txtSch4Zip','txtSch4Country','rdSch4Grd','txtSch4Frm','txtSch4To','txtSch4GrdDt',
'txtSch4Title','txtSch4Main','txtSch4Extras','rdIsNameChng','txtNameDt','txtExp','rdIsCap');
$aryCol3=array(school_name1, "s1_City", "s1_State", "s1_zipCode", s1_country, 
       s1_is_graduate, s1_grd_prd_frm, s1_grd_prd_to, s1_grd_dt, s1_grd_title, 
       s1_grd_main, s1_desc_others, school_name2, "s2_City", "s2_State", 
       "s2_zipCode", s2_country, s2_is_graduate, s2_grd_prd_frm, s2_grd_prd_to, 
       s2_grd_dt, s2_grd_title, s2_grd_main, s2_desc_others, school_name3, 
       "s3_City", "s3_State", "s3_zipCode", s3_country, s3_is_graduate, 
       s3_grd_prd_frm, s3_grd_prd_to, s3_grd_dt, s3_grd_title, s3_grd_main, 
       s3_desc_others, school_name4, "s4_City", "s4_State", "s4_zipCode", 
       s4_country, s4_is_graduate, s4_grd_prd_frm, s4_grd_prd_to, s4_grd_dt, 
       s4_grd_title, s4_grd_main, s4_desc_others, is_nam_chgd, name_det, 
       experience_det, is_capable);
       
$v4=array('txtCurEmpName','txtCurEmpAdd','txtCurEmpCity','txtCurEmpState','txtCurEmpZip','txtCurEmpFrom','txtCurEmpTo','cmdCurEmpSSal',
'cmdCurEmpFSal','txtCurEmpTel','txtCurEmpPos','txtCurEmpDut','txtCurEmpSup','txtCurEmpLeave','rdCurEmpWorking','rdCurEmpMayCont',
'txtPre1EmpName','txtPre1EmpAdd','txtPre1EmpCity','txtPre1EmpState','txtPre1EmpZip','txtPre1EmpFrom','txtPre1EmpTo','cmdPre1EmpSSal',
'cmdPre1EmpFSal','txtPre1EmpTel','txtPre1EmpPos','txtPre1EmpDut','txtPre1EmpSup','txtPre1EmpLeave',
'txtPre2EmpName','txtPre2EmpAdd','txtPre2EmpCity','txtPre2EmpState','txtPre2EmpZip','txtPre2EmpFrom',
'txtPre2EmpTo','cmdPre2EmpSSal','cmdPre2EmpFSal','txtPre2EmpTel','txtPre2EmpPos','txtPre2EmpDut',
'txtPre2EmpSup','txtPre2EmpLeave','txtPre3EmpName','txtPre3EmpAdd','txtPre3EmpCity','txtPre3EmpState',
'txtPre3EmpZip','txtPre3EmpFrom','txtPre3EmpTo','cmdPre3EmpSSal','cmdPre3EmpFSal','txtPre3EmpTel',
'txtPre3EmpPos','txtPre3EmpDut','txtPre3EmpSup','txtPre3EmpLeave'
);
//echo count ($v).'<br>';

$aryCol4=array(empr_name, empr_address, empr_city, empr_state, "empr_zipCode", 
       empr_from, empr_to, empr_start_salary, empr_end_salary, empr_tel_no, 
       empr_position, empr_duties_major, empr_name_supvr, empr_reason_leaving, 
       empr_is_currly_working, empr_may_contact, p1_name, p1_empr_address, 
       p1_empr_city, p1_empr_state, p1_empr_zipcode, p1_from, p1_to, 
       p1_empr_start_salary, p1_empr_end_salary, p1_empr_tel_no, p1_position, 
       p1_empr_duties_major, p1_empr_name_supvr, p1_empr_reason_leaving, 
       p2_name, p2_empr_address, p2_empr_city, p2_empr_state, p2_empr_zipcode, 
       p2_from, p2_to, p2_empr_start_salary, p2_empr_end_salary, p2_empr_tel_no, 
       p2_position, p2_empr_duties_major, p2_empr_name_supvr, p2_empr_reason_leaving, 
       p3_name, p3_empr_address, p3_empr_city, p3_empr_state, p3_empr_zipcode, 
       p3_from, p3_to, p3_empr_start_salary, p3_empr_end_salary, p3_empr_tel_no, 
       p3_position, p3_empr_duties_major, p3_empr_name_supvr, p3_empr_reason_leaving);
       
 $v5=array('rdCmtToOtr','txtOtrExpe','rdAskToRn','txtReasonRn',
'txtRef1Name','txtRef1Occ','txtRef1Add','txtRef1City','txtRef1State','txtRef1Zip','txtRef1TelNo',
'txtRef1YrKn','txtRef2Name','txtRef2Occ','txtRef2Add','txtRef2City','txtRef2State','txtRef2Zip',
'txtRef2TelNo','txtRef2YrKn','txtRef3Name','txtRef3Occ','txtRef3Add','txtRef3City','txtRef3State',
'txtRef3Zip','txtRef3TelNo','txtRef3YrKn','txtRef4Name','txtRef4Occ','txtRef4Add','txtRef4City',
'txtRef4State','txtRef4Zip','txtRef4TelNo','txtRef4YrKn');


$aryCol5=array(any_comm, exp_comm, ask_to_resign, exp_reason, ref1_name, 
       ref1_occ, ref1_address, "ref1_City", "ref1_State", "ref1_zipCode", 
       "ref1_Tel_No", "ref1_no_Yrs", ref2_name, ref2_occ, ref2_address, 
       "ref2_City", "ref2_State", "ref2_zipCode", "ref2_Tel_No", "ref2_no_Yrs", 
       ref3_name, ref3_occ, ref3_address, "ref3_City", "ref3_State", 
       "ref3_zipCode", "ref3_Tel_No", "ref3_no_Yrs", ref4_name, ref4_occ, 
       ref4_address, "ref4_City", "ref4_State", "ref4_zipCode", "ref4_Tel_No", 
       "ref4_no_Yrs");
       

$v6=array('rdLic','rdLicSus','txtSusExp','rdLicVoil',
'txtOff1Off','txtOff1Dt','txtOff1Loc','txtOff1Com',
'txtOff2Off','txtOff2Dt','txtOff2Loc','txtOff2Com',
'txtOff3Off','txtOff3Dt','txtOff3Loc','txtOff3Com',
'txtOff4Off','txtOff4Dt','txtOff4Loc','txtOff4Com',
'rdAutIns','txtInsNo'
);
$aryCol6=array(have_dri_licence, ever_susp, exp_susp, have_traf_violations, 
       off1_det, off1_date, off1_location, off1_commts, off2_det, off2_date, 
       off2_location, off2_commts, off3_det, off3_date, off3_location, 
       off3_commts, off4_det, off4_date, off4_location, off4_commts, 
       have_per_aut_ins, ex_no_insurance);
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
	if($success1!=false && $success2!=false && $success3!=false && $success4!=false && $success5!=false && $success6==false)
	{
		echo"<script>alert('successfully submited')</script>";
			
	}
}
?>
<link rel="stylesheet" type="text/css" href="../../formwizard.css" />


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
<legend style="text-align:left;">Basic Information</legend>
  <br />

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
</div>
<legend style="text-align:left;">Address Information<br></legend>
<div class="contentB">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
          <tr>
            <td height="25" align="left" valign="top"><strong>Previous Address</strong></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtPre1Add","txtPreAdd",$aryRest[0][16],"txtFieldForm");?></td>
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
                  <td height="25"><?php echo $objFrm->textTag("txtPre1City","txtPre1City",$aryRest[0][17],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtPre1State","txtPre1State",$aryRest[0][18],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtPre1Zip","txtPre1Zip",$aryRest[0][19],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">How long did you live  there? (Years/Months) </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtPre1YrsLived","txtPre1YrsLived",$aryRest[0][20],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><strong>Previous Address</strong></td>
          </tr>
          
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtPre2Add","txtPre2Add",$aryRest[0][21],"txtFieldForm");?></td>
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
                  <td height="25"><?php echo $objFrm->textTag("txtPre2City","txtPre2City",$aryRest[0][22],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtPre2State","txtPre2State",$aryRest[0][23],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtPre2Zip","txtPre2Zip",$aryRest[0][24],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          
          <tr>
            <td height="25" align="left" valign="top">How long did you live  there? (Years/Months) </td>
          </tr>
           <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtPre2YrsLived","txtPre2YrsLived",$aryRest[0][25],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><strong>Previous Address </strong></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtPre3Add","txtPre3Add",$aryRest[0][26],"txtFieldForm");?></td>
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
                  <td height="25"><?php echo $objFrm->textTag("txtPre3City","txtPre3City",$aryRest[0][27],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtPre3State","txtPre3State",$aryRest[0][28],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtPre3Zip","txtPre3Zip",$aryRest[0][29],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">How long did you live  there? (Years/Months) </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtPre3YrsLived","txtPre3YrsLived",$aryRest[0][30],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">&nbsp;</td>
          </tr>
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
</div>

<legend style="text-align:left;">Education</legend><br>

<div class="contentB">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
                  <td><?php echo $objFrm->textTag("txtSch1Main","txtSch1Main",$aryRest[0][51],"txtFieldForm");?></td>
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
      <br />
        <strong>POST SECONDARY EDUCATION (College, University, Graduate School, etc.)
          List ALL schools you have attended and / or obtained a degree from below.</strong> <br />
      <br />
     <table width="100%" border="0" cellpadding="0" cellspacing="0" id="phase003">
            <tr>
            <td height="25" align="left" valign="top"><strong>School Name:</strong> </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch2Name","txtSch2Name",$aryRest[0][54],"txtFieldForm");?></td>
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
                  <td height="25"><?php echo $objFrm->textTag("txtSch2City","txtSch2City",$aryRest[0][55],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch2State","txtSch2State",$aryRest[0][56],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch2Zip","txtSch2Zip",$aryRest[0][57],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch2Country","txtSch2Country",$aryRest[0][58],"txtFieldForm");?></td>
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
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdSch2Grd","rdSch2Grd",array(1,0,2),array("Yes","No","Currently Enrolled "),$aryRest[0][59],0)?>
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
                  <td height="25"><?php echo $objFrm->textTag("txtSch2Frm","txtSch2Frm",$aryRest[0][60],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch2To","txtSch2To",$aryRest[0][61],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Graduation Date (mm/yyyy) </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch2GrdDt","txtSch2GrdDt",$aryRest[0][62],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">Diploma/Degree Earned<br /></td>
                  <td width="10">&nbsp;</td>
                  <td>Major/Course of Study</td>
                </tr>
                <tr>
                  <td height="25"><?php echo $objFrm->textTag("txtSch2Title","txtSch2Title",$aryRest[0][63],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch2Main","txtSch2Main",$aryRest[0][64],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Describe Specialized Training, Military Experience, Skills, and  Extra-Curricular Activities </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch2Extras","txtSch2Extras",$aryRest[0][65],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
          </tr>
        </table>
      
  
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        
          <tr>
            <td height="25" align="left" valign="top"><strong>School Name:</strong> </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch3Name","txtSch3Name",$aryRest[0][66],"txtFieldForm");?></td>
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
                  <td height="25"><?php echo $objFrm->textTag("txtSch3City","txtSch3City",$aryRest[0][67],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch3State","txtSch3State",$aryRest[0][68],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch3Zip","txtSch3Zip",$aryRest[0][69],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch3Country","txtSch3Country",$aryRest[0][70],"txtFieldForm");?></td>
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
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdSch3Grd","rdSch3Grd",array(1,0,2),array("Yes","No","Currently Enrolled "),$aryRest[0][71],0)?>
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
                  <td height="25"><?php echo $objFrm->textTag("txtSch3Frm","txtSch3Frm",$aryRest[0][72],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch3To","txtSch3To",$aryRest[0][73],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Graduation Date (mm/yyyy) </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch3GrdDt","txtSch3GrdDt",$aryRest[0][74],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">Diploma/Degree Earned<br /></td>
                  <td width="10">&nbsp;</td>
                  <td>Major/Course of Study</td>
                </tr>
                <tr>
                  <td height="25"><?php echo $objFrm->textTag("txtSch3Title","txtSch3Title",$aryRest[0][75],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch3Main","txtSch3Main",$aryRest[0][76],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Describe Specialized Training, Military Experience, Skills, and  Extra-Curricular Activities </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch3Extras","txtSch3Extras",$aryRest[0][77],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
          </tr>
        </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
  
          <tr>
            <td height="25" align="left" valign="top"><strong>School Name: </strong></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch4Name","txtSch4Name",$aryRest[0][78],"txtFieldForm");?></td>
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
                  <td height="25"><?php echo $objFrm->textTag("txtSch4City","txtSch4City",$aryRest[0][79],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch4State","txtSch4State",$aryRest[0][80],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch4Zip","txtSch4Zip",$aryRest[0][81],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch4Country","txtSch4Country",$aryRest[0][82],"txtFieldForm");?></td>
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
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdSch4Grd","rdSch4Grd",array(1,0,2),array("Yes","No","Currently Enrolled "),$aryRest[0][83],0)?>
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
                  <td height="25"><?php echo $objFrm->textTag("txtSch4Frm","txtSch4Frm",$aryRest[0][84],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch4To","txtSch4To",$aryRest[0][85],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Graduation Date (mm/yyyy) </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch4GrdDt","txtSch4GrdDt",$aryRest[0][86],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25">Diploma/Degree Earned<br /></td>
                  <td width="10">&nbsp;</td>
                  <td>Major/Course of Study</td>
                </tr>
                <tr>
                  <td height="25"><?php echo $objFrm->textTag("txtSch4Title","txtSch4Title",$aryRest[0][87],"txtFieldForm");?></td>
                  <td>&nbsp;</td>
                  <td><?php echo $objFrm->textTag("txtSch4Main","txtSch4Main",$aryRest[0][88],"txtFieldForm");?></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top">Describe Specialized Training, Military Experience, Skills, and  Extra-Curricular Activities </td>
          </tr>
          <tr>
            <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtSch4Extras","txtSch4Extras",$aryRest[0][89],"txtFieldForm");?></td>
          </tr>  
      
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
</div>
<legend style="text-align:left;">Experience</legend><br>

<div class="contentB">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="25"><strong>Name of Previous Employer </strong></td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre1EmpName","txtPre1EmpName",$aryRest[0][110],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Address</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre1EmpAdd","txtPre1EmpAdd",$aryRest[0][111],"txtFieldForm");?></td>
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
              <td height="25"><?php echo $objFrm->textTag("txtPre1EmpCity","txtPre1EmpCity",$aryRest[0][112],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtPre1EmpState","txtPre1EmpState",$aryRest[0][113],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtPre1EmpZip","txtPre1EmpZip",$aryRest[0][114],"txtFieldForm");?></td>
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
              <td height="25"><?php echo $objFrm->textTag("txtPre1EmpFrom","txtPre1EmpFrom",$aryRest[0][115],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtPre1EmpTo","txtPre1EmpTo",$aryRest[0][116],"txtFieldForm");?></td>
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
       
        echo $objFrm->comboTag("cmdPre1EmpSSal","cmdPre1EmpSSal",$aryRest[0][117],$ary,'');?></td>
              <td>&nbsp;</td>
              <td height="25"><?php 
       
        echo $objFrm->comboTag("cmdPre1EmpFSal","cmdPre1EmpFSal",$aryRest[0][118],$ary,'');?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td height="25">Telephone:</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre1EmpTel","txtPre1EmpTel",$aryRest[0][119],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Your Title or Position</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre1EmpPos","txtPre1EmpPos",$aryRest[0][120],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Major Job Duties</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre1EmpDut","txtPre1EmpDut",$aryRest[0][121],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Name of Last Supervisor</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre1EmpSup","txtPre1EmpSup",$aryRest[0][122],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Reason for Leaving</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre1EmpLeave","txtPre1EmpLeave",$aryRest[0][123],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25" class="bttmLine">&nbsp;</td>
        </tr>
        <tr>
          <td height="25">&nbsp;</td>
        </tr>
      </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="25"><strong>Name of Previous Employer </strong></td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre2EmpName","txtPre2EmpName",$aryRest[0][124],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Address</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre2EmpAdd","txtPre2EmpAdd",$aryRest[0][125],"txtFieldForm");?></td>
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
              <td height="25"><?php echo $objFrm->textTag("txtPre2EmpCity","txtPre2EmpCity",$aryRest[0][126],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtPre2EmpState","txtPre2EmpState",$aryRest[0][127],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtPre2EmpZip","txtPre2EmpZip",$aryRest[0][128],"txtFieldForm");?></td>
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
              <td height="25"><?php echo $objFrm->textTag("txtPre2EmpFrom","txtPre2EmpFrom",$aryRest[0][129],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtPre2EmpTo","txtPre2EmpTo",$aryRest[0][130],"txtFieldForm");?></td>
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
      
        echo $objFrm->comboTag("cmdPre2EmpSSal","cmdPre2EmpSSal",$aryRest[0][131],$ary,'');?></td>
              <td>&nbsp;</td>
              <td height="25"><?php 
       
        echo $objFrm->comboTag("cmdPre2EmpFSal","cmdPre2EmpFSal",$aryRest[0][132],$ary,'');?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td height="25">Telephone:</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre2EmpTel","txtPre2EmpTel",$aryRest[0][133],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Your Title or Position</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre2EmpPos","txtPre2EmpPos",$aryRest[0][134],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Major Job Duties</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre2EmpDut","txtPre2EmpDut",$aryRest[0][135],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Name of Last Supervisor</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre2EmpSup","txtPre2EmpSup",$aryRest[0][136],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Reason for Leaving</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre2EmpLeave","txtPre2EmpLeave",$aryRest[0][137],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25" class="bttmLine">&nbsp;</td>
        </tr>
        <tr>
          <td height="25">&nbsp;</td>
        </tr>
      </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="25"><strong>Name of Previous Employer </strong></td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre3EmpName","txtPre3EmpName",$aryRest[0][138],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Address</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre3EmpAdd","txtPre3EmpAdd",$aryRest[0][139],"txtFieldForm");?></td>
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
              <td height="25"><?php echo $objFrm->textTag("txtPre3EmpCity","txtPre3EmpCity",$aryRest[0][140],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtPre3EmpState","txtPre3EmpState",$aryRest[0][141],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtPre3EmpZip","txtPre3EmpZip",$aryRest[0][142],"txtFieldForm");?></td>
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
              <td height="25"><?php echo $objFrm->textTag("txtPre3EmpFrom","txtPre3EmpFrom",$aryRest[0][143],"txtFieldForm");?></td>
              <td>&nbsp;</td>
              <td><?php echo $objFrm->textTag("txtPre3EmpTo","txtPr3EmpTo",$aryRest[0][144],"txtFieldForm");?></td>
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
        
        echo $objFrm->comboTag("cmdPre3EmpSSal","cmdPre3EmpSSal",$aryRest[0][145],$ary,'');?></td>
              <td>&nbsp;</td>
              <td height="25"><?php 
        
        echo $objFrm->comboTag("cmdPre3EmpFSal","cmdPre3EmpFSal",$aryRest[0][146],$ary,'');?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td height="25">Telephone:</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre3EmpTel","txtPre3EmpTel",$aryRest[0][147],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Your Title or Position</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre3EmpPos","txtPre3EmpPos",$aryRest[0][148],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Major Job Duties</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre3EmpDut","txtPre3EmpDut",$aryRest[0][149],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Name of Last Supervisor</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre3EmpSup","txtPre3EmpSup",$aryRest[0][150],"txtFieldForm");?></td>
        </tr>
        <tr>
          <td height="25">Reason for Leaving</td>
        </tr>
        <tr>
          <td height="25"><?php echo $objFrm->textTag("txtPre3EmpLeave","txtPre3EmpLeave",$aryRest[0][151],"txtFieldForm");?></td>
        </tr>
       
      </table></td>
  </tr>
</table>
</div>
<legend style="text-align:left;">Employment History</legend><br>
<div class="contentB">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25" align="left" valign="top"><strong>Name</strong></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef2Name","txtRef2Name",$aryRest[0][164],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Occupation</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef2Occ","txtRef2Occ",$aryRest[0][165],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Address</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef2Add","txtRef2Add",$aryRest[0][166],"txtFieldForm");?></td>
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
                        <td height="25"><?php echo $objFrm->textTag("txtRef2City","txtRef2City",$aryRest[0][167],"txtFieldForm");?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $objFrm->textTag("txtRef2State","txtRef2State",$aryRest[0][168],"txtFieldForm");?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $objFrm->textTag("txtRef2Zip","txtRef2Zip",$aryRest[0][169],"txtFieldForm");?></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Telephone Number</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef2TelNo","txtRef2TelNo",$aryRest[0][170],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Years Known</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef2YrKn","txtRef2YrKn",$aryRest[0][171],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">&nbsp;</td>
                </tr>
              </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25" align="left" valign="top"><strong>Name</strong></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef3Name","txtRef3Name",$aryRest[0][172],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Occupation</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef3Occ","txtRef3Occ",$aryRest[0][173],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Address</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef3Add","txtRef3Add",$aryRest[0][174],"txtFieldForm");?></td>
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
                        <td height="25"><?php echo $objFrm->textTag("txtRef3City","txtRef3City",$aryRest[0][175],"txtFieldForm");?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $objFrm->textTag("txtRef3State","txtRef3State",$aryRest[0][176],"txtFieldForm");?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $objFrm->textTag("txtRef3Zip","txtRef3Zip",$aryRest[0][177],"txtFieldForm");?></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Telephone Number</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef3TelNo","txtRef3TelNo",$aryRest[0][178],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Years Known</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef3YrKn","txtRef3YrKn",$aryRest[0][179],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top" class="bttmLine">&nbsp;</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">&nbsp;</td>
                </tr>
              </table>
              
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td height="25" align="left" valign="top"><strong>Name</strong></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef4Name","txtRef4Name",$aryRest[0][180],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Occupation</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef4Occ","txtRef4Occ",$aryRest[0][181],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Address</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef4Add","txtRef4Add",$aryRest[0][182],"txtFieldForm");?></td>
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
                        <td height="25"><?php echo $objFrm->textTag("txtRef4City","txtRef4City",$aryRest[0][183],"txtFieldForm");?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $objFrm->textTag("txtRef4State","txtRef4State",$aryRest[0][184],"txtFieldForm");?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $objFrm->textTag("txtRef4Zip","txtRef4Zip",$aryRest[0][185],"txtFieldForm");?></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Telephone Number</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef4TelNo","txtRef4TelNo",$aryRest[0][186],"txtFieldForm");?></td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top">Years Known</td>
                </tr>
                <tr>
                  <td height="25" align="left" valign="top"><?php echo $objFrm->textTag("txtRef4YrKn","txtRef4YrKn",$aryRest[0][187],"txtFieldForm");?></td>
                </tr>
             
              </table>
            </td>
        </tr>
      </table></td>
  </tr>
</table>
</div>

<legend style="text-align:left;">Driving Information</legend><br>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
            <td height="25" align="left" valign="top"><?php echo $objFrm->radioTag("rdLicSus","rdLic",array(1,0,2),array("Yes","No","N/A"),$aryRest[0][189],0)?></td>
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
          <tr>
            <td height="25"><strong>Offense</strong></td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff2Off","txtOff2Off",$aryRest[0][196],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Date</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff2Dt","txtOff2Dt",$aryRest[0][197],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Location</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff2Loc","txtOff2Loc",$aryRest[0][198],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Comments</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff2Com","txtOff2Com",$aryRest[0][199],"txtAreaForm");?></td>
          </tr>
          <tr>
            <td height="25" class="bttmLine">&nbsp;</td>
          </tr>
          <tr>
            <td height="25">&nbsp;</td>
          </tr>
        </table>
           <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="25"><strong>Offense</strong></td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff3Off","txtOff3Off",$aryRest[0][200],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Date</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff3Dt","txtOff3Dt",$aryRest[0][201],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Location</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff3Loc","txtOff3Loc",$aryRest[0][202],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Comments</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff3Com","txtOff3Com",$aryRest[0][203],"txtAreaForm");?></td>
          </tr>
          <tr>
            <td height="25" class="bttmLine">&nbsp;</td>
          </tr>
          <tr>
            <td height="25">&nbsp;</td>
          </tr>
        </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="25"><strong>Offense</strong></td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff4Off","txtOff4Off",$aryRest[0][204],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Date</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff4Dt","txtOff4Dt",$aryRest[0][205],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Location</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff4Loc","txtOff4Loc",$aryRest[0][206],"txtFieldForm");?></td>
          </tr>
          <tr>
            <td height="25">Comments</td>
          </tr>
          <tr>
            <td height="25"><?php echo $objFrm->textTag("txtOff4Com","txtOff4Com",$aryRest[0][207],"txtAreaForm");?></td>
          </tr>
          
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
            <td align="left"><div id="results"></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
      </table></td>
  </tr>
</table>
</div>
</td>
                    </tr>
                  </table></td>
                </tr>
              </table>
          </center>


