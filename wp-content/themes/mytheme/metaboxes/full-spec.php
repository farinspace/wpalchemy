<?php

$full_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_full_meta',
	'title' => 'Full Inputs',
	'types' => array('page', 'events'), // added only for pages and to custom post type "events"
	'context' => 'normal', // same as above, defaults to "normal"
	'priority' => 'high', // same as above, defaults to "high"
	'template' => get_stylesheet_directory() . '/metaboxes/full-meta.php'
));

/* eof */