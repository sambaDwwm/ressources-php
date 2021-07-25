<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/customer_variables.php");

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
$includes.="var SUGGEST_TABLE = \"customer_searchsuggest.php\";\r\n".
"var SUGGEST_LOOKUP_TABLE='customer_lookupsuggest.php';\r\n".
"var AUTOCOMPLETE_TABLE=\"customer_autocomplete.php\";\r\n";
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
	document.getElementById('second_name').style.display =  
		document.forms.editform.elements['asearchopt_name'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_contact').style.display =  
		document.forms.editform.elements['asearchopt_contact'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_telephones').style.display =  
		document.forms.editform.elements['asearchopt_telephones'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_postinfo').style.display =  
		document.forms.editform.elements['asearchopt_postinfo'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_customerdetails').style.display =  
		document.forms.editform.elements['asearchopt_customerdetails'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_username').style.display =  
		document.forms.editform.elements['asearchopt_username'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_password').style.display =  
		document.forms.editform.elements['asearchopt_password'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_email').style.display =  
		document.forms.editform.elements['asearchopt_email'].value==\"Between\" ? '' : 'none'; 
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
	document.forms.editform.value_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_name,'advanced')};
	document.forms.editform.value1_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_name,'advanced1')};
	document.forms.editform.value_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_name,'advanced')};
	document.forms.editform.value1_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_name,'advanced1')};
	document.forms.editform.value_contact.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_contact,'advanced')};
	document.forms.editform.value1_contact.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_contact,'advanced1')};
	document.forms.editform.value_contact.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_contact,'advanced')};
	document.forms.editform.value1_contact.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_contact,'advanced1')};
	document.forms.editform.value_telephones.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_telephones,'advanced')};
	document.forms.editform.value1_telephones.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_telephones,'advanced1')};
	document.forms.editform.value_telephones.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_telephones,'advanced')};
	document.forms.editform.value1_telephones.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_telephones,'advanced1')};
	document.forms.editform.value_postinfo.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_postinfo,'advanced')};
	document.forms.editform.value1_postinfo.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_postinfo,'advanced1')};
	document.forms.editform.value_postinfo.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_postinfo,'advanced')};
	document.forms.editform.value1_postinfo.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_postinfo,'advanced1')};
	document.forms.editform.value_username.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_username,'advanced')};
	document.forms.editform.value1_username.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_username,'advanced1')};
	document.forms.editform.value_username.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_username,'advanced')};
	document.forms.editform.value1_username.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_username,'advanced1')};
	document.forms.editform.value_password.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_password,'advanced')};
	document.forms.editform.value1_password.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_password,'advanced1')};
	document.forms.editform.value_password.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_password,'advanced')};
	document.forms.editform.value1_password.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_password,'advanced1')};
	document.forms.editform.value_email.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_email,'advanced')};
	document.forms.editform.value1_email.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_email,'advanced1')};
	document.forms.editform.value_email.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_email,'advanced')};
	document.forms.editform.value1_email.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_email,'advanced1')};
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

// name 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["name"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["name"];
	$smarty->assign("value_name",@$_SESSION[$strTableName."_asearchfor"]["name"]);
	$smarty->assign("value1_name",@$_SESSION[$strTableName."_asearchfor2"]["name"]);
}	
if($not)
	$smarty->assign("not_name"," checked");
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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_name\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_name",$searchtype);
//	edit format
$editformats["name"]="Text field";
// contact 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["contact"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["contact"];
	$smarty->assign("value_contact",@$_SESSION[$strTableName."_asearchfor"]["contact"]);
	$smarty->assign("value1_contact",@$_SESSION[$strTableName."_asearchfor2"]["contact"]);
}	
if($not)
	$smarty->assign("not_contact"," checked");
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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_contact\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_contact",$searchtype);
//	edit format
$editformats["contact"]="Text field";
// telephones 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["telephones"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["telephones"];
	$smarty->assign("value_telephones",@$_SESSION[$strTableName."_asearchfor"]["telephones"]);
	$smarty->assign("value1_telephones",@$_SESSION[$strTableName."_asearchfor2"]["telephones"]);
}	
if($not)
	$smarty->assign("not_telephones"," checked");
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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_telephones\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_telephones",$searchtype);
//	edit format
$editformats["telephones"]="Text field";
// postinfo 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["postinfo"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["postinfo"];
	$smarty->assign("value_postinfo",@$_SESSION[$strTableName."_asearchfor"]["postinfo"]);
	$smarty->assign("value1_postinfo",@$_SESSION[$strTableName."_asearchfor2"]["postinfo"]);
}	
if($not)
	$smarty->assign("not_postinfo"," checked");
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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_postinfo\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_postinfo",$searchtype);
//	edit format
$editformats["postinfo"]="Text field";
// customerdetails 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["customerdetails"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["customerdetails"];
	$smarty->assign("value_customerdetails",@$_SESSION[$strTableName."_asearchfor"]["customerdetails"]);
	$smarty->assign("value1_customerdetails",@$_SESSION[$strTableName."_asearchfor2"]["customerdetails"]);
}	
if($not)
	$smarty->assign("not_customerdetails"," checked");
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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_customerdetails\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_customerdetails",$searchtype);
//	edit format
$editformats["customerdetails"]=EDIT_FORMAT_TEXT_FIELD;
// username 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["username"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["username"];
	$smarty->assign("value_username",@$_SESSION[$strTableName."_asearchfor"]["username"]);
	$smarty->assign("value1_username",@$_SESSION[$strTableName."_asearchfor2"]["username"]);
}	
if($not)
	$smarty->assign("not_username"," checked");
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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_username\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_username",$searchtype);
//	edit format
$editformats["username"]="Text field";
// password 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["password"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["password"];
	$smarty->assign("value_password",@$_SESSION[$strTableName."_asearchfor"]["password"]);
	$smarty->assign("value1_password",@$_SESSION[$strTableName."_asearchfor2"]["password"]);
}	
if($not)
	$smarty->assign("not_password"," checked");
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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_password\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_password",$searchtype);
//	edit format
$editformats["password"]="Text field";
// email 
$opt="";
$not=false;
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["email"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["email"];
	$smarty->assign("value_email",@$_SESSION[$strTableName."_asearchfor"]["email"]);
	$smarty->assign("value1_email",@$_SESSION[$strTableName."_asearchfor2"]["email"]);
}	
if($not)
	$smarty->assign("not_email"," checked");
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
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_email\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$smarty->assign("searchtype_email",$searchtype);
//	edit format
$editformats["email"]="Text field";

$linkdata="";

$linkdata .= "<script type=\"text/javascript\">\r\n";

if ($useAJAX) {
}
else
{
}
$linkdata.="</script>\r\n";

$smarty->assign("linkdata",$linkdata);

$templatefile = "customer_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($smarty,$templatefile);

$smarty->display($templatefile);

?>