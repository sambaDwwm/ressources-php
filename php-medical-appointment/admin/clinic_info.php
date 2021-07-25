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
	
if($objLogin->IsLoggedInAsAdmin()){
	
	$grid = 'first';
	if(isset($_POST['name']) && isset($_POST['address']) && isset($_POST['description'])){
		$grid = 'second';
	}

	$prefix 	= MicroGrid::GetParameter('prefix');
	$action 	= MicroGrid::GetParameter('action');
	
	////////////////////////////////////////////////////////////////////////////
	// CLINIC INFO
	////////////////////////////////////////////////////////////////////////////
	$rid      = '1'; //MicroGrid::GetParameter('rid');
	$mode     = 'edit';
	$msg 	  = '';
	$action_h = '';

	$objClinic = new Clinic();

    if($prefix == 'cln_'){
		$action_h = $action;
		if($action == '' || $action=='edit'){
			$mode = 'edit';
		}else if($action=='update'){
			if($objClinic->UpdateRecord($rid)){
				$msg = draw_success_message(_UPDATING_OPERATION_COMPLETED, false);
				$mode = 'edit';
			}else{
				$msg = draw_important_message($objClinic->error, false);
				$mode = 'edit';
			}		
		}else if($action=='cancel_edit'){				
			$mode = 'edit';
        }else{
            $action_h = '';
        }
	}

	// Start main content
	draw_title_bar(prepare_breadcrumbs(array(_CLINIC_MANAGEMENT=>'',_CLINIC_INFO=>'',ucfirst($action_h)=>'')));	
	
	//if($objSession->IsMessage('notice')) echo $objSession->GetMessage('notice');
	echo $msg;

	draw_content_start();	
	$objClinic->DrawEditMode($rid, '', '', array('reset'=>true, 'cancel'=>false));		
	draw_content_end();
	

	////////////////////////////////////////////////////////////////////////////
	// CLINIC DESCRIPTION
	////////////////////////////////////////////////////////////////////////////
	$objClinicDescr = new ClinicDescription();

	$mode = 'view';
	$rid  = $objClinicDescr->GetParameter('rid');
    $msg  = '';
	$action_hd = '';
	
	if($prefix == ''){
		$action_hd = $action;
		if($action=='add'){		
			$mode = 'add';
		}else if($action=='edit'){
			$mode = 'edit';
		}else if($action=='update'){
			if($objClinicDescr->UpdateRecord($rid)){
				$msg = draw_success_message(_UPDATING_OPERATION_COMPLETED, false);
				$mode = 'view';
			}else{
				$msg = draw_important_message($objClinicDescr->error, false);
				$mode = 'edit';
			}		
		}else if($action=='details'){		
			$mode = 'details';		
		}else if($action=='cancel_add'){		
			$mode = 'view';		
		}else if($action=='cancel_edit'){				
			$mode = 'view';
        }else{
            $action_hd = '';
        }
	}	
	
	// Start main content
	draw_title_bar(prepare_breadcrumbs(array(_CLINIC_MANAGEMENT=>'',_CLINIC_DESCRIPTION=>'',ucfirst($action_hd)=>'')));
    	
	//if($objSession->IsMessage('notice')) echo $objSession->GetMessage('notice');
	echo $msg;

	draw_content_start();	
	if($mode == 'view'){		
		$objClinicDescr->DrawViewMode();	
	}else if($mode == 'add'){		
		$objClinicDescr->DrawAddMode();		
	}else if($mode == 'edit'){		
		$objClinicDescr->DrawEditMode($rid);		
	}else if($mode == 'details'){		
		$objClinicDescr->DrawDetailsMode($rid);		
	}
	draw_content_end();	

}else{
	draw_title_bar(_ADMIN);
	draw_important_message(_NOT_AUTHORIZED);
}

?>