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

	var $_prefix = 'wpalchemy_taxonomy_';

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
		// initial direction for saving data thanks to Brad Williams
		// http://www.strangework.com/2010/07/01/how-to-save-taxonomy-meta-data-as-an-options-array-in-wordpress/
		
		add_action('edit_category_form_fields', array($this, '_setup'));

		add_action('add_category', array($this, '_save'));
		
		add_action('edit_category', array($this, '_save'));

		add_filter('get_term', array($this, '_get_term'));
	}

	function _get_term($term)
	{
		if ($this->taxonomy == $term->taxonomy)
		{
			$data = get_option($this->_prefix . $this->taxonomy);

			if (isset($data[$term->term_id]))
			{
				foreach ($data[$term->term_id] as $n => $v)
				{
					// do not overwrite default values
					if (isset($term->$n))
					{
						$term->{'_' . $n} = $v;
					}
					else
					{
						$term->{$n} = $v;
					}
				}
			}
		}

		return $term;
	}

	function _setup($term)
	{
		if ($this->taxonomy == $term->taxonomy)
		{
			// shortcuts
			$tx =& $this;
			$taxonomy =& $this;
			// $term

			include $this->template;
		}
	}

	function _save($term_id)
	{
		$term = get_term($term_id, $this->taxonomy, ARRAY_A);

		if ($this->taxonomy == $term['taxonomy'])
		{
			$new_data = array();

			// reserved field names
			$keys = array('action', 'submit', 'tag_ID', 'slug', 'name', 'description', 'taxonomy', 'parent');

			foreach ($_POST as $n => $v)
			{
				if (in_array($n, $keys) OR '_wp' == substr($n, 0, 3)) continue;

				$new_data[$n] = $v;
			}

			$data = get_option($this->_prefix . $this->taxonomy);

			if ( ! is_array($data)) $data = array();

			$data[$term_id] = $new_data;

			update_option($this->_prefix . $this->taxonomy, $data);

			//echo '<pre>'; var_dump($_POST); echo '</pre>'; exit;
		}
	}

	// todo: put this function in a global helper file, function needs array key
	// preservation flag
	
	/**
	 * Cleans an array, removing blank ('') values
	 *
	 * @static
	 * @since	1.0
	 * @access	public
	 * @param	array the array to clean (passed by reference)
	 */
	function clean(&$arr)
	{
		if (is_array($arr))
		{
			foreach ($arr as $i => $v)
			{
				if (is_array($arr[$i]))
				{
					WPAlchemy_MetaBox::clean($arr[$i]);

					if (!count($arr[$i]))
					{
						unset($arr[$i]);
					}
				}
				else
				{
					if ('' == trim($arr[$i]) OR is_null($arr[$i]))
					{
						unset($arr[$i]);
					}
				}
			}

			if (!count($arr))
			{
				$arr = array();
			}
			else
			{
				$keys = array_keys($arr);

				$is_numeric = TRUE;

				foreach ($keys as $key)
				{
					if (!is_numeric($key))
					{
						$is_numeric = FALSE;
						break;
					}
				}

				if ($is_numeric)
				{
					$arr = array_values($arr);
				}
			}
		}
	}
}

/* 
	Sample implementation (delete this on final release):

	include_once 'WPAlchemy/Taxonomy.php';

	$tax = new WPAlchemy_Taxonomy(array
	(
		'taxonomy' => 'category',
		'template' => TEMPLATEPATH . '/custom/custom_tax.php',
	));
*/

/* End of file */