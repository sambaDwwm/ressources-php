<?php
/*
version:	2.0
date:		2007-12-22

author:		Jim Mayes
email:		jim.mayes@gmail.com
web:		style-vs-substance.com

note:		Example 7 - Previous, Current and Next Months

copyright:	Use of this script is goverened by the terms of the 
			Creative Commons Attribution-Share Alike 3.0 License
			http://creativecommons.org/licenses/by-sa/3.0/
			
			Author reserves the right to grant licenses for commercial use.
*/

//--------------------------------------------------- include calendar.class.php
require_once('../calendar.class.php');

//--------------------------------------- check $_GET for date passed from links
$date = ( isset($_GET['date']) )? $_GET['date'] : date("Y-m-d");

//--------------------------------------------------- initialize calendar object
/*
Dynamic Date
*/
$calendar = new Calendar($date);

//------------------- first day of month for calulation previous and next months
$first_day = $calendar->year . "-" . $calendar->month . "-01";

$previous_year = date("Y", strtotime("-1 month", strtotime($first_day)));
$previous_month = date("m", strtotime("-1 month", strtotime($first_day)));

$next_year = date("Y", strtotime("+1 month", strtotime($first_day)));
$next_month = date("m", strtotime("+1 month", strtotime($first_day)));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>calendar.class.php - Example 7 - Previous, Current and Next Months</title>
<style type="text/css">
<!--
@import url("base_calendar_style.css");
-->
</style>
</head>

<body>
<?php
//-------------------------------------------------------------- output calendar
print($calendar->output_calendar($previous_year, $previous_month));
print($calendar->output_calendar());
print($calendar->output_calendar($next_year, $next_month));
?>

<p><a href="index.html">back to examples</a></p>
</body>
</html>
<!--
calendar.class.php v2.0
copyright © 2007 Jim Mayes
licensed under: Creative Commons Attribution-Share Alike 3.0 License (http://creativecommons.org/licenses/by-sa/3.0/)
This class may not be used for commercial purposes without written consent.
Visit style-vs-substance.com for information and updates
-->