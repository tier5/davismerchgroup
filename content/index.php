<?php
require('Application.php');
if(isset($_GET['reset']))
{
if(isset($_SESSION['ind_proj'])) unset($_SESSION['ind_proj']);?>
<script type="text/javascript">
    location.href="index.php";
</script> <?php } 
if (isset($_SESSION['emp_type']) && $_SESSION['emp_type'] != '' && $_SESSION['emp_type'] == 1)
    header('location: admin/projects/');
else
{
    require('header.php');
    $paging = 'paging=';

    if (isset($_GET['paging']) && $_GET['paging'] != "")
    {
        $paging .= $_GET['paging'];
    } else
    {
        $paging .= 1;
    }
    include('./pagination.class_home.php');

    if ($debug == "on")
    {
        echo "count resultapp1 IS " . count($resultapp1) . "<br>";
        echo "count dataapp1 IS " . count($dataapp1) . "<br>";
        echo "_SESSION firstname IS " . $_SESSION['firstname'] . "<br>";
        echo "_SESSION lastname IS " . $_SESSION['lastname'] . "<br>";
        print_r($dataapp1);
        print_r(mysql_fetch_array($resultapp1));
    }
    echo "<center>";
    echo "<h3><b>Welcome</b> To the Internal Intranet for $compname<br>";
    echo "Please remember to quit your web browser when finished.</h3>";
    print <<<EOF

<br />
<table width="100%">
<tr valign="top">
<td align="center"><a href="timesheet/"><img src="images/tmsheet.gif" border="0" alt="Time Clock" /></a></td>
<td align="center"><a href="admin/time_reports/"><img src="images/timecardreports.png" border="0" alt="Reports" /></a></td>
<td align="center"><a href="admin/EmployeeDetails/listApplication.php"><img src="images/btn2.jpg" border="0" alt="Employee Applications" /></a></td>
<td align="center"><a href=""><img src="images/btn3.jpg" border="0" alt="Prospects" /></a></td>
<td align="center"><a href="admin/projects/"><img src="images/btn4.jpg" border="0" alt="Projects" /></a></td>
</tr>
<tr>
<td></td>
<td align="center"><a href="admin/email_weekly/index.php"><img src="images/emailschdule.png" border="0" alt="Weekly email" /></a></td>
<td align="center"><a href="admin/signoff_report/index.php"><img src="images/signoffreports.png" border="0" alt="Projects" /></a></td>
<td align="center"><a href="distance/index.php"><img src="images/timereport.png" border="0" alt="Distance" /></a></td>
<td align="center"></td>
<td></td>
</tr>
</table>

EOF;
    ?>
    <br/>
    <?php
    //echo 'time--'.date('Y/m/d',strtotime('monday this week'));

    $sql = 'SELECT distinct (name ),m_pid as pid from project_main order by name';
    if (!($result = pg_query($connection, $sql)))
    {
        print("Failed query1: " . pg_last_error($connection));
        exit;
    }
    while ($row2 = pg_fetch_array($result))
    {
        $data_proj[] = $row2;
    }

    $sql3 = 'Select rid, region from "tbl_region" ORDER BY region ASC ';
    if (!($result = pg_query($connection, $sql3)))
    {
        print("Failed query1: " . pg_last_error($connection));
        exit;
    }
    while ($row3 = pg_fetch_array($result))
    {
        $data_region[] = $row3;
    }
    pg_free_result($result);

    $srch_sql = '';
    if ($_SESSION['perm_manager'] == "on" || $_SESSION['perm_admin'] == "on")
    {
        
    } else
    {
        $srch_sql = ' and(m.merch=' . $_SESSION['employeeID'] . ' or prj.created_by=' . $_SESSION['employeeID'] . ') ';
    }
    
 if(isset($_REQUEST['region']))
{
$_SESSION['ind_proj']['region']=$_REQUEST['region'];
} 
 if(isset($_REQUEST['project']))
{
$_SESSION['ind_proj']['project']=$_REQUEST['project'];
}
 if(isset($_REQUEST['start_dt']))
{
$_SESSION['ind_proj']['start_dt']=$_REQUEST['start_dt'];
} 
 if(isset($_REQUEST['end_dt']))
{
$_SESSION['ind_proj']['end_dt']=$_REQUEST['end_dt'];
} 
    if (isset($_SESSION['ind_proj']['region']) && $_SESSION['ind_proj']['region'] > 0)
    {
        $srch_sql.=' and m.region=' . $_SESSION['ind_proj']['region'];
    }

    if (isset($_SESSION['ind_proj']['project']) && $_SESSION['ind_proj']['project'] > 0)
    {
        $srch_sql.=' and prj.m_pid=' . $_SESSION['ind_proj']['project'];
    }
  if (isset($_SESSION['ind_proj']['start_dt'])&&$_SESSION['ind_proj']['start_dt']!='')
    {
        $srch_sql.=' and m.due_date>=' . strtotime($_SESSION['ind_proj']['start_dt']);
    } 
   if (isset($_SESSION['ind_proj']['end_dt'])&&$_SESSION['ind_proj']['end_dt']!='')
    {
        $srch_sql.=' and m.due_date<=' . strtotime($_SESSION['ind_proj']['end_dt']);
    }      
//$this_w_st=strtotime('monday this week');
//$this_w_end=strtotime('sunday this week');
    $this_w_st = strtotime('saturday previous week');
    $this_w_end = strtotime('friday this week');
    $sql = 'SELECT prj.prj_name ,m1.firstname, m1.lastname,main.name,m.confirm,m.merch,m.m_id,m.city,prj.pid, ch.chain,m.view_stat, st.sto_num, m.st_time,m.due_date 
from projects  as prj  left join prj_merchants_new as m  on m.pid=prj.pid '
            . ' left join tbl_chain as ch on ch.ch_id=m.location left join tbl_chainmanagement as st on st.chain_id = m.store_num '
            . ' left join project_main as main on main.m_pid=prj.m_pid '
            . ' left join "employeeDB" as m1 on m1."employeeID"= m.merch '
            . 'where m.pid = prj.pid and  m."due_date">=' . $this_w_st . ' and m."due_date"<=' . $this_w_end . $srch_sql . ' and  prj.status =1 order by m.m_id asc';
//echo $sql;
    /* if(!($result=pg_query($connection,$sql))){
      print("DB ERROR: " . pg_last_error($connection));
      exit;
      }
      $this_week_sch=array();
      while($row = pg_fetch_array($result))
      {
      $this_week_sch[]=$row;
      } */

    if (!($resultp = pg_query($connection, $sql)))
    {
        print("Failed query: " . pg_last_error($connection));
        exit;
    }
    $items = pg_num_rows($resultp);
    $p_num = $items;
    if ($items > 0)
    {
        $p = new pagination;
        $p->items($items);
        $p->limit(10); // Limit entries per page
        $uri = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '&paging'));
        $uri2 = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '&paging2'));
        if (!$uri)
        {
            $uri = $_SERVER['REQUEST_URI'] . $search_uri;
        }
        //echo "uri==>".$uri;
        $p->target($uri);
        $p->currentPage($_GET[$p->paging]); // Gets and validates the current page
        $p->calculate(); // Calculates what to show
        $p->parameterName('paging');
        $p->adjacents(1); //No. of page away from the current page

        if (!isset($_GET['paging']))
        {
            $p->page = 1;
        } else
        {
            $p->page = $_GET['paging'];
        }
        //Query for limit paging
        $limit = "LIMIT " . $p->limit . " OFFSET " . ($p->page - 1) * $p->limit;
    }
    $sql = $sql . " " . $limit;
    $this_week_sch = array();
    if (!($resultp = pg_query($connection, $sql)))
    {
        print("Failed queryd: " . pg_last_error($connection));
        exit;
    }
    while ($rowd = pg_fetch_array($resultp))
    {
        $this_week_sch[] = $rowd;
    }



    pg_free_result($resultp);
    ?>
    <script type="text/javascript">
        var from_proj=1;    
        var from_report=0;
    </script>

    <div style="border-color:black;border-style:solid;border-width:3px;">
        <table style="margin:0px;padding:0px;" width="100%"><tr><td colspan="3" align="center"><h3>Welcome To the Internal Intranet for Davis Merchandising Group</h3></td></tr>


            <?php
            $sql = 'select * from announcement ';
     if($_SESSION['perm_admin'] == "on"){}else{       
     $sql.=' where region=(select region from "employeeDB" where "employeeID"=' . $_SESSION['employeeID'] . ')';
     }
            $sql.=' order by anc_id desc';
            if (!($resultp = pg_query($connection, $sql)))
            {
                print("Failed query: " . pg_last_error($connection));
                exit;
            }
            $ancmnt = array();

            if (!($resultp = pg_query($connection, $sql)))
            {
                print("Failed query: " . pg_last_error($connection));
                exit;
            }

            if (!($resultp = pg_query($connection, $sql)))
            {
                print("Failed queryd: " . pg_last_error($connection));
                exit;
            }
            while ($rowd = pg_fetch_array($resultp))
            {
                $ancmnt[] = $rowd;
            }
            pg_free_result($resultp);
            ?>




            <tr><td colspan="3">&nbsp;</td></tr>
            <tr><td colspan="3">
                    <form action="" method="get">        
                        <table width="100%" cellspacing="1" cellpadding="1" border="0">        
                            <tr><td colspan="4"><font size="3">Search Schedules</font></td></tr>
                            <tr class="grid001">
                                <td style="color:white;" >Project Name:</td>
                                <td><select class="srch_field" name="project" id="project">
                                        <option value="" selected>--------Select--------</option>
                                        <?php
                                        for ($i = 0; $i < count($data_proj); $i++)
                                        {
                                            if ($data_proj[$i]['pid'] == '')
                                                continue;
                                            echo '<option value="' . $data_proj[$i]['pid'] . '" ';
                                            if ($data_proj[$i]['pid'] == $_SESSION['ind_proj']['project'])
                                                echo ' selected';
                                            echo '>' . $data_proj[$i]['name'] . '</option>';
                                        }
                                        ?> </select></td>
  <td style="color:white;" >Start date:</td>                               
