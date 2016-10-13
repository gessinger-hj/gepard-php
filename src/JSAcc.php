<?php
namespace Gepard;

/**
 * @Author: gess
 * @Date:   2016-10-10 14:14:17
 * @Last Modified by:   gess
 * @Last Modified time: 2016-10-12 21:49:25
 */
class JSAcc {
	public $map;
	function __construct(array &$map=null )
	{
		if ( $map === null ) {
			$this->map = [] ;
		}
		else {
			$this->map = &$map;
		}
	}
	public function map()
	{
		return $this->map;
	}
	public function add($path, $obj=[])
	{
		if ( ! strpos ( $path, "/" ) ) {
			$this->map[$path] = $obj;
			return $obj;
		}
		$plist = explode ( "/", $path );
		$m = &$this->map;

		$len = sizeof ( $plist ) ;
		for ( $i = 0 ; $i < $len ; $i++ )
		{
			$p = $plist[$i] ;
			if ( sizeof ( $p ) == 0 )
			{
				continue ;
			}
			$o = &$m[$p] ;
	  	if ( $i < sizeof ( $plist ) - 1 )
	  	{
	  		if ( !is_array ( $o ) )
	  		{
	  			$m[$p] = [] ;
	  			$mm = &$m[$p] ;
	  			$m = &$mm ;
	  		}
	  		if ( is_array ( $o ) )
	  		{
	  			$m = &$o ;
	  		}
	  		continue ;
  		}
      if ( $i == sizeof ( $plist ) - 1 )
      {
        if ( $obj == null ) $obj = [] ;
        $m[$p] = $obj ;
      }
		}
		return $obj;
	}
  public function &value ( $path, $def=NULL )
  {
		if ( ! strpos ( $path, "/" ) ) {
	    if ( !isset ( $this->map[$path] ) ) {
	    	return $def ;
	    }
			return $this->map[$path] ;
		}
		$plist = explode ( "/", $path );
    $mm = &$this->map ;
		for ( $i = 0 ; $i < sizeof ( $plist ) ; $i++ )
		{
			$p = $plist[$i] ;
			if ( sizeof ( $p ) == 0 )
			{
				continue ;
			}
	    if ( !isset ( $mm[$p] ) ) {
	    	return $def ;
      }
      if ( $i == sizeof ( $plist ) - 1 )
      {
      	$aa = &$mm[$p] ;
        return $aa ;
      }
      if ( is_array ( $mm[$p] ) )
      {
        $mm = &$mm[$p] ;
        continue ;
      }
    }
    return $def ;
  }
  public function &remove ( $path )
  {
		if ( ! strpos ( $path, "/" ) ) {
			$o = &$this->map[$path] ;
			unset ( $this->map[$path] ) ;
			return $o ;
		}
		$plist = explode ( "/", $path );
    $mm = &$this->map ;
		for ( $i = 0 ; $i < sizeof ( $plist ) ; $i++ )
		{
			$p = $plist[$i] ;
			if ( sizeof ( $p ) == 0 )
			{
				continue ;
			}
	    if ( !isset ( $mm[$p] ) ) {
	    	return null ;
      }
      if ( $i == sizeof ( $plist ) - 1 )
      {
      	$aa = &$mm[$p] ;
      	unset ( $mm[$p] ) ;
        return $aa ;
      }
      if ( is_array ( $mm[$p] ) )
      {
        $mm = &$mm[$p] ;
        continue ;
      }
    }
    return null ;
  }
	public function __toString() {
    ob_start();
    var_dump($this->map);
    return ob_get_clean();
	}
	public function toJSON($obj=null) {
		if ( ! $obj ) {
			$obj = &$this->map ;
		}
		$out = json_encode ( $obj ) ;
		return str_replace ( "[]", "{}", $out ) ;
	}
}
