<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: images.php 14414 2012-07-26 09:22:37Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.controller' );
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
class ImageShowControllerImages extends JController
{
	function __construct($config = array())
	{
		parent::__construct($config);
	}

	function display($cachable = false, $urlparams = false)
	{
		$document 		= &JFactory::getDocument();
		$viewType		= $document->getType();
		$viewName 		= JRequest::getCmd('view', 'images');
		$view 			= &$this->getView( $viewName, $viewType);

		$view->setLayout('default');
		$view->display();
	}

	function close()
	{
		global $mainframe;
		JRequest::checkToken() or jexit( 'Invalid Token' );
		$link = 'index.php?option=com_imageshow';

		$mainframe->redirect($link);
	}

	public function loadSourceImages()
	{
		$cateName    = JRequest::getVar('cateName', '');
		$showListID  = JRequest::getVar('showListID', '');
		$sourceType  = JRequest::getVar('sourceType', '');
		$sourceName  = JRequest::getVar('sourceName', '');
		$selectMode  = JRequest::getVar('selectMode', '');
		$offset		 = JRequest::getVar('offset', 0);
		$imageSource = JSNISFactory::getSource($sourceName, $sourceType, $showListID);
		$images = $imageSource->loadImages(array('album'=>$cateName,'showlistid'=>$showListID,'offset'=>$offset));
		if($offset==0 && ($sourceType=="external" || $sourceName == 'folder'))
			$countImages = $imageSource->countImages($cateName);

		if ($selectMode == 'sync')
		{
			$syncIsSelected = $imageSource->checkSync($cateName);
		}
		else
		{
			$syncIsSelected = '';
		}
		include(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'views'.DS.'images'.DS.'tmpl'.DS.'images.php');
		jexit();
	}

	function saveshowlist()
	{
		global $objectLog;
		$session 		= JFactory::getSession();
		$identifier		= md5('jsn_imageshow_inserted_images_identify_name');
		$session->set($identifier, array(), 'jsnimageshowsession');
		$showListID  = JRequest::getVar('showListID', '');
		$sourceName  = JRequest::getVar('sourceName', '');
		$sourceType  = JRequest::getVar('sourceType', '');
		$images      =  JRequest::getVar('images', '');
		$images		 = json_decode($images);
		$imageSource = JSNISFactory::getSource($sourceName, $sourceType, $showListID);
		$syncMode	 = JRequest::getVar('syncMode','');
		if ($syncMode == 'sync')
		{
			$imageSource->resetShowListImages();
			$sync = 1;
		}
		else
		{
			$sync = 0;
		}
		$infoInsert 				= array();
		$infoInsert['showlistID']	= $showListID;

		foreach ($images as $key => $img)
		{
			$ImgDetail = $img->img_detail;
			//check if image not exists => save or exists => not save
			//$exists = $imageSource->chechImageExists($img->imgid, $showListID);

			/*if($exists > 0)
			{
				//return false;
			}
			else {*/
				//if (!$exists)
				//{
					$img->imgid									= urldecode($img->imgid);
					$infoInsert['imgID'][]						= null;
					$infoInsert['imgExtID'][]					= $img->imgid;
					$infoInsert['order'][$img->imgid]			= $img->order + 1;
					$infoInsert['albumID'][$img->imgid]			= urldecode($img->albumid);
					if ($sourceName == 'folder')
					{
						$infoInsert['imgSmall'][$img->imgid]		= urldecode($img->img_thumb);
					}
					else
					{
						$infoInsert['imgSmall'][$img->imgid]		= $ImgDetail->image_small;
					}
					$infoInsert['imgMedium'][$img->imgid]		= $ImgDetail->image_medium;
					$infoInsert['imgBig'][$img->imgid]			= $ImgDetail->image_big;
					$infoInsert['imgTitle'][$img->imgid]		= $ImgDetail->image_title ;
					$infoInsert['imgLink'][$img->imgid]			= $ImgDetail->image_link ;
					$infoInsert['imgDescription'][$img->imgid]	= empty($ImgDetail->image_description)?' ':$ImgDetail->image_description;
					$infoInsert['customData'][$img->imgid]		= 0;
				//}
			//}
		}
		$session->set($identifier, $images, 'jsnimageshowsession');
		$objJSNImages	= JSNISFactory::getObj('classes.jsn_is_images');
		$objJSNImages->addImages($infoInsert);
		jexit();
	}
	/**
	* process delete image from showlist
	*/
	function deleteimageshowlist()
	{
		$showListID  = JRequest::getVar('showListID', '');
		$sourceName  = JRequest::getVar('sourceName', '');
		$sourceType  = JRequest::getVar('sourceType', '');
		$imageID	 = urldecode(JRequest::getVar('imageID',''));
		$images      = JRequest::getVar('images', '');
		$images		 = json_decode($images);
		$imageSource = JSNISFactory::getSource($sourceName, $sourceType, $showListID);
		$config['imgExtID']  = array('0'=>$imageID);
		$config['showlistID'] = $showListID;
		$imageSource->removeImages($config);
		// get total images of each show list and return to ajax function to reindex showlist
		$totalimage = JRequest::getVar('totalimages') - 1;
		echo $totalimage;
		jexit();
		//removeImages
	}
	function savesync()
	{
		$objJSNImages		= JSNISFactory::getObj('classes.jsn_is_images');
		$showlistID 		= JRequest::getVar('showlist_id');
		$AlbumID 			= array(JRequest::getVar('album_extid'));
		$objJSNImages->saveSyncAlbum($showlistID,$AlbumID);
		jexit();
	}
	function removesync(){
		$syncCate    = JRequest::getVar('album_extid', '');
		$showListID  = JRequest::getVar('showlist_id', '');
		$sourceType  = JRequest::getVar('sourceType', '');
		$sourceName  = JRequest::getVar('sourceName', '');
		$imageSource = JSNISFactory::getSource( $sourceName, $sourceType, $showListID );
		$imageSource->removeSync($syncCate);
		jexit();
	}