<td><input name="start_dt" type="text" value="<?php if(isset($_SESSION['ind_proj']['start_dt'])) echo $_SESSION['ind_proj']['start_dt']; ?>" id="start_dt"/></td>
 <td style="color:white;" >End date:</td>
<td><input name="end_dt" type="text" value="<?php if(isset($_SESSION['ind_proj']['end_dt'])) echo $_SESSION['ind_proj']['end_dt']; ?>" id="end_dt"/></td>
                                <td style="color:white;">Region:</td>
                                <td><select class="srch_field" name="region" id="region">
                                        <option value="">--------Select--------</option>                   
    <?php
    for ($i = 0; $i < count($data_region); $i++)
    {
        echo '<option value="' . $data_region[$i]['rid'] . '" ';
        if ($data_region[$i]['rid'] == $_SESSION['ind_proj']['region'])
            echo 'selected="selected" ';
        echo '>' . $data_region[$i]['region'] . '</option>';
    }
    ?>
                                    </select></td>
                            </tr> 
                            <tr>
                                <td colspan="5" align="center">
                                    <input  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" type="submit" value="Search"/>
                                    <input  class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" onclick="location.href='<?php echo $base_url; ?>content/index.php?reset=1'" type="button" value="Cancel"/> 
                                </td>
                            </tr>         
                        </table>
                    </form>             
                </td></tr>

            <tr><td colspan="3">&nbsp;</td></tr>  
            <tr>
                <td width="45%" valign="top">
                    <table width="100%">
                        <tr style="background-color:#24ABED;"><td colspan="12" align="center" style="color:white;"><font size="3"><b>This Week's Schedule</b></font></td></tr>
                        <tr><td colspan="12"><?php if ($p_num > 0) echo $p->show(); ?></td></tr>          
                        <tr style="background-color:#CCC;">
                            <td style="color:black;"><strong>Start Date</strong></td>  <td style="color:black;"><strong>Employee Name</strong></td><td style="color:black;"><strong>Project Name</strong></td>  <td style="color:black;"><strong>Job ID</strong></td>  <td style="color:black;"><strong>Store</strong></td>  
                            <td style="color:black;"><strong>Store&nbsp;#</strong></td><td style="color:black;"><strong>Location</strong></td> <td style="color:black;"><strong>Start Time</strong></td>  <td style="color:black;"><strong>View</strong></td>  
                            <td style="color:black;"><strong>Time Entry</strong></td>    
                            <td style="color:black;"><strong>Confirm</strong></td>  
                            <td style="color:black;"><strong>Status</strong></td>  
                        </tr>
    <?php
    $c_flag = 0;
    $cnt = 0;
    $i=0;
    foreach ($this_week_sch as $week)
    {
        
            $m_prev_f=0;       
for($j=0;$j<$cnt;$j++)
{
 if($this_week_sch[$j]['merch']==$week['merch']&&$this_week_sch[$j]['due_date']==$week['due_date']&&$this_week_sch[$j]['st_time']==$week['st_time'])
 {
$m_prev_f=1;     
 }    
}
   
        ?>
                            <tr <?php if($m_prev_f==1) {echo ' style="display:none;" ';}?> height="30px" <?php if ($c_flag == 1)
        {
            echo ' style="background-color:#B6C4B5;"';
            $c_flag = 0;
        } else
        {
            $c_flag = 1;
        } ?>>
                                <td><?php echo date('l, d- M', $week['due_date']); ?></td> 
                                <td><?php echo $week['firstname'] . ' ' . $week['lastname'] ?></td> 
                                <td><?php echo $week['name'] ?></td> 
                                <td><?php echo $week['prj_name'] ?></td> 
                                <td><?php echo $week['chain'] ?></td>
                                <td><?php echo $week['sto_num'] ?></td>
                                <td><?php echo $week['city'] ?></td>
                                <td><?php echo $week['st_time'] ?></td>
                                <td><img  onclick="goto_poject_page('<?php echo $week['m_id']; ?>','<?php echo $week['pid']; ?>');" src="<?php echo $mydirectory; ?>/images/view.png" width="28" height="28" alt="View" /></td>
                                <td align="center">
        <?php if ($week['merch'] == $_SESSION['employeeID'] && ($_SESSION['perm_admin'] == "on" || $_SESSION['perm_manager'] == "on"))
        { 

if($m_prev_f==0)
{
            ?> 
                                    
                                        <img width="20" height="20" src="<?php echo $mydirectory; ?>/images/clock.jpg" style="cursor:pointer;cursor:hand;" onclick="javascript:loadTimesheet('dt=<?php echo $week['due_date']; ?>&from_prject=1&m_id=<?php echo $week['m_id']; ?>');"/>
                            <?php }} ?>
                                </td>
                                <td>
              <?php           

if($m_prev_f==0)
{   ?>            
                                    
                                    <table width="100%">   
                                        <tr><td>Accept</td><td><input <?php if ($week['confirm'] == 't') echo' checked '; ?> onchange="merch_stat_submit('<?php echo $week['m_id']; ?>',$(this).val());" value="yes" type="radio" name="status_<?php echo $cnt; ?>"/></td></tr>
                                        <tr><td>Deny</td><td><input <?php if ($week['confirm'] == 'f') echo' checked '; ?> onchange="merch_stat_submit('<?php echo $week['m_id']; ?>',$(this).val());" value="no" type="radio" name="status_<?php echo $cnt;
                    //$cnt+=1; ?>"/></td></tr>
                                    </table><?php }?>
                                </td>
                                <td align="center"><img width="20" height="20" src="<?php
                    $bulb_clr = 'bulb_blue.png';
                    if ($week['confirm'] == 't')
                        $bulb_clr = 'bulb_green.png';
                    else if ($week['confirm'] == 'f')
                        $bulb_clr = 'bulb_black.png';
                    else if ($week['view_stat'] == 'seen')
                        $bulb_clr = 'bulb_yellow.png';
                    else
                        $bulb_clr = 'bulb_blue.png';

                    echo $mydirectory;
                            ?>/images/<?php echo $bulb_clr; ?>" style="cursor:pointer;cursor:hand;" /></td>
                            </tr>    
                        <?php $cnt+=1;}
                        ?>
                        <tr><td colspan="12"><?php if ($p_num > 0) echo $p->show(); ?></td></tr>      
                    </table>
                </td>   
                <td width="2%">&nbsp;</td> 
                <td valign="top">
                    <table width="100%"><?php
                    $this_w_st = strtotime('saturday this week');
                    $this_w_end = strtotime('friday next week');
                    $sql = 'SELECT prj.prj_name ,main.name,m1.firstname, m1.lastname,m.view_stat,m.confirm, prj.pid,m.merch, ch.chain,m.m_id, st.sto_num, m.st_time,m.due_date 
