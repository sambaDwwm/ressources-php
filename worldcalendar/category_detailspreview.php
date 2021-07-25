<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/category_variables.php");

if(!@$_SESSION["UserID"])
{ 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{
	return;
}

$conn=db_connect(); 
$recordsCounter = 0;


//$strSQL = $gstrSQL;



$str = SecuritySQL("Search");
if(strlen($str))
	$where.=" and ".$str;
$strSQL = gSQLWhere($where);
//$strSQL = AddWhere($strSQL,$where);

$strSQL.=" ".$gstrOrderBy;

$rowcount=gSQLRowCount($where);


if ( $rowcount ) {
	$rs=db_query($strSQL,$conn);
	echo "Details found".": <strong>".$rowcount."</strong>";
			echo ( $rowcount > 5 ) ? ". Displaying first: <strong>5</strong>.<br /><br />" : "<br /><br />";
	echo "<table cellpadding=1 cellspacing=1 border=0 align=left class=\"detailtable\"><tr>";
	echo "<td><strong>task</strong></td>";
	echo "<td><strong>price</strong></td>";
	echo "<td><strong>taskdetails</strong></td>";
	echo "<td><strong>Color</strong></td>";
	echo "<td><strong>picture</strong></td>";
	echo "</tr>";
	while ($data = db_fetch_array($rs)) {
		$recordsCounter++;
					if ( $recordsCounter > 5 ) { break; }
		echo "<tr>";
		$keylink="";
		$keylink.="&key1=".htmlspecialchars(rawurlencode($data["id"]));

	//	Category - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Category", ""),"field=Category".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	price - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"price", ""),"field=price".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	taskdetails - HTML
		    $value="";
				$value = GetData($data,"taskdetails", "HTML");
			echo "<td>".$value."</td>";
	//	Color - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Color", ""),"field=Color".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
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
			echo "<td>".$value."</td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "Details found".": <strong>".$rowcount."</strong>";
}

echo "counterSeparator".postvalue("counter");
?>