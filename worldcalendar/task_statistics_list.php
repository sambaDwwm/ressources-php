<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/task_statistics_variables.php");

if(!@$_SESSION["UserID"])
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search") && !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add"))
{
	echo "<p>"."You don't have permissions to access this table"." <a href=\"login.php\">"."Back to login page"."</a></p>";
	return;
}


include('libs/Smarty.class.php');
$smarty = new Smarty();



$conn=db_connect();


//	process reqest data, fill session variables

if(!count($_POST) && !count($_GET))
{
	$sess_unset = array();
	foreach($_SESSION as $key=>$value)
		if(substr($key,0,strlen($strTableName)+1)==$strTableName."_" &&
			strpos(substr($key,strlen($strTableName)+1),"_")===false)
			$sess_unset[] = $key;
	foreach($sess_unset as $key)
		unset($_SESSION[$key]);
}

//	Before Process event
if(function_exists("BeforeProcessList"))
	BeforeProcessList($conn);

if(@$_REQUEST["a"]=="showall")
	$_SESSION[$strTableName."_search"]=0;
else if(@$_REQUEST["a"]=="search")
{
	$_SESSION[$strTableName."_searchfield"]=postvalue("SearchField");
	$_SESSION[$strTableName."_searchoption"]=postvalue("SearchOption");
	$_SESSION[$strTableName."_searchfor"]=postvalue("SearchFor");
	if(postvalue("SearchFor")!="" || postvalue("SearchOption")=='Empty')
		$_SESSION[$strTableName."_search"]=1;
	else
		$_SESSION[$strTableName."_search"]=0;
	$_SESSION[$strTableName."_pagenumber"]=1;
}
else if(@$_REQUEST["a"]=="advsearch")
{
	$_SESSION[$strTableName."_asearchnot"]=array();
	$_SESSION[$strTableName."_asearchopt"]=array();
	$_SESSION[$strTableName."_asearchfor"]=array();
	$_SESSION[$strTableName."_asearchfor2"]=array();
	$tosearch=0;
	$asearchfield = postvalue("asearchfield");
	$_SESSION[$strTableName."_asearchtype"] = postvalue("type");
	if(!$_SESSION[$strTableName."_asearchtype"])
		$_SESSION[$strTableName."_asearchtype"]="and";
	foreach($asearchfield as $field)
	{
		$gfield=GoodFieldName($field);
		$asopt=postvalue("asearchopt_".$gfield);
		$value1=postvalue("value_".$gfield);
		$type=postvalue("type_".$gfield);
		$value2=postvalue("value1_".$gfield);
		$not=postvalue("not_".$gfield);
		if($value1 || $asopt=='Empty')
		{
			$tosearch=1;
			$_SESSION[$strTableName."_asearchopt"][$field]=$asopt;
			if(!is_array($value1))
				$_SESSION[$strTableName."_asearchfor"][$field]=$value1;
			else
				$_SESSION[$strTableName."_asearchfor"][$field]=combinevalues($value1);
			$_SESSION[$strTableName."_asearchfortype"][$field]=$type;
			if($value2)
				$_SESSION[$strTableName."_asearchfor2"][$field]=$value2;
			$_SESSION[$strTableName."_asearchnot"][$field]=($not=="on");
		}
	}
	if($tosearch)
		$_SESSION[$strTableName."_search"]=2;
	else
		$_SESSION[$strTableName."_search"]=0;
	$_SESSION[$strTableName."_pagenumber"]=1;
}

//	process masterkey value
$mastertable=postvalue("mastertable");
if($mastertable!="")
{
	$_SESSION[$strTableName."_mastertable"]=$mastertable;
//	copy keys to session
	$i=1;
	while(isset($_REQUEST["masterkey".$i]))
	{
		$_SESSION[$strTableName."_masterkey".$i]=$_REQUEST["masterkey".$i];
		$i++;
	}
	if(isset($_SESSION[$strTableName."_masterkey".$i]))
		unset($_SESSION[$strTableName."_masterkey".$i]);
//	reset search and page number
	$_SESSION[$strTableName."_search"]=0;
	$_SESSION[$strTableName."_pagenumber"]=1;
}
else
	$mastertable=$_SESSION[$strTableName."_mastertable"];

