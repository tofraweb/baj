<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.html.php 13486 2012-06-23 08:57:04Z thangbh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class PoweradminViewSelectmoduletypes extends JView
{
	protected $state;
	protected $items;	
	
	public function display($tpl = null)
	{	
		$JSNMedia = JSNFactory::getMedia();
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI.'jsn.filter.js');
		
		$state	  = $this->get('State');
		$items    = $this->get('Items');
		$position = JRequest::getVar('position', '');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->assignRef('state', $state);
		$this->assignRef('items', $items);
		$this->assignRef('position', $position);

		$customScript = "
			var selectModuleType;
            (function($){
				$(window).ready(function(){
				  	  selectModuleType =  $.jsnFilter(
				  	  {
			  	  		  frameElement: $('#jsn-module-type-container'),
					  	  totalItems  : ".count($items).",
					  	  itemClass   : '.jsn-item-type',
					  	  totalColumn : 3,
					  	  itemWidth   : 220,
					  	  itemHeight  : 30,
					  	  mPosLeft    : 0,
					  	  mPosTop     : 0,
					  	  marginOffset: {
					  	  	  right : 15,
					  	  	  bottom: 20
					  	  },
					  	  eventClick: function(){
						  	  var extension_id = $(this).attr('id');
						  	  var position     = $('#position').val();
						  	  window.parent.jQuery.addNewModule(extension_id, position);
					  	  }
				  	  	}
				  	  );				  	  
				  });
			  })(JoomlaShine.jQuery);
		";
		$JSNMedia->addScriptDeclaration( $customScript );
		
		return parent::display();
	}
}