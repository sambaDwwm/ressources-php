<?php
/**
* @project ApPHP Medical Appointment
* @copyright (c) 2012 - 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

// *** Make sure the file isn't accessed directly
defined('APPHP_EXEC') or die('Restricted Access');
//--------------------------------------------------------------------------

if($objLogin->IsLoggedInAs('owner','mainadmin')){
	
	$action 	= MicroGrid::GetParameter('action');
	$rid    	= MicroGrid::GetParameter('rid');
	$email  	= MicroGrid::GetParameter('email', false);
	$mode       = 'view';
	$msg 	    = '';
	
	$objDoctors = new Doctors();

	if($action=='add'){		
		$mode = 'add';
	}else if($action=='create'){
		if($objDoctors->AddRecord()){
			$msg = draw_success_message(_ADDING_OPERATION_COMPLETED, false);
			$mode = 'view';
		}else{
			$msg = draw_important_message($objDoctors->error, false);
			$mode = 'add';
		}
	}else if($action=='edit'){
		$mode = 'edit';
	}else if($action=='update'){
		if($objDoctors->UpdateRecord($rid)){
			$msg = draw_success_message(_UPDATING_OPERATION_COMPLETED, false);
			$mode = 'view';
		}else{
			$msg = draw_important_message($objDoctors->error, false);
			$mode = 'edit';
		}		
	}else if($action=='delete'){
		if($objDoctors->DeleteRecord($rid)){
			$msg = draw_success_message(_DELETING_OPERATION_COMPLETED, false);
		}else{
			$msg = draw_important_message($objDoctors->error, false);
		}
		$mode = 'view';
	}else if($action=='details'){		
		$mode = 'details';		
	}else if($action=='cancel_add'){		
		$mode = 'view';		
	}else if($action=='cancel_edit'){				
		$mode = 'view';
	}else if($action=='reactivate'){
		if(Doctors::Reactivate($email)){
			$msg = draw_success_message(_EMAIL_SUCCESSFULLY_SENT, false);
		}else{
			$msg = draw_important_message(Doctors::GetStaticError(), false);
		}		
		$mode = 'view';
    }else{
        $action = '';
	}
	
	// Start main content		
	draw_title_bar(prepare_breadcrumbs(array(_ACCOUNTS=>'',_DOCTORS_MANAGEMENT=>'',_DOCTORS=>'',ucfirst($action)=>'')));
	
	//if($objSession->IsMessage('notice')) echo $objSession->GetMessage('notice');
	echo $msg;

	draw_content_start();	
	if($mode == 'view'){		
		$objDoctors->DrawViewMode();	
	}else if($mode == 'add'){		
		$objDoctors->DrawAddMode();		
	}else if($mode == 'edit'){		
		$objDoctors->DrawEditMode($rid);		
	}else if($mode == 'details'){		
		$objDoctors->DrawDetailsMode($rid);		
	}
	draw_content_end();

}else{
	draw_title_bar(_ADMIN);
	draw_important_message(_NOT_AUTHORIZED);
}
?>