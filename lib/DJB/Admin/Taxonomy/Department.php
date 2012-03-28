<?php

namespace DJB\Admin\Taxonomy;

class Department extends \DJB\Admin\Taxonomy {
	public static $taxonomy = 'djb-department';
	public static $object_type = NULL;
	public static $args = array(
		'label' => 'Department',
		'labels' => array(
			'name' => 'Departments',
			'singular_name' => 'Department',
			'search_items' => 'Search Departments',
			'popular_item' => 'Popular Departments',
			'all_items' => 'All Departments',
			'edit_item' => 'Edit Department',
			'update_item' => 'Update Department',
			'add_new_item' => 'Add New Department',
			'new_item_name' => 'New Department Name',
			'separate_items_with_commas' => 'Separate departments with commas',
			'add_or_remove_items' => 'Add or remove departments',
			'choose_from_most_used' => 'Choose from the most used departments',
		),
	);
}//end class \DJB\Admin\Taxonomy\Department
