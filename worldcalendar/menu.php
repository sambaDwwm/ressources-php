<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");

if(!@$_SESSION["UserID"])
{
	header("Location: login.php");
	return;
}


include('libs/Smarty.class.php');
$smarty = new Smarty();

$conn=db_connect();
//	Before Process event
if(function_exists("BeforeProcessMenu"))
	BeforeProcessMenu($conn);


$smarty->assign("username",$_SESSION["UserID"]);
$smarty->assign("not_a_guest",$_SESSION["AccessLevel"] != ACCESS_LEVEL_GUEST);
$strPerm = GetUserPermissions("calendar");
$smarty->assign("allow_calendar",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("category");
$smarty->assign("allow_category",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("daily");
$smarty->assign("allow_daily",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("monthly");
$smarty->assign("allow_monthly",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("weekly");
$smarty->assign("allow_weekly",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("yearly");
$smarty->assign("allow_yearly",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("users");
$smarty->assign("allow_users",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("customer");
$smarty->assign("allow_customer",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("howto");
$smarty->assign("allow_howto",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("editcalendar");
$smarty->assign("allow_Copy_of_calendar",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("customer statistics");
$smarty->assign("allow_customer_statistics",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("task statistics");
$smarty->assign("allow_task_statistics",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));

$templatefile="menu.htm";
if(function_exists("BeforeShowMenu"))
	BeforeShowMenu($smarty,$templatefile);

$smarty->display($templatefile);
?>