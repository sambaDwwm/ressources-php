<?php



// Menu page: Before process
function BeforeProcessMenu(&$conn)
{
//**********  Redirect to another page  ************
header("Location: monthly_list.php");
exit();
}

function dbvalue2time($value)
{
$t = "";
if ($value!="")
{
	$tm1 = localtime(strtotime($value));
	$arr = array(0,0,0,$tm1[2],$tm1[1],0);
	$t = format_time($arr);
}
return $t;
} // function BeforeProcessMenu



















?>