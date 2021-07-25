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

if($objLogin->IsLoggedInAs('owner','mainadmin') && Modules::IsModuleInstalled('doctors')){	

	// Start main content
    draw_title_bar(prepare_breadcrumbs(array(_CLINIC_MANAGEMENT=>'',_INTEGRATION=>'')));	
	draw_message(_WIDGET_INTEGRATION_MESSAGE.'<br>'._INTEGRATION_MESSAGE);
	
	draw_content_start();
?>
	<table>
	<tr><td colspan="2"><b><?php echo _SIDE_PANEL; ?>:</b></td></tr>
	<tr>
		<td valign="top">
			<textarea cols="60" style="height:165px;margin-top:5px;" dir="ltr" onclick="this.select()" readonly="readonly"><?php
                $nl = "\n";
				echo '<script type="text/javascript">'.$nl;
				echo 'var maJsHost = "'.APPHP_BASE.'";'.$nl;
				echo 'var maJsKey = "'.INSTALLATION_KEY.'";'.$nl;
                echo 'var maJsWidth = "215";'.$nl;
                echo 'var maJsHeight = "220";'.$nl;
				echo 'document.write(unescape(\'%3Cscript src="\' + maJsHost + \'widgets/ipanel-left/main.js" type="text/javascript"%3E%3C/script%3E\'));'.$nl;
				echo '</script>'.$nl;
			?></textarea>
			<br>			
		</td>		
		<td>
			<img src="templates/admin/images/integration-side.png" alt="integration" />
		</td>		
	</tr>
	<tr><td colspan="2" nowrap height="10px"></td></tr>
	</table>
	<br><br>

<?php
	draw_content_end();

}else{
	draw_title_bar(_ADMIN);
	draw_important_message(_NOT_AUTHORIZED);
}

?>