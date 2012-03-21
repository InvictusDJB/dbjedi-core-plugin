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
				'tax_input' => array(
					'djb-path' => array(
						'Dark',
					),
				),
			),
			array(
				'post_title' => 'Obelisk',
				'legacy_id' => 'Obelisk',
				'tax_input' => array(
					'djb-path' => array(
						'Dark',
					),
				),
			),
			array(
				'post_title' => 'Sith',
				'legacy_id' => 'Sith',
				'tax_input' => array(
					'djb-path' => array(
						'Dark',
					),
				),
			),
			array(
				'post_title' => 'Consular',
				'legacy_id' => 'Sith',
				'tax_input' => array(
					'djb-path' => array(
						'Light',
					),
				),
			),
			array(
				'post_title' => 'Guardian',
				'legacy_id' => 'Obelisk',
				'tax_input' => array(
					'djb-path' => array(
						'Light',
					),
				),
			),
			array(
				'post_title' => 'Sentinel',
				'legacy_id' => 'Krath',
				'tax_input' => array(
					'djb-path' => array(
						'Light',
					),
				),
			),
		);
		return $data;
	}//end data
}//end Orders
