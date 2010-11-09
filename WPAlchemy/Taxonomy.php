<?php

/**
 * @author		Dimas Begunoff, Robbert LAST_NAME
 * @copyright	Copyright (c) 2009, Dimas Begunoff, http://farinspace.com
 * @license		http://en.wikipedia.org/wiki/MIT_License The MIT License
 * @package		WPAlchemy
 * @version		1.4
 * @link		http://github.com/farinspace/wpalchemy
 * @link		http://farinspace.com
 */

class WPAlchemy_Taxonomy
{
	var $taxonomy;

	var $template;

	function WPAlchemy_Taxonomy($arr)
	{
		if (is_array($arr))
		{
			foreach ($arr as $n => $v)
			{
				$this->$n = $v;
			}

			if (empty($this->template)) die('Taxonomy template file required');

			add_action('admin_init', array($this,'_init'));
		}
		else 
		{
			die('Associative array parameters required');
		}
	}

	function _init()
	{
		add_action('edit_category_form_fields', array($this, '_setup'));

		add_action('add_category', array($this, '_save'));
		
		add_action('edit_category', array($this, '_save'));

		//add_action('edited_category ', array($this, '_save'));

		// related article: http://www.strangework.com/2010/07/01/how-to-save-taxonomy-meta-data-as-an-options-array-in-wordpress/
	}

	function _setup($term)
	{
		if ($this->taxonomy == $term->taxonomy)
		{
			// shortcuts
			$tx =& $this;
			$taxonomy =& $this;

			// recall data

			include $this->template;
		}

		//var_dump($term);
	}

	function _save($term_id)
	{
		$term = get_term($term_id, $this->taxonomy);

		// check all post vars, skip keys: "action", "submit", and any starting with "_wp"
		// possibly also skip "tag_ID", "taxonomy", "name", "slug", "parent", "description"

		var_dump($_POST);

		//var_dump($term);
		exit;
	}
}

/* End of file */