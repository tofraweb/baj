<?php
defined('_JEXEC') or die('Restricted access');
$isWriable = true;
if($offset==0 && ($sourceType=="external" || $sourceName == 'folder')){
?>
<script type="text/javascript">
(function($){
	JSNISImageGrid.imageTotal = <?php echo ($countImages!='')?$countImages:-1;?>;
	$('img.image_img').load(function(){
	  $(this).parent().removeClass('isloading');
	});
})(jQuery);
</script>
<?php }
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$baseURL 	= $objJSNUtils->overrideURL();
$showlistTable = JTable::getInstance('showlist', 'Table');
$showlistID = JRequest::getVar('showListID');
$showlistTable->load($showlistID);
$baseurl = '';
?>
<?php if ($showlistTable->image_source_name == 'folder') {?>
	<?php if (!$objJSNUtils->folderIsWritable('images')) {
		$isWriable = false;
	?>
	<script type="text/javascript">
		alert('<?php echo JText::sprintf('SHOWLIST_FOLDER_IS_UNWRIABLE', DS.'images', array('jsSafe'=>true, 'interpretBackSlashes'=>true, 'script'=>false)); ?>')
	</script>
	<?php } elseif (!$objJSNUtils->folderIsWritable('images'.DS.'jsn_is_thumbs') && is_dir(JPATH_ROOT.DS.'images'.DS.'jsn_is_thumbs')) {
		$isWriable = false;
	?>
	<script type="text/javascript">
		alert('<?php echo JText::sprintf('SHOWLIST_FOLDER_IS_UNWRIABLE', 'images/jsn_is_thumbs', array('jsSafe'=>true, 'interpretBackSlashes'=>true, 'script'=>false)); ?>')
	</script>
	<?php } ?>
<?php } ?>
<?php

if(isset($images->images)){
	$totalimage = count($images->images);
	if ( $totalimage > 0){
		if ( $selectMode != ''){
			$selectMode = ' '.$selectMode;
		}
		$i = 1;
		foreach ($images->images as $image)
		{
			$image 	 		= (array) $image;
			$image['image_title'] = $objJSNUtils->convertSmartQuotes($image['image_title']);
			$image['image_description'] = $objJSNUtils->convertSmartQuotes($image['image_description']);

			$processedImage = array(
								'image_id'			=> (string) $image['image_extid'],
								'image_extid'		=> (string) $image['album_extid'],
								'image_small'		=> (string) $image['image_small'],
								'image_medium'		=> (string) $image['image_medium'],
								'image_big'			=> (string) $image['image_big'],
								'image_link'		=> (string) $image['image_link'],
								'album_extid'		=> (string) $image['album_extid'],
								'image_description'	=> (string) $image['image_description'],
								'image_title'		=> (string) $image['image_title']);
			$checked = $imageSource->checkImageSelected($image['image_extid']);
			//$imageObj['isSelected']				= $checked;
			if ($checked || $syncIsSelected)
			{
				$itemClass = 'image-item-is-selected';
			}
			else
			{
				$itemClass = 'video-item';
			}
			?>
			<?php if ($showlistTable->image_source_name == 'folder' && $isWriable) {?>
			<script type="text/javascript">
			(function($){
					$(document).ready(function () {
						JSNISImageGrid.createThumbForPreview('image_id_<?php echo $i; ?>', 'input_image_thumb_id_<?php echo $i; ?>', '<?php echo urlencode($image['album_extid']);?>', '<?php echo urlencode($image['image_title']);?>', '<?php echo urlencode($image['image_big']);?>');
				});
			})(jQuery);
			</script>
			<?php } ?>
			<div class="<?php echo $itemClass;?><?php echo $selectMode;?>" id="<?php echo urlencode($image['image_extid']);?>">
				<input class="img_extid" type="hidden" value="<?php echo urlencode($image['album_extid']);?>" />
				<input class="img_detail" type="hidden" value="<?php echo htmlspecialchars(json_encode($processedImage), ENT_COMPAT, 'UTF-8');?>" />
				<input class="img_thumb" type="hidden" value="<?php echo urlencode($image['image_big']); ?>" id="input_image_thumb_id_<?php echo $i; ?>" />
				<div class="video-index"><?php echo $i.'/'.$totalimage;?>
					<?php if ( $selectMode != 'sync') {?>
						<button class="move-to-showlist">&nbsp;</button>
					<?php }?>
				</div>
				<div class="video-thumbnail">
					<a class="image_link<?php echo ($showlistTable->image_source_name == 'folder')?' isloading':'';?>" title="<?php echo $image['image_title'];?>">
						<?php
							$baseurl = ($showlistTable->image_source_type == 'external')?'':JURI::root();
						?>
						<?php if ($showlistTable->image_source_name != 'folder') {?>
						<img id="image_id_<?php echo $i; ?>" class="image_img" src="<?php echo $baseurl.$image['image_small'];?>" style="max-height:60px; max-width: 80px;" alt=""/>
						<?php } else {?>

						<img id="image_id_<?php echo $i; ?>" class="image_img" src="<?php echo (!$isWriable)?$baseurl.$image["image_small"]:'';?>" style="max-height:60px; max-width: 80px;" alt=""/>
						<?php } ?>
					</a>
				</div>
				<div id="video-info-<?php echo $image['image_extid'];?>" class="video-info">
					<p><b><?php echo $image['image_title'];?></b></p>
					<p>
					<?php
						if (strlen((string) $image['image_description']) > 100)
						{
							$i = 99;
							while($image['image_description'][$i] != ' ' && $i < strlen($image['image_description']) - 1)
							{
								$i++;
							}
							echo substr($image['image_description'], 0, $i).'...';
						}
						else
						{
							echo $image['image_description'];
						}
					?>
					</p>
					<p><?php echo @$image['image_link'];?></p>
				</div>
				<div class="clearbreak"></div>
			</div>
			<?php
			$i++;
		}
	}elseif($selectMode !='sync'){
		?>
			<div class="jsn-bglabel video-no-found">
				<span class="jsn-icon64 icon-remove"></span>
				<?php echo JText::_('SHOWLIST_NOTICE_NO_IMAGES_FOUND'); ?>
			</div>
		<?php
	}else{
	?>
	<?php } ?>
<?php } ?>
