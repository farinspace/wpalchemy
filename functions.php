<?php

// custom constant (opposite of TEMPLATEPATH)
define('_TEMPLATEURL', WP_CONTENT_URL . '/' . stristr(TEMPLATEPATH, 'themes'));

include_once 'WPAlchemy/MetaBox.php';
 
// include css to style the custom meta boxes, this should be a global
// stylesheet used by all similar meta boxes
if (is_admin()) wp_enqueue_style('custom_meta_css', _TEMPLATEURL . '/custom/meta.css');
 
$custom_metabox = new WPAlchemy_MetaBox(array
(
	'id' => '_custom_meta', // underscore prefix hides fields from the custom fields area
	'title' => 'My Custom Meta',
	'template' => TEMPLATEPATH . '/custom/simple_meta.php',
));
 
// add a second custom meta box
$custom_metabox2 = new WPAlchemy_MetaBox(array
(
	'id' => '_custom_meta2',
	'title' => 'My Custom Meta (full)',
	'types' => array('page','events'), // added only for pages and to custom post type "events"
	'context' => 'normal', // same as above, defaults to "normal"
	'priority' => 'high', // same as above, defaults to "high"
	'template' => TEMPLATEPATH . '/custom/full_meta.php'
));

$custom_checkbox_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_custom_checkbox_meta',
	'title' => 'Checkbox Inputs',
	'template' => TEMPLATEPATH . '/custom/checkbox_meta.php',
));

$custom_radio_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_custom_radio_meta',
	'title' => 'Radio Inputs',
	'template' => TEMPLATEPATH . '/custom/radio_meta.php'
));

$custom_select_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_custom_select_meta',
	'title' => 'Select Inputs',
	'template' => TEMPLATEPATH . '/custom/select_meta.php'
));

/* End of file */