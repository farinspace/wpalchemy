<?php

include_once 'WPAlchemy/MetaBox.php';
 
// include css to help style our custom meta boxes
// this should be a global stylesheet used by all similar meta boxes
if (is_admin()) wp_enqueue_style('custom_meta_css','/wp-content/themes/twentyten/custom/meta.css');
 
$custom_metabox = new WPAlchemy_MetaBox(array
(
	'id' => '_custom_meta', // underscore prefix hides fields from the custom fields area
	'title' => 'My Custom Custom Meta',
	'template' => TEMPLATEPATH . '/custom/meta.php'
));
 
// add a second custom meta box
$custom_metabox2 = new WPAlchemy_MetaBox(array
(
	'id' => '_custom_meta2',
	'title' => 'My Custom Custom Meta #2',
	'types' => array('page','events'), // added only for pages and to custom post type "events"
	'context' => 'normal', // same as above, defaults to "normal"
	'priority' => 'high', // same as above, defaults to "high"
	'template' => TEMPLATEPATH . '/custom/meta2.php'
));

?>