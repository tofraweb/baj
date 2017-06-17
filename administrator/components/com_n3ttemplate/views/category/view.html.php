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

class n3tTemplateViewCategory extends JView {

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
	    JToolBarHelper::custom('save2new','save2new.png','',COM_N3TTEMPLATE_SAVE2NEW,false,false);
	    JToolBarHelper::custom('save2copy','save2copy.png','',COM_N3TTEMPLATE_SAVE2COPY,false,false);
	  }
	  JToolBarHelper::divider();	  
    JToolBarHelper::cancel();
    JToolBarHelper::divider();
    JToolBarHelper::help('category',true);        			
			
    $db	=& JFactory::getDBO();
    $user 	=& JFactory::getUser(); 
    $model	=& $this->getModel();
    $app = &JFactory::getApplication();
      	
		$data=&$this->get('data');
		
		if ($data->id) 
		  n3tTemplateHelperHTML::toolbarTitle($data->title);
		else		
		  n3tTemplateHelperHTML::toolbarTitle(JText::_('COM_N3TTEMPLATE_NEW_CATEGORY'));
		
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::_( 'COM_N3TTEMPLATE_BEING_EDITED' );			
			$app->redirect( 'index.php?option=com_n3ttemplate'. $option, $msg );
		}
    		
		$lists = array();
		$query = 'SELECT ordering AS value, title AS text, plugin'
			. ' FROM #__n3ttemplate_categories'
			. ' WHERE parent_id='.$data->parent_id
			. ' AND published>=0'
			. ' ORDER BY ordering';
    $lists['ordering'] 			= n3tTemplateHelperHTML::ordering($data, $data->id, $query);  
		$lists['published'] 		= n3tTemplateHelperHTML::booleanList('published', $data->published );
		$lists['access'] 		    = n3tTemplateHelperHTML::accesslevel( $data->access );
		$lists['plugin'] 	      = n3tTemplateHelperHTML::pluginsList( 'plugin', $data->plugin, 'plugin' );
      			
		$this->assignRef('lists',		$lists);
		$this->assignRef('data',		$data);
        			
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
	    echo JHtml::_('sliders.start','n3ttemplate-categories-sliders', array('useCookie'=>1));
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
      
      $plugins = $this->get('Plugins');      
      foreach ($plugins as $plugin) {        		  
        if ($plugin->form) {
  			  $temp = new JRegistry;
    		  if ($this->data->plugin == $plugin->plugin && $this->data->plugin_params) {
    			  $temp->loadJSON($this->data->plugin_params);
    		  } else {
    			  $temp->loadJSON($plugin->params);
          }      
   			  $temp2 = new JRegistry;
   			  $temp2->loadArray(array('params' => $temp->toArray()));             			     			            
          $plugin->form->bind($temp2);                                        
          
    		  $fieldSets = $plugin->form->getFieldsets('params');
    			foreach ($fieldSets as $name => $fieldSet) {
    				echo n3tTemplateHelperHTML::pluginPanel($plugin->plugin,JText::_("COM_N3TTEMPLATE_PLUGIN").' - '.JText::_($fieldSet->label), $name.'-options');
    				if (isset($fieldSet->description) && trim($fieldSet->description)) { ?>
    					<p class="tip"><?php echo $this->escape(JText::_($fieldSet->description));?></p>
    				<?php } ?>
    				<fieldset class="panelform">
    					<ul class="adminformlist">
    					<?php foreach ($plugin->form->getFieldset($name) as $field) {?>
    						<li><?php echo $field->label; ?><?php echo $field->input; ?></li>
    					<?php } ?>
    					</ul>
    				</fieldset>
    			<?php
          }   
        }
      }      
      echo JHtml::_('sliders.end');
	  } else {
  		$file 	= JPATH_COMPONENT.DS.'params'.DS.'category.xml';
  		$params = new JParameter( $this->data->params, $file );
      echo $params->render();
      $plugins = n3tTemplateHelperPlugin::loadPlugins();
      
      foreach ($plugins as $plugin) {
        ?>
        <div class="n3tTemplatePluginParams n3tTemplatePluginParams<?php echo $plugin->plugin; ?>"<?php echo ($this->data->plugin == $plugin->plugin) ? '' : ' style="display: none;"'; ?>>
        <?php        
        $lang = &JFactory::getLanguage();       
        $lang->load('plg_n3ttemplate_'.$plugin->plugin,JPATH_ADMINISTRATOR);		      
    		$file 	= JPATH_PLUGINS.DS.'n3ttemplate'.DS.$plugin->plugin.'.xml';
    		if ($this->data->plugin == $plugin->plugin && $this->data->plugin_params)
    		  $params = new JParameter( $this->data->plugin_params, $file );
    		else
    		  $params = new JParameter( $plugin->params, $file );
        echo $params->render($plugin->plugin.'_params[params]');
        ?>
        </div>
        <?php          
      }        
    }
	}

}
?>