$smarty->assign("mastertable",$mastertable);
$smarty->assign("mastertable_short",GetTableURL($mastertable));


if(@$_REQUEST["orderby"])
	$_SESSION[$strTableName."_orderby"]=@$_REQUEST["orderby"];

if(@$_REQUEST["pagesize"])
{
	$_SESSION[$strTableName."_pagesize"]=@$_REQUEST["pagesize"];
	$_SESSION[$strTableName."_pagenumber"]=1;
}

if(@$_REQUEST["goto"])
	$_SESSION[$strTableName."_pagenumber"]=@$_REQUEST["goto"];


//	process reqest data - end

$includes="";

if ($useAJAX) {
	$includes.="<script type=\"text/javascript\" src=\"include/jquery.js\"></script>\r\n";
	$includes.="<script type=\"text/javascript\" src=\"include/ajaxsuggest.js\"></script>\r\n";

}
$includes.="<script type=\"text/javascript\" src=\"include/jsfunctions.js\">".
"</script>\n".
"<script type=\"text/javascript\">".
"\nvar bSelected=false;".
"\nvar TEXT_FIRST = \""."First"."\";".
"\nvar TEXT_PREVIOUS = \""."Previous"."\";".
"\nvar TEXT_NEXT = \""."Next"."\";".
"\nvar TEXT_LAST = \""."Last"."\";".
"\nvar TEXT_PLEASE_SELECT='".jsreplace("Please select")."';".
"\nvar TEXT_SAVE='".jsreplace("Save")."';".
"\nvar TEXT_CANCEL='".jsreplace("Cancel")."';".
"\nvar TEXT_INLINE_ERROR='".jsreplace("Error occurred")."';".
"\nvar locale_dateformat = ".$locale_info["LOCALE_IDATE"].";".
"\nvar locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";".
"\nvar bLoading=false;\r\n";

if ($useAJAX) {
	$includes.="var AUTOCOMPLETE_TABLE='task_statistics_autocomplete.php';\r\n";
	$includes.="var INLINE_EDIT_TABLE='task_statistics_edit.php';\r\n";
	$includes.="var INLINE_ADD_TABLE='task_statistics_add.php';\r\n";
	$includes.="var INLINE_VIEW_TABLE='task_statistics_view.php';\r\n";
	$includes.="var SUGGEST_TABLE='task_statistics_searchsuggest.php';\r\n";
	$includes.="var MASTER_PREVIEW_TABLE='task_statistics_masterpreview.php';\r\n";
	$includes.="var SUGGEST_LOOKUP_TABLE='task_statistics_lookupsuggest.php';";
}
$includes.="\n</script>\n";
if ($useAJAX) {
$includes.="<div id=\"search_suggest\"></div>";
$includes.="<div id=\"master_details\" onmouseover=\"RollDetailsLink.showPopup();\" onmouseout=\"RollDetailsLink.hidePopup();\"></div>";
}

$smarty->assign("includes",$includes);
$smarty->assign("useAJAX",$useAJAX);


//	process session variables
//	order by
$strOrderBy="";
$order_ind=-1;

$smarty->assign("order_dir_DateField2","a");
$smarty->assign("order_dir_TimeField","a");
$smarty->assign("order_dir_Theme","a");
$smarty->assign("order_dir_Description","a");
$smarty->assign("order_dir_Category","a");
$smarty->assign("order_dir_EndTime","a");
$smarty->assign("order_dir_EndDate","a");
$smarty->assign("order_dir_details","a");
$smarty->assign("order_dir_income","a");
$smarty->assign("order_dir_outcome","a");
$smarty->assign("order_dir_Color","a");

