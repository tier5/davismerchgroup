<?php

if(isset($form_type)&&$form_type!=''){}
else $form_type=$_GET['form_type'];
    
$query  = "select * from sign_off_files  where form_id=".$form_id." and form_type='".$form_type."'";
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



for ($k = 0; $k < count($img_files); $k++)
            {
      

     $html.= '<tr>
     <td valign="top" align="left" height="30" colspan="5"> ';
      if($is_client==1)
{
$html.='<a href="'.$base_url.'content/upload_files/images/'.$img_glry_dir.$img_files[$k]['file_name'].'"><img  width="100px" height="100px" id="proj_sign_img_field" src="'.$img_glry_dir.$img_files[$k]['file_name'].'"/></a>';     
 }   
 else{
  $size = getimagesize($img_glry_dir. $img_files[$k]['file_name']);    
     $html.= '<img';
     if($size[0]>600) $html.=' width="600px" ';    
   if($size[1]>480) $html.=' height="480px" ';
   $html.=' src="'.$img_glry_dir. $img_files[$k]['file_name'] . '" />';
 }
   $html.='</td></tr><tr><td>&nbsp;</td></tr>';

            }

?>