<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Image Source Flickr
 * @version $Id: define.php 9919 2011-11-28 10:35:38Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$jsnImageSourcePicasa = array(
	'name' => 'Picasa',
	'identified_name' => 'picasa',
	'type' => 'external',
	'description' => 'Picasa Description',
	'thumb' => 'plugins/jsnimageshow/sourcepicasa/assets/images/thumb-picasa.png'
);

define('JSN_IS_SOURCEPICASA', json_encode($jsnImageSourcePicasa));
