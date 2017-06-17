<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.version');

$version = '';

if (JVersion::isCompatible('1.6.0'))
  $manifest = JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_n3ttemplate' .DS. 'install.j16.xml';
else
  $manifest = JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_n3ttemplate' .DS. 'manifest.j15.xml';

if ($data = @JApplicationHelper::parseXMLInstallFile($manifest)) {
  $version=$data['version'];
}
require(JModuleHelper::getLayoutPath('mod_n3ttemplate_credits'));
?>