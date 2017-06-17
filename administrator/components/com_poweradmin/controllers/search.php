<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: search.php 13370 2012-06-18 04:06:36Z binhpt $
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

class PoweradminControllerSearch extends JControllerForm
{
	function query () {
		$app = JFactory::getApplication();
		$app->setUserState('search.keyword', '');
		$app->setUserState('search.coverage', '');
		
		$this->setRedirect('index.php?option=com_poweradmin&view=search');
	}
	
	/**
	 * Populate search and response results to client as json format
	 * @return void
	 */
	function json ()
	{
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		
		$keyword = JRequest::getString('keyword');
		$coverages = array_map('trim', explode(',', JRequest::getString('coverages')));
		
		require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers/poweradmin.php';
		require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers/plugin.php';
		
		$params = JComponentHelper::getParams('com_poweradmin');
		$resultLimit = (int)$params->get('search_result_num', 10);
		
		//JRequest::setVar('limit', $resultLimit);
		
		// Load language
		JFactory::getLanguage()->load('plg_system_jsnpoweradmin');
		
		// Load model
		$model = JModel::getInstance('Search', 'PowerAdminModel');
		$model->setState('search.keyword', $keyword);		
		$model->setState('search.pagination', false);
		
		$installedComponents = PowerAdminHelper::getInstalledComponents();

		$results = array();
		foreach ($coverages as $coverage) {
			if ($coverage == 'adminmenus') {
				continue;
			}
			
			$components = array();
			if ($coverage == 'components') {
				if (in_array('com_banners', $installedComponents)) $components['com_banners'] = array('com_banners', 'com_banners_categories', 'com_banners_clients');
				if (in_array('com_contacts', $installedComponents)) $components['com_contacts'] = array('com_contacts', 'com_contacts_categories');
				if (in_array('com_messages', $installedComponents)) $components['com_messages'] = array('com_messages');
				if (in_array('com_newsfeeds', $installedComponents)) $components['com_newsfeeds'] = array('com_newsfeeds', 'com_newsfeeds_categories');
				if (in_array('com_weblinks', $installedComponents)) $components['com_weblinks'] = array('com_weblinks', 'com_weblinks_categories');
			}
			
			if ($coverage == 'k2') {
				if (in_array('com_k2', $installedComponents)) {
					$components['com_k2'] = array('com_k2', 'com_k2_categories');
				}
				else {
					continue;
				}
			}
				
				
			if (!empty($components)) {
				foreach ($components as $key => $component) {
					$componentResults = array();
					$type = $component;
					
					if (is_array($component)) {
						$type = $key;
						
						foreach ($component as $subsection) {
							$model->setState('search.coverage', $subsection);
							$componentResults = array_merge($componentResults, $model->getItems());
						}
					}
					else {
						$model->setState('search.coverage', $component);
						$componentResults = array_merge($componentResults, $model->getItems());
					}
					
					if (count($componentResults) > 0) {
						$results[] = array('title' => JText::_('PLG_JSNADMINBAR_SEARCH_COVERAGE_'.strtoupper($type),true), 'description' => '', 'type' => $type, 'coverage' => $coverage);
						$results = array_merge($results, $componentResults);
					}
				}
				
				continue;
			}
			
			$model->setState('search.coverage', $coverage);
			
			
			$total = $model->getTotal();
			$items = $model->getItems();
			
			if (count($items) > 0) {
				$results[] = array(
					'title' => JText::_('PLG_JSNADMINBAR_SEARCH_COVERAGE_'.strtoupper($coverage)), 
					'description' => '', 
					'type' => $coverage, 
					'coverage' => $coverage, 
					'hasMore' => $total - $resultLimit
				);
				
				$results = array_merge($results, $items);
			}
		}

		echo json_encode($results);
		jexit();
	}
}
?>