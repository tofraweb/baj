<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.pane');
jimport('joomla.application.module.helper'); 
jimport('joomla.version');

class n3tTemplateHelperHTML extends JObject
{
	/**
	 * Render the CPanel icon
	 *
	 */
	private static function _cpanelIcon( $link, $image, $text )
	{
		$app = &JFactory::getApplication();
		$lang		=& JFactory::getLanguage();
		$template	= $app->getTemplate();
		?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
          <img src="<?php echo JURI::root(true).'/media/com_n3ttemplate/images/icon-48-'.$image.'.png'; ?>" alt="<?php echo $text; ?>" title="<?php echo $text; ?>" />
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
		<?php
	}
  
  public static function cpanelIcons($icons) 
  {
    foreach($icons as $text=>$iconGroup) 
    {
		?>
	<h3 class="cpanelIconsHeader"><?php echo JText::_('COM_N3TTEMPLATE_CPANEL_'.$text)?></h3>	
	<div class="cpanelIcons">
		<?php
		  foreach($iconGroup as $icon) 
      {
        $link = JURI::base().'index.php?option='.($icon['option'] ? $icon['option'] : JRequest::getCmd('option')).($icon['view'] ? '&view='.$icon['view'] : '').($icon['task'] ? '&task='.$icon['task'] : '').($icon['params'] ? '&'.$icon['params'] : '');
        n3tTemplateHelperHTML::_cpanelIcon($link,$icon['icon'],$icon['text']);
      }
		?>
	</div>
	<div class="clr"></div>
		<?php                  
    }
  }
  
	public static function cpanelModules()
	{	
		$modules = &JModuleHelper::getModules('n3ttemplate.cpanel');
		$pane = &JPane::getInstance('sliders', array('allowAllClose' => true));
		echo $pane->startPane("content-pane");

		foreach ($modules as $module) {
			$title = $module->title ;
			echo $pane->startPanel( $title, 'cpanel-panel-'.$module->name );
			echo JModuleHelper::renderModule($module);
			echo $pane->endPanel();
		}

		echo $pane->endPane();
	}   	 

  private static function _getTitle($table, $id)
  {
    $db	=& JFactory::getDBO(); 
    $db->setQuery('SELECT title FROM #__n3ttemplate_'.$table.' WHERE id='.$id);
		return $db->loadResult();
  }

  private static function _globalTitle()
  {
    if (JRequest::getInt('hidemainmenu',0) == 0)
      return '<a href="'.JURI::base().'index.php?option='.JRequest::getCmd('option').'">'.JText::_('COM_N3TTEMPLATE').'</a>';
    else
      return JText::_('COM_N3TTEMPLATE');
  }

  private static function _categoryTitle()
  {
		$app=JFactory::getApplication();
		$option=JRequest::getCmd('option');
		
    $category_id=$app->getUserStateFromRequest($option.'.category_id','category_id',0,'int');
    
    if ($category_id) {
      if (JRequest::getInt('hidemainmenu',0) == 0)
        return '<a href="'.JURI::base().'index.php?option='.JRequest::getCmd('option').'&view=categories">'.n3tTemplateHelperHTML::_getTitle('categories',$category_id).'</a>';
      else
        return n3tTemplateHelperHTML::_getTitle('categories',$category_id);
    } else 
      return JText::_('COM_N3TTEMPLATE_ALL_CATEGORIES');
  }

  public static function toolbarTitle($text = false) 
  {
    $title = array();
    $view=JRequest::getCmd('view');
    $title[] = n3tTemplateHelperHTML::_globalTitle();
    $title[] = '<small>'.$text.'</small>';
     
    JToolBarHelper::title(implode(' - ',$title),'n3ttemplate');
  }
    
