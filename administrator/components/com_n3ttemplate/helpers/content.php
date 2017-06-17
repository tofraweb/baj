<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.version');

class n3tTemplateHelperContent extends JObject
{

	public static function getArticlePositions() {
    return array(
      'editor',
      'top',
      'bottom',
      'introtop',
      'introbottom'
    ); 
	}

}
?>