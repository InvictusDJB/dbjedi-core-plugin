<?php

namespace DJB\Importer;

class Competitions extends \DJB\Importer {
	public $post_type = 'djb-competition';
	public $page_title = 'Competitions';
	public $paged_import = true;

	public function data( $count = false, $from = 0, $num = 1 ) {
		if( $count ) {
			return \DJB::db('olddjb')->GetOne("SELECT count(*) FROM competitions");
		}//end if

		$sql = "
			SELECT TOP {$num} c.id legacy_id,
			       c.member_id post_author,
						 c.title post_title,
			       c.startdate,
					   c.enddate,
						 c.approved,
						 ISNULL( u.name, c.unit_other ) unit,
						 c.award_type,
						 c.award_other,
						 c.award_processed,
						 c.details post_content,
						 c.platform,
						 c.comments,
						 c.competition_type 
				FROM competitions c
			       LEFT JOIN units u
				     ON u.unit_id = c.unit_id
			 WHERE c.id > ?
			 ORDER BY c.id
		";

		$data = \DJB::db('olddjb')->GetAll( $sql, array( $from ) );

		foreach( $data as &$row ) {
			try {
				\DJB\Legacy::translate( $row, 'user', 'post_author' );
			} catch( \Exception $e ) {
				unset( $row['post_author'] );
			}//end catch
		}//end foreach
		return $data;
	}//end data
}//end Degrees