  public static function subtoolbar() 
  {
    JSubMenuHelper::addEntry(JText::_('COM_N3TTEMPLATE_CONTROL_PANEL'), JURI::base().'index.php?option='.JRequest::getCmd('option'));
    if (n3tTemplateHelperACL::authorizeAdmin()) {        
      JSubMenuHelper::addEntry(JText::_('COM_N3TTEMPLATE_CATEGORIES'), JURI::base().'index.php?option='.JRequest::getCmd('option').'&view=categories');    
      JSubMenuHelper::addEntry(JText::_('COM_N3TTEMPLATE_TEMPLATES'), JURI::base().'index.php?option='.JRequest::getCmd('option').'&view=templates');
      JSubMenuHelper::addEntry(JText::_('COM_N3TTEMPLATE_AUTOTEMPLATES'), JURI::base().'index.php?option='.JRequest::getCmd('option').'&view=autotemplates');
    }    
    if (n3tTemplateHelperACL::authorizeConfig()) {
      if (JVersion::isCompatible('1.6.0'))
        JSubMenuHelper::addEntry(JText::_('COM_N3TTEMPLATE_PLUGINS'), JURI::base().'index.php?option=com_plugins&view=plugins&filter_folder=n3ttemplate');
      else
        JSubMenuHelper::addEntry(JText::_('COM_N3TTEMPLATE_PLUGINS'), JURI::base().'index.php?option=com_plugins&view=plugins&filter_type=n3ttemplate');
    }
  }  
  
  public static function assets() 
  {
		$doc=& JFactory::getDocument();		
	  $doc->addStyleSheet(JURI::root(true).'/media/com_n3ttemplate/n3ttemplate.css');
  }

  public static function assetsPopup() 
  {
		$doc=& JFactory::getDocument();		
	  $doc->addStyleSheet(JURI::root(true).'/media/com_n3ttemplate/n3ttemplate.popup.css');
  }

	public static function categoryTree( $id, $fieldname, $excludeid = null, $size = 10, $autosubmit = false, $alltext = false )
	{
		$db =& JFactory::getDBO();

		if ( $excludeid ) {
			$excludeid = ' AND id != '.(int) $excludeid;
		} else {
			$excludeid = null;
		}

		if (!$id) $id = 0;

		$query = 'SELECT *, title AS name, parent_id AS parent' .
				' FROM #__n3ttemplate_categories c' .
				' WHERE published != -2 AND plugin = ""' .
				$excludeid .
				' ORDER BY parent_id, ordering';
		$db->setQuery( $query );
		$items = $db->loadObjectList();

		$children = array();

		if ( $items )
		{
			foreach ( $items as $v )
			{			  
				$pt 	= $v->parent_id;
				$list 	= @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}

		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );

		$items 	= array();
		if ($alltext) $items[] = JHTML::_('select.option',  '-1', $alltext );
		$items[] = JHTML::_('select.option',  '0', JText::_( 'COM_N3TTEMPLATE_UNCATEGORIZED' ) );

		foreach ( $list as $item ) {
      if (JVersion::isCompatible('1.6.0'))
        // Joomla 1.6.0 bug #24973
			  $items[] = JHTML::_('select.option',  $item->id, html_entity_decode($item->treename, ENT_COMPAT, 'UTF-8') );
      else
			  $items[] = JHTML::_('select.option',  $item->id, $item->treename );
		}
    
    if ($autosubmit) $autosubmit = ' onchange="this.form.submit();"';
		$output = JHTML::_('select.genericlist',   $items, $fieldname, 'class="inputbox" size="'.$size.'"'.$autosubmit, 'value', 'text', $id );

		return $output;
	}
	
	public static function booleanList( $name, $value = null, $id = null ) {
		$arr = array(
			JHTML::_('select.option', '0', JText::_('COM_N3TTEMPLATE_NO')),
			JHTML::_('select.option', '1', JText::_('COM_N3TTEMPLATE_YES'))
		);
		return JHTML::_('select.genericlist',  $arr, $name, 'class="inputbox" size="1"', 'value', 'text', (int)$value, $id );
	}

  public static function filterState($filter_state) {
    $states = array();
    $states[]=JHTML::_('select.option',2,JText::_('COM_N3TTEMPLATE_FILTER_CURRENT'),'id','title');
    $states[]=JHTML::_('select.option',1,JText::_('COM_N3TTEMPLATE_FILTER_PUBLISHED'),'id','title');
    $states[]=JHTML::_('select.option',0,JText::_('COM_N3TTEMPLATE_FILTER_UNPUBLISHED'),'id','title');
    $states[]=JHTML::_('select.option',-2,JText::_('COM_N3TTEMPLATE_FILTER_TRASHED'),'id','title');
    return JHTML::_('select.genericlist',$states,'filter_state','class="inputbox" size="1" onchange="this.form.submit();"','id','title',$filter_state);    
  }

