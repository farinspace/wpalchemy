<?php
include_once WP_CONTENT_DIR . '/wpalchemy/MediaAccess.php';
$wpalchemy_media_access = new WPAlchemy_MediaAccess();

$custom_metabox = $media_mb = new WPAlchemy_MetaBox(array
(
	'id' => '_custom_meta',
	'title' => 'My Custom Media Meta',
	'types' => array('book'),
	'template' => get_stylesheet_directory() . '/metaboxes/media-meta.php',
));

/* eof */
