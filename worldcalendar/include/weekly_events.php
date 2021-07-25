<?php

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






























































// List page: Before display
function BeforeShowList(&$smarty,&$templatefile)
{
global $conn;
global $strTableName;
//*********************************************
$maxn = 0;
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

$ndays = Array();
$ndays[1]=$locale_info["LOCALE_SDAYNAME1"];
$ndays[2]=$locale_info["LOCALE_SDAYNAME2"];
$ndays[3]=$locale_info["LOCALE_SDAYNAME3"];
$ndays[4]=$locale_info["LOCALE_SDAYNAME4"];
$ndays[5]=$locale_info["LOCALE_SDAYNAME5"];
$ndays[6]=$locale_info["LOCALE_SDAYNAME6"];
$ndays[7]=$locale_info["LOCALE_SDAYNAME7"];

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
if(round($yr/4,0)==$yr/4)
$mdays[2]=29;
$wd=date("w",mktime(0,0,0,$mon,$days,$yr));
if ($wd==0)
$wd=7;
$wd=$wd-1;
$nw = date("Y-m-d",strtotime($yr."-".$mon."-".$days)-60*60*24*$wd);
$nw1=$nw;

for ($i=1; $i<8; $i++)
{
$smarty->assign("row00AddHideW".($i)."_value","style=\"display:none;\"");
if(strtotime(date("Y-m-d"))-strtotime($nw)<=0)
$smarty->assign("row00AddHideW" . (int)($i) . "_value","");
$smarty->assign("row00".$i."Date_value",$ndays[$i].", ".$mont[date("n",strtotime($nw))]." ".date("j",strtotime($nw)).", ".date("Y",strtotime($nw)));
$smarty->assign("row00Dateadd".$i."_value","yr=".date("Y",strtotime($nw))."&mon=".date("n",strtotime($nw))."&days=".date("j",strtotime($nw)));
$smarty->assign("row00AddOnclick".$i."_value","onclick='add_div(".date("j",strtotime($nw)).",".date("n",strtotime($nw)).",".date("Y",strtotime($nw)).");return false;'");
if (date("Y",strtotime(now()))==date("Y",strtotime($nw)) && date("n",strtotime(now()))==date("n",strtotime($nw)) && date("j",strtotime(now()))==date("j",strtotime($nw)))
{
$smarty->assign("row00".$i."Now_value",True);
$smarty->assign("row00".$i."TableNow_value","style=\"border:2px solid #990000\"");
$smarty->assign("row00".$i."TableDate_value",$ndays[$i].", ".$mont[date("n",strtotime($nw))]." ".date("j",strtotime($nw)).", ".date("Y",strtotime($nw)));
}
else
{
$smarty->assign("row00".$i."Now_value",False);
$smarty->assign("row00".$i."TableNow_value","style=\"border:1px solid #CCCCCC\"");
}
$smarty->assign("row00".$i."sDay_value",date("j",strtotime($nw)));
$nw = date("Y-m-d",strtotime($nw)+60*60*24);
}


$strSQL="select DateField from calendar where DateField>='".date("Y-m-d",strtotime($nw1))."' and DateField<'".date("Y-m-d",strtotime($nw))."' and idusercal=".$_SESSION["_".$strTableName."_OwnerID"]." group by DateField order by DateField";

$rstmp = db_query($strSQL,$conn);
while ($datatmp = db_fetch_array($rstmp))
{
$strSQL2 = "select calendar.*, category.Color from calendar left join category 
on category.id=calendar.Category where 
DateField>='".date("Y-m-d",strtotime($nw1))."' 
and DateField<'".date("Y-m-d",strtotime($nw))."' 
and DAYOFMONTH(DateField)=".date("j",strtotime($datatmp["DateField"]))." and idusercal=".$_SESSION["_".$strTableName."_OwnerID"]; 


$rstmp2 = db_query($strSQL2,$conn);
$strRow="";
while ($datatmp2 = db_fetch_array($rstmp2))
{
$strColor="";
if ($datatmp2["Color"])
$strColor=" style=\"background-color:".$datatmp2["Color"]."\"";
$strRow.="<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td>";
$strRow.="<table width=100% cellpadding=1 cellspacing=0 style=\"border: 1px solid gray\" id=edit".$datatmp2["id"].">";
$strRow.="<tr><td width=7 ".$strColor."></td>";

$c1="";
$c2="";
if ($datatmp2["DayEvent"]) 
$c1=$strColor;
else
$c2=$strColor;
$strRow.="<td colspan=4 ".$c1." height=3></td></tr>";
$strRow.="<tr><td width=7 ".$c2." valign=top></td><td width=400 valign=top>";
$k=3;
if (!$datatmp2["DayEvent"]) 
{
$strRow.="<font face=Arial>Time:&nbsp;".dbvalue2time($datatmp2["TimeField"])."</font>&nbsp;";
$k=1;
}

if($datatmp2["DateField"])
if ($_SESSION["UserID"]=="Guest" || ((strtotime(now())-strtotime($datatmp2["DateField"]." ".$datatmp2["TimeField"]))>=0 && $datatmp2["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($datatmp2["DateField"]))>0 && $datatmp2["DayEvent"]==1)) 
$strRow.="<a href=\"\" onclick=\"view_div(".date("j",strtotime($datatmp2["DateField"])).",".date("n",strtotime($datatmp2["DateField"])).",".date("Y",strtotime($datatmp2["DateField"])).",".$datatmp2["id"].");return false;\"><b>"."\r\n"; 
else
$strRow.="<a href=\"\" onclick=\"edit_div(".date("j",strtotime($datatmp2["DateField"])).",".date("n",strtotime($datatmp2["DateField"])).",".date("Y",strtotime($datatmp2["DateField"])).",".$datatmp2["id"].");return false;\"><b>"."\r\n"; 
$strtmp=$datatmp2["Theme"];
if (strlen(trim($strtmp))==0 || !$strtmp)
$strtmp="&lt;Empty&gt;";
if (strlen($strtmp)>50)
$strtmp=substr($strtmp,0,50)."...";
if ((strtotime(now())-strtotime($datatmp2["DateField"]." ".$datatmp2["TimeField"]))>=0 && $datatmp2["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($datatmp2["DateField"]))>0 && $datatmp2["DayEvent"]==1)
$strRow.="<font color=red>";
$strRow.=$strtmp;
if ((strtotime(now())-strtotime($datatmp2["DateField"]." ".$datatmp2["TimeField"]))>=0 && $datatmp2["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($datatmp2["DateField"]))>0 && $datatmp2["DayEvent"]==1)
$strRow.="</font>";
$strRow.="</b></a></td>";
$strtmp=$datatmp2["Description"];
if (strlen($strtmp)>300)
$strtmp=sunstr($strtmp,0,300)."...";
$strRow.="<td width=800 valign=top>".$strtmp."</td></tr></table></td></tr>";
$strRow.="<tr><td colspan=4 height=1></td></tr>";
$strRow.="</table>";
}
$ww=date("w",strtotime($datatmp["DateField"]));
if ($ww==0)
$ww=7;
$smarty->assign("row00".$ww."Day_value",$strRow);
} 

$strSQL3 = "select calendar.*, category.Color from calendar left join category on category.id=calendar.Category where Recurrence=1  and idusercal=".$_SESSION["_".$strTableName."_OwnerID"];
$rstmp3 = db_query($strSQL3,$conn);
while ($datatmp3 = db_fetch_array($rstmp3))
{
//Period = 1 year
if ($datatmp3["Period"]=="yyyy")
$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp3["DateField"])), date("j",strtotime($datatmp3["DateField"])), date("Y",strtotime($datatmp3["DateField"]))+1);
//Period = 1 week
if ($datatmp3["Period"]=="ww")
$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp3["DateField"])), date("j",strtotime($datatmp3["DateField"]))+7, date("Y",strtotime($datatmp3["DateField"])));
//Period = 1 month
if ($datatmp3["Period"]=="d")
$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp3["DateField"])), date("j",strtotime($datatmp3["DateField"]))+1, date("Y",strtotime($datatmp3["DateField"])));
//Period = 1 day
if ($datatmp3["Period"]=="m")
$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp3["DateField"]))+1, date("j",strtotime($datatmp3["DateField"])), date("Y",strtotime($datatmp3["DateField"])));
$newdate = date("Y-m-d",$newdate);

