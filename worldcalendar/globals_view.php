<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/globals_variables.php");


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
//	TimePeriod - 
	$value="";
		$value = ProcessLargeText(GetData($data,"TimePeriod", ""),"","",MODE_VIEW);
	$smarty->assign("show_TimePeriod",$value);

$templatefile = "globals_view.htm";
if(function_exists("BeforeShowView"))
	BeforeShowView($smarty,$templatefile,$data);

$smarty->display($templatefile);

?>