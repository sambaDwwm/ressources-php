<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/category_variables.php");


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
$templatefile = "category_add.htm";

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
		$avalues["Category"]=$value;
	}
	}
//	processibng Category - end
//	processing price - start
	if(!$inlineedit)
	{
	$value = postvalue("value_price");
	$type=postvalue("type_price");
	if (in_assoc_array("type_price",$_POST) || in_assoc_array("value_price",$_POST) || in_assoc_array("value_price",$_FILES))
	{
		$value=prepare_for_db("price",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{
		$avalues["price"]=$value;
	}
	}
//	processibng price - end
//	processing taskdetails - start
	if(!$inlineedit)
	{
	$value = postvalue("value_taskdetails");
	$type=postvalue("type_taskdetails");
	if (in_assoc_array("type_taskdetails",$_POST) || in_assoc_array("value_taskdetails",$_POST) || in_assoc_array("value_taskdetails",$_FILES))
	{
		$value=prepare_for_db("taskdetails",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{
		$avalues["taskdetails"]=$value;
	}
	}
//	processibng taskdetails - end
//	processing Color - start
	if(!$inlineedit)
	{
	$value = postvalue("value_Color");
	$type=postvalue("type_Color");
	if (in_assoc_array("type_Color",$_POST) || in_assoc_array("value_Color",$_POST) || in_assoc_array("value_Color",$_FILES))
	{
		$value=prepare_for_db("Color",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{
		$avalues["Color"]=$value;
	}
	}
//	processibng Color - end
//	processing picture - start
	if(!$inlineedit)
	{
	$value = postvalue("value_picture");
	$type=postvalue("type_picture");
	if (in_assoc_array("type_picture",$_POST) || in_assoc_array("value_picture",$_POST) || in_assoc_array("value_picture",$_FILES))
	{
		$value=prepare_for_db("picture",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{
		if($value)
		{
				$ext = CheckImageExtension($_FILES["file_picture"]["name"]);
			$contents = myfile_get_contents($_FILES["file_picture"]['tmp_name']);
			$thumb = CreateThumbnail($contents,150,$ext);
			$file = GetUploadFolder("picture")."th".$value;
			if(file_exists($file))
					@unlink($file);
			$th = fopen($file,"w");
			fwrite($th,$thumb);
			fclose($th);
		}
		$avalues["picture"]=$value;
	}
	}
//	processibng picture - end


//	insert ownerid value if exists
	$avalues["idusercat"]=prepare_for_db("idusercat",$_SESSION["_".$strTableName."_OwnerID"]);



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
	$defvalues["Category"]=@$avalues["Category"];
	$defvalues["price"]=@$avalues["price"];
	$defvalues["taskdetails"]=@$avalues["taskdetails"];
	$defvalues["Color"]=@$avalues["Color"];
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
			$bodyonload.="define('value_Category','".$validatetype."','task');";
			  		$validatetype="IsNumeric";
			if($validatetype)
			$bodyonload.="define('value_price','".$validatetype."','price');";

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
	$includes.="var AUTOCOMPLETE_TABLE='category_autocomplete.php';\r\n";
	$includes.="var SUGGEST_TABLE='category_searchsuggest.php';\r\n";
	$includes.="var SUGGEST_LOOKUP_TABLE='category_lookupsuggest.php';\r\n";
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
