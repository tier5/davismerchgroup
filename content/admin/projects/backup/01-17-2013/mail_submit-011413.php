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
$sent_to=array();

    if (isset($report) && $report == 1) {
        
        $sql = 'SELECT m1.email,m1.firstname,m1.lastname,prj.prj_name, prj.status from projects as prj left join prj_merchants as merch on merch.pid=prj.pid'.
        ' left join "employeeDB" as m1 on m1."employeeID"=merch.merch'        ;
        $sql_add="";
        
        for($i=0;$i<count($email_pid);$i++)
        {
        if($sql_add=='')
           $sql_add.=' prj.pid='.$email_pid[$i];  
       else 
           $sql_add.=' or prj.pid='.$email_pid[$i]; 
       
        }
       // print_r($email_pid);
        if($sql_add!="")
         $sql.=' where '.$sql_add;  
        
       // echo $sql;
         if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
        $count = 0;
        while ($row = pg_fetch_array($result)) {
            $data_prj[] = $row;
            $sent_to[$count]['email']=$row['email'];
            $sent_to[$count++]['name']=$row['lastname'].' '.$row['firstname'];
        }
        pg_free_result($result);   
     
        $prj_type = 'Closed ';
        if($data_prj[0]['status'] > 0)  $prj_type = 'Open ';
        
        $subject = 'Davis Merchandising : '.$prj_type.'Job Reports - '.date('m/d/Y');
        
        //$subject = "An update for {$client} {$data_prj['projectname']} has been made.";
       if(isset($data_prj[0]) && $data_prj[0]['status']==1)
           $email_body = '<h2>Open Projects</h2>';
       else {
           $email_body = '<h2>Closed Projects</h2>';
       }
        $email_body .= "<p>Project Name<br/>" . $data_prj[0]['prjname'] . "</p>";     
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
  $sql = "SELECT  prj.*,merch.location,merch.store_num,str2.sto_num as str2_sto_num ,chain.chain,merch.m_id,str.sto_name,str.sto_num,merch.store_num,merch.st_time,merch.cid,merch.merch,merch.notes, merch.address, merch.phone, merch.city, merch.zip, merch.due_date,prj.prj_name as prjname, c.client, c.\"clientID\", m1.firstname as m1first, m1.lastname as m1last ";
        $sql .= " ,m1.email FROM projects as prj left join prj_merchants as merch on merch.pid=prj.pid  inner join \"clientDB\" as c on c.\"ID\" = merch.cid";
        $sql .= " left join tbl_store as str on str.sid=merch.location";
          $sql .= " left join tbl_store as str2 on str2.sid=merch.store_num ";
		$sql.=" left join tbl_chain as chain on chain.ch_id=merch.location ";
        $sql .= " left join \"employeeDB\" as m1 on m1.\"employeeID\"=merch.merch  where prj.pid=" . $pid." order by merch.m_id desc";

        //echo $sql;

        if (!($result = pg_query($connection, $sql))) {
            print("Failed query1: " . pg_last_error($connection));
            exit;
        }
        $count = 0;
        while ($row = pg_fetch_array($result)) {
            $data_prj[] = $row;
            $sent_to[$count]['email']=$row['email'];
            $sent_to[$count++]['name']=$row['m1last'].' '.$row['m1first'];
        }
        pg_free_result($result);
        
        
   
     $query  = "SELECT * FROM prj_uploads WHERE pid=$pid AND file_type='I' ORDER BY upid ASC;";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed Image query: " . pg_last_error($connection));
        exit;
    }
    unset($images);
    while ($row = pg_fetch_array($result))
    {
        $images[] = $row;
    }
    pg_free_result($result);

    $query  = "SELECT * FROM prj_uploads WHERE pid=$pid AND file_type='F' ORDER BY upid ASC;";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed File query: " . pg_last_error($connection));
        exit;
    }
     unset($files);
    while ($row = pg_fetch_array($result))
    {
        $files[] = $row;
    }
    pg_free_result($result);       
        
        
    
    
   $html = '<table  border="0" cellspacing="0" cellpadding="0"  width="95%" >';
