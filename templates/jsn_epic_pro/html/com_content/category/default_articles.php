<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::core();

// Create some shortcuts.
$params		= &$this->item->params;
$n			= count($this->items);
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<?php if (empty($this->items)) : ?>
<?php if ($this->params->get('show_no_articles',1)) : ?>

<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
<?php endif; ?>
<?php else : ?>
<form action="<?php echo JFilterOutput::ampReplace(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (($this->params->get('filter_field') != 'hide') || ($this->params->get('show_pagination_limit'))) : ?>
	<div class="jsn-infofilter">
		<?php if ($this->params->get('filter_field') != 'hide') : ?>
		<span class="jsn-titlefilter">
		<label class="filter-search-lbl" for="filter-search"><?php echo JText::_('COM_CONTENT_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?></label>
		<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
		</span>
		<?php endif; ?>
		<?php if ($this->params->get('show_pagination_limit')) : ?>
		<span class="jsn-filter-limit"> <?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160; <?php echo $this->pagination->getLimitBox(); ?> </span>
		<?php endif; ?>     
	</div>
	<?php endif; ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="jsn-infotable">
		<?php if ($this->params->get('show_headings')) :?>
		<tr class="jsn-tableheader">
			<td class="sectiontableheader jsn-table-column-order" width="" align="center"><?php echo JText::_('JGLOBAL_NUM'); ?></td>
			<td class="sectiontableheader jsn-table-column-title"><?php  echo JHTML::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder) ; ?></td>
			<?php if ($this->params->get('list_show_author',1)) : ?>
			<td class="sectiontableheader jsn-table-column-author" width="30%"><?php echo JHTML::_('grid.sort', 'JAUTHOR', 'author', $listDirn, $listOrder); ?></td>
			<?php endif; ?>
			<?php if ($date = $this->params->get('list_show_date')) : ?>
			<td class="sectiontableheader jsn-table-column-date" width="20%"><?php echo JHTML::_('grid.sort', 'COM_CONTENT_'.$date.'_DATE', 'a.created', $listDirn, $listOrder); ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('list_show_hits',1)) : ?>
			<td class="sectiontableheader jsn-table-column-hits" width="" align="center"><?php echo JHTML::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?></td>
			<?php endif; ?>
		</tr>
		<?php endif; ?>
		<?php foreach ($this->items as $i => $article) : ?>
		<?php if ($this->items[$i]->state == 0) : ?>
		<tr class="system-unpublished sectiontableentry<?php echo $i % 2 +1; ?>">
			<?php else: ?>
		<tr class="sectiontableentry<?php echo $i % 2 +1; ?>">
			<?php endif; ?>
			<td class="jsn-table-column-order" width="" align="center"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<?php if (in_array($article->access, $this->user->getAuthorisedViewLevels())) : ?>
			<td class="jsn-table-column-title"><a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid)); ?>"> <?php echo $this->escape($article->title); ?> </a>
				<?php if ($article->params->get('access-edit')) : ?>
				<span class="icon-edit"><?php echo JHtml::_('icon.edit',$article, $params); ?></span>
				<?php endif; ?></td>
			<?php if ($this->params->get('list_show_author',1)) : ?>
			<td class="jsn-table-column-author"><?php $author =  $article->author ?>
				<?php $author = ($article->created_by_alias ? $article->created_by_alias : $author);?>
				<?php if (!empty($article->contactid ) &&  $this->params->get('link_author') == true):?>
				<?php echo JHTML::_(
										'link',
										JRoute::_('index.php?option=com_contact&view=contact&id='.$article->contactid),
										$author
								); ?>
				<?php else :?>
				<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
				<?php endif; ?></td>
				<?php endif; ?>
			<?php if ($this->params->get('list_show_date')) : ?>
			<td class="jsn-table-column-date"><?php echo JHTML::_('date',$article->displayDate, $this->escape(
				$this->params->get('date_format', JText::_('DATE_FORMAT_LC3')))); ?></td>
			<?php endif; ?>
			<?php if ($this->params->get('list_show_hits',1)) : ?>
			<td class="jsn-table-column-hits" width="" align="center"><?php echo $article->hits; ?></td>
			<?php endif; ?>
			<?php else : // Show unauth links. ?>
				<td>
					<?php
						echo $this->escape($article->title).' : ';
						$menu		= JFactory::getApplication()->getMenu();
						$active		= $menu->getActive();
						$itemId		= $active->id;
						$link = JRoute::_('index.php?option=com_users&view=login&Itemid='.$itemId);
						$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug));
						$fullURL = new JURI($link);
						$fullURL->setVar('return', base64_encode($returnURL));
					?>
					<a href="<?php echo $fullURL; ?>" class="register">
						<?php echo JText::_( 'COM_CONTENT_REGISTER_TO_READ_MORE' ); ?></a>
				</td>
			<?php endif; ?>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php if ($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2 && $this->pagination->get('pages.total') > 1)) : ?>
	<div class="jsn-pagination-container"> <?php echo $this->pagination->getPagesLinks(); ?>
		<p class="jsn-pageinfo"><?php echo $this->pagination->getPagesCounter(); ?></p>
	</div>
	<?php endif; ?>
	<?php if ($this->category->getParams()->get('access-create')) : ?>
	<div class="jsn-article-submit"><?php echo JHtml::_('icon.create', $article, $article->params); ?></div>
	<?php  endif; ?>
	<!-- @TODO add hidden inputs -->
	<input type="hidden" name="filter_order" value="" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<input type="hidden" name="limitstart" value="" />
</form>
<?php endif; ?>
