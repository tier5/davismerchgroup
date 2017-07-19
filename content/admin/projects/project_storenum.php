<?php
require('Application.php');

extract($_POST);

echo '<option value=""></option>';
$sql="select * from \"tbl_chainmanagement\"  where sto_name =".$chain." order by sto_num asc";
//echo $sql;
if(!($result=pg_query($connection,$sql))){
	print("Failed query: " . pg_last_error($connection));
	exit;
}
	while($row=pg_fetch_array($result))
	{
		
		echo '<option value="'.$row['chain_id'].'">'.$row['sto_num'].'</option>';
	}
	pg_free_result($result);


?>