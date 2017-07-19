<?php

extract($_GET);
$html='';

$query  = 'select * from img_glry_files  where glry_id='.$glry_id;
//echo $query;
if (!($result = pg_query($connection, $query)))
{
    print("Failed client query: " . pg_last_error($connection));
    exit;
}
if(isset($img_files)) unset($img_files);
while ($row = pg_fetch_array($result))
{
    $img_files[] = $row;
}
pg_free_result($result);

$html='';

for ($k = 0; $k < count($img_files); $k++)
            {
      
    $html.='<div style="float:left;" width="140px" height="140px">';
     $html.= '<img width="100px" height="100px" src="'.$img_glry_dir. $img_files[$k]['file_name'] . '" onclick="PopEx(this, null,  null, 0, 0, 50,PopBoxImageLarge);"/>';
       if($_SESSION['emp_type']==0) { 
    $html.='<img width="20px" height="20px" src="'.$mydirectory.'/images/delete.png" onclick="javascript:delGlryFile('.$img_files[$k]['fid'].','.$rc.','.$glry_id.');"/></div>';                              
      }
      else $html.='&nbsp&nbsp&nbsp';
            }

?>