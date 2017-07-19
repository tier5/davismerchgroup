<?php
require_once('Application.php');

if(isset($_GET['iid']))
{
	$aryRest= $objDB->getResultMulti($connection,"
DELETE FROM dtbl_jobapplicant
 WHERE \"appID\" =". $_GET['iid'].";");
	$objDB->getResultMulti($connection,"
DELETE FROM dtbl_jobapplicantaddress
 WHERE \"appID\" =". $_GET['iid'].";");
	$objDB->getResultMulti($connection,"
DELETE FROM dtbl_jobapplicantdri_det
 WHERE \"appID\" =". $_GET['iid'].";");
	$objDB->getResultMulti($connection,"
DELETE FROM dtbl_jobapplicanteducation
 WHERE \"appID\" =". $_GET['iid'].";");
	$objDB->getResultMulti($connection,"
DELETE FROM dtbl_jobapplicantemp_hist
 WHERE \"appID\" =". $_GET['iid'].";");
	$objDB->getResultMulti($connection,"
DELETE FROM dtbl_jobapplicantemployer
 WHERE \"appID\" =". $_GET['iid'].";");
	$objDB->getResultMulti($connection,"
DELETE FROM dtbl_applicant_previous_address
 WHERE \"appID\" =". $_GET['iid'].";");
	$objDB->getResultMulti($connection,"
DELETE FROM dtbl_jobapplicant_education
 WHERE \"appID\" =". $_GET['iid'].";");
	$objDB->getResultMulti($connection,"
DELETE FROM dtbl_jobapplicant_offences
 WHERE \"appID\" =". $_GET['iid'].";");
	$objDB->getResultMulti($connection,"
DELETE FROM dtbl_jobapplicant_references
 WHERE \"appID\" =". $_GET['iid'].";");
	$objDB->getResultMulti($connection,"
DELETE FROM dtbl_previous_employer
 WHERE \"appID\" =". $_GET['iid'].";");
	
	
}
header('Location: listApplication.php');