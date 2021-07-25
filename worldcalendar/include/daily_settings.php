<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="daily";
	$tdata[".OwnerID"]="idusercal";

	
//	id
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "id";
		$fdata["FullName"]= "`calendar`.`id`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
							$tdata["id"]=$fdata;
	
//	idusercal
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "idusercal";
		$fdata["FullName"]= "`idusercal`";
	
	
	
	
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
						$fdata["FieldPermissions"]=true;
		$tdata["DateField"]=$fdata;
	
//	DateField2
	$fdata = array();
	 $fdata["Label"]="Date"; 
	
	
	$fdata["FieldType"]= 7;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		$fdata["GoodName"]= "DateField2";
		$fdata["FullName"]= "`DateField`";
	
	
	
	
	$fdata["Index"]= 4;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
		$tdata["DateField2"]=$fdata;
	
//	TimeField
	$fdata = array();
	 $fdata["Label"]="Time"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "Time";
	
	
		$fdata["GoodName"]= "TimeField";
		$fdata["FullName"]= "`TimeField`";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=20";
					$fdata["FieldPermissions"]=true;
		$tdata["TimeField"]=$fdata;
	
//	Theme
	$fdata = array();
	 $fdata["Label"]="Subject"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "Custom";
	
	
		$fdata["GoodName"]= "Theme";
		$fdata["FullName"]= "`Theme`";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=200";
			$fdata["EditParams"].= " size=60";
				$fdata["FieldPermissions"]=true;
		$tdata["Theme"]=$fdata;
	
//	Description
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "Description";
		$fdata["FullName"]= "`calendar`.`Description`";
	
	
	
	
	$fdata["Index"]= 7;
	
						$fdata["FieldPermissions"]=true;
		$tdata["Description"]=$fdata;
	
//	Category
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "Category";
		$fdata["FullName"]= "`calendar`.`Category`";
	
	
	
	
	$fdata["Index"]= 8;
	
						$fdata["FieldPermissions"]=true;
		$tdata["Category"]=$fdata;
	
//	EndTime
	$fdata = array();
	 $fdata["Label"]="End Time"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "EndTime";
		$fdata["FullName"]= "`EndTime`";
	
	
	
	
	$fdata["Index"]= 9;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=10";
					$fdata["FieldPermissions"]=true;
		$tdata["EndTime"]=$fdata;
	
//	DayEvent
	$fdata = array();
	 $fdata["Label"]="Whole Day Event"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Checkbox";
	$fdata["ViewFormat"]= "Checkbox";
	
	
		$fdata["GoodName"]= "DayEvent";
		$fdata["FullName"]= "`DayEvent`";
	
	
	
	
	$fdata["Index"]= 10;
	
						$fdata["FieldPermissions"]=true;
		$tdata["DayEvent"]=$fdata;
	
//	EndDate
	$fdata = array();
	 $fdata["Label"]="End Date"; 
	
	
	$fdata["FieldType"]= 7;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		$fdata["GoodName"]= "EndDate";
		$fdata["FullName"]= "`EndDate`";
	
	
	
	
	$fdata["Index"]= 11;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
		$tdata["EndDate"]=$fdata;
	
//	Period
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "Period";
		$fdata["FullName"]= "`Period`";
	
	
	
	
	$fdata["Index"]= 12;
	
						$fdata["FieldPermissions"]=true;
		$tdata["Period"]=$fdata;
	
//	Recurrence
	$fdata = array();
	 $fdata["Label"]="Recurrent Event"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Checkbox";
	$fdata["ViewFormat"]= "Checkbox";
	
	
		$fdata["GoodName"]= "Recurrence";
		$fdata["FullName"]= "`Recurrence`";
	
	
	
	
	$fdata["Index"]= 13;
	
						$fdata["FieldPermissions"]=true;
		$tdata["Recurrence"]=$fdata;
	
//	details
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "HTML";
	
	
		$fdata["GoodName"]= "details";
		$fdata["FullName"]= "`details`";
	
	 $fdata["UseRTE"]=true; 
	
	
	$fdata["Index"]= 14;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=300";
		$fdata["nRows"] = 300;
			$fdata["EditParams"].= " cols=600";
		$fdata["nCols"] = 600;
					$fdata["FieldPermissions"]=true;
		$tdata["details"]=$fdata;
	
//	income
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "income";
		$fdata["FullName"]= "`income`";
	
	
	
	
	$fdata["Index"]= 15;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["income"]=$fdata;
	
//	outcome
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "outcome";
		$fdata["FullName"]= "`outcome`";
	
	
	
	
	$fdata["Index"]= 16;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["outcome"]=$fdata;
	
//	Color
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "Color";
		$fdata["FullName"]= "`category`.`Color`";
	
	
	
	
	$fdata["Index"]= 17;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=255";
					$fdata["FieldPermissions"]=true;
		$tdata["Color"]=$fdata;
$tables_data["daily"]=$tdata;
?>