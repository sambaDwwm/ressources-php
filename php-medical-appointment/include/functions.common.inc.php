<?php
/**
* @project ApPHP Medical Appointment
* @copyright (c) 2012 - 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

// COMMON FUNCTIONS 
// Updated: 23.10.2011

/**
 * 	Returns time difference
 * 		@param first_time
 * 		@param last_time
 **/
function time_diff($last_time, $first_time)
{
	// convert to unix timestamps
	$time_diff=strtotime($last_time)-strtotime($first_time);	
	return $time_diff;
}

/**
 *	Highlight rows
 *		@param $ind
 *  	@param $offset
 */
function highlight($ind, $offset = 1)
{
	if(($ind + $offset) % 2  == 0) $highlight = ' class="highlight_light"';
	else $highlight = ' class="highlight_dark"';
	return $highlight;
}

/**
 *  Get random string
 *  	@param $length
 */
function get_random_string($length = 20)
{
	$template = '1234567890abcdefghijklmnopqrstuvwxyz';
	settype($template, 'string');
	settype($length, 'integer');
	settype($rndstring, 'string');
	settype($a, 'integer');
	settype($b, 'integer');           
	for ($a = 0; $a < $length; $a++) {
		$b = rand(0, strlen($template) - 1);
		$rndstring .= $template[$b];
	}       
	return $rndstring;       
}

/**
 *  Camel Case
 *  	@param $string
 **/
function camel_case($string)
{
	if(function_exists('mb_convert_case')){
		return mb_convert_case($string, MB_CASE_TITLE, mb_detect_encoding($string));				
	}else{
		return $string;				
	}	
}

/**
 *  Create SEO url from string
 *  	@param $string
 **/
function create_seo_url($string = '')
{
	$forbidden_symbols = array('\\', '"', "'", '(', ')', '[', ']', '*', '.', ',', '&', ';', ':', '&amp;', '?', '!', '=');

	$string = str_replace($forbidden_symbols, '', $string);
	$splitted_string = explode(' ', $string);
	$seo_url = '';
	$words_counter = 0;
	foreach($splitted_string as $key){
		if(trim($key) != ''){
			if($words_counter++ < 6){
				$seo_url .= ($seo_url != '') ? '-'.$key : $key;   
			}else{
				break;   
			}               
		}           
	}
	return substr($seo_url, 0, 125);
}

/**
 *  Get base URL 
 */
function get_base_url()
{
	$protocol = 'http://';
	$port = '';
	$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	$serverProtocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : '';
	if((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) || strtolower(substr($serverProtocol, 0, 5)) == 'https'){
		$protocol = 'https://';
	}			
	if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80'){
        if(!strpos($http_host, ':')){
			$port = ':'.$_SERVER['SERVER_PORT'];
		}
	}	
	$folder = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')+1);	
	return $protocol.$http_host.$port.$folder;
}

/**
 *  Get page URL
 *  	@param $urlencode
 */
function get_page_url($urlencode = true)
{
	$protocol = 'http://';
	$port = '';
	$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	$serverProtocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : '';
	if((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) || strtolower(substr($serverProtocol, 0, 5)) == 'https'){
		$protocol = 'https://';
	}			
	if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80'){
        if(!strpos($http_host, ':')){
			$port = ':'.$_SERVER['SERVER_PORT'];
		}
	}		
	// fixed for work with both Apache and IIS
	if(!isset($_SERVER['REQUEST_URI'])){	
		$uri = substr(prepare_input($_SERVER['PHP_SELF'], false, 'extra'),0);
		if(isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '') {
			$uri .= '?'.prepare_input($_SERVER['QUERY_STRING'], false, 'extra');
		}
	}else{
		$uri = prepare_input($_SERVER['REQUEST_URI'], false, 'extra');
		if(preg_match("/:|;|'|\(|\)/", $uri)) $uri = '';
	}	
	if(isset($_GET['p'])){
		$uri = remove_url_param($uri, 'p');
	}
	if($urlencode) $uri = str_replace('&', '&amp;', $uri);
	return $protocol.$http_host.$port.$uri;
}

