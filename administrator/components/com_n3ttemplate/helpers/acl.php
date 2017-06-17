<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.version');

class n3tTemplateHelperACL extends JObject
{
  private $user = null;
  
	function __construct()
	{
	  $this->user = & JFactory::getUser();
    if (!JVersion::isCompatible('1.6.0')) {
  		$params =& JComponentHelper::getParams('com_n3ttemplate');
  		$acl = & JFactory::getACL();
  		switch ($params->get('acl_manage','manager'))
  		{
  			case 'manager':
  				$acl->addACL( 'com_n3ttemplate', 'core.manage', 'users', 'manager' );
  			case 'administrator':
  				$acl->addACL( 'com_n3ttemplate', 'core.manage', 'users', 'administrator' );
  			case 'super administrator':
  				$acl->addACL( 'com_n3ttemplate', 'core.manage', 'users', 'super administrator' );
  		}
  		switch ($params->get('acl_admin','administrator'))
  		{
  			case 'manager':
  				$acl->addACL( 'com_n3ttemplate', 'core.admin', 'users', 'manager' );
  			case 'administrator':
  				$acl->addACL( 'com_n3ttemplate', 'core.admin', 'users', 'administrator' );
  			case 'super administrator':
  				$acl->addACL( 'com_n3ttemplate', 'core.admin', 'users', 'super administrator' );
  		}
		}
	}
	
  public static function &getInstance()
	{
		static $instance;

		if (!isset ($instance)) {
			$instance = new n3tTemplateHelperACL();
		}

		return $instance;
	} 
	
	public function authorizeUser($action) {
	  if (JVersion::isCompatible('1.6.0'))
      return $this->user->authorize( $action, 'com_n3ttemplate' );
    else
      return $this->user->authorize( 'com_n3ttemplate', $action );
	}
	
  public static function authorize() {
    $access=n3tTemplateHelperButton::getButtonAccess();
    $user = & JFactory::getUser();
    if (JVersion::isCompatible('1.6.0')) {
      return in_array($access, $user->getAuthorisedViewLevels());
    } else {
      return $access<=$user->get('aid', 0);
    }
    return true;
  }

  public static function authorizeAdmin() {
    $acl = &n3tTemplateHelperACL::getInstance();
    return $acl->authorizeUser( 'core.manage' );
  }

  public static function authorizeConfig() {
    $acl = &n3tTemplateHelperACL::getInstance();
    return $acl->authorizeUser( 'core.admin' );
  }

}
?>