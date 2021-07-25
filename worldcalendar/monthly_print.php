<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
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


include('libs/Smarty.class.php');
$smarty = new Smarty();

$conn=db_connect();

//	Before Process event
if(function_exists("BeforeProcessPrint"))
	BeforeProcessPrint($conn);

$strWhereClause="";

if (@$_REQUEST["a"]!="") 
{
	
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
//	$strSQL = AddWhere($gstrSQL,$sWhere);
	$sWhere=whereAdd($sWhere,SecuritySQL("Search"));
//	if(SecuritySQL("Search"))
//		$strSQL = AddWhere($strSQL, SecuritySQL("Search"));
	$strSQL = gSQLWhere($sWhere);
	$strWhereClause=$sWhere;
}
else
{
	$strWhereClause=@$_SESSION[$strTableName."_where"];
	if(!$strWhereClause)
		$strWhereClause=whereAdd($strWhereClause,SecuritySQL("Search"));
	$strSQL = gSQLWhere($strWhereClause);
}



$strOrderBy=$_SESSION[$strTableName."_order"];
if(!$strOrderBy)
	$strOrderBy=$gstrOrderBy;
$strSQL.=" ".trim($strOrderBy);

$strSQLbak = $strSQL;
if(function_exists("BeforeQueryPrint"))
	BeforeQueryPrint($strSQL,$strWhereClause,$strOrderBy);

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
	
$mypage=(integer)$_SESSION[$strTableName."_pagenumber"];
if(!$mypage)
	$mypage=1;

//	page size
$PageSize=(integer)$_SESSION[$strTableName."_pagesize"];
if(!$PageSize)
	$PageSize=$gPageSize;

$recno=1;
	
if($numrows)
{
	$maxRecords = $numrows;
	$maxpages=ceil($maxRecords/$PageSize);
	if($mypage > $maxpages)
		$mypage = $maxpages;
	if($mypage<1) 
		$mypage=1;
	$maxrecs=$PageSize;
	$strSQL.=" limit ".(($mypage-1)*$PageSize).",".$PageSize;
}
$rs=db_query($strSQL,$conn);

//	hide colunm headers if needed
$recordsonpage=$numrows-($mypage-1)*$PageSize;
if($recordsonpage>$PageSize)
	$recordsonpage=$PageSize;
	if($recordsonpage>=1)
		$smarty->assign("column1show",true);
	else
		$smarty->assign("column1show",false);



//	fill $rowinfo array
	$rowinfo = array();

	$data=db_fetch_array($rs);

	while($data && $recno<=$PageSize)
	{
		$row=array();
		for($col=1;$data && $recno<=$PageSize && $col<=1;$col++)
		{

			$recno++;
			$keylink="";
			$keylink.="&key1=".htmlspecialchars(rawurlencode($data["id"]));


//	DateField - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"DateField", "Short Date"),"field=DateField".$keylink,"",MODE_PRINT);
			$row[$col."DateField_value"]=$value;

//	Description - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Description", ""),"field=Description".$keylink,"",MODE_PRINT);
			$row[$col."Description_value"]=$value;

//	Theme - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Theme", ""),"field=Theme".$keylink,"",MODE_PRINT);
			$row[$col."Theme_value"]=$value;

//	TimeField - 
			$value="";
				$value = ProcessLargeText(GetData($data,"TimeField", ""),"field=TimeField".$keylink,"",MODE_PRINT);
			$row[$col."TimeField_value"]=$value;

//	EndTime - 
			$value="";
				$value = ProcessLargeText(GetData($data,"EndTime", ""),"field=EndTime".$keylink,"",MODE_PRINT);
			$row[$col."EndTime_value"]=$value;

//	DayEvent - 
			$value="";
				$value = ProcessLargeText(GetData($data,"DayEvent", ""),"field=DayEvent".$keylink,"",MODE_PRINT);
			$row[$col."DayEvent_value"]=$value;

//	EndDate - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"EndDate", "Short Date"),"field=EndDate".$keylink,"",MODE_PRINT);
			$row[$col."EndDate_value"]=$value;

//	Period - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Period", ""),"field=Period".$keylink,"",MODE_PRINT);
			$row[$col."Period_value"]=$value;

//	Recurrence - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Recurrence", ""),"field=Recurrence".$keylink,"",MODE_PRINT);
			$row[$col."Recurrence_value"]=$value;

//	Category - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Category", ""),"field=Category".$keylink,"",MODE_PRINT);
			$row[$col."Category_value"]=$value;

//	details - 
			$value="";
				$value = ProcessLargeText(GetData($data,"details", ""),"field=details".$keylink,"",MODE_PRINT);
			$row[$col."details_value"]=$value;

//	income - 
			$value="";
				$value = ProcessLargeText(GetData($data,"income", ""),"field=income".$keylink,"",MODE_PRINT);
			$row[$col."income_value"]=$value;

//	outcome - 
			$value="";
				$value = ProcessLargeText(GetData($data,"outcome", ""),"field=outcome".$keylink,"",MODE_PRINT);
			$row[$col."outcome_value"]=$value;
			$row[$col."show"]=true;
			$data=db_fetch_array($rs);
		}
		$rowinfo[]=$row;
	}
	$smarty->assign("rowinfo",$rowinfo);


	

$strSQL=$_SESSION[$strTableName."_sql"];

$templatefile = "monthly_print.htm";
if(function_exists("BeforeShowPrint"))
	BeforeShowPrint($smarty,$templatefile);

$smarty->display($templatefile);

