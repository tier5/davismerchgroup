<?php

if ($pid > 0)
{
    $query  = "SELECT * FROM prj_uploads WHERE pid=$pid AND file_type='I' ORDER BY upid ASC;";
    if (!($result = pg_query($connection, $query)))
    {
        print("Failed Image query: " . pg_last_error($connection));
        exit;
    }
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
    while ($row = pg_fetch_array($result))
    {
        $files[] = $row;
    }
    pg_free_result($result);
}
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
        
$html .=  '<img  width="101" height="89" style="float:left;padding:5px" src="'.$image_dir . 'thumb/' . $images[$i]['file_name']. '" alt="'.$images[$i]['disp_name'].'" pbsrc="'.$image_dir . $images[$i]['file_name']. '" onClick="popbox(this);" style="z-index:9999;" class="PopBoxImageSmall" title="Click to magnify/shrink" />'.
                '<img style="float:left;" src="'.$mydirectory.'/images/delete.png" width="18px" class="hand" onclick="javascript:delete_file('.$images[$i]['upid'].');" title="Delete '.$images[$i]['disp_name'].'" />';
       
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
      
               $html .='<a href="download.php?upid='.$files[$i]['upid'].'" title="Download '.$files[$i]['disp_name'].'"><strong>'.$files[$i]['disp_name'].'</strong></a>'.
               '<img src="'. $mydirectory.'/images/delete.png" width="18px" class="hand" onclick="javascript:delete_file('.$files[$i]['upid'].');" title="Delete '.$files[$i]['disp_name'].'" />';
        
    }
 $html .='</td>'.
        '</tr>';    
}
$html .= '</table>';
?>
