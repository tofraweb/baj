<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
jimport('joomla.version');

class n3tTemplateInstallHelper
{
  private $app = null;
  private $db = null;
  private $parent = null;
  private $status = null;

	public function __construct()
	{
		$this->app = &JFactory::getApplication();
    $this->db = & JFactory::getDBO();

    $this->status = new JObject();
    $this->status->modules = array();
    $this->status->plugins = array();
    $this->status->extra = array();
    $this->status->texts = array();
    $this->status->operation = '';
	}

	private function setParent($parent) {
    if (!$this->parent) {
      if (JVersion::isCompatible('1.6.0')) {
        $this->parent = &$parent->getParent();
        $this->manifest = &$this->parent->getManifest();
      } else {
        $this->parent = &$parent->parent;
        $this->manifest = &$parent->manifest;
      }
      $this->status->src = $this->parent->getPath('source');
      $this->status->trg = $this->parent->getPath('extension_administrator');

      $lang = &JFactory::getLanguage();
      $lang->load('com_n3ttemplate',JPATH_ADMINISTRATOR);
    }
  }

  private function parseSqlFile($file) {
  	$buffer = JFile::read($file);

  	if ( $buffer === false ) {
  		return false;
  	}

  	$queries = JInstallerHelper::splitSql($buffer);

  	if (count($queries) == 0) {
  		return 0;
  	}

  	foreach ($queries as $query)
  	{
  		$query = trim($query);
  		if ($query != '' && $query{0} != '#') {
  			$this->db->setQuery($query);
  			if (!$this->db->query()) {
  				JError::raiseWarning(1, 'JInstaller::install: '.JText::_('SQL Error')." ".$this->db->stderr(true));
  				return false;
  			}
  		}
  	}
  }
  
  private function updateSchema($version) {
    $update = &$this->manifest->update;
    if (is_array($update)) $update = &$update[0];
    if (isset($update)) {
      $schemas = &$update->schemas;
      if (is_array($schemas)) $schemas = &$schemas[0];
      if (isset($schemas)) {
        foreach ($schemas->children() as $schemapath) {
          $attributes = &$schemapath->attributes();
          $schemapath = $schemapath->data();
          $ext = false;
          if (!JVersion::isCompatible('1.6.0') && $attributes['type'] == 'mysql')
            $ext='sql';
          elseif ($attributes['type'] == 'script')
            $ext='php';
          if ($ext) {
            $files = JFolder::files($this->status->trg.DS.$schemapath,'^[0-9]+\.[0-9]+\.[0-9]+\.'.$ext.'$');
            usort($files,"version_compare");
            foreach($files as $file) {
              $file = str_replace('.'.$ext,'',$file);
              if (version_compare($file,$version,'>')) {
                if ($attributes['type'] == 'mysql')
                  $this->parseSqlFile($this->status->trg.DS.$schemapath.DS.$file.'.'.$ext);
                elseif ($attributes['type'] == 'script')
                  require($this->status->trg.DS.$schemapath.DS.$file.'.'.$ext);
              }
            }
          }
        }        
      }
    }
  }
  
  private function updateVersion() {
    if (!JVersion::isCompatible('1.6.0')) {
      $version = &$this->manifest->version;
      if (is_array($version)) $version =&$version[0];
      $version = $version->data();

      JFile::copy($this->status->src.DS.'install'.DS.'install.check.php',$this->status->trg.DS.'install'.DS.'install.check.php');
      $buffer = JFile::read($this->status->src.DS.'install'.DS.'install.check.php');
      $buffer.= "\n";
      $buffer.= "<".'?php $version ="'.$version.'"; ?'.">";
      JFile::write($this->status->trg.DS.'install'.DS.'install.check.php',$buffer);
    }
  }
  
