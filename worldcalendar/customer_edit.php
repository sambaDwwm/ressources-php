<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/customer_variables.php");


//	check if logged in
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Edit"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

$filename="";	
$status="";
$message="";
$error_happened=false;
$readevalues=false;


$showKeys = array();
$showValues = array();
$showRawValues = array();
$showFields = array();
$showDetailKeys = array();
$IsSaved = false;
$HaveData = true;
$inlineedit = (@$_REQUEST["editType"]=="inline") ? true : false;
$templatefile = "customer_edit.htm";

//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["idcustomer"]=postvalue("editid1");

//	prepare data for saving
if(@$_POST["a"]=="edited")
{
	$strWhereClause=KeyWhere($keys);
	$strSQL = "update ".AddTableWrappers($strOriginalTableName)." set ";
	$evalues=array();
	$efilename_values=array();
	$files_delete=array();
	$files_move=array();
//	processing name - start
	if(!$inlineedit)
	{
	$value = postvalue("value_name");
	$type=postvalue("type_name");
	if (in_assoc_array("type_name",$_POST) || in_assoc_array("value_name",$_POST) || in_assoc_array("value_name",$_FILES))	
	{
		$value=prepare_for_db("name",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["name"]=$value;
	}


//	processibng name - end
	}
//	processing contact - start
	if(!$inlineedit)
	{
	$value = postvalue("value_contact");
	$type=postvalue("type_contact");
	if (in_assoc_array("type_contact",$_POST) || in_assoc_array("value_contact",$_POST) || in_assoc_array("value_contact",$_FILES))	
	{
		$value=prepare_for_db("contact",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["contact"]=$value;
	}


//	processibng contact - end
	}
//	processing telephones - start
	if(!$inlineedit)
	{
	$value = postvalue("value_telephones");
	$type=postvalue("type_telephones");
	if (in_assoc_array("type_telephones",$_POST) || in_assoc_array("value_telephones",$_POST) || in_assoc_array("value_telephones",$_FILES))	
	{
		$value=prepare_for_db("telephones",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["telephones"]=$value;
	}


//	processibng telephones - end
	}
//	processing postinfo - start
	if(!$inlineedit)
	{
	$value = postvalue("value_postinfo");
	$type=postvalue("type_postinfo");
	if (in_assoc_array("type_postinfo",$_POST) || in_assoc_array("value_postinfo",$_POST) || in_assoc_array("value_postinfo",$_FILES))	
	{
		$value=prepare_for_db("postinfo",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["postinfo"]=$value;
	}


//	processibng postinfo - end
	}
//	processing customerdetails - start
	if(!$inlineedit)
	{
	$value = postvalue("value_customerdetails");
	$type=postvalue("type_customerdetails");
	if (in_assoc_array("type_customerdetails",$_POST) || in_assoc_array("value_customerdetails",$_POST) || in_assoc_array("value_customerdetails",$_FILES))	
	{
		$value=prepare_for_db("customerdetails",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["customerdetails"]=$value;
	}


//	processibng customerdetails - end
	}
//	processing username - start
	if(!$inlineedit)
	{
	$value = postvalue("value_username");
	$type=postvalue("type_username");
	if (in_assoc_array("type_username",$_POST) || in_assoc_array("value_username",$_POST) || in_assoc_array("value_username",$_FILES))	
	{
		$value=prepare_for_db("username",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["username"]=$value;
	}


//	processibng username - end
	}
//	processing password - start
	if(!$inlineedit)
	{
	$value = postvalue("value_password");
	$type=postvalue("type_password");
	if (in_assoc_array("type_password",$_POST) || in_assoc_array("value_password",$_POST) || in_assoc_array("value_password",$_FILES))	
	{
		$value=prepare_for_db("password",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["password"]=$value;
	}


//	processibng password - end
	}
//	processing email - start
	if(!$inlineedit)
	{
	$value = postvalue("value_email");
	$type=postvalue("type_email");
	if (in_assoc_array("type_email",$_POST) || in_assoc_array("value_email",$_POST) || in_assoc_array("value_email",$_FILES))	
	{
		$value=prepare_for_db("email",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["email"]=$value;
	}


//	processibng email - end
	}

	foreach($efilename_values as $ekey=>$value)
		$evalues[$ekey]=$value;
//	do event
	$retval=true;
	if(function_exists("BeforeEdit"))
		$retval=BeforeEdit($evalues,$strWhereClause,$dataold,$keys,$message,$inlineedit);
	if($retval)
	{		
//	construct SQL string
		foreach($evalues as $ekey=>$value)
		{
			$strSQL.=AddFieldWrappers($ekey)."=".add_db_quotes($ekey,$value).", ";
		}
		if(substr($strSQL,-2)==", ")
			$strSQL=substr($strSQL,0,strlen($strSQL)-2);
		$strSQL.=" where ".$strWhereClause;
		if(SecuritySQL("Edit"))
			$strSQL .= " and (".SecuritySQL("Edit").")";
		set_error_handler("edit_error_handler");
		db_exec($strSQL,$conn);
		set_error_handler("error_handler");
		if(!$error_happened)
		{
//	delete & move files
			foreach ($files_delete as $file)
			{
				if(file_exists($file))
					@unlink($file);
			}
			foreach ($files_move as $file)
			{
				move_uploaded_file($file[0],$file[1]);
				if(strtoupper(substr(PHP_OS,0,3))!="WIN")
					@chmod($file[1],0777);
			}
			$message="<div class=message><<< "."Record updated"." >>></div>";
//	after edit event
			if(function_exists("AfterEdit"))
				AfterEdit($evalues,KeyWhere($keys),$dataold,$keys,$inlineedit);
		}
	}
	else
		$readevalues=true;
}

//	get current values and show edit controls

//$strSQL = $gstrSQL;

$strWhereClause=KeyWhere($keys);
//	select only owned records
$strWhereClause=whereAdd($strWhereClause,SecuritySQL("Edit"));

$strSQL=gSQLWhere($strWhereClause);

$strSQLbak = $strSQL;
//	Before Query event
if(function_exists("BeforeQueryEdit"))
	BeforeQueryEdit($strSQL,$strWhereClause);

if($strSQLbak == $strSQL)
	$strSQL=gSQLWhere($strWhereClause);
LogInfo($strSQL);
$rs=db_query($strSQL,$conn);
$data=db_fetch_array($rs);

if($readevalues)
{
	$data["name"]=$evalues["name"];
	$data["contact"]=$evalues["contact"];
	$data["telephones"]=$evalues["telephones"];
	$data["postinfo"]=$evalues["postinfo"];
	$data["customerdetails"]=$evalues["customerdetails"];
	$data["username"]=$evalues["username"];
	$data["password"]=$evalues["password"];
	$data["email"]=$evalues["email"];
}

include('libs/Smarty.class.php');
$smarty = new Smarty();

	//	include files
	$includes="";

	//	validation stuff
	$bodyonload="";
	$onsubmit="";
		$includes.="<script language=\"JavaScript\" src=\"include/validate.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\">\r\n";
	$includes.="var TEXT_FIELDS_REQUIRED='".addslashes("The Following fields are Required")."';\r\n";
	$includes.="var TEXT_FIELDS_ZIPCODES='".addslashes("The Following fields must be valid Zipcodes")."';\r\n";
	$includes.="var TEXT_FIELDS_EMAILS='".addslashes("The Following fields must be valid Emails")."';\r\n";
	$includes.="var TEXT_FIELDS_NUMBERS='".addslashes("The Following fields must be Numbers")."';\r\n";
	$includes.="var TEXT_FIELDS_CURRENCY='".addslashes("The Following fields must be currency")."';\r\n";
	$includes.="var TEXT_FIELDS_PHONE='".addslashes("The Following fields must be Phone Numbers")."';\r\n";
	$includes.="var TEXT_FIELDS_PASSWORD1='".addslashes("The Following fields must be valid Passwords")."';\r\n";
	$includes.="var TEXT_FIELDS_PASSWORD2='".addslashes("should be at least 4 characters long")."';\r\n";
	$includes.="var TEXT_FIELDS_PASSWORD3='".addslashes("Cannot be 'password'")."';\r\n";
	$includes.="var TEXT_FIELDS_STATE='".addslashes("The Following fields must be State Names")."';\r\n";
	$includes.="var TEXT_FIELDS_SSN='".addslashes("The Following fields must be Social Security Numbers")."';\r\n";
	$includes.="var TEXT_FIELDS_DATE='".addslashes("The Following fields must be valid dates")."';\r\n";
	$includes.="var TEXT_FIELDS_TIME='".addslashes("The Following fields must be valid time in 24-hours format")."';\r\n";
	$includes.="var TEXT_FIELDS_CC='".addslashes("The Following fields must be valid Credit Card Numbers")."';\r\n";
	$includes.="var TEXT_FIELDS_SSN='".addslashes("The Following fields must be Social Security Numbers")."';\r\n";
	$includes.="</script>\r\n";
		  		$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
			$bodyonload.="define('value_name','".$validatetype."','name');";

	if($bodyonload)
	{
		$onsubmit="return validate();";
		$bodyonload="onload=\"".$bodyonload."\"";
	}

	if ($useAJAX) {
	$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
	}
	$includes.="<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
	$includes.="<script language=\"JavaScript\">\r\n".
	"var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
	"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
	"var bLoading=false;\r\n".
	"var TEXT_PLEASE_SELECT='".addslashes("Please select")."';\r\n";
	if ($useAJAX) {
	$includes.="var AUTOCOMPLETE_TABLE='customer_autocomplete.php';\r\n";
	$includes.="var SUGGEST_TABLE='customer_searchsuggest.php';\r\n";
	$includes.="var SUGGEST_LOOKUP_TABLE='customer_lookupsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";
	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";



		include("plugins/fckeditor/fckeditor.php");


	$smarty->assign("includes",$includes);
	$smarty->assign("bodyonload",$bodyonload);
	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$smarty->assign("onsubmit",$onsubmit);

$smarty->assign("key1",htmlspecialchars($keys["idcustomer"]));
$showKeys[] = rawurlencode($keys["idcustomer"]);
	$smarty->assign("show_key1", htmlspecialchars(GetData($data,"idcustomer", "")));

$smarty->assign("message",$message);

$readonlyfields=array();

$smarty->assign("value_name",@$data["name"]);
$smarty->assign("value_contact",@$data["contact"]);
$smarty->assign("value_telephones",@$data["telephones"]);
$smarty->assign("value_postinfo",@$data["postinfo"]);
$smarty->assign("value_customerdetails",@$data["customerdetails"]);
$smarty->assign("value_username",@$data["username"]);
$smarty->assign("value_password",@$data["password"]);
$smarty->assign("value_email",@$data["email"]);


$linkdata="";

if ($useAJAX) 
{
	$record_id= postvalue("recordID");

		$linkdata = "<script type=\"text/javascript\">\r\n".
		"$(document).ready(function(){ \r\n".
		$linkdata.
		"});</script>";
	
} else {
}

$smarty->assign("linkdata",$linkdata);


	if(function_exists("BeforeShowEdit"))
		BeforeShowEdit($smarty,$templatefile);
	$smarty->display($templatefile);

function edit_error_handler($errno, $errstr, $errfile, $errline)
{
	global $readevalues, $message, $status, $inlineedit, $error_happened;
		$message="<div class=message><<< "."Record was NOT edited"." >>><br><br>".$errstr."</div>";
	$readevalues=true;
	$error_happened=true;
}

?>