<?php

/**
 *	SocialNetworks MicroGrid Class
 *  --------------
 *	Description : encapsulates methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.1
 *  Updated	    : 25.12.2013
 *  Usage       : Core Class (ALL)
 *	Differences : no
 *
 *	PUBLIC:				  	STATIC:				 	PRIVATE:
 * 	------------------	  	---------------     	---------------
 *	__construct             DrawSocialIcons    
 *	__destruct
 *	LoadData
 *	GetAllData
 *	GetParameter
 *	UpdateFields
 *	
 **/


class SocialNetworks {
	
	protected $debug = false;
    
    private $res;
	
	//==========================================================================
    // Class Constructor
	//==========================================================================
	function __construct()
	{		
		$this->error = '';
		
		$this->LoadData();
	}
	
	//==========================================================================
    // Class Destructor
	//==========================================================================
    function __destruct()
	{
		// echo 'this object has been destroyed';        
    }

	/**
	 *	Loads parameters 
	 */
	public function LoadData()
	{
		$sql = 'SELECT * FROM '.TABLE_SOCIAL_NETWORKS;
		$result = database_query($sql, DATA_AND_ROWS, ALL_ROWS);
        for($i=0; $i<$result[1]; $i++){
            $this->res[$result[0][$i]['code']] = array('name'=>$result[0][$i]['name'], 'url'=>$result[0][$i]['url']); 
        }                
	}    
    
	/**
	 *	GetAllData
	 */
	public function GetAllData()
	{
        return $this->res;
	}

	/**
	 *	Returns parameter value by name
	 *		@param $field_name	
	 */
	public function GetParameter($field_name = '')
	{
		if(isset($this->res[$field_name]['url'])){
			return decode_text($this->res[$field_name]['url']);
		}else{
			return '';
		}
	}
    
    /**
     * Update table
     *      @param $params
     */
    public function UpdateFields($params = array())
    {
		// check if this is a DEMO
		if(strtolower(SITE_MODE) == 'demo'){ $this->error = defined('_OPERATION_BLOCKED') ? _OPERATION_BLOCKED : 'This operation is blocked in Demo Version!'; return false; }
		
		if(count($params) > 0){
			// prepare UPDATE statement
			$sql = 'UPDATE '.TABLE_SOCIAL_NETWORKS.' SET ';
            $sql .= 'url = CASE ';
			foreach($params as $key => $val){
				$sql .= 'WHEN code = \''.str_ireplace('link_', '', prepare_input($key)).'\' THEN \''.encode_text($val).'\' ';				
			}
            $sql .= 'END ';
			if(database_void_query($sql)){
				$this->LoadData();
				return true;
			}else{
				/// echo $sql.'<br>'.database_error();
				$this->error = _TRY_LATER;
				return false;
			}				
		}else{
			return '';						
		}
	}
    
    /**
     * Draws social networks icons
     *      @param $params
     *      @param $draw
     */
    public static function DrawSocialIcons($params = array(), $draw = true)
    {
        $output = '';
        $target = isset($params['target']) ? $params['target'] : '_blank';
        $link_class = isset($params['link_class']) ? $params['link_class'] : '';
        $wrapper = isset($params['wrapper']) ? $params['wrapper'] : '';
        $wrapper_class = isset($params['wrapper_class']) ? $params['wrapper_class'] : '';
        
		$sql = 'SELECT * FROM '.TABLE_SOCIAL_NETWORKS;
		$result = database_query($sql, DATA_AND_ROWS, ALL_ROWS);
        for($i=0; $i<$result[1]; $i++){
            if($result[0][$i]['url'] != ''){
                if($wrapper == 'div'){
                    $output .= '<div'.($wrapper_class? ' class="'.$wrapper_class.'"' : '').'>';
                }
                $output .= '<a';
                $output .= ($target ? ' target="'.$target.'"' : '');
                $output .= ($link_class ? ' class="'.$link_class.'"' : '');
                $output .= ' href="'.$result[0][$i]['url'].'">';
                $output .= '<img src="images/social_networks/'.$result[0][$i]['code'].'.png" alt="'.$result[0][$i]['name'].'">';
                $output .= '</a>';
                if($wrapper == 'div'){
                    $output .= '</div>';
                }
                $output .= "\n";
            }
        }
        
        if($draw) echo $output;
        else return $output;
    }    

}
?>