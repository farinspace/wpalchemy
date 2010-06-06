<?php

/*

Copyright (c) 2009 Dimas Begunoff, http://www.farinspace.com

Licensed under the MIT license
http://en.wikipedia.org/wiki/MIT_License

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

*/

add_action('admin_head',array(WPAlchemy_MetaBox,'setup_special'));

class WPAlchemy_MetaBox
{
	var $id;
	var $title = 'Custom Meta';
	var $types;
	var $context = 'normal';
	var $priority = 'high';
	var $template;
	var $autosave = TRUE;

	var $exclude_template;
	var $exclude_category_id;
	var $exclude_category;
	var $exclude_tag_id;
	var $exclude_tag;
	var $exclude_post_id;

	var $include_template;
	var $include_category_id;
	var $include_category;
	var $include_tag_id;
	var $include_tag;
	var $include_post_id;

	var $meta;
	var $name;
	var $subname;
	var $lenth = 0;
	var $current = -1;
	var $in_loop = FALSE;
	var $group_tag;
	
	function WPAlchemy_MetaBox($arr)
	{
		$this->meta = array();

		$this->types = array('post','page');

		if (is_array($arr))
		{
			foreach ($arr as $n => $v)
			{
				$this->$n = $v;
			}

			if (empty($this->id)) die('Meta box ID required');

			if (is_numeric($this->id)) die('Meta box ID must be a string');

			if (empty($this->template)) die('Meta box template file required');

			// check for nonarray values
			
			$exc_inc = array
			(
				'exclude_template',
				'exclude_category_id',
				'exclude_category',
				'exclude_tag_id',
				'exclude_tag',
				'exclude_post_id',

				'include_template',
				'include_category_id',
				'include_category',
				'include_tag_id',
				'include_tag',
				'include_post_id'
			);

			foreach ($exc_inc as $v)
			{
				if (!empty($this->$v) AND !is_array($this->$v)) 
				{
					$this->$v = array_map('trim',explode(',',$this->$v));
				}
			}

			add_action('admin_init',array($this,'init'));
		}
		else 
		{
			die('Associative array parameters required');
		}
	}

	function can_output()
	{
		global $post;
		
		$post_id = ($_GET['post']) ? $_GET['post'] : $_POST['post_ID'] ;

		$post_id = (!empty($post) AND $post->ID) ? $post->ID : $post_id ;

		if (!empty($this->exclude_template) OR !empty($this->include_template))
		{
			$template_file = get_post_meta($post_id,'_wp_page_template',TRUE);
		}

		if 
		(
			!empty($this->exclude_category) OR 
			!empty($this->exclude_category_id) OR 
			!empty($this->include_category) OR
			!empty($this->include_category_id)
		)
		{
			$categories = wp_get_post_categories($post_id,'fields=all');
		}

		if 
		(
			!empty($this->exclude_tag) OR 
			!empty($this->exclude_tag_id) OR 
			!empty($this->include_tag) OR
			!empty($this->include_tag_id)
		)
		{
			$tags = wp_get_post_tags($post_id);
		}

		// processing order: "exclude" then "include"
		// processing order: "template" then "category" then "post"

		$can_output = TRUE; // include all

		if 
		(
			!empty($this->exclude_template) OR 
			!empty($this->exclude_category_id) OR 
			!empty($this->exclude_category) OR 
			!empty($this->exclude_tag_id) OR
			!empty($this->exclude_tag) OR
			!empty($this->exclude_post_id) OR
			!empty($this->include_template) OR 
			!empty($this->include_category_id) OR 
			!empty($this->include_category) OR 
			!empty($this->include_tag_id) OR 
			!empty($this->include_tag) OR 
			!empty($this->include_post_id)
		)
		{
			if (!empty($this->exclude_template))
			{
				if (in_array($template_file,$this->exclude_template)) 
				{
					$can_output = FALSE;
				}
			}

			if (!empty($this->exclude_category_id))
			{
				foreach ($categories as $cat)
				{
					if (in_array($cat->term_id,$this->exclude_category_id)) 
					{
						$can_output = FALSE;
						break;
					}
				}
			}

			if (!empty($this->exclude_category))
			{
				foreach ($categories as $cat)
				{
					if 
					(
						in_array($cat->slug,$this->exclude_category) OR
						in_array($cat->name,$this->exclude_category)
					) 
					{
						$can_output = FALSE;
						break;
					}
				}
			}

			if (!empty($this->exclude_tag_id))
			{
				foreach ($tags as $tag)
				{
					if (in_array($tag->term_id,$this->exclude_tag_id)) 
					{
						$can_output = FALSE;
						break;
					}
				}
			}

			if (!empty($this->exclude_tag))
			{
				foreach ($tags as $tag)
				{
					if 
					(
						in_array($tag->slug,$this->exclude_tag) OR 
						in_array($tag->name,$this->exclude_tag)
					) 
					{
						$can_output = FALSE;
						break;
					}
				}
			}

			if (!empty($this->exclude_post_id))
			{
				if (in_array($post_id,$this->exclude_post_id)) 
				{
					$can_output = FALSE;
				}
			}

			// excludes are not set use "include only" mode

			if 
			(
				empty($this->exclude_template) AND 
				empty($this->exclude_category_id) AND 
				empty($this->exclude_category) AND 
				empty($this->exclude_tag_id) AND 
				empty($this->exclude_tag) AND 
				empty($this->exclude_post_id)
			)
			{
				$can_output = FALSE;
			}

			if (!empty($this->include_template))
			{
				if (in_array($template_file,$this->include_template)) 
				{
					$can_output = TRUE;
				}
			}

			if (!empty($this->include_category_id))
			{
				foreach ($categories as $cat)
				{
					if (in_array($cat->term_id,$this->include_category_id)) 
					{
						$can_output = TRUE;
						break;
					}
				}
			}

			if (!empty($this->include_category))
			{
				foreach ($categories as $cat)
				{
					if 
					(
						in_array($cat->slug,$this->include_category) OR
						in_array($cat->name,$this->include_category)
					)
					{
						$can_output = TRUE;
						break;
					}
				}
			}

			if (!empty($this->include_tag_id))
			{
				foreach ($tags as $tag)
				{
					if (in_array($tag->term_id,$this->include_tag_id)) 
					{
						$can_output = TRUE;
						break;
					}
				}
			}

			if (!empty($this->include_tag))
			{
				foreach ($tags as $tag)
				{
					if 
					(
						in_array($tag->slug,$this->include_tag) OR
						in_array($tag->name,$this->include_tag)
					) 
					{
						$can_output = TRUE;
						break;
					}
				}
			}

			if (!empty($this->include_post_id))
			{
				if (in_array($post_id,$this->include_post_id)) 
				{
					$can_output = TRUE;
				}
			}
		}

		return $can_output;
	}