if(@$_SESSION[$strTableName."_orderby"])
{
	$order_field=substr($_SESSION[$strTableName."_orderby"],1);
	$order_dir=substr($_SESSION[$strTableName."_orderby"],0,1);
	$order_ind=GetFieldIndex($order_field);

	$smarty->assign("order_dir_DateField2","a");
	if($order_field=="DateField2")
	{
		if($order_dir=="a")
		{
			$smarty->assign("order_dir_DateField2","d");
			$img="up";
		}
		else
			$img="down";
		$smarty->assign("order_image_DateField2","<img src=\"images/".$img.".gif\" border=0>");
	}
	$smarty->assign("order_dir_TimeField","a");
	if($order_field=="TimeField")
	{
		if($order_dir=="a")
		{
			$smarty->assign("order_dir_TimeField","d");
			$img="up";
		}
		else
			$img="down";
		$smarty->assign("order_image_TimeField","<img src=\"images/".$img.".gif\" border=0>");
	}
	$smarty->assign("order_dir_Theme","a");
	if($order_field=="Theme")
	{
		if($order_dir=="a")
		{
			$smarty->assign("order_dir_Theme","d");
			$img="up";
		}
		else
			$img="down";
		$smarty->assign("order_image_Theme","<img src=\"images/".$img.".gif\" border=0>");
	}
	$smarty->assign("order_dir_Description","a");
	if($order_field=="Description")
	{
		if($order_dir=="a")
		{
			$smarty->assign("order_dir_Description","d");
			$img="up";
		}
		else
			$img="down";
		$smarty->assign("order_image_Description","<img src=\"images/".$img.".gif\" border=0>");
	}
	$smarty->assign("order_dir_Category","a");
	if($order_field=="Category")
	{
		if($order_dir=="a")
		{
			$smarty->assign("order_dir_Category","d");
			$img="up";
		}
		else
			$img="down";
		$smarty->assign("order_image_Category","<img src=\"images/".$img.".gif\" border=0>");
	}
	$smarty->assign("order_dir_EndTime","a");
	if($order_field=="EndTime")
	{
		if($order_dir=="a")
		{
			$smarty->assign("order_dir_EndTime","d");
			$img="up";
		}
		else
			$img="down";
		$smarty->assign("order_image_EndTime","<img src=\"images/".$img.".gif\" border=0>");
	}
	$smarty->assign("order_dir_EndDate","a");
	if($order_field=="EndDate")
	{
		if($order_dir=="a")
		{
			$smarty->assign("order_dir_EndDate","d");
			$img="up";
		}
		else
			$img="down";
		$smarty->assign("order_image_EndDate","<img src=\"images/".$img.".gif\" border=0>");
	}
	$smarty->assign("order_dir_details","a");
	if($order_field=="details")
	{
		if($order_dir=="a")
		{
			$smarty->assign("order_dir_details","d");
			$img="up";
		}
		else
			$img="down";
		$smarty->assign("order_image_details","<img src=\"images/".$img.".gif\" border=0>");
	}
	$smarty->assign("order_dir_income","a");
	if($order_field=="income")
	{
		if($order_dir=="a")
		{
			$smarty->assign("order_dir_income","d");
			$img="up";
		}
		else
			$img="down";
		$smarty->assign("order_image_income","<img src=\"images/".$img.".gif\" border=0>");
	}
	$smarty->assign("order_dir_outcome","a");
	if($order_field=="outcome")
	{
		if($order_dir=="a")
		{
			$smarty->assign("order_dir_outcome","d");
			$img="up";
		}
		else
			$img="down";
		$smarty->assign("order_image_outcome","<img src=\"images/".$img.".gif\" border=0>");
	}
	$smarty->assign("order_dir_Color","a");
	if($order_field=="Color")
	{
		if($order_dir=="a")
		{
			$smarty->assign("order_dir_Color","d");
			$img="up";
		}
		else
			$img="down";
		$smarty->assign("order_image_Color","<img src=\"images/".$img.".gif\" border=0>");
	}

	if($order_ind)
	{
		if($order_dir=="a")
			$strOrderBy="order by ".($order_ind)." asc";
		else 
			$strOrderBy="order by ".($order_ind)." desc";
	}
}
if(!$strOrderBy)
	$strOrderBy=$gstrOrderBy;

//	page number
$mypage=(integer)$_SESSION[$strTableName."_pagenumber"];
if(!$mypage)
	$mypage=1;

