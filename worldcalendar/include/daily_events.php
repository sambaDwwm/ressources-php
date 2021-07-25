<?php



// Add page: Before process
function BeforeProcessAdd(&$conn)
{
if ($_REQUEST["mon"]) 
	$mon=$_REQUEST["mon"];
else
	if (!@$_SESSION["mon"]) 
		$mon=date("n",strtotime(now()));
	else
		$mon=$_SESSION["mon"];

if ($_REQUEST["yr"]) 
	$yr=$_REQUEST["yr"];
else
	if (!@$_SESSION["yr"]) 
		$yr=date("Y",strtotime(Now()));
	else
		$yr=$_SESSION["yr"];

if ($_REQUEST["days"]) 
	$days=$_REQUEST["days"]+0;
else
	if (!$_SESSION["days"]) 
		$days=date("j",strtotime(Now()));
	else
		$days=$_SESSION["days"];

if ($_REQUEST["wind"])
	$_SESSION["row00window_value"]=$_REQUEST["wind"];


$_SESSION["days"]=$days;
$_SESSION["mon"]=$mon;
$_SESSION["yr"]=$yr;


} // function BeforeProcessAdd














































// After record added
function AfterAdd(&$values,&$keys,$inline)
{
global $conn;

if ($_SESSION["row00window_value"]!="daily" && $_SESSION["row00window_value"]!="calendar")
{
   if (!$values["Recurrence"])
   {
       $tmpSQL="select max(id) as maxid from category";
       $rstmp = db_query($tmpSQL,$conn);
       $datatmp = db_fetch_array($rstmp);
       $kol = $datatmp["maxid"];

       $tmpSQL="select id,Color,Category from category order by id";
       $rstmp2 = db_query($tmpSQL,$conn);

       ?><script>
       var a = new Array(<?php echo $kol?>);
       <?php        
       while ($datatmp2 = db_fetch_array($rstmp2))
           echo "a[".$datatmp2["id"]."]='".$datatmp2["Color"]."';";
       ?>
var txt,strtmp,strColor,stime,c1,c2,devent,k,kolstr,c,sdate,kk,cd;
cd="#F2F2F2";
if("<?php echo date("w",strtotime($values["DateField"]))?>"=="0" || "<?php echo date("w",strtotime($values["DateField"]))?>"=="6")
    cd="shade";
strtmp="<?php echo $values["Theme"]?>";
sdate=<?php echo round((strtotime(now())-strtotime($values["DateField"]." ".$values["TimeField"]))/60,0)?>;
sdate1=<?php echo strtotime(date("Y-m-d"))-strtotime($values["DateField"])?>;
c="<?php echo $values["Category"]?>";
if(c=="" || c==null)
	strColor=cd;
else
	strColor=a[c];
stime="<?php echo dbvalue2time($values["TimeField"])?>";
kolstr=0;
kolstr=parent.document.getElementById("kol<?php echo $_SESSION["days"]?>").value;
k=2;
kk=2;
if("<?php echo $_SESSION["row00window_value"]?>"=="weekly")
{
	kolstr=0;
	k=3;
	kk=4;
}
devent="<?php echo $values["DayEvent"]?>";
if(kolstr<5)
{
	kolstr++;
	txt="<table cellpadding=0 cellspacing=0 width=100% border=0><tr><td>";
	txt+="<table cellpadding=1 cellspacing=0 width=100% border=0 style='border: 1px solid gray' id=edit<?php echo $keys["id"]?>>";
	txt+="<tr><td width=7 style='background-color:" + strColor + "'></td>";
	c1=cd;
	c2=cd;
	if(devent!="" && devent!=0)
		c1=strColor;
	else
		c2=strColor;
	txt+="<td colspan="+kk+" style='background-color:" + c1 + "' height=3></td></tr>";
	txt+="<tr><td width=7 style='background-color:" + c2 + "' valign=top></td><td>";
	if(devent=="" || devent==0)
	{
		txt+="<font face=Arial>";
		if("<?php echo $_SESSION["row00window_value"]?>"!="monthly")
			txt+="Time:&nbsp;";
		txt+=stime+"</font>&nbsp;";
		k=1;
	}
	txt+="<a href='' onclick='edit_div(<?php echo date("j",strtotime($values["DateField"]))?>,<?php echo date("n",strtotime($values["DateField"]))?>,<?php echo date("Y",strtotime($values["DateField"]))?>,<?php echo $keys["id"]?>);return false;'><b>";
	if("<?php echo $_SESSION["row00window_value"]?>"=="monthly")
	{
		if(strtmp.length>15)
			kolstr++;
		if(sdate>=0 && devent!=1 || sdate1>0 && devent==1)
			txt+="<font color=red>";
		if(strtmp.length>30)
			strtmp=strtmp.substr(0,27)+"...";
		if(strtmp.length>15 && kolstr>5) 	
			strtmp=strtmp.substr(0,12)+"..." ;
		if(strtmp.length==0 || strtmp==null) 
			strtmp="&lt;Empty&gt;";
		txt+=strtmp;
		if(sdate>0 && devent!=1 || sdate1>0 && devent==1)
			txt+="</font>";
		txt+="</b></a></td></tr></table></td></tr>";
		if(kolstr<=5)
			txt+="<tr><td colspan=3 height=1></td></tr>";
	}
	else
	{
		if(sdate>0 && devent!=1 || sdate1>0 && devent==1)
			txt+="<font color=red>";
		if(strtmp.length>50)
			strtmp=strtmp.substr(0,50)+"...";
		if(strtmp.length==0 || strtmp==null) 
			strtmp="&lt;Empty&gt;";
		txt+=strtmp;
		if(sdate>0 && devent!=1 || sdate1>0 && devent==1)
			txt+="</font>";
		txt+="</b></a></td>";
		strtmp="<?php echo str_replace(array("\"","\r\n"),array("\\\"","<br>"),$values["Description"])?>";
		if(strtmp.length>300)
			strtmp=strtmp.substr(0,300)+"...";
		txt+="<td width=800>"+strtmp+"</td></tr>";
		txt+="</table></td></tr>";
		txt+="<tr><td colspan=3 height=1></td></tr>";
	}
	txt+="</table>";
	parent.document.getElementById("add<?php echo date("j",strtotime($values["DateField"]))?>").innerHTML+=txt;
	parent.document.getElementById("kol<?php echo date("j",strtotime($values["DateField"]))?>").value=kolstr;
}
else
{
	if("<?php echo $_SESSION["row00window_value"]?>"=="monthly")
		parent.document.getElementById("more<?php echo date("j",strtotime($values["DateField"]))?>").style.display="";
}
if("<?php echo $_SESSION["row00window_value"]?>"=="monthly")
{
var s=parent.document.getElementById("count<?php echo date("j",strtotime($values["DateField"]))?>").innerHTML;
if(s=="") 
	s=0;
else
	s=parseInt(parent.document.getElementById("count<?php echo date("j",strtotime($values["DateField"]))?>").innerHTML);
parent.document.getElementById("count<?php echo date("j",strtotime($values["DateField"]))?>").innerHTML=s+1;
}

parent.document.getElementById("addnew").style.display="none";
</script>
<?php
}
else
{
?>
<script>
if("<?php echo $_SESSION["row00window_value"]?>"=="monthly")
	parent.window.location.href="monthly_list.php";
if("<?php echo $_SESSION["row00window_value"]?>"=="weekly")
	parent.window.location.href="weekly_list.php";
</script>
<?php
}
}
} // function AfterAdd






















































