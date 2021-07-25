<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
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
			$keylink.="&key1=".htmlspecialchars(rawurlencode($data["idcustomer"]));


//	idcustomer - 
			$value="";
				$value = ProcessLargeText(GetData($data,"idcustomer", ""),"field=idcustomer".$keylink,"",MODE_PRINT);
			$row[$col."idcustomer_value"]=$value;

//	name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"name", ""),"field=name".$keylink,"",MODE_PRINT);
			$row[$col."name_value"]=$value;

//	contact - 
			$value="";
				$value = ProcessLargeText(GetData($data,"contact", ""),"field=contact".$keylink,"",MODE_PRINT);
			$row[$col."contact_value"]=$value;

//	telephones - 
			$value="";
				$value = ProcessLargeText(GetData($data,"telephones", ""),"field=telephones".$keylink,"",MODE_PRINT);
			$row[$col."telephones_value"]=$value;

//	postinfo - 
			$value="";
				$value = ProcessLargeText(GetData($data,"postinfo", ""),"field=postinfo".$keylink,"",MODE_PRINT);
			$row[$col."postinfo_value"]=$value;

//	customerdetails - HTML
			$value="";
				$value = GetData($data,"customerdetails", "HTML");
			$row[$col."customerdetails_value"]=$value;

//	username - 
			$value="";
				$value = ProcessLargeText(GetData($data,"username", ""),"field=username".$keylink,"",MODE_PRINT);
			$row[$col."username_value"]=$value;

//	password - 
			$value="";
				$value = ProcessLargeText(GetData($data,"password", ""),"field=password".$keylink,"",MODE_PRINT);
			$row[$col."password_value"]=$value;

//	email - 
			$value="";
				$value = ProcessLargeText(GetData($data,"email", ""),"field=email".$keylink,"",MODE_PRINT);
			$row[$col."email_value"]=$value;
			$row[$col."show"]=true;
			$data=db_fetch_array($rs);
		}
		$rowinfo[]=$row;
	}
	$smarty->assign("rowinfo",$rowinfo);


	

$strSQL=$_SESSION[$strTableName."_sql"];

$templatefile = "customer_print.htm";
if(function_exists("BeforeShowPrint"))
	BeforeShowPrint($smarty,$templatefile);

$smarty->display($templatefile);

