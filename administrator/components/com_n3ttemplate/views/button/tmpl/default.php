<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted Access');

JHTML::_('behavior.mootools'); 
JHTML::script('mootree.js','media/system/js/');
JHTML::stylesheet('mootree.css','media/system/css/');

$mootree_url=JURI::root(true).'/media/com_n3ttemplate/mootree/';
$eName	= JRequest::getVar('e_name');
$eName	= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
?>
<script language="javascript" type="text/javascript">

var tree;

function insertTemplate()
{	
  if (tree.selected && tree.selected.data.template) {
    if (typeof Request != "undefined") {
      new Request({
       method : 'get',
       url: '<?php echo JURI::base(); ?>index.php?option=com_n3ttemplate&view=button&task=template&'+tree.selected.data.template,
       onComplete : function(response) {
         window.parent.jInsertEditorText(response, '<?php echo $eName; ?>');
         window.parent.SqueezeBox.close();
       }
      }).send();
    } else if (typeof Ajax != "undefined") {
      new Ajax('<?php echo JURI::base(); ?>index.php?option=com_n3ttemplate&view=button&task=template&'+tree.selected.data.template, {
       method : 'get',
       onComplete : function(response) {
         window.parent.jInsertEditorText(response, '<?php echo $eName; ?>');
         window.parent.SqueezeBox.close();
       }
      }).request();
    }
	}
	return false;
}

function insertTemplateLink() 
{
  if (tree.selected && tree.selected.data.templateid) { 
    window.parent.jInsertEditorText('{n3ttemplate '+tree.selected.data.templateid+'}', '<?php echo $eName; ?>');
    window.parent.SqueezeBox.close();
  }
}
			
window.addEvent('domready', function() {
  tree = new MooTreeControl({
    div: 'n3tTemplateTree',
    mode: 'files',
    grid: true,
    theme: '<?php echo $mootree_url; ?>theme.gif',
    onSelect: function(node, state) {
      if (state) {
        $('n3tTemplateButtonInsert').disabled = !node.data.template;
        $('n3tTemplateButtonInsertLink').disabled = !node.data.templateid;
<?php if ($this->params->get( 'show_preview', 1 )) { ?>
        if (node.data.template) {
          $('n3tTemplatePreview').setProperty('src', '<?php echo JURI::base(); ?>index.php?option=com_n3ttemplate&view=button&task=preview&tmpl=component&'+node.data.template); 
        }
<?php } ?>    
      }
    },
    loader: {icon:'<?php echo $mootree_url; ?>loader.gif', text:'<?php echo JText::_('COM_N3TTEMPLATE_LOADING'); ?>', color:'#a0a0a0'}    
  },{
    text: '<?php echo JText::_('COM_N3TTEMPLATE_TEMPLATES'); ?>',
    open: true
  });
  
  tree.root.load('<?php echo JURI::base(); ?>index.php?option=com_n3ttemplate&view=button&format=xml');			
});
</script>  
<div id="n3tTemplateWrapper">
  <div id="n3tTemplateButtons">
    <button onclick="insertTemplateLink();" id="n3tTemplateButtonInsertLink" disabled="disabled"><?php echo JText::_( 'COM_N3TTEMPLATE_INSERT_TEMPLATE_LINK' ); ?></button>
    <button onclick="insertTemplate();" id="n3tTemplateButtonInsert" disabled="disabled"><?php echo JText::_( 'COM_N3TTEMPLATE_INSERT_TEMPLATE' ); ?></button> 
  </div>
  <div id="n3tTemplateTree"<?php echo $this->params->get( 'show_preview', 1 ) ? '' : ' class="n3tTemplateTreeFull"'; ?>>
  </div>
  <?php if ($this->params->get( 'show_preview', 1 )) { ?>
  <iframe id="n3tTemplatePreview"></iframe>
  <?php } ?>
</div>