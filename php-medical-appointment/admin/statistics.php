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
	
    define ('TABS_DIR', 'modules/tabs/');
    require_once(TABS_DIR.'tabs.class.php');
	
	echo '<script type="text/javascript" src="https://www.google.com/jsapi"></script>';

	$nl = "\n";
	$first_tab_content 	= '';
	$second_tab_content = '';
	$third_tab_content 	= '';	
	$tabid 				= isset($_POST['tabid']) ? prepare_input($_POST['tabid']) : '1_1';
	
	$chart_type = isset($_POST['chart_type']) ? prepare_input($_POST['chart_type']) : 'columnchart';
	$year 		= isset($_POST['year']) ? prepare_input($_POST['year']) : date('Y');

	if($tabid == '1_1'){		
		$sql = 'SELECT
					COUNT(*) as cnt,
					c.abbrv as country_abbrv,
					c.name as country_name
				FROM '.TABLE_PATIENTS.' u
					LEFT OUTER JOIN '.TABLE_COUNTRIES.' c ON u.b_country = c.abbrv AND c.is_active = 1
				WHERE c.is_active = 1
				GROUP BY c.abbrv';
				
		$result = database_query($sql, DATA_AND_ROWS, ALL_ROWS);
		$first_tab_content = '			
			<script type="text/javascript">
			 google.load(\'visualization\', \'1\', {\'packages\': [\'geomap\']});
			 google.setOnLoadCallback(drawMap);
			
			  function drawMap() {
				var data = new google.visualization.DataTable();
				';
				
				if($result[1] > 0){
					$first_tab_content .= $nl.'data.addRows('.$result[1].');';
					$first_tab_content .= $nl.'data.addColumn(\'string\', \''._COUNTRY.'\');';
					$first_tab_content .= $nl.'data.addColumn(\'number\', \''._POPULARITY.'\');';
					$first_tab_content .= $nl.'data.addColumn(\'string\', \'HOVER\', \'\');';
					for($i=0; $i < $result[1]; $i++){
						$first_tab_content .= $nl.'data.setValue('.$i.', 0, \''.$result[0][$i]['country_abbrv'].'\');';
						$first_tab_content .= $nl.'data.setValue('.$i.', 1, '.(int)$result[0][$i]['cnt'].');';
						$first_tab_content .= $nl.'data.setValue('.$i.', 2, \''.decode_text($result[0][$i]['country_name']).'\');';
					}
				}else{
					$first_tab_content .= $nl.'data.addRows(1);';
					$first_tab_content .= $nl.'data.addColumn(\'string\', \''._COUNTRY.'\');';
					$first_tab_content .= $nl.'data.addColumn(\'number\', \''._POPULARITY.'\');';
					$first_tab_content .= $nl.'data.addColumn(\'string\', \'HOVER\', \'\');';
					$first_tab_content .= $nl.'data.setValue(0, 0, \'USA\');';
					$first_tab_content .= $nl.'data.setValue(0, 1, 0);';
					$first_tab_content .= $nl.'data.setValue(0, 2, \'USA\');';										
				}
				
		$first_tab_content .= '	
				var options = {};
				options[\'dataMode\'] = \'regions\';
				options[\'width\'] = \'675px\';
				options[\'showLegend\'] = true;
			
				var container = document.getElementById(\'map_canvas\');
				var geomap = new google.visualization.GeoMap(container);
				geomap.draw(data, options);
			};
			</script>
			<div id="map_canvas" style="padding:1px 10px 1px 10px;"></div>		
		';	
	}else if($tabid == '1_2') {		
		
		$second_tab_content = '
			<script type="text/javascript">
				function drawVisualization(){
				// Create and populate the data table.
				var data = new google.visualization.DataTable();
				data.addColumn(\'string\', \''._MONTH.'\');
				data.addColumn(\'number\', \''._REGISTRATIONS.'\');';
				
				$selStatType = 'COUNT(*)';
				$join_clause = '';
				$where_clause = ' ';

				$sql = 'SELECT
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'01\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month1,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'02\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month2,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'03\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month3,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'04\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month4,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'05\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month5,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'06\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month6,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'07\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month7,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'08\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month8,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'09\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month9,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'10\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month10,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'11\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month11,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_created, 6, 2) = \'12\' AND SUBSTRING(u.date_created, 1, 4) = \''.$year.'\' '.$where_clause.') as month12
				  FROM '.TABLE_PATIENTS;         
	
				$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
					
		$second_tab_content .= $nl.'data.addRows(12);';
		
		if($result[1] >= 0){
			$second_tab_content .= draw_set_values($result[0], $chart_type, _REGISTRATIONS);
		}
				 
		$second_tab_content .= ' } </script>';
		
		$second_tab_content .= '<script type="text/javascript">';
		$second_tab_content .= $nl.'google.load(\'visualization\', \'1\', {packages: [\''.$chart_type.'\']});';
		$second_tab_content .= $nl.'google.setOnLoadCallback(drawVisualization);';
		$second_tab_content .= $nl.'function frmStatistics_Submit() { document.frmStatistics.submit(); }';
		$second_tab_content .= '</script>';	
		
		$second_tab_content .= get_chart_changer('1_2', $chart_type, $year);
		
		$second_tab_content .= '<div id="div_visualization" style="width:600px;height:310px;">
		<img src="images/ajax_loading.gif" style="margin:100px auto;" alt="loading" /></div>';
		
	}else if($tabid == '1_3') {		
		
		$third_tab_content = '
			<script type="text/javascript">
				function drawVisualization(){
				// Create and populate the data table.
				var data = new google.visualization.DataTable();
				data.addColumn(\'string\', \''._MONTH.'\');
				data.addColumn(\'number\', \''._REGISTRATIONS.'\');';
				
				$selStatType = 'COUNT(*)';
				$join_clause = '';
				$where_clause = ' ';

				$sql = 'SELECT
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('01').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month1,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('02').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month2,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('03').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month3,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('04').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month4,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('05').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month5,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('06').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month6,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('07').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month7,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('08').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month8,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('09').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month9,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('10').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month10,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('11').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month11,
				  (SELECT '.$selStatType.' FROM '.TABLE_PATIENTS.' u '.$join_clause.' WHERE SUBSTRING(u.date_lastlogin, 6, 2) = '.quote_text('12').' AND SUBSTRING(u.date_lastlogin, 1, 4) = '.quote_text($year).' '.$where_clause.') as month12
				  FROM '.TABLE_PATIENTS;         
	
				$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
			
		$third_tab_content .= $nl.'data.addRows(12);';
		
		if($result[1] >= 0){
			$third_tab_content .= draw_set_values($result[0], $chart_type, _LOGINS);
		}
				   
		$third_tab_content .= ' } </script>';

		$third_tab_content .= '<script type="text/javascript">';
		$third_tab_content .= $nl.'google.load(\'visualization\', \'1\', {packages: [\''.$chart_type.'\']});';
		$third_tab_content .= $nl.'google.setOnLoadCallback(drawVisualization);';
		$third_tab_content .= $nl.'function frmStatistics_Submit() { document.frmStatistics.submit(); }';
		$third_tab_content .= '</script>';

		$third_tab_content .= get_chart_changer('1_3', $chart_type, $year);		
		
		$third_tab_content .= '<div id="div_visualization" style="width:600px;height:310px;">
		<img src="images/ajax_loading.gif" style="margin:100px auto;" alt="loading" /></div>';
	}
	

	$tabs = new Tabs(1, 'xp', TABS_DIR, '?admin=statistics');   
 
	$tab1=$tabs->AddTab(_PATIENTS.' ('._MAP_OVERLAY.')', $first_tab_content);
	$tab2=$tabs->AddTab(_PATIENTS.' ('._REGISTRATIONS.')', $second_tab_content);
	$tab3=$tabs->AddTab(_PATIENTS.' ('._LOGINS.')', $third_tab_content);
	 
	## +---------------------------------------------------------------------------+
	## | 2. Customizing:                                                           |
	## +---------------------------------------------------------------------------+
	## *** set container's width in pixels (px), inches (in) or points (pt)
	$tabs->SetWidth('696px');
 
	## *** set container's height in pixels (px), inches (in) or points (pt)
	$tabs->SetHeight('auto'); // 'auto'
 
	## *** set alignment inside the container (left, center or right)
	$tabs->SetAlign(Application::Get('defined_left'));
 
	## *** set container's color in RGB format or using standard names
	/// $tabs->SetContainerColor('#64C864');
	## *** set border's width in pixels (px), inches (in) or points (pt)
	/// $tabs->SetBorderWidth('5px');
	## *** set border's color in RGB format or using standard names
	/// $tabs->SetBorderColor('#64C864');
	/// $tabs->SetBorderColor('blue');
	/// $tabs->SetBorderColor('#445566');
	## *** show debug info - false|true
	$tabs->Debug(false);
	## *** allow refresh selected tabs - false|true
	/// $tabs->AllowRefreshSelectedTabs(true);
	## *** set form submission type: 'get' or 'post'
	$tabs->SetSubmissionType('post');

	draw_title_bar(prepare_breadcrumbs(array(_ACCOUNTS=>'', _STATISTICS=>'')));

	draw_content_start();	
	$tabs->Display();
	draw_content_end();

}else{
	draw_title_bar(_ADMIN);
	draw_important_message(_NOT_AUTHORIZED);
}
?>