$newweek=date("w",strtotime($newdate));
if ($newweek==0)
$newweek=7;
while (((strtotime($datatmp3["EndDate"])-strtotime($newdate))/60*60*24)>=0)
{
$strRow="";
if ((strtotime($nw1)-strtotime($newdate))<=0 && (strtotime($nw)-strtotime($newdate))>0)
{
$strColor="";
if ($datatmp3["Color"])
$strColor=" style=\"background-color:".$datatmp3["Color"]."\"";
$strRow.="<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td>";
$strRow.="<table cellpadding=1 cellspacing=0 width=100% border=0 style=\"border: 1px solid gray\">";
$strRow.="<tr><td width=7 ".$strColor."></td>";

$c1="";
$c2="";
if ($datatmp3["DayEvent"])
$c1=$strColor;
else
$c2=$strColor;
$strRow.="<td colspan=4 ".$c1." height=3></td></tr>";
$strRow.="<tr><td width=7 ".$c2." valign=top></td><td width=400>";
$k=3;
if (!$datatmp3["DayEvent"])
{
$strRow.="<font face=Arial>Time:&nbsp;".dbvalue2time($datatmp3["TimeField"])."</font>&nbsp;";
$k=1;
}

if ($_SESSION["UserID"]=="Guest" || ((strtotime(now())-strtotime($newdate." ".$datatmp3["TimeField"]))>=0 && $datatmp3["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($newdate))>0 && $datatmp3["DayEvent"]==1)) 
$strRow.="<a href=\"\" onclick=\"view_div(".date("j",strtotime($newdate)).",".date("n",strtotime($newdate)).",".date("Y",strtotime($newdate)).",".$datatmp3["id"].");return false;\"><b>"."\r\n"; 
else
$strRow.="<a href=\"\" onclick=\"edit_div(".date("j",strtotime($newdate)).",".date("n",strtotime($newdate)).",".date("Y",strtotime($newdate)).",".$datatmp3["id"].");return false;\"><b>"."\r\n"; 
$strtmp=$datatmp3["Theme"];
if (strlen(trim($strtmp))==0 || !$strtmp)
$strtmp="&lt;Empty&gt;";
if ((strtotime(now())-strtotime($newdate." ".$datatmp3["TimeField"]))>=0 && $datatmp3["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($newdate))>0 && $datatmp3["DayEvent"]==1)
$strRow.="<font color=red>";
if (strlen($strtmp)>36)
$strtmp=substr($strtmp,0,36)."...";
if (strlen(trim($strtmp))==0 || !$strtmp)
$strtmp="&lt;Empty&gt;";
$strRow.=$strtmp."&nbsp;<img src=images/repeat.gif border=0>";
if ((strtotime(now())-strtotime($newdate." ".$datatmp3["TimeField"]))>=0 && $datatmp3["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($newdate))>0 && $datatmp3["DayEvent"]==1)
$strRow.="</font>";
$strRow.="</b></a></td>";
$strtmp2="";
$strtmp2=$datatmp3["Description"];
if (strlen($strtmp2)>300)
$strtmp2=substr($strtmp2,0,300)."...";
$strRow.="<td>".$strtmp2."</td></tr></table></td></tr>";
$strRow.="<tr><td colspan=4 height=1></td></tr>";
$strRow.="</table>";
if ($smarty->get_template_vars("row00".$newweek."Day_value"))
{
$tmpstr = $smarty->get_template_vars("row00".$newweek."Day_value").$strRow;
$smarty->assign("row00".$newweek."Day_value",$tmpstr);
}
else
$smarty->assign("row00".$newweek."Day_value",$strRow);
}
//Period = 1 year
if ($datatmp3["Period"]=="yyyy")
$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate)), date("Y",strtotime($newdate))+1);
//Period = 1 week
if ($datatmp3["Period"]=="ww")
$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate))+7, date("Y",strtotime($newdate)));
//Period = 1 month
if ($datatmp3["Period"]=="d")
$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate))+1, date("Y",strtotime($newdate)));
//Period = 1 day
if ($datatmp3["Period"]=="m")
$newdate = mktime (0, 0, 0, date("n",strtotime($newdate))+1, date("j",strtotime($newdate)), date("Y",strtotime($newdate)));
$newdate = date("Y-m-d",$newdate);
$newweek=date("w",strtotime($newdate));
if ($newweek==0)
$newweek=7;
} 
}


