<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/customer_variables.php");

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
			$keys["idcustomer"]=refine($_REQUEST["mdelete1"][$ind-1]);
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
			$keys["idcustomer"]=urldecode($arr[0]);
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
$smarty->display("customer_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=customer.xls");

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
	header("Content-Disposition: attachment;Filename=customer.doc");

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
	header("Content-Disposition: attachment;Filename=customer.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("idcustomer"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"idcustomer",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("name"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"name",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("contact"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"contact",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("telephones"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"telephones",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("postinfo"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"postinfo",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("customerdetails"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"customerdetails",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("username"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"username",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("password"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"password",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("email"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"email",""));
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
	header("Content-Disposition: attachment;Filename=customer.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"idcustomer\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"name\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"contact\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"telephones\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"postinfo\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"customerdetails\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"username\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"password\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"email\"";
	echo $outstr;
	echo "\r\n";

// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		$outstr="";
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"idcustomer",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"name",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"contact",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"telephones",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"postinfo",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="HTML";
		$outstr.='"'.htmlspecialchars(GetData($row,"customerdetails",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"username",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"password",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"email",$format)).'"';
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
		echo '<td style="width: 100" x:str>'.PrepareForExcel("idcustomer").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("name").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("contact").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("telephones").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("postinfo").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("customerdetails").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("username").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("password").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("email").'</td>';
	}
	else
	{
		echo "<td>idcustomer</td>";
		echo "<td>name</td>";
		echo "<td>contact</td>";
		echo "<td>telephones</td>";
		echo "<td>postinfo</td>";
		echo "<td>customerdetails</td>";
		echo "<td>username</td>";
		echo "<td>password</td>";
		echo "<td>email</td>";
	}
	echo "</tr>";

	$totals=array();
// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		echo "<tr>";
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"idcustomer",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"name",$format));
		else
			echo htmlspecialchars(GetData($row,"name",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"contact",$format));
		else
			echo htmlspecialchars(GetData($row,"contact",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"telephones",$format));
		else
			echo htmlspecialchars(GetData($row,"telephones",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"postinfo",$format));
		else
			echo htmlspecialchars(GetData($row,"postinfo",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="HTML";
			echo GetData($row,"customerdetails",$format);
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"username",$format));
		else
			echo htmlspecialchars(GetData($row,"username",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"password",$format));
		else
			echo htmlspecialchars(GetData($row,"password",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"email",$format));
		else
			echo htmlspecialchars(GetData($row,"email",$format));
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
		$this->Rect($x,$this->y0,$colwidth["idcustomer"],$this->maxheight);
		$x+=$colwidth["idcustomer"];
		$this->Rect($x,$this->y0,$colwidth["name"],$this->maxheight);
		$x+=$colwidth["name"];
		$this->Rect($x,$this->y0,$colwidth["contact"],$this->maxheight);
		$x+=$colwidth["contact"];
		$this->Rect($x,$this->y0,$colwidth["telephones"],$this->maxheight);
		$x+=$colwidth["telephones"];
		$this->Rect($x,$this->y0,$colwidth["postinfo"],$this->maxheight);
		$x+=$colwidth["postinfo"];
		$this->Rect($x,$this->y0,$colwidth["customerdetails"],$this->maxheight);
		$x+=$colwidth["customerdetails"];
		$this->Rect($x,$this->y0,$colwidth["username"],$this->maxheight);
		$x+=$colwidth["username"];
		$this->Rect($x,$this->y0,$colwidth["password"],$this->maxheight);
		$x+=$colwidth["password"];
		$this->Rect($x,$this->y0,$colwidth["email"],$this->maxheight);
		$x+=$colwidth["email"];
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
		$this->Cell($colwidth["idcustomer"],$this->rowheight,"idcustomer",1,0,'C',1);
		$this->Cell($colwidth["name"],$this->rowheight,"name",1,0,'C',1);
		$this->Cell($colwidth["contact"],$this->rowheight,"contact",1,0,'C',1);
		$this->Cell($colwidth["telephones"],$this->rowheight,"telephones",1,0,'C',1);
		$this->Cell($colwidth["postinfo"],$this->rowheight,"postinfo",1,0,'C',1);
		$this->Cell($colwidth["customerdetails"],$this->rowheight,"customerdetails",1,0,'C',1);
		$this->Cell($colwidth["username"],$this->rowheight,"username",1,0,'C',1);
		$this->Cell($colwidth["password"],$this->rowheight,"password",1,0,'C',1);
		$this->Cell($colwidth["email"],$this->rowheight,"email",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/9;
	$colwidth=array();
    $colwidth["idcustomer"]=$defwidth;
    $colwidth["name"]=$defwidth;
    $colwidth["contact"]=$defwidth;
    $colwidth["telephones"]=$defwidth;
    $colwidth["postinfo"]=$defwidth;
    $colwidth["customerdetails"]=$defwidth;
    $colwidth["username"]=$defwidth;
    $colwidth["password"]=$defwidth;
    $colwidth["email"]=$defwidth;
	
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
		$pdf->MultiCell($colwidth["idcustomer"],$rowheight,GetData($row,"idcustomer",""));
		$x+=$colwidth["idcustomer"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["name"],$rowheight,GetData($row,"name",""));
		$x+=$colwidth["name"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["contact"],$rowheight,GetData($row,"contact",""));
		$x+=$colwidth["contact"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["telephones"],$rowheight,GetData($row,"telephones",""));
		$x+=$colwidth["telephones"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["postinfo"],$rowheight,GetData($row,"postinfo",""));
		$x+=$colwidth["postinfo"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["customerdetails"],$rowheight,GetData($row,"customerdetails","HTML"));
		$x+=$colwidth["customerdetails"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["username"],$rowheight,GetData($row,"username",""));
		$x+=$colwidth["username"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["password"],$rowheight,GetData($row,"password",""));
		$x+=$colwidth["password"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["email"],$rowheight,GetData($row,"email",""));
		$x+=$colwidth["email"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["idcustomer"],$pdf->maxheight);
		$x+=$colwidth["idcustomer"];
		$pdf->Rect($x,$pdf->y0,$colwidth["name"],$pdf->maxheight);
		$x+=$colwidth["name"];
		$pdf->Rect($x,$pdf->y0,$colwidth["contact"],$pdf->maxheight);
		$x+=$colwidth["contact"];
		$pdf->Rect($x,$pdf->y0,$colwidth["telephones"],$pdf->maxheight);
		$x+=$colwidth["telephones"];
		$pdf->Rect($x,$pdf->y0,$colwidth["postinfo"],$pdf->maxheight);
		$x+=$colwidth["postinfo"];
		$pdf->Rect($x,$pdf->y0,$colwidth["customerdetails"],$pdf->maxheight);
		$x+=$colwidth["customerdetails"];
		$pdf->Rect($x,$pdf->y0,$colwidth["username"],$pdf->maxheight);
		$x+=$colwidth["username"];
		$pdf->Rect($x,$pdf->y0,$colwidth["password"],$pdf->maxheight);
		$x+=$colwidth["password"];
		$pdf->Rect($x,$pdf->y0,$colwidth["email"],$pdf->maxheight);
		$x+=$colwidth["email"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>