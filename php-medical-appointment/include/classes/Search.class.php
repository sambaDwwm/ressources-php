<?php

/**
 *	Class Search 
 *  -------------- 
 *  Description : encapsulates search methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 07.01.2014
 *  Usage       : MedicalAppointment
 *	
 *	PUBLIC:				  	STATIC:				 	PRIVATE:
 * 	------------------	  	---------------     	---------------
 *  __construct				DrawQuickSearch		    HighLight				  
 *	__destruct
 *	SearchBy
 *	DrawSearchResult
 *	DrawPopularSearches
 *	
 **/

class Search {

	private $pageSize;
	private $totalSearchRecords;

	//==========================================================================
    // Class Constructor
	//==========================================================================
	function __construct()
	{
		$this->pageSize = 20;
		$this->totalSearchRecords = 0;        
    }

	//==========================================================================
    // Class Destructor
	//==========================================================================
    function __destruct()
	{
		// echo 'this object has been destroyed';
    }

	/***
	 * Searchs in pages by keyword
	 *		@param $keyword - keyword
	 *		@param $page
	 *		@param $search_in
	 */	
	public function SearchBy($keyword, $page = 1, $search_in = 'pages')
	{
		$lang_id = Application::Get('lang');
		
		if($search_in == 'news'){
			$sql = 'SELECT
						CONCAT(\'page=news&nid=\', id) as url,
						header_text as title,
						body_text as text,
						\'article\' as content_type,
						\'\' as link_url 
					FROM '.TABLE_NEWS.' n
					WHERE
						language_id = \''.$lang_id.'\' AND
						(
						  header_text LIKE \'%'.encode_text($keyword).'%\' OR
						  body_text LIKE \'%'.encode_text($keyword).'%\'
						)';
			$order_field = 'n.id';						
		}else{ // pages
			$sql = 'SELECT
						CONCAT(\'page=pages&pid=\', id) as url,
						page_title as title,
						page_text as text,
						content_type,
						link_url 
					FROM '.TABLE_PAGES.' p
					WHERE
						language_id = \''.$lang_id.'\' AND
						is_published = 1 AND						
						show_in_search = 1 AND
						is_removed = 0 AND
						(finish_publishing IS NULL OR finish_publishing >= \''.date('Y-m-d').'\') AND 
						(
						  page_title LIKE \'%'.encode_text($keyword).'%\' OR
						  page_text LIKE \'%'.encode_text($keyword).'%\'
						)';
			$order_field = 'p.id';									
		}					

		if(!is_numeric($page) || (int)$page <= 0) $page = 1;
		$this->totalSearchRecords = (int)database_query($sql, ROWS_ONLY);
		$total_pages = ($this->totalSearchRecords / $this->pageSize);			
		if(($this->totalSearchRecords % $this->pageSize) != 0) $total_pages = (int)$total_pages + 1;
		$start_row = ($page - 1) * $this->pageSize;
		
		$result = database_query($sql.' ORDER BY '.$order_field.' ASC LIMIT '.$start_row.', '.$this->pageSize, DATA_AND_ROWS);		

		// update search results table		
		if((strtolower(SITE_MODE) != 'demo') && ($result[1] > 0)){
			$sql = 'INSERT INTO '.TABLE_SEARCH_WORDLIST.' (word_text, word_count) VALUES (\''.$keyword.'\', 1) ON DUPLICATE KEY UPDATE word_count = word_count + 1';
			database_void_query($sql);

			// store table contains up to 1000 records
			$sql = 'SELECT id, COUNT(*) as cnt FROM '.TABLE_SEARCH_WORDLIST.' ORDER BY word_count ASC';
			$res1 = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
			if($res1[1] > 0 && $res1[0]['cnt'] > 1000){
				$sql = 'DELETE FROM '.TABLE_SEARCH_WORDLIST.' WHERE id = '.(int)$res1[0]['id'];
				database_void_query($sql);
			}						
		}		
		return $result;
	}

	/**
	 * Draws search result
	 *		@param $search_result - search result
	 *		@param $page
	 *		@param $keyword 
	 */	
	public function DrawSearchResult($search_result, $page = 1, $keyword = '')
	{		
		$total_pages = (int)($this->totalSearchRecords / $this->pageSize);
		if(!is_numeric($total_pages) || (int)$total_pages <= 0) $total_pages = 1;

		if($search_result != '' && $search_result[1] > 0){
			echo '<div class="pages_contents">';					
			for($i = 0; $i < $search_result[1]; $i++){		
				if($search_result[0][$i]['content_type'] == 'article'){
                    echo ($i+1).'. '.prepare_permanent_link('index.php?'.$search_result[0][$i]['url'], decode_text($search_result[0][$i]['title'])).'<br />';
					
					$page_text = $search_result[0][$i]['text'];
					$page_text = str_replace(array('\\r', '\\n'), '', $page_text);
					$page_text = preg_replace('/{module:(.*?)}/i', '', $page_text);	
					$page_text = strip_tags($page_text);
					$page_text = decode_text($page_text);
                    $page_text = substr_by_word($page_text, 512);

					if(!empty($keyword)) $page_text = $this->HighLight($page_text, array($keyword));
                    
					echo $page_text.'...<br />';				
				}else{
					echo ($i+1).'. <a href="'.$search_result[0][$i]['link_url'].'">'.decode_text($search_result[0][$i]['title']).'</a> <img src="images/external_link.gif" alt="external link"><br />';					
				}
				echo '<br />';				
				draw_line();		
				echo '<br />';
			}
			echo '<b>'._PAGES.':</b> ';
			for($i = 1; $i <= $total_pages; $i++){
				echo '<a class="paging_link" href="javascript:void(0);" onclick="javascript:appPerformSearch('.$i.');">'.(($i == $page) ? '<b>['.$i.']</b>' : $i).'</a> ';
			}
			echo '</div>';
		}else{
			draw_important_message(_NO_RECORDS_FOUND);		
		}				
	}	

	/**
	 * Draws popular search keywords
	 */
	public function DrawPopularSearches()
	{
		$sql = 'SELECT word_text, word_count FROM '.TABLE_SEARCH_WORDLIST.' ORDER BY word_count DESC LIMIT 0, 20';
		$result = database_query($sql, DATA_AND_ROWS, ALL_ROWS);
		if($result[1] > 0){
			echo '<div class="pages_contents"><a href="javascript:void(0);" onclick="appToggleJQuery(\'popular_search\')">'._POPULAR_SEARCH.' +</a></div>';
			echo '<div class="popular_search_wrapper">';
            echo '<fieldset class="popular_search">';
			echo '<legend>'._KEYWORDS.'</legend>';
			for($i = 0; $i < $result[1]; $i++){
				if($i > 0) echo ', ';
				echo '<a onclick="javascript:appPerformSearch(1, \''.$result[0][$i]['word_text'].'\');" href="javascript:void(0)">'.$result[0][$i]['word_text'].'</a>';
			}
			echo '</fieldset>';
            echo '</div>';			
		}
	}		
	
	/**
	 * Draws quick search form
	 */
	public static function DrawQuickSearch($draw = true)
	{	
		$keyword = isset($_POST['keyword']) ? trim(prepare_input($_POST['keyword'])) : _SEARCH_KEYWORDS.'...';
		$keyword   = str_replace('"', '&#034;', $keyword);
		$keyword   = str_replace("'", '&#039;', $keyword);			

		$output = '<form id="search-form" name="frmQuickSearch" action="index.php?page=search" method="post">
			<div class="header_search">
			    '.draw_hidden_field('task', 'quick_search', false).'
				'.draw_hidden_field('p', '1', false).'
				'.draw_hidden_field('search_in', Application::Get('search_in'), false, 'search_in').'
				'.draw_token_field(false).'
				
				<div class="search_input">
				<input 	type="text"
						class="search_field"
						onblur="if(this.value == \'\') this.value=\''._SEARCH_KEYWORDS.'...\';"
						onfocus="if(this.value == \''._SEARCH_KEYWORDS.'...\') this.value = \'\';"
						maxlength="50" size="'.(strlen(_SEARCH_KEYWORDS)+5).'"
						value="'.$keyword.'"
						name="keyword" />
				<input class="button" type="button" value="'._SEARCH.'" onclick="appQuickSearch()" />
				</div>				
			</div>
			</form>';
		
		if($draw) echo $output;
		else return $output;
	}	

	/**
	 * Higlhlight search result
	 * 		@param $str
	 * 		@param $words
	 */
	private function HighLight($str, $words)
	{
		if(!is_array($words) || empty($words) || !is_string($str)){
			return false;
		}
		$arr_words = implode('|', $words);
		return preg_replace('@('.$arr_words.')@si', '<strong style="background-color:yellow">$1</strong>', $str);
	}
    
}

?>