// Add page: Before display
function BeforeShowAdd(&$smarty,&$templatefile)
{
$smarty->assign("row00window_value",$_SESSION["row00window_value"]);

//added
$smarty->assign("row00header_value","");
$smarty->assign("row00form_value","");
if ($_SESSION["row00window_value"]!="daily" && $_SESSION["row00window_value"]!="calendar")
	$smarty->assign("row00header_value","style=\"cursor:move;\" onmousedown=\"omd(event);\" onmouseup=\"mclick=0;\"");
$smarty->assign("hshow",true);
if ($_SESSION["row00window_value"]=="monthly" || $_SESSION["row00window_value"]=="weekly")
{
	$smarty->assign("row00form_value","target=iframelist");
	$smarty->assign("hshow",false);
}

} // function BeforeShowAdd















// Edit page: Before process
function BeforeProcessEdit(&$conn)
{
if ($_REQUEST["wind"])
	$_SESSION["row00window_value"]=$_REQUEST["wind"];

if ($_REQUEST["mon"])	 
	$mon=$_REQUEST["mon"];
else
	if (!@$_SESSION["mon"]) 
		$mon=date("n",strtotime(now()));
	else
		$mon=$_SESSION["mon"];

if ($_REQUEST["yr"]) 
	$yr=$_REQUEST["yr"];
else
	if (!@$_SESSION["yr"]) 
		$yr=date("Y",strtotime(now()));
	else
		$yr=$_SESSION["yr"];

if ($_REQUEST["days"]) 
	$days=$_REQUEST["days"]+0;
else
	if (!$_SESSION["days"]) 
		$days=date("j",strtotime(Now()));
	else
		$days=$_SESSION["days"];

$_SESSION["days"]=$days;
$_SESSION["mon"]=$mon;
$_SESSION["yr"]=$yr;

if ($_REQUEST["delete"])
{
$strDelete = "delete from calendar where id=".$_REQUEST["editid1"];
db_exec($strDelete,$conn);
?>
<script>
	if("<?php echo $_SESSION["row00window_value"]?>"=="monthly")
		parent.window.location.href="monthly_list.php";
	if("<?php echo $_SESSION["row00window_value"]?>"=="weekly")
		parent.window.location.href="weekly_list.php";
</script>
<?php
}
} // function BeforeProcessEdit






























































// Edit page: Before display
function BeforeShowEdit(&$smarty,&$templatefile)
{
$smarty->assign("row00window_value",$_SESSION["row00window_value"]);

$recurrence = $smarty->get_template_vars("value_Recurrence");
if ($recurrence==1)
$smarty->assign("show_Hiden","style=\"display:'';\"");
else
$smarty->assign("show_Hiden","style=\"display:none;\"");

//added
$smarty->assign("row00header_value","");
$smarty->assign("row00form_value","");
if ($_SESSION["row00window_value"]!="daily" && $_SESSION["row00window_value"]!="calendar")
	$smarty->assign("row00header_value","style=\"cursor:move;\" onmousedown=\"omd(event);\" onmouseup=\"mclick=0;\"");
$smarty->assign("hshow",true);
if ($_SESSION["row00window_value"]=="monthly" || $_SESSION["row00window_value"]=="weekly")
{
	$smarty->assign("row00form_value","target=iframelist");
	$smarty->assign("hshow",false);
}

//added2
if ($smarty->get_template_vars("value_TimeField")!="")
{
    $tm1 = localtime(strtotime($smarty->get_template_vars("value_TimeField")));
    $ar1 = array(0,0,0,$tm1[2],$tm1[1],0);
    $smarty->assign("value_TimeField",format_time($ar1));
}
if ($smarty->get_template_vars("value_EndTime")!="")
{    
    $tm2 = localtime(strtotime($smarty->get_template_vars("value_EndTime")));
    $ar2 = array(0,0,0,$tm2[2],$tm2[1],0);    
    $smarty->assign("value_EndTime",format_time($ar2));
}
} // function BeforeShowEdit


























