<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="howto";
	$tdata[".OwnerID"]="";

	
//	id
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "id";
	        	$fdata["FullName"]= "`howto`.`id`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
							$tdata["id"]=$fdata;
	
//	ask
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "ask";
	        	$fdata["FullName"]= "`howto`.`ask`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=100";
					$fdata["FieldPermissions"]=true;
		$tdata["ask"]=$fdata;
	
//	answer
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "HTML";
	
	
		$fdata["GoodName"]= "answer";
	        	$fdata["FullName"]= "`howto`.`answer`";
	 $fdata["IsRequired"]=true; 
	 $fdata["UseRTE"]=true; 
	
	
	$fdata["Index"]= 3;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=300";
		$fdata["nRows"] = 300;
			$fdata["EditParams"].= " cols=600";
		$fdata["nCols"] = 600;
					$fdata["FieldPermissions"]=true;
		$tdata["answer"]=$fdata;
$tables_data["howto"]=$tdata;
?>