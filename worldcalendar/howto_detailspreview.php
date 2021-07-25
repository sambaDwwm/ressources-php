<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/howto_variables.php");

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
			echo ( $rowcount > 10 ) ? ". Displaying first: <strong>10</strong>.<br /><br />" : "<br /><br />";
	echo "<table cellpadding=1 cellspacing=1 border=0 align=left class=\"detailtable\"><tr>";
	echo "<td><strong>ask</strong></td>";
	echo "<td><strong>answer</strong></td>";
	echo "</tr>";
	while ($data = db_fetch_array($rs)) {
		$recordsCounter++;
					if ( $recordsCounter > 10 ) { break; }
		echo "<tr>";
		$keylink="";
		$keylink.="&key1=".htmlspecialchars(rawurlencode($data["id"]));

	//	ask - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"ask", ""),"field=ask".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	answer - HTML
		    $value="";
				$value = GetData($data,"answer", "HTML");
			echo "<td>".$value."</td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "Details found".": <strong>".$rowcount."</strong>";
}

echo "counterSeparator".postvalue("counter");
?>