//	page size
$PageSize=(integer)$_SESSION[$strTableName."_pagesize"];
if(!$PageSize)
	$PageSize=$gPageSize;

	$smarty->assign("rpp10_selected",($PageSize==10)?"selected":"");
	$smarty->assign("rpp20_selected",($PageSize==20)?"selected":"");
	$smarty->assign("rpp30_selected",($PageSize==30)?"selected":"");
	$smarty->assign("rpp50_selected",($PageSize==50)?"selected":"");
	$smarty->assign("rpp100_selected",($PageSize==100)?"selected":"");
	$smarty->assign("rpp500_selected",($PageSize==500)?"selected":"");

// delete record
$selected_recs=array();
if (@$_REQUEST["mdelete"])
{
	foreach(@$_REQUEST["mdelete"] as $ind)
	{
		$keys=array();
		$keys["id"]=refine($_REQUEST["mdelete1"][$ind-1]);
		$selected_recs[]=$keys;
	}
}
elseif(@$_REQUEST["selection"])
{
	foreach(@$_REQUEST["selection"] as $keyblock)
	{
		$arr=split("&",refine($keyblock));
		if(count($arr)<1)
			continue;
		$keys=array();
		$keys["id"]=urldecode(@$arr[0]);
		$selected_recs[]=$keys;
	}
}

$records_deleted=0;
foreach($selected_recs as $keys)
{
	$where = KeyWhere($keys);
//	delete only owned records
	$where = whereAdd($where,SecuritySQL("Delete"));

	$strSQL="delete from ".AddTableWrappers($strOriginalTableName)." where ".$where;
	$retval=true;
	if(function_exists("AfterDelete") || function_exists("BeforeDelete"))
	{
		$deletedrs = db_query(gSQLWhere($where),$conn);
		$deleted_values = db_fetch_array($deletedrs);
	}
	if(function_exists("BeforeDelete"))
		$retval = BeforeDelete($where,$deleted_values);
	if($retval)
	{
		$records_deleted++;
				// delete associated uploaded files if any
		DeleteUploadedFiles($where);
		LogInfo($strSQL);
		db_exec($strSQL,$conn);
		if(function_exists("AfterDelete"))
			AfterDelete($where,$deleted_values);
	}
}

if(count($selected_recs))
{
	if(function_exists("AfterMassDelete"))
		AfterMassDelete($records_deleted);
}

//	make sql "select" string

//$strSQL = $gstrSQL;
$strWhereClause="";

//	add search params

