<?php

namespace DJB;

class Legacy {

	/**
	 * translates an old DJB course number to a WP Course ID
	 */
	public static function course_to_id( $legacy_id ) {
		return self::_old_to_new_by_meta( 'djb-course', $legacy_id );
	}//end course_to_id

	/**
	 * translates an old DJB degree id to a WP Degree ID
	 */
	public static function degree_to_id( $legacy_id ) {
		return self::_old_to_new_by_meta( 'djb-degree', $legacy_id );
	}//end degree_to_id

	/**
	 * translates an old DJB department id to a WP department id
	 */
	public static function department_to_id( $legacy_id ) {
		return self::_old_to_new_by_meta( 'djb-department', $legacy_id );
	}//end department_to_id

	/**
	 * translates an old DJB medal id to a WP Medal ID
	 */
	public static function medal_to_id( $legacy_id ) {
		return self::_old_to_new_by_meta( 'djb-medal', $legacy_id );
	}//end medal_to_id

	/**
	 * translates an old DJB order to a WP order id
	 */
	public static function order_to_id( $legacy_id ) {
		return self::_old_to_new_by_meta( 'djb-rank', $legacy_id );
	}//end order_to_id

	/**
	 * translates an old DJB rank id to a WP rank ID
	 */
	public static function rank_to_id( $legacy_id ) {
		return self::_old_to_new_by_meta( 'djb-rank', $legacy_id );
	}//end rank_to_id

	/**
	 * translates an old DJB species id to a WP species ID
	 */
	public static function species_to_id( $legacy_id ) {
		return self::_old_to_new_by_meta( 'djb-species', $legacy_id );
	}//end species_to_id

	/**
	 * translates a given field from a legacy DB value to a WP value
	 *
	 * @param $row array Row from the database
	 * @param $what string What type of translation is being done. Ex: user, department, course, etc
	 * @param $field string Field in $row with a legacy value that will be replaced
	 * @param $by string Field from the post table (sort of). This maps to a function name
	 * @param $required boolean Throw an error if no match was found in WP
	 */
	public static function translate( &$row, $what, $field, $by = 'id', $required = true ) {
		$function = "{$what}_to_{$by}";

		$value = $row[ $field ];

		if( $value ) {
			if( $result = self::$function( $value ) ) {
				$row[ $field ] = $result;
			} elseif( $required ) {
				throw new \Exception("Whoops!  Could not find a {$what} with an id of {$value}.  Perhaps the {$what}s haven't been fully imported yet?");
			}//end elseif
		}//end if

		if( ! $row[ $field ] ) {
			unset( $row[ $field ] );
		}//end if
	}//end translate

	/**
	 * translates an old DJB pin number to a WP User ID
	 *
	 * @param $pin int Old DJB PIN number
	 */
	public static function user_to_id( $pin ) {
		if( ! $pin ) return null;

		$user = get_users( array(
			'meta_key' => 'pin',
			'meta_value' => $pin,
		));

		if( $user = $user[0] ) {
			return $user->ID;
		}//end if

		return null;
	}//end user_to_id

	private static function _old_to_new( $args ) {
		if( $data = new \WP_Query( $args ) ) {
			if( ! $data->found_posts ) return null;

			$data = $data->get_posts();

			return $data[0]->ID;
		}//end if

		return null;
	}//end args

	private static function _old_to_new_by_meta( $post_type, $value, $args = array() ) {
		$defaults = array(
			'post_type' => $post_type,
			'posts_per_page' => 1,
			'meta_key' => 'legacy_id',
			'meta_value' => $value,
		);

		$args = array_merge( $defaults, $args );

		return self::_old_to_new( $args );
	}//end _old_to_new_by_meta

	private static function _old_to_new_by_title( $post_type, $value, $args = array() ) {
		$defaults = array(
			'post_type' => $post_type,
			'posts_per_page' => 1,
			'name' => strtolower( str_replace(' ', '-', $value) ),
		);

		$args = array_merge( $defaults, $args );

		return self::_old_to_new( $args );
	}//end _old_to_new_by_title
}//end class DJB\Legacy