from projects  as prj  left join prj_merchants_new as m  on m.pid=prj.pid ' .
                            ' left join tbl_chain as ch on ch.ch_id=m.location left join tbl_chainmanagement as st on st.chain_id = m.store_num '
                            . ' left join project_main as main on main.m_pid=prj.m_pid '
                            . ' left join "employeeDB" as m1 on m1."employeeID"= m.merch '
                            . ' where m.pid = prj.pid and  m."due_date">=' . $this_w_st . ' and m."due_date"<=' . $this_w_end . $srch_sql . '  and  prj.status =1 order by m."due_date"';
//echo $sql;

                    $next_week_sch = array();


                    if (!($resultp = pg_query($connection, $sql)))
                    {
                        print("Failed query: " . pg_last_error($connection));
                        exit;
                    }
                    $items = pg_num_rows($resultp);
                    $p2_num = $items;
                    if ($items > 0)
                    {
                        $p2 = new pagination;
                        $p2->items($items);
                        $p2->limit(10); // Limit entries per page
                        //$uri = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '&paging2'));
                        if (!$uri2)
                        {
                            $uri2 = $_SERVER['REQUEST_URI'] . $search_uri;
                        }
                        //echo "uri==>".$uri;
                        $p2->target($uri2);
                        $p2->currentPage($_GET[$p2->paging]); // Gets and validates the current page
                        $p2->calculate(); // Calculates what to show
                        $p2->parameterName('paging2');
                        $p2->adjacents(1); //No. of page away from the current page

                        if (!isset($_GET['paging2']))
                        {
                            $p2->page = 1;
                        } else
                        {
                            $p2->page = $_GET['paging2'];
                        }
                        //Query for limit paging
                        $limit = "LIMIT " . $p2->limit . " OFFSET " . ($p2->page - 1) * $p2->limit;
                    }
                    $sql = $sql . " " . $limit;
                    if (!($resultp = pg_query($connection, $sql)))
                    {
                        print("Failed queryd: " . pg_last_error($connection));
                        exit;
                    }
                    while ($rowd = pg_fetch_array($resultp))
                    {
                        $next_week_sch[] = $rowd;
                    }


                    pg_free_result($resultp);
                        ?><tr style="background-color:#24ABED;"><td colspan="12" align="center" style="color:white;"><font size="3"><b>Upcoming Schedule</b></font></td></tr>
                        <tr><td colspan="12"><?php if ($p2_num > 0) echo $p2->show(); ?></td></tr>    
                        <tr style="background-color:#CCC;">
                            <td style="color:black;"><strong>Start Date</strong></td>  <td style="color:black;"><strong>Employee Name</strong></td><td style="color:black;"><strong>Project Name</strong></td>  <td style="color:black;"><strong>Job ID</strong></td>  <td style="color:black;"><strong>Store</strong></td>  
                            <td style="color:black;"><strong>Store&nbsp;#</strong></td> <td style="color:black;"><strong>Location</strong></td><td style="color:black;"><strong>Start Time</strong></td>  <td style="color:black;"><strong>View</strong></td>  
                            <td style="color:black;"><strong>Time Entry</strong></td>    
                            <td style="color:black;"><strong>Confirm</strong></td>  
                            <td style="color:black;"><strong>Status</strong></td>  
                        </tr>
    <?php
    $cnt=0;
    foreach ($next_week_sch as $week)
    {
        $m_prev_f=0;       
for($j=0;$j<$cnt;$j++)
{
 if($next_week_sch[$j]['merch']==$week['merch']&&$next_week_sch[$j]['due_date']==$week['due_date']&&$next_week_sch[$j]['st_time']==$week['st_time'])
 {
$m_prev_f=1;     
 }    
}
        ?>
                            <tr <?php if($m_prev_f==1) echo ' style="display:none;" ';?> height="30px" <?php if ($c_flag == 1)
                                
        {
            echo ' style="background-color:#B6C4B5;"';
            $c_flag = 0;
        } else
        {
            $c_flag = 1;
        } ?>>
                                <td><?php echo date('l, d- M', $week['due_date']); ?></td> 
                                <td><?php echo $week['firstname'] . ' ' . $week['lastname'] ?></td> 
                                <td><?php echo $week['name'] ?></td> 
                                <td><?php echo $week['prj_name'] ?></td> 
                                <td><?php echo $week['chain'] ?></td>
                                <td><?php echo $week['sto_num'] ?></td>
                                <td><?php echo $week['city'] ?></td>
                                <td><?php echo $week['st_time'] ?></td>
                                <td><img  onclick="goto_poject_page('<?php echo $week['m_id']; ?>','<?php echo $week['pid']; ?>');" src="<?php echo $mydirectory; ?>/images/view.png" width="28" height="28" alt="View" /></td>
                                <td align="center">
        <?php if ($week['merch'] == $_SESSION['employeeID'] && ($_SESSION['perm_admin'] == "on" || $_SESSION['perm_manager'] == "on"))
        {  
if($m_prev_f==0)
{?>
                                    
                                        <img width="20" height="20" src="<?php echo $mydirectory; ?>/images/clock.jpg" style="cursor:pointer;cursor:hand;" onclick="javascript:loadTimesheet('dt=<?php echo $week['due_date']; ?>&from_prject=1&m_id=<?php echo $week['m_id']; ?>');"/>
        <?php }} ?>
                                </td>
                                <td>
       <?php    
if($m_prev_f==0)
{        ?>                
                                    <table width="100%">   
                                        <tr><td>Accept</td><td><input <?php if ($week['confirm'] == 't') echo' checked '; ?> onchange="merch_stat_submit('<?php echo $week['m_id']; ?>',$(this).val());" value="yes" type="radio" name="status_<?php echo $cnt; ?>"/></td></tr>
                                        <tr><td>Deny</td><td><input <?php if ($week['confirm'] == 'f') echo' checked '; ?> onchange="merch_stat_submit('<?php echo $week['m_id']; ?>',$(this).val());" value="no" type="radio" name="status_<?php echo $cnt;
        ?>"/></td></tr>
                                    </table><?php }?>
                                </td>
                                <td align="center"><img width="20" height="20" src="<?php
        $bulb_clr = 'bulb_blue.png';
        if ($week['confirm'] == 't')
            $bulb_clr = 'bulb_green.png';
        else if ($week['confirm'] == 'f')
            $bulb_clr = 'bulb_black.png';
        else if ($week['view_stat'] == 'seen')
            $bulb_clr = 'bulb_yellow.png';
        else
            $bulb_clr = 'bulb_blue.png';

        echo $mydirectory;
        ?>/images/<?php echo $bulb_clr; ?>" style="cursor:pointer;cursor:hand;" /></td>
                            </tr>      
    <?php   $cnt+=1;}
    ?>
                        <tr><td colspan="12"><?php if ($p2_num > 0) echo $p2->show(); ?></td></tr>         
                    </table>
                </td> 
            </tr>
        </table>
    </div>
    <div id="dialogue">
        <div id='sub_content'></div>
    </div>
    <div id='sub_content_confirm' style="display:none;">
        <form id='sub_content_confirm_form'>    
            <table width="100%">  
                <tr><td>State your reason for the denying the job.</td></tr>    
                <tr><td><textarea id="deny_reason" name="deny_reason" cols="75" rows="10"></textarea></td></tr> 
                <tr><td><input class="ui-button ui-button-text-only ui-widget ui-state-default ui-corner-all" type="button" value="Submit" onclick="javascript:submit_merch_deny();"/></td></tr> 
            </table></form>  
    </div>    
    <link rel="stylesheet" type="text/css" href="<?php echo $mydirectory; ?>/css/jquery.jgrowl.css" />    <style type="text/css">

