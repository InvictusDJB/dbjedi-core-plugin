<?php

namespace DJB\Importer;

class Degrees extends \DJB\Importer {
	public $post_type = 'djb-degree';
	public $page_title = 'Degrees';

	public function data( $count = false ) {
		if( $count ) {
			return \DJB::db('olddjb')->GetOne("SELECT count(*) FROM sa_courses LEFT OUTER JOIN courses co ON co.course_id = dblink WHERE co.coursetype = 'Degree'");
		}//end if

		$sql = "
			SELECT c.id legacy_id,
						 CONVERT(text, c.course_name) post_title,
						 co.sort_order menu_order,
						 co.abbr abbr,
						 co.coursetype as \"type\",
						 c.dblink legacy_dblink,
						 c.sa_course_group_id department_id,
						 g.member_id instructor_id,
						 CONVERT(text, co.mailtext) mailtext,
						 CONVERT(text, c.notes) post_content
				FROM sa_courses c
						 LEFT OUTER JOIN sa_course_groups g
							ON g.sa_course_group_id = c.sa_course_group_id
						 LEFT OUTER JOIN courses co
							ON co.course_id = c.dblink
			 WHERE co.coursetype = 'Degree'
		";

		$data = \DJB::db('olddjb')->GetAll( $sql );

		include_once \DJB\WordPress::plugin_dir() . '/external/Textile.php';

		$textile = new \Textile();

		foreach( $data as &$row ) {
			$row['post_content'] = $textile->TextileThis( html_entity_decode( $row['post_content'] ) );

			\DJB\Legacy::translate( $row, 'department', 'department_id' );
			\DJB\Legacy::translate( $row, 'user', 'instructor_id' );

		}//end foreach
		return $data;
	}//end data
}//end Degrees
