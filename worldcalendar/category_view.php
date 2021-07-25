<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/category_variables.php");


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

	$smarty->assign("show_key1", htmlspecialchars(GetData($data,"id", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode($data["id"]));

////////////////////////////////////////////
//	id - 
	$value="";
		$value = ProcessLargeText(GetData($data,"id", ""),"","",MODE_VIEW);
	$smarty->assign("show_id",$value);
////////////////////////////////////////////
//	picture - File-based Image
	$value="";
		if(CheckImageExtension($data["picture"])) 
	{
				 	// show thumbnail
		$thumbname="th".$data["picture"];
		if(substr("files/",0,7)!="http://" && !file_exists(GetUploadFolder("picture").$thumbname))
			$thumbname=$data["picture"];
		$value="<a target=_blank href=\"".htmlspecialchars(AddLinkPrefix("picture",$data["picture"]))."\">";
		$value.="<img";
		if($thumbname==$data["picture"])
		{
								}
		$value.=" border=0";
		$value.=" src=\"".htmlspecialchars(AddLinkPrefix("picture",$thumbname))."\"></a>";
	}
	$smarty->assign("show_picture",$value);
////////////////////////////////////////////
//	price - 
	$value="";
		$value = ProcessLargeText(GetData($data,"price", ""),"","",MODE_VIEW);
	$smarty->assign("show_price",$value);
////////////////////////////////////////////
//	Category - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Category", ""),"","",MODE_VIEW);
	$smarty->assign("show_Category",$value);
////////////////////////////////////////////
//	taskdetails - HTML
	$value="";
		$value = GetData($data,"taskdetails", "HTML");
	$smarty->assign("show_taskdetails",$value);
////////////////////////////////////////////
//	Color - 
	$value="";
		$value = ProcessLargeText(GetData($data,"Color", ""),"","",MODE_VIEW);
	$smarty->assign("show_Color",$value);

$templatefile = "category_view.htm";
if(function_exists("BeforeShowView"))
	BeforeShowView($smarty,$templatefile,$data);

$smarty->display($templatefile);

?>