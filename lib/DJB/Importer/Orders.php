<?php

namespace DJB\Importer;

class Orders extends \DJB\Importer {
	public $post_type = 'djb-order';
	public $page_title = 'Orders';

	public function data() {
		$data = array(
			array(
				'post_title' => 'Krath',
				'legacy_id' => 'Krath',
				'path' => 'dark',
			),
			array(
				'post_title' => 'Obelisk',
				'legacy_id' => 'Obelisk',
				'path' => 'dark',
			),
			array(
				'post_title' => 'Sith',
				'legacy_id' => 'Sith',
				'path' => 'dark',
			),
			array(
				'post_title' => 'Consular',
				'legacy_id' => 'Sith',
				'path' => 'light',
			),
			array(
				'post_title' => 'Guardian',
				'legacy_id' => 'Obelisk',
				'path' => 'light',
			),
			array(
				'post_title' => 'Sentinel',
				'legacy_id' => 'Krath',
				'path' => 'light',
			),
		);
		return $data;
	}//end data
}//end Orders
