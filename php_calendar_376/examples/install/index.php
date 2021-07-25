<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP Calendar                                                  		   #
##  Developed by:  ApPhp <info@apphp.com>                                      #
##  License:       GNU LGPL v.3                                                #
##  Site:          https://www.apphp.com/php-calendar/                         #
##  Copyright:     ApPHP Calendar (c) 2009-2012. All rights reserved.          #
##                                                                             #
################################################################################

    require_once('settings.inc.php');    
       
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
	if(EI_CHECK_PHP_MINIMAL_VERSION && (EI_PHP_MINIMAL_VERSION > phpversion())){
		$is_error = true;
		$error_mg[] = 'This program requires at least PHP version '.EI_PHP_MINIMAL_VERSION.' installed. You cannot proceed the installation.';	
	}
	if(EI_CHECK_CONFIG_DIR_WRITABILITY && !is_writable(EI_CONFIG_FILE_DIRECTORY)){
		$is_error = true;
		$error_mg[] = 'You must have write access (0755 or 777 - depending on your system settings) to <b>'.EI_CONFIG_FILE_DIRECTORY.'</b> folder before you start installation!<br />';
	}	
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Installation Guide</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="img/styles.css"></link>
	<script type="text/javascript">
	function toggleInstructions(key){
		if(key == "1"){
			document.getElementById("tblWizard").style.display = "";
			document.getElementById("tblManually").style.display = "none";
		}else{
			document.getElementById("tblWizard").style.display = "none";
			document.getElementById("tblManually").style.display = "";		
		}
	}
	</script>
</head>
<body>