if(@$_SESSION[$strTableName."_search"]==1)
//	 regular search
{  
	$strSearchFor=trim($_SESSION[$strTableName."_searchfor"]);
	$strSearchOption=trim($_SESSION[$strTableName."_searchoption"]);
	if(@$_SESSION[$strTableName."_searchfield"])
	{
		$strSearchField = $_SESSION[$strTableName."_searchfield"];
		if($where = StrWhere($strSearchField, $strSearchFor, $strSearchOption, ""))
			$strWhereClause = whereAdd($strWhereClause,$where);
//			$strSQL = AddWhere($strSQL,$where);
		else
			$strWhereClause = whereAdd($strWhereClause,"1=0");
//			$strSQL = AddWhere($strSQL,"1=0");
	}
	else
	{
		$strWhere = "1=0";
		if($where=StrWhere("DateField2", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("TimeField", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Theme", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Description", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Category", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("EndTime", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("DayEvent", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("EndDate", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Period", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("Recurrence", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("details", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("income", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("outcome", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		$strWhereClause = whereAdd($strWhereClause,$strWhere);
//		$strSQL = AddWhere($strSQL,$strWhere);
	}
}
else if(@$_SESSION[$strTableName."_search"]==2)
//	 advanced search
{
	$sWhere="";
	foreach(@$_SESSION[$strTableName."_asearchfor"] as $f => $sfor)
		{
			$strSearchFor=trim($sfor);
			$strSearchFor2="";
			$type=@$_SESSION[$strTableName."_asearchfortype"][$f];
			if(array_key_exists($f,@$_SESSION[$strTableName."_asearchfor2"]))
				$strSearchFor2=trim(@$_SESSION[$strTableName."_asearchfor2"][$f]);
			if($strSearchFor!="" || true)
			{
				if (!$sWhere) 
				{
					if($_SESSION[$strTableName."_asearchtype"]=="and")
						$sWhere="1=1";
					else
						$sWhere="1=0";
				}
				$strSearchOption=trim($_SESSION[$strTableName."_asearchopt"][$f]);
				if($where=StrWhereAdv($f, $strSearchFor, $strSearchOption, $strSearchFor2,$type))
				{
					if($_SESSION[$strTableName."_asearchnot"][$f])
						$where="not (".$where.")";
					if($_SESSION[$strTableName."_asearchtype"]=="and")
	   					$sWhere .= " and ".$where;
					else
	   					$sWhere .= " or ".$where;
				}
			}
		}
		$strWhereClause = whereAdd($strWhereClause,$sWhere);
//		$strSQL = AddWhere($strSQL,$sWhere);
	}



if(SecuritySQL("Search"))
	$strWhereClause = whereAdd($strWhereClause,SecuritySQL("Search"));

if($mastertable=="category")
{
	$where ="";
		$where.= GetFullFieldName("Category")."=".make_db_value("Category",$_SESSION[$strTableName."_masterkey1"]);
	$strWhereClause = whereAdd($strWhereClause,$where);
//	$strSQL = AddWhere($strSQL,$where);
}

$strSQL = gSQLWhere($strWhereClause);

//	order by
$strSQL.=" ".trim($strOrderBy);

//	save SQL for use in "Export" and "Printer-friendly" pages

$_SESSION[$strTableName."_sql"] = $strSQL;
$_SESSION[$strTableName."_where"] = $strWhereClause;
$_SESSION[$strTableName."_order"] = $strOrderBy;

//	select and display records
if(CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{
	$strSQLbak = $strSQL;
	if(function_exists("BeforeQueryList"))
		BeforeQueryList($strSQL,$strWhereClause,$strOrderBy);
//	Rebuild SQL if needed
	if($strSQL!=$strSQLbak)
	{
//	changed $strSQL - old style	
		$numrows=GetRowCount($strSQL);
	}
	else
	{
		$strSQL = gSQLWhere($strWhereClause);
		$strSQL.=" ".trim($strOrderBy);
		$numrows=gSQLRowCount($strWhereClause);
	}
	LogInfo($strSQL);

//	 Pagination:
	if(!$numrows)
	{
		$smarty->assign("rowsfound",false);
		$message="No records found";
				$smarty->assign("message",$message);
	}
	else
	{
		$smarty->assign("rowsfound",true);
		$smarty->assign("records_found",$numrows);
		$maxRecords = $numrows;
		$maxpages=ceil($maxRecords/$PageSize);
		if($mypage > $maxpages)
			$mypage = $maxpages;
		if($mypage<1) 
			$mypage=1;
		$maxrecs=$PageSize;
		$smarty->assign("page",$mypage);
		$smarty->assign("maxpages",$maxpages);

//	write pagination
$smarty->assign("pagination","<script language=\"JavaScript\">WritePagination(".$mypage.",".$maxpages.");
		function GotoPage(nPageNumber)
		{
			window.location='task_statistics_list.php?goto='+nPageNumber;
		}
</script>");
		
		$strSQL.=" limit ".(($mypage-1)*$PageSize).",".$PageSize;
	}
	$rs=db_query($strSQL,$conn);

//	hide colunm headers if needed
	$recordsonpage=$numrows-($mypage-1)*$PageSize;
	if($recordsonpage>$PageSize)
	$recordsonpage=$PageSize;
	if($recordsonpage>=1)
		$smarty->assign("column1show",true);
	else
		$smarty->assign("column1show",false);

	$totals=array();
	$totals["income"]=0;
	$totals["outcome"]=0;

//	fill $rowinfo array
	$rowinfo = array();
	$shade=false;
	$recno=1;
	$editlink="";
	$copylink="";

	while($data=db_fetch_array($rs))
	{
		if(function_exists("BeforeProcessRowList"))
		{
			if(!BeforeProcessRowList($data))
				continue;
		}
		break;
	}

	while($data && $recno<=$PageSize)
	{
		$row=array();
		if(!$shade)
		{
			$row["shadeclass"]='class="shade"';
			$row["shadeclassname"]="shade";
			$shade=true;
		}
		else
		{
			$row["shadeclass"]="";
			$row["shadeclassname"]="";
			$shade=false;
		}
		for($col=1;$data && $recno<=$PageSize && $col<=1;$col++)
		{

							$totals["income"]+=($data["income"]+0);
							$totals["outcome"]+=($data["outcome"]+0);

//	key fields
			$keyblock="";
			$row[$col."id1"]=htmlspecialchars($data["id"]);
			$keyblock.= rawurlencode($data["id"]);
			$row[$col."keyblock"]=htmlspecialchars($keyblock);
			$row[$col."recno"] = $recno;
//	detail tables
//	edit page link
			$editlink="";
			$editlink.="editid1=".htmlspecialchars(rawurlencode($data["id"]));
			$row[$col."editlink"]=$editlink;

			$copylink="";
			$copylink.="copyid1=".htmlspecialchars(rawurlencode($data["id"]));
			$row[$col."copylink"]=$copylink;
			$keylink="";
			$keylink.="&key1=".htmlspecialchars(rawurlencode($data["id"]));


//	Theme - Custom
			$value="";
				$value = GetData($data,"Theme", "Custom");
			$row[$col."Theme_value"]=$value;

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
								$value=ProcessLargeText(GetDataInt($lookupvalue,$data,"Description", ""),"field=Description".$keylink,"",MODE_LIST);
			}
			else
				$value="";
			$row[$col."Description_value"]=$value;

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
								$value=ProcessLargeText(GetDataInt($lookupvalue,$data,"Category", ""),"field=Category".$keylink,"",MODE_LIST);
			}
			else
				$value="";
			$row[$col."Category_value"]=$value;

//	details - HTML
			$value="";
				$value = GetData($data,"details", "HTML");
			$row[$col."details_value"]=$value;

//	DateField2 - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"DateField2", "Short Date"),"field=DateField2".$keylink,"",MODE_LIST);
			$row[$col."DateField2_value"]=$value;

//	EndDate - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"EndDate", "Short Date"),"field=EndDate".$keylink,"",MODE_LIST);
			$row[$col."EndDate_value"]=$value;

//	Color - 
			$value="";
				$value = ProcessLargeText(GetData($data,"Color", ""),"field=Color".$keylink,"",MODE_LIST);
			$row[$col."Color_value"]=$value;

//	TimeField - Time
			$value="";
				$value = ProcessLargeText(GetData($data,"TimeField", "Time"),"field=TimeField".$keylink,"",MODE_LIST);
			$row[$col."TimeField_value"]=$value;

//	EndTime - 
			$value="";
				$value = ProcessLargeText(GetData($data,"EndTime", ""),"field=EndTime".$keylink,"",MODE_LIST);
			$row[$col."EndTime_value"]=$value;

//	income - 
			$value="";
				$value = ProcessLargeText(GetData($data,"income", ""),"field=income".$keylink,"",MODE_LIST);
			$row[$col."income_value"]=$value;

//	outcome - 
			$value="";
				$value = ProcessLargeText(GetData($data,"outcome", ""),"field=outcome".$keylink,"",MODE_LIST);
			$row[$col."outcome_value"]=$value;
			$row[$col."show"]=true;
			if(function_exists("BeforeMoveNextList"))
				BeforeMoveNextList($data,$row,$col);
				
			while($data=db_fetch_array($rs))
			{
				if(function_exists("BeforeProcessRowList"))
				{
					if(!BeforeProcessRowList($data))
						continue;
				}
				break;
			}
			$recno++;
			
		}
		$rowinfo[]=$row;
	}
	$smarty->assign("rowinfo",$rowinfo);

		//	show totals
	$total=GetTotals("income",$totals["income"],"TOTAL",$recno-1,"");
		$smarty->assign("showtotal_income", $total);
		$total=GetTotals("outcome",$totals["outcome"],"TOTAL",$recno-1,"");
		$smarty->assign("showtotal_outcome", $total);
}


if(CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{
	if($_SESSION[$strTableName."_search"]==1)
	{
		$onload = "onLoad=\"if(document.getElementById('SearchFor')) document.getElementById('ctlSearchFor').focus();\"";
		$smarty->assign("onload",$onload);
//	fill in search variables
	//	field selection
		if(@$_SESSION[$strTableName."_searchfield"]=="DateField2")
			$smarty->assign("search_DateField2","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="TimeField")
			$smarty->assign("search_TimeField","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="Theme")
			$smarty->assign("search_Theme","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="Description")
			$smarty->assign("search_Description","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="Category")
			$smarty->assign("search_Category","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="EndTime")
			$smarty->assign("search_EndTime","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="DayEvent")
			$smarty->assign("search_DayEvent","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="EndDate")
			$smarty->assign("search_EndDate","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="Period")
			$smarty->assign("search_Period","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="Recurrence")
			$smarty->assign("search_Recurrence","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="details")
			$smarty->assign("search_details","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="income")
			$smarty->assign("search_income","selected");
		if(@$_SESSION[$strTableName."_searchfield"]=="outcome")
			$smarty->assign("search_outcome","selected");
	// search type selection
		if(@$_SESSION[$strTableName."_searchoption"]=="Contains")
			$smarty->assign("search_contains_option_selected","selected");		
		if(@$_SESSION[$strTableName."_searchoption"]=="Equals")
			$smarty->assign("search_equals_option_selected","selected");		
		if(@$_SESSION[$strTableName."_searchoption"]=="Starts with ...")
			$smarty->assign("search_startswith_option_selected","selected");		
		if(@$_SESSION[$strTableName."_searchoption"]=="More than ...")
			$smarty->assign("search_more_option_selected","selected");		
		if(@$_SESSION[$strTableName."_searchoption"]=="Less than ...")
			$smarty->assign("search_less_option_selected","selected");		
		if(@$_SESSION[$strTableName."_searchoption"]=="Equal or more than ...")
			$smarty->assign("search_equalormore_option_selected","selected");		
		if(@$_SESSION[$strTableName."_searchoption"]=="Equal or less than ...")
			$smarty->assign("search_equalorless_option_selected","selected");		
		if(@$_SESSION[$strTableName."_searchoption"]=="Empty")
			$smarty->assign("search_empty_option_selected","selected");		

		$smarty->assign("search_searchfor","value=\"".htmlspecialchars(@$_SESSION[$strTableName."_searchfor"])."\"");
	}
}

$smarty->assign("userid",htmlspecialchars($_SESSION["UserID"]));


//	table selector
$strPerm = GetUserPermissions("calendar");
$smarty->assign("allow_calendar",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("category");
$smarty->assign("allow_category",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("daily");
$smarty->assign("allow_daily",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("monthly");
$smarty->assign("allow_monthly",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("weekly");
$smarty->assign("allow_weekly",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("yearly");
$smarty->assign("allow_yearly",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("users");
$smarty->assign("allow_users",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("customer");
$smarty->assign("allow_customer",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("howto");
$smarty->assign("allow_howto",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("editcalendar");
$smarty->assign("allow_Copy_of_calendar",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("customer statistics");
$smarty->assign("allow_customer_statistics",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));
$strPerm = GetUserPermissions("task statistics");
$smarty->assign("allow_task_statistics",!(strpos($strPerm, "A")===false && strpos($strPerm, "S")===false));

$smarty->assign("displayheader","<script language=\"JavaScript\">DisplayHeader();</script>");

	$smarty->assign("allow_delete",CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Delete"));
	$smarty->assign("allow_add",CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add"));
	$smarty->assign("allow_edit",CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Edit"));
	$smarty->assign("allow_export",CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Export"));
	$smarty->assign("allow_search",CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"));
	$smarty->assign("allow_deleteorexport",CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Delete") || CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Export") );


//	display master table info
$masterkeys=array();
$smarty->assign("showmasterfile","empty.htm");
if($mastertable=="category")
{
//	include proper masterlist.php code
	include("include/category_masterlist.php");
	$masterkeys[]=@$_SESSION[$strTableName."_masterkey1"];
	DisplayMasterTableInfo("task statistics", $masterkeys);
	$smarty->assign("showmasterfile","category_masterlist.htm");
}

$linkdata="";

if ($useAJAX) {
	$linkdata .= "<script type=\"text/javascript\">\r\n";
	$linkdata .= "

function SetDate(fldName, recordID)
{
	if ( $('select#month'+fldName+'_'+recordID).get(0).value!='' && $('select#day'+fldName+'_'+recordID).get(0).value!='' && $('select#year'+fldName+'_'+recordID).get(0).value!='') {
		$('input#'+fldName+'_'+recordID).get(0).value = '' + 
			$('select#year'+fldName+'_'+recordID).get(0).value + '-' + 
			$('select#month'+fldName+'_'+recordID).get(0).value + '-' + 
			$('select#day'+fldName+'_'+recordID).get(0).value;
		if ( $('input#ts'+fldName+'_'+recordID)[0] )
			$('input#ts'+fldName+'_'+recordID).get(0).value = '' + 
				$('select#day'+fldName+'_'+recordID).get(0).value + '-' + 
				$('select#month'+fldName+'_'+recordID).get(0).value + '-' + 
				$('select#year'+fldName+'_'+recordID).get(0).value;
	} else {
		if ( $('input#ts'+fldName+'_'+recordID)[0] )
			$('input#ts'+fldName+'_'+recordID).get(0).value= '10-6-2007';
		if ( $('input#'+fldName+'_'+recordID)[0] )
			$('input#'+fldName+'_'+recordID).get(0).value= '';
	}
}


function update(fldName, recordID, newDate, showTime)
{
	var dt_datetime;
	var curdate = new Date();
	dt_datetime = newDate;
	
	if ( $('select#day'+fldName+'_'+recordID)[0] ) {
		$('input#'+fldName+'_'+recordID).get(0).value = dt_datetime.getFullYear() + '-' 
			+ (dt_datetime.getMonth()+1) + '-' + dt_datetime.getDate();
		$('select#day'+fldName+'_'+recordID).get(0).selectedIndex = dt_datetime.getDate();
		$('select#month'+fldName+'_'+recordID).get(0).selectedIndex = dt_datetime.getMonth() + 1;
		for ( i=0; i<$('select#year'+fldName+'_'+recordID).get(0).options.length; i++ ) {
			if ( $('select#year'+fldName+'_'+recordID).get(0).options[i].value == dt_datetime.getFullYear() ) { 
				$('select#year'+fldName+'_'+recordID).get(0).selectedIndex = i; 
				break; 
			} 
			$('input#ts'+fldName+'_'+recordID).get(0).value = dt_datetime.getDate() + '-' + 
				( dt_datetime.getMonth() + 1 ) + '-' + dt_datetime.getFullYear();
		}
	} else {
		$('input#'+fldName+'_'+ recordID).get(0).value = print_datetime(newDate,".$locale_info["LOCALE_IDATE"].",showTime);
		$('input#ts'+fldName+'_'+ recordID).get(0).value = print_datetime(newDate,-1,showTime);
	}
}";
	$linkdata .= "</script>\r\n";

}

if ($useAJAX) {
$linkdata.="<script>
if(!$('[@disptype=control1]').length && $('[@disptype=controltable1]').length)
	$('[@disptype=controltable1]').hide();
</script>";
}
$smarty->assign("linkdata",$linkdata);

$strSQL=$_SESSION[$strTableName."_sql"];
$smarty->assign("guest",$_SESSION["AccessLevel"] == ACCESS_LEVEL_GUEST);

$templatefile = "task_statistics_list.htm";
if(function_exists("BeforeShowList"))
	BeforeShowList($smarty,$templatefile);

$smarty->display($templatefile);

