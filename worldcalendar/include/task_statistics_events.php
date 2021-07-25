<?php


































// List page: After record processed
function BeforeMoveNextList(&$data,&$row,$col)
{
$strtmp="days=".date("j",strtotime($data["DateField2"]))."&mon=".date("n",strtotime($data["DateField2"]));
$strtmp.="&yr=".date("Y",strtotime($data["DateField2"]));
$row["DateValue"] = $strtmp;

if(!$data["Theme"])
{
	$row["1Theme_value"]="&lt;Empty&gt;";
	if ($data["DateField2"])
		if (strtotime(now())-strtotime(date("Y-m-d",strtotime($data["DateField2"]))." ".$data["TimeField"])>0)	
			$row["1Theme_value"]="<font color=red>&lt;Empty&gt;</font>";
}
				
if ((strtotime(now())-strtotime($data["DateField2"]." ".$data["TimeField"]))>=0 && $data["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($data["DateField2"]))/60*60*24>0 && $data["DayEvent"]==1)
	$row["HideMess"]=false;
else
	$row["HideMess"]=true;

} // function BeforeMoveNextList






















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







































// Print page: Before SQL query
function BeforeQueryPrint(&$strSQL,&$strWhereClause,&$strOrderBy)
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
$strWhereClause="1=1";

if ($strWhereClause2=="")
$strWhereClause="1=1";

if ($strWhereClause3=="")
$strWhereClause="1=1";

if ($strWhereClause4=="")
$strWhereClause="1=1";

if ($strWhereClause5=="")
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
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
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
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
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
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
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
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
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
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days(`calendar`.`DateField` + interval `numbers`.`n` day) - to_days(`calendar`.`DateField`)) = 0)
where ((`calendar`.`Recurrence` is null) or (`calendar`.`Recurrence` <> 1)) and (".$strWhereClause5.")
union select 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0";
$gsqlFrom = " from numbers ";
$gsqlWhere = "";
$strWhereClause = "n<".$count;
$strOrderBy = " and 1=0 ".$strOrderBy;
} // function BeforeQueryPrint







































// Export page: Before SQL query
function BeforeQueryExport(&$strSQL,&$strWhereClause,&$strOrderBy)
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
$strWhereClause="1=1";

if ($strWhereClause2=="")
$strWhereClause="1=1";

if ($strWhereClause3=="")
$strWhereClause="1=1";

if ($strWhereClause4=="")
$strWhereClause="1=1";

if ($strWhereClause5=="")
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
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
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
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
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
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
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
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
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
`calendar`.`income` AS `income`,
`calendar`.`outcome` AS `outcome`,
`category`.`Color` AS `Color` 
from `calendar` left join `category`
on (`calendar`.`Category`=`category`.`id`)
left join `numbers` 
on ((to_days(`calendar`.`DateField` + interval `numbers`.`n` day) - to_days(`calendar`.`DateField`)) = 0)
where ((`calendar`.`Recurrence` is null) or (`calendar`.`Recurrence` <> 1)) and (".$strWhereClause5.")
union select 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0";
$gsqlFrom = " from numbers ";
$gsqlWhere = "";
$strWhereClause = "n<".$count;
$strOrderBy = " and 1=0 ".$strOrderBy;
} // function BeforeQueryExport











































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














// List page: Before process
function BeforeProcessList(&$conn)
{
$_SESSION["days"]=date("j",strtotime(Now()));
$_SESSION["mon"]=date("n",strtotime(now()));
$_SESSION["yr"]=date("Y",strtotime(Now()));
} // function BeforeProcessList





































?>