div.jGrowl div.manilla {
background-color:	#FFF1C2;
color:navy;
}

div.jGrowl div.smoke {
background: url(smoke.png) no-repeat;
-moz-border-radius:0px;
-webkit-border-radius:	0px;
width:280px;
height:	55px;
overflow:	hidden;
}

div.jGrowl div.flora {
background:#E6F7D4 url(flora-notification.png) no-repeat;
-moz-border-radius:0px;
-webkit-border-radius:	0px;
opacity:	1;
filter:	alpha(opacity = 100);
width:270px;
height:	90px;
padding:	0px;
overflow:	hidden;
border-color:#5ab500;
}

div.jGrowl div.flora div.message {
padding: 5px;
color:#000;
}

div.jGrowl div.flora div.header {
background:url(flora-header.png) no-repeat;
padding:	5px;
}

div.jGrowl div.flora div.close {
background:url(flora-close.png) no-repeat;
padding:	5px;
color:transparent;
padding:	0px;
margin:	5px;
width:	17px;
}

div.jGrowl div.iphone {
font-family:"Helvetica Neue", "Helvetica";
font-size:	12px;
background:url(iphone.png) no-repeat;
-moz-border-radius:0px;
-webkit-border-radius:	0px;
opacity:	.90;
filter:	alpha(opacity = 90);
width:314px;
height:	137px;
overflow:hidden;
color:#fff;
border:0px;
}

