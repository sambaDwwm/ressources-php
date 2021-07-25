<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/monthly_variables.php");

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
$smarty->display("monthly_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=monthly.xls");

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
	header("Content-Disposition: attachment;Filename=monthly.doc");

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
	header("Content-Disposition: attachment;Filename=monthly.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("Date"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"DateField",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Description"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Description",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Subject"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Theme",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Time"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"TimeField",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("EndTime"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"EndTime",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Whole Day Event"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"DayEvent",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("EndDate"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"EndDate",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Period"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Period",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Recurrence"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Recurrence",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("Category"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"Category",""));
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
	header("Content-Disposition: attachment;Filename=monthly.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Date\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Description\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Subject\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Time\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"EndTime\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Whole Day Event\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"EndDate\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Period\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Recurrence\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"Category\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"details\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"income\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"outcome\"";
	echo $outstr;
	echo "\r\n";

// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		$outstr="";
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"DateField",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Description",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Theme",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"TimeField",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"EndTime",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"DayEvent",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"EndDate",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Period",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Recurrence",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"Category",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"details",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"income",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"outcome",$format)).'"';
		echo $outstr;
		echo "\r\n";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}

//	display totals
	$first=true;

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
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Date").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Description").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Subject").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Time").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("EndTime").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Whole Day Event").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("EndDate").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Period").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Recurrence").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("Category").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("details").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("income").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("outcome").'</td>';
	}
	else
	{
		echo "<td>Date</td>";
		echo "<td>Description</td>";
		echo "<td>Subject</td>";
		echo "<td>Time</td>";
		echo "<td>EndTime</td>";
		echo "<td>Whole Day Event</td>";
		echo "<td>EndDate</td>";
		echo "<td>Period</td>";
		echo "<td>Recurrence</td>";
		echo "<td>Category</td>";
		echo "<td>details</td>";
		echo "<td>income</td>";
		echo "<td>outcome</td>";
	}
	echo "</tr>";

	$totals=array();
// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		echo "<tr>";
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"DateField",$format));
		else
			echo htmlspecialchars(GetData($row,"DateField",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Description",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Theme",$format));
		else
			echo htmlspecialchars(GetData($row,"Theme",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"TimeField",$format));
		else
			echo htmlspecialchars(GetData($row,"TimeField",$format));
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

		$format="";
			echo htmlspecialchars(GetData($row,"DayEvent",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"EndDate",$format));
		else
			echo htmlspecialchars(GetData($row,"EndDate",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"Period",$format));
		else
			echo htmlspecialchars(GetData($row,"Period",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Recurrence",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"Category",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"details",$format));
		else
			echo htmlspecialchars(GetData($row,"details",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"income",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"outcome",$format));
	echo '</td>';
		echo "</tr>";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}

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
		$this->Rect($x,$this->y0,$colwidth["DateField"],$this->maxheight);
		$x+=$colwidth["DateField"];
		$this->Rect($x,$this->y0,$colwidth["Description"],$this->maxheight);
		$x+=$colwidth["Description"];
		$this->Rect($x,$this->y0,$colwidth["Theme"],$this->maxheight);
		$x+=$colwidth["Theme"];
		$this->Rect($x,$this->y0,$colwidth["TimeField"],$this->maxheight);
		$x+=$colwidth["TimeField"];
		$this->Rect($x,$this->y0,$colwidth["EndTime"],$this->maxheight);
		$x+=$colwidth["EndTime"];
		$this->Rect($x,$this->y0,$colwidth["DayEvent"],$this->maxheight);
		$x+=$colwidth["DayEvent"];
		$this->Rect($x,$this->y0,$colwidth["EndDate"],$this->maxheight);
		$x+=$colwidth["EndDate"];
		$this->Rect($x,$this->y0,$colwidth["Period"],$this->maxheight);
		$x+=$colwidth["Period"];
		$this->Rect($x,$this->y0,$colwidth["Recurrence"],$this->maxheight);
		$x+=$colwidth["Recurrence"];
		$this->Rect($x,$this->y0,$colwidth["Category"],$this->maxheight);
		$x+=$colwidth["Category"];
		$this->Rect($x,$this->y0,$colwidth["details"],$this->maxheight);
		$x+=$colwidth["details"];
		$this->Rect($x,$this->y0,$colwidth["income"],$this->maxheight);
		$x+=$colwidth["income"];
		$this->Rect($x,$this->y0,$colwidth["outcome"],$this->maxheight);
		$x+=$colwidth["outcome"];
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
		$this->Cell($colwidth["DateField"],$this->rowheight,"Date",1,0,'C',1);
		$this->Cell($colwidth["Description"],$this->rowheight,"Description",1,0,'C',1);
		$this->Cell($colwidth["Theme"],$this->rowheight,"Subject",1,0,'C',1);
		$this->Cell($colwidth["TimeField"],$this->rowheight,"Time",1,0,'C',1);
		$this->Cell($colwidth["EndTime"],$this->rowheight,"EndTime",1,0,'C',1);
		$this->Cell($colwidth["DayEvent"],$this->rowheight,"Whole Day Event",1,0,'C',1);
		$this->Cell($colwidth["EndDate"],$this->rowheight,"EndDate",1,0,'C',1);
		$this->Cell($colwidth["Period"],$this->rowheight,"Period",1,0,'C',1);
		$this->Cell($colwidth["Recurrence"],$this->rowheight,"Recurrence",1,0,'C',1);
		$this->Cell($colwidth["Category"],$this->rowheight,"Category",1,0,'C',1);
		$this->Cell($colwidth["details"],$this->rowheight,"details",1,0,'C',1);
		$this->Cell($colwidth["income"],$this->rowheight,"income",1,0,'C',1);
		$this->Cell($colwidth["outcome"],$this->rowheight,"outcome",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/13;
	$colwidth=array();
    $colwidth["DateField"]=$defwidth;
    $colwidth["Description"]=$defwidth;
    $colwidth["Theme"]=$defwidth;
    $colwidth["TimeField"]=$defwidth;
    $colwidth["EndTime"]=$defwidth;
    $colwidth["DayEvent"]=$defwidth;
    $colwidth["EndDate"]=$defwidth;
    $colwidth["Period"]=$defwidth;
    $colwidth["Recurrence"]=$defwidth;
    $colwidth["Category"]=$defwidth;
    $colwidth["details"]=$defwidth;
    $colwidth["income"]=$defwidth;
    $colwidth["outcome"]=$defwidth;
	
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
		$pdf->MultiCell($colwidth["DateField"],$rowheight,GetData($row,"DateField","Short Date"));
		$x+=$colwidth["DateField"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Description"],$rowheight,GetData($row,"Description",""));
		$x+=$colwidth["Description"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Theme"],$rowheight,GetData($row,"Theme",""));
		$x+=$colwidth["Theme"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["TimeField"],$rowheight,GetData($row,"TimeField",""));
		$x+=$colwidth["TimeField"];
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
		$pdf->MultiCell($colwidth["DayEvent"],$rowheight,GetData($row,"DayEvent",""));
		$x+=$colwidth["DayEvent"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["EndDate"],$rowheight,GetData($row,"EndDate","Short Date"));
		$x+=$colwidth["EndDate"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Period"],$rowheight,GetData($row,"Period",""));
		$x+=$colwidth["Period"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Recurrence"],$rowheight,GetData($row,"Recurrence",""));
		$x+=$colwidth["Recurrence"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["Category"],$rowheight,GetData($row,"Category",""));
		$x+=$colwidth["Category"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["details"],$rowheight,GetData($row,"details",""));
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
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["DateField"],$pdf->maxheight);
		$x+=$colwidth["DateField"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Description"],$pdf->maxheight);
		$x+=$colwidth["Description"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Theme"],$pdf->maxheight);
		$x+=$colwidth["Theme"];
		$pdf->Rect($x,$pdf->y0,$colwidth["TimeField"],$pdf->maxheight);
		$x+=$colwidth["TimeField"];
		$pdf->Rect($x,$pdf->y0,$colwidth["EndTime"],$pdf->maxheight);
		$x+=$colwidth["EndTime"];
		$pdf->Rect($x,$pdf->y0,$colwidth["DayEvent"],$pdf->maxheight);
		$x+=$colwidth["DayEvent"];
		$pdf->Rect($x,$pdf->y0,$colwidth["EndDate"],$pdf->maxheight);
		$x+=$colwidth["EndDate"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Period"],$pdf->maxheight);
		$x+=$colwidth["Period"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Recurrence"],$pdf->maxheight);
		$x+=$colwidth["Recurrence"];
		$pdf->Rect($x,$pdf->y0,$colwidth["Category"],$pdf->maxheight);
		$x+=$colwidth["Category"];
		$pdf->Rect($x,$pdf->y0,$colwidth["details"],$pdf->maxheight);
		$x+=$colwidth["details"];
		$pdf->Rect($x,$pdf->y0,$colwidth["income"],$pdf->maxheight);
		$x+=$colwidth["income"];
		$pdf->Rect($x,$pdf->y0,$colwidth["outcome"],$pdf->maxheight);
		$x+=$colwidth["outcome"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>