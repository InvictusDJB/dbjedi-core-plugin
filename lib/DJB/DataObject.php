<?php

namespace DJB;

class DataObject {
	public function __construct( $data ) {
		if( $data ) {
			$this->populate( $data );
		}//end if
	}//end constructor

  public function get_static( $var ) { 
    return static::$$var;
  }//end static

	/**
	 * take a row or object and place those vars into this object
	 */
  public function populate( $row ) { 
		if( is_object( $row ) ) {
			$row = get_object_vars( $row );
		}//end if

    foreach( $row as $key => $value ) { 
      if( isset($value) ) { 
        $this->$key = $value;
      }//end if 
    }//end foreach 
  }//end populate

  /** 
   *
   */
  public function serialize() {
    $s = get_object_vars($this);

    return serialize($s);
  }//end serialize

  /** 
   *
   */
  public function unserialize( $serialized ) { 
    $s = unserialize($serialized);
    $this->populate($s);
  }//end unserialize
}//end class
