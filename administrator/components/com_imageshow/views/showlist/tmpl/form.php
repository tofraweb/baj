<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form.php 14043 2012-07-16 04:56:07Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.html.pane');
$task 				= JRequest::getVar('task');
$showlistID 		= JRequest::getVar('cid');
$showlistID 		= $showlistID[0];
$pane 				= JPane::getInstance('Sliders', array('allowAllClose' => true));
$msgChangeSource	= JText::_('SHOWLIST_MSG_CHANGE_SOURCE', true);
$changeSource 		= (!empty($this->items->image_source_name)) ? "<button type=\"button\" onclick=\"JSNISImageShow.confirmChangeSource('".$msgChangeSource."', ".(int)$showlistID.", ".(int) $this->countImage.");\" class=\"btn\"><i class=\"icon-folder-open\"></i> ".JText::_('SHOWLIST_CHANGE_SOURCE', true)."</button>" : "";
$text = '';
if (isset($this->items->image_source_name) && $this->items->image_source_name != '')
{
	$text = JText::_('SHOWLIST_TITLE_SHOWLIST_IMAGES');
}
else
{
	$text = JText::_('SHOWLIST_TITLE_SHOWLIST_SOURCES');
}
?>
<!--[if IE]>
	<link href="<?php echo JURI::base();?>components/com_imageshow/assets/css/fixie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<div id="jsn-showlist-settings" class="jsn-page-edit">
<?php
echo $this->loadTemplate('showlist');
echo '<div class="jsn-showlist-images jsn-section">';
echo '<h2 class="jsn-section-header jsn-bootstrap">'.$text.$changeSource.'</h2>';
		if($task == 'add'){
			echo '
				<div id="jsn-no-showlist" class="jsn-section-content jsn-section-empty jsn-bootstrap">
					<p class="jsn-bglabel"><span class="jsn-icon64 icon-save"></span>'.JText::_('SHOWLIST_PLEASE_SAVE_THIS_SHOWLIST_BEFORE_SELECTING_IMAGES').'</p>
					<div class="form-actions">
						<a id="jsn-go-link" class="btn" href="javascript:Joomla.submitbutton(\'apply\');">'.JText::_('SHOWLIST_SAVE_SHOWLIST').'</a>
					</div>
				</div>';
		}
		else
		{
			if (isset($this->items->image_source_name) && $this->items->image_source_name != '') {
				echo $this->loadTemplate('sortable');
			} else {
				echo '<div class="jsn-section-content">';
				echo $this->loadTemplate('sources');
				echo $this->loadTemplate('install_sources');
				echo '</div>';
			}
		}
echo '</div>';
?>
<?php
//include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php');
include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'contextmenu.php');
?>
</div>
<div>
	<div id="jsn-is-dialogbox" class="jsn-display-none"></div>
	<div class="ui-widget-overlay" id="jsn-is-tmp-sbox-window">
		<div class="img-box-loading" id="jsn-is-img-box-loading">
			<img src="components/com_imageshow/assets/images/ajax-loader.gif">
		</div>
	</div>
</div>
<div id="tmp_id_auto_modal_window"></div>