  public static function filterPosition($filter_position) {
    $positions = array();
    $positions[]=JHTML::_('select.option','',JText::_('COM_N3TTEMPLATE_SELECT_ARTICLE_POSITION'),'id','title');
    $positions[]=JHTML::_('select.option','editor',JText::_('COM_N3TTEMPLATE_ARTICLE_POSITION_EDITOR'),'id','title');
    $positions[]=JHTML::_('select.option','top',JText::_('COM_N3TTEMPLATE_ARTICLE_POSITION_TOP'),'id','title');
    $positions[]=JHTML::_('select.option','bottom',JText::_('COM_N3TTEMPLATE_ARTICLE_POSITION_BOTTOM'),'id','title');
    $positions[]=JHTML::_('select.option','introtop',JText::_('COM_N3TTEMPLATE_ARTICLE_POSITION_INTROTOP'),'id','title');
    $positions[]=JHTML::_('select.option','introbottom',JText::_('COM_N3TTEMPLATE_ARTICLE_POSITION_INTROBOTTOM'),'id','title');
    return JHTML::_('select.genericlist',$positions,'position','class="inputbox" size="1" onchange="this.form.submit();"','id','title',$filter_position);    
  }
  
  public static function displayAccess($access, $groupname) {
		$color_access = '';
    if (!JVersion::isCompatible('1.6.0')) {    
  		if ( !$access )  {
  			$color_access = ' style="color: green;"';
  		} else if ( $access == 1 ) {
  			$color_access = ' style="color: red;"';
  		} else {
  			$color_access = ' style="color: black;"';
  		}
		}

		return '<span'. $color_access .'>'. JText::_( $groupname ) .'</span>';
  }
  
  public static function ordering($value, $id, $query, $neworder = 0) {
    if (JVersion::isCompatible('1.6.0')) {
  		if (is_object($value)) $value = $value->ordering;  
  		if ($id) $neworder = 0;
  		else $neworder = $neworder ? -1 : 1;
  		return JHTML::_('list.ordering','ordering', $query, '', $value, $neworder);      
    } else {
      return JHTML::_('list.specificordering',  $value, $value->id, $query, $neworder );
    }
  } 

	public static function accesslevel($value, $name = 'access', $emptyvalue = false)
	{
	  if (JVersion::isCompatible('1.6.0')) {
	    if ($emptyvalue) 
		    return JHtml::_('access.assetgrouplist', $name, $value, array('class' => 'inputbox', 'size' => '1'), array('title' => $emptyvalue ));
		  else
		    return JHtml::_('access.assetgrouplist', $name, $value, array('class' => 'inputbox', 'size' => '1'));
    } else {
  		$db =& JFactory::getDBO();

  		$query = 'SELECT id AS value, name AS text'
  		. ' FROM #__groups'
  		. ' ORDER BY id'
  		;
  		$db->setQuery( $query );
		  $groups = $db->loadObjectList();
		  if ($emptyvalue) {
	      $empty = new StdClass();
	      $empty->value = '';
	      $empty->text = $emptyvalue;		  
		    $groups = array_merge(array($empty),$groups);
		  }
		  return JHTML::_('select.genericlist', $groups, $name, 'class="inputbox" size="1"', 'value', 'text', $value, '', 1 );
    }
	}
  
	public static function publishedIcon($value, $img1 = 'tick.png', $img0 = 'publish_x.png')
	{
		$img	= $value ? $img1 : $img0;		
    
    if (JVersion::isCompatible('1.6.0')) {
      $alt	= $value ? JText::_('JPUBLISHED') : JText::_('JUNPUBLISHED'); 
		  return $href = JHtml::_('image','admin/'.$img, $alt, NULL, true);
		} else {
      $alt	= $value ? JText::_( 'Published' ) : JText::_( 'Unpublished' );  
		  return '<img src="images/'. $img .'" border="0" alt="'. $alt .'" />';
		}
	}
  
