<?php
require 'Application.php';
extract($_POST);

//$sql = 'select pid, prj_name from projects where status=';
//echo $sql;
$where='';
if($_SESSION['perm_admin'] != "on" && $_SESSION['emp_type']==0)
{
  $where.=" inner join prj_merchants_new as m on m.m_id = (select mer.m_id from prj_merchants_new as mer  where mer.pid = prj.pid and mer.merch='".
          $_SESSION['employeeID']."' OR prj.created_by ='".$_SESSION['employeeID']."' limit 1 )";    
    
}


$sql ='SELECT distinct ( main.name ), main.m_pid as pid from projects  as prj  left join project_main as main on main.m_pid=prj.m_pid ';
if($_SESSION['emp_type']!=0)
  {
   
   $sql .= " left join prj_signoff_clients as c_sign on  c_sign.client = '".trim($_SESSION['client_id']). "' ";
   
  }
 $sql .= $where.' where ';
if($status=='close') $sql.='prj.status=0'; else $sql.='prj.status=1 ';
if($_SESSION['emp_type']!=0)
  {
    $sql.= "and c_sign.pid = main.m_pid";
  }

//echo $sql;

if (!($result = pg_query($connection, $sql)))
{
    print("Failed query1: " . pg_last_error($connection));
    exit;
}
while ($row2 = pg_fetch_array($result))
{
    $data_project[] = $row2;
}
echo ' <option value="">--------Select--------</option>';

for ($i = 0; $i < count($data_project); $i++)
{
    if($data_project[$i]['pid']=='') continue;
    echo '<option value="' . $data_project[$i]['pid'] . '"';
    if ($data_project[$i]['pid'] == $_GET['project'])
        echo ' selected="selected" ';
    echo '>' . $data_project[$i]['name'] . '</option>';
}
              
?>