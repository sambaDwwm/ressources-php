<?php

























// List page: Before display
function BeforeShowList(&$smarty,&$templatefile)
{
global $conn;

//change#1
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
//$mdays = Array(31,28,31,30,31,30,31,31,30,31,30,31);
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

$smarty->assign("row00sdname1",$ndays[1]);
$smarty->assign("row00sdname2",$ndays[2]);
$smarty->assign("row00sdname3",$ndays[3]);
$smarty->assign("row00sdname4",$ndays[4]);
$smarty->assign("row00sdname5",$ndays[5]);
$smarty->assign("row00sdname6",$ndays[6]);
$smarty->assign("row00sdname7",$ndays[7]);

$days=$_SESSION["days"]+0;
$mon=$_SESSION["mon"]+0;
$yr=$_SESSION["yr"]+0;

if($days>$mdays[$mon])
	$days=$mdays[$mon];

if(round($yr/4,0)==$yr/4)
	$mdays[2]=29;

$wd=date("w",mktime(0,0,0,$mon,1,$yr));
if ($wd==0)
	$wd=7;
$wd=$wd-1;

for ($i=1; $i<=50; $i++)
{
	$smarty->assign("row00".$i."WeekDays_value","");
	$smarty->assign("row00AddHideM".($i+$wd)."_value","style=\"display:none;\"");
}
for ($i=1; $i<=$mdays[$mon]; $i++)
{
	if(strtotime(date("Y-m-d"))-strtotime($yr."-".$mon."-".$i)<=0)
		$smarty->assign("row00AddHideM" . (int)($i+$wd) . "_value","");
	$smarty->assign("row00".(int)($i+$wd)."Date_value",$i);
	$smarty->assign("row00Dateadd".(int)($i+$wd)."_value","yr=".$yr."&mon=".$mon."&days=".$i);
	$smarty->assign("row00more" . ($i+$wd) . "_value","style=\"display:none;\"");
	$smarty->assign("row00".$i."WeekDays_value","style=\"background-color:#C5D2E5;\"");
	$smarty->assign("row00AddOnclick".(int)($i+$wd)."_value","onclick='add_div(".$i.",".$mon.",".$yr.");return false;'");
	if (date("Y")==$yr && date("n")==$mon && date("j")==$i)
		$smarty->assign("row00".(int)($i+$wd)."Now_value","style=\"border:2px solid #990000\"");
	else
		$smarty->assign("row00".(int)($i+$wd)."Now_value","style=\"border:1px solid #CCCCCC\"");
}

for ($i=1; $i<=$mdays[$mon]; $i++)
{
	$_SESSION[$i."-".$mon."-".$yr]=0;
	$_SESSION["more".$i."-".$mon."-".$yr]=0;
	$_SESSION["count".$i."-".$mon."-".$yr]=0;
}

//change #2,#3
$strSQL = "select DateField from calendar where Year(DateField)=".$yr." and Month(DateField)=".$mon." and idusercal=".$_SESSION["_".$strTableName."_OwnerID"]." group by DateField";

$rstmp = db_query($strSQL,$conn);
while ($datatmp = db_fetch_array($rstmp))
{
	$strSQL2 = "select calendar.*, category.Color from calendar left join category on category.id=calendar.Category where Year(DateField)=".$yr." and Month(DateField)=".$mon." and DAYOFMONTH(DateField)=".date("j",strtotime($datatmp["DateField"]))." and idusercal=".$_SESSION["_".$strTableName."_OwnerID"];
   $rstmp2 = db_query($strSQL2,$conn);
	
	$strRow="";
	$kolstr=date("j-n-Y",strtotime($datatmp["DateField"]));
	while ($datatmp2 = db_fetch_array($rstmp2))
	{
		$a=strlen($datatmp2["Theme"]);
		if($a>15)
		{
			 if($_SESSION[$kolstr]<4)
				  $a=30;
			 else
				  $a=15;
		}
		else
			 $a=15;

		$_SESSION["more" . $kolstr]+=$a;
		$_SESSION["count" . $kolstr]++;
		if($_SESSION[$kolstr]<5)
		{
			$_SESSION[$kolstr]=$_SESSION[$kolstr]+1;
			$strColor="";
			if ($datatmp2["Color"])
				$strColor=" style=\"background-color:".$datatmp2["Color"]."\"";
			$strRow.="<table cellpadding=0 cellspacing=0 width=100% border=0><tr><td>"."\r\n";
			$strRow.="<table cellpadding=1 cellspacing=0 width=100% border=0 style=\"border: 1px solid gray\" id=edit".$datatmp2["id"].">"."\r\n";
			//if strColor1<>"" then 
			$strRow.="<tr><td width=7 ".$strColor."></td>"."\r\n";
			$c1="";
			$c2="";
			if ($datatmp2["DayEvent"]) 
				$c1=$strColor;
			else
				$c2=$strColor;
			$strRow.= "<td colspan=2 ".$c1." height=3></td></tr>"."\r\n";
			$strRow.= "<tr><td width=7 ".$c2." valign=top></td><td>"."\r\n";
			$k=2;
			if (!$datatmp2["DayEvent"]) 
			{
				$strRow.="<font face=Arial>".trim(dbvalue2time($datatmp2["TimeField"]))."</font>"."\r\n";
				$k=1;
			}
			if($datatmp2["DateField"])
				if ($_SESSION["UserID"]=="Guest" || ((strtotime(now())-strtotime($datatmp2["DateField"]." ".$datatmp2["TimeField"]))>=0 && $datatmp2["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($datatmp2["DateField"]))>0 && $datatmp2["DayEvent"]==1)) 
					$strRow.="<a href=\"\" onclick=\"view_div(".date("j",strtotime($datatmp2["DateField"])).",".date("n",strtotime($datatmp2["DateField"])).",".date("Y",strtotime($datatmp2["DateField"])).",".$datatmp2["id"].");return false;\"><b>"."\r\n"; 
				else
					$strRow.="<a href=\"\" onclick=\"edit_div(".date("j",strtotime($datatmp2["DateField"])).",".date("n",strtotime($datatmp2["DateField"])).",".date("Y",strtotime($datatmp2["DateField"])).",".$datatmp2["id"].");return false;\"><b>"."\r\n"; 
			$strtmp=$datatmp2["Theme"];
			if (strlen($strtmp)>15)
				$_SESSION[$kolstr]=$_SESSION[$kolstr]+1;
			if ((strtotime(now())-strtotime($datatmp2["DateField"]." ".$datatmp2["TimeField"]))>=0 && $datatmp2["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($datatmp2["DateField"]))>0 && $datatmp2["DayEvent"]==1)
				$strRow.="<font color=red>";
			if (strlen($strtmp)>30)
				$strtmp=substr($strtmp,0,27)."...";
			if (strlen($strtmp)>15 && $_SESSION[$kolstr]>5)
				$ztrtmp=substr($strtmp,0,12)."...";
			if (strlen(trim($strtmp))==0 || !$strtmp)
				$strtmp="&lt;Empty&gt;";
			$strRow.=$strtmp."\r\n";
			if ((strtotime(now())-strtotime($datatmp2["DateField"]." ".$datatmp2["TimeField"]))>=0 && $datatmp2["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($datatmp2["DateField"]))>0 && $datatmp2["DayEvent"]==1)
				$strRow.="</font>"."\r\n";
			$strRow.="</b></a></td></tr></table></td></tr>"."\r\n";
			if ($_SESSION[$kolstr]<=5)
				$strRow.="<tr><td colspan=3 height=1></td></tr>"."\r\n";
			$strRow.="</table>"."\r\n";
		}
	}
	
	$smarty->assign("row00".(date("j",strtotime($datatmp["DateField"]))+$wd+0)."Day_value",$strRow);
	$smarty->assign("row00".(date("j",strtotime($datatmp["DateField"]))+$wd+0)."KolStr_value",$_SESSION[$kolstr]);
	if($_SESSION["more" . $kolstr]>75)
		$smarty->assign("row00more".(date("j",strtotime($datatmp["DateField"]))+$wd+0)."_value","");
	$smarty->assign("row00count".(date("j",strtotime($datatmp["DateField"]))+$wd+0)."_value",$_SESSION["count" . $kolstr]);
}

//change #4

$strSQL2 = "select calendar.*, category.Color from calendar left join category on category.id=calendar.Category where Recurrence=1 and idusercal=".$_SESSION["_".$strTableName."_OwnerID"];
$rstmp2 = db_query($strSQL2,$conn);
while ($datatmp2 = db_fetch_array($rstmp2))
{
	//Period = 1 year
	if ($datatmp2["Period"]=="yyyy")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp2["DateField"])), date("j",strtotime($datatmp2["DateField"])), date("Y",strtotime($datatmp2["DateField"]))+1);
	//Period = 1 week
	if ($datatmp2["Period"]=="ww")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp2["DateField"])), date("j",strtotime($datatmp2["DateField"]))+7, date("Y",strtotime($datatmp2["DateField"])));
	//Period = 1 month
	if ($datatmp2["Period"]=="d")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp2["DateField"])), date("j",strtotime($datatmp2["DateField"]))+1, date("Y",strtotime($datatmp2["DateField"])));
	//Period = 1 day
	if ($datatmp2["Period"]=="m")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp2["DateField"]))+1, date("j",strtotime($datatmp2["DateField"])), date("Y",strtotime($datatmp2["DateField"])));
	$newdate = date("Y-m-d",$newdate);

	while (((strtotime($datatmp2["EndDate"])-strtotime($newdate))/60*60*24)>=0)
	{
		$strRow="";
		$kolstr=date("j-n-Y",strtotime($newdate));
		if (date("n",strtotime($newdate))==$_SESSION["mon"] && date("Y",strtotime($newdate))==$_SESSION["yr"])
		{
			$a=strlen($datatmp2["Theme"]);
			if($a>15)
			{
				 if($_SESSION[$kolstr]<4)
					  $a=30;
				 else
					  $a=15;
			}
			else
				 $a=15;

			$_SESSION["more" . $kolstr]+=$a;
			$_SESSION["count" . $kolstr]++;
			if($_SESSION[$kolstr]<5)
			{
				$_SESSION[$kolstr]=$_SESSION[$kolstr]+1;
				$strColor="";
				if ($datatmp2["Color"])
					$strColor=" style=\"background-color:".$datatmp2["Color"]."\"";
				$strRow.="<table cellpadding=0 cellspacing=0 width=100% border=0><tr><td>"."\r\n";
				$strRow.="<table cellpadding=1 cellspacing=0 width=100% border=0 style=\"border: 1px solid gray\">"."";
				//if strColor1<>"" then 
				$strRow.="<tr><td width=7 ".$strColor."></td>"."\r\n";
				$c1="";
				$c2="";
				if ($datatmp2["DayEvent"]) 
					$c1=$strColor;
				else
					$c2=$strColor;
				$strRow.="<td colspan=2 ".$c1." height=3></td></tr>"."\r\n";
				$strRow.="<tr><td width=7 ".$c2." valign=top></td><td>"."\r\n";
				$k=2;
				if (!$datatmp2["DayEvent"]) 
				{
					$strRow.="<font face=Arial>".trim(dbvalue2time($datatmp2["TimeField"]))."</font>"."\r\n";
					$k=1;
				}
				if($datatmp2["DateField"])
					if ($_SESSION["UserID"]=="Guest" || ((strtotime(now())-strtotime($newdate." ".$datatmp2["TimeField"]))>=0 && $datatmp2["DayEvent"]!=1 || (strtotime(date("Y-m-d"))-strtotime($newdate))>0 && $datatmp2["DayEvent"]==1)) 
						$strRow.="<a href=\"\" onclick=\"view_div(".date("j",strtotime($newdate)).",".date("n",strtotime($newdate)).",".date("Y",strtotime($newdate)).",".$datatmp2["id"].");return false;\"><b>"."\r\n"; 
					else
						$strRow.="<a href=\"\" onclick=\"edit_div(".date("j",strtotime($newdate)).",".date("n",strtotime($newdate)).",".date("Y",strtotime($newdate)).",".$datatmp2["id"].");return false;\"><b>"."\r\n"; 				
				$strtmp=$datatmp2["Theme"];
				if (strlen($strtmp)>18)
					$_SESSION[$kolstr]=$_SESSION[$kolstr]+1;
 			   if (strtotime(now())-strtotime($newdate." ".$datatmp2["TimeField"])>=0 && $datatmp2["DayEvent"]!=1 || strtotime(date("Y-m-d"))-strtotime($newdate)>0 && $datatmp2["DayEvent"]==1)
					$strRow.="<font color=red>";
				if (strlen($strtmp)>36)
					$strtmp=substr($strtmp,0,33)."...";
				if (strlen($strtmp)>18 && $_SESSION[$kolstr]>5)
					$strtmp=substr($strtmp,0,15)."...";
				if (strlen(trim($strtmp))==0 || !$strtmp)
					$strtmp="&lt;Empty&gt;";
				$strRow.=$strtmp." <img src=images/repeat.gif border=0>"."\r\n";
			   if (strtotime(now())-strtotime($newdate." ".$datatmp2["TimeField"])>=0 && $datatmp2["DayEvent"]!=1 || strtotime(date("Y-m-d"))-strtotime($newdate)>0 && $datatmp2["DayEvent"]==1)
					$strRow.="</font>";
				$strRow.="</b></a></td></tr></table></td></tr>"."\r\n";
				if ($_SESSION[$kolstr]<=5)
					$strRow.="<tr><td colspan=3 height=1></td></tr>"."\r\n";
				$strRow.="</table>"."\r\n";
				$strRow2 = $smarty->get_template_vars("row00".(date("j",strtotime($newdate))+$wd+0)."Day_value").$strRow;
				$smarty->assign("row00".(date("j",strtotime($newdate))+$wd+0)."Day_value",$strRow2);			
				$smarty->assign("row00".(date("j",strtotime($newdate))+$wd)."KolStr_value",$_SESSION[$kolstr]);
			}
			if($_SESSION["more" . $kolstr]>75)
				$smarty->assign("row00more".(date("j",strtotime($newdate))+$wd+0)."_value","");
			$smarty->assign("row00count".(date("j",strtotime($newdate))+$wd+0)."_value",$_SESSION["count" . $kolstr]);
		}
		//Period = 1 year
		if ($datatmp2["Period"]=="yyyy")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate)), date("Y",strtotime($newdate))+1);
		//Period = 1 week
		if ($datatmp2["Period"]=="ww")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate))+7, date("Y",strtotime($newdate)));
		//Period = 1 month
		if ($datatmp2["Period"]=="d")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate))+1, date("Y",strtotime($newdate)));
		//Period = 1 day
		if ($datatmp2["Period"]=="m")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate))+1, date("j",strtotime($newdate)), date("Y",strtotime($newdate)));
		$newdate = date("Y-m-d",$newdate);
		//$newdate in Unix format
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