$smarty->assign("iframe","<iframe name=iframelist style=\"display:none;\"></iframe>");

$tmpSQL="select max(id) as maxid from category";
$rstmp = db_query($tmpSQL,$conn);
$datatmp = db_fetch_array($rstmp);
$kol=$datatmp["maxid"];

$tmpSQL2="select * from category order by id";
$rstmp2 = db_query($tmpSQL2,$conn);

$monarray = "";
$monarray.= "<script>var a = new Array(".$kol.");";
while ($datatmp2 = db_fetch_array($rstmp2))
$monarray.= "a[".$datatmp2["id"]."]='".$datatmp2["Color"]."';";
$monarray.="</script>";

$smarty->assign("Weekly_Array",$monarray);

} // function BeforeShowList


















































function Weekly_Next_Prev(&$params)
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
$days1=$days-7;
$days2=$days+7;
$mon1=$mon;
$mon2=$mon;
$yr1=$yr;
$yr2=$yr;


if($days2>$mdays[$mon2])
{
    $days2-=$mdays[$mon2];
    $mon2++;
    if($mon2>12)
    {
        $yr2++;
        $mon2=1;
    }
}
if($days1<1)
{
    $mon1--;
    if($mon1<1)
    {
        $yr1--;
        $mon1=12;
    }
    $days1+=$mdays[$mon];
}

