<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/task_statistics_variables.php");


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
$templatefile = "task_statistics_edit.htm";

//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessEdit"))
	BeforeProcessEdit($conn);

$keys=array();
$keys["id"]=postvalue("editid1");

//	prepare data for saving
if(@$_POST["a"]=="edited")
{
	$strWhereClause=KeyWhere($keys);
	$strSQL = "update ".AddTableWrappers($strOriginalTableName)." set ";
	$evalues=array();
	$efilename_values=array();
	$files_delete=array();
	$files_move=array();
//	processing DateField - start
	if(!$inlineedit)
	{
	$value = postvalue("value_DateField");
	$type=postvalue("type_DateField");
	if (in_assoc_array("type_DateField",$_POST) || in_assoc_array("value_DateField",$_POST) || in_assoc_array("value_DateField",$_FILES))	
	{
		$value=prepare_for_db("DateField",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["DateField"]=$value;
	}


//	processibng DateField - end
	}
//	processing TimeField - start
	if(!$inlineedit)
	{
	$value = postvalue("value_TimeField");
	$type=postvalue("type_TimeField");
	if (in_assoc_array("type_TimeField",$_POST) || in_assoc_array("value_TimeField",$_POST) || in_assoc_array("value_TimeField",$_FILES))	
	{
		$value=prepare_for_db("TimeField",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["TimeField"]=$value;
	}


//	processibng TimeField - end
	}
//	processing Theme - start
	if(!$inlineedit)
	{
	$value = postvalue("value_Theme");
	$type=postvalue("type_Theme");
	if (in_assoc_array("type_Theme",$_POST) || in_assoc_array("value_Theme",$_POST) || in_assoc_array("value_Theme",$_FILES))	
	{
		$value=prepare_for_db("Theme",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["Theme"]=$value;
	}


//	processibng Theme - end
	}
//	processing Description - start
	if(!$inlineedit)
	{
	$value = postvalue("value_Description");
	$type=postvalue("type_Description");
	if (in_assoc_array("type_Description",$_POST) || in_assoc_array("value_Description",$_POST) || in_assoc_array("value_Description",$_FILES))	
	{
		$value=prepare_for_db("Description",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["Description"]=$value;
	}


//	processibng Description - end
	}
//	processing Category - start
	if(!$inlineedit)
	{
	$value = postvalue("value_Category");
	$type=postvalue("type_Category");
	if (in_assoc_array("type_Category",$_POST) || in_assoc_array("value_Category",$_POST) || in_assoc_array("value_Category",$_FILES))	
	{
		$value=prepare_for_db("Category",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["Category"]=$value;
	}


//	processibng Category - end
	}
//	processing EndTime - start
	if(!$inlineedit)
	{
	$value = postvalue("value_EndTime");
	$type=postvalue("type_EndTime");
	if (in_assoc_array("type_EndTime",$_POST) || in_assoc_array("value_EndTime",$_POST) || in_assoc_array("value_EndTime",$_FILES))	
	{
		$value=prepare_for_db("EndTime",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["EndTime"]=$value;
	}


//	processibng EndTime - end
	}
//	processing DayEvent - start
	if(!$inlineedit)
	{
	$value = postvalue("value_DayEvent");
	$type=postvalue("type_DayEvent");
	if (in_assoc_array("type_DayEvent",$_POST) || in_assoc_array("value_DayEvent",$_POST) || in_assoc_array("value_DayEvent",$_FILES))	
	{
		$value=prepare_for_db("DayEvent",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["DayEvent"]=$value;
	}


//	processibng DayEvent - end
	}
//	processing EndDate - start
	if(!$inlineedit)
	{
	$value = postvalue("value_EndDate");
	$type=postvalue("type_EndDate");
	if (in_assoc_array("type_EndDate",$_POST) || in_assoc_array("value_EndDate",$_POST) || in_assoc_array("value_EndDate",$_FILES))	
	{
		$value=prepare_for_db("EndDate",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["EndDate"]=$value;
	}


//	processibng EndDate - end
	}
//	processing Period - start
	if(!$inlineedit)
	{
	$value = postvalue("value_Period");
	$type=postvalue("type_Period");
	if (in_assoc_array("type_Period",$_POST) || in_assoc_array("value_Period",$_POST) || in_assoc_array("value_Period",$_FILES))	
	{
		$value=prepare_for_db("Period",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["Period"]=$value;
	}


//	processibng Period - end
	}
//	processing Recurrence - start
	if(!$inlineedit)
	{
	$value = postvalue("value_Recurrence");
	$type=postvalue("type_Recurrence");
	if (in_assoc_array("type_Recurrence",$_POST) || in_assoc_array("value_Recurrence",$_POST) || in_assoc_array("value_Recurrence",$_FILES))	
	{
		$value=prepare_for_db("Recurrence",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["Recurrence"]=$value;
	}


//	processibng Recurrence - end
	}
//	processing details - start
	if(!$inlineedit)
	{
	$value = postvalue("value_details");
	$type=postvalue("type_details");
	if (in_assoc_array("type_details",$_POST) || in_assoc_array("value_details",$_POST) || in_assoc_array("value_details",$_FILES))	
	{
		$value=prepare_for_db("details",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["details"]=$value;
	}


//	processibng details - end
	}
//	processing income - start
	if(!$inlineedit)
	{
	$value = postvalue("value_income");
	$type=postvalue("type_income");
	if (in_assoc_array("type_income",$_POST) || in_assoc_array("value_income",$_POST) || in_assoc_array("value_income",$_FILES))	
	{
		$value=prepare_for_db("income",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["income"]=$value;
	}


//	processibng income - end
	}
//	processing outcome - start
	if(!$inlineedit)
	{
	$value = postvalue("value_outcome");
	$type=postvalue("type_outcome");
	if (in_assoc_array("type_outcome",$_POST) || in_assoc_array("value_outcome",$_POST) || in_assoc_array("value_outcome",$_FILES))	
	{
		$value=prepare_for_db("outcome",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{	
		$evalues["outcome"]=$value;
	}


//	processibng outcome - end
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
	$data["DateField"]=$evalues["DateField"];
	$data["TimeField"]=$evalues["TimeField"];
	$data["Theme"]=$evalues["Theme"];
	$data["Description"]=$evalues["Description"];
	$data["Category"]=$evalues["Category"];
	$data["EndTime"]=$evalues["EndTime"];
	$data["DayEvent"]=$evalues["DayEvent"];
	$data["EndDate"]=$evalues["EndDate"];
	$data["Period"]=$evalues["Period"];
	$data["Recurrence"]=$evalues["Recurrence"];
	$data["details"]=$evalues["details"];
	$data["income"]=$evalues["income"];
	$data["outcome"]=$evalues["outcome"];
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
			if($validatetype)
			$bodyonload.="define('value_Description','".$validatetype."','customer');";
				$validatetype="";
			if($validatetype)
			$bodyonload.="define('value_Category','".$validatetype."','task');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_DayEvent','".$validatetype."','Whole Day Event');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_Recurrence','".$validatetype."','Recurrence');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_income','".$validatetype."','income');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_outcome','".$validatetype."','outcome');";

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
	$includes.="var AUTOCOMPLETE_TABLE='task_statistics_autocomplete.php';\r\n";
	$includes.="var SUGGEST_TABLE='task_statistics_searchsuggest.php';\r\n";
	$includes.="var SUGGEST_LOOKUP_TABLE='task_statistics_lookupsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";
	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";

		//	include datepicker files
	$includes.="<script language=\"JavaScript\" src=\"include/calendar.js\"></script>\r\n";
	

		include("plugins/fckeditor/fckeditor.php");


	$smarty->assign("includes",$includes);
	$smarty->assign("bodyonload",$bodyonload);
	if(strlen($onsubmit))
		$onsubmit="onSubmit=\"".$onsubmit."\"";
	$smarty->assign("onsubmit",$onsubmit);

$smarty->assign("key1",htmlspecialchars($keys["id"]));
$showKeys[] = rawurlencode($keys["id"]);
	$smarty->assign("show_key1", htmlspecialchars(GetData($data,"id", "")));

$smarty->assign("message",$message);

$readonlyfields=array();

$smarty->assign("value_DateField",@$data["DateField"]);
$smarty->assign("value_TimeField",@$data["TimeField"]);
$smarty->assign("value_Theme",@$data["Theme"]);
$smarty->assign("value_Description",@$data["Description"]);
$smarty->assign("value_Category",@$data["Category"]);
$smarty->assign("value_EndTime",@$data["EndTime"]);
$smarty->assign("value_DayEvent",@$data["DayEvent"]);
$smarty->assign("value_EndDate",@$data["EndDate"]);
$smarty->assign("value_Period",@$data["Period"]);
$smarty->assign("value_Recurrence",@$data["Recurrence"]);
$smarty->assign("value_details",@$data["details"]);
$smarty->assign("value_income",@$data["income"]);
$smarty->assign("value_outcome",@$data["outcome"]);


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