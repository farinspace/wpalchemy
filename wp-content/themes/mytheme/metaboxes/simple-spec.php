<?php

$custom_metabox = $simple_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_custom_meta',
	'title' => 'My Custom Meta',
	'template' => get_stylesheet_directory_uri() . '/metaboxes/simple-meta.php',
));

/* eof */