<?php

$strTableName="howto";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="howto";

$gPageSize=20;
$ColumnsCount		= 1;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="select `id`,   `ask`,   `answer`  ";
$gsqlFrom="From `howto`";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "select `id`,   `ask`,   `answer`  From `howto`";
$gstrSQL = gSQLWhere("");

include("include/howto_settings.php");
include("include/howto_events.php");
?>