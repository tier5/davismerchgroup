<?php
class ClassFunc
{
function date_picker($name, $startyear=NULL, $endyear=NULL)
{
    if($startyear==NULL) $startyear = date("Y");
    if($endyear==NULL) $endyear=date("Y")+20; 

    $months=array('','January','February','March','April','May',
    'June','July','August', 'September','October','November','December');

    // Month dropdown
    $html="<select name=\"".$name."month\">";

    for($i=1;$i<=12;$i++)
    {
       $html.="<option value='$i'>$months[$i]</option>";
    }
    $html.="</select> ";
   
    // Day dropdown
    $html.="<select name=\"".$name."day\">";
    for($i=1;$i<=31;$i++)
    {
       $html.="<option $selected value='$i'>$i</option>";
    }
    $html.="</select> ";

    // Year dropdown
    $html.="<select name=\"".$name."year\">";

    for($i=$startyear;$i<=$endyear;$i++)
    {      
      $html.="<option value='$i'>$i</option>";
    }
    $html.="</select> ";

    return $html;
}
function comboTag($name,$id,$value,$ary,$class)
	{
		$str="<select name='$name' id='$id' class='$class'>";
		if($value=='')
		{
			$str.="<option >---Select---</option>";
		}
		for($i=0;$i<count($ary);$i++)
		{
			$str.="<option value=";
			$str.=$ary[$i][0];
			if ($ary[$i][0] == $value) 
			{ 
				$str.= " selected"; 
			}
			$str.=">";
			$str.= $ary[$i][1];
			$str.="</option>";
		}
		$str.="</select>";
		return $str;
	}
function textTag($name,$id,$value,$class)
	{
		
		$str= "<input type=text  length=30 name='$name' id='$id' value='$value' class='$class'>";
		return $str;
	}
function submitButton($name,$value)
	{
		$str= "<input name='$name' type=submit";  
		$str.=" value=".$value;
		$str.=">";
		return $str;
    }
function radioTag($name,$id,$aryvalue,$aryName,$val,$checkVal)
	{
		$start= "<input type=radio name='$name' id='$id' ";//value='$value'";
		$str="";
		if(count($aryvalue)>0)
		{
			for($i=0;$i<count($aryvalue);$i++)
			{
				$str.=$start."value='".$aryvalue[$i]."'";
				if($val=='')
				{
					if($aryvalue[$i]==$checkVal)
					{
						$str.= "checked=checked />&nbsp;".$aryName[$i];	
					}
					else
					{
						$str.="/>&nbsp;".$aryName[$i];
					}
				}
				else if($val!='')
				{
					if($aryvalue[$i]==$val)
					{
						$str.= "checked=checked />&nbsp;$aryName[$i]";	
					}
					else
					{
						$str.="/>&nbsp;".$aryName[$i];
					}
				}
				else
				{
					$str.="/>&nbsp;".$aryName[$i];
				}
			}
			
		}
		
		return $str;
	}
	function textareaTag($name,$id,$value,$content,$cols,$rows,$js)
	{
		$str="";
		if($content!='')
		{
			$str="<textarea id='$id' name='$name' cols='$cols' rows='$rows' onBlur=\"javascript:$js;\">$content</textarea>";
		}
		else if($value!='')
		{
			$str="<textarea id='$id' name='$name' cols='$cols' rows='$rows' onBlur=\"javascript:$js;\">$value</textarea>";
		}
		else
		{
			$str="<textarea id='$id' name='$name' cols='$cols' rows='$rows' onBlur=\"javascript:$js;\">$value</textarea>";
		}
		return $str;
	}
	function ChangeToPost($ary)
	{
	
		for($i=0;$i<count($ary);$i++)
		{
			$r=$ary[$i];	
			if(substr($r, 0, 2)=='tx')
			{
				
				$ary[$i]="'".$_POST[$r]."'";
			}
			else if(substr($r, 0, 2)=='rd')
			{
				
				$ary[$i]=$_POST[$r];
			}
			else if(substr($r, 0, 2)=='cm')
			{
				
				$ary[$i]=$_POST[$r];
			}
			else
			{				
			}					
		}
		return $ary;
		
	}
function getEDTable($aryField,$ary2Value)//if not $ahref=100
	{	
		
			$str="<table width=100% border=0 cellspacing=1 cellpadding=1>";
			$str.= "<tr>";
			for($h=0;$h<count($aryField);$h++)
			{
				$str.= "<td height=25 class=grid001>$aryField[$h]</td>";
			}
			$str.= "</tr>";
		
			for($i=0;$i<count($ary2Value);$i++)
			{$str.= "<tr>";
				$str.= "<td height=25 class=gridVal>".$ary2Value[$i][1]."</td>";
				$str.= "<td height=25 class=gridVal>".$ary2Value[$i][2]."</td>";
                                $str.= "<td height=25 class=gridVal>".$ary2Value[$i][4]."</td>";
				$str.= "<td height=25 class=gridVal>".$ary2Value[$i][3]."</td>";
				$str.= "<td height=25 class=gridVal><a href=editApplication.php?iid=".$ary2Value[$i][0]."><img src=\"../../images/listviewEdit.png\" width=32 height=32></a></td>";	
				$str.= "<td height=25 class=gridVal><a href=deleteApplication.php?iid=".$ary2Value[$i][0]."><img src=\"../../images/1277880471_close.png\" width=32 height=32></a></td>";
				$str.= "</tr>";	
			}
			
			$str.= "</table>";
						
		return $str;
	}
}