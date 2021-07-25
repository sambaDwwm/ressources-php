<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/customer_statistics_variables.php");

//	check if logged in
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

//connect database
$conn = db_connect();

include('libs/Smarty.class.php');
$smarty = new Smarty();

//	Before Process event
if(function_exists("BeforeProcessSearch"))
	BeforeProcessSearch($conn);


$includes=
"<STYLE>
	.vis1	{ visibility:\"visible\" }
	.vis2	{ visibility:\"hidden\" }
</STYLE>
<script language=\"JavaScript\" src=\"include/calendar.js\"></script>
<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
if ($useAJAX) {
$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>
<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
}
$includes.="<script language=\"JavaScript\" type=\"text/javascript\">\r\n".
"var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
"var bLoading=false;\r\n".
"var TEXT_PLEASE_SELECT='".addslashes("Please select")."';\r\n";
if ($useAJAX) {
$includes.="var SUGGEST_TABLE = \"customer_statistics_searchsuggest.php\";\r\n".
"var SUGGEST_LOOKUP_TABLE='customer_statistics_lookupsuggest.php';\r\n".
"var AUTOCOMPLETE_TABLE=\"customer_statistics_autocomplete.php\";\r\n";
}
$includes.="var detect = navigator.userAgent.toLowerCase();

function checkIt(string)
{
	place = detect.indexOf(string) + 1;
	thestring = string;
	return place;
}


function ShowHideControls()
{
	document.getElementById('second_DateField2').style.display =  
		document.forms.editform.elements['asearchopt_DateField2'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_TimeField').style.display =  
		document.forms.editform.elements['asearchopt_TimeField'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Theme').style.display =  
		document.forms.editform.elements['asearchopt_Theme'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Description').style.display =  
		document.forms.editform.elements['asearchopt_Description'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Category').style.display =  
		document.forms.editform.elements['asearchopt_Category'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_EndTime').style.display =  
		document.forms.editform.elements['asearchopt_EndTime'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_DayEvent').style.display =  
		document.forms.editform.elements['asearchopt_DayEvent'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_EndDate').style.display =  
		document.forms.editform.elements['asearchopt_EndDate'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Period').style.display =  
		document.forms.editform.elements['asearchopt_Period'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_Recurrence').style.display =  
		document.forms.editform.elements['asearchopt_Recurrence'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_details').style.display =  
		document.forms.editform.elements['asearchopt_details'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_income').style.display =  
		document.forms.editform.elements['asearchopt_income'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_outcome').style.display =  
		document.forms.editform.elements['asearchopt_outcome'].value==\"Between\" ? '' : 'none'; 
	return false;
}
function ResetControls()
{
	var i;
	e = document.forms[0].elements; 
	for (i=0;i<e.length;i++) 
	{
		if (e[i].name!='type' && e[i].className!='button' && e[i].type!='hidden')
		{
			if(e[i].type=='select-one')
				e[i].selectedIndex=0;
			else if(e[i].type=='select-multiple')
			{
				var j;
				for(j=0;j<e[i].options.length;j++)
					e[i].options[j].selected=false;
			}
			else if(e[i].type=='checkbox' || e[i].type=='radio')
				e[i].checked=false;
			else 
				e[i].value = ''; 
		}
		else if(e[i].name.substr(0,6)=='value_' && e[i].type=='hidden')
			e[i].value = ''; 
	}
	ShowHideControls();	
	return false;
}";

if ($useAJAX) {
$includes.="
$(document).ready(function() {
	document.forms.editform.value_TimeField.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_TimeField,'advanced')};
	document.forms.editform.value1_TimeField.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_TimeField,'advanced1')};
	document.forms.editform.value_TimeField.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_TimeField,'advanced')};
	document.forms.editform.value1_TimeField.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_TimeField,'advanced1')};
	document.forms.editform.value_Theme.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Theme,'advanced')};
	document.forms.editform.value1_Theme.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Theme,'advanced1')};
	document.forms.editform.value_Theme.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Theme,'advanced')};
	document.forms.editform.value1_Theme.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Theme,'advanced1')};
	document.forms.editform.value_EndTime.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_EndTime,'advanced')};
	document.forms.editform.value1_EndTime.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_EndTime,'advanced1')};
	document.forms.editform.value_EndTime.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_EndTime,'advanced')};
	document.forms.editform.value1_EndTime.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_EndTime,'advanced1')};
	document.forms.editform.value_DayEvent.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_DayEvent,'advanced')};
	document.forms.editform.value1_DayEvent.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_DayEvent,'advanced1')};
	document.forms.editform.value_DayEvent.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_DayEvent,'advanced')};
	document.forms.editform.value1_DayEvent.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_DayEvent,'advanced1')};
	document.forms.editform.value_Period.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Period,'advanced')};
	document.forms.editform.value1_Period.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Period,'advanced1')};
	document.forms.editform.value_Period.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Period,'advanced')};
	document.forms.editform.value1_Period.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Period,'advanced1')};
	document.forms.editform.value_Recurrence.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_Recurrence,'advanced')};
	document.forms.editform.value1_Recurrence.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_Recurrence,'advanced1')};
	document.forms.editform.value_Recurrence.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_Recurrence,'advanced')};
	document.forms.editform.value1_Recurrence.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_Recurrence,'advanced1')};
	document.forms.editform.value_income.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_income,'advanced')};
	document.forms.editform.value1_income.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_income,'advanced1')};
	document.forms.editform.value_income.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_income,'advanced')};
	document.forms.editform.value1_income.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_income,'advanced1')};
	document.forms.editform.value_outcome.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_outcome,'advanced')};
	document.forms.editform.value1_outcome.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_outcome,'advanced1')};
	document.forms.editform.value_outcome.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_outcome,'advanced')};
	document.forms.editform.value1_outcome.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_outcome,'advanced1')};
});
</script>
<div id=\"search_suggest\"></div>
";
} else {
$includes.="
function OnKeyDown(e)
{ if(!e) e = window.event; 
if (e.keyCode == 13){ e.cancel = true; document.forms[0].submit();} }

</script>";
}

