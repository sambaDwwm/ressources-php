<?php
include("include/category_settings.php");

function DisplayMasterTableInfo($detailtable, $keys)
{
	global $conn,$strTableName,$smarty;
	
	$oldTableName=$strTableName;
	$strTableName="category";

//$strSQL = "select `id`,   `idusercat`,   `Category`,   `price`,   `taskdetails`,   `picture`,   `Color`  From `category`";

$sqlHead="select `id`,   `idusercat`,   `Category`,   `price`,   `taskdetails`,   `picture`,   `Color`  ";
$sqlFrom="From `category`";
$sqlWhere="";
$sqlTail="";

$where="";

if($detailtable=="task statistics")
{
		$where.= GetFullFieldName("id")."=".make_db_value("id",$keys[1-1]);
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
	$keylink.="&key1=".htmlspecialchars(rawurlencode($data["id"]));
	

//	Category - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Category", ""),"field=Category".$keylink);
			$smarty->assign("showmaster_Category",$value);

//	price - 
			$value="";
				$value = ProcessLargeText(GetData($data,"price", ""),"field=price".$keylink);
			$smarty->assign("showmaster_price",$value);

//	taskdetails - HTML
			$value="";
				$value = GetData($data,"taskdetails", "HTML");
			$smarty->assign("showmaster_taskdetails",$value);

//	Color - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Color", ""),"field=Color".$keylink);
			$smarty->assign("showmaster_Color",$value);

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
			$smarty->assign("showmaster_picture",$value);
	$strTableName=$oldTableName;
}

// events

?>