<?php
/**
 * @package    AjaxContact
 * @author     Douglas Machado {@link http://idealextensions.com}
 * @author     Created on 22-Mar-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

require_once dirname(__FILE__).DS.'helper.php';


$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));


$suffix = JUtility::getHash(microtime());


require JModuleHelper::getLayoutPath('mod_ajaxcontact', $params->get('layout', 'default'));