	function init()
	{
		if ($this->can_output())
		{
			foreach ($this->types as $type) 
			{
				add_meta_box($this->id . '_metabox', $this->title, array($this,'setup'), $type, $this->context, $this->priority);
			}

			add_action('save_post',array($this,'save'));
		}
	}
	 
	function setup()
	{
		global $post;

		$mb =& $this;
		$metabox =& $this;
		
		$id = $this->id;

		$meta = $this->the_meta();

		include $this->template;
	 
		// create a nonce for verification
		echo '<input type="hidden" name="'. $this->id .'_nonce" value="' . wp_create_nonce($this->id) . '" />';
	}

	function setup_special()
	{
// include javascript for special functionality
echo <<<'WPAMB'
<style type="text/css"> .wpa_group.tocopy { display:none; } </style>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(function($)
{
	$(document).click(function(e)
	{		
		var elem = $(e.target);

		if (elem.attr('class') && elem.filter('[class*=dodelete]').length)
		{
			e.preventDefault();

			var the_name = elem.attr('class').match(/dodelete-(\w*)/i);
			the_name = (the_name && the_name[1]) ? the_name[1] : null ;

			if (confirm('This action can not be undone, are you sure?'))
			{
				if (the_name)
				{
					$('.wpa_group-'+ the_name).not('.tocopy').remove();
				}
				else
				{
					elem.parents('.wpa_group').remove();
				}
			}

			$('.docopy-'+the_name).trigger('click');
		}
	});
	
	$('[class*=docopy-]').click(function(e)
	{
		e.preventDefault();

		var the_name = $(this).attr('class').match(/docopy-(\w*)/i)[1];

		var the_group = $('.wpa_group-'+ the_name +':first.tocopy');
		
		var the_clone = the_group.clone().removeClass('tocopy');

		the_group.find('input, textarea, select, button, label').each(function(i,elem)
		{
			var the_name = $(elem).attr('name');

			if (undefined != the_name)
			{
				var the_match = the_name.match(/\[(\d+)\]/i);
				the_name = the_name.replace(the_match[0],'['+(+the_match[1]+1)+']');
				$(elem).attr('name',the_name);
			}
		});

		if ($(this).hasClass('ontop'))
		{
			$('.wpa_group-'+ the_name +':first').before(the_clone);
		}
		else
		{
			the_group.before(the_clone);
		}
	});
});
/* ]]> */
</script>
WPAMB;
	}

	function the_meta()
	{
		global $post;

		$this->meta = get_post_meta($post->ID, $this->id, TRUE);

		// bug: when exporting then importing from wp, wp will double serialize the postmeta value
		if (!is_array($this->meta)) $this->meta = unserialize($this->meta);

		return $this->meta;
	}

	function the_id()
	{
		echo $this->get_the_id();
	}

	function get_the_id()
	{
		return $this->id;
	}

	function the_field($n)
	{
		if ($this->in_loop) $this->subname = $n;
		else $this->name = $n;
	}

	function have_value($n=NULL)
	{
		if ($this->get_the_value($n)) return TRUE;
		
		return FALSE;
	}

	function the_value($n=NULL)
	{
		echo $this->get_the_value($n);
	}

