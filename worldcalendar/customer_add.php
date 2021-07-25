<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/customer_variables.php");


//	check if logged in
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

$filename="";
$status="";
$message="";
$error_happened=false;
$readavalues=false;


$showKeys = array();
$showValues = array();
$showRawValues = array();
$showFields = array();
$showDetailKeys = array();
$IsSaved = false;
$HaveData = true;
$inlineedit = (@$_REQUEST["editType"]=="inline") ? true : false;
$keys=array();
$templatefile = "customer_add.htm";

//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessAdd"))
	BeforeProcessAdd($conn);

include('libs/Smarty.class.php');
$smarty = new Smarty();

// insert new record if we have to

if(@$_POST["a"]=="added")
{
	$afilename_values=array();
	$avalues=array();
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
		$avalues["name"]=$value;
	}
	}
//	processibng name - end
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
		$avalues["contact"]=$value;
	}
	}
//	processibng contact - end
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
		$avalues["telephones"]=$value;
	}
	}
//	processibng telephones - end
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
		$avalues["postinfo"]=$value;
	}
	}
//	processibng postinfo - end
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
		$avalues["customerdetails"]=$value;
	}
	}
//	processibng customerdetails - end
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
		$avalues["email"]=$value;
	}
	}
//	processibng email - end
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
		$avalues["username"]=$value;
	}
	}
//	processibng username - end
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
		$avalues["password"]=$value;
	}
	}
//	processibng password - end


//	insert ownerid value if exists
	$avalues["idusercus"]=prepare_for_db("idusercus",$_SESSION["_".$strTableName."_OwnerID"]);



//	add filenames to values
	foreach($afilename_values as $akey=>$value)
		$avalues[$akey]=$value;
//	make SQL string
	$strSQL = "insert into ".AddTableWrappers($strOriginalTableName)." ";
	$strFields="(";
	$strValues="(";
	
//	before Add event
	$retval = true;
	if(function_exists("BeforeAdd"))
		$retval=BeforeAdd($avalues,$message,$inlineedit);
	if($retval)
	{
		foreach($avalues as $akey=>$value)
		{
			$strFields.=AddFieldWrappers($akey).", ";
			$strValues.=add_db_quotes($akey,$value).", ";
		}
		if(substr($strFields,-2)==", ")
			$strFields=substr($strFields,0,strlen($strFields)-2);
		if(substr($strValues,-2)==", ")
			$strValues=substr($strValues,0,strlen($strValues)-2);
		$strSQL.=$strFields.") values ".$strValues.")";
		LogInfo($strSQL);
		set_error_handler("add_error_handler");
		db_exec($strSQL,$conn);
		set_error_handler("error_handler");
//	move files
		if(!$error_happened)
		{
			foreach ($files_move as $file)
			{
				move_uploaded_file($file[0],$file[1]);
				if(strtoupper(substr(PHP_OS,0,3))!="WIN")
					@chmod($file[1],0777);
			}
			$message="<div class=message><<< "."Record was added"." >>></div>";

//	after edit event
			if(function_exists("AfterAdd"))
				AfterAdd($avalues,$keys,$inlineedit);
		}
	}
	else
		$readavalues=true;
}

$defvalues=array();


//	copy record
if(array_key_exists("copyid1",$_REQUEST) || array_key_exists("editid1",$_REQUEST))
{
	$copykeys=array();
	if(array_key_exists("copyid1",$_REQUEST))
	{
		$copykeys["idcustomer"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["idcustomer"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strWhere=whereAdd($strWhere,SecuritySQL("Search"));
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["idcustomer"]="";
//call CopyOnLoad event
	if(function_exists("CopyOnLoad"))
		CopyOnLoad($defvalues,$strWhere);
}
else if(!count($defvalues))
{
}
if($readavalues)
{
	$defvalues["name"]=@$avalues["name"];
	$defvalues["contact"]=@$avalues["contact"];
	$defvalues["telephones"]=@$avalues["telephones"];
	$defvalues["postinfo"]=@$avalues["postinfo"];
	$defvalues["customerdetails"]=@$avalues["customerdetails"];
	$defvalues["username"]=@$avalues["username"];
	$defvalues["password"]=@$avalues["password"];
	$defvalues["email"]=@$avalues["email"];
}

foreach($defvalues as $key=>$value)
	$smarty->assign("value_".GoodFieldName($key),$value);

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
	if ($useAJAX) {
	$includes.="<div id=\"search_suggest\"></div>\r\n";
	}



		include("plugins/fckeditor/fckeditor.php");


	$smarty->assign("includes",$includes);
	$smarty->assign("bodyonload",$bodyonload);
	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$smarty->assign("onsubmit",$onsubmit);

$smarty->assign("message",$message);
$smarty->assign("status",$status);

$readonlyfields=array();

//	show readonly fields

$linkdata="";

if ($useAJAX) 
{
	$record_id= postvalue("recordID");

		$linkdata = "<script type=\"text/javascript\">\r\n".
		"$(document).ready(function(){ \r\n".
		$linkdata.
		"});</script>";
} 
else 
{
}

$smarty->assign("linkdata",$linkdata);

	if(function_exists("BeforeShowAdd"))
		BeforeShowAdd($smarty,$templatefile);

	$smarty->display($templatefile);
function add_error_handler($errno, $errstr, $errfile, $errline)
{
	global $readavalues, $message, $status, $inlineedit, $error_happened;
		$message="<div class=message><<< "."Record was NOT added"." >>><br><br>".$errstr."</div>";
	$readavalues=true;
	$error_happened=true;
}
?>
