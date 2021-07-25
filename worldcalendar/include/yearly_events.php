<?php

























// List page: Before display
function BeforeShowList(&$smarty,&$templatefile)
{
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

$yr=$_SESSION["yr"];

if(round($yr/4)==$yr/4)
	$mdays[2]=29;

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

$smarty->assign("row00sdname1_value",$locale_info["LOCALE_SABBREVDAYNAME1"]);
$smarty->assign("row00sdname2_value",$locale_info["LOCALE_SABBREVDAYNAME2"]);
$smarty->assign("row00sdname3_value",$locale_info["LOCALE_SABBREVDAYNAME3"]);
$smarty->assign("row00sdname4_value",$locale_info["LOCALE_SABBREVDAYNAME4"]);
$smarty->assign("row00sdname5_value",$locale_info["LOCALE_SABBREVDAYNAME5"]);
$smarty->assign("row00sdname6_value",$locale_info["LOCALE_SABBREVDAYNAME6"]);
$smarty->assign("row00sdname7_value",$locale_info["LOCALE_SABBREVDAYNAME7"]);

for($i=1;$i<=12;$i++)
{
	$smarty->assign("row00MonthName" . $i . "_value",$mont[$i]);
	$smarty->assign("linkMonthly" . $i,"monthly_list.php?yr=" . $yr . "&mon=" . $i);
	$wd=date("w",mktime(0,0,0,$i,1,$yr));
	if ($wd==0)
		$wd=7;
	$wd=$wd+1;

	$c=1;
	for($k=1;$k<=6;$k++)
	{
		for($m=1;$m<=8;$m++)
		{
			if($m>1 && ($k-1)*8+$m>=$wd)
			{
				$smarty->assign("row00Month" . $i . "Day" . (($k-1)*8+$m) . "_value",$c);
				$smarty->assign("linkDayM" . $i . "Day" . (($k-1)*8+$m),"daily_list.php?yr=" . $yr . "&mon=" . $i . "&days=" . $c);
				if(date("Y")==$yr && date("n")==$i && date("j")==$c)
					$smarty->assign("NowM" . $i . "D" . (($k-1)*8+$m),"font-weight:normal;border:2px solid #990000");
				if($m==7 || $m==8)
					$smarty->assign("style_m" . $i . "d" . (($k-1)*8+$m),"class=blackshade");
				else
					$smarty->assign("style_m" . $i . "d" . (($k-1)*8+$m),"class=shade");
				$c++;
				if($c>$mdays[$i])
				{
					$m=100;
					$k=100;
				}
			}
			$kk=$k;
			if($k==100)
				$kk=6;
			if($smarty->get_template_vars("row00Month" . $i . "Day" . (($kk-1)*8+2) . "_value"))
				$smarty->assign("linkWeeklyM" . $i . "Week" . $kk,"weekly_list.php?yr=" . $yr . "&mon=" . $i . "&days=" . $smarty->get_template_vars("row00Month" . $i . "Day" . (($kk-1)*8+2) . "_value"));
			else if($smarty->get_template_vars("row00Month" . $i . "Day" . (($kk-1)*8+8) . "_value"))
				$smarty->assign("linkWeeklyM" . $i . "Week" . $kk,"weekly_list.php?yr=" . $yr . "&mon=" . $i . "&days=" . $smarty->get_template_vars("row00Month" . $i . "Day" . (($kk-1)*8+8) . "_value"));
		}
		if($k==100)
			if(!$smarty->get_template_vars("row00Month" . $i . "Day42_value"))
				$smarty->assign("Month" . $i . "HideRow6","display:none;");
			else
				$smarty->assign("Month" . $i . "HideRow6","");
	}
}
} // function BeforeShowList


















































function Yearly_Next_Prev(&$params)
{
$days=$_SESSION["days"];
$mon=$_SESSION["mon"];
$yr=$_SESSION["yr"];

$yr1=$yr-1;
$yr2=$yr+1;

echo "<div align=center>";
echo "<a href=\"yearly_list.php?yr=" . $yr1 . "\"><span style=\"font-size:14\">prev</span></a>&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<select id=yselect onchange=\"window.location.href='yearly_list.php?yr='+document.getElementById('yselect').value\" style=\"font-size:16\">";
for($i=date("Y");$i<=date("Y")+10;$i++)
{
	$s="";
	if($i==$yr+0)
		$s=" selected";
	echo "<option value=" . $i . " " . $s . ">" . $i . "</option>";
}
echo "</select>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"yearly_list.php?yr=" . $yr2 . "\"><span style=\"font-size:14\">next</span></a>";
echo "</div>";
}


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





































?>