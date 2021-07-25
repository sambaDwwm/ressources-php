<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/category_variables.php");


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
$templatefile = "category_edit.htm";

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
		$evalues["price"]=$value;
	}


//	processibng price - end
	}
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
		$evalues["taskdetails"]=$value;
	}


//	processibng taskdetails - end
	}
//	processing picture - start
	if(!$inlineedit)
	{
	$value = postvalue("value_picture");
	$type=postvalue("type_picture");
	if (in_assoc_array("type_picture",$_POST) || in_assoc_array("value_picture",$_POST) || in_assoc_array("value_picture",$_FILES))	
	{
		$value=prepare_for_db("picture",$value,$type,postvalue("filename_picture"));
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
		$evalues["picture"]=$value;
	}


//	processibng picture - end
	}
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
		$evalues["Color"]=$value;
	}


//	processibng Color - end
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
	$data["Category"]=$evalues["Category"];
	$data["price"]=$evalues["price"];
	$data["taskdetails"]=$evalues["taskdetails"];
	$data["Color"]=$evalues["Color"];
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
	if ($useAJAX)
		$includes.="<div id=\"search_suggest\"></div>\r\n";



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

$smarty->assign("value_Category",@$data["Category"]);
$smarty->assign("value_price",@$data["price"]);
$smarty->assign("value_taskdetails",@$data["taskdetails"]);
$smarty->assign("value_picture",@$data["picture"]);
$smarty->assign("value_Color",@$data["Color"]);


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