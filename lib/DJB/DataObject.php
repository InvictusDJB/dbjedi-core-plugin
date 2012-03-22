<?php

namespace DJB;

class DataObject {
	protected $meta = null;

	public function __construct( $data ) {
		if( $data ) {
			$this->populate( $data );
		}//end if
	}//end constructor

	/**
	 * retrieves the object by ID or slug
	 */
	public static function get( $which ) {
		$class = get_class();

		$args = array(
			'post_type' => static::$post_type,
		);

		if( $which && ! preg_match( '/[^0-9]/', $which ) ) {
			$args['p'] = $which;
		} elseif( $which ) {
			$args['name'] = $which;
		} else {
			return null;
		}//end else

		$query = new \WP_Query( $args );
		return new $class( $query->posts[0] );
	}//end get

  public function get_static( $var ) { 
    return static::$$var;
  }//end static

	public function meta( $var ) {
		if( ! $this->meta ) {
			$this->meta = get_post_custom( $this->ID );
		}//end if

		return $this->meta[ $var ];
	}//end meta

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

	public function __get( $var ) {
		return $this->meta( $var );
	}//end __get
}//end class
