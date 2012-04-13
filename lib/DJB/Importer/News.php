<?php

namespace DJB\Importer;

class News extends \DJB\Importer {
	public $post_type = 'post';
	public $page_title = 'News';

	public function data( $count = false ) {
		if( $count ) {
			return \DJB::db('olddjb')->GetOne("SELECT count(*) FROM news");
		}//end if

		$sql = "SELECT n.Member_ID post_author, 
                            (CONVERT(DATETIME, n.ItemDate) + CONVERT(DATETIME, n.ItemTime)) post_date, 
                            n.Body post_content,
                            (CAST(n.Title AS VARCHAR(510))) post_title,
                            (LEFT(n.Body, 255)) post_excerpt,
                            (CASE WHEN n.Comments = 1 THEN 'open' WHEN n.Comments = 0 THEN 'closed' ELSE 'oops!' END) comment_status,
                            n.News_ID legacy_news_id,
                            (CAST(n.URL_Image AS VARCHAR(510))) post_image,
                            n.Category AS post_category
                        FROM News n WITH (readuncommitted)";

		$data = \DJB::db('olddjb')->GetAll( $sql );

		include_once \DJB\WordPress::plugin_dir() . '/external/Textile.php';

		$textile = new \Textile();

		foreach( $data as &$row ) {
			$row['post_content'] = $textile->TextileThis( html_entity_decode( $row['post_content'] ) );

			\DJB\Legacy::translate( $row, 'legacy_id', 'legacy_news_id' );
			\DJB\Legacy::translate( $row, 'user', 'post_author' );

		}//end foreach
		return $data;
	}//end data
}//end Courses
