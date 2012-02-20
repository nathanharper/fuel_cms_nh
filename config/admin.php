<?php
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