// After record updated
function AfterEdit(&$values, $where, &$oldvalues, &$keys,$inline)
{
global $conn;
if ($_SESSION["row00window_value"]!="daily" && $_SESSION["row00window_value"]!="calendar")
{
    if(!$values["Recurrence"] && !$oldvalues["Recurrence"])
    {
        $tmpSQL="select max(id) as maxid from category";
        $rstmp = db_query($tmpSQL,$conn);
        $datatmp = db_fetch_array($rstmp);
        $kol = $datatmp["maxid"];
    
        $tmpSQL="select id,Color,Category from category order by id";
        $rstmp2 = db_query($tmpSQL,$conn);

        ?><script>
        var a = new Array(<?php echo $kol?>);
        <?php
        while ($datatmp2 = db_fetch_array($rstmp2))
            echo "a[".$datatmp2["id"]."]='".$datatmp2["Color"]."';";
        ?>
var txt,strtmp,strColor,stime,c1,c2,devent,k,kolstr,c,sdate,kk,desc,cd,kolold;
strtmp="<?php echo $values["Theme"]?>";
kolold=parseInt("<?php echo strlen($oldvalues["Theme"])/15?>");
sdate=<?php echo round((strtotime(now())-strtotime($values["DateField"]." ".$values["TimeField"]))/60,0)?>;
cd=parent.document.getElementById("edit<?php echo $keys["id"]?>").rows[1].cells[1].style.backgroundColor;
if("<?php echo date("w",strtotime($values["DateField"]))?>"=="0" || "<?php echo date("w",strtotime($values["DateField"]))?>"=="6")
    cd=parent.document.getElementById("edit<?php echo $keys["id"]?>").style.backgroundColor;
c="<?php echo $values["Category"]?>";
if(c=="" || c==null)
    strColor=cd;
else
    strColor=a[c];
stime="<?php echo dbvalue2time($values["TimeField"])?>";
kolstr=0;
txt="";
kolstr=parent.document.getElementById("kol<?php echo $_SESSION["days"]?>").value;
if("<?php echo $_SESSION["row00window_value"]?>"=="weekly")
    kolstr=0;
devent="<?php echo $values["DayEvent"]?>";

    c1=cd;
    c2=cd;
    if(strtmp.length>15 && kolold<2)
        kolstr++;
    if(strtmp.length<=15 && kolold>1)
        kolstr--;
    if(devent!="" && devent!=0)
        c1=strColor;
    else
        c2=strColor;
    if(devent=="" || devent==0)
    {
        txt+="<font face=Arial>";
        if("<?php echo $_SESSION["row00window_value"]?>"!="monthly")
            txt+="Time:&nbsp;";
        txt+=stime+"</font>&nbsp;";
        k=1;
    }
    txt+="<a href='' onclick='edit_div(<?php echo date("j",strtotime($values["DateField"]))?>,<?php echo date("n",strtotime($values["DateField"]))?>,<?php echo date("Y",strtotime($values["DateField"]))?>,<?php echo $keys["id"]?>);return false;'><b>";
    if("<?php echo $_SESSION["row00window_value"]?>"=="monthly")
    {
        if(sdate>0)
            txt+="<font color=red>";
        if(strtmp.length>30)
            strtmp=strtmp.substr(0,27)+"...";
        if(strtmp.length>15 && kolstr>5)     
            strtmp=strtmp.substr(0,12)+"..." ;
        if(strtmp.length==0 || strtmp==null) 
            strtmp="&lt;Empty&gt;";
        txt+=strtmp;
        if(sdate>0)
            txt+="</font>";
        txt+="</b></a>";
    }
    else
    {
        if(sdate>0)
            txt+="<font color=red>";
        if(strtmp.length>50)
            strtmp=strtmp.substr(0,50)+"...";
        if(strtmp.length==0 || strtmp==null) 
            strtmp="&lt;Empty&gt;";
        txt+=strtmp;
        if(sdate>0)
            txt+="</font>";
        txt+="</b></a></td>";
        desc="<?php echo str_replace(array("\"","\r\n"),array("\\\"","<br>"),$values["Description"])?>";
        if(desc.length>300)
            desc=desc.substr(0,300)+"...";
    }
    parent.document.getElementById("edit<?php echo $keys["id"]?>").rows[1].cells[1].innerHTML="";

    parent.document.getElementById("edit<?php echo $keys["id"]?>").rows[0].cells[0].style.backgroundColor=strColor;
    parent.document.getElementById("edit<?php echo $keys["id"]?>").rows[0].cells[1].style.backgroundColor=c1;
    parent.document.getElementById("edit<?php echo $keys["id"]?>").rows[1].cells[0].style.backgroundColor=c2;
    parent.document.getElementById("edit<?php echo $keys["id"]?>").rows[1].cells[1].innerHTML=txt;
    if("<?php echo $_SESSION["row00window_value"]?>"=="weekly")
    {
        parent.document.getElementById("edit<?php echo $keys["id"]?>").rows[1].cells[2].innerHTML="";
        parent.document.getElementById("edit<?php echo $keys["id"]?>").rows[1].cells[2].innerHTML=desc;
    }
if(kolstr>5)
	kolstr=5;
parent.document.getElementById("kol<?php echo date("j",strtotime($values["DateField"]))?>").value=kolstr;
parent.document.getElementById("addnew").style.display="none";
</script>
<?php
}
else
{
?>
<script>
if("<?php echo $_SESSION["row00window_value"]?>"=="monthly")
	parent.window.location.href="monthly_list.php";
if("<?php echo $_SESSION["row00window_value"]?>"=="weekly")
	parent.window.location.href="weekly_list.php";
</script>
<?php

}
}
} // function AfterEdit























// List page: Before process
function BeforeProcessList(&$conn)
{
if ($_REQUEST["mon"]) 
	$mon=$_REQUEST["mon"];
else
	if (!@$_SESSION["mon"]) 
		$mon=date("n",strtotime(now()));
	else
		$mon=$_SESSION["mon"];

if ($_REQUEST["yr"]) 
	$yr=$_REQUEST["yr"];
else
	if (!@$_SESSION["yr"]) 
		$yr=date("Y",strtotime(Now()));
	else
		$yr=$_SESSION["yr"];

if ($_REQUEST["days"]) 
	$days=$_REQUEST["days"]+0;
else
	if (!$_SESSION["days"]) 
		$days=date("j",strtotime(Now()));
	else
		$days=$_SESSION["days"];

$_SESSION["days"]=$days;
$_SESSION["mon"]=$mon;
$_SESSION["yr"]=$yr;



} // function BeforeProcessList























