<a id="top" name="top"></a>
<table align="center" width="70%" cellspacing="0" cellpadding="2" border="0">
<tbody>
<tr><td>&nbsp;</td></tr>
<tr>
	<td class="text" valign="top">
		<h2>New Installation of <?php echo EI_APPLICATION_NAME;?>!</h2>
		
		<table width="100%" cellspacing="5" cellpadding="0" border="0">
		<tbody>
		<tr>
			<td class="text" nowrap="nowrap">
				<input type="radio" checked name="install_type" id="install_type_wizard" onclick="toggleInstructions(1)" /><label for="install_type_wizard">Follow the <b>Wizard</b> to Setup Your Database</label>
				&nbsp;&nbsp;
				<input type="radio" name="install_type" id="install_type_manual" onclick="toggleInstructions(2)" /><label for="install_type_manual">Perform a <b>Manual</b> Installation</label>
			</td>
		</tr>
		<tr>
			<td class="gray_table">
				<table id="tblWizard" width="100%" cellspacing="0" cellpadding="0" border="0">
				<tbody>
				<tr><td class="ltcorner"></td><td></td><td class="rtcorner"></td></tr>
				<tr>
					<td></td>
					<td align=middle>						
						<table width="100%" cellspacing="0" cellpadding="0" border="0">
						<tbody>
						<tr>
							<td class="text" align="left">
								<b>Getting System Info</b>
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
									$short_open_tag = isset($phpinfo[$php_core_index]['short_open_tag']) ? $phpinfo[$php_core_index]['short_open_tag'][0] : 'unknown';

									$session_support = isset($phpinfo['session']['Session Support']) ? $phpinfo['session']['Session Support'] : 'unknown';
									$magic_quotes_gpc = ini_get('magic_quotes_gpc') ? 'On' : 'Off';
									$magic_quotes_runtime = ini_get('magic_quotes_runtime') ? 'On' : 'Off';
									$magic_quotes_sybase = ini_get('magic_quotes_sybase') ? 'On' : 'Off';
								?>
                                <ul>
                                    <li>PHP Version: <b><i><?php echo phpversion(); ?></i></b></li>
									<li>Database System: <b><i>MySQL - <?php echo $database_system.' ('.$database_system_version.')'; ?></i></b></li>
									<li>Server OS: <b><i><?php echo $system; ?></i></b></li>									
								</ul>	
                                <ul>
									<li>Build Date: <b><i><?php echo $build_date; ?></i></b></li>
                                    <li>Server API: <b><i><?php echo $server_api; ?></i></b></li>
									<li>Virtual Directory Support: <b><i><?php echo $vd_support; ?></i></b></li>
									<li>Safe Mode: <b><i><?php echo $safe_mode; ?></i></b></li>
								</ul>	
								<ul>
                                    <li>Asp Tags: <b><i><?php echo $asp_tags; ?></i></b></li>
									<li>Short Open Tag: <b><i><?php echo $short_open_tag; ?></i></b></li>
									<li>Session Support: <b><i><?php echo $session_support; ?></i></b></li>
									<li>Magic Quotes GPC: <b><i><?php echo $magic_quotes_gpc; ?></i></b></li>
									<li>Magic Quotes RunTime: <b><i><?php echo $magic_quotes_runtime; ?></i></b></li>
									<li>Magic Quotes SyBase: <b><i><?php echo $magic_quotes_sybase; ?></i></b></li>
								</ul>
							</td>
						</tr>
						<tr><td>&nbsp;</td></tr>
						<?php if(!$is_error){ ?>
							<tr>
								<td class="text" align="left">
									Click on Start button to continue.
								</td>
							</tr>
						<?php }else{ ?>
							<?php
								if($is_error){
									foreach($error_mg as $msg){
										echo '<tr><td class="text" align="left"><span style="color:#bb5500;">&#8226; '.$msg.'</span></td></tr>';
									}								
								}
							?>						
						<?php } ?>
						</tbody>
						</table>
						<br />						
						<?php if(!$is_error){ ?>
							<table width="100%" border="0" cellspacing="0" cellpadding="2" class="main_text">
							<tr>
								<td colspan="2" align="left">
									<a href="step1.php"><img class="form_button" src="img/button_start.gif" name="submit" title="Click to start installation" alt="" /></a>
								</td>
							</table>						
						<?php } ?>
					</td>
					<td></td>						
				</tr>
				<tr><td class="lbcorner"></td><td></td><td class="rbcorner"></td></tr>
				</tbody>
				</table>
				

				<table id="tblManually" style="display:none;" width="100%" cellspacing="0" cellpadding="0" border="0">
				<tbody>
				<tr><td class="ltcorner"></td><td></td><td class="rtcorner"></td></tr>
				<tr>
					<td class="text" align="left" style="width:100%; padding:10px 20px 10px 20px; font-size:12px;">
						<h2 id="post-1">Installation of ApPHP Calendar (version 3.0.0 or above)</h2>  
						<table style="font-size:12px;">
						<tbody>
						<tr>
							<td>
								<ul>
									<li>
									<a href="index.php#Installation_of_ApPHP_Calendar"><span>1.</span> <span>Installation of ApPHP Calendar</span></a> 
									<UL>
										<li class=toclevel-2><a href="index.php#Step_1._Uncompressing_downloaded_file."><span>1.1</span> <span>Step 1. Uncompressing downloaded file.</span></a> 
										<li class=toclevel-2><a href="index.php#Step_2._Uploading_files."><span>1.2</span> <span>Step 2. Uploading files.</span></a> 
										<li class=toclevel-2><a href="index.php#Step_3._Creating_database."><span>1.3</span> <span>Step 3. Creating database.</span></a>                        
										<li class=toclevel-2><a href="index.php#Step_4._Settings_and_access_rights."><span>1.4</span> <span>Step 4. Settings and access rights.</span></a>                        
										<li class=toclevel-2><a href="index.php#Step_5._Running_example_file."><span>1.5</span> <span>Step 5. Running code_example.php file.</span></a>
									</UL>
									</LI>
								</ul>
							</td>
						</tr>
						</tbody>
						</table>
				
				
						<a name=Installation_of_ApPHP_Calendar></a>
						<p>(for version 3.0.0 or above) </p>
						<p>A new installation of ApPHP Calendar is a very straight forward process: </p>
						
						<div class=editsection style="FLOAT: right; MARGIN-LEFT: 5px">[<a href="#top">top</a>]</div>
						<a name="Step_1._Uncompressing_downloaded_file."></a>
						<h3><b>Step 1. Uncompressing downloaded file.</b></h3>
						<hr>
						<p>Uncompress the ApPHP Calendar version 3.x.x script archive. The archive will create a directory called "PHPCAL_3xx".</p>
						<p><br /></p>
						
						<div class=editsection style="FLOAT: right; MARGIN-LEFT: 5px">[<a href="#top">top</a>]</div>
						<a name="Step_2._Uploading_files."></a>
						<h3><b>Step 2. Uploading files.</b></h3>
						<hr>
						<p>Upload content of sub-directory <b>calendar/</b> (all files and sub-directories it includes) to your 
						document root (public_html, www, httpdocs etc.) or your calendar directory using FTP.</p>
						<b>Pay attention:</b> DON'T use the capital letters in the name of the folder (for Linux users).
						<p>For example: </p>
						<PRE>public_html/</PRE>
						or
						<PRE>public_html/{calendar directory}/</PRE>
						<p><br /></p>        
						
						<div class=editsection style="FLOAT: right; MARGIN-LEFT: 5px">[<a href="#top">top</a>]</div>
						<a name="Step_3._Creating_database."></a>
						<h3><b>Step 3. Creating database.</b></h3>
						<hr>
						<p>3.1. Using your hosting Control Panel, phpMyAdmin or another tool, create your database and
						user, and assign that user to the database. Grant SELECT, INSERT, DELETE, and UPDATE privileges to this user.
						Write down hosting, name of the database, username, and password for the Script Installation Procedure. </p>
						
						<p>3.2. Create all appropriate database tables and stored procedures using <b>examples/install/sql_dump/installation_new.sql</b> and
						<b>examples/install/sql_dump/stored_procedures.sql</b> files (you have to import them). Before importing, change
						"&lt;DB_PREFIX&gt;" table prefix holders on any prefix you want (for example, "<b>CAL_</b>").</p>
						
						<p>3.3. Create <b>config.inc.php</b> file in <b>calendar/inc/</b> folder (take example from examples/install/config.tpl) and change database host,
						database name, username and user password with appropriate values, saved on step 3.1. Change in <b>define('DB_PREFIX', '');</b>
						the the value with the prefix you have selected before for importing sql dump file. For example: <b>define('DB_PREFIX', 'CAL_');</b>
