<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="customer";
	$tdata[".OwnerID"]="idusercus";

	
//	idcustomer
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "idcustomer";
	        	$fdata["FullName"]= "`customer`.`idcustomer`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["idcustomer"]=$fdata;
	
//	idusercus
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "idusercus";
	        	$fdata["FullName"]= "`customer`.`idusercus`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
							$tdata["idusercus"]=$fdata;
	
//	name
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "name";
	        	$fdata["FullName"]= "`customer`.`name`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
		$tdata["name"]=$fdata;
	
//	contact
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "contact";
	        	$fdata["FullName"]= "`customer`.`contact`";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=100";
					$fdata["FieldPermissions"]=true;
		$tdata["contact"]=$fdata;
	
//	telephones
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "telephones";
	        	$fdata["FullName"]= "`customer`.`telephones`";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=100";
					$fdata["FieldPermissions"]=true;
		$tdata["telephones"]=$fdata;
	
//	postinfo
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "postinfo";
	        	$fdata["FullName"]= "`customer`.`postinfo`";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=100";
					$fdata["FieldPermissions"]=true;
		$tdata["postinfo"]=$fdata;
	
//	customerdetails
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "HTML";
	
	
		$fdata["GoodName"]= "customerdetails";
	        	$fdata["FullName"]= "`customer`.`customerdetails`";
	
	 $fdata["UseRTE"]=true; 
	
	
	$fdata["Index"]= 7;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=600";
		$fdata["nCols"] = 600;
					$fdata["FieldPermissions"]=true;
		$tdata["customerdetails"]=$fdata;
	
//	username
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "username";
	        	$fdata["FullName"]= "`customer`.`username`";
	
	
	
	
	$fdata["Index"]= 8;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["username"]=$fdata;
	
//	password
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "password";
	        	$fdata["FullName"]= "`customer`.`password`";
	
	
	
	
	$fdata["Index"]= 9;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["password"]=$fdata;
	
//	email
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "email";
	        	$fdata["FullName"]= "`customer`.`email`";
	
	
	
	
	$fdata["Index"]= 10;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["email"]=$fdata;
$tables_data["customer"]=$tdata;
?>