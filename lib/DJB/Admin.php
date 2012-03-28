<?php

namespace DJB;

class Admin {
	public static function acc() {}//end acc

	/**
	 * initialize the admin menu
	 */
	public static function admin_menu() {
		add_menu_page( 'Academy', 'Academy', 'manage_options', 'djb-academy', array( 'DJB\Admin', 'shadow_academy' ) );
		add_menu_page( 'ACC', 'ACC', 'manage_options', 'djb-acc', array( 'DJB\Admin', 'acc' ) );
		add_menu_page( 'MAA', 'MAA', 'manage_options', 'djb-maa', array( 'DJB\Admin', 'maa' ) );
		add_menu_page( 'DJB Data', 'DJB Data', 'manage_options', 'djb-data', array( 'DJB\Admin', 'page_data' ) );
		add_menu_page( 'DJB Importer', 'DJB Importer', 'manage_options', 'djb-data-importer', array( 'DJB\Admin', 'importer' ) );

		$importers = static::importers();

		add_submenu_page( 'djb-data-importer', 'Dashboard', 'Dashboard', 'manage_options', 'djb-data-importer', array( 'DJB\Admin', 'importer' ) );

		foreach( $importers as $slug => $name ) {
			$object = self::importer_object( $name );

			add_submenu_page( 
				'djb-data-importer', 
				$name, 
				$name, 
				'manage_options', 
				'djb-data-importer-' . $slug, 
				array( 
					$object, 
					'page'
				) 
			);
		}//end foreach
	}//end admin_menu

	public static function importer() {
		include WordPress::template_dir() . '/admin/importer.php';
	}//end importer

	public static function importer_object( $which ) {
		static $objects = array();

		if( ! $objects[ $which ] ) {
			$objects[ $which ] = Importer::get( $which );
		}//end if

		return $objects[ $which ];
	}//end importer_object

	public static function importer_dependencies() {
		$importers = array(
			'djb-course' => array(
				'djb-users' => array(
				),
				'djb-department' => array(
				),
			),
			'djb-department' => array(
			),
			'djb-degree' => array(
				'djb-course' => array(
				),
			),
			'djb-medal' => array(
			),
			'djb-rank' => array(
				'djb-order' => array(
				),
				'djb-medal' => array(
				),
			),
			'djb-species' => array(
				'djb-medal' => array(
				),
			),
			'djb-users' => array(
				'djb-rank' => array(
				),
			),
		);

		return $importers;
	}//end importers

	public static function importers() {
		$importers = array(
			'djb-course' => 'Courses',
			'djb-degree' => 'Degrees',
			'djb-department' => 'Departments',
			'djb-medal' => 'Medals',
			'djb-order' => 'Orders',
			'djb-species' => 'Species',
			'djb-rank' => 'Ranks',
			'djb-users' => 'Users',
		);

		return $importers;
	}//end importers

	public static function maa() {}//end maa

	public static function page_data() {}//end page_data

	public static function shadow_academy() {}//end shadow_academy

}//end DJB\Plugin
