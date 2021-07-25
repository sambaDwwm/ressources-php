<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/daily_variables.php");


//	check if logged in
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

$filename="";	
$message="";

//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessView"))
	BeforeProcessView($conn);


$keys=array();
$keys["id"]=postvalue("editid1");

//	get current values and show edit controls

$strWhereClause = KeyWhere($keys);


//	select only owned records
$strWhereClause=whereAdd($strWhereClause,SecuritySQL("Search"));

$strSQL=gSQLWhere($strWhereClause);

$strSQLbak = $strSQL;
if(function_exists("BeforeQueryView"))
	BeforeQueryView($strSQL,$strWhereClause);
if($strSQLbak == $strSQL)
	$strSQL=gSQLWhere($strWhereClause);

LogInfo($strSQL);
$rs=db_query($strSQL,$conn);
$data=db_fetch_array($rs);


include('libs/Smarty.class.php');
$smarty = new Smarty();

	$smarty->assign("show_key1", htmlspecialchars(GetData($data,"id", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode($data["id"]));

////////////////////////////////////////////
//	DateField - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"DateField", "Short Date"),"","",MODE_VIEW);
	$smarty->assign("show_DateField",$value);
////////////////////////////////////////////
//	TimeField - Time
	$value="";
		$value = ProcessLargeText(GetData($data,"TimeField", "Time"),"","",MODE_VIEW);
	$smarty->assign("show_TimeField",$value);
////////////////////////////////////////////
//	Theme - Custom
	$value="";
		$value = GetData($data,"Theme", "Custom");
	$smarty->assign("show_Theme",$value);
////////////////////////////////////////////
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
		$value=ProcessLargeText(GetDataInt($lookupvalue,$data,"Description", ""),"field=Description".$keylink,"",MODE_VIEW);
	}
	else
		$value="";
	$smarty->assign("show_Description",$value);
////////////////////////////////////////////
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
		$value=ProcessLargeText(GetDataInt($lookupvalue,$data,"Category", ""),"field=Category".$keylink,"",MODE_VIEW);
	}
	else
		$value="";
	$smarty->assign("show_Category",$value);
////////////////////////////////////////////
//	details - HTML
	$value="";
		$value = GetData($data,"details", "HTML");
	$smarty->assign("show_details",$value);
////////////////////////////////////////////
//	EndTime - 
	$value="";
		$value = ProcessLargeText(GetData($data,"EndTime", ""),"","",MODE_VIEW);
	$smarty->assign("show_EndTime",$value);
////////////////////////////////////////////
//	DayEvent - Checkbox
	$value="";
		$value = GetData($data,"DayEvent", "Checkbox");
	$smarty->assign("show_DayEvent",$value);
////////////////////////////////////////////
//	Recurrence - Checkbox
	$value="";
		$value = GetData($data,"Recurrence", "Checkbox");
	$smarty->assign("show_Recurrence",$value);
////////////////////////////////////////////
//	DateField2 - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"DateField2", "Short Date"),"","",MODE_VIEW);
	$smarty->assign("show_DateField2",$value);
////////////////////////////////////////////
//	EndDate - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"EndDate", "Short Date"),"","",MODE_VIEW);
	$smarty->assign("show_EndDate",$value);
////////////////////////////////////////////
//	Period - 
	$value="";
		if(strlen($data["Period"]))
	{
		$strdata = make_db_value("Period",$data["Period"]);
		$LookupSQL="SELECT ";
					$LookupSQL.="`Period`";
		$LookupSQL.=" FROM `period` WHERE `ValueField` = " . $strdata;
					LogInfo($LookupSQL);
		$rsLookup = db_query($LookupSQL,$conn);
		$lookupvalue=$data["Period"];
		if($lookuprow=db_fetch_numarray($rsLookup))
			$lookupvalue=$lookuprow[0];
		$value=ProcessLargeText(GetDataInt($lookupvalue,$data,"Period", ""),"field=Period".$keylink,"",MODE_VIEW);
	}
	else
		$value="";
	$smarty->assign("show_Period",$value);
////////////////////////////////////////////
//	income - 
	$value="";
		$value = ProcessLargeText(GetData($data,"income", ""),"","",MODE_VIEW);
	$smarty->assign("show_income",$value);
////////////////////////////////////////////
//	outcome - 
	$value="";
		$value = ProcessLargeText(GetData($data,"outcome", ""),"","",MODE_VIEW);
	$smarty->assign("show_outcome",$value);

$templatefile = "daily_view.htm";
if(function_exists("BeforeShowView"))
	BeforeShowView($smarty,$templatefile,$data);

$smarty->display($templatefile);

?>