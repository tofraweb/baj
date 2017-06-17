<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.html.php 12795 2012-05-21 02:35:16Z binhpt $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class PoweradminViewChangeposition extends JView
{	
	public function display($tpl = null)
	{		
		$app 		= JFactory::getApplication();
		$JSNMedia 	= JSNFactory::getMedia();
		$jsntemplate = JSNFactory::getTemplate();
		$positions   = $jsntemplate->loadXMLPositions();
		
		$pos = array();
		for($i = 0; $i < count($positions); $i++){
			$pos[$i] = $positions[$i]->name;
		}
		//sort( $pos, SORT_STRING );
		
		$this->assign('positions', $pos);

		$moduleid = $app->getUserState( 'com_poweradmin.changeposition.moduleid' );
		
		$old_positions = Array();
		$model = $this->getModel('changeposition');
		for( $i = 0; $i < count($moduleid); $i++ ){
			$old_positions[] = $model->getModulePosition(  $moduleid[$i] );
		}
		$this->assign('old_positions', $old_positions);

		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI.'jsn.functions.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI.'jsn.filter.js');
		
		$js_variables = $jsntemplate->loadArrayJavascriptTemplatePositions(false);
		
		$customScript = "
			".$js_variables."
			var baseUrl  = '".JURI::root()."';
			var moduleid = new Array();
			moduleid = [". implode(",", $app->getUserState( 'com_poweradmin.changeposition.moduleid' ))."];
			var changeposition;
			(function($){
				$(window).ready(function(){
					changeposition =  $.jsnFilter(
					{
						frameElement	: $('#jsn-position-listing'),
						totalItems		: positions.length,
						itemClass		: '.position-selector',
						totalColumn		: 5,
						itemWidth		: 125,
						itemHeight		: 28,
						mPosLeft		: 10,
						mPosTop			: 9,
						marginOffset	: {
							right	: 17,
							bottom	: 20
						},
						eventClick   : function(){
							if (!$(this).hasClass('item-active')){
								$('.item-active').removeClass('item-active');
								$(this).addClass('item-active');
								$.setPosition(moduleid, $(this).attr('id'));
							}
						}
					});
				});
			})(JoomlaShine.jQuery);
		";
		$JSNMedia->addScriptDeclaration( $customScript );
	 
		return parent::display();
	}	
}