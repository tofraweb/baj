<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal'); 
JHTML::_('behavior.formvalidation');  
?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
    
    var text = <?php echo $this->editor->getContent( 'template' ); ?> 
    if (document.formvalidator.isValid(form) && text != '') {
			<?php echo $this->editor->save( 'template' ) ; ?> 		
			submitform( pressbutton );
		} else {
		  var msg = "<?php echo JText::_( 'COM_N3TTEMPLATE_INPUT_ERRORS', true ); ?>\n";
		  if($('title').hasClass('invalid')){msg += "\n\t* <?php echo JText::_( 'COM_N3TTEMPLATE_INPUT_EMPTY_TITLE', true ); ?>";}
		  if(text == ''){msg += "\n\t* <?php echo JText::_( 'COM_N3TTEMPLATE_INPUT_NO_CONTENT', true ); ?>";}
		  alert(msg);
    }
	}
</script> 
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col width-60 fltlft">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_N3TTEMPLATE_DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title" title="<?php echo JText::_( 'COM_N3TTEMPLATE_TITLE_DESC' ); ?>">
					<?php echo JText::_( 'COM_N3TTEMPLATE_TITLE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area required" type="text" name="title" id="title" size="50" maxlength="250" value="<?php echo $this->data->title;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="note" title="<?php echo JText::_( 'COM_N3TTEMPLATE_NOTE_DESC' ); ?>">
					<?php echo JText::_( 'COM_N3TTEMPLATE_NOTE' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="note" id="note" size="50" maxlength="250" value="<?php echo $this->data->note;?>" />
			</td>
		</tr>		
		<tr>
			<td align="right" class="key">
				<label for="category" title="<?php echo JText::_( 'COM_N3TTEMPLATE_CATEGORY_DESC' ); ?>">
					<?php echo JText::_( 'COM_N3TTEMPLATE_CATEGORY' ); ?>:
				</label>
			</td>
			<td>
				<?php echo n3tTemplateHelperHTML::categoryTree( $this->data->category_id, 'category_id' ); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
			  <label for="published" title="<?php echo JText::_( 'COM_N3TTEMPLATE_PUBLISHED_DESC' ); ?>">
				  <?php echo JText::_( 'COM_N3TTEMPLATE_PUBLISHED' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
			  <label for="access" title="<?php echo JText::_( 'COM_N3TTEMPLATE_ACCESS_DESC' ); ?>">
				  <?php echo JText::_( 'COM_N3TTEMPLATE_ACCESS' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['access']; ?>
			</td>
		</tr>		
		<tr>
			<td align="right" class="key">
			  <label for="display_access" title="<?php echo JText::_( 'COM_N3TTEMPLATE_DISPLAY_ACCESS_DESC' ); ?>">
				  <?php echo JText::_( 'COM_N3TTEMPLATE_DISPLAY_ACCESS' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['display_access']; ?>
			</td>
		</tr>		
		<tr>
			<td align="right" class="key">
				<label for="ordering" title="<?php echo JText::_( 'COM_N3TTEMPLATE_ORDERING_DESC' ); ?>">
					<?php echo JText::_( 'COM_N3TTEMPLATE_ORDERING' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['ordering']; ?>
			</td>
		</tr>
		<?php if($this->data->id) { ?>
		<tr>
			<td align="right" class="key">
				<label title="<?php echo JText::_( 'COM_N3TTEMPLATE_ID_DESC' ); ?>">
					<?php echo JText::_( 'COM_N3TTEMPLATE_ID' ); ?>:
				</label>
			</td>
			<td>				
				<span class="readonly"><?php echo $this->data->id;?></span>
			</td>
		</tr>
    <?php } ?>				
	</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_N3TTEMPLATE_TEMPLATE' ); ?></legend>

		<table class="admintable">
		<tr>
			<td>
			  <?php echo $this->editor->display( 'template',  htmlspecialchars($this->data->template, ENT_QUOTES), '550', '300', '60', '20' ) ; ?>
			</td>
		</tr>
		</table>
	</fieldset>	
</div>
<div class="col width-40 fltrt">
	<fieldset class="adminform">	
		<legend><?php echo JText::_( 'COM_N3TTEMPLATE_AUTOTEMPLATE_CONTENT_CATEGORIES' ); ?></legend>
	  <table class="admintable">
  		<?php foreach($this->lists['autotemplates'] as $position => $categories) { ?>
  		<tr>
	   		<td width="100" align="right" class="key">
      		<label for="" title="<?php echo JText::_( 'COM_N3TTEMPLATE_ARTICLE_POSITION_'.strtoupper($position).'_DESC' ); ?>">
      			<?php echo JText::_( 'COM_N3TTEMPLATE_ARTICLE_POSITION_'.strtoupper($position) ); ?>:
      		</label>
	   		</td>
	   		<td>      		
  		    <?php echo $categories; ?>
  		  </td>
		  </tr>
      <?php } ?>
    </table>
	</fieldset>
</div>
<div class="col width-40 fltrt clrrt">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_N3TTEMPLATE_PARAMETERS' ); ?></legend>
		<?php $this->renderParams();?>
	</fieldset>
</div>
<div class="clr"></div>

	<input type="hidden" name="option" value="com_n3ttemplate" />
	<input type="hidden" name="cid[]" value="<?php echo $this->data->id; ?>" />
	<input type="hidden" name="view" value="templates" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>