<pre>
define('DATABASE_HOST', 'host');
define('DATABASE_NAME', 'database name'); 
define('DATABASE_USERNAME', 'user name');
define('DATABASE_PASSWORD', 'user password');

define('DB_PREFIX', 'CAL_');
define('INSTALLATION_KEY', 'your_key'); // Unique key for installation
define('DB_CONNECTION_MODE', 'production'); // 'debug'|'production'
</pre>
						</p>        
						<p><br /></p>
						
						<div class=editsection style="FLOAT: right; MARGIN-LEFT: 5px">[<a href="#top">top</a>]</div>
						<a name="Step_4._Settings_and_access_rights."></a>
						<h3><b>Step 4. Settings and access rights.</b></h3>
						<hr>
						<p>
							4.1. Set permissions: e.g chmod 755 for <b>calendar/inc/</b> and <b>calendar/tmp/</b> folders.  <br /><br />							
							4.2. If you run ApPHP Calendar under IIS, you have to allow rewrite mode for Windows.<br /><br />
							- To do this, open the <b>httpd.conf</b> file and uncomment the following lines (remove the trailing #s):<br />							
							<PRE>#LoadModule rewrite_module modules/mod_rewrite.so<br />#AddModule mod_rewrite.c</PRE>
							- Another way is simply to "share" your <b>calendar/inc/</b> and <b>calendar/tmp/</b> folders.							
						</p>
						<p><br /></p>
				
						<div class=editsection style="FLOAT: right; MARGIN-LEFT: 5px">[<a href="#top">top</a>]</div>
						<a name="Step_5._Running_example_file."></a>
						<h3><b>Step 5. Running code_example.php file.</b></h3>
						<hr>
						<p>Now you can run the example file. To do this, open a browser and type in Address Bar
						<pre>http://localhost/{calendar directory}/code_example.php</pre>
						or 
						<pre>http://{www.example.com}/{calendar directory}/code_example.php</pre>
						
						</p>
						<p><br /></p>
				
						
						Congratulations, you now have ApPHP Calendar v3.x.x. Installed!					
					</td>
				</tr>
				<tr><td class="lbcorner"></td><td></td><td class="rbcorner"></td></tr>
				</tbody>
				</table>

			</td>
		</tr>
		</tbody>
		</table>

		<?php include_once('footer.php'); ?>        
	</td>
</tr>
</tbody>
</table>

</body>
</html>