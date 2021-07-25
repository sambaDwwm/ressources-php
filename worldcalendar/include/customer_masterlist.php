<?php
include("include/customer_settings.php");

function DisplayMasterTableInfo($detailtable, $keys)
{
	global $conn,$strTableName,$smarty;
	
	$oldTableName=$strTableName;
	$strTableName="customer";

//$strSQL = "select `idcustomer`,   `idusercus`,   `name`,   `contact`,   `telephones`,   `postinfo`,   `customerdetails`,   `username`,   `password`,   `email`  From `customer`";

$sqlHead="select `idcustomer`,   `idusercus`,   `name`,   `contact`,   `telephones`,   `postinfo`,   `customerdetails`,   `username`,   `password`,   `email`  ";
$sqlFrom="From `customer`";
$sqlWhere="";
$sqlTail="";

$where="";

if($detailtable=="customer statistics")
{
		$where.= GetFullFieldName("idcustomer")."=".make_db_value("idcustomer",$keys[1-1]);
}
if(!$where)
{
	$strTableName=$oldTableName;
	return;
}
	$str = SecuritySQL("Search");
	if(strlen($str))
		$where.=" and ".$str;

	$strWhere=whereAdd($sqlWhere,$where);
	if(strlen($strWhere))
		$strWhere=" where ".$strWhere." ";
	$strSQL= $sqlHead.$sqlFrom.$strWhere.$sqlTail;

//	$strSQL=AddWhere($strSQL,$where);
	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$data=db_fetch_array($rs);
	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode($data["idcustomer"]));
	

//	name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"name", ""),"field=name".$keylink);
			$smarty->assign("showmaster_name",$value);

//	contact - 
			$value="";
				$value = ProcessLargeText(GetData($data,"contact", ""),"field=contact".$keylink);
			$smarty->assign("showmaster_contact",$value);

//	telephones - 
			$value="";
				$value = ProcessLargeText(GetData($data,"telephones", ""),"field=telephones".$keylink);
			$smarty->assign("showmaster_telephones",$value);

//	postinfo - 
			$value="";
				$value = ProcessLargeText(GetData($data,"postinfo", ""),"field=postinfo".$keylink);
			$smarty->assign("showmaster_postinfo",$value);

//	customerdetails - HTML
			$value="";
				$value = GetData($data,"customerdetails", "HTML");
			$smarty->assign("showmaster_customerdetails",$value);

//	email - 
			$value="";
				$value = ProcessLargeText(GetData($data,"email", ""),"field=email".$keylink);
			$smarty->assign("showmaster_email",$value);

//	username - 
			$value="";
				$value = ProcessLargeText(GetData($data,"username", ""),"field=username".$keylink);
			$smarty->assign("showmaster_username",$value);

//	password - 
			$value="";
				$value = ProcessLargeText(GetData($data,"password", ""),"field=password".$keylink);
			$smarty->assign("showmaster_password",$value);
	$strTableName=$oldTableName;
}

// events

?>