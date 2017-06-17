<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.version');

class n3tTemplateViewTemplate extends JView {

	function display($tpl=null) {
    $this->loadHelper('html');
    JHTML::_('behavior.tooltip');     
	       		
		n3tTemplateHelperHTML::assets();
		
		JToolBarHelper::apply();
	  JToolBarHelper::save();
    if (JVersion::isCompatible('1.6.0')) {    	  
	    JToolBarHelper::save2new();
	    JToolBarHelper::save2copy();
	  } else {
	    JToolBarHelper::custom('save2new','save2new','','COM_N3TTEMPLATE_SAVE2NEW',false,false);
	    JToolBarHelper::custom('save2copy','save2copy','','COM_N3TTEMPLATE_SAVE2COPY',false,false);
	  }
	  JToolBarHelper::divider();
    JToolBarHelper::cancel();
    JToolBarHelper::divider();
    JToolBarHelper::help('template',true);    			
			
    $db	=& JFactory::getDBO();
    $user 	=& JFactory::getUser(); 
    $model	=& $this->getModel();
    $app = &JFactory::getApplication();
    $editor =& JFactory::getEditor();  
      	
		$data=&$this->get('data');
		
		if ($data->id) 
		  n3tTemplateHelperHTML::toolbarTitle($data->title);
		else		
		  n3tTemplateHelperHTML::toolbarTitle(JText::_('COM_N3TTEMPLATE_NEW_TEMPLATE'));
		
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::_( 'COM_N3TTEMPLATE_BEING_EDITED' );
			$app->redirect( 'index.php?option=com_n3ttemplate', $msg );
		}
    		
		$lists = array();
		$query = 'SELECT ordering AS value, title AS text'
			. ' FROM #__n3ttemplate_templates'
			. ' WHERE category_id='.$data->category_id
			. ' AND published>=0'
			. ' ORDER BY ordering';		
    $lists['ordering'] 			= n3tTemplateHelperHTML::ordering($data, $data->id, $query);  
    $lists['published'] 		= n3tTemplateHelperHTML::booleanList('published', $data->published );
    $lists['access'] 		    = n3tTemplateHelperHTML::accesslevel( $data->access );
    $lists['display_access']= n3tTemplateHelperHTML::accesslevel( $data->display_access, 'display_access' );
    $lists['autotemplates'] = array();
    foreach(n3tTemplateHelperContent::getArticlePositions() as $position)
      $lists['autotemplates'][$position] = n3tTemplateHelperHTML::contentCategories('autotemplates['.$position.']', $data->autotemplates[$position] );
    
    JFilterOutput::objectHTMLSafe( $data, ENT_QUOTES, 'template' );
       			
		$this->assignRef('lists',		$lists);
		$this->assignRef('data',		$data);
		$this->assignRef('editor',		$editor);
        			
		parent::display($tpl);
	}
	
	function renderParams() {
	  if (JVersion::isCompatible('1.6.0')) {
		  $form = $this->get('Form');
		  if ($form && $this->data->params) {
			  $temp = new JRegistry;
			  $temp->loadJSON($this->data->params);
        $form->bind($temp);
		  }
	    echo JHtml::_('sliders.start','n3ttemplate-templates-sliders', array('useCookie'=>1));
		  $fieldSets = $form->getFieldsets();
			foreach ($fieldSets as $name => $fieldSet) {
				echo JHtml::_('sliders.panel',JText::_($fieldSet->label), $name.'-options');
				if (isset($fieldSet->description) && trim($fieldSet->description)) { ?>
					<p class="tip"><?php echo $this->escape(JText::_($fieldSet->description));?></p>
				<?php } ?>
				<fieldset class="panelform">
					<ul class="adminformlist">
					<?php foreach ($form->getFieldset($name) as $field) { ?>
						<li><?php echo $field->label; ?><?php echo $field->input; ?></li>
					<?php } ?>
					</ul>
				</fieldset>
			<?php
      }
      echo JHtml::_('sliders.end');
	  } else {	    
  		$file 	= JPATH_COMPONENT.DS.'params'.DS.'template.xml';
	   	$params = new JParameter( $this->data->params, $file );
      echo $params->render();
    }
	}

}
?>