// List page: Before SQL query
function BeforeQueryList(&$strSQL,&$strWhereClause,&$strOrderBy)
{
if ($strWhereClause=="")
$strWhereClause="1=1";

$DateField21 = "(`calendar`.`DateField` + interval `numbers`.`n` day)";
$DateField22 = "(`calendar`.`DateField` + interval (`numbers`.`n`*7) day)";
$DateField23 = "(`calendar`.`DateField` + interval `numbers`.`n` month)";
$DateField24 = "(`calendar`.`DateField` + interval `numbers`.`n` year)";
$DateField25 = "(`calendar`.`DateField` + interval `numbers`.`n` day)";

$strWhereClause1 = str_replace("`DateField`",$DateField21,$strWhereClause);
$strWhereClause2 = str_replace("`DateField`",$DateField22,$strWhereClause);
$strWhereClause3 = str_replace("`DateField`",$DateField23,$strWhereClause);
$strWhereClause4 = str_replace("`DateField`",$DateField24,$strWhereClause);
$strWhereClause5 = str_replace("`DateField`",$DateField25,$strWhereClause);

if ($strWhereClause1=="")
$strWhereClause1="1=1";

if ($strWhereClause2=="")
$strWhereClause2="1=1";

if ($strWhereClause3=="")
$strWhereClause3="1=1";

if ($strWhereClause4=="")
$strWhereClause4="1=1";

if ($strWhereClause5=="")
$strWhereClause5="1=1";

///////////////////
//calculate count
global $conn;
$sqlcount = "select
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` day)) -
to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'd') and
(".$strWhereClause1.") and (".$strWhereClause.")
union all
select
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval (`numbers`.`n`*7) day)) -
to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'ww') and
(".$strWhereClause2.") and (".$strWhereClause.")
union all
select
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` month)) -
to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'm') and
(".$strWhereClause3.") and (".$strWhereClause.")
union all
select
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` year)) -
to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'yyyy') and
(".$strWhereClause4.") and (".$strWhereClause.")
union all
select
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days(`calendar`.`DateField` + interval `numbers`.`n` day) -
to_days(`calendar`.`DateField`)) = 0)
where ((`calendar`.`Recurrence` is null) or (`calendar`.`Recurrence` <> 1))
and (".$strWhereClause5.") and (".$strWhereClause.")";
$rs = db_query($sqlcount,$conn);
$count = 0;
while ($data = db_fetch_numarray($rs))
$count+= $data[0];
$_SESSION["customcount"] = $count;
///////////////////

global $gsqlHead,$gsqlFrom,$gsqlWhere;
$gsqlHead = "select
`calendar`.`id` AS `id`,
`calendar`.`idusercal` AS `idusercal`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` day) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
`category`.`Color` AS `Color`
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` day)) -
to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'd') and
(".$strWhereClause1.") and (".$strWhereClause.")
union all
select
`calendar`.`id` AS `id`,
`calendar`.`idusercal` AS `idusercal`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval (`numbers`.`n`*7) day) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
`category`.`Color` AS `Color`
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval (`numbers`.`n`*7) day)) -
to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'ww') and
(".$strWhereClause2.") and (".$strWhereClause.")
union all
select
`calendar`.`id` AS `id`,
`calendar`.`idusercal` AS `idusercal`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` month) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
`category`.`Color` AS `Color`
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` month)) -
to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'm') and
(".$strWhereClause3.") and (".$strWhereClause.")
union all
select
`calendar`.`id` AS `id`,
`calendar`.`idusercal` AS `idusercal`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` year) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
`category`.`Color` AS `Color`
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` year)) -
to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'yyyy') and
(".$strWhereClause4.") and (".$strWhereClause.")
union all
select `calendar`.`id` AS `id`,
`calendar`.`idusercal` AS `idusercal`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` year) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
`category`.`Color` AS `Color`
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days(`calendar`.`DateField` + interval `numbers`.`n` day) -
to_days(`calendar`.`DateField`)) = 0)
where ((`calendar`.`Recurrence` is null) or (`calendar`.`Recurrence` <> 1))
and (".$strWhereClause5.") and (".$strWhereClause.")
union select 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0";
$gsqlFrom = " from numbers ";
$gsqlWhere = "";
$strWhereClause = "n<".$count;
$strOrderBy = " and 1=0 ".$strOrderBy;


} // function BeforeQueryList

























// Print page: Before process
function BeforeProcessPrint(&$conn)
{
if ($_REQUEST["mon"]) 
	$mon=$_REQUEST["mon"];
else
	if (!@$_SESSION["mon"]) 
		$mon=date("n",strtotime(now()));
	else
		$mon=$_SESSION["mon"];

if ($_REQUEST["yr"]) 
	$yr=$_REQUEST["yr"];
else
	if (!@$_SESSION["yr"]) 
		$yr=date("Y",strtotime(Now()));
	else
		$yr=$_SESSION["yr"];

if ($_REQUEST["days"]) 
	$days=$_REQUEST["days"]+0;
else
	if (!$_SESSION["days"]) 
		$days=date("j",strtotime(Now()));
	else
		$days=$_SESSION["days"];

$_SESSION["days"]=$days;
$_SESSION["mon"]=$mon;
$_SESSION["yr"]=$yr;



} // function BeforeProcessPrint




















































// Print page: Before SQL query
function BeforeQueryPrint(&$strSQL,&$strWhereClause,&$strOrderBy)
{
$days=$_SESSION["days"];
$mon=$_SESSION["mon"];
$yr=$_SESSION["yr"];

$datefield21 = "`calendar`.`DateField` + interval `numbers`.`n` day";
$strWhereClause1="DAYOFMONTH($datefield21)=".$days." and Month($datefield21)=".$mon." and Year($datefield21)=".$yr;

$datefield22 = "`calendar`.`DateField` + interval (`numbers`.`n`*7) day";
$strWhereClause2="DAYOFMONTH($datefield22)=".$days." and Month($datefield22)=".$mon." and Year($datefield22)=".$yr;

$datefield23 = "`calendar`.`DateField` + interval `numbers`.`n` month";
$strWhereClause3="DAYOFMONTH($datefield23)=".$days." and Month($datefield23)=".$mon." and Year($datefield23)=".$yr;

$datefield24 = "`calendar`.`DateField` + interval `numbers`.`n` year";
$strWhereClause4="DAYOFMONTH($datefield24)=".$days." and Month($datefield24)=".$mon." and Year($datefield24)=".$yr;

$datefield25 = "`calendar`.`DateField` + interval `numbers`.`n` day";
$strWhereClause5="DAYOFMONTH($datefield25)=".$days." and Month($datefield25)=".$mon." and Year($datefield25)=".$yr;

//////////////////////added event//////////////////////////////////////////////////

if ($strWhereClause=="")
$strWhereClause="1=1";

///////////////////
//calculate count
global $conn;
$sqlcount = "select 
count(*)
from `calendar` left join `category` 
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` day)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'd') and (".$strWhereClause1.")
union all 
select 
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval (`numbers`.`n`*7) day)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'ww') and (".$strWhereClause2.")
union all 
select 
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` month)) - to_days(`calendar`.`EndDate`)) <= 0) 
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'm') and (".$strWhereClause3.")
union all 
select 
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` year)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'yyyy') and (".$strWhereClause4.")
union all 
select 
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days(`calendar`.`DateField` + interval `numbers`.`n` day) - to_days(`calendar`.`DateField`)) = 0)
where ((`calendar`.`Recurrence` is null) or (`calendar`.`Recurrence` <> 1)) and (".$strWhereClause5.")";
$rs = db_query($sqlcount,$conn);
$count = 0;
while ($data = db_fetch_numarray($rs))
	$count+= $data[0];
$_SESSION["customcount"] = $count;
///////////////////

global $gsqlHead,$gsqlFrom,$gsqlWhere;
$gsqlHead = "select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` day) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category` 
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` day)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'd') and (".$strWhereClause1.")
union all 
select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval (`numbers`.`n`*7) day) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval (`numbers`.`n`*7) day)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'ww') and (".$strWhereClause2.")
union all 
select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` month) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` month)) - to_days(`calendar`.`EndDate`)) <= 0) 
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'm') and (".$strWhereClause3.")
union all 
select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` year) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` year)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'yyyy') and (".$strWhereClause4.")
union all 
select `calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` year) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days(`calendar`.`DateField` + interval `numbers`.`n` day) - to_days(`calendar`.`DateField`)) = 0)
where ((`calendar`.`Recurrence` is null) or (`calendar`.`Recurrence` <> 1)) and (".$strWhereClause5.")
union select 0,0,0,0,0,0,0,0,0,0,0,0,0";
$gsqlFrom = " from numbers ";
$gsqlWhere = "";
$strWhereClause = "n<".$count;
$strOrderBy = " and 1=0 ".$strOrderBy;
} // function BeforeQueryPrint





















