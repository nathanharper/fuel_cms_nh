<?php
require_once(APPPATH . 'config/admin.php');

// You'll need to symlink to the css and js files...
// Also, include the project's main jquery js/css files.

$admin_config = array(
	// js and css automatically loaded in Master class
	'admin_js' => array(
		'admin' => 'admin.js',
	),

	'admin_css' => array(
		'admin' => 'admin.css',
	),

	'admin_tabs' => array(),
);

$project_config = get_admin_config();

return array_merge_recursive($project_config, $admin_config);

// Sample config file below...
// Don't 
/*
function get_admin_config() {
	return array(
		// js and css automatically loaded in Master class
		'admin_js' => array(
			'jquery' => 'jquery-1.6.2.min.js',
			'jqcustom' => 'jquery-custom.min.js',
			'track' => 'track_field.js',
			'admin' => 'admin.js',
		),

		'admin_css' => array(
			'admin' => 'admin.css',
			'jqcustom' => 'jquery-custom.css',
		),

		'admin_tabs' => array(
			'artists' => '\\Model_Artist',
			'releases' => '\\Model_Release',
			'blogposts' => '\\Model_Blogpost',
			'gigs' => '\\Model_Gig',
			'audiofiles' => '\\Model_Audiofile',
			'transactions' => '\\Model_Transaction',
		),
	);
}
*/
