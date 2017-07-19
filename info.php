<?php
require('Application.php');
$sql='SELECT version()';
if(!($resultp=pg_query($connection,$sql))){
	print("Failed queryd: " . pg_last_error($connection));
	exit;
}
$row=  pg_fetch_array($resultp);
echo 'ver->';
print_r($row);

?>
