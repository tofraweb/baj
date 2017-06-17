<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_sources.php 13727 2012-07-02 08:06:10Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$task 			= JRequest::getWord('task','','post');
$showlistID 	= JRequest::getVar('cid');
$showlistID 	= $showlistID[0];
$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
$baseURL 		= $objJSNUtils->overrideURL();
$showlistTable 	= JTable::getInstance('showlist', 'Table');
$showlistTable->load($showlistID);
if ($showlistTable->image_source_name != '')
{
	$this->imageSource->loadScript();
	$sync = $this->imageSource->getShowlistMode();
}
else
{
	$sync = false;
}
if ($this->selectMode != '')
{
		$selectMode = ' '.$this->selectMode;
}
else
{
	$selectMode	='';
}
?>
<script type="text/javascript">
	window.addEvent('domready', function()
	{
		JSNISImageShow.getScriptCheckThumb(<?php echo (int) $showlistID; ?>);
	});
</script>
<div class="jsn-showlist-video" id="showlist-video-layout">
	<div class="ui-layout-west" id="panel-west">
		<div class="sourcevideo-header"><h3><?php echo JText::_('SHOWLIST_SHOWLIST_SOURCE_IMAGES'); ?></h3></div>
		<div class="sourcevideo-panel-container">
			<div class="panel-right jsn-bootstrap">
				<div class="panel-header clearafter jsn-iconbar" id="source-video-header">
					<a href="javascript: void(0);" class="image-show-grid pull-left jsn-icon16 icon-layout icon-thumbnails disabled" title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_SELECT_THUMB_PRESENTATION_MODE'); ?>"></a>
					<a href="javascript: void(0);" class="image-show-list pull-left jsn-icon16 icon-layout icon-thumbnaildetails disabled" title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_SELECT_DETAILS_PRESENTATION_MODE'); ?>"></a>
					<a href="javascript: void(0);" class="pull-right jsn-icon16 icon-ok disabled" id="move-selected-video-source" title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_ADD_SELECTED_IMAGE'); ?>">
					</a>
				</div>
				<div class="sourcevideo-container" id="sourcevideo-container">
					<div class="source-images showgrid" id="source-images">
						<?php if($showlistTable->image_source_type == 'external' || $showlistTable->image_source_name == 'folder'):?>
						<div id="showMoreImages">
							<button id="showMoreImagesBtn" class="btn"><?php echo JText::_('SHOWLIST_IMAGE_LOAD_MORE_IMAGES'); ?></button>
							<input type="hidden" id="cateNameInShowlist" value=""/>
						</div>
						<?php endif;?>
					</div>
			   </div>
			</div>

			<?php
			$imageFolder = $this->imageSource->getCategories();
			$folderlists = json_decode(json_encode((array) simplexml_load_string('<nodes>'.$imageFolder.'</nodes>')),1);
			$imageFolder  =  @$this->imageSource->convertXmlToTreeMenu($folderlists['node'],$this->catSelected);
			?>
			<div class="panel-left">
				<div class="panel-header clearafter jsn-bootstrap" id="jsn-header-tree-control">
					<h3 class="pull-left"><?php echo $showlistTable->image_source_name;?></h3>
					<?php if($showlistTable->image_source_type != 'external'):?>
					<button class="btn btn-small pull-right sync<?php echo ($sync=='sync')?' btn-success':'';?>"><?php echo JText::_('SYNC_UPPERCASE', true);?>: <?php echo ($sync=='sync')?JText::_('ON_UPPERCASE', true):JText::_('OFF_UPPERCASE', true);?></button>
					<?php endif;?>
				</div>
				<div class="sourcevideo-container">
<!--
					<div class="jsn-header-tree-control jsn-bootstrap" id="jsn-header-tree-control">
						<button class="btn expand-all"><i class="icon-plus"></i></button>
						<button class="btn collapse-all"><i class="icon-minus"></i></button>
					</div>
