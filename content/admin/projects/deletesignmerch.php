<?php
require 'Application.php';
$sql='delete from signmerch_list where id='.$_POST['id'];
$result = pg_query($connection, $sql);
?>