  private function installModules() {
    $modules = &$this->manifest->modules;
    if (is_array($modules)) $modules = &$modules[0];
    if (isset($modules)) {
      $attributes = &$modules->attributes();
      $folder = isset($attributes['folder']) ? $attributes['folder'] : 'modules';
    	foreach ($modules->children() as $module) {
        $attributes = &$module->attributes();
    		$name = isset($attributes['module']) ? $attributes['module'] : '';
    		if (!empty($name)) {
    		  $path = $this->status->src.DS.$folder.DS.$name;
          if (JVersion::isCompatible('1.6.0')) {
            JFile::move($name.'.j16.xml',$name.'.xml',$path);
            JFile::delete($path.DS.$name.'.j15.xml');
          } else {
            JFile::move($name.'.j15.xml',$name.'.xml',$path);
            JFile::delete($path.DS.$name.'.j16.xml');
          }
      		$client = isset($attributes['client']) ? $attributes['client'] : 'site';
      		$position = isset($attributes['position']) ? $attributes['position'] : ($client == 'site' ? 'left' : 'n3ttemplate.cpanel');
      		$ordering = isset($attributes['ordering']) ? $attributes['ordering'] : '99';
      		
      		$query = "SELECT 1 FROM #__modules WHERE module=".$this->db->Quote($name);
          $this->db->setQuery($query);
          $installed = $this->db->loadResult();

      		$installer = new JInstaller;
      		$result = $installer->install($path);
      		if ($result) {
      		  if ($installed) $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_UPDATED');
      		  else $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_INSTALLED');
      		} else { 
      		  if ($installed) $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_UPDATED');
      		  else $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_INSTALLED');
          } 
      		$this->status->modules[] = array('name'=>$name, 'client' => $client, 'position' => $position, 'result'=>$result, 'text_result'=>$text_result );
          
          if (!$installed) {
            if (JVersion::isCompatible('1.6.0')) {
              $query = "UPDATE #__modules SET position=".$this->db->Quote($position).", ordering=".$this->db->Quote($ordering).", published=1 WHERE module=".$this->db->Quote($name);
            } else {
          		$query = "SELECT title FROM #__modules WHERE module=".$this->db->Quote($name);
              $this->db->setQuery($query);
      		    $title = $this->db->loadResult();      		    
              $title = JText::_($title);
              $query = "UPDATE #__modules SET title=".$this->db->Quote($title).", position=".$this->db->Quote($position).", ordering=".$this->db->Quote($ordering).", published=1 WHERE module=".$this->db->Quote($name);
            }
            $this->db->setQuery($query);
            $this->db->query();
            if (JVersion::isCompatible('1.6.0')) {
              $query = "INSERT IGNORE INTO #__modules_menu (moduleid, menuid) SELECT id,0 FROM #__modules WHERE module=".$this->db->Quote($name);
              $this->db->setQuery($query);
              $this->db->query();
            }
          }
        }
    	}
    }
  }

  private function installPlugins() {
    $plugins = &$this->manifest->plugins;
    if (is_array($plugins)) $plugins = &$plugins[0];
    if (isset($plugins)) {
      $attributes = &$plugins->attributes();      
      $folder = isset($attributes['folder']) ? $attributes['folder'] : 'plugins';
    	foreach ($plugins->children() as $plugin) {
    	  $attributes = &$plugin->attributes();
    		$name = isset($attributes['plugin']) ? $attributes['plugin'] : '';
    		$group = isset($attributes['group']) ? $attributes['group'] : '';
    		if (!empty($name) && !empty($group)) {
      		$path = $this->status->src.DS.$folder.DS.$group.DS.$name;
          if (JVersion::isCompatible('1.6.0')) {
            JFile::move($name.'.j16.xml',$name.'.xml',$path);
            JFile::delete($path.DS.$name.'.j15.xml');
          } else {
            JFile::move($name.'.j15.xml',$name.'.xml',$path);
            JFile::delete($path.DS.$name.'.j16.xml');
          }
                    
          if (JVersion::isCompatible('1.6.0')) {
    		    $query = "SELECT 1 FROM #__extensions WHERE type='plugin' and element=".$this->db->Quote($name)." AND folder=".$this->db->Quote($group);
          } else {
            $query = "SELECT 1 FROM #__plugins WHERE element=".$this->db->Quote($name)." AND folder=".$this->db->Quote($group);
          }
      		$this->db->setQuery($query);
      		$installed = $this->db->loadResult();

      		$installer = new JInstaller;
      		$result = $installer->install($path);
      		if ($result) {
      		  if ($installed) $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_UPDATED');
      		  else $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_INSTALLED');
      		} else { 
      		  if ($installed) $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_UPDATED');
      		  else $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_INSTALLED');
          } 
      		$this->status->plugins[] = array('name'=>$name, 'group'=>$group, 'result'=>$result, 'text_result'=>$text_result);
          
          if (!$installed) {
            if (JVersion::isCompatible('1.6.0')) {
      		    $query = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' and element=".$this->db->Quote($name)." AND folder=".$this->db->Quote($group);
            } else {
              $query = "SELECT name FROM #__plugins WHERE element=".$this->db->Quote($name)." AND folder=".$this->db->Quote($group);
      		    $this->db->setQuery($query);
      		    $title = $this->db->loadResult();
              $title = JText::_($title);
              $query = "UPDATE #__plugins SET name=".$this->db->Quote($title).", published=1 WHERE element=".$this->db->Quote($name)." AND folder=".$this->db->Quote($group);              
            }
        		$this->db->setQuery($query);
        		$this->db->query();
        	}
    		}
    	}
    }
  }