$smarty->assign("includes",$includes);
$smarty->assign("noAJAX",!$useAJAX);

$onload="onLoad=\"javascript: ShowHideControls();\"";
$smarty->assign("onload",$onload);

if(@$_SESSION[$strTableName."_asearchtype"]=="or")
{
	$smarty->assign("any_checked"," checked");
	$smarty->assign("all_checked","");
}
else
{
	$smarty->assign("any_checked","");
	$smarty->assign("all_checked"," checked");
}

$editformats=array();

// DateField2 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["DateField2"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["DateField2"];
	$smarty->assign("value_DateField2",@$_SESSION[$strTableName."_asearchfor"]["DateField2"]);
	$smarty->assign("value1_DateField2",@$_SESSION[$strTableName."_asearchfor2"]["DateField2"]);
}	
if($not)
	$smarty->assign("not_DateField2"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_DateField2\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_DateField2",$searchtype);
//	edit format
$editformats["DateField2"]="Date";
// TimeField 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["TimeField"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["TimeField"];
	$smarty->assign("value_TimeField",@$_SESSION[$strTableName."_asearchfor"]["TimeField"]);
	$smarty->assign("value1_TimeField",@$_SESSION[$strTableName."_asearchfor2"]["TimeField"]);
}	
if($not)
	$smarty->assign("not_TimeField"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_TimeField\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_TimeField",$searchtype);
//	edit format
$editformats["TimeField"]="Text field";
// Theme 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Theme"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Theme"];
	$smarty->assign("value_Theme",@$_SESSION[$strTableName."_asearchfor"]["Theme"]);
	$smarty->assign("value1_Theme",@$_SESSION[$strTableName."_asearchfor2"]["Theme"]);
}	
if($not)
	$smarty->assign("not_Theme"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Theme\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_Theme",$searchtype);
//	edit format
$editformats["Theme"]="Text field";
// Description 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Description"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Description"];
	$smarty->assign("value_Description",@$_SESSION[$strTableName."_asearchfor"]["Description"]);
	$smarty->assign("value1_Description",@$_SESSION[$strTableName."_asearchfor2"]["Description"]);
}	
if($not)
	$smarty->assign("not_Description"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Description\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_Description",$searchtype);
//	edit format
$editformats["Description"]="Lookup wizard";
// Category 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Category"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Category"];
	$smarty->assign("value_Category",@$_SESSION[$strTableName."_asearchfor"]["Category"]);
	$smarty->assign("value1_Category",@$_SESSION[$strTableName."_asearchfor2"]["Category"]);
}	
if($not)
	$smarty->assign("not_Category"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Category\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_Category",$searchtype);
//	edit format
$editformats["Category"]="Lookup wizard";
// EndTime 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["EndTime"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["EndTime"];
	$smarty->assign("value_EndTime",@$_SESSION[$strTableName."_asearchfor"]["EndTime"]);
	$smarty->assign("value1_EndTime",@$_SESSION[$strTableName."_asearchfor2"]["EndTime"]);
}	
if($not)
	$smarty->assign("not_EndTime"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_EndTime\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_EndTime",$searchtype);
//	edit format
$editformats["EndTime"]="Text field";
// DayEvent 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["DayEvent"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["DayEvent"];
	$smarty->assign("value_DayEvent",@$_SESSION[$strTableName."_asearchfor"]["DayEvent"]);
	$smarty->assign("value1_DayEvent",@$_SESSION[$strTableName."_asearchfor2"]["DayEvent"]);
}	
if($not)
	$smarty->assign("not_DayEvent"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_DayEvent\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_DayEvent",$searchtype);
//	edit format
$editformats["DayEvent"]="Text field";
// EndDate 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["EndDate"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["EndDate"];
	$smarty->assign("value_EndDate",@$_SESSION[$strTableName."_asearchfor"]["EndDate"]);
	$smarty->assign("value1_EndDate",@$_SESSION[$strTableName."_asearchfor2"]["EndDate"]);
}	
if($not)
	$smarty->assign("not_EndDate"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_EndDate\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_EndDate",$searchtype);
//	edit format
$editformats["EndDate"]="Date";
// Period 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Period"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Period"];
	$smarty->assign("value_Period",@$_SESSION[$strTableName."_asearchfor"]["Period"]);
	$smarty->assign("value1_Period",@$_SESSION[$strTableName."_asearchfor2"]["Period"]);
}	
if($not)
	$smarty->assign("not_Period"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Period\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_Period",$searchtype);
//	edit format
$editformats["Period"]="Text field";
// Recurrence 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["Recurrence"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["Recurrence"];
	$smarty->assign("value_Recurrence",@$_SESSION[$strTableName."_asearchfor"]["Recurrence"]);
	$smarty->assign("value1_Recurrence",@$_SESSION[$strTableName."_asearchfor2"]["Recurrence"]);
}	
if($not)
	$smarty->assign("not_Recurrence"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_Recurrence\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_Recurrence",$searchtype);
//	edit format
$editformats["Recurrence"]="Text field";
// details 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["details"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["details"];
	$smarty->assign("value_details",@$_SESSION[$strTableName."_asearchfor"]["details"]);
	$smarty->assign("value1_details",@$_SESSION[$strTableName."_asearchfor2"]["details"]);
}	
if($not)
	$smarty->assign("not_details"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_details\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_details",$searchtype);
//	edit format
$editformats["details"]=EDIT_FORMAT_TEXT_FIELD;
// income 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["income"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["income"];
	$smarty->assign("value_income",@$_SESSION[$strTableName."_asearchfor"]["income"]);
	$smarty->assign("value1_income",@$_SESSION[$strTableName."_asearchfor2"]["income"]);
}	
if($not)
	$smarty->assign("not_income"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_income\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_income",$searchtype);
//	edit format
$editformats["income"]="Text field";
// outcome 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["outcome"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["outcome"];
	$smarty->assign("value_outcome",@$_SESSION[$strTableName."_asearchfor"]["outcome"]);
	$smarty->assign("value1_outcome",@$_SESSION[$strTableName."_asearchfor2"]["outcome"]);
}	
if($not)
	$smarty->assign("not_outcome"," checked");
//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_outcome\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_outcome",$searchtype);
//	edit format
$editformats["outcome"]="Text field";

$linkdata="";

$linkdata .= "<script type=\"text/javascript\">\r\n";

if ($useAJAX) {
}
else
{
}
$linkdata.="</script>\r\n";

$smarty->assign("linkdata",$linkdata);

$templatefile = "customer_statistics_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($smarty,$templatefile);

$smarty->display($templatefile);

?>