$tdate=$yr."-".$mon."-".$days;
$tdn=date("w",strtotime($tdate));
if ($tdn==0)
	$tdn=7;
$tdn--;

$nw=date("Y-m-d",strtotime($tdate)-60*60*24*$tdn);
$kw=date("Y-m-d",strtotime($nw)+60*60*24*6);

echo "<div align=center>";
echo "<a href=\"weekly_list.php?mon=".$mon1."&yr=".$yr1."&days=".$days1."\">prev</a>&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<b>";

if(date("n",strtotime($nw))!=date("n",strtotime($kw)))
{
    if(date("Y",strtotime($nw))!=date("Y",strtotime($kw)))
	 {
			echo "<select id=mselect onchange=\"window.location.href='weekly_list.php?days=1&mon='+document.getElementById('mselect').value+'&yr='+document.getElementById('yselect').value\">";
			for($i=1;$i<=12;$i++)
			{
				$s="";
				if($i==date("n",strtotime($nw)))
					$s=" selected";
				echo "<option value=" . $i . " " . $s . ">" . $mont[$i] . "</option>";
			}
			echo "</select>&nbsp;&nbsp;";

			echo "<select id=yselect onchange=\"window.location.href='weekly_list.php?days=1&mon='+document.getElementById('mselect').value+'&yr='+document.getElementById('yselect').value\">";
			for($i=date("Y");$i<=date("Y")+10;$i++)
			{			
				$s="";
				if($i==date("Y",strtotime($nw))) 
					$s=" selected";
				echo "<option value=" . $i . " " . $s . ">" . $i . "</option>";
			}
			echo "</select>";

			echo "&nbsp;-&nbsp;" . $mont[date("n",strtotime($kw))] . ",&nbsp;" . date("Y",strtotime($kw));
	 }
    else
    {
			echo "<select id=mselect onchange=\"window.location.href='weekly_list.php?days=1&mon='+document.getElementById('mselect').value+'&yr='+document.getElementById('yselect').value\">";
			for($i=1;$i<=12;$i++)
			{
				$s="";
				if($i==date("n",strtotime($nw)))
					$s=" selected";
				echo "<option value=" . $i . " " . $s . ">" . $mont[$i] . "</option>";
			}
			echo "</select>";

			echo "&nbsp;-&nbsp;" . $mont[date("n",strtotime($kw))] . ",&nbsp;";
			echo "<select id=yselect onchange=\"window.location.href='weekly_list.php?days=1&mon='+document.getElementById('mselect').value+'&yr='+document.getElementById('yselect').value\">";
			for($i=date("Y");$i<=date("Y")+10;$i++)
			{			
				$s="";
				if($i==date("Y",strtotime($nw))) 
					$s=" selected";
				echo "<option value=" . $i . " " . $s . ">" . $i . "</option>";
			}
			echo "</select>";
		}
}
else
{
			echo "<select id=mselect onchange=\"window.location.href='weekly_list.php?days=1&mon='+document.getElementById('mselect').value+'&yr='+document.getElementById('yselect').value\">";
			for($i=1;$i<=12;$i++)
			{
				$s="";
				if($i==date("n",strtotime($nw)))
					$s=" selected";
				echo "<option value=" . $i . " " . $s . ">" . $mont[$i] . "</option>";
			}
			echo "</select>&nbsp;&nbsp;";

			echo "<select id=yselect onchange=\"window.location.href='weekly_list.php?days=1&mon='+document.getElementById('mselect').value+'&yr='+document.getElementById('yselect').value\">";
			for($i=date("Y");$i<=date("Y")+10;$i++)
			{			
				$s="";
				if($i==date("Y",strtotime($nw))) 
					$s=" selected";
				echo "<option value=" . $i . " " . $s . ">" . $i . "</option>";
			}
			echo "</select>";
}

