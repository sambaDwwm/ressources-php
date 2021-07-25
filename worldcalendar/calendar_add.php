<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/calendar_variables.php");


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
$templatefile = "calendar_add.htm";

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
		$avalues["DateField"]=$value;
	}
	}
//	processibng DateField - end
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
		$avalues["TimeField"]=$value;
	}
	}
//	processibng TimeField - end
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
		$avalues["Theme"]=$value;
	}
	}
//	processibng Theme - end
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
		$avalues["Description"]=$value;
	}
	}
//	processibng Description - end
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
		$avalues["EndTime"]=$value;
	}
	}
//	processibng EndTime - end
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
		$avalues["DayEvent"]=$value;
	}
	}
//	processibng DayEvent - end
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
		$avalues["EndDate"]=$value;
	}
	}
//	processibng EndDate - end
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
		$avalues["Period"]=$value;
	}
	}
//	processibng Period - end
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
		$avalues["Recurrence"]=$value;
	}
	}
//	processibng Recurrence - end
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
		$avalues["details"]=$value;
	}
	}
//	processibng details - end
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
		$avalues["income"]=$value;
	}
	}
//	processibng income - end
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
		$avalues["outcome"]=$value;
	}
	}
//	processibng outcome - end


//	insert ownerid value if exists
	$avalues["idusercal"]=prepare_for_db("idusercal",$_SESSION["_".$strTableName."_OwnerID"]);



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
		$copykeys["id"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["id"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strWhere=whereAdd($strWhere,SecuritySQL("Search"));
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["id"]="";
//call CopyOnLoad event
	if(function_exists("CopyOnLoad"))
		CopyOnLoad($defvalues,$strWhere);
}
else if(!count($defvalues))
{
}
if($readavalues)
{
	$defvalues["DateField"]=@$avalues["DateField"];
	$defvalues["TimeField"]=@$avalues["TimeField"];
	$defvalues["Theme"]=@$avalues["Theme"];
	$defvalues["Description"]=@$avalues["Description"];
	$defvalues["EndTime"]=@$avalues["EndTime"];
	$defvalues["DayEvent"]=@$avalues["DayEvent"];
	$defvalues["EndDate"]=@$avalues["EndDate"];
	$defvalues["Period"]=@$avalues["Period"];
	$defvalues["Recurrence"]=@$avalues["Recurrence"];
	$defvalues["details"]=@$avalues["details"];
	$defvalues["income"]=@$avalues["income"];
	$defvalues["outcome"]=@$avalues["outcome"];
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
			if($validatetype)
			$bodyonload.="define('value_Description','".$validatetype."','customer');";
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
	$includes.="var AUTOCOMPLETE_TABLE='calendar_autocomplete.php';\r\n";
	$includes.="var SUGGEST_TABLE='calendar_searchsuggest.php';\r\n";
	$includes.="var SUGGEST_LOOKUP_TABLE='calendar_lookupsuggest.php';\r\n";
	}
	$includes.="</script>\r\n";
	if ($useAJAX) {
	$includes.="<div id=\"search_suggest\"></div>\r\n";
	}

		//	include datepicker files
	$includes.="<script language=\"JavaScript\" src=\"include/calendar.js\"></script>\r\n";
	

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