/**
 *  Removes parameter from url
 *      @param $url
 *      @param $param
 */
function remove_url_param($url, $param)
{
    $url_parse = parse_url($url);
    parse_str($url_parse['query'], $uri_parts);
    unset($uri_parts['p']);
    return $url_parse['path'].'?'.http_build_query($uri_parts);   
}

/**
 *  Read subfolders of directory
 *      @param $dir
 */
function read_directory_subfolders($dir = '.')
{
	$folder = dir($dir); 
	$arrFolderEntries = array();
	while($folderEntry=$folder->read()){
		if($folderEntry != '.' && $folderEntry != '..' && is_dir($dir.$folderEntry) && strtolower($folderEntry) != 'admin') 
			$arrFolderEntries[] = $folderEntry; 
	}     
	$folder->close(); 
	return $arrFolderEntries;
}

/**
 *  Cut string by last word
 **/
function substr_by_word($text, $length = '0', $three_dots = false, $lang = 'en')
{
	$output = substr($text, 0, (int)$length);
	if(strlen($text) > $length){
		$blank_pos = strrpos($output, ' ');		
        if($lang == 'en'){
            if($blank_pos > 0) $output = substr($output, 0, $blank_pos);		
        }else{
			if($blank_pos > 0) $output = mb_substr($text, 0, $length, 'UTF-8');
        }
		if($three_dots) $output .= '...';
	}
	return $output;
}

/**
 *  Get current IP
 **/
function get_current_ip()
{
	if (isset($_SERVER['HTTP_X_FORWARD_FOR']) && $_SERVER['HTTP_X_FORWARD_FOR']) { 
		$user_ip = $_SERVER['HTTP_X_FORWARD_FOR'];
	} else {
		$user_ip = $_SERVER['REMOTE_ADDR'];
	}
	return $user_ip;
}

/**
 *  Format datetime
 *  	@param $datetime
 *  	@param $format
 *  	@param $empty_text
 *  	@param $locale
 */
function format_datetime($datetime, $format = '', $empty_text = '', $locale = false)
{	
	$format = ($format == '') ? get_datetime_format() : $format;
	
	$datetime_check = preg_replace('/0|-| |:/', '', $datetime);	
	if($datetime_check != ''){
		$datetime_new = @mktime(substr($datetime, 11, 2), substr($datetime, 14, 2),
							   substr($datetime, 17, 2), substr($datetime, 5, 2),
						       substr($datetime, 8, 2), substr($datetime, 0, 4));

		// convert datetime according to local settings
		if($locale && Application::Get('lang') != 'en'){
			$format = str_replace('%b', get_month_local(@strftime('%m', $datetime_new)), get_datetime_format(true, true));
			return @strftime($format, $datetime_new);
		}

		return @date($format, $datetime_new);						
	}else{
		return $empty_text;
	}		
}

/**
 * Get datetime format
 * 		@param $show_hours
 * 		@param $locale
 */
function get_datetime_format($show_hours = true, $locale = false)
{
	global $objSettings;	
	
	if($objSettings->GetParameter('date_format') == 'dd/mm/yyyy'){
		if($locale) $datetime_format = ($show_hours) ? '%d %b, %Y %H:%M' : '%d %b, %Y';
		else $datetime_format = ($show_hours) ? 'd M, Y g:i A' : 'd M, Y';
	}else{
		if($locale) $datetime_format = ($show_hours) ? '%b %d, %Y %H:%M' : '%b %d %Y';
		else $datetime_format = ($show_hours) ? 'M d, Y g:i A' : 'M d, Y';
	}
	return $datetime_format;
}

/**
 * Get time format
 * 		@param $show_seconds
 * 		@param $settings_format
 */
function get_time_format($show_seconds = true, $settings_format = false)
{
	global $objSettings;	
	
	if($objSettings->GetParameter('time_format') == 'am/pm'){
		if($settings_format) $time_format = 'am/pm';
		else $time_format = ($show_seconds) ? 'g:i:s A' : 'g:i A'; 		
	}else{
		if($settings_format) $time_format = '24';
		else $time_format = ($show_seconds) ? 'H:i:s' : 'H:i'; 		
	}
	return $time_format;
}