	/**
	* Ajax function remove all sync (click button Sync to disable sync)
	*/
	function removeallSync()
	{
		$showListID  = JRequest::getVar('showListID', '');
		$sourceName  = JRequest::getVar('sourceName', '');
		$sourceType  = JRequest::getVar('sourceType', '');
		$imageSource = JSNISFactory::getSource($sourceName, $sourceType, $showListID);
		$syncMode	 = JRequest::getVar('syncMode','');
		$imageSource->resetShowListImages();
		jexit();
	}
	/**
	 *
	 * Check sync
	 */
	public function checksyncalbum()
	{
		$syncCate    = JRequest::getVar('syncCate', '');
		//$syncCate	 = str_replace("cat_","",$syncCate);
		$showListID  = JRequest::getVar('showListID', '');
		$sourceType  = JRequest::getVar('sourceType', '');
		$sourceName  = JRequest::getVar('sourceName', '');

		$imageSource = JSNISFactory::getSource( $sourceName, $sourceType, $showListID );

		if ($imageSource->getShowlistMode() =='sync' && $imageSource->checkSync($syncCate) ){
			$status = 'is_selected';
		}else{
			$status  = 'none';
		}

		jexit($status);
	}

	function checkthumb()
	{
		$session 		= JFactory::getSession();
		$identifier		= md5('jsn_imageshow_inserted_images_identify_name');
		$images			= $session->get($identifier, array(), 'jsnimageshowsession');
		$post 			= JRequest::get('post');
		$objImages      = JSNISFactory::getObj('classes.jsn_is_images');
		$document 		= JFactory::getDocument();
		$tmpImages		= array();
		foreach ($images as $key => $image)
		{
			$ImgDetail = $image->img_detail;
			$isExisted = $objImages->checkThumb($post['showListID'], urldecode($image->imgid));
			if ($isExisted == false)
			{
				$tmpObj = new stdClass();
				$tmpObj->image_big 			= (string) urldecode($ImgDetail->image_big);
				$tmpObj->image_extid 		= (string) urldecode($image->imgid);
				$tmpObj->album_extid 		= (string) urldecode($image->albumid);
				$tmpImages [] 			    = $tmpObj;
			}
		}
		$images			= $session->set($identifier, array(), 'jsnimageshowsession');
		echo  json_encode($tmpImages);
		jexit();
	}

	function createThumb()
	{
		$objImages   = JSNISFactory::getObj('classes.jsn_is_images');
		$post 		 = JRequest::get('post');
		$imageSource = JSNISFactory::getSource($post['sourceName'], $post['sourceType'], $post['showListID']);
		$thumb 		 = $imageSource->createThumb($post['image']);
		$objImages->updateImageThumb($post['showListID'], urldecode($post['image']['image_extid']), urldecode($thumb->image_small));
		jexit();
	}

