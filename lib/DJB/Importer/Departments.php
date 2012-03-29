<?php

namespace DJB\Importer;

class Departments extends \DJB\Importer {
	public $post_type = 'djb-department';
	public $page_title = 'Departments';

	public function data( $count = false ) {
		if( $count ) {
			return \DJB::db('olddjb')->GetOne("SELECT count(*) FROM sa_course_groups");
		}//end if

		$sql = "
			SELECT sa_course_group_id legacy_id,
			       name post_title,
						 member_id legacy_instructor_id
				FROM sa_course_groups
		";

		$data = \DJB::db('olddjb')->GetAll( $sql );

		foreach( $data as &$row ) {
			if( $row['legacy_instructor_id'] ) {

				$user_id = \DJB\Legacy::user_to_id( $row['legacy_instructor_id'] );

				if( ! $user_id ) {
					throw new \Exception("Whoops!  Could not find a user with a pin of {$row['legacy_instructor_id']}.  Perhaps users haven't been fully imported yet?");
				}//end if

				$row['instructor_id'] = $user_id;
			}//end if

			unset( $row['legacy_instructor_id'] );
		}//end foreach
		return $data;
	}//end data
}//end Departments
