<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/daily_variables.php");

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
	echo "<td><strong>Date</strong></td>";
	echo "<td><strong>Time</strong></td>";
	echo "<td><strong>Description</strong></td>";
	echo "<td><strong>Category</strong></td>";
	echo "<td><strong>details</strong></td>";
	echo "<td><strong>Subject</strong></td>";
	echo "<td><strong>income</strong></td>";
	echo "<td><strong>outcome</strong></td>";
	echo "<td><strong>Color</strong></td>";
	echo "</tr>";
	while ($data = db_fetch_array($rs)) {
		$recordsCounter++;
					if ( $recordsCounter > 10 ) { break; }
		echo "<tr>";
		$keylink="";
		$keylink.="&key1=".htmlspecialchars(rawurlencode($data["id"]));

	//	DateField - Short Date
		    $value="";
				$value = ProcessLargeText(GetData($data,"DateField", "Short Date"),"field=DateField".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	TimeField - Time
		    $value="";
				$value = ProcessLargeText(GetData($data,"TimeField", "Time"),"field=TimeField".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	Description - 
		    $value="";
				if(strlen($data["Description"]))
			{
				$strdata = make_db_value("Description",$data["Description"]);
				$LookupSQL="SELECT ";
							$LookupSQL.="`name`";
				$LookupSQL.=" FROM `customer` WHERE `idcustomer` = " . $strdata;
							$LookupSQL.=" and ("." idusercus = ".$_SESSION["OwnerID"].")"; 
				LogInfo($LookupSQL);
				$rsLookup = db_query($LookupSQL,$conn);
				$lookupvalue=$data["Description"];
				if($lookuprow=db_fetch_numarray($rsLookup))
					$lookupvalue=$lookuprow[0];
				$value=ProcessLargeText(GetDataInt($lookupvalue,$data,"Description", ""),"field=Description".$keylink,"",MODE_PRINT);
			}
			else
				$value="";
			echo "<td>".$value."</td>";
	//	Category - 
		    $value="";
				if(strlen($data["Category"]))
			{
				$strdata = make_db_value("Category",$data["Category"]);
				$LookupSQL="SELECT ";
							$LookupSQL.="`Category`";
				$LookupSQL.=" FROM `category` WHERE `id` = " . $strdata;
							$LookupSQL.=" and ("." idusercat = ".$_SESSION["OwnerID"].")"; 
				LogInfo($LookupSQL);
				$rsLookup = db_query($LookupSQL,$conn);
				$lookupvalue=$data["Category"];
				if($lookuprow=db_fetch_numarray($rsLookup))
					$lookupvalue=$lookuprow[0];
				$value=ProcessLargeText(GetDataInt($lookupvalue,$data,"Category", ""),"field=Category".$keylink,"",MODE_PRINT);
			}
			else
				$value="";
			echo "<td>".$value."</td>";
	//	details - HTML
		    $value="";
				$value = GetData($data,"details", "HTML");
			echo "<td>".$value."</td>";
	//	Theme - Custom
		    $value="";
				$value = GetData($data,"Theme", "Custom");
			echo "<td>".$value."</td>";
	//	income - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"income", ""),"field=income".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	outcome - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"outcome", ""),"field=outcome".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	Color - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Color", ""),"field=Color".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "Details found".": <strong>".$rowcount."</strong>";
}

echo "counterSeparator".postvalue("counter");
?>