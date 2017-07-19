<?php

require('Application.php');
$error = "";
$msg = "";
$mail = 0;
$return_arr = array();
extract($_POST);
$return_arr['error'] = "";
$return_arr['msg'] = "";
$return_arr['email'] = "";
$pid = 0;

if (isset($_POST['email']) && $_POST['email'] != "") {
    $sent_to = $email;
    if (isset($report) && $report == 1) {
        $sql = 'SELECT  prj_name, status from projects';
        $sql_add="";
        
        for($i=0;$i<count($email_pid);$i++)
        {
        if($sql_add=='')
           $sql_add.=' pid='.$email_pid[$i];  
       else 
           $sql_add.=' or pid='.$email_pid[$i]; 
       
        }
       // print_r($email_pid);
        if($sql_add!="")
         $sql.=' where '.$sql_add;  
         if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
        while ($row = pg_fetch_array($result)) {
            $data_prj[] = $row;
        }
        pg_free_result($result);   
     
     $headers = "From: Davis Merchandising" . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        //$subject = "An update for {$client} {$data_prj['projectname']} has been made.";
       if(isset($data_prj[0]) && $data_prj[0]['status']==1)
           $email_body = '<h2>Open Projects</h2>';
       else {
           $email_body = '<h2>Closed Projects</h2>';
       }
        $email_body .= "<p>Project Details...<br/>" . $data_prj[0]['prjname'] . "</p>";     
        for ($i = 0; $i < count($data_prj); $i++) {
        $email_body.=($i+1).". ".$data_prj[$i]['prj_name'] .'<br/>';  
        }
        
       $email_body .= "<p><strong>Please login to internal.davismerchgroup.com/content/admin/projects/  to review details of all open projects.
            </strong></p>";
        
        
      // echo $email_body; 
    } else {
    $pid = $email_pid;
        /* $sql = "SELECT prj.*, c.client, c.\"clientID\", m1.firstname as m1first, m1.lastname as m1last, m2.firstname as m2first, m2.lastname as m2last,  m3.firstname as m3first, m3.lastname as m3last ";
          $sql .= " FROM projects as prj inner join \"clientDB\" as c on c.\"ID\" = prj.cid";

          $sql .= " left join \"employeeDB\" as m1 on m1.\"employeeID\"=prj.merch_1 left join \"employeeDB\" as m2 on m2.\"employeeID\"=prj.merch_2 left join \"employeeDB\" as m3 on m3.\"employeeID\"=prj.merch_3 ";
          $sql .= "$where"; */
        $sql = "SELECT str.sto_name,merch.location,merch.st_time,merch.cid,merch.merch,merch.notes,merch.due_date,prj.prj_name as prjname, c.client, c.\"clientID\", m1.firstname as m1first, m1.lastname as m1last ";
        $sql .= " FROM projects as prj left join prj_merchants as merch on merch.pid=prj.pid  inner join \"clientDB\" as c on c.\"ID\" = merch.cid";
        $sql .= " left join tbl_store as str on str.pid=merch.location";
        $sql .= " left join \"employeeDB\" as m1 on m1.\"employeeID\"=merch.merch  where prj.pid=" . $pid;

        //echo $sql;

        if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
        while ($row = pg_fetch_array($result)) {
            $data_prj[] = $row;
        }
        pg_free_result($result);

        //echo "cnt".count($data_prj);
        $headers = "From: Davis Merchandising" . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        //$subject = "An update for {$client} {$data_prj['projectname']} has been made.";
        $email_body = "<p>You have recieved a new message from the enquiries form on your website.</p>";
        $email_body .= "<p><strong>Project Name : </strong>" . $data_prj[0]['prjname'] . "</p>";

//						
//						  <p><strong>Project/PO: </strong>";
//						  if($data_prjPurchase['purchaseorder']!="")
//						  {
//							  $email_body .=$data_prjPurchase['purchaseorder'];
//						  }
//						  else
//						  {
//							  $email_body .=$data_prj['projectname'];
//						  }
        for ($i = 0; $i < count($data_prj); $i++) {
            $email_body .= "<br/><br/><strong><u>Merchandiser " . ($i + 1) . "</u></strong></p> 
                               <p><strong>Client Name: </strong> " . $data_prj[$i]['client'] . " </p>";


            if (isset($data_prj[$i]['sto_name']) && $data_prj[$i]['sto_name'] != "")
                $email_body .= "<p><strong>Location:</strong> " . $data_prj[$i]['sto_name'] . " </p>";

            $email_body .="	  <p><strong>Due Date: </strong> " . date('m/d/Y', $data_prj[$i]['due_date']) . " </p>";
            if (isset($data_prj[$i]['st_time']) && $data_prj[$i]['st_time'] != "")
                $email_body .="	  <p><strong>Start Time: </strong> " . $data_prj[$i]['st_time'] . " </p>";
            if (isset($data_prj[$i]['m1first']) && $data_prj[$i]['m1first'] != "")
                $email_body .="<p><strong>Project Manager:</strong>" . $data_prj[$i]['m1first'] . " " . $data_prj[$i]['m1last'] . " </p>";

            if (isset($data_prj[$i]['notes']) && $data_prj[$i]['notes'] != "")
                $email_body .= "<p><strong>Notes:</strong> " . $data_prj[$i]['notes'] . " </p>";
        }
        $email_body .= "<p><strong>Please login to internal.davismerchgroup.com/content/admin/projects/  to review details of all open projects.
            </strong></p>";
       
    }
    if($email_body != '')
    {
         require($PHPLIBDIR . 'mailfunctions.php');
        //if($is_mail ==1 && $clientEmail !="") {
        $headers = create_smtp_headers($subject, "admin@davismerchgroup.com", $sent_to, $subject, "", "text/html");
        $data = $headers . "<html><BODY>" . $email_body . "</body></html>";
        if ((send_smtp($mailServerAddress, "admin@davismerchgroup.com", $sent_to, $data)) == false) {
            $return_arr['error'] = "Unable to send email. Please try again later";
            echo json_encode($return_arr);
            return;
        }
    }
} else {
    $return_arr['error'] = "Email field appears to be empty";
    echo json_encode($return_arr);
    return;
}

echo json_encode($return_arr);
return;
?>