echo "</b>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"weekly_list.php?mon=".$mon2."&yr=".$yr2."&days=".$days2."\">next</a>";
echo "</div>";

}






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






























































// Print page: Before display
function BeforeShowPrint(&$smarty,&$templatefile)
{
global $conn;
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

$ndays = Array();
$ndays[1]=$locale_info["LOCALE_SDAYNAME1"];
$ndays[2]=$locale_info["LOCALE_SDAYNAME2"];
$ndays[3]=$locale_info["LOCALE_SDAYNAME3"];
$ndays[4]=$locale_info["LOCALE_SDAYNAME4"];
$ndays[5]=$locale_info["LOCALE_SDAYNAME5"];
$ndays[6]=$locale_info["LOCALE_SDAYNAME6"];
$ndays[7]=$locale_info["LOCALE_SDAYNAME7"];

$days=$_SESSION["days"];
$mon=$_SESSION["mon"];
$yr=$_SESSION["yr"];
if($days>$mdays[$mon])
	$days=$mdays[$mon];
if(round($yr/4,0)==$yr/4)
	$mdays[2]=29;
$wd=date("w",mktime(0,0,0,$mon,$days,$yr));
if ($wd==0)
	$wd=7;
$wd=$wd-1;
$nw = date("Y-m-d",strtotime($yr."-".$mon."-".$days)-60*60*24*$wd);
$nw1=$nw;

for ($i=1; $i<8; $i++)
{
	$smarty->assign("row00".$i."Date_value",$ndays[$i].", ".$mont[date("n",strtotime($nw))]." ".date("j",strtotime($nw)).", ".date("Y",strtotime($nw)));
	$smarty->assign("row00Dateadd".$i."_value","yr=".date("Y",strtotime($nw))."&mon=".date("n",strtotime($nw))."&days=".date("j",strtotime($nw)));
	$smarty->assign("row00AddOnclick".$i."_value","onclick='add_div(".date("j",strtotime($nw)).",".date("n",strtotime($nw)).",".date("Y",strtotime($nw)).");return false;'");
	if (date("Y",strtotime(now()))==date("Y",strtotime($nw)) && date("n",strtotime(now()))==date("n",strtotime($nw)) && date("j",strtotime(now()))==date("j",strtotime($nw)))
	{
		$smarty->assign("row00".$i."Now_value",True);
		$smarty->assign("row00".$i."Date_value",$ndays[$i].", ".$mont[date("n",strtotime($nw))]." ".date("j",strtotime($nw)).", ".date("Y",strtotime($nw)));
	}
	else
		$smarty->assign("row00".$i."Now_value",False);

	$smarty->assign("row00".$i."sDay_value",date("j",strtotime($nw)));
	$nw = date("Y-m-d",strtotime($nw)+60*60*24);
}


$strSQL="select DateField from calendar where DateField>='".date("Y-m-d",strtotime($nw1))."' and DateField<'".date("Y-m-d",strtotime($nw))."' group by DateField order by DateField";
$rstmp = db_query($strSQL,$conn);
while ($datatmp = db_fetch_array($rstmp))
{
	$strSQL2 = "select calendar.*, category.Color from calendar left join category 
			on category.id=calendar.Category where 
			DateField>='".date("Y-m-d",strtotime($nw1))."'
			and DateField<'".date("Y-m-d",strtotime($nw))."' 
			and DAYOFMONTH(DateField)=".date("j",strtotime($datatmp["DateField"]));	

	$rstmp2 = db_query($strSQL2,$conn);
	$strRow="";
	while ($datatmp2 = db_fetch_array($rstmp2))
	{
		//$strColor="";
		//if ($datatmp2["Color"])
		//	$strColor=" style=\"background-color:".$datatmp2["Color"]."\"";
		$strRow.="<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td>";
		$strRow.="<table width=100% cellpadding=1 cellspacing=0 style=\"border: 1px solid gray\" name=edit".$datatmp2["id"].">";
		$strRow.="<tr><td width=7></td>";

		$strRow.="<td colspan=4 height=3></td></tr>";
		$strRow.="<tr><td width=7 valign=top></td><td width=400 valign=top>";
		$k=3;
		if (!$datatmp2["DayEvent"]) 
		{
			$strRow.="<font face=Arial>Time:&nbsp;".dbvalue2time($datatmp2["TimeField"])."</font>&nbsp;";
			$k=1;
		}
		
		$strRow.="<b>";
		$strtmp=$datatmp2["Theme"];
		if (strlen($strtmp)>50)
			$strtmp=substr($strtmp,0,50)."...";
		$strRow.=$strtmp;
		$strRow.="</b></td>";
		$strtmp=$datatmp2["Description"];
		if (strlen($strtmp)>300)
			$strtmp=sunstr($strtmp,0,300)."...";
		$strRow.="<td width=800 valign=top>".$strtmp."</td></tr></table></td></tr>";
		$strRow.="<tr><td colspan=4 height=1></td></tr>";
		$strRow.="</table>";
	}
	$ww=date("w",strtotime($datatmp["DateField"]));
	if ($ww==0)
		$ww=7;
	$smarty->assign("row00".$ww."Day_value",$strRow);
}	

$strSQL3 = "select calendar.*, category.Color from calendar left join category on category.id=calendar.Category where Recurrence=1";
$rstmp3 = db_query($strSQL3,$conn);
while ($datatmp3 = db_fetch_array($rstmp3))
{
	//Period = 1 year
	if ($datatmp3["Period"]=="yyyy")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp3["DateField"])), date("j",strtotime($datatmp3["DateField"])), date("Y",strtotime($datatmp3["DateField"]))+1);
	//Period = 1 week
	if ($datatmp3["Period"]=="ww")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp3["DateField"])), date("j",strtotime($datatmp3["DateField"]))+7, date("Y",strtotime($datatmp3["DateField"])));
	//Period = 1 month
	if ($datatmp3["Period"]=="d")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp3["DateField"])), date("j",strtotime($datatmp3["DateField"]))+1, date("Y",strtotime($datatmp3["DateField"])));
	//Period = 1 day
	if ($datatmp3["Period"]=="m")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp3["DateField"]))+1, date("j",strtotime($datatmp3["DateField"])), date("Y",strtotime($datatmp3["DateField"])));
	$newdate = date("Y-m-d",$newdate);

	$newweek=date("w",strtotime($newdate));
	if ($newweek==0)
		$newweek=7;
	while (((strtotime($datatmp3["EndDate"])-strtotime($newdate))/60*60*24)>=0)
	{
		$strRow="";
		if ((strtotime($nw1)-strtotime($newdate))<=0 && (strtotime($nw)-strtotime($newdate))>0)
		{
			$strRow.="<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td>";
			$strRow.="<table cellpadding=1 cellspacing=0 width=100% border=0 style=\"border: 1px solid gray\" name=edit".$datatmp3["id"].">";
			$strRow.="<tr><td width=7></td>";

			$strRow.="<td colspan=4 height=3></td></tr>";
			$strRow.="<tr><td width=7 valign=top></td><td width=400>";
			$k=3;
			if (!$datatmp3["DayEvent"])
			{
				$strRow.="<font face=Arial>Time:&nbsp;".dbvalue2time($datatmp3["TimeField"])."</font>&nbsp;";
				$k=1;
			}

			$strRow.="<b>";
			$strtmp=$datatmp3["Theme"];
			if (strlen($strtmp)>36)
				$strtmp=substr($strtmp,0,36)."...";
			if (strlen(trim($strtmp))==0 || !$strtmp)
				$strtmp="&lt;Empty&gt;";
			$strRow.=$strtmp."&nbsp;<img src=images/repeat.gif border=0>";
			$strRow.="</b></td>";
			$strtmp2="";
			$strtmp2=$datatmp3["Description"];
			if (strlen($strtmp2)>300)
				$strtmp2=substr($strtmp2,0,300)."...";
			$strRow.="<td>".$strtmp2."</td></tr></table></td></tr>";
			$strRow.="<tr><td colspan=4 height=1></td></tr>";
			$strRow.="</table>";
			if ($smarty->get_template_vars("row00".$newweek."Day_value"))
			{
				$tmpstr = $smarty->get_template_vars("row00".$newweek."Day_value").$strRow;
				$smarty->assign("row00".$newweek."Day_value",$tmpstr);
			}
			else
				$smarty->assign("row00".$newweek."Day_value",$strRow);
		}
		//Period = 1 year
		if ($datatmp3["Period"]=="yyyy")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate)), date("Y",strtotime($newdate))+1);
		//Period = 1 week
		if ($datatmp3["Period"]=="ww")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate))+7, date("Y",strtotime($newdate)));
		//Period = 1 month
		if ($datatmp3["Period"]=="d")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate))+1, date("Y",strtotime($newdate)));
		//Period = 1 day
		if ($datatmp3["Period"]=="m")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate))+1, date("j",strtotime($newdate)), date("Y",strtotime($newdate)));
		$newdate = date("Y-m-d",$newdate);
		$newweek=date("w",strtotime($newdate));
	if ($newweek==0)
		$newweek=7;
	}	
}

} // function BeforeShowPrint









?>