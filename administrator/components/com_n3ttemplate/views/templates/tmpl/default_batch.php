<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.version');

$lists = $this->lists;
if (JVersion::isCompatible('1.6.0'))
  $clearJS = "document.getElement('select[name=batch[category_id]]').value=-1;document.getElement('select[name=batch[access]]').value='';document.getElement('select[name=batch[display_access]]').value='';"; 
else
  $clearJS = "$('batchcategory_id').value=-1;$('batchaccess').value='';$('batchdisplay_access').value='';";
if ($lists["filter_state"]>-2) { ?>
  <fieldset class="batch">
  	<legend><?php echo JText::_('COM_N3TTEMPLATE_BATCH_OPTIONS');?></legend>  	
		<table class="admintable">
		<tr>
			<td width="200" align="right" class="key">
				<label for="batch-category-id" title="<?php echo JText::_( 'COM_N3TTEMPLATE_BATCH_MOVECOPY_DESC' ); ?>">
					<?php echo JText::_( 'COM_N3TTEMPLATE_BATCH_MOVECOPY' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $lists['batch-category-id']; ?>				
				<?php echo $lists['batch-movecopy']; ?>
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<label for="batch-access" title="<?php echo JText::_( 'COM_N3TTEMPLATE_BATCH_ACCESS_DESC' ); ?>">
					<?php echo JText::_( 'COM_N3TTEMPLATE_BATCH_ACCESS' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $lists['batch-access']; ?>				
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<label for="batch-display-access" title="<?php echo JText::_( 'COM_N3TTEMPLATE_BATCH_DISPLAY_ACCESS_DESC' ); ?>">
					<?php echo JText::_( 'COM_N3TTEMPLATE_BATCH_DISPLAY_ACCESS' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $lists['batch-display-access']; ?>				
			</td>
		</tr>		
		</table> 		
  	<button type="button" onclick="javascript:if (document.adminForm.boxchecked.value==0){alert('<?php echo JText::_('COM_N3TTEMPLATE_SELECT_ITEM',true); ?>');}else{ <?php echo JVersion::isCompatible('1.6.0') ? 'Joomla.' : ''; ?>submitbutton('batch')};">
  		<?php echo JText::_('COM_N3TTEMPLATE_BATCH_PROCESS'); ?>
  	</button>
  	<button type="button" onclick="<?php echo $clearJS; ?>">
  		<?php echo JText::_('COM_N3TTEMPLATE_BATCH_CLEAR'); ?>
  	</button>
  </fieldset>	 
<?php } ?>	
