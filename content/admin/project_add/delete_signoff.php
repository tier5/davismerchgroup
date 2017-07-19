<?php
require('Application.php');
$query='delete from prj_signoff_clients where sign_id='.$_POST['sign_id'];
pg_query($connection,$query);
?>