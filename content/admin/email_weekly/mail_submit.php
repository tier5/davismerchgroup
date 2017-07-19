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
$where="";
 //$join_type='left join';
//$where = " where prj.status =1";  //$client_sql";
if ($query) $where .= " AND $qtype LIKE '%".  pg_escape_string($query)."%' ";
$cl_flag=0;

if(isset($_REQUEST['date_from'])&&$_REQUEST['date_from']!="")
{

     $date_arr = explode('/',$_REQUEST['date_from']);
    $from_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);

 $where.=' AND m."due_date">='.$from_date;   
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['date_to'])&&$_REQUEST['date_to']!="")
{
      $date_arr = explode('/',$_REQUEST['date_to']);
    $to_date = strtotime($date_arr[2].'-'.$date_arr[0].'-'.$date_arr[1]);
 $where.=' AND m."due_date"<='.$to_date;  
 $join_type='inner join';
 $cl_flag=1;
}


if(isset($_REQUEST['project'])&&$_REQUEST['project']!="")
{
      
 $where.=" and main.m_pid =".$_REQUEST['project']."";   
 //$join_type='inner join';
 $cl_flag=1;
}
if(isset($_REQUEST['jobid_num'])&&$_REQUEST['jobid_num']!="")
{

 $where.=" and  prj.prj_name ilike '%".$_REQUEST['jobid_num']."%'";   
 //$join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['st_name'])&&$_REQUEST['st_name']!="")
{
      
 $where.=" AND chain.ch_id =".$_REQUEST['st_name']."";   
 $join_type='inner join';
 $cl_flag=1;
}



if(isset($_REQUEST['sto_num'])&&$_REQUEST['sto_num']!="")
{
      
 $where.=" AND st.sto_num like '%".$_REQUEST['sto_num']."%' ";  
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['merch'])&&$_REQUEST['merch']!="")
{
      
 $where.=" AND m.merch =".$_REQUEST['merch']."";  
 $join_type='inner join';
 $cl_flag=1;
}

if(isset($_REQUEST['client'])&&$_REQUEST['client']!="")
{
      
 $where.=" AND m.cid =".$_REQUEST['client']."";  
 $join_type='inner join';
 $cl_flag=1;
}
//echo '--H'.$where.'--H';
$where = ' where  prj.status =1 and m.region=(select region from "employeeDB" where "employeeID"='.$_SESSION['employeeID'].') '.$where;

    if(isset($data_prj))
        unset($data_prj);
     if(isset($prev))
        unset($prev);
    $data_prj=array();
//echo $k.'<br/>';
$sql ='SELECT distinct ( prj.prj_name ),main.name, prj.pid,clnt.client, chain.chain,m1.firstname, m1.lastname, reg_name.region, st.sto_num, m.st_time,m.due_date 
,m.city,m.notes,m1.email,m.notes,m.phone,m.zip,m.address from projects  as prj  
 left join prj_merchants_new as m on m.pid =prj.pid 
left join "employeeDB" as m1 on m1."employeeID"= m.merch left join tbl_chainmanagement as st on st.chain_id = m.store_num  '.
   ' left join project_main as main on main.m_pid=prj.m_pid '.
         ' left join "clientDB" as clnt on clnt."ID"=m.cid   left join tbl_region as reg_name on reg_name.rid=m.region  '.
 '  left join tbl_chain as chain on chain.ch_id=m.location  left join tbl_region as reg on reg.rid=m1.region '.$where ;
 //echo $sql;
if (!($result = pg_query($connection, $sql)))
    {
        $ret['error'] = "Failed to add / edit project: " . pg_last_error($connection);
        echo json_encode($ret);
        return;
    }
 
$i=-1;  
while($out = pg_fetch_array($result)){
 if(!isset($prev)||($out['pid']!=$prev))
 {
     $i+=1;
 $data_prj[$i]=array();

 }
 //print_r($out).'<br/>';
    $data_prj[$i][] = $out;
    $prev=$out['pid'];

}
$num_row=pg_num_rows($result);
//echo 'num-'.$num_row;
pg_free_result($result);

   




