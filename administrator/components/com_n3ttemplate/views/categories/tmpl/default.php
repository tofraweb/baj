<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

$lists = $this->lists;
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_( 'COM_N3TTEMPLATE_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo htmlspecialchars($lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_( 'COM_N3TTEMPLATE_FILTER_DESC' );?>"/>
				<button onclick="this.form.submit();"><?php echo JText::_( 'COM_N3TTEMPLATE_FILTER_APPLY' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'COM_N3TTEMPLATE_FILTER_RESET' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php echo $lists['state']; ?>
			</td>
		</tr>
	</table> 

	<table class="adminlist">
		<thead>
			<tr>
				<th width="5" nowrap="nowrap">
				<?php echo JText::_('COM_N3TTEMPLATE_NUM'); ?>
				</th>
				<th width="5" nowrap="nowrap">
				<input class="inputbox" type="checkbox" name="toggle" value="0" onclick="checkAll('<?php echo count($this->data); ?>')" />
				</th>
				<th>
				<?php echo JHTML::_('grid.sort',  'COM_N3TTEMPLATE_TITLE', 'c.title', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<?php if ($lists["filter_state"]>-2) { ?>
				<th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'COM_N3TTEMPLATE_PLUGIN', 'c.plugin', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
				<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'COM_N3TTEMPLATE_PUBLISHED', 'c.published', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th> 
				<th width="5%" nowrap="nowrap">
				<?php echo JText::_('COM_N3TTEMPLATE_PLUGIN_PUBLISHED'); ?>
        </th> 
				<th width="10%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'COM_N3TTEMPLATE_ACCESS', 'c.access', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th> 
				<th width="9%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'COM_N3TTEMPLATE_ORDERING', 'c.ordering', @$lists['order_Dir'], @$lists['order'] ); ?>
	 			</th>
				<th width="1%">
				<?php echo JHTML::_('grid.order',  $this->data ); ?>
				</th>
				<?php } ?>
        <th width="50" nowrap="nowrap">
				<?php echo JText::_('COM_N3TTEMPLATE_ID'); ?>
				</th>
		</thead>
		<tbody>
		<?php
			$k=0;
			for($i=0;$i<count($this->data);$i++) {
				$row=$this->data[$i];
				
				$link='index.php?option=com_n3ttemplate&view=categories&task=edit&cid[]='.$row->id;
				?>
				<tr class="row<?php echo $k; ?>">
					<td><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td><?php echo JHTML::_('grid.checkedout',$row,$i); ?></td>
					<td><?php if(JTable::isCheckedOut($this->user->get('id'),$row->checked_out) || $lists["filter_state"]==-2) {
						echo $row->treename;
					} else {
						?>
						<a href="<?php echo $link; ?>"><?php echo $row->treename; ?></a>
						<?php
					}
          echo n3tTemplateHelperHTML::smallsub(array(JText::_('COM_N3TTEMPLATE_NOTE') => $row->note));
					?></td>
					<?php if ($lists["filter_state"]>-2) { ?>
					<td align="center">
            <?php echo $row->plugin ? $row->plugin_title.n3tTemplateHelperHTML::smallsub(array(JText::_('COM_N3TTEMPLATE_PLUGIN') => $row->plugin)) : '&nbsp;'; ?>
          </td>
					<td align="center"><?php echo JHTML::_('grid.published',$row,$i); ?></td>
					<td align="center"><?php echo $row->plugin ? n3tTemplateHelperHTML::publishedIcon($row->plugin_published) : '&nbsp;'; ?></td>
					<td align="center">
            <?php echo n3tTemplateHelperHTML::displayAccess($row->access,$row->groupname); ?>
					  <?php echo $row->plugin ? n3tTemplateHelperHTML::smallsub(array(JText::_('COM_N3TTEMPLATE_PLUGIN') => n3tTemplateHelperHTML::displayAccess($row->plugin_access,$row->plugin_groupname))) : ''; ?>
          </td>
					<td class="order" colspan="2" align="center">
						<span><?php echo $this->pagination->orderUpIcon( $i, $row->order_up, 'orderup', 'Move Up', $lists['ordering'] ); ?></span>
						<span><?php echo $this->pagination->orderDownIcon( $i, count($this->data), $row->order_down, 'orderdown', 'Move Down', $lists['ordering'] ); ?></span>						
						<input type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" class="text_area" style="text-align: center" <?php echo $lists['ordering'] ?  '' : 'disabled="disabled" '; ?>/>
					</td>     
          <?php } ?>      					
					<td align="center"><?php echo $row->id; ?></td>
				</tr>
				<?php
				$k=1-$k;
			}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="<?php echo $lists["filter_state"]>-2 ? 10 : 4; ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>	
		</tfoot>
	</table>
	<?php echo $this->loadTemplate('batch'); ?> 
</div>
<input type="hidden" name="option" value="com_n3ttemplate" />
<input type="hidden" name="view" value="categories" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" /> 
<?php echo JHTML::_('form.token'); ?>
</form>