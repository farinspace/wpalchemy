<?php

/**
 * @author   	Dimas Begunoff
 * @copyright	Copyright (c) 2011, Dimas Begunoff, http://farinspace.com/
 * @license  	http://en.wikipedia.org/wiki/MIT_License The MIT License
 * @package  	WPAlchemy
 * @version  	0.2.1
 * @link     	http://github.com/farinspace/wpalchemy/
 * @link     	http://farinspace.com/
 */

 class WPAlchemy_MediaAccess
{
	/**
	 * User defined identifier for the css class name of the HTML button element,
	 * used when pairing the field and button elements
	 *
	 * @since	0.1
	 * @access	public
	 * @var		string required
	 */
	public $button_class_name = 'mediabutton';

	/**
	 * User defined identifier for the css class name of the HTML field element,
	 * used when pairing the field and button elements
	 *
	 * @since	0.1
	 * @access	public
	 * @var		string required
	 */
	public $field_class_name = 'mediafield';

	/**
	 * User defined label for the insert button in the media upload box, this
	 * can be set once or per field and button pair.
	 *
	 * @since	0.1
	 * @access	public
	 * @var		string optional
	 * @see		setInsertButtonLabel()
	 */
	public $insert_button_label = null;

	/**
	 * Used to track the current groupname for pairing a field and button.
	 *
	 * @since	0.1
	 * @access	private
	 * @var		string
	 * @see		setGroupName()
	 */
	private $groupname = null;

	/**
	 * Used to track the current tab for the media upload box.
	 *
	 * @since	0.1
	 * @access	private
	 * @var		string
	 * @see		setTab()
	 */
	private $tab = null;

	/**
	 * MediaAccess class
	 *
	 * @since	0.1
	 * @access	public
	 * @param	array $a
	 */
	public function __construct(array $a = array())
	{
		foreach ($a as $n => $v)
		{
			$this->$n = $v;
		}

		if ( ! defined('WPALCHEMY_SEND_TO_EDITOR_ENABLED'))
		{
			add_action('admin_footer', array($this, 'init'));

			define('WPALCHEMY_SEND_TO_EDITOR_ENABLED', true);
		}
	}

	/**
	 * Used to generate short unique/random names
	 *
	 * @since	0.1
	 * @access	public
	 * @return	string
	 */
	private function getName()
	{
		return substr(md5(microtime() . rand()), rand(0,25), 6);
	}

	/**
	 * Used to set the insert button label in the media upload box, this can be
	 * set once or per field and button pair.
	 *
	 * @since	0.1
	 * @access	public
	 * @param	string $label button label/title
	 * @return	object $this
	 * @see		setGroupName()
	 */
	public function setInsertButtonLabel($label = 'Insert')
	{
		$this->insert_button_label = $label;

		return $this;
	}

	public function setTab($name)
	{
		$this->tab = $name;

		$this;
	}

	/**
	 * Used before calls to getField(), getButton() or getButtonClass() to set
	 * the groupname to pair a field and button element.
	 *
	 * @since	0.1
	 * @access	public
	 * @param	string $name unique name per pair of field and button
	 * @return	object $this
	 * @see		setInsertButtonLabel()
	 */
	public function setGroupName($name)
	{
		$this->groupname = $name;

		return $this;
	}

	/**
	 * Used to insert a form field of type "text", this should be paired with a
	 * button element. The name and value attributes are required.
	 *
	 * @since	0.1
	 * @access	public
	 * @param	array $attr INPUT tag parameters
	 * @return	HTML
	 * @see		getButton()
	 */
	public function getField(array $attr)
	{
		$groupname = isset($attr['groupname']) ? $attr['groupname'] : $this->groupname ;
		
		$attr_default = array
		(
			'type' => 'text',
			'class' => $this->field_class_name . '-' . $groupname,
		);

		###

		if (isset($attr['class']))
		{
			$attr['class'] = $attr_default['class'] . ' ' . trim($attr['class']);
		}

		$attr = array_merge($attr_default, $attr);

		###

		$elem_attr = array();

		foreach ($attr as $n => $v)
		{
			array_push($elem_attr, $n . '="' . $v . '"');
		}

		###

		return '<input ' . implode(' ', $elem_attr) . '/>';
	}

	/**
	 * Used to get the link used for the button element. If creating custom
	 * buttons, this method should be used to get the link needed for proper
	 * functionality.
	 *
	 * @since	0.1
	 * @access	public
	 * @param	string $tab name that the media upload box will initially load
	 * @return	string link
	 * @see		getButtonClass(), getButton()
	 */
	public function getButtonLink($tab = null)
	{
		// this is set even for new posts/pages
		global $post_ID; //wp

		$tab = ! empty($tab) ? $tab : $this->tab ;

		$tab = ! empty($tab) ? $tab : 'library' ;
		
		return 'media-upload.php?post_id=' . $post_ID . '&tab=' . $tab . '&TB_iframe=1';
	}

	/**
	 * Used to get the CSS class name(s) used for the button element. If
	 * creating custom buttons, this method should be used to get the css class
	 * names needed for proper functionality.
	 *
	 * @since	0.1
	 * @access	public
	 * @param	string $groupname name used when pairing a text field and button
	 * @return	string css class(es)
	 * @see		getButtonLink(), getButton()
	 */
	public function getButtonClass($groupname = null)
	{
		$groupname = isset($groupname) ? $groupname : $this->groupname ;
		
		return $this->button_class_name . '-' . $groupname . ' thickbox';
	}