// View page: Before process
function BeforeProcessView(&$conn)
{
if ($_REQUEST["wind"])
	$_SESSION["row00window_value"]=$_REQUEST["wind"];


if ($_REQUEST["mon"]) 
	$mon=$_REQUEST["mon"];
else
	if (!@$_SESSION["mon"]) 
		$mon=date("n",strtotime(now()));
	else
		$mon=$_SESSION["mon"];

if ($_REQUEST["yr"]) 
	$yr=$_REQUEST["yr"];
else
	if (!@$_SESSION["yr"]) 
		$yr=date("Y",strtotime(Now()));
	else
		$yr=$_SESSION["yr"];

if ($_REQUEST["days"]) 
	$days=$_REQUEST["days"]+0;
else
	if (!$_SESSION["days"]) 
		$days=date("j",strtotime(Now()));
	else
		$days=$_SESSION["days"];

$_SESSION["days"]=$days;
$_SESSION["mon"]=$mon;
$_SESSION["yr"]=$yr;


} // function BeforeProcessView






























































// View page: Before display
function BeforeShowView(&$smarty,&$templatefile, $values)
{
if($_SESSION["row00window_value"]!="daily" && $_SESSION["row00window_value"]!="calendar")
	$smarty->assign("row00header_value","style=\"cursor:move;\" onmousedown=\"omd(event);\" onmouseup=\"mclick=0;\"");

$smarty->assign("row00window_value",$_SESSION["row00window_value"]);
if ($values["Recurrence"]==1)
$smarty->assign("show_Hiden","style=\"display:'';\"");
else
$smarty->assign("show_Hiden","style=\"display:none;\"");

} // function BeforeShowView



































// View page: Before SQL query
function BeforeQueryView(&$strSQL,&$strWhereClause)
{
$days=$_SESSION["days"];
$mon=$_SESSION["mon"];
$yr=$_SESSION["yr"];

//$strWhereClause="calendar.id=".$_REQUEST["editid1"]." and DAYOFMONTH(DateField2)=".$days." and Month(DateField2)=".$mon." and Year(DateField2)=".$yr;

$datefield21 = "`calendar`.`DateField` + interval `numbers`.`n` day";
$strWhereClause1="calendar.id=".$_REQUEST["editid1"]." and DAYOFMONTH($datefield21)=".$days." and Month($datefield21)=".$mon." and Year($datefield21)=".$yr;

$datefield22 = "`calendar`.`DateField` + interval `numbers`.`n` week";
$strWhereClause2="calendar.id=".$_REQUEST["editid1"]." and DAYOFMONTH($datefield22)=".$days." and Month($datefield22)=".$mon." and Year($datefield22)=".$yr;

$datefield23 = "`calendar`.`DateField` + interval `numbers`.`n` month";
$strWhereClause3="calendar.id=".$_REQUEST["editid1"]." and DAYOFMONTH($datefield23)=".$days." and Month($datefield23)=".$mon." and Year($datefield23)=".$yr;

$datefield24 = "`calendar`.`DateField` + interval `numbers`.`n` year";
$strWhereClause4="calendar.id=".$_REQUEST["editid1"]." and DAYOFMONTH($datefield24)=".$days." and Month($datefield24)=".$mon." and Year($datefield24)=".$yr;

$datefield25 = "`calendar`.`DateField` + interval `numbers`.`n` day";
$strWhereClause5="calendar.id=".$_REQUEST["editid1"]." and DAYOFMONTH($datefield25)=".$days." and Month($datefield25)=".$mon." and Year($datefield25)=".$yr;

//$strWhereClause="";

global $gsqlHead,$gsqlFrom,$gsqlWhere;
$strSQL = "select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` day) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category` 
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` day)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'd') and (".$strWhereClause1.")
union all 
select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` week) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` week)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'ww') and (".$strWhereClause2.")
union all 
select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` month) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` month)) - to_days(`calendar`.`EndDate`)) <= 0) 
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'm') and (".$strWhereClause3.")
union all 
select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` year) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` year)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'yyyy') and (".$strWhereClause4.")
union all 
select `calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` year) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days(`calendar`.`DateField` + interval `numbers`.`n` day) - to_days(`calendar`.`DateField`)) = 0)
where ((`calendar`.`Recurrence` is null) or (`calendar`.`Recurrence` <> 1)) and (".$strWhereClause5.")";

} // function BeforeQueryView






















// Export page: Before process
function BeforeProcessExport(&$conn)
{
if ($_REQUEST["mon"]) 
	$mon=$_REQUEST["mon"];
else
	if (!@$_SESSION["mon"]) 
		$mon=date("n",strtotime(now()));
	else
		$mon=$_SESSION["mon"];

if ($_REQUEST["yr"]) 
	$yr=$_REQUEST["yr"];
else
	if (!@$_SESSION["yr"]) 
		$yr=date("Y",strtotime(Now()));
	else
		$yr=$_SESSION["yr"];

if ($_REQUEST["days"]) 
	$days=$_REQUEST["days"]+0;
else
	if (!$_SESSION["days"]) 
		$days=date("j",strtotime(Now()));
	else
		$days=$_SESSION["days"];

$_SESSION["days"]=$days;
$_SESSION["mon"]=$mon;
$_SESSION["yr"]=$yr;


} // function BeforeProcessExport



















































// Export page: Before SQL query
function BeforeQueryExport(&$strSQL,&$strWhereClause,&$strOrderBy)
{
$days=$_SESSION["days"];
$mon=$_SESSION["mon"];
$yr=$_SESSION["yr"];

$datefield21 = "`calendar`.`DateField` + interval `numbers`.`n` day";
$strWhereClause1="DAYOFMONTH($datefield21)=".$days." and Month($datefield21)=".$mon." and Year($datefield21)=".$yr;

$datefield22 = "`calendar`.`DateField` + interval (`numbers`.`n`*7) day";
$strWhereClause2="DAYOFMONTH($datefield22)=".$days." and Month($datefield22)=".$mon." and Year($datefield22)=".$yr;

$datefield23 = "`calendar`.`DateField` + interval `numbers`.`n` month";
$strWhereClause3="DAYOFMONTH($datefield23)=".$days." and Month($datefield23)=".$mon." and Year($datefield23)=".$yr;

$datefield24 = "`calendar`.`DateField` + interval `numbers`.`n` year";
$strWhereClause4="DAYOFMONTH($datefield24)=".$days." and Month($datefield24)=".$mon." and Year($datefield24)=".$yr;

$datefield25 = "`calendar`.`DateField` + interval `numbers`.`n` day";
$strWhereClause5="DAYOFMONTH($datefield25)=".$days." and Month($datefield25)=".$mon." and Year($datefield25)=".$yr;

//////////////////////added event//////////////////////////////////////////////////

if ($strWhereClause=="")
$strWhereClause="1=1";

///////////////////
//calculate count
global $conn;
$sqlcount = "select 
count(*)
from `calendar` left join `category` 
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` day)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'd') and (".$strWhereClause1.")
union all 
select 
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval (`numbers`.`n`*7) day)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'ww') and (".$strWhereClause2.")
union all 
select 
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` month)) - to_days(`calendar`.`EndDate`)) <= 0) 
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'm') and (".$strWhereClause3.")
union all 
select 
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` year)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'yyyy') and (".$strWhereClause4.")
union all 
select 
count(*)
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days(`calendar`.`DateField` + interval `numbers`.`n` day) - to_days(`calendar`.`DateField`)) = 0)
where ((`calendar`.`Recurrence` is null) or (`calendar`.`Recurrence` <> 1)) and (".$strWhereClause5.")";
$rs = db_query($sqlcount,$conn);
$count = 0;
while ($data = db_fetch_numarray($rs))
	$count+= $data[0];
