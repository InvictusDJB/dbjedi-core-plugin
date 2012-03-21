<?php

namespace DJB\Admin\Taxonomy;

class Path extends \DJB\Admin\Taxonomy {
	public static $taxonomy = 'djb-path';
	public static $object_type = NULL;
	public static $args = array(
		'label' => 'Path',
		'labels' => array(
			'name' => 'Paths',
			'singular_name' => 'Path',
			'search_items' => 'Search Paths',
			'popular_item' => 'Popular Paths',
			'all_items' => 'All Paths',
			'edit_item' => 'Edit Path',
			'update_item' => 'Update Path',
			'add_new_item' => 'Add New Path',
			'new_item_name' => 'New Path Name',
			'separate_items_with_commas' => 'Separate paths with commas',
			'add_or_remove_items' => 'Add or remove paths',
			'choose_from_most_used' => 'Choose from the most used paths',
		),
	);
}//end class \DJB\Admin\Taxonomy\Path
