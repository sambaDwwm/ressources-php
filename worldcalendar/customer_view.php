<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/customer_variables.php");


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
$keys["idcustomer"]=postvalue("editid1");

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

	$smarty->assign("show_key1", htmlspecialchars(GetData($data,"idcustomer", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode($data["idcustomer"]));

////////////////////////////////////////////
//	idcustomer - 
	$value="";
		$value = ProcessLargeText(GetData($data,"idcustomer", ""),"","",MODE_VIEW);
	$smarty->assign("show_idcustomer",$value);
////////////////////////////////////////////
//	name - 
	$value="";
		$value = ProcessLargeText(GetData($data,"name", ""),"","",MODE_VIEW);
	$smarty->assign("show_name",$value);
////////////////////////////////////////////
//	contact - 
	$value="";
		$value = ProcessLargeText(GetData($data,"contact", ""),"","",MODE_VIEW);
	$smarty->assign("show_contact",$value);
////////////////////////////////////////////
//	telephones - 
	$value="";
		$value = ProcessLargeText(GetData($data,"telephones", ""),"","",MODE_VIEW);
	$smarty->assign("show_telephones",$value);
////////////////////////////////////////////
//	postinfo - 
	$value="";
		$value = ProcessLargeText(GetData($data,"postinfo", ""),"","",MODE_VIEW);
	$smarty->assign("show_postinfo",$value);
////////////////////////////////////////////
//	customerdetails - HTML
	$value="";
		$value = GetData($data,"customerdetails", "HTML");
	$smarty->assign("show_customerdetails",$value);
////////////////////////////////////////////
//	username - 
	$value="";
		$value = ProcessLargeText(GetData($data,"username", ""),"","",MODE_VIEW);
	$smarty->assign("show_username",$value);
////////////////////////////////////////////
//	password - 
	$value="";
		$value = ProcessLargeText(GetData($data,"password", ""),"","",MODE_VIEW);
	$smarty->assign("show_password",$value);
////////////////////////////////////////////
//	email - 
	$value="";
		$value = ProcessLargeText(GetData($data,"email", ""),"","",MODE_VIEW);
	$smarty->assign("show_email",$value);

$templatefile = "customer_view.htm";
if(function_exists("BeforeShowView"))
	BeforeShowView($smarty,$templatefile,$data);

$smarty->display($templatefile);

?>