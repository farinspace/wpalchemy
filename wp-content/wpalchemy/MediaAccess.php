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
			// Ensure the media upload scripts and styles are added
			add_action( "admin_print_scripts", array( $this, "enqueueAdminScripts") );
			// Ensure send to editor button is added.
			add_filter( "get_media_item_args", array( $this, "getMediaItemArgs" ) );
			// Initialize the footer script
			add_action('admin_footer', array($this, 'init'));

			define('WPALCHEMY_SEND_TO_EDITOR_ENABLED', true);
		}
		
		
	}

	/**
	 * Used to ensure the Insert button is added to media upload panel
	 * in case the editor isn't present on the screen.
	 *
	 * @since 0.2.1
	 * @access public
	 * @param array $args Arguments for media upload
	 * @return $args
	 */
	function getMediaItemArgs( $args ) {
		$args['send'] = true;
		return $args;
	}

	/**
	 * Used to enqueue media upload scripts
	 * in case the editor isn't present on the screen.
	 *
	 * @since 0.2.1
	 * @access public
	 */
	public function enqueueAdminScripts() 
	{
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
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
			'class' => $this->getButtonClass($groupname) . ' button'
		);

		if (isset($this->insert_button_label))
		{
			$attr_default['data-button-label'] = $this->insert_button_label;
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

				jQuery( function( $ )
				{

					if ( typeof send_to_editor === 'function' )
					{
						var wpalchemy_insert_button_label = '',

						 	wpalchemy_mediafield = null,

						 	wpalchemy_send_to_editor_default = send_to_editor,

						 	$trigger, // We'll be setting this during the button's 'click' event.

						 	$doc = $( document ) // Global caching of document element, we'll bind to it.
						 ;

						send_to_editor = function( html )
						{
							if ( wpalchemy_mediafield )
							{
								var src = html.match(/src=['|"](.*?)['|"] alt=/i),
								
									href = html.match(/href=['|"](.*?)['|"]/i),

									url
								;

								src = ( src && src[ 1 ] ) ? src[ 1 ] : '';
								href = ( href && href[ 1 ] ) ? href[ 1 ] : '';
								url = src || href;

								wpalchemy_mediafield.val(url);

								// reset insert button label
								// NOTE: Not sure why this would be necessary?
								//setInsertButtonLabel( wpalchemy_insert_button_label );

								wpalchemy_mediafield = null;
							}
							else
							{
								wpalchemy_send_to_editor_default(html);
							}

							tb_remove();
						}


						/**
						 * Returns the triggered elements label
						 */
						function getTriggerLabel() {

								// Get the data button-label property.
							var label = $trigger.data( "button-label" );
							
							//
							// If the label is set, return the label, otherwise return "Insert"
							//
							return ( label ) ? label : "Insert"; 
						}

						function getInsertButtonLabel( $tbFrame )
						{
							return $tbFrame.contents().find('.media-item .savesend input[type=submit], #insertonlybutton').val();
						}

						function setInsertButtonLabel( $tbFrame, label )
						{
							$tbFrame.contents().find('.media-item .savesend input[type=submit], #insertonlybutton').val( label );
						}

						function iframeSetup( e, $tbFrame )
						{

							if ( $tbFrame.contents().find('.media-item .savesend input[type=submit], #insertonlybutton').length )
							{
								// run once
								if ( ! wpalchemy_insert_button_label.length )
								{
									wpalchemy_insert_button_label = getInsertButtonLabel( $tbFrame );
								}

								setInsertButtonLabel( $tbFrame, getTriggerLabel() );
							
							}

						}

						//
						// Checks to see if the iframe has been added to the DOM
						// Once the iframe is added, we trigger the 'ma:frame:loaded' event on the document object.
						//
						// Triggered by $doc publish event: 'ma:frame:check_loaded'
						//
						function checkFrameLoaded() {
								
							var $tbFrame = $( '#TB_iframeContent' );
							
							// Verify if we have an iframe								
							if( !$tbFrame || $tbFrame.length === 0 ) { 
								//
								// No iframe found, try again.
								// Trigger the 'ma:frame:check_loaded' event, which will recurse to this function.
								//
								// Delay the trigger, to avoid overflooding of the publish stack.
								setTimeout( function() {
									$doc.trigger( "ma:frame:check_loaded" );	
								}, 0 )
								
								return;
							}

							// We found the iFrame in the dom. 
							// Bind the 'load' event to the iframe.
							// 
							// This is really to help us handle any reloading that occours within the iframe. 
							// For example: clicking between tabs, filtering or searching.
							//
							// Triggers the 'ma:frame:loaded' event.
							$tbFrame.on( "load", function() {
							
								// Everytime the iframe loads,
								// Trigger the 'ma:frame:loaded' event.
								// We're passing the $tbFrame (iframe) element to the bound functions
								//
								$doc.trigger( "ma:frame:loaded", [ $tbFrame ] );
							
							} );
							
							//
							// Since we found the iframe, we can trigger the 'ma:frame:loaded' event
							// This should trigger the iframeSetup function.
							// We're passing the $tbFrame (iframe) element to the bound functions
							//
							$doc.trigger( "ma:frame:loaded", [ $tbFrame ] );
							
						}

						/** 
						 * Delegated Event handler for the MediaAccess button.
						 */
						function show_frame( e ) {
							
							e.preventDefault();
							
							var $this = $trigger = $( this ),

								name = $this.attr('class').match(/<?php echo $this->button_class_name; ?>-([a-zA-Z0-9_-]*)/i)
							;
							
							name = ( name && name[1] ) ? name[1] : '' ;

							wpalchemy_mediafield = $('.<?php echo $this->field_class_name; ?>-' + name, $this.closest('.postbox') );

							// Let's start checking for the iframe.
							$doc.trigger( "ma:frame:check_loaded" );

						}

						// Bind to the document click event,
						// Delegate the event to MediaAccess' button class.
						$doc.on( 'click', '[class*=<?php echo $this->button_class_name; ?>]', show_frame )
				
							// Bind the ma:frame:loaded event. 
							// Triggered when iframe is first loaded, and on it's load events
							//
							.on( "ma:frame:loaded", iframeSetup )
				
							//
							// This event will help us verify when the iframe has been loaded to the dom.
							//
							.on( "ma:frame:check_loaded", checkFrameLoaded )
						;
						

					}

				});

			/* ]]> */
			</script><?php
		}
	}
}

/* End of file */