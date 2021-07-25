<?php

// *** Make sure the file isn't accessed directly
defined('APPHP_EXEC') or die('Restricted Access');
//--------------------------------------------------------------------------

$clinic_info = Clinic::GetClinicInfo();

?>

<!-- header -->
<div id="header" class="no_print">
	<div class="site_name" <?php echo 'f'.Application::Get('defined_left'); ?>>
		<a href="<?php echo APPHP_BASE; ?>index.php"><?php echo ($objLogin->IsLoggedInAsAdmin()) ? _ADMIN_PANEL : $objSiteDescription->DrawHeader('header_text'); ?></a>
		<br />
		<strong>
		<?php
			if($objLogin->IsLoggedInAsAdmin() && Application::Get('preview') == 'yes'){
				echo '<a class="header" href="index.php?preview=no">'._BACK_TO_ADMIN_PANEL.'</a>';						
			}else{
				echo $objSiteDescription->GetParameter('slogan_text');				
			}
		?>		
		</strong>
	</div>
	<?php			
		Search::DrawQuickSearch();
	?>	
	<div class="phones <?php echo 'f'.Application::Get('defined_right'); ?>">
		<?php echo (isset($clinic_info['phone']) ? $clinic_info['phone'] : ''); ?><br />
		<?php echo (isset($clinic_info['fax']) ? $clinic_info['fax'] : ''); ?>
	</div>
</div>
