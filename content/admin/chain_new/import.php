<?php
require('Application.php');
require('../../header.php');

header('Content-Type: text/html; charset=UTF-8');

if(isset($_POST["Import"])){

	$filename=$_FILES["file"]["tmp_name"];		

	if($_FILES["file"]["size"] > 0)
	{
		$file = fopen($filename, "r");
		$i = 0;

		$queryapp1="INSERT INTO tbl_chain (chain, status) VALUES ('Quik Trip','1') RETURNING *";

		if(!($resultapp1=pg_query($connection,$queryapp1))){
			print("Failed resultapp1: " . pg_last_error($connection));
			exit;
		}
		$rowapp1 = pg_fetch_array($resultapp1);

		while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
		{
			$i++;

			//echo "<pre>";
			//echo $i;
			if($i>1){
				
				$ex = explode(",", $getData[2]);
				
				$state_zip = explode(" ", $ex[2]);


				$st_name = $rowapp1['ch_id'];
				$store = $getData[1];
				$address =  $ex[0];
				$city = $ex[1];
				$state = $state_zip[1];
				$zip = $state_zip[2];


				$sql="INSERT INTO tbl_chainmanagement (";
		
				if(isset($st_name) && $st_name!="")
				$sql.='"sto_name"';
				if(isset($store) && $store!="")
				$sql.=', "sto_num"';		
				if(isset($address) && $address!="")
				$sql.=', "address"';		
				if(isset($city) && $city!="")
				$sql.=', "city"';
				if(isset($state) && $state!="")
				$sql.=', "state"';
				if(isset($zip) && $zip!="")
				$sql.=', "zip"';
				if(isset($phone) && $phone!="")
				$sql.=', "phone"';
				if(isset($fax) && $fax!="")
				$sql.=', "fax"';
				
				
				$sql.=")";
				$sql.=" VALUES (";
				if(isset($st_name) && $st_name!="")
				$sql.="'".trim($st_name)."'";
				if(isset($store) && $store!="")
				$sql.=" ,'".trim($store)."'";		
				if(isset($address) && $address!="")
				$sql.=" ,'".trim($address)."'";
				if(isset($city) && $city!="")
				$sql.=" ,'".trim($city)."'";
				if(isset($state) && $state!="")
				$sql.=" ,'".trim($state)."'";
				if(isset($zip) && $zip!="")
				$sql.=" ,'".trim($zip)."'";
				if(isset($phone) && $phone!="")
				$sql.=" ,'".trim($phone)."'";
				if(isset($fax) && $fax!="")
				$sql.=" ,'".$fax."'";
				
				
				$sql.=" )";

				if ($sql != '')
				{
					if(!($result=pg_query($connection,$sql)))
					{
						echo "Error :".pg_last_error($connection);
						return;
					}
					pg_free_result($result);
				}

			}
			
		} //endwhile

		fclose($file);	
	}
}	 
?>
<form class="form-horizontal" method="post" name="upload_excel" enctype="multipart/form-data">
    <fieldset>

        <!-- Form Name -->
        <legend>Import chain list</legend>

        <!-- File Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="filebutton">Select File</label>
            <div class="col-md-4">
                <input type="file" name="file" id="file" class="input-large">
            </div>
        </div>

        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="singlebutton">Import data</label>
            <div class="col-md-4">
                <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
            </div>
        </div>

    </fieldset>
</form>
<?php
require('../../trailer.php');
?>