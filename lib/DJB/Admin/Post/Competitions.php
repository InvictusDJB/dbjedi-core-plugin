<?php

namespace DJB\Admin\Post;

class Competitions extends \DJB\Admin\Post {
	public static $post_type = 'djb-competition';
	public static $parent_menu = 'djb-maa';
	public static $plural = 'Competitions';
	public static $singular = 'Competition';
	public static $supports = array(
		'title',
		'editor',
		'custom-fields',
		'revisions',
	);
}//end class
