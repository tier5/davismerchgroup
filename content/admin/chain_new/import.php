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
		while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
		{
			$i++;

			//echo "<pre>";
			//echo $i;
			if($i>2){
				//print_r($getData);
				$st_name = "64";
				$store = $getData[0];
				$address = $getData[1];
				$city = $getData[3];
				$state = $getData[4];

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