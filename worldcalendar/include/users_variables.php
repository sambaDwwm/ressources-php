<?php

$strTableName="users";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="users";

$gPageSize=20;
$ColumnsCount		= 1;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="select `idus`,   `username`,   `password`,   `group`,   `email`,   `info`  ";
$gsqlFrom="From `users`";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "select `idus`,   `username`,   `password`,   `group`,   `email`,   `info`  From `users`";
$gstrSQL = gSQLWhere("");

include("include/users_settings.php");
include("include/users_events.php");
?>