//echo $html;
        
        $prj_type = '';
       
        
        //echo "cnt".count($data_prj);
      
 // print_r($data_prj);
        for ($i = 0; $i < count($data_prj); $i++) {
             for ($j = 0; $j < count($data_prj[$i]); $j++) {
       $subject = 'Davis Merchandising : '.$prj_type .'Job: '.$data_prj[$i][$j]['prj_name']. ' - '.date('m/d/Y', $data_prj[$i][$j]['due_date']);     
         //  $email_body[$i][$j]=array('cnt','email');
            $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']. "<p>You have recieved a new Job message from Davis merchandising Group.</p>";
            
    if ((isset($data_prj[$i][$j]['firstname']) && $data_prj[$i][$j]['firstname'] != "")||(isset($data_prj[$i][$j]['firstname']) && $data_prj[$i][$j]['lastname'] != ""))
    $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']."<p><strong>.&nbsp;</strong><font size='4'>Merchandiser Name: </font>" . $data_prj[$i][$j]['firstname'] . " " . $data_prj[$i][$j]['lastname'] . " </p>";            
 
      $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']. " 
                              <p><strong>.&nbsp;Client Name: </strong> " . $data_prj[$i][$j]['client'] . " </p>";
  $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']. "<p><strong>.&nbsp;Project: </strong>" . $data_prj[$i][$j]['name'] . "</p>";     
 $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']. "<p><strong>.&nbsp;Job ID#: </strong>" . $data_prj[$i][$j]['prj_name'] . "</p>";
 
 $email_body[$i][$j]['cnt'] .="<p><strong>.&nbsp;Scheduled Job Date: </strong> " . date('l, d- M', $data_prj[$i][$j]['due_date']) . " </p>";
 
 if (isset($data_prj[$i][$j]['st_time']) && $data_prj[$i][$j]['st_time'] != "")
                $email_body[$i][$j]['cnt']=$email_body[$i][$j]['cnt']."	  <p><strong>.&nbsp;Start Time: </strong> " . $data_prj[$i][$j]['st_time'] . " </p>";
 
  if (isset($data_prj[$i][$j]['chain']) && $data_prj[$i][$j]['chain'] != ""){}
                $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']."<p><strong>.&nbsp;Store Name: </strong> " . $data_prj[$i][$j]['chain'] . " </p>";
                
   if (isset($data_prj[$i][$j]['sto_num']) && $data_prj[$i][$j]['sto_num'] != "")
                
                $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']. "<p><strong>.&nbsp;Store Number:</strong> " . $data_prj[$i][$j]['sto_num'] . " </p>";
   
   if (isset($data_prj[$i][$j]['address']) && $data_prj[$i][$j]['address'] != "")
                
                $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']. "<p><strong>.&nbsp;Store Address:</strong> " . $data_prj[$i][$j]['address'] . " </p>";
   
    if (isset($data_prj[$i][$j]['city']) && $data_prj[$i][$j]['city'] != "")
                
                $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']. "<p><strong>.&nbsp;City:</strong> " . $data_prj[$i][$j]['city'] . " </p>";
        if (isset($data_prj[$i][$j]['zip']) && $data_prj[$i][$j]['zip'] != "")
                
                $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']. "<p><strong>.&nbsp;Zip:</strong> " . $data_prj[$i][$j]['zip'] . " </p>";
        if (isset($data_prj[$i][$j]['phone']) && $data_prj[$i][$j]['phone'] != "")
                
                $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']. "<p><strong>.&nbsp;Phone:</strong> " . $data_prj[$i][$j]['phone'] . " </p>";     

            if (isset($data_prj[$i][$j]['notes']) && $data_prj[$i][$j]['notes'] != "")
                $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']. "<p><strong>.&nbsp;Job Notes:</strong> " . $data_prj[$i][$j]['notes'] . " </p>";
         //    $email_body[$i][$j]['cnt'] =$email_body[$i][$j]['cnt']. "<p><strong>.&nbsp;Pizza Blizt (insert summary project notes here):</strong>&nbsp;&nbsp;<hr><hr></p>";
        $email_body[$i][$j]['cnt']=$email_body[$i][$j]['cnt'].$html;
        $email_body[$i][$j]['cnt']=$email_body[$i][$j]['cnt']."<p><strong>.&nbsp;Please log onto The intranet to review this open project, view images of the set, and download sign off sheets. You can view your complete schedule from your homepage on the dashboard. Remember, if you need support, call the office.
            </strong></p>"; 
                
            
        $email_body[$i][$j]['email']=$data_prj[$i][$j]['email'];
        //print_r($email_body[0]);
       // print_r($email_body[1]);
       // echo '<br/>------------------------------<br/>';
        }
        }
        
//print_r($email_body);
        //echo 'mail--'.$email_body[0][1]['cnt'].'--';
       
       
    if($email_body[0][0]['cnt'] != '')
    {
          $headers = "From: Davis Merchandising" . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        if(trim($mailServerAddress) == '')
            $mailServerAddress = 'localhost';
	require($PHPLIBDIR . 'mailfunctions.php');
      //print_r($sent_to);
       // print_r($email_body[0][0]);
   for($i=0;$i<count($email_body);$i++)
   {
    for($j=0;$j<count($email_body[$i]);$j++)
   {
       $headers = create_smtp_headers($subject, "davismerchgroup@me.com", $email_body[$i][$j]['email'], "Davis Merchandising", $sent_to[$i]['name'], "text/html");
        $data = $headers . "<html><BODY>";
      $data .= $email_body[$i][$j]['cnt'];
           
        $data .=   "</body></html>";
       // echo $data."<br/>";
       if ((send_smtp($mailServerAddress, "davismerchgroup@me.com", $email_body[$i][$j]['email'], $data)) == false) {
            $return_arr['error'] = "Unable to send email to : ".$email_body[$i][$j]['email'] ." Please check the email ID";
            echo json_encode($return_arr);
            return;
        }
        
       
    }
   }
    }

header('Content-Type: application/json');
echo json_encode($return_arr);
return;
?>