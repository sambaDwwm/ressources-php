<?php

$strTableName="customer";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="customer";

$gPageSize=20;
$ColumnsCount		= 1;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="select `idcustomer`,   `idusercus`,   `name`,   `contact`,   `telephones`,   `postinfo`,   `customerdetails`,   `username`,   `password`,   `email`  ";
$gsqlFrom="From `customer`";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "select `idcustomer`,   `idusercus`,   `name`,   `contact`,   `telephones`,   `postinfo`,   `customerdetails`,   `username`,   `password`,   `email`  From `customer`";
$gstrSQL = gSQLWhere("");

include("include/customer_settings.php");
include("include/customer_events.php");
?>