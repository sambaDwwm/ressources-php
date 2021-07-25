<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="globals";
	$tdata[".OwnerID"]="";

	
//	id
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "id";
	        	$fdata["FullName"]= "`globals`.`id`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
							$tdata["id"]=$fdata;
	
//	TimePeriod
	$fdata = array();
	 $fdata["Label"]="Time Period"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Lookup wizard";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "TimePeriod";
	        	$fdata["FullName"]= "`globals`.`TimePeriod`";
	
	
	
	
	$fdata["Index"]= 2;
	
						$fdata["FieldPermissions"]=true;
		$tdata["TimePeriod"]=$fdata;
$tables_data["globals"]=$tdata;
?>