<?php
namespace Gepard;

class MultiHash {
	const CLASSNAME = "MultiHash";
  public $map = [] ;
  function __construct() {

  }
  public function __toString() {
    ob_start();
    var_dump($this->map);
	  return "(" . MultiHash::CLASSNAME . ")\n" . ob_get_clean();
  }
  public function put ( $name, $value ) {
		if (isset($this->map[$name])) {
			$l = $this->map[$name] ;
	 	}
		else {
			$l = [] ;
		}
		array_push($l, $value);
		$this->map[$name] = $l ;
  }
  public function get ( $name ) {
  	if (!isset($this->map[$name])) {
  		return false ;
  	}
  	return $this->map[$name];
  }
}