	/**
	 * Used to get the CSS class name used for the field element. If
	 * creating a custom field, this method should be used to get the css class
	 * name needed for proper functionality.
	 *
	 * @since	0.2
	 * @access	public
	 * @param	string $groupname name used when pairing a text field and button
	 * @return	string css class(es)
	 * @see		getButtonClass(), getField()
	 */
	public function getFieldClass($groupname = null)
	{
		$groupname = isset($groupname) ? $groupname : $this->groupname ;

		return $this->field_class_name . '-' . $groupname;
	}

	/**
	 * Used to insert a WordPress styled button, should be paired with a text
	 * field element.
	 *
	 * @since	0.1
	 * @access	public
	 * @return	HTML
	 * @see		getField(), getButtonClass(), getButtonLink()
	 */
	public function getButton(array $attr = array())
	{
		$groupname = isset($attr['groupname']) ? $attr['groupname'] : $this->groupname ;

		$tab = isset($attr['tab']) ? $attr['tab'] : $this->tab ;
		
		$attr_default = array
		(
			'label' => 'Add Media',
			'href' => $this->getButtonLink($tab),
			'class' => $this->getButtonClass($groupname) . ' button',
		);

		if (isset($this->insert_button_label))
		{
			$attr_default['class'] .= " {label:'" . $this->insert_button_label . "'}";
		}

		###

		if (isset($attr['class']))
		{
			$attr['class'] = $attr_default['class'] . ' ' . trim($attr['class']);
		}

		$attr = array_merge($attr_default, $attr);

		$label = $attr['label'];

		unset($attr['label']);

		###

		$elem_attr = array();

		foreach ($attr as $n => $v)
		{
			array_push($elem_attr, $n . '="' . $v . '"');
		}

		###

		return '<a ' . implode(' ', $elem_attr) . '>' . $label . '</a>';
	}

	/**
	 * Used to insert global STYLE or SCRIPT tags into the footer, called on
	 * WordPress admin_footer action.
	 *
	 * @since	0.1
	 * @access	public
	 * @return	HTML/Javascript
	 */
	public function init()
	{
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL ;

		$file = basename(parse_url($uri, PHP_URL_PATH));

		if ($uri AND in_array($file, array('post.php', 'post-new.php')))
		{
			// include javascript for special functionality
			?><script type="text/javascript">
			/* <![CDATA[ */

				var interval = null;

				jQuery(function($)
				{
					if (typeof send_to_editor === 'function')
					{
						var wpalchemy_insert_button_label = '';

						var wpalchemy_mediafield = null;

						var wpalchemy_send_to_editor_default = send_to_editor;

						send_to_editor = function(html)
						{
							clearInterval(interval);

							if (wpalchemy_mediafield)
							{
								var src = html.match(/src=['|"](.*?)['|"] alt=/i);
								src = (src && src[1]) ? src[1] : '' ;

								var href = html.match(/href=['|"](.*?)['|"]/i);
								href = (href && href[1]) ? href[1] : '' ;

								var url = src ? src : href ;

								wpalchemy_mediafield.val(url);

								// reset insert button label
								setInsertButtonLabel(wpalchemy_insert_button_label);

								wpalchemy_mediafield = null;
							}
							else
							{
								wpalchemy_send_to_editor_default(html);
							}

							tb_remove();
						}

						function getInsertButtonLabel()
						{
							return $('#TB_iframeContent').contents().find('.media-item .savesend input[type=submit], #insertonlybutton').val();
						}

						function setInsertButtonLabel(label)
						{
							$('#TB_iframeContent').contents().find('.media-item .savesend input[type=submit], #insertonlybutton').val(label);
						}

						$('[class*="<?php echo $this->button_class_name; ?>"]').on('click', function()
						{
							var name = $(this).attr('class').match(/<?php echo $this->button_class_name; ?>-([a-zA-Z0-9_-]*)/i);
							name = (name && name[1]) ? name[1] : '' ;

							var data = $(this).attr('class').match(/({.*})/i);
							data = (data && data[1]) ? data[1] : '' ;
							data = eval("(" + (data.indexOf('{') < 0 ? '{' + data + '}' : data) + ")");

							wpalchemy_mediafield = $('.<?php echo $this->field_class_name; ?>-' + name, $(this).closest('.postbox'));

							function iframeSetup()
							{
								if ($('#TB_iframeContent').contents().find('.media-item .savesend input[type=submit], #insertonlybutton').length)
								{
									// run once
									if ( ! wpalchemy_insert_button_label.length)
									{
										wpalchemy_insert_button_label = getInsertButtonLabel();
									}

									setInsertButtonLabel((data && data.label)?data.label:'Insert');

									// tab "type" needs a timer in order to properly change the button label

									//clearInterval(interval);

									// setup iframe.load as soon as it becomes available
									// prevent multiple binds
									//$('#TB_iframeContent').unbind('load', iframeSetup).bind('load', iframeSetup);
								}
							}

							clearInterval(interval);

							interval = setInterval(iframeSetup, 500);
						});
					}
				});

			/* ]]> */
			</script><?php
		}
	}
}

/* End of file */