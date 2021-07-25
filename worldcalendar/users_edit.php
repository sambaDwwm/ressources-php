<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/users_variables.php");


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
$templatefile = "users_edit.htm";

//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["idus"]=postvalue("editid1");

//	prepare data for saving
if(@$_POST["a"]=="edited")
{
	$strWhereClause=KeyWhere($keys);
	$strSQL = "update ".AddTableWrappers($strOriginalTableName)." set ";
	$evalues=array();
	$efilename_values=array();
	$files_delete=array();
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
		$evalues["group"]=$value;
	}


//	processibng group - end
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
		$evalues["info"]=$value;
	}


//	processibng info - end
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
	$data["username"]=$evalues["username"];
	$data["password"]=$evalues["password"];
	$data["group"]=$evalues["group"];
	$data["email"]=$evalues["email"];
	$data["info"]=$evalues["info"];
}

include('libs/Smarty.class.php');
$smarty = new Smarty();

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
	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";





	$smarty->assign("includes",$includes);
	$smarty->assign("bodyonload",$bodyonload);
	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$smarty->assign("onsubmit",$onsubmit);

$smarty->assign("key1",htmlspecialchars($keys["idus"]));
$showKeys[] = rawurlencode($keys["idus"]);
	$smarty->assign("show_key1", htmlspecialchars(GetData($data,"idus", "")));

$smarty->assign("message",$message);

$readonlyfields=array();

$smarty->assign("value_username",@$data["username"]);
$smarty->assign("value_password",@$data["password"]);
$smarty->assign("value_group",@$data["group"]);
$smarty->assign("value_email",@$data["email"]);
$smarty->assign("value_info",@$data["info"]);


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