<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="yearly";
	$tdata[".OwnerID"]="idusercal";

	
//	id
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "id";
		$fdata["FullName"]= "`id`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["id"]=$fdata;
	
//	idusercal
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "idusercal";
		$fdata["FullName"]= "`calendar`.`idusercal`";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
							$tdata["idusercal"]=$fdata;
	
//	DateField
	$fdata = array();
	 $fdata["Label"]="Date"; 
	
	
	$fdata["FieldType"]= 7;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		$fdata["GoodName"]= "DateField";
		$fdata["FullName"]= "`DateField`";
	
	
	
	
	$fdata["Index"]= 3;
	 $fdata["DateEditType"]=13; 
							$tdata["DateField"]=$fdata;
	
//	Description
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "Description";
		$fdata["FullName"]= "`Description`";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
							$tdata["Description"]=$fdata;
	
//	Theme
	$fdata = array();
	 $fdata["Label"]="Subject"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "Custom";
	
	
		$fdata["GoodName"]= "Theme";
		$fdata["FullName"]= "`Theme`";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=200";
					$fdata["FieldPermissions"]=true;
		$tdata["Theme"]=$fdata;
	
//	TimeField
	$fdata = array();
	 $fdata["Label"]="Time"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "Time";
	
	
		$fdata["GoodName"]= "TimeField";
		$fdata["FullName"]= "`TimeField`";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=20";
						$tdata["TimeField"]=$fdata;
	
//	EndTime
	$fdata = array();
	 $fdata["Label"]="End Time"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "EndTime";
		$fdata["FullName"]= "`EndTime`";
	
	
	
	
	$fdata["Index"]= 7;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=10";
						$tdata["EndTime"]=$fdata;
	
//	DayEvent
	$fdata = array();
	 $fdata["Label"]="Whole Day Event"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "DayEvent";
		$fdata["FullName"]= "`DayEvent`";
	
	
	
	
	$fdata["Index"]= 8;
	
			$fdata["EditParams"]="";
							$tdata["DayEvent"]=$fdata;
	
//	EndDate
	$fdata = array();
	 $fdata["Label"]="End Date"; 
	
	
	$fdata["FieldType"]= 7;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		$fdata["GoodName"]= "EndDate";
		$fdata["FullName"]= "`EndDate`";
	
	
	
	
	$fdata["Index"]= 9;
	 $fdata["DateEditType"]=13; 
							$tdata["EndDate"]=$fdata;
	
//	Period
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "Period";
		$fdata["FullName"]= "`Period`";
	
	
	
	
	$fdata["Index"]= 10;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=255";
						$tdata["Period"]=$fdata;
	
//	Recurrence
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "Recurrence";
		$fdata["FullName"]= "`Recurrence`";
	
	
	
	
	$fdata["Index"]= 11;
	
			$fdata["EditParams"]="";
							$tdata["Recurrence"]=$fdata;
	
//	Category
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "Category";
		$fdata["FullName"]= "`Category`";
	
	
	
	
	$fdata["Index"]= 12;
	
							$tdata["Category"]=$fdata;
	
//	details
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "details";
		$fdata["FullName"]= "`details`";
	
	
	
	
	$fdata["Index"]= 13;
	
			$fdata["EditParams"]="";
							$tdata["details"]=$fdata;
	
//	income
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "income";
		$fdata["FullName"]= "`income`";
	
	
	
	
	$fdata["Index"]= 14;
	
			$fdata["EditParams"]="";
							$tdata["income"]=$fdata;
	
//	outcome
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "outcome";
		$fdata["FullName"]= "`outcome`";
	
	
	
	
	$fdata["Index"]= 15;
	
			$fdata["EditParams"]="";
							$tdata["outcome"]=$fdata;
$tables_data["yearly"]=$tdata;
?>