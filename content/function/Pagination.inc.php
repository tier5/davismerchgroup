<?php
error_reporting(0);

class CommonUtilities {	
	/*****************************************************************************/
	/* FUNCTION:   Pagination	    				     			     		 */
	/*                                                                			 */
	/* PURPOSE:    To avail Pagination                                           */
	/*                                                                           */
	/* PARAMETERS:   $strURl, $totaljobs, $strQueryString, $limit                */
	/*     INPUT:    $strURl, $totaljobs, $strQueryString, $limit				 */
	/*     OUTPUT:   Printing the links              	                         */
	/*                                                                           */
	/* SPECIFICATION: Pagination()                                               */
	/*                                                                           */
	/* CALLED BY:      When user wants to avail pagination in a page             */
	/*                 Example:                                                  */
	/*                           $strURl-->In which URL pagination needed        */
	/*							 $totaljobs-->use mysql_num_rows() to get Tot rds*/
	/*                           $strQueryString-->assign $_REQUEST to this arr  */
	/*                           $limit-->How many links to display in a page    */
	/*								$limit = 5; //Link per page                  */
	/*                              $intlimit = 30; //How much record to fetch   */
	/*                          if (isset($_GET["Start"]) & $_GET["Start"]!=1) { */
	/*                               $intFrom = $intlimit*($_GET["Start"]-1);    */
	/*                          }else {                                          */
	/*                               $intFrom = 0; //From Record                 */
	/*                          }                                                */
	/*                         $strQuery = "SELECT * FROM <tablename>            */
	/*                         limit $intFrom, $intlimit";                       */
	/*                           $strResult = mysql_query($strQuery);            */
	/*                    while( $strRowValue = mysql_fetch_array($strResult) ) {*/
	/*                      	$messagelist[] = $strRowValue;                   */
	/*                    }                                                      */
	/*                    $query = "SELECT <field> FROM <tablename>";            */
	/*                    $res = mysql_query($query);                            */
	/*                    $totaljobs = mysql_num_rows($res);                     */
	/*       $strURl = "https://localhost/xampp/php/swofs/swofs-pagination.php"; */
	/*       $strQueryString = $_GET;                                            */
	/*       Pagination( $strURl, $totaljobs, $strQueryString, $limit );         */
	/*****************************************************************************/
	public function Pagination( $strURl, $totaljobs, $strQueryString, $limit ) {
		unset($strQueryString["Start"]);
		unset($strQueryString["Index"]);
		if (count($strQueryString)>0) {
			$strURl = $strURl."?".http_build_query($strQueryString);
			$strQuQsAppend = "&";
		}else {
			$strQuQsAppend = "?";
		}
		echo "<table><tr> ";
		$inttotalrecords = $totaljobs / $limit;
		$intremainder = $totaljobs % $limit;
		if ($intremainder != 0)
		{
			$inttotalrecords =$inttotalrecords +1;
		}
		$inttotalrecords = (int) $inttotalrecords;
		$ParentArr = array();
		for ($j=1;$j<=$inttotalrecords;$j++) {
			$intremainder = $j % $limit;
			$intAppend = ($j-1)*$limit;
			$ChildArr[] = $j;
			if ($intremainder == 0 & $j!=0) {
				array_push($ParentArr,$ChildArr);
				unset($ChildArr);
			} elseif ($j == $inttotalrecords) {
				array_push($ParentArr,$ChildArr);
				unset($ChildArr);
			}
		}
		(isset($_GET["Index"]) & !empty($_GET["Index"]))?$k=$_GET["Index"]:$k=0;
		$ParentCount = count($ParentArr);
		for ($k=$k;$k<=$ParentCount;$k++) {
			$InnerCount = count($ParentArr[$k]);
			(isset($k) & $k==0)?$index=0:$index=$k-1;
			//Displaying Image when the record is not in the first link
			if ($_GET['Index'] > 0) {
				$intPreviousNumber = $ParentArr[$index][0];
				print "<td><a href='$strURl{$strQuQsAppend}Start=$intPreviousNumber&Index=$index'><img src='../../function/first.gif' border='0' alt='Previous $limit Pages' /></a></td>";
			}
			if ($_GET["Start"]!=1 & isset($_GET["Start"])) {
				$intPrevValue = $_GET["Start"]-1;
				//Taking index
				$intPageNumber = $ParentArr[$k][0];
				(isset($_GET["Start"]) & $_GET["Start"]==$intPageNumber)?$intPrevIndex=$_GET["Index"]-1:$intPrevIndex=$_GET["Index"];
				print "<td><a href='$strURl{$strQuQsAppend}Start=$intPrevValue&Index=$intPrevIndex'><img src='../../function/previous.gif' border='0' alt='Previous Page' /></a></td>";
			}
			for ($m=0;$m<$InnerCount;$m++) {
				$intPageNo = $ParentArr[$k][$m];
				$page="$strURl&Index=$k&Start";
				//Getting default starting value
				(isset($_GET["Start"]) & !empty($_GET["Start"]))?$start=$_GET["Start"]:$start=1;
				if ($intPageNo == $start) {
					echo "<td><b>$intPageNo</b></td>";
					$intNextLink = $intPageNo;
					//Checking Next Index existing or Not
					$nextM = $m+1;
					$intChkNextIndex = $ParentArr[$k][$nextM];
					(!empty($intChkNextIndex))?$intNextIndex=$k:$intNextIndex=($k+1);
				} else {
					echo "<td><a href='$strURl{$strQuQsAppend}Start=$intPageNo&Index=$k'>$intPageNo</a></td>";
				}
			}
			if (isset($ParentArr[$intNextIndex][0])) {
				$StartValue = $intNextLink+1;
				print "<td><a href='$strURl{$strQuQsAppend}Start=$StartValue&Index=$intNextIndex'><img src='../../function/next.gif' border='0' alt='Next Page' /></a></td>";
			}
			//To fetch next arrays first index
			$getLastIndex = $_GET["Index"]+1;
			if (isset($ParentArr[$getLastIndex])) {
				//Point to next arrays first index
				$intNextNumber = $ParentArr[$getLastIndex][0];
				print "<td><a href='$strURl{$strQuQsAppend}Start=$intNextNumber&Index=$getLastIndex'><img src='../../function/last.gif' border='0' alt='Next $limit Pages' /></a></td>";
			}
			break;
		}
		echo "</tr></table>";
	}
}
$objEmail = new CommonUtilities();
?>