<?php
/**
 * @version		$Id: mooaccordion.php 1.0.4 2012-05-28 17:17:00Z andrewp $
 * @package		MooAccordion content plugin
 * @author		Andrew Patton
 * @copyright	Copyright (C) 2012 Andrew Patton. All rights reserved.
 * @license		GNU/GPL v2; see LICENSE.php
 **/
// no direct access
defined( '_JEXEC' ) or die;

jimport( 'joomla.event.plugin' );

if ( is_object( $mainframe ) && method_exists( $mainframe, 'registerEvent' ) )
	$mainframe->registerEvent( 'onContentPrepare', 'plgContentMooAccordion' );

class plgContentMooAccordion extends JPlugin {
	
	var $_loaded;
	/**
	 * Constructor -- used to keep track in case plugin gets called twice (using _loaded variable)
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 */
	public function plgContentMooAccordion( &$subject, $params )
	{
		$this->_loaded = false;
		parent::__construct( $subject, $params );
	}
	
	/**
	 * Function to generate mootools accordion slides from {mooblock} syntax
	 */
	public function onContentPrepare( $context, &$article, &$params, $page = 0 )
	{
		// first figure out whether to use article id or item_id:
		$id = isset($article->item_id) ? $article->item_id : $article->id;
		// simple performance check to determine whether bot should process further
		if (strpos($article->text, '{mooblock') === false || ($this->_loaded == $id)) {
			return true;
		}
		
	 	// expression to search for; includes a check for <p> or <div> tag in the plugin syntax, added by wysiwig editors
	 	//$regex = '/(?:<(div|p)[^>]*>)?{mooblock(?:=(.+))?}(?(1) *<\/\1>)([\s\S]+?)(?:<(div|p)[^>]*>)?{\/mooblock}(?(4) *<\/\4>)/i';
		$regex = '/((?:<(div|p)[^>]*>)?{mooblock[\s\S]+?{\/mooblock}(?: *<\/(div|p)>)*\s*)+/i'; // get each accordion chunk

	 	// find all instances of slides and put in $matches
		if (preg_match_all($regex, $article->text, $accordions)) { // make sure there is a match
			
			JHTML::_('behavior.mootools'); // make sure mootools is loaded-- do we need this if using addScriptDeclaration()?
			$document	=& JFactory::getDocument();
			if (!$this->_loaded) // only add css if this is the first first time
				$document->addStyleDeclaration('	.mooblock-title {cursor:pointer;} .mooblock-el {height:0px;}');
			
			// prep more specific regex:
			// add something to strip <br>s, like (?:<br ?\/?\>)
			$regex = '/(?:<(div|p)[^>]*>)?{mooblock(?:=(.+))?}(?(1) *<\/\1>)?([\s\S]+?)(?:<(div|p)[^>]*>)?{\/mooblock}(?(4) *<\/\4>)/i';
			// note: need to consider when {mooblock=...} syntax is on same line as mooblock content
			foreach($accordions[0] as $key=>$accordion) {
				
				if (preg_match_all($regex, $accordion, $matches, PREG_SET_ORDER)) { // ensure the more specific regex matches
					
					// id the mooblocks to make it possible to have multiple articles w. mooblocks on one page
					// and multiple mooblocks within one article
					$identifier = 'mb' . $id . '_' . ($key+1);
					
					// Doing it the right way (selecting multiple classes) requires mootools 1.2 or later.
					// So, made identifier specific to titles/elements
									
					$js = '
		window.addEvent("domready", function(){
			var mooBlock = new Accordion( ".' . $identifier . 't", ".' . $identifier . 'e", {
				' . ($this->params->get('show_first') ?
				 		($this->params->get('first_transition') ? 'display: 0'
						: 'show: 0' ) 
					: 'display: -1') . ',
				' . (!$this->params->get('opacity') ? 'opacity: false,
				': '') . ($this->params->get('always_hide') ? 'alwaysHide: true,
				': '') . ($this->params->get('duration') != '500' || $this->params->get('transition') ? 'duration: ' . ($this->params->get('transition') ? ($this->params->get('duration') + 500) : $this->params->get('duration')) . ',
				': '') . ($this->params->get('transition') ? 'transition: Fx.Transitions.' . $this->params->get('transition') . '.easeOut,
				': '') . 'onActive: function(title, el){
					title.addClass("expanded");
					el.addClass("expanded");
				},
				onBackground: function(title, el){
					title.removeClass("expanded");
					el.removeClass("expanded");
				}
			});' . ($this->params->get('trigger') ? '
			$$(".' . $identifier . 't").addEvent("mouseenter", function() { this.fireEvent("click"); });' : '') . '
		});';
				
					$document->addScriptDeclaration($js);
				
					foreach ($matches as $match) {
						// $match[0] is full pattern match, $match[1] is an html tag wrapping '{mooblock ...}' (if found),
						// $match[2] is the title (toggler), $match[3] is the actual block, 
						// $match[4] is an html tag wrapping '{/mooblock}' (if found)
				
						$output = '';
						// Make sure we've got what we need:
						if ($match[3]) {
							$titleClass = 'mooblock-title ' . $identifier . 't';
							$elClass = 'mooblock-el ' . $identifier . 'e';
							
							// toggler:
							$tag = array();
							if (preg_match_all('/<([A-Z][A-Z0-9]*)([^>])*>(.*?)<\/\1>/i', $match[1], $tag, PREG_SET_ORDER) == 1) {
								// it's wrapped in one tag, so let's use that:
								if ($tag[2]) { // could already have a class
									if (strpos($tag[2], 'class=') !== false) {
										$tag[2] = str_replace('class="', 'class="' . $titleClass . ' ', $tag[2]); 
									}
									else {
										$tag[2] .= ' class="' . $titleClass .'"';
									}
								}
								else { // need to add title class:
									$tag[2] = ' class="' . $titleClass . '"';
								}
								$output .= '<' . $tag[1] . $tag[2] . '>' . $tag[3] . '</' . $tag[1] . '>';
							}
							else { // no html tags or multiple html tags, instead just wrap it in a <h4 class="mooblock-title">
								$output .= '<h4 class="' . $titleClass . '">' . $match[2] . '</h4>';
							}
							// block:
							// using height="0", the contents of the blocks are not seen on load in IE 6, but it breaks validation
							// $output .= "\n" . '<div class="' . $elClass . '" height="0">' . $match[3] . '</div>';
							$output .= "\n" . '<div class="' . $elClass . '">' . $match[3] . '</div>';
						}
						$article->text = str_replace($match[0], $output, $article->text);
					
					}
				}
			}
			// set _loaded to article's id, so that if it gets called again on this article, it won't do all of this again
			// (though it will load again if called on a different article [like in a category page])
			$this->_loaded = $id;
		}
	}
}
?>