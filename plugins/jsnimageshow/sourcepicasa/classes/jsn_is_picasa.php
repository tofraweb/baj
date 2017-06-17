<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_picasa.php 14200 2012-07-20 04:06:33Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
class JSNISPicasa
{
	public function getSourceParameters(){
		$query = 'SELECT params FROM #__extensions WHERE element = "sourcepicasa" AND folder = "jsnimageshow"';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$db->query();
		return $db->loadResult();
	}
}