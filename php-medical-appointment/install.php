<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP Medical Appointment                                                  #
##  Developed by:  ApPHP <info@apphp.com>                                      #
##  License:       GNU LGPL v.3                                                #
##  Site:          https://www.apphp.com/php-medical-appointment/              #
##  Copyright:     ApPHP Medical Appointment (c) 2012. All rights reserved.    #
##  Last changed:  12.12.2013                                                  #
##                                                                             #
################################################################################

	require_once('install/settings.inc.php');    
	require_once('install/functions.inc.php');    
	
	if(file_exists(EI_CONFIG_FILE_DIRECTORY.EI_CONFIG_FILE_NAME)){        
		header('location: '.EI_APPLICATION_START_FILE);
		exit;
	}
	
    ob_start();
    
	if(function_exists('phpinfo')) @phpinfo(-1);
	$phpinfo = array('phpinfo' => array());
	if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
	foreach($matches as $match){
		$array_keys = array_keys($phpinfo);
		$end_array_keys = end($array_keys);
		if(strlen($match[1])){
			$phpinfo[$match[1]] = array();
		}else if(isset($match[3])){
			$phpinfo[$end_array_keys][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
		}else{
			$phpinfo[$end_array_keys][] = $match[2];
		}
	}

	$is_error = false;
	$error_mg = array();
	$is_warning = false;
	$warning_mg = array();
	if(EI_CHECK_PHP_MINIMAL_VERSION && (EI_PHP_MINIMAL_VERSION > phpversion())){
		$is_error = true;
		$error_mg[] = 'This program requires at least PHP version '.EI_PHP_MINIMAL_VERSION.' installed. You cannot proceed the installation.';	
	}
	if(EI_CHECK_CONFIG_DIR_WRITABILITY && !is_writable(EI_CONFIG_FILE_DIRECTORY)){
		$is_error = true;
		$error_mg[] = 'The directory <b>'.EI_CONFIG_FILE_DIRECTORY.'</b> is not writable! <br />You must grant access rights 0755 or 777 (depending on your system settings) to this directory before you start the installation!<br />';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>ApPHP Medical Appointment :: Installation Wizard</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="install/img/styles.css"></link>
	<style>
		.error { color:#bb2200; }
		.warning { color:#C79810; }
	</style>
</head>
<body text="#000000" vlink="#2971c1" alink="#2971c1" link="#2971c1" bgcolor="#ffffff">    
<table align="center" width="70%" cellspacing="0" cellpadding="2" border="0">
<tbody>
<tr><td>&nbsp;</td></tr>
<tr>
    <td class="text" valign="top">
        <h2>New Installation of <?php echo EI_APPLICATION_NAME;?> v<?php echo EI_APPLICATION_VERSION; ?>!</h2>		
        
        Follow the wizard to setup your database.<br /><br />
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
        <tr>
            <td class="gray_table">
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                <tr><td class="ltcorner"></td><td></td><td class="rtcorner"></td></tr>
                <tr>
                    <td></td>
                    <td align="middle">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                        <tr>
                            <td class="text" align="left">
								<b>Getting Important System Info</b>
                            </td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td class="text" align="left">
								<?php
									$php_core_index = ((version_compare(phpversion(), '5.3.0', '<'))) ? 'PHP Core' : 'Core';

									$system = isset($phpinfo['phpinfo']['System']) ? $phpinfo['phpinfo']['System'] : 'unknown';
									$database_system = isset($phpinfo['mysql']) ? $phpinfo['mysql']['MySQL Support'] : 'unknown';
									$database_system_version = isset($phpinfo['mysql']) ? $phpinfo['mysql']['Client API version'] : 'unknown';
									$build_date = isset($phpinfo['phpinfo']['Build Date']) ? $phpinfo['phpinfo']['Build Date'] : 'unknown';
									$server_api = isset($phpinfo['phpinfo']['Server API']) ? $phpinfo['phpinfo']['Server API'] : 'unknown';
									$vd_support = isset($phpinfo['phpinfo']['Virtual Directory Support']) ? $phpinfo['phpinfo']['Virtual Directory Support'] : 'unknown';

									$asp_tags 	= isset($phpinfo[$php_core_index]['asp_tags']) ? $phpinfo[$php_core_index]['asp_tags'][0] : 'unknown';
									$safe_mode 	= isset($phpinfo[$php_core_index]['safe_mode']) ? $phpinfo[$php_core_index]['safe_mode'][0] : 'unknown';
                                    // mode_rewrite
                                    if(function_exists('apache_get_modules')){
                                        // works only if PHP is not running as CGI module
                                        $mode_rewrite = (in_array('rewrite', get_loaded_extensions())) ? 'On' : 'Off';
                                    }else{
                                        // old - $mod_rewrite = getenv('HTTP_MOD_REWRITE') == 'On' ? true : false ;
                                        $mode_rewrite = (file_get_contents(get_base_url().'install/tests/test1.txt') == '2') ? 'On' : 'Off';
                                    }
                                    if($mode_rewrite == 'Off'){
                                        $is_warning = true;
                                        $warning_mg[] = 'Turned Off <b>Mode Rewrite</b> may cause problems while running the site with SEO links option. Please check curefully you have this option enabled.';	
                                    }
									$short_open_tag = isset($phpinfo[$php_core_index]) ? $phpinfo[$php_core_index]['short_open_tag'][0] : 'unknown';
									$magic_quotes_gpc = ini_get('magic_quotes_gpc') ? 'On' : 'Off';
									$magic_quotes_runtime = ini_get('magic_quotes_runtime') ? 'On' : 'Off';
									$magic_quotes_sybase = ini_get('magic_quotes_sybase') ? 'On' : 'Off';
									// session support
									if(isset($phpinfo['session']['Session Support']) && strtolower($phpinfo['session']['Session Support']) == "enabled"){
										$session_support = 'enabled';
									}else{
										$session_support = 'disabled';
										$is_warning = true;
										$warning_mg[] = 'Disabled <b>Session Support</b> may cause seriouse problems while running the site. It\'s strongly recommended to enable session support.';	
									}
									// mbstring support
									if(function_exists('mb_detect_encoding')){
										$mbstring_support = 'enabled';
									}else{
										$mbstring_support = 'disabled';
										$is_warning = true;
										$warning_mg[] = 'Disabled <b>mbString Support</b> may cause some problems when running multi-language site.';	
									}
								?>
                                <ul>
                                    <li>PHP Version: <b><i><?php echo phpversion(); ?></i></b></li>
									<li>Database System: <b><i>MySQL - <?php echo $database_system.' ('.$database_system_version.')'; ?></i></b></li>
									<li>System: <b><i><?php echo $system; ?></i></b></li>
								</ul>	
                                <ul>
									<li>Build Date: <b><i><?php echo $build_date; ?></i></b></li>
                                    <li>Server API: <b><i><?php echo $server_api; ?></i></b></li>
									<li>Virtual Directory Support: <b><i><?php echo $vd_support; ?></i></b></li>
									<li>Safe Mode: <b><i><?php echo $safe_mode; ?></i></b></li>
									<li>Mode Rewrite: <b><i><?php echo $mode_rewrite; ?></i></b></li>
								</ul>	
								<ul>
                                    <li>Asp Tags: <b><i><?php echo $asp_tags; ?></i></b></li>
									<li>Short Open Tag: <b><i><?php echo $short_open_tag; ?></i></b></li>
									<li>Session Support: <b><i><?php echo $session_support; ?></i></b></li>
									<li>mbString Support: <b><i><?php echo $mbstring_support; ?></i></b></li>
									<li>Magic Quotes GPC: <b><i><?php echo $magic_quotes_gpc; ?></i></b></li>
									<li>Magic Quotes RunTime: <b><i><?php echo $magic_quotes_runtime; ?></i></b></li>
									<li>Magic Quotes SyBase: <b><i><?php echo $magic_quotes_sybase; ?></i></b></li>
								</ul>
							</td>
                        </tr>
						<?php
							if($is_error && count($error_mg) > 0){
								echo '<tr><td nowrap height="10px"></td></tr>';
								echo '<tr><td class="text" align="left"><span class="error">Errors:</span></td></tr>';
								foreach($error_mg as $msg){
									echo '<tr><td class="text" align="left"><span class="error">&#8226; '.$msg.'</span></td></tr>';
								}
							}
							if($is_warning && count($warning_mg) > 0){
								echo '<tr><td nowrap height="10px"></td></tr>';
								echo '<tr><td class="text" align="left"><span class="warning">Warnings:</span></td></tr>';
								foreach($warning_mg as $msg){
									echo '<tr><td class="text" align="left"><span class="warning">&#8226; '.$msg.'</span></td></tr>';
								}
							}
						?>						
						</tbody>
                        </table>
						<br />						
						<?php if(!$is_error){ ?>
							<table width="100%" border="0" cellspacing="0" cellpadding="2" class="main_text">
							<tr>
								<td align="left" width="70px">
									<a href="install/step1.php"><img class="form_button" src="install/img/button_start.gif" name="submit" title="Click to start installation" alt="Start Installation" /></a>
								</td>
								<td class="text" align="left">
									Click on Start button to continue.
								</td>
							</table>						
						<?php } ?>
					</td>
                    <td></td>
                </tr>
				<tr><td class="lbcorner"></td><td></td><td class="rbcorner"></td></tr>
                </tbody>
                </table>
            </td>
        </tr>
        </tbody>
        </table>
        <?php include_once('install/footer.php'); ?>        
    </td>
</tr>
</tbody>
</table>                 
</body>
</html>