if (isset($images) && isset($images[0]))
{
$html .=
    "<tr>".
        "<td>".
            "<strong>Images:</strong>".
        "</td>".
    "</tr>";
$html .= "<tr>".
            "<td>";
    for ($i = 0; $i < count($images); $i++)
    {
        
$html.='<a href="'.$base_url.'content/upload_files/images/'.rawurlencode($images[$i]['file_name']).'">';        
$html .='<img  width="101" src="'.$base_url.'content/upload_files/images/'.rawurlencode($images[$i]['file_name']).'" height="89" style="float:left;padding:5px"  alt="'.$images[$i]['disp_name'].'"  />';
 $html.='</a>';               
       
    }
       $html .= '</td>'.
       ' </tr>';
}
if (isset($files) && isset($files[0]))
{
   $html .= '<tr>'.
        '<td>'.
            '<strong>Files:</strong>'.
        '</td>'.
    '</tr>';
    $html .= '<tr>'.
            '<td>';
    for ($i = 0; $i < count($files); $i++)
    {
      
               $html .='<a href="'.$base_url.'content/admin/projects/download.php?upid='.$files[$i]['upid'].'" title="Download '.$files[$i]['disp_name'].'"><strong>'.$files[$i]['disp_name'].'</strong></a>';
              
        
    }
 $html .='</td>'.
        '</tr>';    
}
$html .= '</table>'; 
        
        $prj_type = 'Closed ';
        if($data_prj[0]['status'] > 0)  $prj_type = 'Open ';
        $subject = 'Davis Merchandising : '.$prj_type .'Job: -'.$data_prj[0]['prjname']. ' - '.date('m/d/Y');
        //echo "cnt".count($data_prj);
      
        //$subject = "An update for {$client} {$data_prj['projectname']} has been made.";
        $email_body = "<p>You have recieved a new Job message from Davis merchandising Group.</p>";
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

   if ((isset($data_prj[$i]['m1last']) && $data_prj[$i]['m1last'] != "")||(isset($data_prj[$i]['m1first']) && $data_prj[$i]['m1first'] != ""))
                $email_body .="<p><strong>Project Manager:</strong>" . $data_prj[$i]['m1first'] . " " . $data_prj[$i]['m1last'] . " </p>";
           

            $email_body .="	  <p><strong>Due Date: </strong> " . date('m/d/Y', $data_prj[$i]['due_date']) . " </p>";
            if (isset($data_prj[$i]['st_time']) && $data_prj[$i]['st_time'] != "")
                $email_body .="	  <p><strong>Start Time: </strong> " . $data_prj[$i]['st_time'] . " </p>";
            
             if (isset($data_prj[$i]['chain']) && $data_prj[$i]['chain'] != "")
                $email_body .="	  <p><strong>Chain: </strong> " . $data_prj[$i]['chain'] . " </p>";
              if (isset($data_prj[$i]['str2_sto_num']) && $data_prj[$i]['str2_sto_num'] != "")
                
                $email_body .= "<p><strong>store#:</strong> " . $data_prj[$i]['str2_sto_num'] . " </p>";
              
               if (isset($data_prj[$i]['address']) && $data_prj[$i]['address'] != "")
                
                $email_body .= "<p><strong>Address:</strong> " . $data_prj[$i]['address'] . " </p>";
               
                    if (isset($data_prj[$i]['phone']) && $data_prj[$i]['phone'] != "")
                
                $email_body .= "<p><strong>Phone:</strong> " . $data_prj[$i]['phone'] . " </p>";
                     if (isset($data_prj[$i]['city']) && $data_prj[$i]['city'] != "")
                
                $email_body .= "<p><strong>City:</strong> " . $data_prj[$i]['city'] . " </p>";
                         if (isset($data_prj[$i]['zip']) && $data_prj[$i]['zip'] != "")
                
                $email_body .= "<p><strong>Zip:</strong> " . $data_prj[$i]['zip'] . " </p>";
         

            if (isset($data_prj[$i]['notes']) && $data_prj[$i]['notes'] != "")
                $email_body .= "<p><strong>Notes:</strong> " . $data_prj[$i]['notes'] . " </p>";
        }
        
        $email_body.=$html;
        $email_body .= "<p><strong>Please login to internal.davismerchgroup.com/content/admin/projects/  to review details of all open projects.
            </strong></p>";
       
    }    
    if($email_body != '')
    {
          $headers = "From: Davis Merchandising" . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	require($PHPLIBDIR . 'mailfunctions.php');
      //print_r($sent_to);
   for($i=0;$i<count($sent_to);$i++)
   {
    // echo $sent_to[$i]['email']."<br/>";
      // echo $email_body."<br/>";
      // $email_body= htmlentities($email_body);
         
        //if($is_mail ==1 && $clientEmail !="") {
       $headers = create_smtp_headers($subject, "admin@davismerchgroup.com", $sent_to[$i]['email'], "Davis Merchandising", $sent_to[$i]['name'], "text/html");
        $data = $headers . "<html><BODY>" . $email_body . "</body></html>";
        //echo $data."<br/>";
       if ((send_smtp($mailServerAddress, "admin@davismerchgroup.com", $sent_to[$i]['email'], $data)) == false) {
            $return_arr['error'] = "Unable to send email. Please try again later";
            echo json_encode($return_arr);
            return;
        }
    }
    }


echo json_encode($return_arr);
return;
?>