  private function installJoomfish() {
    $joomfish = &$this->manifest->joomfish;
    if (is_array($joomfish)) $joomfish = &$joomfish[0];  
    if (isset($joomfish) && count($joomfish->children())) {
      $result = false;
      if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements')) {
    		$result = true;
    		
    		foreach ($joomfish->children() as $element) {
    			$result = JFile::copy($this->status->src.DS.'admin'.DS.'contentelements'.DS.$element->data(),JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS.$element->data()) && $result;
    		}
      }
  		if ($result) $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_INSTALLED');
 		  else $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_INSTALLED');      
      $this->status->extra[] = array('name'=>'Joom!Fish content elements', 'result'=>$result, 'text_result'=>$text_result);
    }
  }

  private function installDemo() {
    $demo = &$this->manifest->demo;
    if (is_array($demo)) $demo = &$demo[0];
    if (isset($demo) && isset($demo->sql) && count($demo->sql)) {
      $sql = &$demo->sql;
      if (is_array($sql)) $sql =&$sql[0];
      $result = $this->parent->parseSQLFiles($sql);
  		if ($result) $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_INSTALLED');
 		  else $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_INSTALLED');      
      $this->status->extra[] = array('name'=>JText::_('COM_N3TTEMPLATE_INSTALL_DEMO_DATA'), 'result'=>$result, 'text_result'=>$text_result);
    }
  }

  public function install($parent) {
    $this->setParent($parent);
    
    $this->status->operation = 'install';
    $this->status->texts['ok'] = JText::_('COM_N3TTEMPLATE_INSTALL_INSTALLED');
    $this->status->texts['ko'] = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_INSTALLED');
    $this->status->texts['heading'] = JText::_('COM_N3TTEMPLATE_INSTALL_INSTALL_HEADING');

    if (!JVersion::isCompatible('1.6.0')) {
      $this->db->setQuery('UPDATE #__components SET link="" WHERE option="com_n3ttemplate"');
      $this->db->query();

      $this->updateVersion();
    }

    $this->installModules();
    $this->installPlugins();
    $this->installJoomfish();
    $this->installDemo();
  }

  public function update($parent) {
    $this->setParent($parent);

    $this->status->operation = 'update';
    $this->status->texts['ok'] = JText::_('COM_N3TTEMPLATE_INSTALL_UPDATED');
    $this->status->texts['ko'] = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_UPDATED');
    $this->status->texts['heading'] = JText::_('COM_N3TTEMPLATE_INSTALL_UPDATE_HEADING');

    $version=false;
    if (JVersion::isCompatible('1.6.0')) {
  		$row	= JTable::getInstance('extension');
  		$eid	= $row->find(
  			array(
  				'element'	=> 'com_n3ttemplate',
  				'type'		=>'component'
  			)
  		);
      $query = $this->db->getQuery(true);
			$query->select('version_id')->from('#__schemas')->where('extension_id = ' . $eid);
			$this->db->setQuery($query);
			$version = $this->db->loadResult();
    } else {
      require_once($this->status->trg.DS.'install'.DS.'install.check.php');
      if (!$version) $version='1.5.0';
    }

    $this->updateSchema($version);
    $this->updateVersion();

    $this->installModules();
    $this->installPlugins();
    $this->installJoomfish();
    
	  $app =& JFactory::getApplication();
    $app->setUserState( "com_n3ttemplate.update.found", false);
    $app->setUserState( "com_n3ttemplate.update.check", false);
  }

  private function uninstallModules() {
    $modules = &$this->manifest->modules;
    if (is_array($modules)) $modules = &$modules[0];
    if (isset($modules)) {
    	foreach ($modules->children() as $module) {
    	  $attributes = &$module->attributes();
    		$name = isset($attributes['module']) ? $attributes['module'] : '';
    		if (!empty($name)) {
      		$client = isset($attributes['client']) ? $attributes['client'] : 'site';
      		if (JVersion::isCompatible('1.6.0'))
      		  $query = "SELECT extension_id FROM #__extensions WHERE type='module' and client_id=".($client == 'site' ? 0 : 1)." and element=".$this->db->Quote($name);
      		else
      		  $query = "SELECT id FROM #__modules WHERE module = ".$this->db->Quote($name)."";
      		$this->db->setQuery($query);
      		$db_modules = $this->db->loadResultArray();
      		$result = false;
      		if (count($db_modules)) {
      		  $result = true;
      			foreach ($db_modules as $db_module) {
      				$installer = new JInstaller;
      				$result = $installer->uninstall('module', $db_module, $client == 'site' ? 0 : 1) && $result;
      			}
      		}
      		if ($result) $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_REMOVED');
      		else $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_REMOVED');
      		$this->status->modules[] = array ('name'=>$name, 'client'=>$client, 'result'=>$result, 'text_result'=>$text_result);
        }
    	}
    }
  }

  private function uninstallPlugins() {
    $plugins = &$this->manifest->plugins;
    if (is_array($plugins)) $plugins = &$plugins[0];
    if (isset($plugins)) {
    	foreach ($plugins->children() as $plugin) {
    	  $attributes = &$plugin->attributes();
    		$name = isset($attributes['plugin']) ? $attributes['plugin'] : '';
    		$group = isset($attributes['group']) ? $attributes['group'] : '';
    		if (!empty($name) && !empty($group)) {
    		  if (JVersion::isCompatible('1.6.0'))
    		    $query = "SELECT extension_id FROM #__extensions WHERE type='plugin' and folder=".$this->db->Quote($group)." and element=".$this->db->Quote($name)."";
          else
            $query = 'SELECT id FROM #__plugins WHERE element = '.$this->db->Quote($name).' AND folder = '.$this->db->Quote($group);
      		$this->db->setQuery($query);
      		$db_plugins = $this->db->loadResultArray();
      		$result = false;
      		if (count($db_plugins)) {
      		  $result = true;
      			foreach ($db_plugins as $db_plugin) {
      				$installer = new JInstaller;
      				$result = $installer->uninstall('plugin', $db_plugin, 0) && $result;
      			}
      		}
      		if ($result) $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_REMOVED');
      		else $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_REMOVED');
      		$this->status->plugins[] = array ('name'=>$name, 'group'=>$group, 'result'=>$result, 'text_result'=>$text_result);
        }
    	}
    }
  }

  private function uninstallJoomfish() {
    $joomfish = &$this->manifest->joomfish;
    if (is_array($joomfish)) $joomfish = &$joomfish[0];
    if (isset($joomfish) && count($joomfish->children())) {
    	$result = false;
      if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements')) {
    		$result = true;
    		foreach ($joomfish->children() as $element) {
    			if(JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS.$element->data()))
    				JFile::delete(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS.$element->data());
    		}
    	}    	
   		if ($result) $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_REMOVED');
   		else $text_result = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_REMOVED');
    	$this->status->extra[] = array('name'=>'Joom!Fish content elements', 'result'=>$result, 'text_result'=>$text_result);
    }
  }

  public function uninstall($parent) {
    $this->setParent($parent);

    $this->status->operation = 'uninstall';
    $this->status->texts['ok'] = JText::_('COM_N3TTEMPLATE_INSTALL_REMOVED');
    $this->status->texts['ko'] = JText::_('COM_N3TTEMPLATE_INSTALL_NOT_REMOVED');
    $this->status->texts['heading'] = JText::_('COM_N3TTEMPLATE_INSTALL_UNINSTALL_HEADING');

    $this->uninstallModules();
    $this->uninstallPlugins();
    $this->uninstallJoomfish();

    $this->displayStatus();
  }

  public function postflight($type, $parent) {    
    $this->displayStatus();
  }

  private function displayStatus() {
    $k = 0;
    $status = &$this->status;
    if ($status->operation != 'uninstall') {
?>
<img src="../media/com_n3ttemplate/images/icon-48-n3ttemplate.png" width="48" height="48" alt="n3tTemplate" align="right" />
<?php
    }
?>
<h2><?php echo $status->texts['heading']; ?></h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('COM_N3TTEMPLATE_INSTALL_EXTENSION'); ?></th>
			<th width="30%" align="center"><?php echo JText::_('COM_N3TTEMPLATE_INSTALL_STATUS'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'n3tTemplate '.JText::_('COM_N3TTEMPLATE_INSTALL_COMPONENT'); ?></td>
			<td><strong style="color: green;"><?php echo $status->texts['ok']; ?></strong></td>
		</tr>
		<?php if (count($status->modules)) { ?>
		<tr class="row<?php echo (++$k % 2); ?>">
			<th><?php echo JText::_('COM_N3TTEMPLATE_INSTALL_MODULE'); ?></th>
			<th><?php echo JText::_('COM_N3TTEMPLATE_INSTALL_CLIENT'); ?></th>
			<th>&nbsp;</th>
		</tr>
		<?php foreach ($status->modules as $module) { ?>
		<tr class="row<?php echo (++$k % 2); ?>">
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo $module['client']; ?></td>
			<td><strong style="color: <?php echo ($module['result']) ? 'green' : 'red' ?>;"><?php echo $module['text_result']; ?></strong></td>
		</tr>
		<?php } ?>
		<?php } ?>
		<?php if (count($status->plugins)) { ?>
		<tr class="row<?php echo (++$k % 2); ?>">
			<th><?php echo JText::_('COM_N3TTEMPLATE_INSTALL_PLUGIN'); ?></th>
			<th><?php echo JText::_('COM_N3TTEMPLATE_INSTALL_GROUP'); ?></th>
			<th>&nbsp;</th>
		</tr>
		<?php foreach ($status->plugins as $plugin) { ?>
		<tr class="row<?php echo (++$k % 2); ?>">
			<td class="key"><?php echo $plugin['name']; ?></td>
			<td class="key"><?php echo $plugin['group']; ?></td>
			<td><strong style="color: <?php echo ($plugin['result']) ? 'green' : 'red' ?>;"><?php echo $plugin['text_result']; ?></strong></td>
		</tr>
		<?php } ?>
		<?php } ?>
		<?php if (count($status->extra)) { ?>
		<tr class="row<?php echo (++$k % 2); ?>">
			<th colspan=2><?php echo JText::_('COM_N3TTEMPLATE_INSTALL_EXTRA'); ?></th>
			<th>&nbsp;</th>
		</tr>
		<?php foreach ($status->extra as $extra) { ?>
		<tr class="row<?php echo (++$k % 2); ?>">
			<td class="key" colspan="2"><?php echo $extra['name']; ?></td>
			<td><strong style="color: <?php echo ($extra['result']) ? 'green' : 'red' ?>;"><?php echo $extra['text_result']; ?></strong></td>
		</tr>
		<?php } ?>
		<?php } ?>
	</tbody>
</table>
<?php
    if ($status->operation != 'uninstall') {
      $lang = &JFactory::getLanguage();
      if (JFile::exists($status->trg.DS.'install'.DS.'notes'.DS.$lang->getTag().'.php'))
        require($status->trg.DS.'install'.DS.'notes'.DS.$lang->getTag().'.php');
      elseif (JFile::exists($status->trg.DS.'install'.DS.'notes'.DS.'en-GB.php'))
        require($status->trg.DS.'install'.DS.'notes'.DS.'en-GB.php');
    }
  }
}
?>