/**
 *  Format date
 *  	@param $date
 *  	@param $format
 *  	@param $empty_text
 *  	@param $locale 
 */
function format_date($date, $format = '', $empty_text = '', $locale = false)
{	
	$format = ($format == '') ? get_date_format() : $format;
	
	if(!empty($date)){
		$date_new = mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4));

		// convert date according to local settings
		if($locale && Application::Get('lang') != 'en'){
			$format = str_replace('%b', get_month_local(@strftime('%m', $date_new)), get_date_format('', false, true));
			return @strftime($format, $date_new);
		}

		return @date($format, $date_new);						
	}else{
		return $empty_text;
	}		
}

/**
 * Get date format
 * 		@param $format
 * 		@param $settings_format
 * 		@param $locale 
 */
function get_date_format($format = 'view', $settings_format = false, $locale = false)
{
	global $objSettings;	
	
	if($objSettings->GetParameter('date_format') == 'mm/dd/yyyy'){
		if($locale) $date_format = '%b %d, %Y';
		else if($settings_format) $date_format = 'mm/dd/yyyy';
		else $date_format = ($format == 'edit') ? 'm-d-y' : 'M d, Y';		
	}else{
		if($locale) $date_format = '%d %b, %Y';
		else if($settings_format) $date_format = 'dd/mm/yyyy';
		else $date_format = ($format == 'edit') ? 'd-m-y' : 'd M, Y';
	}
	return $date_format;
}

/**
 *	Get encoded text
 *		@param $string
 */
function encode_text($string = '')
{
	$search	 = array("\\","\0","\n","\r","\x1a","'",'"',"\'",'\"');
	$replace = array("\\\\","\\0","\\n","\\r","\Z","\'",'\"',"\\'",'\\"');
	return str_replace($search, $replace, $string);
}

/**
 *	Get quoted text
 *		@param $string
 **/
function quote_text($string = '')
{
	return '\''.$string.'\'';
}

/**
 *	Get decoded text
 *		@param $string
 *		@param $code_quotes
 *		@param $quotes_type
 */
function decode_text($string = '', $code_quotes = true, $quotes_type = '')
{
	$single_quote = "'";
	$double_quote = '"';		
	if($code_quotes){
		if(!$quotes_type){
			$single_quote = '&#039;';
			$double_quote = '&#034;';
		}else if($quotes_type == 'single'){
			$single_quote = '&#039;';
		}else if($quotes_type == 'double'){
			$double_quote = '&#034;';
		}
	}
	
	$search  = array("\\\\","\\0","\\n","\\r","\Z","\\'",'\\"','"',"'");
	$replace = array("\\","\0","\n","\r","\x1a","\'",'\"',$double_quote,$single_quote);
	return str_replace($search, $replace, $string);
}
   
/**
 * Draw breadcrumbs
 * 		@param $breadcrumbs
 */
function prepare_breadcrumbs($breadcrumbs)
{
	$output = '';
	if(is_array($breadcrumbs)){
		$raquo = '&raquo;';

		foreach($breadcrumbs as $key => $val){
			if(!empty($key)){
				if(!empty($output)) $output .= ' '.$raquo.' ';
				if(!empty($val)) $output .= '<a class="cbc" href="'.APPHP_BASE.$val.'">'.$key.'</a>';
				else $output .= '<span class="cbc">'.$key.'</span>';
			}
		}	
	}
	return $output;
}

/**
 * Prepares alpha numeric input
 * @param $field
 */
function prepare_input_alphanumeric($field = '')
{
	$field = (string)$field;
	
	if(preg_match('/[^a-zA-z0-9_\-\.]/', $field)){
		return '';
	}else{
		return $field;
	}
}

/**
 *	Remove bad chars from input
 *	  	@param $str_words - input
 *	  	@param $escape
 *	  	@param $level
 */