$_SESSION["customcount"] = $count;
///////////////////

global $gsqlHead,$gsqlFrom,$gsqlWhere;
$gsqlHead = "select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` day) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category` 
on (`calendar`.`Category`=`category`.`id`)
left join `numbers`
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` day)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'd') and (".$strWhereClause1.")
union all 
select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval (`numbers`.`n`*7) day) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval (`numbers`.`n`*7) day)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'ww') and (".$strWhereClause2.")
union all 
select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` month) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` month)) - to_days(`calendar`.`EndDate`)) <= 0) 
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'm') and (".$strWhereClause3.")
union all 
select 
`calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` year) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days((`calendar`.`DateField` + interval `numbers`.`n` year)) - to_days(`calendar`.`EndDate`)) <= 0)
where (`calendar`.`Recurrence` = 1) and (`calendar`.`Period` = 'yyyy') and (".$strWhereClause4.")
union all 
select `calendar`.`id` AS `id`,
`calendar`.`DateField` AS `DateField`,
(`calendar`.`DateField` + interval `numbers`.`n` year) AS `DateField2`,
`calendar`.`TimeField` AS `TimeField`,
`calendar`.`Theme` AS `Theme`,
`calendar`.`Description` AS `Description`,
`calendar`.`Category` AS `Category`,
`calendar`.`EndTime` AS `EndTime`,
`calendar`.`DayEvent` AS `DayEvent`,
`calendar`.`EndDate` AS `EndDate`,
`calendar`.`Period` AS `Period`,
`calendar`.`Recurrence` AS `Recurrence`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days(`calendar`.`DateField` + interval `numbers`.`n` day) - to_days(`calendar`.`DateField`)) = 0)
where ((`calendar`.`Recurrence` is null) or (`calendar`.`Recurrence` <> 1)) and (".$strWhereClause5.")
union select 0,0,0,0,0,0,0,0,0,0,0,0,0";
$gsqlFrom = " from numbers ";
$gsqlWhere = "";
$strWhereClause = "n<".$count;
$strOrderBy = " and 1=0 ".$strOrderBy;
} // function BeforeQueryExport























































function Daily_JavaScript(&$params)
{
global $conn;
$tmpSQL="select max(id) as maxid from category";
$rstmp = db_query($tmpSQL,$conn);
$datatmp = db_fetch_array($rstmp);
$kol=$datatmp["maxid"];

$tmpSQL2="select id,Color,Category from category order by id";
$rstmp2 = db_query($tmpSQL2,$conn);

?>
<script>
var a = new Array(<?php echo $kol ?>);
<?php
while ($datatmp2 = db_fetch_array($rstmp2))
    echo "a[".$datatmp2["id"]."]='".$datatmp2["Color"]."';";
?>
</script>
<?php

}






































function Daily_Javascipt(&$params)
{
global $conn;
$tmpSQL="select max(id) as maxid from category";
$rstmp = db_query($tmpSQL,$conn);
$datatmp = db_fetch_array($rstmp);
$kol=$datatmp["maxid"];

$tmpSQL2="select id,Color,Category from category order by id";
$rstmp2 = db_query($tmpSQL2,$conn);

?>
<script>
var a = new Array(<?php echo $kol ?>);
<?php
while ($datatmp2 = db_fetch_array($rstmp2))
    echo "a[".$datatmp2["id"]."]='".$datatmp2["Color"]."';";
?>
</script>
<?php
}






































function Daily_Status1(&$params)
{
global $conn;

if($_REQUEST["editid1"]!="")
{
    $tmpSQL="select category.Color from calendar left join category on category.id=calendar.Category where calendar.id=".$_REQUEST["editid1"];
    $rstmp = db_query($tmpSQL,$conn);
    if ($data = db_fetch_array($rstmp))
		echo "style=\"background-color:".$data["Color"]."\"";
}
}






































function Daily_Status(&$params)
{
global $conn;

if($_REQUEST["editid1"]!="")
{
    $tmpSQL="select category.Color from calendar left join category on category.id=calendar.category where calendar.id=".$_REQUEST["editid1"];
    $rstmp = db_query($tmpSQL,$conn);
    if ($data = db_fetch_array($rstmp))
		echo "style=\"background-color:".$data["Color"]."\"";
}

}






































function Daily_Next_Prev(&$params)
{
$mont = Array();
global $locale_info;
$mont[1]=$locale_info["LOCALE_SMONTHNAME1"];
$mont[2]=$locale_info["LOCALE_SMONTHNAME2"];
$mont[3]=$locale_info["LOCALE_SMONTHNAME3"];
$mont[4]=$locale_info["LOCALE_SMONTHNAME4"];
$mont[5]=$locale_info["LOCALE_SMONTHNAME5"];
$mont[6]=$locale_info["LOCALE_SMONTHNAME6"];
$mont[7]=$locale_info["LOCALE_SMONTHNAME7"];
$mont[8]=$locale_info["LOCALE_SMONTHNAME8"];
$mont[9]=$locale_info["LOCALE_SMONTHNAME9"];
$mont[10]=$locale_info["LOCALE_SMONTHNAME10"];
$mont[11]=$locale_info["LOCALE_SMONTHNAME11"];
$mont[12]=$locale_info["LOCALE_SMONTHNAME12"];

$mdays = Array();
$mdays[1]=31;
$mdays[2]=28;
$mdays[3]=31;
$mdays[4]=30;
$mdays[5]=31;
$mdays[6]=30;
$mdays[7]=31;
$mdays[8]=31;
$mdays[9]=30;
$mdays[10]=31;
$mdays[11]=30;
$mdays[12]=31;

$days=$_SESSION["days"];
$mon=$_SESSION["mon"];
$yr=$_SESSION["yr"];
if($days>$mdays[$mon])
	$days=$mdays[$mon];
if(round($yr/4,0)==$yr/4)
	$mdays[2]=29;
$days1=$days-1;
$days2=$days+1;
$mon1=$mon;
$mon2=$mon;
$yr1=$yr;
$yr2=$yr;

if ($days2>$mdays[$mon2])
{
	$days2=1;
	$mon2++;
	if ($mon2>12) 
	{
		$yr2++;
		$mon2=1;
	}
}
if ($days1<1)
{
	$mon1--;
	if ($mon1<1)
	{
		$yr1--;
		$mon1=12;
	}
	$days1=$mdays[$mon1];
}	

echo "<div align=center>";
echo "<a href=\"daily_list.php?mon=".$mon1."&yr=".$yr1."&days=".$days1."\">prev</a>&nbsp;&nbsp;&nbsp;&nbsp;";

echo "<select id=dselect onchange=\"window.location.href='daily_list.php?days='+document.getElementById('dselect').value+'&mon='+document.getElementById('mselect').value+'&yr='+document.getElementById('yselect').value\">";
for($i=1;$i<=$mdays[$mon];$i++)
{
	$s="";
	if($i==$days) 	
		$s=" selected";
	echo "<option value=" . $i . " " . $s . ">" . $i . "</option>";
}
echo "</select>&nbsp;&nbsp;";

echo "<select id=mselect onchange=\"window.location.href='daily_list.php?days='+document.getElementById('dselect').value+'&mon='+document.getElementById('mselect').value+'&yr='+document.getElementById('yselect').value\">";
for($i=1;$i<=12;$i++)
{
	$s="";
	if($i==$mon)
		$s=" selected";
	echo "<option value=" . $i . " " . $s . ">" . $mont[$i] . "</option>";
}
echo "</select>&nbsp;&nbsp;";

echo "<select id=yselect onchange=\"window.location.href='daily_list.php?days='+document.getElementById('dselect').value+'&mon='+document.getElementById('mselect').value+'&yr='+document.getElementById('yselect').value\">";
for($i=$yr-10;$i<=$yr+10;$i++)
{
	$s="";
	if($i==$yr)
		$s=" selected";
	echo "<option value=" . $i . " " . $s . ">" . $i . "</option>";
}
echo "</select>";

echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"daily_list.php?mon=".$mon2."&yr=".$yr2."&days=".$days2."\">next</a>";
echo "</div>";
}











// Before record added
function BeforeAdd(&$values,&$message,$inline)
{
global $conn;
if ((strtotime(now())-strtotime($values["DateField"]." ".$values["TimeField"]))>=0 && $values["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($values["DateField"]))/60*60*24>0 && $values["DayEvent"]==1 && $values["Recurrence"]!=1)
{
    if ($_SESSION["row00window_value"]=="daily" || $_SESSION["row00window_value"]=="calendar")
        $message="<span class=message>Error Date or Time</span>";
    else
        {
                ?><script>
                alert("Error Date or Time");
                </script><?php
        }       
    return false;
}
else if ($values["Recurrence"]==1 && (strtotime($values["EndDate"])-strtotime($values["DateField"]))<0)
{
    if ($_SESSION["row00window_value"]=="daily" || $_SESSION["row00window_value"]=="calendar")
        $message="<span class=message>Error End Date or Time</span>";
    else
        {
                ?><script>
                alert("Error End Date or Time");
                </script><?php
    }
    return false;
}
else if ((strtotime($values["DateField"]." ".$values["TimeField"])-strtotime($values["DateField"]." ".$values["EndTime"]))>=0 && $values["DayEvent"]!=1 && $values["Recurrence"]!=1 && $values["EndTime"])
{
        if ($_SESSION["row00window_value"]=="daily" || $_SESSION["row00window_value"]=="calendar")
        $message="<span class=message>Error Time</span>";
    else
        {
            ?><script>
                alert("Error Time");
                </script><?php
    }
    return false;
}
else 
        return true;

return true;
} // function BeforeAdd





















































// List page: Before display
function BeforeShowList(&$smarty,&$templatefile)
{
//*********************************************
$maxn = 0;
global $conn;
$strN = "select max(n) from numbers";
$rsN = db_query($strN,$conn);
if ($dataN = db_fetch_numarray($rsN))
	$maxn = $dataN[0]+1;
if ($maxn<5000)
{
	?>
	<script type="text/javascript" src="include/jquery.js"></script>
	<script>
	$.get("monthly_list.php",
	{
		rndval: Math.random(),
		ajaxcount: 'yes',
		maxn: <?php echo $maxn?>
	},
	function(xml){}
	);
	</script><?php 
}
//**********************************************

} // function BeforeShowList


















































function Build_TimeField(&$params)
{
$t=date("G",strtotime(now()));
$m=date("i",strtotime(now()));

global $conn;
$tperiod = 60;
$str = "select * from globals";
$rs = db_query($str,$conn);
if ($data = db_fetch_array($rs))
        $tperiod = $data["TimePeriod"];
if($tperiod=="")
	$tperiod=60;
        
for ($i=1;$i<=60/$tperiod;$i++)
{
  if ($m<=$tperiod*$i)
  {
	 $m=$tperiod*$i;
	 $i=100;
  }
}       

if ($m>=60)
{
   $t++;
   $m=0;
}

if ($t>23) 
	  $t=0;

$tm = array(0,0,0,$t,$m,0);
$t=format_time($tm);

$str = "";
$str.= "<select name=\"value_TimeField\" onchange=\"if(document.editform.value_EndTime.value==''){t=document.editform.value_TimeField.selectedIndex+2;if(t>document.editform.value_TimeField.length-1)t-=2;document.editform.value_EndTime.options[t].selected=true;return false;}\">";
$str.= "<option value=\"\">Please select</option>";
for ($i=0; $i<24;$i++)
{
        for ($j=1;$j<=60/$tperiod;$j++)
        {
            $s1="";
            $p=($j-1)*$tperiod;
            $tm1 = array(0,0,0,$i,$p,0);
            if ($t==format_time($tm1))
					$s1=" selected";
				$time1 = sprintf("%02d:%02d:%02d",$i,$p,0);
            $str.= "<option value=\"".$time1."\"".$s1.">".format_time($tm1)."</option>";
        }
}
$str.= "</select>";
echo $str;
}
















// Before record updated
function BeforeEdit(&$values, $where, &$oldvalues, &$keys,&$message,$inline)
{
global $conn;
if ((strtotime(now())-strtotime($values["DateField"]." ".$values["TimeField"]))>=0 && $values["DayEvent"]!=1 && $values["Recurrence"]!=1 || (strtotime(date("Y-m-d"))-strtotime($values["DateField"]))/60*60*24>0 && $values["DayEvent"]==1 && $values["Recurrence"]!=1)
{
    if ($_SESSION["row00window_value"]=="daily" || $_SESSION["row00window_value"]=="calendar")
        $message="<span class=message>Error Date or Time</span>";
    else
        {
                ?><script>
                alert("Error Date or Time");
                </script><?php
        }       
    return false;
}
else if ($values["Recurrence"]==1 && (strtotime($values["EndDate"])-strtotime($values["DateField"]))<0)
{
    if ($_SESSION["row00window_value"]=="daily" || $_SESSION["row00window_value"]=="calendar")
        $message="<span class=message>Error End Date or Time</span>";
    else
        {
                ?><script>
                alert("Error End Date or Time");
                </script><?php
    }
    return false;
}
else if ((strtotime($values["DateField"]." ".$values["TimeField"])-strtotime($values["DateField"]." ".$values["EndTime"]))>=0 && $values["DayEvent"]!=1 && $values["Recurrence"]!=1 && $values["EndTime"])
{
        if ($_SESSION["row00window_value"]=="daily" || $_SESSION["row00window_value"]=="calendar")
        $message="<span class=message>Error Time</span>";
    else
        {
            ?><script>
                alert("Error Time");
                </script><?php
    }
    return false;
}
else 
        return true;

return true;
} // function BeforeEdit

























































// List page: After record processed
function BeforeMoveNextList(&$data,&$row,$col)
{
if(!$data["Theme"])
{
	$row["1Theme_value"]="&lt;Empty&gt;";
	if ($data["DateField2"])
		if ((strtotime(now())-strtotime($data["DateField2"]." ".$data["TimeField"]))>=0 && $data["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($data["DateField2"]))>0 && $data["DayEvent"]==1)	
			$row["1Theme_value"]="<font color=red>&lt;Empty&gt;</font>";
}	
if ((strtotime(now())-strtotime($data["DateField2"]." ".$data["TimeField"]))>=0 && $data["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($data["DateField2"]))>0 && $data["DayEvent"]==1)
	$row["HideMess"]=false;
else
	$row["HideMess"]=true;
} // function BeforeMoveNextList









































function Build_EndTime(&$params)
{
global $conn;
$tperiod = 60;
$str = "select * from globals";
$rs = db_query($str,$conn);
if ($data = db_fetch_array($rs))
        $tperiod = $data["TimePeriod"];
if($tperiod=="")
	$tperiod=60;
$str = "";
$str.= "<select name=\"value_EndTime\";>";
$str.= "<option value=\"\">Please select</option>";
for ($i=0; $i<24;$i++)
{
        for ($j=1;$j<=60/$tperiod;$j++)
        {
            $s1="";
            $p=($j-1)*$tperiod;
            $tm1 = array(0,0,0,$i,$p,0);
				$time1 = sprintf("%02d:%02d:%02d",$i,$p,0);
            $str.= "<option value=\"".$time1."\">".format_time($tm1)."</option>";
        }
}
$str.= "</select>";
echo $str;


//**********  Custom code  ************
// put your custom code here


}






































function Build_TimeField1(&$params)
{
global $conn,$smarty;
$tperiod = 60;
$str = "select * from globals";
$rs = db_query($str,$conn);
if ($data = db_fetch_array($rs))
        $tperiod = $data["TimePeriod"];
if($tperiod=="")
	$tperiod=60;
$t="";
$t = dbvalue2time($smarty->get_template_vars("value_TimeField"));
$str = "";
$str.= "<select name=\"value_TimeField\" onchange=\"if(document.editform.value_EndTime.value==''){t=document.editform.value_TimeField.selectedIndex+2;if(t>document.editform.value_TimeField.length-1)t-=2;document.editform.value_EndTime.options[t].selected=true;return false;}\">";
$str.= "<option value=\"\">Please select</option>";
for ($i=0; $i<24;$i++)
{
        for ($j=1;$j<=60/$tperiod;$j++)
        {
            $s1="";
            $p=($j-1)*$tperiod;
            $tm1 = array(0,0,0,$i,$p,0);
				if($t!="")
					if ($t==format_time($tm1))
						$s1=" selected";
				$time1 = sprintf("%02d:%02d:%02d",$i,$p,0);
            $str.= "<option value=\"".$time1."\"".$s1.">".format_time($tm1)."</option>";
        }
}
$str.= "</select>";
echo $str;
}






































function Build_EndTime1(&$params)
{
global $conn,$smarty;
$tperiod = 60;
$str = "select * from globals";
$rs = db_query($str,$conn);
if ($data = db_fetch_array($rs))
        $tperiod = $data["TimePeriod"];
if($tperiod=="")
	$tperiod=60;
$t="";
$t = dbvalue2time($smarty->get_template_vars("value_EndTime"));
$str = "";
$str.= "<select name=\"value_EndTime\";>";
$str.= "<option value=\"\">Please select</option>";
for ($i=0; $i<24;$i++)
{
        for ($j=1;$j<=60/$tperiod;$j++)
        {
            $s1="";
            $p=($j-1)*$tperiod;
            $tm1 = array(0,0,0,$i,$p,0);
				if($t!="")
					if ($t==format_time($tm1))
						$s1=" selected";
				$time1 = sprintf("%02d:%02d:%02d",$i,$p,0);
            $str.= "<option value=\"".$time1."\"".$s1.">".format_time($tm1)."</option>";
        }
}
$str.= "</select>";
echo $str;
}

?>