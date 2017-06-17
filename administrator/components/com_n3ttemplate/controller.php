<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class n3tTemplateController extends JController {
	protected $_url=null;
	protected $_editurl=null;
  	
	private $_modelName=null;
	private $_viewName=null;
	private $_rawFields=null;
	
	function __construct($config=array()) {
		parent::__construct($config);		
	}

	function _setUrl($url) {
  	$this->_url=$url;
	}

	function _setEditUrl($url) {
  	$this->_editurl=$url;
	}

	function _setModelName($modelName) {
  	$this->_modelName=$modelName;
	}

	function _setViewName($viewName) {
  	$this->_viewName=$viewName;
	}
	
	function _setRawFields($rawFields) {
	  if ($rawFields) {
	    if (is_string($rawFields))
	      $this->_rawFields=explode(',',$rawFields);
	    elseif (is_array($rawFields))  
	      $this->_rawFields=$rawFields;
    } else
  	  $this->_rawFields=null;
	}

	function display() {
  	parent::display();
	}
	 
	function orderup()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel($this->_modelName);
		if ($model->move(-1))
		  $this->setRedirect($this->_url,JText::_( 'COM_N3TTEMPLATE_NEW_ORDERING_SAVED' ));
		else
      $this->setRedirect($this->_url,$model->getError(),'error');		  
	}

	function orderdown()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel($this->_modelName);
		if ($model->move(1))
		  $this->setRedirect($this->_url,JText::_( 'COM_N3TTEMPLATE_NEW_ORDERING_SAVED' ));
		else
      $this->setRedirect($this->_url,$model->getError(),'error');		  
	}
  	
	function saveorder()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_N3TTEMPLATE_SELECT_ITEM' ) );
		}

		$model = $this->getModel($this->_modelName);
		if ($model->saveorder($cid, $order))
		  $this->setRedirect($this->_url,JText::_( 'COM_N3TTEMPLATE_NEW_ORDERING_SAVED' ));
		else
      $this->setRedirect($this->_url,$model->getError(),'error');		  
	} 
	
	function publish()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_N3TTEMPLATE_SELECT_ITEM' ) );
		}

		$model = $this->getModel($this->_modelName);
		if(!$model->publish($cid, 1)) 
			$this->setRedirect($this->_url,$model->getError(),'error');
		else 
			$this->setRedirect($this->_url);			
	}


	function unpublish()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_N3TTEMPLATE_SELECT_ITEM' ) );
		}

		$model = $this->getModel($this->_modelName);
		if(!$model->publish($cid, 0))
			$this->setRedirect($this->_url,$model->getError(),'error');
		else 
			$this->setRedirect($this->_url);			
	}
 	
	function edit() {		
	  JRequest::setVar('view',$this->_viewName);
		JRequest::setVar('layout','form');
		JRequest::setVar('hidemainmenu',1);

		$model=$this->getModel($this->_modelName);
		$model->checkout();
		
		parent::display();
	}
		
  function _save($close, $copy, $new) {
		JRequest::checkToken() or jexit('Invalid token');
		
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		if ($copy)
		  $post['id'] = 0;
		else
		  $post['id'] = (int) $cid[0];
		if ($this->_rawFields && is_array($this->_rawFields)) {
		  foreach($this->_rawFields as $rawField)
        $post[$rawField] = JRequest::getVar( $rawField, '', 'post', 'string', JREQUEST_ALLOWRAW ); 
    }

		$model=$this->getModel($this->_modelName);
		if( $id = $model->store($post, $copy) ) {
  		if ($new)
  		  $url = $this->_editurl;
		  else if (!$close)
  		  $url = $this->_editurl.$id;
  		else
  		  $url = $this->_url;
      $this->setRedirect($url,JText::_('COM_N3TTEMPLATE_SAVED_ITEM'));         			
		} else {
      $url = $this->_editurl.$cid[0]; 
			$this->setRedirect($url,$model->getError(),'error');
		}
		
		$model->checkin();
	}
  	
  function save() {
    $this->_save(true,false,false);
	}
	
	function apply() {
    $this->_save(false,false,false);
	}

	function save2copy() {
    $this->_save(false,true,false);
	}

	function save2new() {
    $this->_save(false,false,true);
	}

	function copy() {
		JRequest::checkToken() or jexit('Invalid token');
		
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid); 
    	
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_N3TTEMPLATE_SELECT_ITEM' ) );
		} 
          	
		$model=$this->getModel($this->_modelName);		
		if(!$model->copy($cid)) {
			$this->setRedirect($this->_url,$model->getError(),'error');
		} else {
			$this->setRedirect($this->_url,JText::_('COM_N3TTEMPLATE_COPIED_ITEM'));
		}
	}

	function batch()
	{
		JRequest::checkToken() or jexit( 'Invalid Token' );

    $data	= JRequest::getVar('batch', array(), 'post', 'array');    
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_N3TTEMPLATE_SELECT_ITEM' ) );
		}

		$model = $this->getModel($this->_modelName);
		if(!$model->batch($cid, $data)) 
			$this->setRedirect($this->_url,$model->getError(),'error');
		else 
			$this->setRedirect($this->_url,JText::_('COM_N3TTEMPLATE_BATCH_SUCCESS'));			
	}

	function cancel() {
		JRequest::checkToken() or jexit('Invalid token');
		
		$model=$this->getModel($this->_modelName);
		$model->checkin();
		
		$this->setRedirect($this->_url);
	}
	
	function remove() {
		JRequest::checkToken() or jexit('Invalid token');
		
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid); 
    	
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_N3TTEMPLATE_SELECT_ITEM' ) );
		} 
          	
		$model=$this->getModel($this->_modelName);		
		if(!$model->remove($cid)) {
			$this->setRedirect($this->_url,$model->getError(),'error');
		} else {
			$this->setRedirect($this->_url,JText::_('COM_N3TTEMPLATE_REMOVED_ITEM'));
		}
	}
	
	function restore() {
		JRequest::checkToken() or jexit('Invalid token');
		
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid); 
    	
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_N3TTEMPLATE_SELECT_ITEM' ) );
		} 
          	
		$model=$this->getModel($this->_modelName);		
		if(!$model->restore($cid)) {
			$this->setRedirect($this->_url,$model->getError(),'error');
		} else {
			$this->setRedirect($this->_url,JText::_('COM_N3TTEMPLATE_RESTORED_ITEM'));
		}
	}
	
	function delete() {
		JRequest::checkToken() or jexit('Invalid token');
		
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid); 
    	
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_N3TTEMPLATE_SELECT_ITEM' ) );
		} 
          	
		$model=$this->getModel($this->_modelName);		
		if(!$model->delete($cid)) {
			$this->setRedirect($this->_url,$model->getError(),'error');
		} else {
			$this->setRedirect($this->_url,JText::_('COM_N3TTEMPLATE_DELETED_ITEM'));
		}
	}	
}
?>