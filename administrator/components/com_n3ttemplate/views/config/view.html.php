<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport('joomla.version');

class n3tTemplateViewConfig extends JView {

	function display($tpl=null) {
    $this->loadHelper('html');
	       
		n3tTemplateHelperHTML::toolbarTitle(JText::_('COM_N3TTEMPLATE_CONFIGURATION'));		
		n3tTemplateHelperHTML::assets();
		
	  JToolBarHelper::apply();	  
	  JToolBarHelper::save();	  
    JToolBarHelper::cancel();
    JToolBarHelper::divider();    			 		
    JToolBarHelper::help('config',true);
    
    parent::display($tpl);  
	}
	
	function renderParams() {
	  if (JVersion::isCompatible('1.6.0')) {
		  $form = $this->get('Form');
		  $component = $this->get('Component');
		  if ($form && $component->params) {
			 $form->bind($component->params);
		  }
		  echo JHtml::_('tabs.start','n3ttemplate-configuration-tabs', array('useCookie'=>1));
		  $fieldSets = $form->getFieldsets();
      foreach ($fieldSets as $name => $fieldSet) {
			$label = empty($fieldSet->label) ? 'COM_CONFIG_'.$name.'_FIELDSET_LABEL' : $fieldSet->label;
			echo JHtml::_('tabs.panel',JText::_($label), 'publishing-details');
			if (isset($fieldSet->description) && !empty($fieldSet->description)) 
				echo '<p class="tab-description">'.JText::_($fieldSet->description).'</p>';
	    ?>
			<ul class="config-option-list">
			<?php foreach ($form->getFieldset($name) as $field) { ?>
				<li>
				<?php echo $field->hidden ? '' : $field->label; ?>
				<?php echo $field->input; ?>
        </li>
			<?php } ?>
      </ul>
	    <div class="clr"></div>
	    <?php
		  }
      echo JHtml::_('tabs.end');
    } else {
      $params=&$this->get('params');
      echo $params->render();
    }
  }
}
?>