	function get_the_value($n=NULL)
	{
		if ($this->in_loop)
		{
			if(!empty($this->meta[$this->name]))
			{
				$n = is_null($n) ? $this->subname : $n ;

				if(!is_null($n))
				{
					if(!empty($this->meta[$this->name][$this->current][$n]))
					{
						return $this->meta[$this->name][$this->current][$n];
					}
				}
				else
				{
					if(!empty($this->meta[$this->name][$this->current]))
					{
						return $this->meta[$this->name][$this->current];
					}
				}
			}
		}
		else
		{
			$n = is_null($n) ? $this->name : $n ;

			if(!empty($this->meta[$n])) return $this->meta[$n];
		}

		return NULL;
	}

	function the_name($n=NULL)
	{
		echo $this->get_the_name($n);
	}

	function get_the_name($n=NULL)
	{
		if ($this->in_loop)
		{
			$n = is_null($n) ? $this->subname : $n ;

			if (!is_null($n)) return $this->id . '[' . $this->name . '][' . $this->current . '][' . $n . ']' ;

			return $this->id . '[' . $this->name . '][' . $this->current . ']' ;
		}
		else
		{
			$n = is_null($n) ? $this->name : $n ;

			return $this->id . '[' . $n . ']';
		}
	}

	function the_index()
	{
		echo $this->get_the_index();
	}

	function get_the_index()
	{
		return $this->in_loop ? $this->current : 0 ;
	}

	function is_first()
	{
		if ($this->in_loop AND $this->current == 0) return TRUE;

		return FALSE;
	}

	function is_last()
	{
		if ($this->in_loop AND ($this->current+1) == $this->length) return TRUE;

		return FALSE;
	}

	function is_value($v=NULL)
	{
		if ($this->get_the_value() == $v) return TRUE;

		return FALSE;
	}

	function the_group_open($t='div')
	{
		echo $this->get_the_group_open($t);
	}

	function get_the_group_open($t='div')
	{
		$this->group_tag = $t;

		$css_class = array('wpa_group','wpa_group-'. $this->name);

		if ($this->is_first())
		{
			array_push($css_class,'first');
		}

		if ($this->is_last())
		{
			array_push($css_class,'last');

			if ($this->in_loop == 'multi')
			{
				array_push($css_class,'tocopy');
			}
		}

		return '<'. $t .' class="'. implode(' ',$css_class) .'">';
	}

	function the_group_close()
	{
		echo $this->get_the_group_close();
	}

	function get_the_group_close()
	{
		return '</'. $this->group_tag .'>';
	}

	function have_fields_and_multi($n)
	{
		$this->in_loop = 'multi';
		return $this->loop($n,NULL,2);
	}

	function have_fields_and_one($n)
	{
		$this->in_loop = 'single';
		return $this->loop($n,NULL,1);
	}

	function have_fields($n,$length=NULL)
	{
		$this->in_loop = 'normal';
		return $this->loop($n,$length);
	}

	function loop($n,$length=NULL,$and_one=0)
	{
		if (!$this->in_loop) 
		{
			$this->in_loop = TRUE;
		}
		
		$this->name = $n;

		$length = is_null($length) ? count(!empty($this->meta[$n])?$this->meta[$n]:NULL) : $length ;
		
		$this->length = $length;

		if ($and_one)
		{
			if ($length == 0)
			{
				$this->length = $and_one;
			}
			else
			{
				$this->length = $length+1;
			}
		}

		$this->current++;

		if ($this->current < $this->length)
		{
			$this->subname = NULL;

			$this->fieldtype = NULL;

			return TRUE;
		}
		else if ($this->current == $this->length)
		{
			$this->name = NULL;

			$this->current = -1;
		}

		$this->in_loop = FALSE;

		return FALSE;
	}
	 
	function save($post_id) 
	{
		// check autosave
		if (defined('DOING_AUTOSAVE') AND DOING_AUTOSAVE AND !$this->autosave) return $post_id;
	 
		// make sure data came from our meta box, verify nonce
		if (!wp_verify_nonce($_POST[$this->id.'_nonce'],$this->id)) return $post_id;
	 
		// check user permissions
		if ($_POST['post_type'] == 'page') 
		{
			if (!current_user_can('edit_page', $post_id)) return $post_id;
		}
		else 
		{
			if (!current_user_can('edit_post', $post_id)) return $post_id;
		}
	 
		// authentication passed, save data
	 
		$current_data = get_post_meta($post_id, $this->id, TRUE);	
	 
		$new_data = $_POST[$this->id];
	 
		WPAlchemy_MetaBox::clean($new_data);
	 
		if ($current_data) 
		{
			if (is_null($new_data)) delete_post_meta($post_id,$this->id);
			else update_post_meta($post_id,$this->id,$new_data);
		}
		elseif (!is_null($new_data))
		{
			add_post_meta($post_id,$this->id,$new_data,TRUE);
		}
	 
		return $post_id;
	}
	 
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
					if (trim($arr[$i]) == '') 
					{
						unset($arr[$i]);
					}
				}
			}

			if (!count($arr)) 
			{
				$arr = NULL;
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
