<?php

namespace DJB;

class Admin {
	/**
	 * initialize the admin menu
	 */
	public static function admin_menu() {
		add_menu_page( 'DJB Data', 'DJB Data', 'manage_options', 'djb-data', array( 'DJB\Admin', 'page_data' ) );
		add_menu_page( 'DJB Importer', 'DJB Importer', 'manage_options', 'djb-data-importer', array( 'DJB\Admin', 'importer' ) );

		$importers = array(
			'djb-medal' => 'Medals',
			'djb-order' => 'Orders',
			'djb-species' => 'Species',
			'djb-rank' => 'Ranks',
			'djb-users' => 'Users',
		);

		foreach( $importers as $slug => $name ) {
			add_submenu_page( 'djb-data-importer', $name, $name, 'manage_options', 'djb-data-importer-' . $slug, array( 'DJB\Admin', 'importer_' . str_replace( '-', '_', $slug ) ) );
		}//end foreach
	}//end admin_menu

	public static function page_data() {
	}//end page_data

	public static function importer() {
	}//end importer

	public static function importer_djb_medal() {
		$importer = new Importer\Medals;

		$importer->page();
	}//end importer_medal

	public static function importer_djb_order() {
		$importer = new Importer\Orders;

		$importer->page();
	}//end importer_order

	public static function importer_djb_rank() {
		$importer = new Importer\Ranks;

		$importer->page();
	}//end importer_rank

	public static function importer_djb_species() {
		$importer = new Importer\Species;

		$importer->page();
	}//end importer_species

	public static function importer_djb_users() {
		$importer = new Importer\Users;

		$importer->page();
	}//end importer_users
}//end DJB\Plugin
