<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/users_variables.php");


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
$templatefile = "users_add.htm";

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
//	processing group - start
	if(!$inlineedit)
	{
	$value = postvalue("value_group");
	$type=postvalue("type_group");
	if (in_assoc_array("type_group",$_POST) || in_assoc_array("value_group",$_POST) || in_assoc_array("value_group",$_FILES))
	{
		$value=prepare_for_db("group",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{
		$avalues["group"]=$value;
	}
	}
//	processibng group - end
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
//	processing info - start
	if(!$inlineedit)
	{
	$value = postvalue("value_info");
	$type=postvalue("type_info");
	if (in_assoc_array("type_info",$_POST) || in_assoc_array("value_info",$_POST) || in_assoc_array("value_info",$_FILES))
	{
		$value=prepare_for_db("info",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{
		$avalues["info"]=$value;
	}
	}
//	processibng info - end


//	insert ownerid value if exists
	$avalues["idus"]=prepare_for_db("idus",$_SESSION["_".$strTableName."_OwnerID"]);



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
		$copykeys["idus"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["idus"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strWhere=whereAdd($strWhere,SecuritySQL("Search"));
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["idus"]="";
//call CopyOnLoad event
	if(function_exists("CopyOnLoad"))
		CopyOnLoad($defvalues,$strWhere);
}
else if(!count($defvalues))
{
}
if($readavalues)
{
	$defvalues["username"]=@$avalues["username"];
	$defvalues["password"]=@$avalues["password"];
	$defvalues["group"]=@$avalues["group"];
	$defvalues["email"]=@$avalues["email"];
	$defvalues["info"]=@$avalues["info"];
}

foreach($defvalues as $key=>$value)
	$smarty->assign("value_".GoodFieldName($key),$value);

	//	include files
	$includes="";

	//	validation stuff
	$bodyonload="";
	$onsubmit="";

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
	$includes.="var AUTOCOMPLETE_TABLE='users_autocomplete.php';\r\n";
	$includes.="var SUGGEST_TABLE='users_searchsuggest.php';\r\n";
	$includes.="var SUGGEST_LOOKUP_TABLE='users_lookupsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";
	if ($useAJAX) {
	$includes.="<div id=\"search_suggest\"></div>\r\n";
	}





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
