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
	$doc_id     = MicroGrid::GetParameter('doc_id', false);
	$mode       = 'view';
	$msg 	    = '';
	
	$objTimeoffs = new Timeoffs($doc_id);

	if($action=='add'){		
		$mode = 'add';
	}else if($action=='create'){
		if($objTimeoffs->AddRecord()){
			$msg = draw_success_message(_ADDING_OPERATION_COMPLETED, false);
			$mode = 'view';
		}else{
			$msg = draw_important_message($objTimeoffs->error, false);
			$mode = 'add';
		}
	}else if($action=='edit'){
		$mode = 'edit';
	}else if($action=='update'){
		if($objTimeoffs->UpdateRecord($rid)){
			$msg = draw_success_message(_UPDATING_OPERATION_COMPLETED, false);
			$mode = 'view';
		}else{
			$msg = draw_important_message($objTimeoffs->error, false);
			$mode = 'edit';
		}		
	}else if($action=='delete'){
		if($objTimeoffs->DeleteRecord($rid)){
			$msg = draw_success_message(_DELETING_OPERATION_COMPLETED, false);
		}else{
			$msg = draw_important_message($objTimeoffs->error, false);
		}
		$mode = 'view';
	}else if($action=='details'){		
		$mode = 'details';		
	}else if($action=='cancel_add'){		
		$mode = 'view';		
	}else if($action=='cancel_edit'){				
		$mode = 'view';
    }else{
        $action = '';
    }
	
	// Start main content		
	draw_title_bar(
		prepare_breadcrumbs(array(_CLINIC_MANAGEMENT=>'',_TIMEOFF=>'',ucfirst($action)=>'')),
		((!empty($doc_id)) ? prepare_permanent_link('index.php?admin=doctors_management', _BUTTON_BACK) : '')
	);
	
	//if($objSession->IsMessage('notice')) echo $objSession->GetMessage('notice');
	echo $msg;

	draw_content_start();	
	if($mode == 'view'){		
		$objTimeoffs->DrawViewMode();	
	}else if($mode == 'add'){		
		$objTimeoffs->DrawAddMode();		
	}else if($mode == 'edit'){		
		$objTimeoffs->DrawEditMode($rid);		
	}else if($mode == 'details'){		
		$objTimeoffs->DrawDetailsMode($rid);		
	}
	draw_content_end();

}else{
	draw_title_bar(_ADMIN);
	draw_important_message(_NOT_AUTHORIZED);
}
?>