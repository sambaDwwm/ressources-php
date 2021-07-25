<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="users";
	$tdata[".OwnerID"]="idus";

	
//	idus
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "idus";
	        	$fdata["FullName"]= "`users`.`idus`";
	
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["idus"]=$fdata;
	
//	username
	$fdata = array();
	 $fdata["Label"]="Username"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "username";
	        	$fdata["FullName"]= "`users`.`username`";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
		$tdata["username"]=$fdata;
	
//	password
	$fdata = array();
	 $fdata["Label"]="Password"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "password";
	        	$fdata["FullName"]= "`users`.`password`";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
		$tdata["password"]=$fdata;
	
//	group
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "group";
	        	$fdata["FullName"]= "`users`.`group`";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["group"]=$fdata;
	
//	email
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "email";
	        	$fdata["FullName"]= "`users`.`email`";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["email"]=$fdata;
	
//	info
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "info";
	        	$fdata["FullName"]= "`users`.`info`";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["info"]=$fdata;
$tables_data["users"]=$tdata;
?>