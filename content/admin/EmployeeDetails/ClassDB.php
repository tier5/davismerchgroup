<?php
class ClassDB
{
	function SelectTable($table ,$aryField,$aryWhere,$arySort,$srt,$lmt,$offset)
	{
		$whe="";
		$sot="";
		if(count($aryWhere)!=0)
		{
			if(count($aryWhere)==1)
			{
				$whe=" Where ".$aryWhere[0];
			}
			else
			{
				$WH=array_filter($aryWhere);
				$whVal = implode(" AND ", $WH);
			 	$whe=" WHERE ".$whVal;
			}
		}
		else
		{
			$whe="";
		}
		if(count($arySort)!=0)
		{
			if($srt==1)
			{
				if(count($arySort)==1)
				{
					$sot=" ORDER BY \"".$arySort[0]."\" ASC";
				}
				else
				{
					$ST=array_filter($arySort);
					$stVal = implode(" AND ", "\"$ST\"");
				 	$sot=" ORDER BY ".$stVal;
				}
			}
			else if($srt==0)
			{
			if(count($arySort)==1)
				{
					$sot=" ORDER BY \"".$arySort[0]."\" DESC";
				}
				else
				{
					$ST=array_filter($arySort);
					$stVal = implode(" AND ", "\"$ST\"");
				 	$sot=" ORDER BY ".$stVal;
				}
			}
			else
			{
				$sot="";
			}
		}
		else
		{
			$sot="";
		}
		if($lmt!=0)
		{
			$l=" LIMIT ".$lmt ." OFFSET ".$offset;
		}
		else
		{
			$l="";
		}
		
		$query="SELECT ";
			for($i=0;$i<count($aryField);$i++)
			{
				if($i==count($aryField)-1)
				{
					$query.="\"$aryField[$i]\"";
				}
				else
				{
					$query.="\"$aryField[$i]\",";
				}				
			}
			$query.=" FROM ".$table .$whe.$sot.$l.";";
			//echo $query;
			return $query;
	}
	function InsertTable($table ,$aryField,$aryValue)
	{
		
		$query="INSERT INTO $table ( ";
		
		if(count($aryField)==count($aryValue))
		{
			for($i=0;$i<count($aryField);$i++)
			{
				if($i==count($aryField)-1)
				{
					$query.="\"$aryField[$i]\"";
				}
				else
				{
					$query.="\"$aryField[$i]\",";
				}				
			}
			$query.=") VALUES (";
			for($j=0;$j<count($aryValue);$j++)
			{
				
				if($j==count($aryValue)-1)
				{
					if($aryValue[$j]=="''" ||$aryValue[$j]==''||$aryValue[$j]=='---Select---')
					{
						$query.="null";
					}
					else
					{
						$query.=$aryValue[$j];
					}
				}
				else
				{
					if($aryValue[$j]=="''" ||$aryValue[$j]==''||$aryValue[$j]=='---Select---' )
					{
						$query.="null,";
					}
					else
					{
						$query.=$aryValue[$j].",";
					}
				}				
			}
			$query.=");";
			
		}
	//echo $query;
		return $query;		
	}
	
	function UpdateTable($table,$aryField,$aryValue,$aryWhere)
	{
	if(count($aryWhere)!=0)
		{
			if(count($aryWhere)==1)
			{
				$whe=" Where ".$aryWhere[0];
			}
			else
			{
				$WH=array_filter($aryWhere);
				$whVal = implode(" AND ", $WH);
			 	$whe=" WHERE ".$whVal;
			}
		}
		else
		{
			$whe="";
		}
		$query="Update $table SET ";
	for($j=0;$j<count($aryValue);$j++)
			{
				
				if($j==count($aryValue)-1)
				{
					if($aryValue[$j]=="''" ||$aryValue[$j]==''||$aryValue[$j]=='---Select---')
					{
						$query.="\"$aryField[$j]\" = null";
					}
					else
					{
						$query.="\"$aryField[$j]\" =$aryValue[$j] ";
					}
				}
				else
				{
					if($aryValue[$j]=="''" ||$aryValue[$j]==''||$aryValue[$j]=='---Select---' )
					{
						$query.="\"$aryField[$j]\" =null,";
					}
					else
					{
						$query.="\"$aryField[$j]\" =$aryValue[$j], ";
					}
				}				
			}
		
			$query.=" $whe".";";	
		//echo $query;
		return $query;		
	}
	function getResult($con,$sql)
	{
			$result = pg_query($con, $sql);
			$arr = pg_fetch_array($result, $i,PGSQL_NUM);
			return $arr;
	}
function getResultMulti($con,$sql)
	{
		//echo $sql;
			$result = pg_query($con, $sql);
		$rows = pg_num_rows($result);
			for($i=0;$i<$rows;$i++)
			{
			
				$arr = pg_fetch_array($result, $i,PGSQL_NUM);
				$aryData[$i]=$arr;		
			}
			return $aryData;
	}
	
	function getData($con,$sql,$result_type)
	{
		$result = pg_query($con, $sql);
		$rows = pg_num_rows($result);
		$num = pg_num_fields($result);
		$aryData=array();
		for($i=0;$i<$rows;$i++)
		{
			IF($result_type==1)
			{
				$arr = pg_fetch_array($result, $i,PGSQL_ASSOC);
			}
			ELSE IF($result_type==2)
			{
				$arr = pg_fetch_array($result, $i,PGSQL_NUM);
			}
			ELSE
			{
				
			}
			$aryData[$i]=$arr;		
		}	
		return $aryData;
	}
	function insertData($con,$tbl,$aryCol,$aryValue)
	{
		$return_arr['error'] = "";
		$query= $this->InsertTable($tbl,$aryCol,$aryValue);
		$result="";
		try 
		{
			$result=pg_query($con,$query);				
		} 
		catch (Exception $e) 
		{
			$return_arr['error'] = "Error while storing Item information to database!";	
		}		
		pg_free_result($result);	
		return $result;
	}
function updateData($con,$tbl,$aryCol,$aryValue,$whe)
	{
		$return_arr['error'] = "";
		$query= $this->UpdateTable($tbl,$aryCol,$aryValue,$whe);
		$result="";
		try 
		{
			$result=pg_query($con,$query);				
		} 
		catch (Exception $e) 
		{
			$return_arr['error'] = "Error while storing Item information to database!";	
		}		
		pg_free_result($result);	
		//echo json_encode($return_arr);
		return;
	}
	function makeCondition($where,$condition,$val)
	{
		$str="";
		if($condition!='')
		{
			If($val==1)
			{
				$str=$where."='".$condition."'";
			}
			else if($val==2)
			{
				$str=$where."like %".$condition."%";
			}
			else if($val==3)
			{
				if($condition=='---Select---')
				{
					$str="";
				}
				else
				{
					$str=$where."='".$condition."'";
				}
			}
		}
		else
		{
			$str="";
		}
		return $str;
	}
	
}
?>
