<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/customer_variables.php");

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
	echo "<td><strong>name</strong></td>";
	echo "<td><strong>contact</strong></td>";
	echo "<td><strong>telephones</strong></td>";
	echo "<td><strong>postinfo</strong></td>";
	echo "<td><strong>customerdetails</strong></td>";
	echo "<td><strong>email</strong></td>";
	echo "<td><strong>username</strong></td>";
	echo "<td><strong>password</strong></td>";
	echo "</tr>";
	while ($data = db_fetch_array($rs)) {
		$recordsCounter++;
					if ( $recordsCounter > 10 ) { break; }
		echo "<tr>";
		$keylink="";
		$keylink.="&key1=".htmlspecialchars(rawurlencode($data["idcustomer"]));

	//	name - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"name", ""),"field=name".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	contact - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"contact", ""),"field=contact".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	telephones - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"telephones", ""),"field=telephones".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	postinfo - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"postinfo", ""),"field=postinfo".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	customerdetails - HTML
		    $value="";
				$value = GetData($data,"customerdetails", "HTML");
			echo "<td>".$value."</td>";
	//	email - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"email", ""),"field=email".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	username - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"username", ""),"field=username".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	password - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"password", ""),"field=password".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "Details found".": <strong>".$rowcount."</strong>";
}

echo "counterSeparator".postvalue("counter");
?>