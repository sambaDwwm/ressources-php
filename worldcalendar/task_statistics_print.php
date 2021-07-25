<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
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


	$totals=array();
	$totals["id"]=0;
	$totals["income"]=0;
	$totals["outcome"]=0;

//	fill $rowinfo array
	$rowinfo = array();

	$data=db_fetch_array($rs);

	while($data && $recno<=$PageSize)
	{
		$row=array();
		for($col=1;$data && $recno<=$PageSize && $col<=1;$col++)
		{

							$totals["id"]+= ($data["id"]!="");
							$totals["income"]+=($data["income"]+0);
							$totals["outcome"]+=($data["outcome"]+0);
			$recno++;
			$keylink="";
			$keylink.="&key1=".htmlspecialchars(rawurlencode($data["id"]));


//	id - 
			$value="";
				$value = ProcessLargeText(GetData($data,"id", ""),"field=id".$keylink,"",MODE_PRINT);
			$row[$col."id_value"]=$value;

//	EndTime - 
			$value="";
				$value = ProcessLargeText(GetData($data,"EndTime", ""),"field=EndTime".$keylink,"",MODE_PRINT);
			$row[$col."EndTime_value"]=$value;

//	DateField2 - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"DateField2", "Short Date"),"field=DateField2".$keylink,"",MODE_PRINT);
			$row[$col."DateField2_value"]=$value;

//	TimeField - Time
			$value="";
				$value = ProcessLargeText(GetData($data,"TimeField", "Time"),"field=TimeField".$keylink,"",MODE_PRINT);
			$row[$col."TimeField_value"]=$value;

//	Theme - Custom
			$value="";
				$value = GetData($data,"Theme", "Custom");
			$row[$col."Theme_value"]=$value;

//	Description - 
			$value="";
				if(strlen($data["Description"]))
			{
				$strdata = make_db_value("Description",$data["Description"]);
				$LookupSQL="SELECT ";
							$LookupSQL.="`name`";
				$LookupSQL.=" FROM `customer` WHERE `idcustomer` = " . $strdata;
							$LookupSQL.=" and ("." idusercus = ".$_SESSION["OwnerID"].")"; 
				LogInfo($LookupSQL);
				$rsLookup = db_query($LookupSQL,$conn);
				$lookupvalue=$data["Description"];
				if($lookuprow=db_fetch_numarray($rsLookup))
					$lookupvalue=$lookuprow[0];
									$value=ProcessLargeText(GetDataInt($lookupvalue,$data,"Description", ""),"field=Description".$keylink,"",MODE_PRINT);

			}
			else
				$value="";
			$row[$col."Description_value"]=$value;

//	Category - 
			$value="";
				if(strlen($data["Category"]))
			{
				$strdata = make_db_value("Category",$data["Category"]);
				$LookupSQL="SELECT ";
							$LookupSQL.="`Category`";
				$LookupSQL.=" FROM `category` WHERE `id` = " . $strdata;
							$LookupSQL.=" and ("." idusercat = ".$_SESSION["OwnerID"].")"; 
				LogInfo($LookupSQL);
				$rsLookup = db_query($LookupSQL,$conn);
				$lookupvalue=$data["Category"];
				if($lookuprow=db_fetch_numarray($rsLookup))
					$lookupvalue=$lookuprow[0];
									$value=ProcessLargeText(GetDataInt($lookupvalue,$data,"Category", ""),"field=Category".$keylink,"",MODE_PRINT);

			}
			else
				$value="";
			$row[$col."Category_value"]=$value;

//	details - HTML
			$value="";
				$value = GetData($data,"details", "HTML");
			$row[$col."details_value"]=$value;

//	income - 
			$value="";
				$value = ProcessLargeText(GetData($data,"income", ""),"field=income".$keylink,"",MODE_PRINT);
			$row[$col."income_value"]=$value;

//	outcome - 
			$value="";
				$value = ProcessLargeText(GetData($data,"outcome", ""),"field=outcome".$keylink,"",MODE_PRINT);
			$row[$col."outcome_value"]=$value;

//	Color - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Color", ""),"field=Color".$keylink,"",MODE_PRINT);
			$row[$col."Color_value"]=$value;
			$row[$col."show"]=true;
			$data=db_fetch_array($rs);
		}
		$rowinfo[]=$row;
	}
	$smarty->assign("rowinfo",$rowinfo);

//	show totals
$smarty->assign("showtotal_id", GetTotals("id",$totals["id"],"COUNT",$recno-1,""));
$smarty->assign("showtotal_income", GetTotals("income",$totals["income"],"TOTAL",$recno-1,""));
$smarty->assign("showtotal_outcome", GetTotals("outcome",$totals["outcome"],"TOTAL",$recno-1,""));

	
//	display master table info
$mastertable=$_SESSION[$strTableName."_mastertable"];
$masterkeys=array();
$smarty->assign("showmasterfile","empty.htm");
if($mastertable=="category")
{
//	include proper masterprint.php code
	include("include/category_masterprint.php");
	$masterkeys[]=@$_SESSION[$strTableName."_masterkey1"];
	DisplayMasterTableInfo("task statistics", $masterkeys);
	$smarty->assign("showmasterfile","category_masterprint.htm");
}

$strSQL=$_SESSION[$strTableName."_sql"];

$templatefile = "task_statistics_print.htm";
if(function_exists("BeforeShowPrint"))
	BeforeShowPrint($smarty,$templatefile);

$smarty->display($templatefile);

