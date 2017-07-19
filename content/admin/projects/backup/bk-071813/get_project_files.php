<?php
 $plano=array();
  $img=array();
   $cl_sign=array();
if ($pid > 0)
{
    $query  = "SELECT * FROM prj_uploads WHERE pid=$pid  ORDER BY upid ASC;";
   // echo $query;
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed Image query: " . pg_last_error($connection));
        exit;
    }
    while ($row = pg_fetch_array($result))
    {
        switch($row['file_cat'])
        {
       case "plano":     
        $plano[] = $row;
           break;
        case "img":     
        $img[] = $row;
           break;
        case "cl_sign":     
        $cl_sign[] = $row;
           break;
        }
    }
    pg_free_result($result);

}
//print_r();
$html = '<table  border="0" cellspacing="0" cellpadding="0"  width="95%" >';
if (isset($img) && isset($img[0]))
{
$html .=
    "<tr>".
        "<td>".
            "<strong>Image Of Display:</strong>".
        "</td>".
    "</tr>";
$html .= "<tr>".
            "<td>";
    for ($i = 0; $i < count($img); $i++)
    {
        
$html .=  '<img  width="101" height="89" style="float:left;padding:5px" src="'.$image_dir  . $img[$i]['file_name']. '" alt="'.$img[$i]['disp_name'].'" pbsrc="'.$image_dir . $img[$i]['file_name']. '" onClick="popbox(this);" style="z-index:9999;" class="PopBoxImageSmall" title="Click to magnify/shrink" />'.
                '<img style="float:left;" src="'.$mydirectory.'/images/delete.png" width="18px" class="hand" onclick="javascript:delete_file('.$img[$i]['upid'].');" title="Delete '.$img[$i]['disp_name'].'" />';
       
    }
       $html .= '</td>'.
       ' </tr>';
}
if (isset($plano) && isset($plano[0]))
{
$html .=
    "<tr>".
        "<td>".
            "<strong>Planogram:</strong>".
        "</td>".
    "</tr>";
$html .= "<tr>".
            "<td>";
  for ($i = 0; $i < count($plano); $i++)
    {
      
               $html .='<a href="download.php?upid='.$plano[$i]['upid'].'" title="Download '.$plano[$i]['disp_name'].'"><strong>'.$plano[$i]['disp_name'].'</strong></a>'.
               '<img src="'. $mydirectory.'/images/delete.png" width="18px" class="hand" onclick="javascript:delete_file('.$plano[$i]['upid'].');" title="Delete '.$plano[$i]['disp_name'].'" />';
        
    }
       $html .= '</td>'.
       ' </tr>';
}
if (isset($cl_sign) && isset($cl_sign[0]))
{
$html .=
    "<tr>".
        "<td>".
            "<strong>Client Sign Off:</strong>".
        "</td>".
    "</tr>";
$html .= "<tr>".
            "<td>";
 for ($i = 0; $i < count($cl_sign); $i++)
    {
      
               $html .='<a href="download.php?upid='.$cl_sign[$i]['upid'].'" title="Download '.$cl_sign[$i]['disp_name'].'"><strong>'.$cl_sign[$i]['disp_name'].'</strong></a>'.
               '<img src="'. $mydirectory.'/images/delete.png" width="18px" class="hand" onclick="javascript:delete_file('.$cl_sign[$i]['upid'].');" title="Delete '.$cl_sign[$i]['disp_name'].'" />';
        
    }
       $html .= '</td>'.
       ' </tr>';
}

$html .= '</table>';
?>
