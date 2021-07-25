<?php

$tdata=array();
	 $tdata[".NumberOfChars"]=80; 
	$tdata[".ShortName"]="category";
	$tdata[".OwnerID"]="idusercat";

	
//	id
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "id";
	        	$fdata["FullName"]= "`category`.`id`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["id"]=$fdata;
	
//	idusercat
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "idusercat";
	        	$fdata["FullName"]= "`category`.`idusercat`";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
							$tdata["idusercat"]=$fdata;
	
//	Category
	$fdata = array();
	 $fdata["Label"]="task"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "Category";
	        	$fdata["FullName"]= "`category`.`Category`";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
		$tdata["Category"]=$fdata;
	
//	price
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "price";
	        	$fdata["FullName"]= "`category`.`price`";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
		$tdata["price"]=$fdata;
	
//	taskdetails
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "HTML";
	
	
		$fdata["GoodName"]= "taskdetails";
	        	$fdata["FullName"]= "`category`.`taskdetails`";
	
	 $fdata["UseRTE"]=true; 
	
	
	$fdata["Index"]= 5;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=600";
		$fdata["nCols"] = 600;
					$fdata["FieldPermissions"]=true;
		$tdata["taskdetails"]=$fdata;
	
//	picture
	$fdata = array();
	
	
	 $fdata["LinkPrefix"]="files/"; 
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Document upload";
	$fdata["ViewFormat"]= "File-based Image";
	
	
		$fdata["GoodName"]= "picture";
	        	$fdata["FullName"]= "`category`.`picture`";
	
	
	
	 $fdata["UploadFolder"]="files"; 
	$fdata["Index"]= 6;
	
						$fdata["FieldPermissions"]=true;
		$fdata["CreateThumbnail"]=true;
	$fdata["ThumbnailPrefix"]="th";
	$tdata["picture"]=$fdata;
	
//	Color
	$fdata = array();
	
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		$fdata["GoodName"]= "Color";
	        	$fdata["FullName"]= "`category`.`Color`";
	
	
	
	
	$fdata["Index"]= 7;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=255";
					$fdata["FieldPermissions"]=true;
		$tdata["Color"]=$fdata;
$tables_data["category"]=$tdata;
?>