function prepare_input($str_words, $escape = false, $level = 'high')
{
	$found = false;
	if($level == 'low'){
		$bad_string = array('%20union%20', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<applet', '<meta', '<style', '<form', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', 'include_path', 'prefix', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=', 'onload=');
	}else if($level == 'medium'){
		$bad_string = array('xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', 'script>', '<iframe', '<applet', '<meta', '<style', '<form', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=', 'onload=');		
	}else if($level == 'high'){
		$bad_string = array('select', 'drop', '--', 'insert', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', 'script>', '<iframe', '<applet', '<meta', '<style', '<form', '<img', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'http://', 'https://', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=', 'onload=');
	}else if($level == 'extra'){
		$bad_string = array('select', 'drop', '--', 'insert', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', 'script>', '<iframe', '<applet', '<meta', '<style', '<form', '<img', '<body', '<link', '<input', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'http://', 'https://', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=', 'onload=', '<', '>', "'", '"');
	}
	for($i = 0; $i < count($bad_string); $i++){
		$str_words = str_ireplace($bad_string[$i], '', $str_words);	
	}
	
	if($escape){
		$str_words = encode_text($str_words); 
	}
	
	return $str_words;            
}

/**
 * Start Caching of page
 *      @param $cachefile - name of file to be cached
 */
function start_caching($cachefile)
{
	global $objSettings;	

    $nl = "\n";
	$cache_lifetime = (int)$objSettings->GetParameter('cache_lifetime');
	
	if($cachefile != '' && file_exists(CACHE_DIRECTORY.$cachefile)) {        
		$cachetime = $cache_lifetime * 60; /* cache lifetime in minutes */
		// Serve from the cache if it is younger than $cachetime
		if(file_exists(CACHE_DIRECTORY.$cachefile) && (filesize(CACHE_DIRECTORY.$cachefile) > 0) && ((time() - $cachetime) < filemtime(CACHE_DIRECTORY.$cachefile))){
			// the page has been cached from an earlier request output the contents of the cache file
			include_once(CACHE_DIRECTORY.$cachefile); 
			echo '<!-- Generated from cache at '.@date('H:i', filemtime(CACHE_DIRECTORY.$cachefile)).' -->'.$nl;
			return true;
		}        
	}
	// start the output buffer
	ob_start();
}

/**
 * Finish Caching of page
 * 	    @param $cachefile - name of file to be cached
 */
function finish_caching($cachefile)
{
	if($cachefile != ''){
		$fp = @fopen(CACHE_DIRECTORY.$cachefile, 'w'); 
		@fwrite($fp, ob_get_contents());
		@fclose($fp); 
		// Send the output to the browser
		ob_end_flush();
		// check if we exeeded max number of cache files
		check_cache_files();
	}
}

/**
 * Delete all cache files
 */
function delete_cache()
{
	global $objSettings;	
	
	///if(!$objSettings->GetParameter('caching_allowed')) return false;
	
	if($hdl = @opendir(CACHE_DIRECTORY)){
		while(false !== ($obj = @readdir($hdl))){
			if($obj == '.' || $obj == '..' || $obj == '.htaccess') continue; 
			@unlink(CACHE_DIRECTORY.$obj);
		}
	}
}    

/**
 * Check chache files
 */
function check_cache_files()
{		
	$oldest_file_name = '';
	$oldest_file_time = @date('Y-m-d H:i:s');

	if(count(glob(CACHE_DIRECTORY.'*')) > 100){
		if($hdl = opendir(CACHE_DIRECTORY)){
			while(false !== ($obj = @readdir($hdl))){
				if($obj == '.' || $obj == '..' || $obj == '.htaccess') continue; 
				$file_time = @date('Y-m-d H:i:s', filectime(CACHE_DIRECTORY.$obj));
				if($file_time < $oldest_file_time){
					$oldest_file_time = $file_time;
					$oldest_file_name = CACHE_DIRECTORY.$obj;
				}				
			}
		}		
		@unlink($oldest_file_name);		
	}
}

/**
 * Prepare permanent link
 * 		@param $href
 * 		@param $link
 * 		@param $target
 * 		@param $css_class
 * 		@param $title
 * 		@param $js_event
 * 		ex.: prepare_permanent_link('index.php?admin=login', _ADMIN_LOGIN, '', 'main_link');
 */
function prepare_permanent_link($href, $link, $target = '', $css_class = '', $title = '', $js_event = '')
{
	$css_class = ($css_class != '') ? ' class="'.$css_class.'"' : '';
	$target = ($target != '') ? ' target="'.$target.'"' : '';
	$title = ($title != '') ? ' title="'.decode_text($title).'"' : '';
	$js_event = ($js_event != '') ? ' '.$js_event : '';
	$base = !preg_match('/http:\/\/|https:\/\/|ftp:\/\/|javascript|www./i', $href) ? APPHP_BASE : '';
	
	return '<a'.$css_class.$target.$title.$js_event.' href="'.$base.$href.'">'.$link.'</a>';
}

/**
 * Prepare link
 * 		@param $page_type
 * 		@param $page_id_param
 * 		@param $page_id
 * 		@param $page_url_name
 * 		@param $page_name
 * 		@param $css_class
 * 		@param $title
 * 		@param $href_only
 * 		@param $target
 */
function prepare_link($page_type, $page_id_param, $page_id, $page_url_name, $page_name, $css_class = '', $title = '', $href_only = false, $target = '')
{
	global $objSettings;	
	
	$css_class = ($css_class != '') ? ' class="'.$css_class.'"' : '';
	$title = ($title != '') ? ' title="'.decode_text($title).'"' : '';
	$page_url_name = str_replace(array(" ", "\\'", '\\"', '#', "'", '"'), '-', (($page_url_name != '') ? $page_url_name : $page_name));
    $page_url_name = str_replace(array('---', '--'), '-', $page_url_name);
    $target = ($target != '') ? ' target="'.$target.'"' : '';

	// Use SEO optimized link	
	if($objSettings->GetParameter('seo_urls') == '1'){
		$href = $page_type.(($page_id != '') ? '/'.$page_id : '').(($page_url_name != 'index') ? '/'.$page_url_name.'.html' : '.html');
		if($href_only) return $href;
        else return '<a'.$css_class.$title.$target.' href="'.APPHP_BASE.$href.'">'.$page_name.'</a>';
	}else{
		$href = 'index.php?page='.$page_type.(($page_id_param != '') ? '&amp;'.$page_id_param.'='.$page_id : '');
		if($href_only) return $href;
        else return '<a'.$css_class.$title.$target.' href="'.APPHP_BASE.$href.'">'.decode_text($page_name).'</a>';
	}	
}

/**
 * Get OS name
 */
function get_os_name()
{
	// some possible outputs
	// Linux: Linux localhost 2.4.21-0.13mdk #1 Fri Mar 14 15:08:06 EST 2003 i686		
	// FreeBSD: FreeBSD localhost 3.2-RELEASE #15: Mon Dec 17 08:46:02 GMT 2001		
	// WINNT: Windows NT XN1 5.1 build 2600		
	// MAC: Darwin Ron-Cyriers-MacBook-Pro.local 10.6.0 Darwin Kernel Version 10.6.0: Wed Nov 10 18:13:17 PST 2010; root:xnu-1504.9.26~3/RELEASE_I386 i386
	$os_name = strtoupper(substr(PHP_OS, 0, 3));
	switch($os_name){
		case 'WIN':
			return 'windows'; break;
		case 'LIN':
			return 'linux'; break;
		case 'FRE':
			return 'freebsd'; break;
		case 'DAR':
			return 'mac'; break;
		default:
			return 'windows'; break;
	}
}

/**
 * Send email
 * 		@param $recipient
 * 		@param $sender
 * 		@param $email_template
 * 		@param $replace_holders
 * 		@param $cc_email
 * 		@param $cc_subject
 * 		@param $debug
 */
function send_email($recipient, $sender, $email_template, $replace_holders = array(), $lang = '', $cc_email = '', $cc_subject = '', $debug = false)
{
	global $objSettings;
	
	if($lang == ''){
		$lang = Application::Get('lang');
		$lang_dir = Application::Get('lang_dir');
	}else{
		$lang_dir = Languages::Get($lang, 'lang_dir');
	}
	
	$objEmailTemplates = new EmailTemplates();				
	$email_info = $objEmailTemplates->GetTemplate($email_template, $lang);
	$arr_constants = array();
	$arr_constants_all = array(
		'{FIRST NAME}', '{LAST NAME}', '{USER NAME}', '{USER PASSWORD}', '{USER EMAIL}',
		'{REGISTRATION CODE}', '{BASE URL}', '{WEB SITE}', '{YEAR}', '{EVENT}'
	);
	$arr_values  = array();
	
	foreach($replace_holders as $key => $val){
		$arr_constants[] = $key;
		$arr_values[] = $val;
	}
	// add the rest of holders
	foreach($arr_constants_all as $key){
		if(!in_array($key, $arr_constants)){
			$arr_constants[] = $key;
			$arr_values[] = '';
		}
	}

	$subject = str_ireplace($arr_constants, $arr_values, $email_info['template_subject']);
	if($cc_email == '' && $cc_subject != '') $subject = $cc_subject;

	$body  = '<div style=direction:'.$lang_dir.'>';
	$body .= str_ireplace($arr_constants, $arr_values, $email_info['template_content']);
	$body .= '</div>';			
	
	if($objSettings->GetParameter('mailer') == 'smtp'){
		$mail = PHPMailer::Instance();
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug  = 0;          // enables SMTP debug information (for testing)
										// 1 = errors and messages
										// 2 = messages only
		$mail->SMTPAuth   = ($objSettings->GetParameter('smtp_auth') == '1') ? true : false; // enable SMTP authentication                                          
		$mail->SMTPSecure = (in_array($objSettings->GetParameter('smtp_secure'), array('ssl', 'tls'))) ? $objSettings->GetParameter('smtp_secure') : '';  // sets the prefix to the server
		$mail->Host       = $objSettings->GetParameter('smtp_host');  
		$mail->Port       = $objSettings->GetParameter('smtp_port');  
		$mail->Username   = $objSettings->GetParameter('smtp_username'); 
		$mail->Password   = $objSettings->GetParameter('smtp_password'); 
		
		$mail->ClearAddresses();       // clear previously added 'To' addresses
		$mail->ClearReplyTos();        // clear previously added 'ReplyTo' addresses
		$mail->SetFrom($sender);       // $mail->SetFrom($mail_from, 'First Last');
		$mail->AddReplyTo($sender);    // $mail->AddReplyTo($mail_to, 'First Last');

		$recipients = explode(',', $recipient);
		foreach($recipients as $key){
			$mail->AddAddress($key);    // $mail->AddAddress($mail_to, 'John Doe'); 	
		}

		$mail->Subject    = $subject;
		$mail->AltBody    = strip_tags($body);
		$mail->MsgHTML(nl2br($body));

		$result = $mail->Send();
		
		if($cc_email != ''){
			$mail->ClearAddresses();       // clear previously added 'To' addresses
			$mail->ClearReplyTos();        // clear previously added 'ReplyTo' addresses
			$mail->AddAddress($cc_email);  // $mail->AddAddress($mail_to, 'John Doe');
			$mail->Subject = (($cc_subject != '') ? $cc_subject : $subject);
			$result = $mail->Send();		
		}		
	}else{
		$text_version = strip_tags($body);
		$html_version = nl2br($body);
	
		$objEmail = new Email($recipient, $sender, $subject); 				
		$objEmail->textOnly = false;
		$objEmail->content = $html_version;	
		$result = $objEmail->Send();
		
		if($cc_email != ''){
			if($cc_subject != '') $subject = $cc_subject;
			$objEmail = new Email($cc_email, $sender, $subject); 				
			$objEmail->textOnly = false;
			$objEmail->content = $html_version;	
			$result = $objEmail->Send();		
		}		
	}
	
	if($debug){
		echo 'To: '.$recipient.' <br>From: '.$sender.' <br>Subject: '.$subject.' <br>'.$body;
		if($cc_email != ''){
			echo '<br>--------<br>To: '.$cc_email.' <br>From: '.$sender.' <br>';
		}
		exit;
	}
	return $result;
}

/**
 * Send email
 * 		@param $recipient
 * 		@param $sender
 * 		@param $title
 * 		@param $body
 * 		@param $lang
 * 		@param $debug
 */
function send_email_wo_template($recipient, $sender, $subject, $body, $lang = '', $debug = false)
{
	global $objSettings;
	
	if($lang == ''){
		$lang = Application::Get('lang');
		$lang_dir = Application::Get('lang_dir');
	}else{
		$lang_dir = Languages::Get($lang, 'lang_dir');
	}
	
	$text  = '<div style="direction:'.$lang_dir.'">';
	$text .= $body;
	$text .= '</div>';			

	if($objSettings->GetParameter('mailer') == 'smtp'){
		$mail = PHPMailer::Instance();
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug  = 0;          // enables SMTP debug information (for testing)
										// 1 = errors and messages
										// 2 = messages only
		$mail->SMTPAuth   = ($objSettings->GetParameter('smtp_auth') == '1') ? true : false; // enable SMTP authentication                                          
		$mail->SMTPSecure = (in_array($objSettings->GetParameter('smtp_secure'), array('ssl', 'tls'))) ? $objSettings->GetParameter('smtp_secure') : '';  // sets the prefix to the server
		$mail->Host       = $objSettings->GetParameter('smtp_host');  
		$mail->Port       = $objSettings->GetParameter('smtp_port');  
		$mail->Username   = $objSettings->GetParameter('smtp_username'); 
		$mail->Password   = $objSettings->GetParameter('smtp_password'); 
		
		$mail->ClearAddresses();       // clear previously added 'To' addresses
		$mail->ClearReplyTos();        // clear previously added 'ReplyTo' addresses
		$mail->SetFrom($sender);       // $mail->SetFrom($mail_from, 'First Last');
		$mail->AddReplyTo($sender);    // $mail->AddReplyTo($mail_to, 'First Last');
		$mail->AddAddress($recipient); // $mail->AddAddress($mail_to, 'John Doe'); 

		$mail->Subject    = $subject;
		$mail->AltBody    = strip_tags($body);

		$mail->MsgHTML(nl2br($text));
		$result = $mail->Send();		
	}else{
		$text_version = strip_tags($text);
		$html_version = nl2br($text);
	
		$objEmail = new Email($recipient, $sender, $subject); 				
		$objEmail->textOnly = false;
		$objEmail->content = $html_version;	
		$result = $objEmail->Send();
	}

	if($debug){ echo $text; exit; }
	return $result;	
}

/**
 * Returns timezone by offset (last change 12.09.2011)
 */
function get_timezone_by_offset($offset)
{
	$zonelist = array(
	   'Pacific/Kwajalein' => -12.00,
	   'Pacific/Samoa' => -11.00,
	   'Pacific/Honolulu' => -10.00,
	   'Pacific/Marquesas' => -9.50,
	   'America/Juneau' => -9.00,
	   'America/Los_Angeles' => -8.00,
	   'America/Denver' => -7.00,
	   'America/Mexico_City' => -6.00,
	   'America/New_York' => -5.00,
	   'America/Caracas' => -4.50,
	   'America/Halifax' => -4.00,
	   'America/St_Johns' => -3.50,
	   'America/Argentina/Buenos_Aires' => -3.00,
	   'Atlantic/South_Georgia' => -2.00,
	   'Atlantic/Azores' => -1.00,
	   //'Europe/London' => 0,
	   'UTC' => 0,
	   'Europe/Berlin' => 1.00,
	   'Europe/Helsinki' => 2.00,
	   'Asia/Kuwait' => 3.00,
	   'Asia/Tehran' => 3.50,      
	   'Asia/Muscat' => 4.00,
	   'Asia/Kabul' => 4.50,
	   'Asia/Yekaterinburg' => 5.00,
	   'Asia/Kolkata' => 5.50,
	   'Asia/Kathmandu' => 5.75,
	   'Asia/Dhaka' => 6.00,
	   'Asia/Rangoon' => 6.50,
	   'Asia/Bangkok' => 7.00,
	   'Asia/Brunei' => 8.00,
	   'Australia/Eucla' => 8.75,      
	   'Asia/Tokyo' => 9.00,
	   'Australia/Darwin' => 9.50,
	   'Australia/Canberra' => 10.00,
	   'Australia/Lord_Howe' => 10.50,
	   'Asia/Magadan' => 11.00,
	   'Pacific/Norfolk' => 11.50,
	   'Pacific/Fiji' => 12.00,
	   'Pacific/Chatham' => 12.75,
	   'Pacific/Tongatapu' => 13.00,
	   'Pacific/Kiritimati' => 14.00
	);
	$index = array_keys($zonelist, $offset);
	if(sizeof($index)!=1) return false;
	return $index[0];
} 

/**
 * Get month local name
 * 		@param $mon
 */
function get_month_local($mon)
{
	$months = array(
		'1' => _JANUARY,
		'2' => _FEBRUARY,
		'3' => _MARCH,
		'4' => _APRIL,
		'5' => _MAY,
		'6' => _JUNE,
		'7' => _JULY,
		'8' => _AUGUST,
		'9' => _SEPTEMBER,
		'10' => _OCTOBER,
		'11' => _NOVEMBER,
		'12' => _DECEMBER
	);
	return isset($months[(int)$mon]) ? $months[(int)$mon] : '';
}

/**
 * Get week day local name
 * 		@param $wday
 */
function get_weekday_local($wday)
{
	$weekdays = array(
		'1' => _SUNDAY,
		'2' => _MONDAY,
		'3' => _TUESDAY,
		'4' => _WEDNESDAY,
		'5' => _THURSDAY,
		'6' => _FRIDAY,
		'7' => _SATURDAY
	);
	return isset($weekdays[(int)$wday]) ? $weekdays[(int)$wday] : '';
}

/**
 * Get currency format
 */
function get_currency_format()
{
	global $objSettings;	
	
	if($objSettings->GetParameter('price_format') == 'european'){
		$price_format = 'european';
	}else{
		$price_format = 'american';
	}
	return $price_format;
}

/**
 * Get time slots
 */
function get_time_slots()
{
	return array('5'=>'5 '._MIN, '10' => '10 '._MIN, '15' => '15 '._MIN, '20' => '20 '._MIN, '25' => '25 '._MIN, '30' => '30 '._MIN, '35' => '35 '._MIN, '40' => '40 '._MIN, '45' => '45 '._MIN, '50' => '50 '._MIN, '55' => '55 '._MIN, '60' => '60 '._MIN.' ('._HOUR.')', '90' => '1.5 '._HOURS, '120' => '2 '._HOURS, '150' => '2.5 '._HOURS, '180' => '3 '._HOURS, '210' => '3.5 '._HOURS, '240' => '4 '._HOURS);
}

/**
 * Perform redirect to specific page
 * 		@param $page
 * 		@param $delay in milliseconds
 * 		@param $text
 */
function redirect_to($page = '', $delay = '', $text = '', $draw = true)
{
	$output = '';
	if($page == '') return false;
	if($delay == ''){
		@header('location: '.(($page != '') ? $page : ''));
		$output .= '<script type="text/javascript">window.location.href="'.(($page != '') ? $page : '').'";</script>';
	}else{
		$output .= '<script type="text/javascript">setTimeout(\'window.location.href="'.(($page != '') ? $page : '').'"\', '.(int)$delay.');</script>';
		$output .= $text;
	}

	if($draw){
		echo $output;
		exit;		
	}else{
		return $output;
	}	
}

/**
 * Prepare pagination part
 **/
function pagination_get_links($total_pages, $url)
{
	$output = '';	
	$current_page = isset($_GET['p']) ? abs((int)$_GET['p']) : '1';
	if($total_pages > 1){
		$output .= '<div class="pagging">&nbsp;'._PAGES.': ';
		for($page_ind = 1; $page_ind <= $total_pages; $page_ind++){
			$output .= '<a class="pagging_link" href="'.$url.'&p='.$page_ind.'">'.(($page_ind == $current_page) ? '<b>'.$page_ind.'</b>' : $page_ind).'</a> ';
		}
		$output .= '</div>'; 
	}
	return $output;	
}

/**
 *	Convert to decimal number with leading zero
 *  	@param $number
 */	
function convert_to_decimal($number)
{
	return (($number < 0) ? '-' : '').((abs($number) < 10) ? '0' : '').abs($number);
}

?>