div.jGrowl div.iphone .jGrowl-close {
padding-right:20px;
}

div.jGrowl div.iphone div.message {
padding-top:0px;
padding-bottom:	7px;
padding-left:15px;
padding-right:15px;

}


div.jGrowl div.iphone div.header {
padding:	7px;
padding-left:15px;
padding-right:15px;
font-size:	17px;
}

div.jGrowl div.iphone div.close {
display:	none;
}

div#random {
width:1000px;
background-color:	red;
line-height:60px;
}
.jGrowl-notification
{
float:left;
}
.jGrowl-closer 
{clear:both;}
.jGrowl-message
{
  font-size: 15px;
    overflow: hidden;
    padding: 5px;
    text-align: justify;   
}
div.jGrowl-header
{ 
 font-size:18px;     
}
div.jGrowl-notification {
    max-height: 80px;
    overflow: auto;
    background-color: red;
}
.highlight{
    clear:both;
}

    </style>
    <link rel="stylesheet" type="text/css" href="<?php echo $mydirectory; ?>/css/jquery-ui-1.8.19.custom.css" />
    <script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-1.7.2.min.js'></script>
    <script type='text/javascript' src='<?php echo $mydirectory; ?>/js/jquery/jquery-ui-1.8.19.custom.min.js'></script> 
    <script type='text/javascript' src='<?php echo $mydirectory; ?>/js/timesheet.js'></script>
    <script type="text/javascript" src="<?php echo $mydirectory; ?>/js/jquery.jgrowl.js"></script>
    <script type='text/javascript'>
        $(document).ready(function(){
 
            $('#sub_content_confirm').dialog({
                width:'650',
                height:'400',
                autoOpen: false,
                modal: true
            }); 
    $('#start_dt').datepicker();
    $('#end_dt').datepicker();
       
    <?php
   // echo 'fff->'.$_SESSION['f_t_not_flag'];
  if(isset($_SESSION['f_t_not_flag'])&&$_SESSION['f_t_not_flag']==1){unset($_SESSION['f_t_not_flag']);     
    foreach ($ancmnt as $anc)
    {
        if ($anc['msg'] != '' && $anc['title'] != '')
        {
            ?>
       var msg="<?php 
$anc['msg']=str_replace("\n", '', $anc['msg']);
$anc['msg']=str_replace("\r", '', $anc['msg']);
$anc['msg']=str_replace("\r\n", '', $anc['msg']);
$anc['msg']=str_replace(PHP_EOL, '', $anc['msg']);  

 rtrim($anc['msg']); 
// if(strlen($anc['msg'])>280){
//$anc['msg']=substr($anc['msg'], 0,280);
// $anc['msg'].="&nbsp;<a style='color:#24ABED;' target='_blank' href='./announcement/index.php?edit=".$anc['anc_id']."'>Read more..</a>";
// }
echo $anc['msg'];
?>";                        
                    $.jGrowl(msg, { header: "<?php echo str_replace(PHP_EOL,'',$anc['title']."&nbsp;&nbsp;<a style='color:#24ABED;' target='_blank' href='./announcement/index.php?edit=".$anc['anc_id']."'>Read more..</a>"); ?>", sticky: true,position:'center'});
        <?php }
    }}
    ?>
        });
        function loadTimesheet(dataString){
         
            dataString+='&from_proj=1';
            showLoading();
            $.ajax({type: 'POST',url: '<?php echo $mydirectory; ?>/timesheet/addtime.php',data: dataString,dataType: 'html',success: function(html){
                    sub_content.html(html);
                    //initTime();
                    sub_content.dialog( "open" );
                    hideLoading();
                    //var sp=dataString.split('=');
                    //initDatetimepicker(sp[1]);
                }});
        }   
        
        function loadTimesheet_new(dt)
        {
            $( "#sub_content" ).dialog('close');
            loadTimesheet(dt+'&add_new=1'); 
        }  

        function merch_stat_submit(m_id,obj)
        {

            var data="m_id="+m_id+"&status="+obj;
            if(obj=='yes'||obj=='seen') 
            {    
                merch_confirm_sendmail(data);
            }
            else { 
                if($('#merch_cfrm_sm_div').length>0)$('#merch_cfrm_sm_div').remove();
                var htm_cnt='<div id="merch_cfrm_sm_div"><input type="hidden" name="m_id" value="'+m_id+'"/>';    
                htm_cnt+='<input type="hidden" name="status" value="no"/></div>';  
                $('#sub_content_confirm_form').append(htm_cnt);             
                $('#sub_content_confirm').dialog('open');             
              
            }

        }

        function submit_merch_deny()
        {
            if($.trim($('#deny_reason').val())=='')
                {
          alert('Please state your reason and continue...');  
          return;
                }
            merch_confirm_sendmail($('#sub_content_confirm_form').serialize());
            $('#sub_content_confirm').dialog('close');    
        }

        function merch_confirm_sendmail(data)
        {
            $.ajax({
                url:'merch_stat_submit.php',
                data:data,
                datatype:'json',
                type:'post',
                success:function(res){
                    location.reload();
                },
                error:function(){
                    location.reload();      
                }
            });   
        }

        function goto_poject_page(m_id,pid)
        {
        
            merch_stat_submit(m_id,'seen');

            var win=window.open('admin/projects/project.php?pid='+pid, '_blank');
            win.focus();    
        }

        function loadTimesheet_edit(time_id)
        {
            $( "#sub_content" ).dialog('close');
            loadTimesheet('id='+time_id); 
        }
    </script>
    <?php
    echo "</center>";
    require('trailer.php');
}
?>