	public static function pluginsList( $name, $value = null, $id = null ) {
	  $plugins = n3tTemplateHelperPlugin::loadPlugins();
	  $empty = new StdClass();
	  $empty->plugin = '';
	  $empty->name = '';
    $plugins = array_merge(array($empty), $plugins); 
		return JHTML::_('select.genericlist',  $plugins, $name, 'class="inputbox" size="1" onchange="checkPluginParams();"', 'plugin', 'name', $value, $id );
	}        
	
	public static function pluginPanel($plugin, $text, $id)
	{
		return '</div></div><div class="panel n3tTemplatePluginParams n3tTemplatePluginParams'.$plugin.'" style="display: none;"><h3 class="pane-toggler title" id="'.$id.'"><a href="javascript:void(0);"><span>'.$text.'</span></a></h3><div class="pane-slider content">';
	}	
	
	public static function mootoolsVersion() {
	  $return = '<script language="javascript" type="text/javascript">'."\n";
	  $return.= 'var MooToolsVersion = new Array();'."\n";
	  $return.= 'var MooToolsMajor, MooToolsMinor, MooToolsRelease;'."\n";

	  $return.= 'MooToolsVersion=MooTools.version.split(".");'."\n";
	  $return.= 'MooToolsMajor=MooToolsVersion[0];'."\n";
	  $return.= 'MooToolsMinor=MooToolsVersion[1];'."\n";
	  $return.= 'MooToolsRelease=MooToolsVersion[2];'."\n";
	  $return.= 'if (MooToolsMinor.substring(0,1) == "1") { '."\n";
	  $return.= '  MooToolsRelease=MooToolsMinor.substring(1,1);'."\n";
	  $return.= '  MooToolsMinor=MooToolsMinor.substring(0,1);'."\n";
	  $return.= '}'."\n";
	  $return.= '</script>'."\n";	 	
    return $return;  
  }
  
  public static function smallsub($values) {
    $return = '';    
    foreach($values as $key => $value) {
      if ($value) {
        if ($return) $return .= ', ';
        if (JVersion::isCompatible('1.6.0'))   
          $return = '<span>'.$key.'</span>: '.$value;
        else
          $return = '<span style="color: #a0a0a0;">'.$key.'</span>: '.$value;
      }
    }
    if ($return) { 
      if (JVersion::isCompatible('1.6.0')) 
        $return = '<p class="smallsub">('.$return.')</p>';
      else
        $return = '<p style="margin: 3px 0 0; color: #666666;">('.$return.')</p>';
    } 
    return $return;
  }
  
  public static function contentCategories($name, $selectedCategories) {
    $db =& JFactory::getDBO();
	  if (JVersion::isCompatible('1.6.0')) {
      $categories = JHtml::_('category.options', 'com_content');     
    } else {
	    $categories = array();
  		$query = 'SELECT id, title FROM #__sections WHERE published = 1 AND scope="content" ORDER BY ordering';
  		$db->setQuery( $query );
  		$sections = $db->loadObjectList();  		
  		foreach ($sections as $section) {
  		  $categories[] = JHTML::_('select.option', '<OPTGROUP>', $section->title );   		  
		    $query = 'SELECT id AS value, title AS text FROM #__categories WHERE section = '.$section->id
		      .' AND published = 1 ORDER BY ordering';
		    $db->setQuery( $query );  
		    $categories = array_merge($categories, $db->loadObjectList());
  		  $categories[] = JHTML::_('select.option', '</OPTGROUP>', '' );   		  
      }      
    }
		$category = JHTML::_('select.genericlist', $categories, $name.'[]', 'class="inputbox" size="10" multiple="multiple"', 'value', 'text', $selectedCategories );
		return $category;     
  }
  
  public static function batchMoveCopy()
  {
		$options = array(
			JHtml::_('select.option', 'copy', JText::_('COM_N3TTEMPLATE_BATCH_COPY')),
			JHtml::_('select.option', 'move', JText::_('COM_N3TTEMPLATE_BATCH_MOVE'))
		);    
		return JHTML::_( 'select.radiolist', $options, 'batch[movecopy]', '', 'value', 'text', 'move');
  }
  
}
?>