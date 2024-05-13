    
<?php
class Pagination{
	var $baseURL		= '';
	var $totalRows  	= '';
	var $perPage	 	= '';
	var $numLinks		=  20;
	var $currentPage	=  '';
	var $nextLink		= 'Next';
	var $prevLink		= 'Prev';
	var $fullTagOpen	= '<div class="pagination">';
	var $fullTagClose	= '</div>';
	var $curTagOpen		= '&nbsp;<b>';
	var $curTagClose	= '</b>';
	var $nextTagOpen	= '&nbsp;';
	var $nextTagClose	= '&nbsp;';
	var $prevTagOpen	= '&nbsp;';
	var $prevTagClose	= '';
	var $numTagOpen		= '&nbsp;';
	var $numTagClose	= '';
	var $anchorClass	= '';
	var $link_func      = '';
	var $contentDiv     = '';
    
	function __construct($params = array()){
		if (count($params) > 0){
			$this->initialize($params);		
		}
		if ($this->anchorClass != ''){
			$this->anchorClass = 'class="'.$this->anchorClass.'" ';
		}	
	}
	function initialize($params = array()){
		if (count($params) > 0){
			foreach ($params as $key => $val){
				if (isset($this->$key)){
					$this->$key = $val;
				}
			}		
		}
	}
	
	// Generate the pagination links	
	function createLinks(){ 
		if ($this->totalRows == 0 OR $this->perPage == 0){

			return '';
		}
		// Calculate the total number of pages
		$numPages = ceil($this->totalRows/ $this->perPage);
		
		// Links content string variable
		$output = '';
		
	
		$uriPageNum = $this->currentPage;

		// Calculate the start and end numbers. 
		$start = (($this->currentPage - $this->numLinks) > 0) ? $this->currentPage - ($this->numLinks - 1) : 1;
		$end   = (($this->currentPage + $this->numLinks) < $numPages) ? $this->currentPage + $this->numLinks : $numPages;


		// Render the "previous" link
		if  ($this->currentPage != 1){
			$i = $uriPageNum - $this->perPage;
			if ($i == 0) $i = '';
			$output .= $this->prevTagOpen 
				. $this->getAJAXlink( $i, $this->prevLink )
				. $this->prevTagClose;
		}

		// Write the digit links
		// Write the digit links
		for ($loop = $start - 1; $loop <= $end; $loop++) {
			$i = ($loop * $this->perPage) - $this->perPage + 1; // Calculate the appropriate value for columnSorting

			if ($i >= 0) {
				if ($this->currentPage == $loop) {
					$output .= $this->curTagOpen . $loop . $this->curTagClose;
				} else {
					$output .= $this->numTagOpen
						. $this->getAJAXlink($i, $loop) // Pass $i as the parameter for columnSorting
						. $this->numTagClose;
				}
			}
		}


		// Render the "next" link
		if ($this->currentPage < $numPages){
			$output .= $this->nextTagOpen 
				. $this->getAJAXlink( $this->currentPage * $this->perPage , $this->nextLink )
				. $this->nextTagClose;
		}


		// Remove double slashes
		$output = preg_replace("#([^:])//+#", "\\1/", $output);

		// Add the wrapper HTML if exists
		$output = $this->fullTagOpen.$output.$this->fullTagClose;
		
		return $output;		
	}

	function getAJAXlink( $count, $text) {
        if($this->link_func == '')
            return '<a href="'.$this->baseURL.'?'.$count.'"'.$this->anchorClass.'>'.$text.'</a>';
		$pageCount = $count?$count:0;
		if(!empty($this->link_func)){
			$linkClick = 'onclick="'.$this->link_func.'('.$pageCount.')"';
		}else{
			$linkClick = "onclick=\"$.post('". $this->baseURL."', {'page' : $pageCount}, function(data){
					   $('#". $this->contentDiv . "').html(data); }); return false;\"";
		}
		
	    return "<a href=\"javascript:void(0);\" " . $this->anchorClass . "
				". $linkClick .">". $text .'</a>';
	}
}
?>
