<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.version');

class n3tTemplateUpdateInfo extends JObject {
  public $loaded = false;
  public $updateFound = false;
  public $currentVersion = '';
  public $updateVersion = '';
  public $updateUrl = '';
  public $updateDetailsUrl = '';
  
  public $fullLoaded = false;
  public $updateInfoUrl = '';
  public $updateInfoTitle = '';
  public $updateDownloadUrl = '';
  
	public function __construct($full = false)
	{
		parent::__construct();		
		$this->load($full);
	}

  private function load($full) {
    if (JVersion::isCompatible('1.6.0')) {
      $manifest = JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_n3ttemplate' .DS. 'install.j16.xml';
      $xml = JFactory::getXML($manifest);
      if($xml && $xml->getName() == 'extension') {
        $this->currentVersion = (string)$xml->version;
        $this->updateUrl = (string)$xml->updateservers->server; 
      }        
    } else {
      $manifest = JPATH_ADMINISTRATOR .DS. 'components' .DS. 'com_n3ttemplate' .DS. 'manifest.j15.xml';
      $xml = & JFactory::getXMLParser('Simple');
      if ($xml->loadFile($manifest) && is_object($xml->document) && $xml->document->name() == 'install') {
        $element = & $xml->document->version[0];
		    $this->currentVersion = $element ? $element->data() : '';
		    
		    $element = & $xml->document->updateservers[0];
        if ($element) {		    
		      $element = & $element->server[0];
		      $this->updateUrl = $element ? $element->data() : '';
		    }
      } 
    }
    
    if ($this->updateUrl) {
      if (JVersion::isCompatible('1.6.0')) {
        $xml = @simplexml_load_file($this->updateUrl, 'JXMLElement');
        if($xml && $xml->getName() == 'extensionset') {
          $elements = $xml->xpath('//extension[@element="com_n3ttemplate"]');
          if ($elements && !empty($elements)) {
            $element = $elements[0];
            if ($element) {
              $this->updateVersion = (string)$element->attributes()->version;
              $this->updateDetailsUrl = (string)$element->attributes()->detailsurl;
            }
          }                      
        }
      } else {      
        $xml = & JFactory::getXMLParser('Simple');
        $xmlStr = trim( @file_get_contents($this->updateUrl) );                 
        if ($xmlStr != '' && $xml->loadString($xmlStr) && is_object($xml->document) && $xml->document->name() == 'extensionset') {
          $elements = & $xml->document->extension;
          foreach ($elements as $element) {
            $attributes = $element->attributes(); 
            if ($attributes['element'] == "com_n3ttemplate") {
              $this->updateVersion = $attributes['version'];
              $this->updateDetailsUrl = $attributes['detailsurl'];
              break;
            }
          }   		      		    
        }         
      }
    } 
    
    $this->loaded = $this->currentVersion && $this->updateVersion;
    if ($this->loaded)
      $this->updateFound = version_compare($this->updateVersion, $this->currentVersion) == 1;
       
    if ($full && $this->updateFound && $this->updateDetailsUrl) {
      if (JVersion::isCompatible('1.6.0')) {
        $xml = @simplexml_load_file($this->updateDetailsUrl, 'JXMLElement');        
        if($xml && $xml->getName() == 'updates') {
          $elements = $xml->xpath('//update');
          if ($elements && !empty($elements)) {
            foreach ($elements as $element) {
              if ($element->targetplatform               
              && (string)$element->targetplatform->attributes()->name=='joomla'
              && preg_match('/'.$element->targetplatform->attributes()->version.'/', JVERSION))  {
                $this->fullLoaded = true;
                $this->updateInfoUrl = (string)$element->infourl;
                $this->updateInfoTitle = (string)$element->infourl->attributes()->title;
                $downloads = $element->xpath('//downloads//downloadurl');
                $this->updateDownloadUrl = (string)$downloads[0];
                break;
              } 
            }
          }                      
        }
      } else {
        $xml = & JFactory::getXMLParser('Simple');
        $xmlStr = trim( @file_get_contents($this->updateDetailsUrl) );
        if ($xmlStr != '' && $xml->loadString($xmlStr) && is_object($xml->document) && $xml->document->name() == 'updates') {
          $elements = & $xml->document->update;
          foreach ($elements as $element) {
            $targetPlatform = $element->targetplatform;
            if ($targetPlatform) {
              $targetPlatform = $targetPlatform[0];
              $attributes = $targetPlatform->attributes();
              if ($attributes['name']=='joomla'
              && preg_match('/'.$attributes['version'].'/', JVERSION))  {
                $this->fullLoaded = true;                
                $this->updateInfoUrl = $element->infourl[0]->data();
                $attributes = $element->infourl[0]->attributes();
                $this->updateInfoTitle = $attributes['title'];
                $downloads = $element->downloads[0]->downloadurl;
                $this->updateDownloadUrl = $downloads[0]->data();
                break;              
              }
            }                           
          }   		      		    
        }         
      }      
    }       
  }
	
}

class n3tTemplateHelperUpdate extends JObject
{

	public static function checkVersion() {
	  $app =& JFactory::getApplication();
	  $updateCheck = $app->getUserState( "com_n3ttemplate.update.check", false);	  	  
    if (!$updateCheck) {      
      $updateInfo = new n3tTemplateUpdateInfo();
      $app->setUserState( "com_n3ttemplate.update.found", $updateInfo->updateFound);
      $app->setUserState( "com_n3ttemplate.update.check", true);
    } 
    
    return $app->getUserState( "com_n3ttemplate.update.found", false);		  	    
	}
  	
  public static function versionInfo() {
    return new n3tTemplateUpdateInfo(true);
  }
  
}
?>