<?php

namespace DJB\Importer;

class Courses extends \DJB\Importer {
	public $post_type = 'djb-course';
	public $page_title = 'Courses';

	public function data( $count = false ) {
		if( $count ) {
			return \DJB::db('olddjb')->GetOne("SELECT count(*) FROM sa_courses LEFT OUTER JOIN courses co ON co.course_id = dblink WHERE co.coursetype <> 'Degree'");
		}//end if

		$sql = "
			SELECT c.id legacy_id,
						 CONVERT(text, c.course_name) post_title,
						 co.sort_order menu_order,
						 co.coursetype as \"type\",
						 c.dblink,
						 c.sa_course_group_id course_group_id,
						 g.name course_group,
						 g.member_id legacy_instructor_id,
						 CONVERT(text, co.mailtext) mailtext,
						 CONVERT(text, c.notes) post_content
				FROM sa_courses c
						 LEFT OUTER JOIN sa_course_groups g
							ON g.sa_course_group_id = c.sa_course_group_id
						 LEFT OUTER JOIN courses co
							ON co.course_id = c.dblink
			 WHERE co.coursetype <> 'Degree'
		";

		$data = \DJB::db('olddjb')->GetAll( $sql );

		include_once \DJB\WordPress::plugin_dir() . '/external/Textile.php';

		$textile = new \Textile();

		foreach( $data as &$row ) {
			$row['post_content'] = $textile->TextileThis( html_entity_decode( $row['post_content'] ) );
			if( $row['legacy_instructor_id'] ) {
				$user = get_users( array(
					'meta_key' => 'pin',
					'meta_value' => $row['legacy_instructor_id'],
				));

				if( ! ($user = $user[0]) ) {
					throw new \Exception("Whoops!  Could not find a user with a pin of {$row['legacy_instructor_id']}.  Perhaps users haven't been fully imported yet?");
				}//end if

				$row['instructor_id'] = $user->ID;
			}//end if

			unset( $row['legacy_instructor_id'] );
		}//end foreach
		return $data;
	}//end data
}//end Courses
