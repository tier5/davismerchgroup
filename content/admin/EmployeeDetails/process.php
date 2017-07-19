<?php
echo "hai";

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
       
 $v5=array('rdCmtToOtr','rdCmtToOtr','rdAskToRn','txtReasonRn',
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
       
if($_POST['subNext']=="Next")
{	
	$aryValue1=array("'".$_POST['txtJob']."'",$_POST['txtSalary'],$_POST['cmdReff'],
	 		"'".$_POST['cmdStDtday']."/". $_POST['cmdStDtmonth']."/". $_POST['cmdStDtyear']."'",
			"'".$_POST['txtSSN1'].$_POST['txtSSN2'].$_POST['txtSSN2']."'",
			"'".$_POST['txtLegName']."'","'".$_POST['txtFrstName']."'",
			"'".$_POST['txtSrName']."'","'".$_POST['txtMdlName']."'","'".$_POST['txtNickName']."'");
	
	
	$success=$objDB-> updateData($connection,"dtbl_jobapplicant",$aryCol1,$aryValue1);
	
	$aryValue2=$objFrm->ChangeToPost($v2);
	$success2=$objDB-> insertData($connection,"dtbl_jobapplicantaddress",$aryCol2,$aryValue2);	
			
	$aryValue3=$objFrm->ChangeToPost($v3);
	$success=$objDB-> insertData($connection,"dtbl_jobapplicanteducation",$aryCol3,$aryValue3);
	
	$aryValue4=$objFrm->ChangeToPost($v4);
	$success=$objDB-> insertData($connection,"dtbl_jobapplicantemployer",$aryCol4,$aryValue4);
	
	$aryValue5=$objFrm->ChangeToPost($v5);
	$success=$objDB-> insertData($connection,"dtbl_jobapplicantemp_hist",$aryCol5,$aryValue5);
	
	$aryValue6=$objFrm->ChangeToPost($v6);
	$success=$objDB->insertData($connection,"dtbl_jobapplicantdri_det",$aryCol6,$aryValue6);
}
?>