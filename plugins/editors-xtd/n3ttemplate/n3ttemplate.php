<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport('joomla.version');

class plgButtonN3tTemplate extends JPlugin
{

	function plgButtonN3tTemplate(& $subject, $config)
	{
		parent::__construct($subject, $config); 		
	}

	function onDisplay($name)
	{
	  $this->loadLanguage('', JPATH_ADMINISTRATOR);    
      
		$doc=& JFactory::getDocument();		
    $css = '.button2-left .n3ttemplate a {padding: 0 24px 0 6px !important; background: url("/media/com_n3ttemplate/images/icon-16-n3ttemplate.png") no-repeat scroll right transparent;}'."\n";
    $css.= '.button2-left .n3ttemplate {padding-right: 6px;}'."\n";
    $doc->addStyleDeclaration($css);
     
		JHTML::_('behavior.modal');
    $link = 'index.php?option=com_n3ttemplate&amp;view=button&amp;tmpl=component&amp;e_name='.$name;
    
		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('PLG_EDITORS_XTD_N3TTEMPLATE_TEMPLATES'));
		$button->set('name', 'n3ttemplate blank');
		$button->set('options', "{handler: 'iframe', size: {x: 570, y: 400}}");

    $this->autoTemplate($name);
    
		return $button;
	}
	
	function autoTemplate($name) 
	{
	  $app =& JFactory::getApplication(); 
	  if ($app->isAdmin()) {
      if (JVersion::isCompatible('1.6.0'))
        $useAutoTemplate = JRequest::getCmd('option')=='com_content'
          && JRequest::getCmd('view')=='article'
          && JRequest::getCmd('layout')=='edit'
          && !JRequest::getInt('id');
      else
        $useAutoTemplate = JRequest::getCmd('option')=='com_content'
          && JRequest::getCmd('task')=='add';
    } else {
      if (JVersion::isCompatible('1.6.0'))
        $useAutoTemplate = JRequest::getCmd('option')=='com_content'
          && JRequest::getCmd('view')=='form'
          && JRequest::getCmd('layout')=='edit'
          && !JRequest::getInt('a_id');
      else
        $useAutoTemplate = JRequest::getCmd('option')=='com_content'
          && JRequest::getCmd('view')=='article'
          && JRequest::getCmd('layout')=='form'
          && !JRequest::getInt('id');
    }    
    if ( $useAutoTemplate ) {
      JHTML::_('behavior.mootools'); 
      $doc =& JFactory::getDocument();
      $getContent = $this->_subject->getContent($name); 
      $setContent = $this->_subject->setContent($name,'response');
      // JCE strange behavior
      $setContent = str_replace("'response'", "response", $setContent);       
      if (JVersion::isCompatible('1.6.0')) {
        $catName = 'jform[catid]';
        $confirm = JText::_('PLG_EDITORS_XTD_N3TTEMPLATE_AUTOTEMPLATE_CONFIRM_REPLACE',false, false);
      } else {
        $catName = 'catid';
        $confirm = str_replace("\n", '\n', JText::_('PLG_EDITORS_XTD_N3TTEMPLATE_AUTOTEMPLATE_CONFIRM_REPLACE',false));
      }
      $script = 
        'var n3tLastTemplate = "";'."\n".
        ''."\n".
        'function n3tLoadAutoTemplate() {'."\n".
        '  var catid = document.getElement("select[name='.$catName.']");'."\n".
        '  var content = '.$getContent."\n".
        '  var canLoad = catid;'."\n".
        '  if (canLoad) {'."\n".
        '    if (content && (content!=n3tLastTemplate))'."\n".
        '      canLoad = confirm("'.$confirm.'");'."\n".
        '    else'."\n".
        '      canLoad = true;'."\n".
        '    if (canLoad) {'."\n".
        '      if (typeof Request != "undefined") {'."\n".
        '        new Request({'."\n".
        '          method : "get",'."\n".
        '          url: "'.JURI::base().'index.php?option=com_n3ttemplate&view=button&task=autotemplate&id="+catid.get("value"),'."\n".
        '          onComplete : function(response) {'."\n".
        '            '.$setContent."\n".
        '            n3tLastTemplate = '.$getContent."\n".
        '          }'."\n".
        '        }).send();'."\n".      
        '      } else if (typeof Ajax != "undefined") {'."\n".
        '        new Ajax("'.JURI::base().'index.php?option=com_n3ttemplate&view=button&task=autotemplate&id="+catid.getValue(), {'."\n".
        '          method : "get",'."\n".
        '          onComplete : function(response) {'."\n".
        '            '.$setContent."\n".
        '            n3tLastTemplate = '.$getContent."\n".
        '          }'."\n".
        '        }).request();'."\n".        
        '      }'."\n".
        '    }'."\n".
        '  }'."\n".
        '}'."\n".
        ''."\n".        
        'window.addEvent("domready",function() {'."\n".
        '  var catid = document.getElement("select[name='.$catName.']");'."\n".
        '  if (catid) {'."\n".
        '    catid.addEvent("change",function(e) {'."\n".
        '      n3tLoadAutoTemplate();'."\n".        
        '    })'."\n".        
        '  }'."\n";
      if (!JVersion::isCompatible('1.6.0')) {
      $script.=
        '  var sectionid = document.getElement("select[name=sectionid]");'."\n".
        '  if (sectionid) {'."\n".
        '    sectionid.addEvent("change",function(e) {'."\n".
        '      n3tLoadAutoTemplate();'."\n".        
        '    })'."\n".        
        '  }'."\n";
      }  
      $script.=          
        '})'."\n".
        ''."\n".
        'window.addEvent("load",function() {'."\n".
        '  n3tLoadAutoTemplate();'."\n".        
        '})'."\n";
      $doc->addScriptDeclaration($script); 
    }   	
	}
}