$smarty->assign("Monthly_Array",$monarray);
} // function BeforeShowList














// List page: Before process
function BeforeProcessList(&$conn)
{
//************************added***************************
if ($_REQUEST["ajaxcount"])
{
			for ($i=0;$i<100;$i++)
			db_exec("insert into numbers (n) values (".($i+$_REQUEST["maxn"]+0).")",$conn);
			exit();
}
//********************************************************

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










































































function Monthly_Next_Prev(&$params)
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

$mon1=$mon-1;
$mon2=$mon+1;
$yr1=$yr;
$yr2=$yr;

if($mon2>12)
{
    $yr2++;
    $mon2=1;
}
if($mon1<1)
{
    $yr1--;
    $mon1=12;
}

if($days>$mdays[$mon])
{
    $days=1;
    $mon=$mon+1;
    if($mon>12)
    {
        $yr++;
        $mon=1;
    }
}
if($days<1)
{
    $mon--;
    if($mon<1)
    {
        $yr--;
        $mon=12;
    }
    $days=$mdays[$mon];
}
echo "<div align=center>";
echo "<a href=\"monthly_list.php?mon=".$mon1."&yr=".$yr1."\">prev</a>&nbsp;&nbsp;&nbsp;&nbsp;";

echo "<select id=mselect onchange=\"window.location.href='monthly_list.php?mon='+document.getElementById('mselect').value+'&yr='+document.getElementById('yselect').value\">";
for($i=1;$i<=12;$i++)
{
	$s="";
	if($i==$mon)
		$s=" selected";
	echo "<option value=" . $i . " " . $s . ">" . $mont[$i] . "</option>";
}
echo "</select>&nbsp;&nbsp;";

echo "<select id=yselect onchange=\"window.location.href='monthly_list.php?mon='+document.getElementById('mselect').value+'&yr='+document.getElementById('yselect').value\">";
for($i=Date("Y");$i<=Date("Y")+10;$i++)
{
	$s="";
	if($i==$yr)
		$s=" selected";
	echo "<option value=" . $i . " " . $s . ">" . $i . "</option>";
}
echo "</select>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"monthly_list.php?mon=".$mon2."&yr=".$yr2."\">next</a>";
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

//$mdays = Array(31,28,31,30,31,30,31,31,30,31,30,31);
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

$days=$_SESSION["days"]+0;
$mon=$_SESSION["mon"]+0;
$yr=$_SESSION["yr"]+0;

if($days>$mdays[$mon])
	$days=$mdays[$mon];

if(round($yr/4,0)==$yr/4)
	$mdays[2]=29;

$wd=date("w",mktime(0,0,0,$mon,1,$yr));
if ($wd==0)
	$wd=7;
$wd=$wd-1;

for ($i=1; $i<=$mdays[$mon]; $i++)
{
	$smarty->assign("row00".(int)($i+$wd)."Date_value",$i);
}

for ($i=1; $i<=$mdays[$mon]; $i++)
	$_SESSION[$i."-".$mon."-".$yr]=0;

$strSQL = "select DateField from calendar where Year(DateField)=".$yr." and Month(DateField)=".$mon." group by DateField";
$rstmp = db_query($strSQL,$conn);
while ($datatmp = db_fetch_array($rstmp))
{
	$strSQL2 = "select calendar.*, category.Color from calendar left join category on category.id=calendar.Category where Year(DateField)=".$yr." and Month(DateField)=".$mon." and DAYOFMONTH(DateField)=".date("j",strtotime($datatmp["DateField"]));
	$rstmp2 = db_query($strSQL2,$conn);
	
	$strRow="";
	$kolstr=date("j-n-Y",strtotime($datatmp["DateField"]));
	while (($datatmp2 = db_fetch_array($rstmp2)) && $_SESSION[$kolstr]<5)
	{
		$_SESSION[$kolstr]=$_SESSION[$kolstr]+1;
		
		$strRow.="<table cellpadding=0 cellspacing=0 width=100% border=0><tr><td>"."\r\n";
		$strRow.="<table cellpadding=1 cellspacing=0 width=100% border=0 style=\"border: 1px solid gray\" id=edit".$datatmp2["id"].">"."\r\n";
		$strRow.="<tr><td width=7></td>"."\r\n";
		
		$strRow.= "<td colspan=2 height=3></td></tr>"."\r\n";
		$strRow.= "<tr><td width=7 valign=top></td><td>"."\r\n";
		$k=2;
		if (!$datatmp2["DayEvent"]) 
		{
			$strRow.="<font face=Arial>".trim(dbvalue2time($datatmp2["TimeField"]))."</font>"."\r\n";
			$k=1;
		}
		$strRow.="<b>";
		$strtmp=$datatmp2["Theme"];
		if (strlen($strtmp)>15)
			$_SESSION[$kolstr]=$_SESSION[$kolstr]+1;
		if (strlen($strtmp)>30)
			$strtmp=substr($strtmp,0,27)."...";
		if (strlen($strtmp)>15 && $_SESSION[$kolstr]>5)
			$ztrtmp=substr($strtmp,0,12)."...";
		if (strlen(trim($strtmp))==0 || !$strtmp)
			$strtmp="&lt;Empty&gt;";
		$strRow.=$strtmp."\r\n";
		$strRow.="</b></td></tr></table></td></tr>"."\r\n";
		if ($_SESSION[$kolstr]<=5)
			$strRow.="<tr><td colspan=3 height=1></td></tr>"."\r\n";
		$strRow.="</table>"."\r\n";
	}
	
	$smarty->assign("row00".(date("j",strtotime($datatmp["DateField"]))+$wd+0)."Day_value",$strRow);
	$smarty->assign("row00".(date("j",strtotime($datatmp["DateField"]))+$wd+0)."KolStr_value",$_SESSION[$kolstr]);
}

$strSQL2 = "select calendar.*, category.Color from calendar left join category on category.id=calendar.Category where Recurrence=1";
$rstmp2 = db_query($strSQL2,$conn);
while ($datatmp2 = db_fetch_array($rstmp2))
{
	//Period = 1 year
	if ($datatmp2["Period"]=="yyyy")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp2["DateField"])), date("j",strtotime($datatmp2["DateField"])), date("Y",strtotime($datatmp2["DateField"]))+1);
	//Period = 1 week
	if ($datatmp2["Period"]=="ww")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp2["DateField"])), date("j",strtotime($datatmp2["DateField"]))+7, date("Y",strtotime($datatmp2["DateField"])));
	//Period = 1 month
	if ($datatmp2["Period"]=="d")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp2["DateField"])), date("j",strtotime($datatmp2["DateField"]))+1, date("Y",strtotime($datatmp2["DateField"])));
	//Period = 1 day
	if ($datatmp2["Period"]=="m")
		$newdate = mktime (0, 0, 0, date("n",strtotime($datatmp2["DateField"]))+1, date("j",strtotime($datatmp2["DateField"])), date("Y",strtotime($datatmp2["DateField"])));
	$newdate = date("Y-m-d",$newdate);

	while (((strtotime($datatmp2["EndDate"])-strtotime($newdate))/60*60*24)>=0)
	{
		$strRow="";
		$kolstr=date("j-n-Y",strtotime($newdate));
		if (date("n",strtotime($newdate))==$_SESSION["mon"] && date("Y",strtotime($newdate))==$_SESSION["yr"] && $_SESSION[$kolstr]<5)
		{
			$_SESSION[$kolstr]=$_SESSION[$kolstr]+1;
			$strRow.="<table cellpadding=0 cellspacing=0 width=100% border=0><tr><td>"."\r\n";
			$strRow.="<table cellpadding=1 cellspacing=0 width=100% border=0 style=\"border: 1px solid gray\">"."";
			$strRow.="<tr><td width=7></td>"."\r\n";

			$strRow.="<td colspan=2 height=3></td></tr>"."\r\n";
			$strRow.="<tr><td width=7 valign=top></td><td>"."\r\n";
			$k=2;
			if (!$datatmp2["DayEvent"]) 
			{
				$strRow.="<font face=Arial>".trim(dbvalue2time($datatmp2["TimeField"]))."</font>"."\r\n";
				$k=1;
			}
			$strRow.="<b>";
			$strtmp=$datatmp2["Theme"];
			if (strlen($strtmp)>18)
				$_SESSION[$kolstr]=$_SESSION[$kolstr]+1;
			if (strlen($strtmp)>36)
				$strtmp=substr($strtmp,0,33)."...";
			if (strlen($strtmp)>18 && $_SESSION[$kolstr]>5)
				$strtmp=substr($strtmp,0,15)."...";
			if (strlen(trim($strtmp))==0 || !$strtmp)
				$strtmp="&lt;Empty&gt;";
			$strRow.=$strtmp." <img src=images/repeat.gif border=0>"."\r\n";
			$strRow.="</b></td></tr></table></td></tr>"."\r\n";
			if ($_SESSION[$kolstr]<=5)
				$strRow.="<tr><td colspan=3 height=1></td></tr>"."\r\n";
			$strRow.="</table>"."\r\n";
			$strRow2 = $smarty->get_template_vars("row00".(date("j",strtotime($newdate))+$wd+0)."Day_value").$strRow;
			$smarty->assign("row00".(date("j",strtotime($newdate))+$wd+0)."Day_value",$strRow2);			
			$smarty->assign("row00".(date("j",strtotime($newdate))+$wd)."KolStr_value",$_SESSION[$kolstr]);
		}
		//Period = 1 year
		if ($datatmp2["Period"]=="yyyy")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate)), date("Y",strtotime($newdate))+1);
		//Period = 1 week
		if ($datatmp2["Period"]=="ww")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate))+7, date("Y",strtotime($newdate)));
		//Period = 1 month
		if ($datatmp2["Period"]=="d")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate)), date("j",strtotime($newdate))+1, date("Y",strtotime($newdate)));
		//Period = 1 day
		if ($datatmp2["Period"]=="m")
			$newdate = mktime (0, 0, 0, date("n",strtotime($newdate))+1, date("j",strtotime($newdate)), date("Y",strtotime($newdate)));
		$newdate = date("Y-m-d",$newdate);
		//$newdate in Unix format
	}
}


} // function BeforeShowPrint









?>