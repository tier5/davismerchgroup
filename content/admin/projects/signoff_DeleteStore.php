<?php
require 'Application.php';
if(isset($_POST['ss_it_id'])&&$_POST['ss_it_id']>0)
{
 $query='delete from ssr_form_item where ss_it_id='.$_POST['ss_it_id'];
 pg_query($connection, $query);
}
?>