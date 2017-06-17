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
				<?php
				echo $lists['category'];
				echo $lists['position'];
				?>
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
				<?php echo JHTML::_('grid.sort',  'COM_N3TTEMPLATE_CONTENT_CATEGORY', 'cc.title', @$lists['order_Dir'], @$lists['order'] ); ?>				
				</th>				
				<th>
				<?php echo JHTML::_('grid.sort',  'COM_N3TTEMPLATE_ARTICLE_POSITION', 'at.position', @$lists['order_Dir'], @$lists['order'] ); ?>				
				</th>				
				<th>
				<?php echo JHTML::_('grid.sort',  'COM_N3TTEMPLATE_TEMPLATE', 't.title', @$lists['order_Dir'], @$lists['order'] ); ?>				
				</th>				
				<th width="15%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'COM_N3TTEMPLATE_CATEGORY', 'c.title', @$lists['order_Dir'], @$lists['order'] ); ?>
        </th> 
		</thead>
		<tbody>
		<?php
			$k=0;
			for($i=0;$i<count($this->data);$i++) {
				$row=$this->data[$i];
				
				$link='index.php?option=com_n3ttemplate&view=templates&task=edit&cid[]='.$row->template_id;
				?>
				<tr class="row<?php echo $k; ?>">
					<td><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td><?php echo JHTML::_('grid.checkedout',$row,$i); ?></td>
					<td><?php echo $row->content_category_title; ?></td>
					<td><?php echo $row->position_title; ?></td>
					<td><?php if(JTable::isCheckedOut($this->user->get('id'),$row->template_checked_out)) {
						echo $row->title;
					} else {
						?>
						<a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
						<?php
					}
					echo n3tTemplateHelperHTML::smallsub(array(JText::_('COM_N3TTEMPLATE_NOTE') => $row->note));
					?></td>
					<td><?php echo $row->category_title; ?></td>
				</tr>
				<?php
				$k=1-$k;
			}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>	
		</tfoot>
	</table>
</div>
<input type="hidden" name="option" value="com_n3ttemplate" />
<input type="hidden" name="view" value="autotemplates" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" /> 
<?php echo JHTML::_('form.token'); ?>
</form>