	function createThumbForPreview()
	{
		$objJSNThumbnail = JSNISFactory::getObj('classes.jsn_is_imagethumbnail');
		$post = JRequest::get('post');
		$JSNISThumbsPath	= JPATH_ROOT.DS.'images'.DS.'jsn_is_thumbs'.DS;
		$imagePath			= JPATH_ROOT.DS.str_replace('/', DS, urldecode($post['imagePath']));

		$tmpReturnedPath = str_replace(JPATH_ROOT.DS, '', $imagePath);
		$returnedPath = JURI::root().str_replace(DS, "/", $tmpReturnedPath);
		// check jsn_is_thumbs folder, if not existed->created
		if (!is_writable(JPATH_ROOT.DS.'images'.DS))
		{
			$data = array("status"=>false, "image_path"=>$returnedPath, "encode_image_path"=>urlencode(str_replace(DS, "/", $tmpReturnedPath)));
			echo json_encode($data);
			exit();
		}

		if (!JFolder::exists($JSNISThumbsPath))
		{
			if(!$objJSNThumbnail->createThumbnailFolder(JPATH_ROOT.DS.'images'.DS, $JSNISThumbsPath))
			{
				$data = array("status"=>false, "image_path"=>$returnedPath, "encode_image_path"=>urlencode(str_replace(DS, "/", $tmpReturnedPath)));
			 	echo json_encode($data);
				exit();
			}
		}

		if (JFolder::exists($JSNISThumbsPath))
		{
			if (is_writable($JSNISThumbsPath))
			{
				//create a folder that contains all images previewed
				$imageFolderPath = $JSNISThumbsPath.str_replace('/', DS, urldecode($post['folderName']));
				if (!JFolder::exists($imageFolderPath))
				{
					if(!$objJSNThumbnail->createThumbnailFolder($JSNISThumbsPath, $imageFolderPath))
					{
						$data = array("status"=>false, "image_path"=>$returnedPath, "encode_image_path"=>urlencode(str_replace(DS, "/", $tmpReturnedPath)));
					 	echo json_encode($data);
						exit();
					}
				}

				if (JFolder::exists($imageFolderPath))
				{
					if (!JFile::exists($imageFolderPath.DS.urldecode($post['imageName'])))
					{
						if(!$objJSNThumbnail->createFileThumbnail($imagePath, $imageFolderPath.DS.urldecode($post['imageName'])))
						{
							$data = array("status"=>false, "image_path"=>$returnedPath, "encode_image_path"=>urlencode(str_replace(DS, "/", $tmpReturnedPath)));
						 	echo json_encode($data);
							exit();
						}
						else
						{
							$tmpReturnedPath = str_replace(JPATH_ROOT.DS, '', $imageFolderPath.DS.urldecode($post['imageName']));
							$returnedPath = JURI::root().str_replace(DS, "/", $tmpReturnedPath);
							$data = array("status"=>true, "image_path"=>$returnedPath, "encode_image_path"=>urlencode(str_replace(DS, "/", $tmpReturnedPath)));
						 	echo json_encode($data);
							exit();
						}
					}
					elseif (JFile::exists($imageFolderPath.DS.urldecode($post['imageName'])) && @filemtime($imagePath) > @filemtime($imageFolderPath.DS.urldecode($post['imageName'])))
					{
						JFile::delete($imageFolderPath.DS.urldecode($post['imageName']));
						if(!$objJSNThumbnail->createFileThumbnail($imagePath, $imageFolderPath.DS.urldecode($post['imageName'])))
						{
							$data = array("status"=>false, "image_path"=>$returnedPath, "encode_image_path"=>urlencode(str_replace(DS, "/", $tmpReturnedPath)));
						 	echo json_encode($data);
							exit();
						}
						else
						{
							$tmpReturnedPath = str_replace(JPATH_ROOT.DS, '', $imageFolderPath.DS.urldecode($post['imageName']));
							$returnedPath = JURI::root().str_replace(DS, "/", $tmpReturnedPath);
							$data = array("status"=>true, "image_path"=>$returnedPath, "encode_image_path"=>urlencode(str_replace(DS, "/", $tmpReturnedPath)));
						 	echo json_encode($data);
							exit();
						}
					}
					else
					{
						$tmpReturnedPath = str_replace(JPATH_ROOT.DS, '', $imageFolderPath.DS.urldecode($post['imageName']));
						$returnedPath = JURI::root().str_replace(DS, "/", $tmpReturnedPath);
						$data = array("status"=>true, "image_path"=>$returnedPath, "encode_image_path"=>urlencode(str_replace(DS, "/", $tmpReturnedPath)));
					 	echo json_encode($data);
						exit();
					}
				}
			}
			else
			{
				$data = array("status"=>true, "image_path"=>$returnedPath, "encode_image_path"=>urlencode(str_replace(DS, "/", $tmpReturnedPath)));
			 	echo json_encode($data);
				exit();
			}
		}
		exit();
	}
}
?>