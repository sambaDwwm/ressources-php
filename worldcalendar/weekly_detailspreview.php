<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/weekly_variables.php");

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
	echo "<td><strong>Description</strong></td>";
	echo "<td><strong>Category</strong></td>";
	echo "<td><strong>details</strong></td>";
	echo "<td><strong>EndDate</strong></td>";
	echo "<td><strong>Period</strong></td>";
	echo "<td><strong>Recurrence</strong></td>";
	echo "<td><strong>EndTime</strong></td>";
	echo "<td><strong>Whole Day Event</strong></td>";
	echo "<td><strong>Time</strong></td>";
	echo "<td><strong>Subject</strong></td>";
	echo "<td><strong>Date</strong></td>";
	echo "<td><strong>income</strong></td>";
	echo "<td><strong>outcome</strong></td>";
	echo "</tr>";
	while ($data = db_fetch_array($rs)) {
		$recordsCounter++;
					if ( $recordsCounter > 10 ) { break; }
		echo "<tr>";
		$keylink="";
		$keylink.="&key1=".htmlspecialchars(rawurlencode($data["id"]));

	//	Description - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Description", ""),"field=Description".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	Category - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Category", ""),"field=Category".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	details - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"details", ""),"field=details".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	EndDate - Short Date
		    $value="";
				$value = ProcessLargeText(GetData($data,"EndDate", "Short Date"),"field=EndDate".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	Period - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Period", ""),"field=Period".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	Recurrence - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Recurrence", ""),"field=Recurrence".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	EndTime - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"EndTime", ""),"field=EndTime".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	DayEvent - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"DayEvent", ""),"field=DayEvent".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	TimeField - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"TimeField", ""),"field=TimeField".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	Theme - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"Theme", ""),"field=Theme".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	DateField - Short Date
		    $value="";
				$value = ProcessLargeText(GetData($data,"DateField", "Short Date"),"field=DateField".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	income - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"income", ""),"field=income".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
	//	outcome - 
		    $value="";
				$value = ProcessLargeText(GetData($data,"outcome", ""),"field=outcome".$keylink,"",MODE_PRINT);
			echo "<td>".$value."</td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "Details found".": <strong>".$rowcount."</strong>";
}

echo "counterSeparator".postvalue("counter");
?>