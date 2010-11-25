<?
class frmValid {

/*
	function __construct($historyBack = 1) {
		$this->historyBack = $historyBack;	
	}
*/

	function lengthlt($str, $length) {
		if(strlen(trim($str)) < $length) 
			return true;
		else 
			return false;
	}	

	function lengthgt($str, $length) {
		if(strlen(trim($str)) > $length) 
			return true;
		else 
			return false;
	}	

	function lengthTerm($str, $min, $max) {
		if( (strlen(trim($str)) >= $min) AND (strlen(trim($str)) <= $max) ) 
			return true;
		else
			return false;
	}
}

$fv = new frmValid();
?>