-->
					 <div class="jsn-jtree" id="jsn-jtree-categories">
						<ul>
							<?php echo @$imageFolder;?>
						</ul>
					</div>
				</div>
			</div>
			<div class="clearbreak"></div>
		</div>
	</div>
	<?php
		$config = array('showlist_id'=>$showlistID);
		if(trim($selectMode)=='sync')
		{
			$imagesStored = $this->imageSource->getSyncImages($config);
		}
		else
		{
			$imagesStored = $this->imageSource->getImages($config);
		}
		$countImagesStored = count($imagesStored);
	?>
	<div class="ui-layout-center" id="panel-center">
		<div class="sourcevideo-header"><h3><?php echo JText::_('SHOWLIST_SHOWLIST_S_IMAGES');?></h3></div>
		<div class="sourcevideo-panel-container jsn-bootstrap">
			<div class="panel-header clearafter jsn-iconbar" id="showlist-video-header">
				<a href="javascript: void(0);" class="image-show-grid pull-left jsn-icon16 icon-layout icon-thumbnails disabled" title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_SELECT_THUMB_PRESENTATION_MODE'); ?>"></a>
				<a href="javascript: void(0);" class="image-show-list pull-left jsn-icon16 icon-layout icon-thumbnaildetails disabled" title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_SELECT_DETAILS_PRESENTATION_MODE'); ?>"></a>
				<a href="javascript: void(0);" class="pull-right jsn-icon16 icon-trash disabled" id="delete-video-showlist"  <?php if ($sync=='sync'){ echo 'style="display:none;"';}?> title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_DELETE_SELECTED_IMAGE'); ?>">
				</a>
				<a href="javascript: void(0);" class="pull-right jsn-icon16 icon-pencil disabled" id="edit-video-showlist"  <?php if ($sync=='sync'){ echo 'style="display:none;"';}?> title="<?php echo JText::_('SHOWLIST_IMAGE_MANAGER_EDIT_SELECTED_IMAGE'); ?>">
				</a>
				<div class="clearbreak"></div>
			</div>
			<div class="sourcevideo-container" id="showlistvideo-container">
				<div class="showlist-images showgrid <?php if (count($imagesStored) == 0){ echo 'jsn-section-empty'; }?>" id="showlist-images">

				<?php
				if ($countImagesStored > 0){
					foreach ($imagesStored as $image)
					{
						$image = (array) $image;
						$processedImage = array(
								'image_id'			=> (string) $image['image_id'],
								'image_title'		=> (string) $image['image_title'],
								'image_small'		=> (string) $image['image_small'],
								'image_medium'		=> (string) $image['image_medium'],
								'image_big'			=> (string) $image['image_big'],
								'image_description'	=> (string) $image['image_description'],
								'image_link'		=> (string) $image['image_link'],
								'image_extid'		=> (string) $image['image_extid'],
								'album_extid'		=> (string) $image['album_extid'],
								'image_size'		=> (string) $image['image_size'],
								'custom_data'		=> (string) $image['custom_data'],
								'exif_data'			=> (string) $image['exif_data'],
								'original_title'	=> (string) $image['original_title'],
								'original_description'		=> (string) $image['original_description'],
								'original_link'		=> (string) $image['original_link']);
						?>
							<div class="video-item" id="<?php echo urlencode($image['image_extid']);?>">
								<input type="hidden" value="<?php echo $image['original_link']; ?>" id="linkcheck" name="linkcheck" />
								<input class="img_extid" type="hidden" value="<?php echo urlencode($image['album_extid']);?>">
								<input class="img_detail" type="hidden" value="<?php echo htmlspecialchars(json_encode($processedImage), ENT_COMPAT, 'UTF-8');?>" />
								<div class="video-index">&nbsp;</div>
								<?php echo (isset($image['custom_data']) && $image['custom_data']==1)?'<div class="modified"></div>':'';?>
								<div class="video-thumbnail">
									<a class="image_link" title="<?php echo $image['image_title'];?>">
										<?php
										$baseurl = ($showlistTable->image_source_type=='external')?'':JURI::root();
										?>
										<img src="<?php echo  $baseurl.$image['image_small'];?>" style="max-height:60px; max-width: 80px;"/>
									</a>
								</div>
								<div id="<?php echo $image['album_extid'];?>" class="video-info">
									<p><b><?php echo $image['image_title'];?></b></p>
									<p>
									<?php
										if ( strlen($image['image_description']) > 100 ){
											$i = 99;
											while( $image['image_description'][$i] != ' ' && $i < strlen($image['image_description']) - 1){
												$i++;
											}
											echo substr($image['image_description'], 0, $i).'...';
										}else{
											echo $image['image_description'];
										}
									?>
									</p>
									<p><?php echo $image['image_link'];?></p>
								</div>
								<div class="clearbreak"></div>
							</div>
						<?php
					}
				}else{
					?>
						<div class="jsn-bglabel <?php echo (trim($selectMode) == 'sync')?'showlist-sync-image-notice':'showlist-drag-drop-video-notice'?>">
							<?php if (trim($selectMode) == 'sync') { ?>
							<span class="jsn-icon64 icon-refresh"></span>
							<?php echo JText::_('SHOWLIST_NOTICE_IN_SYNC_MODE');?>
							<?php } else {?>
							<span class="jsn-icon64 icon-pointer"></span>
							<?php echo JText::_('SHOWLIST_NOTICE_DRAG_AND_DROP');?>
							<?php } ?>
						</div>
					<?php
				}
				?>
			</div>
		</div>
		<div class="clearbreak"></div>
	</div>
</div>

<input type="hidden" value="" id="start" name="start" />
<input type="hidden" value="" id="stop" name="stop" />
<input type="hidden" value="" id="start_image_showlist" name="start" />
<input type="hidden" value="" id="stop_image_showlist" name="stop" />
</div>