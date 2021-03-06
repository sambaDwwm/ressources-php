<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");

if(@$_POST["a"]=="logout" || @$_GET["a"]=="logout")
{
	session_unset();
	setcookie("username","",time()-365*1440*60);
	setcookie("password","",time()-365*1440*60);
	header("Location: login.php");
	exit();
}

include('libs/Smarty.class.php');
$smarty = new Smarty();


$conn=db_connect();
//	Before Process event
if(function_exists("BeforeProcessLogin"))
	BeforeProcessLogin($conn);


$myurl=@$_SESSION["MyURL"];
unset($_SESSION["MyURL"]);

$defaulturl="";
		$defaulturl="menu.php";




$message="";

$pUsername=postvalue("username");
$pPassword=postvalue("password");

if(@$_COOKIE["username"] || @$_COOKIE["password"])
	$smarty->assign("checked"," checked");

if (@$_POST["btnSubmit"] == "Login")
{
	if(@$_POST["remember_password"] == 1)
	{
		setcookie("username",$pUsername,time()+365*1440*60);
		setcookie("password",$pPassword,time()+365*1440*60);
		$smarty->assign("checked"," checked");
	}
	else
	{
		setcookie("username","",time()-365*1440*60);
		setcookie("password","",time()-365*1440*60);
		$smarty->assign("checked","");
	}
//   	 username and password are stored in the database
	$strUsername = (string)$pUsername;
	$strPassword = (string)$pPassword;
	$sUsername=$strUsername;
	$sPassword=$strPassword;
	$rstemp=db_query("select * from `users` where 1=0",$conn);
		
	if(FieldNeedQuotes($rstemp,$cUserNameField))
		$strUsername="'".db_addslashes($strUsername)."'";
	else
		$strUsername=(0+$strUsername);
	if(FieldNeedQuotes($rstemp,$cPasswordField))
		$strPassword="'".db_addslashes($strPassword)."'";
	else
		$strPassword=(0+$strPassword);
		$strSQL = "select * from `users` where ".AddFieldWrappers($cUserNameField).
		"=".$strUsername." and ".AddFieldWrappers($cPasswordField).
		"=".$strPassword;
		if(function_exists("BeforeLogin"))
		if(!BeforeLogin($pUsername,$pPassword))
			$strSQL="select * from `users` where 1<0";
	
	$rs=db_query($strSQL,$conn);
 	$data=db_fetch_array($rs);
   	if($data && @$data[$cUserNameField]==$sUsername && @$data[$cPasswordField]==$sPassword)
	{
		$_SESSION["UserID"] = $pUsername;
   		$_SESSION["AccessLevel"] = ACCESS_LEVEL_USER;

		$_SESSION["GroupID"] = $data["group"];
		if($_SESSION["GroupID"]=="<Default>")
	   		$_SESSION["AccessLevel"] = ACCESS_LEVEL_ADMINGROUP;
		



					$_SESSION["OwnerID"] = $data["idus"];
	$_SESSION["_calendar_OwnerID"] = $data["idus"];
			$_SESSION["_category_OwnerID"] = $data["idus"];
			$_SESSION["_daily_OwnerID"] = $data["idus"];
			$_SESSION["_monthly_OwnerID"] = $data["idus"];
			$_SESSION["_weekly_OwnerID"] = $data["idus"];
			$_SESSION["_yearly_OwnerID"] = $data["idus"];
			$_SESSION["_users_OwnerID"] = $data["idus"];
			$_SESSION["_customer_OwnerID"] = $data["idus"];
			$_SESSION["_howto_OwnerID"] = $data["idus"];
			$_SESSION["_editcalendar_OwnerID"] = $data["idus"];
			$_SESSION["_customer statistics_OwnerID"] = $data["idus"];
			$_SESSION["_task statistics_OwnerID"] = $data["idus"];
	

		if(function_exists("AfterSuccessfulLogin"))
			AfterSuccessfulLogin($pUsername,$pPassword,$data);
		if($myurl)
			header("Location: ".$myurl);
		else
			header("Location: ".$defaulturl);
		return;
   	}
	else
	{
		if(function_exists("AfterUnsuccessfulLogin"))
			AfterUnsuccessfulLogin($pUsername,$pPassword);
		$message = "Invalid Login";
	}
}

$_SESSION["MyURL"]=$myurl;
if($myurl)
	$smarty->assign("url",$myurl);
else
	$smarty->assign("url",$defaulturl);


if(@$_POST["username"] || @$_GET["username"])
	$smarty->assign("value_username","value=\"".htmlspecialchars($pUsername)."\"");
else
	$smarty->assign("value_username","value=\"".htmlspecialchars(refine(@$_COOKIE["username"]))."\"");


if(@$_POST["password"])
	$smarty->assign("value_password","value=\"".htmlspecialchars($pPassword)."\"");
else
	$smarty->assign("value_password","value=\"".htmlspecialchars(refine(@$_COOKIE["password"]))."\"");


if(@$_GET["message"]=="expired")
	$message = "Your session has expired. Please login again.";


$smarty->assign("message",$message);

$templatefile="login.htm";
if(function_exists("BeforeShowLogin"))
	BeforeShowLogin($smarty,$templatefile);

$smarty->display($templatefile);
?>