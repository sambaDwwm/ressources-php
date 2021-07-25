<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/task_statistics_variables.php");

if(!@$_SESSION["UserID"])
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Export"))
{
	echo "<p>"."You don't have permissions to access this table"."<a href=\"login.php\">"."Back to login page"."</a></p>";
	return;
}

$conn=db_connect();
//	Before Process event
if(function_exists("BeforeProcessExport"))
	BeforeProcessExport($conn);

$strWhereClause="";

$options = "1";
if (@$_REQUEST["a"]!="") 
{
	$options = "";
	
	$sWhere = "1=0";	

//	process selection
	$selected_recs=array();
	if (@$_REQUEST["mdelete"])
	{
		foreach(@$_REQUEST["mdelete"] as $ind)
		{
			$keys=array();
			$keys["id"]=refine($_REQUEST["mdelete1"][$ind-1]);
			$selected_recs[]=$keys;
		}
	}
	elseif(@$_REQUEST["selection"])
	{
		foreach(@$_REQUEST["selection"] as $keyblock)
		{
			$arr=split("&",refine($keyblock));
			if(count($arr)<1)
				continue;
			$keys=array();
			$keys["id"]=urldecode($arr[0]);
			$selected_recs[]=$keys;
		}
	}

	foreach($selected_recs as $keys)
	{
		$sWhere = $sWhere . " or ";
		$sWhere.=KeyWhere($keys);
	}

	$sWhere = whereAdd($sWhere,SecuritySQL("Search"));

	$strSQL = gSQLWhere($sWhere);
	$strWhereClause=$sWhere;
	
	$_SESSION[$strTableName."_SelectedSQL"] = $strSQL;
	$_SESSION[$strTableName."_SelectedWhere"] = $sWhere;
}

if ($_SESSION[$strTableName."_SelectedSQL"]!="" && @$_REQUEST["records"]=="") 
{
	$strSQL = $_SESSION[$strTableName."_SelectedSQL"];
	$strWhereClause=@$_SESSION[$strTableName."_SelectedWhere"];
}
else
{
	$strWhereClause=@$_SESSION[$strTableName."_where"];
	if($strWhereClause=="")
		$strWhereClause = whereAdd($strWhereClause,SecuritySQL("Search"));
	$strSQL=gSQLWhere($strWhereClause);
}


$mypage=1;
if(@$_REQUEST["type"])
{
//	order by
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);

	$strSQLbak = $strSQL;
	if(function_exists("BeforeQueryExport"))
		BeforeQueryExport($strSQL,$strWhereClause,$strOrderBy);
//	Rebuild SQL if needed
	if($strSQL!=$strSQLbak)
	{
//	changed $strSQL - old style	
		$numrows=GetRowCount($strSQL);
	}
	else
	{
		$strSQL = gSQLWhere($strWhereClause);
		$strSQL.=" ".trim($strOrderBy);
		$numrows=gSQLRowCount($strWhereClause);
	}
	LogInfo($strSQL);

//	 Pagination:

	$nPageSize=0;
	if(@$_REQUEST["records"]=="page" && $numrows)
	{
		$mypage=(integer)@$_SESSION[$strTableName."_pagenumber"];
		$nPageSize=(integer)@$_SESSION[$strTableName."_pagesize"];
		if($numrows<=($mypage-1)*$nPageSize)
			$mypage=ceil($numrows/$nPageSize);
		if(!$nPageSize)
			$nPageSize=$gPageSize;
		if(!$mypage)
			$mypage=1;

		$strSQL.=" limit ".(($mypage-1)*$nPageSize).",".$nPageSize;
	}
	$rs=db_query($strSQL,$conn);

	if(!ini_get("safe_mode"))
		set_time_limit(300);
	
	if(@$_REQUEST["type"]=="excel")
		ExportToExcel();
	else if(@$_REQUEST["type"]=="word")
		ExportToWord();
	else if(@$_REQUEST["type"]=="xml")
		ExportToXML();
	else if(@$_REQUEST["type"]=="csv")
		ExportToCSV();
	else if(@$_REQUEST["type"]=="pdf")
		ExportToPDF();

	db_close($conn);
	return;
}

header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 

