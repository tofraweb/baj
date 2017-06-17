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

echo n3tTemplateHelperHTML::mootoolsVersion();  
?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

    if (document.formvalidator.isValid(form)) {
			submitform( pressbutton );
		} else {
		  var msg = "<?php echo JText::_( 'COM_N3TTEMPLATE_INPUT_ERRORS', true ); ?>\n";
		  if($('title').hasClass('invalid')){msg += "\n\t* <?php echo JText::_( 'COM_N3TTEMPLATE_INPUT_EMPTY_TITLE', true ); ?>";}
		  alert(msg);
    }
	}
	
	function checkPluginParams() {
	  $$('.n3tTemplatePluginParams').setStyle('display','none');
	  var plugin, use_plugin;
	  if (MooToolsMajor == '1' && MooToolsMinor == '1') {
	    plugin = $('plugin').value;
	    use_plugin = $('use_plugin_params').checked;
    } else {
      plugin = $('plugin').get('value');
      use_plugin = $('use_plugin_params').get('checked');
    }
    if (plugin) $('use_plugin_params').disabled = false;
    else $('use_plugin_params').disabled = true;	  
	  if (plugin && use_plugin)
	    $$('.n3tTemplatePluginParams'+plugin).setStyle('display','block');
  }
  
  window.addEvent('domready',function() {
    checkPluginParams(); 
  });
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
			<td align="right" class="key" title="<?php echo JText::_( 'COM_N3TTEMPLATE_PARENT_DESC' ); ?>">
				<label for="parent">
					<?php echo JText::_( 'COM_N3TTEMPLATE_PARENT' ); ?>:
				</label>
			</td>
			<td>
				<?php echo n3tTemplateHelperHTML::categoryTree( $this->data->parent_id, 'parent_id', $this->data->id ); ?>
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
				<label for="ordering" title="<?php echo JText::_( 'COM_N3TTEMPLATE_ORDERING_DESC' ); ?>">
					<?php echo JText::_( 'COM_N3TTEMPLATE_ORDERING' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['ordering']; ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="plugin" title="<?php echo JText::_( 'COM_N3TTEMPLATE_PLUGIN_DESC' ); ?>">
					<?php echo JText::_( 'COM_N3TTEMPLATE_PLUGIN' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['plugin']; ?>
			</td>
		</tr>		
		<tr>
			<td width="100" align="right" class="key">
				<label for="use_plugin_params" title="<?php echo JText::_( 'COM_N3TTEMPLATE_USE_PLUGIN_PARAMS_DESC' ); ?>">
					<?php echo JText::_( 'COM_N3TTEMPLATE_USE_PLUGIN_PARAMS' ); ?>:
				</label>
			</td>
			<td>
				<input class="" type="checkbox" name="use_plugin_params" id="use_plugin_params"<?php echo $this->data->plugin_params ? ' checked="checked"' : '';?><?php echo $this->data->plugin ? '' : ' disabled="disabled"';?> onchange="checkPluginParams();" />
			</td>
		</tr>		
		<?php if($this->data->id) { ?>
		<tr>
			<td width="100" align="right" class="key">
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
</div>
<div class="col width-40 fltrt">
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_N3TTEMPLATE_PARAMETERS' ); ?></legend>
		<?php $this->renderParams();?>
	</fieldset>
</div>
<div class="clr"></div>

	<input type="hidden" name="option" value="com_n3ttemplate" />
	<input type="hidden" name="cid[]" value="<?php echo $this->data->id; ?>" />
	<input type="hidden" name="view" value="categories" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>