include('libs/Smarty.class.php');
$smarty = new Smarty();
$smarty->assign("options",$options);
$smarty->display("task_statistics_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=task_statistics.xls");

	echo "<html>";
	echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">";
	
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToWord()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-word");
	header("Content-Disposition: attachment;Filename=task_statistics.doc");

	echo "<html>";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToXML()
{
	global $nPageSize,$rs,$strTableName,$conn;
	header("Content-type: text/xml");
	header("Content-Disposition: attachment;Filename=task_statistics.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("id"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"id",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("End Time"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"EndTime",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Date"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"DateField2",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Time"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"TimeField",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Subject"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Theme",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("customer"));
		echo "<".$field.">";
		if(strlen($row["Description"]))
		{
			$strdata = make_db_value("Description",$row["Description"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`name`";
			$LookupSQL.=" FROM `customer` WHERE `idcustomer` = " . $strdata;
					$LookupSQL.=" and ("." idusercus = ".$_SESSION["OwnerID"].")"; 
			LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["Description"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"Description", ""));
		}
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("task"));
		echo "<".$field.">";
		if(strlen($row["Category"]))
		{
			$strdata = make_db_value("Category",$row["Category"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Category`";
			$LookupSQL.=" FROM `category` WHERE `id` = " . $strdata;
					$LookupSQL.=" and ("." idusercat = ".$_SESSION["OwnerID"].")"; 
			LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["Category"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			echo htmlspecialchars(GetDataInt($lookupvalue,$row,"Category", ""));
		}
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("details"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"details",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("income"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"income",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("outcome"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"outcome",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Color"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Color",""));
		echo "</".$field.">\r\n";
		echo "</row>\r\n";
		$i++;
		$row=db_fetch_array($rs);
	}
	echo "</table>\r\n";
}

function ExportToCSV()
{
	global $rs,$nPageSize,$strTableName,$conn;
	header("Content-type: application/csv");
	header("Content-Disposition: attachment;Filename=task_statistics.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();
	$totals["id"]=0;
	$totals["income"]=0;
	$totals["outcome"]=0;

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"id\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"End Time\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Date\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Time\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Subject\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"customer\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"task\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"details\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"income\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"outcome\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Color\"";
	echo $outstr;
	echo "\r\n";

// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
							$totals["id"]+= ($row["id"]!="");
							$totals["income"]+=($row["income"]+0);
							$totals["outcome"]+=($row["outcome"]+0);
		$outstr="";
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"id",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"EndTime",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"DateField2",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Time";
		$outstr.='"'.htmlspecialchars(GetData($row,"TimeField",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Custom";
		$outstr.='"'.htmlspecialchars(GetData($row,"Theme",$format)).'"';
		if($outstr!="")
			$outstr.=",";
		if(strlen($row["Description"]))
		{
			$strdata = make_db_value("Description",$row["Description"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`name`";
			$LookupSQL.=" FROM `customer` WHERE `idcustomer` = " . $strdata;
					$LookupSQL.=" and ("." idusercus = ".$_SESSION["OwnerID"].")"; 
			LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["Description"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"Description", "")).'"';
		}
		if($outstr!="")
			$outstr.=",";
		if(strlen($row["Category"]))
		{
			$strdata = make_db_value("Category",$row["Category"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Category`";
			$LookupSQL.=" FROM `category` WHERE `id` = " . $strdata;
					$LookupSQL.=" and ("." idusercat = ".$_SESSION["OwnerID"].")"; 
			LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);

			$lookupvalue=$row["Category"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$outstr.='"'.htmlspecialchars(GetDataInt($lookupvalue,$row,"Category", "")).'"';
		}
		if($outstr!="")
			$outstr.=",";
			$format="HTML";
		$outstr.='"'.htmlspecialchars(GetData($row,"details",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"income",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"outcome",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Color",$format)).'"';
		echo $outstr;
		echo "\r\n";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}

//	display totals
	$first=true;
	if(!$first)
		echo ",";
	else
		$first=false;
	echo "\"";
						echo "Count".": ";
	echo htmlspecialchars(GetTotals("id",$totals["id"],"COUNT",$iNumberOfRows,""));
	echo "\"";
	if(!$first)
		echo ",";
	else
		$first=false;
	echo "\"";
		echo "\"";
	if(!$first)
		echo ",";
	else
		$first=false;
	echo "\"";
		echo "\"";
	if(!$first)
		echo ",";
	else
		$first=false;
	echo "\"";
		echo "\"";
	if(!$first)
		echo ",";
	else
		$first=false;
	echo "\"";
		echo "\"";
	if(!$first)
		echo ",";
	else
		$first=false;
	echo "\"";
		echo "\"";
	if(!$first)
		echo ",";
	else
		$first=false;
	echo "\"";
		echo "\"";
	if(!$first)
		echo ",";
	else
		$first=false;
	echo "\"";
		echo "\"";
	if(!$first)
		echo ",";
	else
		$first=false;
	echo "\"";
						echo "Total".": ";
	echo htmlspecialchars(GetTotals("income",$totals["income"],"TOTAL",$iNumberOfRows,""));
	echo "\"";
	if(!$first)
		echo ",";
	else
		$first=false;
	echo "\"";
						echo "Total".": ";
	echo htmlspecialchars(GetTotals("outcome",$totals["outcome"],"TOTAL",$iNumberOfRows,""));
	echo "\"";
	if(!$first)
		echo ",";
	else
		$first=false;
	echo "\"";
		echo "\"";
	echo "\r\n";

}


function WriteTableData()
{
	global $rs,$nPageSize,$strTableName,$conn;
	if(!($row=db_fetch_array($rs)))
		return;
// write header
	echo "<tr>";
	if($_REQUEST["type"]=="excel")
	{
		echo '<td style="width: 100" x:str>'.PrepareForExcel("id").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("End Time").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Date").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Time").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Subject").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("customer").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("task").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("details").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("income").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("outcome").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Color").'</td>';
	}
	else
	{
		echo "<td>id</td>";
		echo "<td>End Time</td>";
		echo "<td>Date</td>";
		echo "<td>Time</td>";
		echo "<td>Subject</td>";
		echo "<td>customer</td>";
		echo "<td>task</td>";
		echo "<td>details</td>";
		echo "<td>income</td>";
		echo "<td>outcome</td>";
		echo "<td>Color</td>";
	}
	echo "</tr>";

	$totals=array();
	$totals["id"]=0;
	$totals["income"]=0;
	$totals["outcome"]=0;
// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
							$totals["id"]+= ($row["id"]!="");
							$totals["income"]+=($row["income"]+0);
							$totals["outcome"]+=($row["outcome"]+0);
		echo "<tr>";
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"id",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"EndTime",$format));
		else
			echo htmlspecialchars(GetData($row,"EndTime",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"DateField2",$format));
		else
			echo htmlspecialchars(GetData($row,"DateField2",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="Time";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"TimeField",$format));
		else
			echo htmlspecialchars(GetData($row,"TimeField",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="Custom";
			echo GetData($row,"Theme",$format);
	echo '</td>';
	echo '<td>';
		if(strlen($row["Description"]))
		{
			$strdata = make_db_value("Description",$row["Description"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`name`";
			$LookupSQL.=" FROM `customer` WHERE `idcustomer` = " . $strdata;
					$LookupSQL.=" and ("." idusercus = ".$_SESSION["OwnerID"].")"; 
			LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["Description"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];

			$strValue=GetDataInt($lookupvalue,$row,"Description", "");
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	echo '<td>';
		if(strlen($row["Category"]))
		{
			$strdata = make_db_value("Category",$row["Category"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Category`";
			$LookupSQL.=" FROM `category` WHERE `id` = " . $strdata;
					$LookupSQL.=" and ("." idusercat = ".$_SESSION["OwnerID"].")"; 
			LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["Category"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];

			$strValue=GetDataInt($lookupvalue,$row,"Category", "");
						if($_REQUEST["type"]=="excel")
				echo PrepareForExcel($strValue);
			else
				echo htmlspecialchars($strValue);

		}
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="HTML";
			echo GetData($row,"details",$format);
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"income",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"outcome",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Color",$format));
		else
			echo htmlspecialchars(GetData($row,"Color",$format));
	echo '</td>';
		echo "</tr>";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}
	echo "<tr>";
	echo "<td>";
						echo "Count".": ";
	echo htmlspecialchars(GetTotals("id",$totals["id"],"COUNT",$iNumberOfRows,""));
	echo "</td>";
	echo "<td>";
		echo "</td>";
	echo "<td>";
		echo "</td>";
	echo "<td>";
		echo "</td>";
	echo "<td>";
		echo "</td>";
	echo "<td>";
		echo "</td>";
	echo "<td>";
		echo "</td>";
	echo "<td>";
		echo "</td>";
	echo "<td>";
						echo "Total".": ";
	echo htmlspecialchars(GetTotals("income",$totals["income"],"TOTAL",$iNumberOfRows,""));
	echo "</td>";
	echo "<td>";
						echo "Total".": ";
	echo htmlspecialchars(GetTotals("outcome",$totals["outcome"],"TOTAL",$iNumberOfRows,""));
	echo "</td>";
	echo "<td>";
		echo "</td>";
	echo "</tr>";

}

function XMLNameEncode($strValue)
{	
	$search=array(" ","#","'","/","\\","(",")",",","[","]","+","\"","-","_","|","}","{","=");
	return str_replace($search,"",$strValue);
}

function PrepareForExcel($str)
{
	$ret = htmlspecialchars($str);
	if (substr($ret,0,1)== "=") 
		$ret = "&#61;".substr($ret,1);
	return $ret;

}




function ExportToPDF()
{
	global $nPageSize,$rs,$strTableName,$conn;
		global $colwidth,$leftmargin;
	if(!($row=db_fetch_array($rs)))
		return;


	include("libs/fpdf.php");

	class PDF extends FPDF
	{
	//Current column
		var $col=0;
	//Ordinate of column start
		var $y0;
		var $maxheight;

	function AcceptPageBreak()
	{
		global $colwidth,$leftmargin;
		if($this->y0+$this->rowheight>$this->PageBreakTrigger)
			return true;
		$x=$leftmargin;
		if($this->maxheight<$this->PageBreakTrigger-$this->y0)
			$this->maxheight=$this->PageBreakTrigger-$this->y0;
		$this->Rect($x,$this->y0,$colwidth["id"],$this->maxheight);
		$x+=$colwidth["id"];
		$this->Rect($x,$this->y0,$colwidth["EndTime"],$this->maxheight);
		$x+=$colwidth["EndTime"];
		$this->Rect($x,$this->y0,$colwidth["DateField2"],$this->maxheight);
		$x+=$colwidth["DateField2"];
		$this->Rect($x,$this->y0,$colwidth["TimeField"],$this->maxheight);
		$x+=$colwidth["TimeField"];
		$this->Rect($x,$this->y0,$colwidth["Theme"],$this->maxheight);
		$x+=$colwidth["Theme"];
		$this->Rect($x,$this->y0,$colwidth["Description"],$this->maxheight);
		$x+=$colwidth["Description"];
		$this->Rect($x,$this->y0,$colwidth["Category"],$this->maxheight);
		$x+=$colwidth["Category"];
		$this->Rect($x,$this->y0,$colwidth["details"],$this->maxheight);
		$x+=$colwidth["details"];
		$this->Rect($x,$this->y0,$colwidth["income"],$this->maxheight);
		$x+=$colwidth["income"];
		$this->Rect($x,$this->y0,$colwidth["outcome"],$this->maxheight);
		$x+=$colwidth["outcome"];
		$this->Rect($x,$this->y0,$colwidth["Color"],$this->maxheight);
		$x+=$colwidth["Color"];
		$this->maxheight = $this->rowheight;
//	draw frame	
		return true;
	}

	function Header()
	{
		global $colwidth,$leftmargin;
	    //Page header
		$this->SetFillColor(192);
		$this->SetX($leftmargin);
		$this->Cell($colwidth["id"],$this->rowheight,"id",1,0,'C',1);
		$this->Cell($colwidth["EndTime"],$this->rowheight,"End Time",1,0,'C',1);
		$this->Cell($colwidth["DateField2"],$this->rowheight,"Date",1,0,'C',1);
		$this->Cell($colwidth["TimeField"],$this->rowheight,"Time",1,0,'C',1);
		$this->Cell($colwidth["Theme"],$this->rowheight,"Subject",1,0,'C',1);
		$this->Cell($colwidth["Description"],$this->rowheight,"customer",1,0,'C',1);
		$this->Cell($colwidth["Category"],$this->rowheight,"task",1,0,'C',1);
		$this->Cell($colwidth["details"],$this->rowheight,"details",1,0,'C',1);
		$this->Cell($colwidth["income"],$this->rowheight,"income",1,0,'C',1);
		$this->Cell($colwidth["outcome"],$this->rowheight,"outcome",1,0,'C',1);
		$this->Cell($colwidth["Color"],$this->rowheight,"Color",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/11;
	$colwidth=array();
    $colwidth["id"]=$defwidth;
    $colwidth["EndTime"]=$defwidth;
    $colwidth["DateField2"]=$defwidth;
    $colwidth["TimeField"]=$defwidth;
    $colwidth["Theme"]=$defwidth;
    $colwidth["Description"]=$defwidth;
    $colwidth["Category"]=$defwidth;
    $colwidth["details"]=$defwidth;
    $colwidth["income"]=$defwidth;
    $colwidth["outcome"]=$defwidth;
    $colwidth["Color"]=$defwidth;
	
	$pdf->AddFont('CourierNewPSMT','','courcp1252.php');
	$pdf->rowheight=$rowheight;
	
	$pdf->SetFont('CourierNewPSMT','',8);
	$pdf->AddPage();
	

	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		$pdf->maxheight=$rowheight;
		$x=$leftmargin;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["id"],$rowheight,GetData($row,"id",""));
		$x+=$colwidth["id"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["EndTime"],$rowheight,GetData($row,"EndTime",""));
		$x+=$colwidth["EndTime"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["DateField2"],$rowheight,GetData($row,"DateField2","Short Date"));
		$x+=$colwidth["DateField2"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["TimeField"],$rowheight,GetData($row,"TimeField","Time"));
		$x+=$colwidth["TimeField"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Theme"],$rowheight,GetData($row,"Theme","Custom"));
		$x+=$colwidth["Theme"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["Description"]))
		{
			$strdata = make_db_value("Description",$row["Description"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`name`";
			$LookupSQL.=" FROM `customer` WHERE `idcustomer` = " . $strdata;
					$LookupSQL.=" and ("." idusercus = ".$_SESSION["OwnerID"].")"; 
			LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["Description"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["Description"],$rowheight,GetDataInt($lookupvalue,$row,"Description", ""));
		}
		$x+=$colwidth["Description"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		if(strlen($row["Category"]))
		{
			$strdata = make_db_value("Category",$row["Category"]);
			$LookupSQL="SELECT ";
					$LookupSQL.="`Category`";
			$LookupSQL.=" FROM `category` WHERE `id` = " . $strdata;
					$LookupSQL.=" and ("." idusercat = ".$_SESSION["OwnerID"].")"; 
			LogInfo($LookupSQL);
			$rsLookup = db_query($LookupSQL,$conn);
			$lookupvalue=$row["Category"];
			if($lookuprow=db_fetch_numarray($rsLookup))
				$lookupvalue=$lookuprow[0];
			$pdf->Cell($colwidth["Category"],$rowheight,GetDataInt($lookupvalue,$row,"Category", ""));
		}
		$x+=$colwidth["Category"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["details"],$rowheight,GetData($row,"details","HTML"));
		$x+=$colwidth["details"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["income"],$rowheight,GetData($row,"income",""));
		$x+=$colwidth["income"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["outcome"],$rowheight,GetData($row,"outcome",""));
		$x+=$colwidth["outcome"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Color"],$rowheight,GetData($row,"Color",""));
		$x+=$colwidth["Color"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["id"],$pdf->maxheight);
		$x+=$colwidth["id"];
		$pdf->Rect($x,$pdf->y0,$colwidth["EndTime"],$pdf->maxheight);
		$x+=$colwidth["EndTime"];
		$pdf->Rect($x,$pdf->y0,$colwidth["DateField2"],$pdf->maxheight);
		$x+=$colwidth["DateField2"];
		$pdf->Rect($x,$pdf->y0,$colwidth["TimeField"],$pdf->maxheight);
		$x+=$colwidth["TimeField"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Theme"],$pdf->maxheight);
		$x+=$colwidth["Theme"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Description"],$pdf->maxheight);
		$x+=$colwidth["Description"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Category"],$pdf->maxheight);
		$x+=$colwidth["Category"];
		$pdf->Rect($x,$pdf->y0,$colwidth["details"],$pdf->maxheight);
		$x+=$colwidth["details"];
		$pdf->Rect($x,$pdf->y0,$colwidth["income"],$pdf->maxheight);
		$x+=$colwidth["income"];
		$pdf->Rect($x,$pdf->y0,$colwidth["outcome"],$pdf->maxheight);
		$x+=$colwidth["outcome"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Color"],$pdf->maxheight);
		$x+=$colwidth["Color"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>