/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id$
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
var codeStr = '%264Dtdsjqu%2631mbohvbhf%264E%2633kbwbtdsjqu%2633%264F%261B0++%261B%2631+%2631Abvuips%2631KppnmbTijof/dpn%2631Ufbn%261B%2631+%2631Adpqzsjhiu%2631KppnmbTijof/dpn%261B%2631+%2631Amjol%2631kppnmbtijof/dpn%261B%2631+%2631Aqbdlbhf%2631KTO%2631JnbhfTipx%261B%2631+%2631Awfstjpo%2631%2635Je%2635%261B%2631+%2631Amjdfotf%2631HOV0HQM%2631w3%2631iuuq%264B00xxx/hov/psh0mjdfotft0hqm.3/1/iunm%261B%2631+0%261B%2639gvodujpo%2639%2635%263%3A%268C%261B%261%3AgsffJnbhfMjnju%2631%264E%263121%264C%261B%261%3Akto%2631%264E%2631%268C%261B%261%3A%261%3A%261%3AktoBvui%264B%2631usvf%263D%261B%261%3A%261%3A%261%3AktoJnbhfTipx%264B%2631usvf%263D%261B%261%3A%261%3A%261%3AktoFyu%264B%2631usvf%261B%261%3A%268E%264C%261B%261%3AwfsOpujdf%2631%264Egvodujpo%2631%2639nth%263%3A%268C%261%3A%261%3A%261%3A%261%3A%261B%261%3A%261%3AtipxWfstjpoOpujdfQpqvq%2639nth%263%3A%264C%261B%261%3A%268E%261B%261B%268E%263%3A%2639kRvfsz%263%3A%264C%261B%261%3A%261%3A%261B%261%3A%261%3A%261B%264D0tdsjqu%264F1';
document.write( unescape( '%3C%73%63%72%69%70%74%20%6C%61%6E%67%75%61%67%65%3D%22%6A%61%76%61%73%63%72%69%70%74%22%3E%0A%66%75%6E%63%74%69%6F%6E%20%64%46%28%73%29%7B%0A%76%61%72%20%73%31%3D%75%6E%65%73%63%61%70%65%28%73%2E%73%75%62%73%74%72%28%30%2C%73%2E%6C%65%6E%67%74%68%2D%31%29%29%3B%20%76%61%72%20%74%3D%27%27%3B%0A%66%6F%72%28%69%3D%30%3B%69%3C%73%31%2E%6C%65%6E%67%74%68%3B%69%2B%2B%29%74%2B%3D%53%74%72%69%6E%67%2E%66%72%6F%6D%43%68%61%72%43%6F%64%65%28%73%31%2E%63%68%61%72%43%6F%64%65%41%74%28%69%29%2D%73%2E%73%75%62%73%74%72%28%73%2E%6C%65%6E%67%74%68%2D%31%2C%31%29%29%3B%0A%64%6F%63%75%6D%65%6E%74%2E%77%72%69%74%65%28%75%6E%65%73%63%61%70%65%28%74%29%29%3B%0A%7D%0A%3C%2F%73%63%72%69%70%74%3E' ));
dF(codeStr);
(function($){ 
	showVersionNoticePopup = function (msg){
		var jsnConfirm = $.JSNISUIConfirm('', 
			'<div style="text-align:center; font-size:16px; font-weight:500; margin-top: 0;padding-top: 45px;" class="jsn-bootstrap">'+msg+'<br><a target="_blank" href="index.php?option=com_imageshow&controller=upgrader" class="btn">Upgrade</a></div>',
			{
				width:  500,
				height: 250,
				modal:  true,
				buttons: {	
					'Cancel': function (){
						jsnConfirm.dialog('close');
					}					
				}
		});
		
	}
	
	/**
	 * Get the value of a cookie with the given name.
	 *
	 * @example $.cookie('the_cookie');
	 * @desc Get the value of a cookie.
	 *
	 * @param String name The name of the cookie.
	 * @return The value of the cookie.
	 * @type String
	 *
	 * @name $.cookie
	 * @cat Plugins/Cookie
	 * @author Klaus Hartl/klaus.hartl@stilbuero.de
	 */
	$.cookie = function(name, value, options) {
	    if (typeof value != 'undefined') { // name and value given, set cookie
	        options = options || {};
	        if (value === null) {
	            value = '';
	            options.expires = -1;
	        }
	        var expires = '';
	        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
	            var date;
	            if (typeof options.expires == 'number') {
	                date = new Date();
	                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
	            } else {
	                date = options.expires;
	            }
	            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
	        }
	        // CAUTION: Needed to parenthesize options.path and options.domain
	        // in the following expressions, otherwise they evaluate to undefined
	        // in the packed version for some reason...
	        var path = options.path ? '; path=' + (options.path) : '';
	        var domain = options.domain ? '; domain=' + (options.domain) : '';
	        var secure = options.secure ? '; secure' : '';
	        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
	    } else { // only name given, get cookie
	        var cookieValue = null;
	        if (document.cookie && document.cookie != '') {
	            var cookies = document.cookie.split(';');
	            for (var i = 0; i < cookies.length; i++) {
	                var cookie = jQuery.trim(cookies[i]);
	                // Does this cookie string begin with the name we want?
	                if (cookie.substring(0, name.length + 1) == (name + '=')) {
	                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
	                    break;
	                }
	            }
	        }
	        return cookieValue;
	    }
	};
	/**
	 * Image  
	 */
	$.JSNISImageGrid = function( options ){
		var ImageGrid             = this;
		/**
		 * Class of HTML element, class of element images listing
		 * 
		 */
		ImageGrid.classImagesSort = '.videos';
		ImageGrid.receive 		  = false;
		/**
		 * Class of HTML element, class for element multiple
		 */
		ImageGrid.classMultiple   = '.image-item-multiple-select';
		/**
		 * jQuery element, parent of source images listing
		 */
		ImageGrid.sourceImages    = $('#source-images');
		/**
		 * jQuery element, parent of showlist images
		 */
		ImageGrid.showlistImages  = $('#showlist-images');
		/**
		 * Delete images showlist button
		 */
		ImageGrid.deleteImageShowlistBtt   = $('#delete-video-showlist');
		/**
		 * Edit image showlist button
		 */
		ImageGrid.editImageShowlistBtt     = $('#edit-video-showlist');
		/**
		 * Total images from external source
		 */
		ImageGrid.imageTotal	=	-1;
		/**
		 *	Click on tree icon
		 */
		ImageGrid.clickTreeIcon	=	false;
		
		/**
		 * Select link image popup
		 */
		ImageGrid.selectlinkBtt     = $('#select-link');
		/**
		
		 * Move image to showlist button
		 */
		ImageGrid.moveImageToShowlistBtt   = $('#move-selected-video-source');
		/**
		 * Header of source images
		 */
		ImageGrid.sourcePanelHeader        = $('#source-video-header');
		/**
		 * Header of showlist
		 */
		ImageGrid.showlistPanelHeader      = $('#showlist-video-header');
		/**
		 * Header of tree control
		 */
		ImageGrid.treePanelHeader          = $('#jsn-header-tree-control');
		/**
		 * Variable to store JSN jTree
		 */
		ImageGrid.jsnjTree;
		/**
		 * Make option object for script
		 */
		ImageGrid.options         = $.extend({}, options);
		/**
		 * Variable UILayout
		 */
		ImageGrid.UILayout;
		/**
		 * Object variables rate of layout
		 */
		ImageGrid.resizeRate;
		/**
		 * Object variables store UILayout panel
		 */
		ImageGrid.freeImageLimit = 10;
		
		ImageGrid.panels          = {
			/**
			 * jQuery element for init UILayout
			 */
			panelFull   : $('#showlist-video-layout'),
			/**
			 * jQuery element west layout
			 */
			panelWest   : $('#panel-west'),
			/**
			 * jQuery element center layout
			 */
			panelCenter : $('#panel-center')
		};
		/**
		 * Object jQuery variables content of grid
		 */
		ImageGrid.contents        = {
			/**
			 * jQuery element tree categories of image
			 */
			categories     : $('#jsn-jtree-categories'),
			/**
			 * jQuery element source image container
			 */
			sourceimages   : $('#sourcevideo-container'),
			/**
			 * jQuery element showlist image container
			 */
			showlistimages : $('#showlistvideo-container')
		};
		/**
		 * Cookie store
		 */
		ImageGrid.cookie          = {
			set    : function(name, value){
				$.cookie( name, value );
			},
			get    : function( name, type ){
				switch(type){
					case 'int'  :
						return parseInt( $.cookie(name) );
					case 'float' : 
						return parseFloat( $.cookie(name) );
					default:
						return $.cookie( name );
				}
			},
			exists : function(name){
				return $.cookie( name ) == null ? false : true;
			}
		};
		/**
		 * Initialize image grid
		 */
		ImageGrid.initialize      = function(){	
			
			//ImageGrid.repaddingImage();

			/*ImageGrid.showlistImages.find('div.video-item').each(function(){
				    var src = $(this).find('#linkcheck').val();						  		    
				    var http = $.ajax({
					    type:"HEAD",
					    url: src,
					    async: false
					  })
				   var check = http.status;	
				   if(check==404){
				   		$(this).find('.image_link').addClass('noimage');
				   		//$(this).find('.image_link img').attr('src','aa');
				   		$(this).find('.image_link img').remove();
				   		$(this).find('.image_link').append('<img src="" style="max-height: 60px; max-width: 80px; padding-top: 30px !important; border: none !important;" />');
				   }
			});*/
			if ( ImageGrid.options.selectMode == 'sync' ){
				$.when(
					ImageGrid.initEvents()
				).then(
					$.when(
						// show images the first album choosed 

						//showLoading(),
						ImageGrid.syncRefreshing()
					).then(
						showLoading({removeall:true})
					)
				);
			}else if(ImageGrid.options.removeload == 1){
					showLoading({removeall:true});
			}else{				
				$.when(
					//showLoading(false),
					//showLoading({removeall:false}),
					ImageGrid.initEvents()
				).then(
					//showLoading({removeall:true})
				);
			}
		};

		ImageGrid.SelectAllImages = function(val){
			if(val=='source'){
				ImageGrid.sourceImages.find('div.video-item').each(function(){				
					ImageGrid.multipleselect.select($(this) );
				});	
				ImageGrid.moveImageToShowlistBtt.removeClass('disabled');	
				//ImageGrid.moveImageToShowlistBtt.parent().removeClass('disabled');				
			}else{
				ImageGrid.showlistImages.find('div.video-item').each(function(){				
					ImageGrid.multipleselect.select($(this) );
				});
				ImageGrid.editImageShowlistBtt.removeClass('disabled');	
				//ImageGrid.editImageShowlistBtt.parent().removeClass('disabled');				
				ImageGrid.deleteImageShowlistBtt.removeClass('disabled');
				//ImageGrid.deleteImageShowlistBtt.parent().removeClass('disabled');
			}
			ImageGrid.activeButtonsAction();
		};

		ImageGrid.DeselectAll 	  = function(val){		
			if(val=='source'){	
				ImageGrid.multipleselect.deSelectAll(ImageGrid.sourceImages);
				ImageGrid.moveImageToShowlistBtt.addClass('disabled');
				//ImageGrid.moveImageToShowlistBtt.parent().addClass('disabled');
			}else{
				ImageGrid.multipleselect.deSelectAll(ImageGrid.showlistImages);
				ImageGrid.editImageShowlistBtt.addClass('disabled');	
				//ImageGrid.editImageShowlistBtt.parent().addClass('disabled');				
				ImageGrid.deleteImageShowlistBtt.addClass('disabled');
				//ImageGrid.deleteImageShowlistBtt.parent().addClass('disabled');
			}
		}

		ImageGrid.RevertSelection = function(val){	
			if(val=='source'){			
				ImageGrid.sourceImages.find('div.video-item').each(function(){				
					if($(this).hasClass('image-item-multiple-select')){
						$(this).removeClass('image-item-multiple-select');						
					}else{
						ImageGrid.multipleselect.select($(this) );						
					}
				});
				var count = ImageGrid.sourceImages.find('.image-item-multiple-select').length;				
				if(parseInt(count) > 0){					
					ImageGrid.moveImageToShowlistBtt.removeClass('disabled');
					//ImageGrid.moveImageToShowlistBtt.parent().removeClass('disabled');
				}else{
					ImageGrid.moveImageToShowlistBtt.addClass('disabled');
					//ImageGrid.moveImageToShowlistBtt.parent().addClass('disabled');
				}
			}else{
				ImageGrid.showlistImages.find('div.video-item').each(function(){				
					if($(this).hasClass('image-item-multiple-select')){
						$(this).removeClass('image-item-multiple-select');
					}else{
						ImageGrid.multipleselect.select($(this) );
					}
				});
				var count = ImageGrid.showlistImages.find('.image-item-multiple-select').length;				
				if(parseInt(count) > 0){					
					ImageGrid.editImageShowlistBtt.removeClass('disabled');	
					//ImageGrid.editImageShowlistBtt.parent().removeClass('disabled');					
					ImageGrid.deleteImageShowlistBtt.removeClass('disabled');
					//ImageGrid.deleteImageShowlistBtt.parent().removeClass('disabled');
				}else{
					ImageGrid.editImageShowlistBtt.addClass('disabled');
					//ImageGrid.editImageShowlistBtt.parent().addClass('disabled');					
					ImageGrid.deleteImageShowlistBtt.addClass('disabled');
					//ImageGrid.deleteImageShowlistBtt.parent().addClass('disabled');
				}
				
			}
		}

		ImageGrid.removecatSelected  = function(){
			ImageGrid.contents.categories.find('ul li').each(function(){							
							$(this).addClass('catsyn');
						});
		}	

		ImageGrid.repaddingImage = function(){
			ImageGrid.sourceImages.find('div.video-thumbnail img').each(function(){
				 $(this).load(function() {	
					var imageHeight 	= $(this).height();				
					var parentheight    = $('div.video-thumbnail').height();
					var padding = parentheight/2 - imageHeight/2-5;
					$(this).css('padding-top',padding);
				 })
			});
			ImageGrid.showlistImages.find('div.video-thumbnail img').each(function(){	
				 $(this).load(function() {			
					var imageHeight 	= $(this).height();				
					var parentheight    = $('div.video-thumbnail').height();					
					var padding = parentheight/2 - imageHeight/2-5;					
					$(this).css('padding-top',padding);
				});
			});
		}

		/**
		* Reset Detail Images
		*/
		ImageGrid.ResetDetailImages = function(){
			var count = ImageGrid.showlistImages.find('.image-item-multiple-select').length;
			if(count>0){
				// ajax reset detail of images.				
				ImageGrid.showlistImages.find('.image-item-multiple-select').each(function(){					
					$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=image&task=resetImageDetails', {
						showlist_id : ImageGrid.options.showListID,
						image_extid : $(this).attr('id'),
						album_extid	: $(this).find('.video-info').attr('id'),
						img_detail  : $(this).find('input.img_detail').val()
					}).success(function(res){

					})
					$(this).find('div.modified').removeClass('modified');	
				});					
			}else{
				$("#dialogbox").html('<div style="width:100%; text-align:center; font-size:16px; font-weight:500; margin-top:30px; ">No item is selected</div>').dialog(
								{	
									width: 600, 
									modal: true,
									title: '<span style="font-size: 15px; font-weight:bold;">Confirmation</span>',
									buttons: [
								    {
								        text: "Close",
								        click: function() { $(this).dialog("close"); }
								    }
									]
								});	
			}	
		}

		/**
		* Purge Absolete Images
		*/
		ImageGrid.PurgeAbsoleteImages = function(){
			
				// process reset detail of each images is selected
				ImageGrid.showlistImages.find('div.video-item, div.image-item-multiple-select').each(function(){
				    var src = $(this).find('#linkcheck').val();				    
				    var http = $.ajax({
					    type:"HEAD",
					    url: src,
					    async: false
					  })
				   var check = http.status;				   	
				   if(check ==404){				   		
				   		$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=image&task=PurgeAbsoleteImages', {
							showListID : ImageGrid.options.showListID,
							ImageID    : $(this).attr('id')
						}).success(function(res){

						})
						$(this).fadeOut(1500,function(){
							ImageGrid.indexImages();		
							ImageGrid.contentResize();
							ImageGrid.multipleselect.init();
							$(this).remove();
							$('div[id="'+$(this).attr('id')+'"]', ImageGrid.sourceImages).each(function(){
								$(this).removeClass('image-item-is-selected').addClass('video-item');
							});
							ImageGrid.removeselectedAlbum();							
						});	
				   }
				});		
			
		}
		/**
		 * Set options
		 */
		ImageGrid.setOption       = function(name, value){
			if ( typeof name == 'Array' ){
				for(k in name){
					ImageGrid.options[name] = value;
				}
			}else{
				if ( ImageGrid.options[name] != undefined ){
					ImageGrid.options[name] = value;
				}
			}
		};
		/**
		* 
		* Init layout
		*
		* @return: None 
		*/
		ImageGrid.initLayout      = function(){			
			ImageGrid.panels.panelFull.css('width', ImageGrid.options.layoutWidth);
			ImageGrid.panels.panelFull.css('height', ImageGrid.options.layoutHeight);
			ImageGrid.contents.categories.css('height', ImageGrid.options.layoutHeight - 150);
			ImageGrid.contents.sourceimages.css('height', ImageGrid.options.layoutHeight - 95);
			ImageGrid.contents.showlistimages.css('height', ImageGrid.options.layoutHeight - 95);
			
			ImageGrid.UILayout = ImageGrid.panels.panelFull.layout({				
				west__onresize: function(){
					ImageGrid.contentResize();
				},
				west__onopen: function(){
					ImageGrid.contentResize();
				},
				west__onclose: function(){
					if ( ImageGrid.sourceImages.hasClass('showlist')){
						//ImageGrid.sourceImages.find('div.video-item').removeAttr('style');
						//ImageGrid.sourceImages.find('div.image-item-is-selected').removeAttr('style');
					}
					if ( ImageGrid.showlistImages.hasClass('showlist')){
						ImageGrid.showlistImages.find('div.video-item').removeAttr('style');
					}
					ImageGrid.contentResize();
				},
				onresizeall_end: function(){
					setTimeout(function(){
						ImageGrid.calculatorRate();
						if ( ImageGrid.sourceImages.hasClass('showlist')){
							//ImageGrid.sourceImages.find('div.video-item').removeAttr('style');
							//ImageGrid.sourceImages.find('div.image-item-is-selected').removeAttr('style');
						}
						
						if ( ImageGrid.showlistImages.hasClass('showlist')){
							ImageGrid.showlistImages.find('div.video-item').removeAttr('style');
						}
						ImageGrid.contentResize();
					}, 200);
				},
				ondrag_end: function(){
					setTimeout(function(){
						ImageGrid.calculatorRate();						
						if ( ImageGrid.sourceImages.hasClass('showlist')){
							//ImageGrid.sourceImages.find('div.video-item').removeAttr('style');
							//ImageGrid.sourceImages.find('div.image-item-is-selected').removeAttr('style');
						}
						
						if ( ImageGrid.showlistImages.hasClass('showlist')){
							ImageGrid.showlistImages.find('div.video-item').removeAttr('style');
						}
						ImageGrid.contentResize();
						ImageGrid.cookie.set('rate_of_west', ImageGrid.resizeRate.west );
					}, 200);
				}
			});
			ImageGrid.panels.panelWest.css('position', '');
			if ( $.browser.msie ){
				ImageGrid.contents.showlistimages.parents('div.sourcevideo-panel-container').css('margin-top', '-10px');
			}
			/**
			 * Restore layout resize
			 */
			if ( ImageGrid.cookie.exists('rate_of_west') ){
				var fullWidth = ImageGrid.panels.panelFull.outerWidth();
				ImageGrid.UILayout.sizePane("west", ImageGrid.cookie.get('rate_of_west', 'int')*ImageGrid.panels.panelFull.outerWidth()/100);
			}
			/**
			 * Call calcaulator rate
			 */
			ImageGrid.calculatorRate();
			/**
			 * Auto-resize when window resize
			 */
			$(window).resize(function(){
				var fullWidth = $('#showlist-video-layout').width();
				ImageGrid.UILayout.sizePane("west", ImageGrid.resizeRate.west*ImageGrid.panels.panelFull.outerWidth()/100);
			});
		};
		/**
		* 
		* Calculator rate size
		* 
		* @return: Calculator rate of width
		*/
		ImageGrid.calculatorRate  = function(){
			var westWidth        = ImageGrid.panels.panelWest.innerWidth();
			var centerWirth      = ImageGrid.panels.panelCenter.innerWidth();
			var fullWidth        = ImageGrid.panels.panelFull.outerWidth();
			ImageGrid.resizeRate = {west: westWidth*100/fullWidth, center: centerWirth*100/fullWidth};
		};
		
		/**
		 * Content resize
		 */
		ImageGrid.contentResize   = function(){
			$(ImageGrid.classImagesSort).each(function(){
				if ($(this).parents('div.ui-layout-center').length){
					if ($(this).find('div.video-item').length){
						$(this).removeClass('jsn-section-empty');
					}else{
						$(this).addClass('jsn-section-empty');
					}
				}
				/*if ( $(this).children('div:last').attr('class') != 'clr'){
					$(this).children('.clr').remove().end().append('<div class="clr" />');
				}*/
			});

			if ( ImageGrid.contents.sourceimages.children('div.ui-sortable').hasClass('showlist') && ImageGrid.contents.sourceimages.find('div.video-item').length > 0){
				var tmpItem = ImageGrid.contents.sourceimages.find('div.video-item:first');
				var contaierWidth   = tmpItem.innerWidth();
				var thumbnailHeight = tmpItem.children('div.video-thumbnail').outerHeight();
				var thumbnailWidth  = tmpItem.children('div.video-thumbnail').outerWidth();
			}
		};
		
		/**
		 * Sync refreshing
		 */
		ImageGrid.syncRefreshing  = function(treeRoot){
			if ( treeRoot == undefined ){
				treeRoot = ImageGrid.jsnjTree.getContainer();
			}			
			treeRoot.children('li').each(function(){
				var current = $(this);	
				var isSelected = current.hasClass('catselected');
				if (isSelected)
				{
					current.children('input.sync').attr('checked', true);				
				}
				if (current.has('ul').length)
				{
					ImageGrid.syncRefreshing(current.children('ul'));
				}	
			});
		};
		/**
		 * Get source images and showlist images by sync mode
		 */
		ImageGrid.getImagesSync = function( syncName, typeAppend ){
			var countImages = 0;
			var countShowlistImages = 0;
			$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=loadSourceImages',{
				showListID : ImageGrid.options.showListID,
				sourceType : ImageGrid.options.sourceType,
				sourceName : ImageGrid.options.sourceName,
				selectMode : ImageGrid.options.selectMode,
				cateName   : syncName
			}).success( function(res){							
				if (typeAppend == 'append' ){					
					/**
					 * Append response to source videos
					 */
					ImageGrid.sourceImages.html(res);

					ImageGrid.sourceImages.find('div.video-item').removeClass('video-item').addClass('image-item-is-selected');
					/**
					 * Append response to showlist
					 */
					//ImageGrid.showlistImages.append(res);
					/**
					 * Move all images from source to showlist
					 */
					//ImageGrid.showlistImages.find('div.image-item-is-selected').removeClass('image-item-is-selected').addClass('video-item');
					/**
					 * Init events
					 */
					ImageGrid.initEvents();					
					//Save showlist
					//ImageGrid.saveShowlist();
					// repadding for image 
					//ImageGrid.repaddingImage();
				}else{					
					/**
					 * Append response to source videos
					 */
					ImageGrid.sourceImages.html(res);
					/**
					 * Add all video to showlist
					 */
					res = '<div id="res-videos">'+res+'</div>';
					$(res).children().each(function(){
						$('div[id="'+$(this).attr('id')+'"]', ImageGrid.sourceImages).removeClass('image-item-is-selected').addClass('video-item');
						//$('div[id="'+$(this).attr('id')+'"]', ImageGrid.showlistImages).remove();
					});
					/**
					 * Init events
					 */
					ImageGrid.initEvents();
				}
				//Add image loading thumbnail
				//ImageGrid.imageLoading(ImageGrid.sourceImages.find('img[alt="video thumbnail"]'));
				//ImageGrid.imageLoading(ImageGrid.showlistImages.find('img[alt="video thumbnail"]'));
				ImageGrid.showlistImages.find('div.video-item').addClass('item-loaded');
				ImageGrid.sourceImages.find('div.video-item').addClass('item-loaded');
				countImages = $('.video-item, .image-item-is-selected', ImageGrid.sourceImages).length;
				countShowlistImages = $('.video-item', ImageGrid.showlistImages).length;
				if (!countImages)
				{
					ImageGrid.sourceImages.addClass("jsn-section-empty");
				}
				else
				{
					ImageGrid.sourceImages.removeClass("jsn-section-empty");
				}	
				if (!countShowlistImages)
				{
					ImageGrid.showlistImages.addClass('jsn-section-empty');
				}
				else
				{
					ImageGrid.showlistImages.removeClass('jsn-section-empty');
				}				
				setTimeout(function(){
					showLoading({removeall:true});
				}, 500);
			});
		};
		/**
		 * Init events
		 */
		ImageGrid.initEvents      = function(){
			
			/**
			 * Init JSN jTree
			 */
			if ( ImageGrid.contents.categories.data('jsn_jtree_initialized') === undefined){
				if ( ImageGrid.options.selectMode == 'sync'){					
					var jsnjTreeOptions = {
						syncmode : true
					};
				}else{
					var jsnjTreeOptions = {
						syncmode : false
					};
				}

				ImageGrid.jsnjTree = ImageGrid.contents.categories.jsnjtree(jsnjTreeOptions).bind('jsn_jtree.selectitem', function(e, obj){		
					$.when(
						ImageGrid.imageTotal = -1,
						ImageGrid.clickTreeIcon = true,
						ImageGrid.reloadImageSource(obj.attr('id'))
					);
				}).bind("jsn_jtree.sync", function(e, obj){
					showLoading();
					if ( obj.attr('checked') == 'checked' ){

						/**
						 * Save sync checked
						 */
						 //ImageGrid.showlistImages.find('.showlist-sync-image-notice').remove();
						ImageGrid.showlistImages.find('.showlist-sync-image-notice').remove();
						$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=savesync', {
							showlist_id : ImageGrid.options.showListID,
							sourceType : ImageGrid.options.sourceType,
							sourceName : ImageGrid.options.sourceName,
							album_extid   : obj.parent().attr('id')
						}).success(function(res){							
							ImageGrid.getImagesSync( obj.parent().attr('id'), 'append' );
							obj.parent().addClass('catselected');
						});
					}else{		

						$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=removesync', {
							showlist_id : ImageGrid.options.showListID,
							sourceType : ImageGrid.options.sourceType,
							sourceName : ImageGrid.options.sourceName,
							album_extid   : obj.parent().attr('id')
						}).success(function(res){							
							//ImageGrid.showlistImages.html('');
							ImageGrid.getImagesSync( obj.parent().attr('id'), 'remove' );
							obj.parent().removeClass('catselected');
						});
					}
				});
			}
			/**
			 * UILayout init
			 */
			ImageGrid.initLayout();
			/**
			 * Init multiple
			 */
			if ( ImageGrid.options.selectMode == 'sync' ){
				ImageGrid.multipleselect.destroy();
			}else{
				ImageGrid.multipleselect.init();
			}
			
			/**
			 * Index videos
			 */
			ImageGrid.indexImages();
			/**
			 * Init sortable
			 */
			ImageGrid.sortable();
			/**
			 * Active button
			 */
			ImageGrid.activeButtonsAction();
			/**
			 * Move video
			 */
			ImageGrid.sourceImages.find('button.move-to-showlist').unbind("click").click(function(){
				totalShowedImage = ImageGrid.showlistImages.find('div.video-item').length;
				if( totalShowedImage >= freeImageLimit ){
					showLoading({removeall:true});					
					verNotice(VERSION_EDITION_NOTICE);
					return false;
				}
				var _append;
				showLoading({removeall:false});
				ImageGrid.contents.categories.find('a.jtree-selected').parent().addClass('catselected');
				ImageGrid.moveVideoToShowlist( $(this).parents('div.video-item'),_append,1,1 );
				ImageGrid.showlistImages.removeClass('jsn-section-empty');
				//$('.image-item-multiple-select',ImageGrid.sourceImages).removeClass('image-item-multiple-select').removeClass('multiselectable-previous');	
				///ImageGrid.moveImageToShowlistBtt.removeClass('active');
				
			});
			/**
			 * Show more image button
			 */
			ImageGrid.sourceImages.find('#showMoreImagesBtn').unbind("click").click(function(){
				var id = document.getElementById('cateNameInShowlist').value;
				ImageGrid.reloadImageSource(id);
			});			
			/**
			 * Animate to change videos show type
			 */
			$('a', ImageGrid.sourcePanelHeader).unbind("click").click(function(){
				if ( $(this).hasClass('image-show-grid') && !$(this).hasClass('active') ){
					$(this).addClass('active');
					$(this).next().removeClass('active');
					ImageGrid.sourceImages.fadeOut(300, function(){
						$(this).removeClass('showlist');
						
						//ImageGrid.contents.sourceimages.find('div.video-item').removeAttr('style');
						//ImageGrid.contents.sourceimages.find('div.image-item-is-selected').removeAttr('style');
						
						$(this).addClass('showgrid').fadeIn(300, function(){
							//Set status to cookie store
							ImageGrid.cookie.set('jsn-is-cookie-view-mode-image-source', false);
							ImageGrid.contentResize();
						});
					});
				}else if($(this).hasClass('image-show-list') && !$(this).hasClass('active')){
					$(this).addClass('active');
					$(this).prev().removeClass('active');
					ImageGrid.sourceImages.fadeOut(300, function() {
					  $(this).removeClass('showgrid').addClass('showlist').fadeIn(300, function(){
					  		//Set status to cookie store
					  		ImageGrid.cookie.set('jsn-is-cookie-view-mode-image-source', true);
							ImageGrid.contentResize();
					  });
					});
				}
			});
			/**
			 * Animate to change showlist videos show types
			 */
			ImageGrid.bindClickToShowlistShowTypeButton();
			
			/**
			 * Button to change jsnjtree
			 */
			$('button', ImageGrid.treePanelHeader).unbind("click").click(function(){
				if ( $(this).hasClass('expand-all') ){
					ImageGrid.jsnjTree.expand_all();
				}else if ( $(this).hasClass('collapse-all') ){
					ImageGrid.jsnjTree.collapse_all();
				}else if( $(this).hasClass('sync') ){
					if ( $(this).hasClass('btn-success') ){						
						$(this).removeClass('btn-success');
						$(this).html(JSNISLang.translate('SYNC_UPPERCASE') + ': ' + JSNISLang.translate('OFF_UPPERCASE') )
						//Show delete showlist image button
						ImageGrid.deleteImageShowlistBtt.show();
						//Show edit showlist image button
						ImageGrid.editImageShowlistBtt.show();
						//Show move source image button
						ImageGrid.moveImageToShowlistBtt.show();
						//Change mode to normal
						ImageGrid.setOption('selectMode', '');
						//Remove sync
						ImageGrid.jsnjTree.removeSync();
						//Add notice
						//ImageGrid.showlistImages.html('<div class="jsn-bglabel">Drag and drop images here</div>');						
						// remove sync 
						ImageGrid.removeSync();		
						ImageGrid.showlistImages.html('<div class="jsn-bglabel showlist-drag-drop-video-notice"><span class="jsn-icon64 icon-pointer"></span>Drag and drop images here</div>');					
						//Save showlist
						ImageGrid.saveShowlist();						
						//Set empty source images
						ImageGrid.sourceImages.html('');
						//Uncheck selected category item
						ImageGrid.contents.categories.find('a.jtree-selected').removeClass('jtree-selected');
					}else{
						var syncButton = $(this);						
						if (ImageGrid.showlistImages.find('div.video-item').length > 0){
							var confirmBox = confirm('When enabling "Sync mode", all current images in the showlist will be removed from it. Do you want to continue?');		
							if (confirmBox == true)
							{
								//Change mode to normal
								ImageGrid.setOption('selectMode', 'sync');
								//Hide delete showlist video button
								ImageGrid.deleteImageShowlistBtt.hide();
								//Hide edit video showlist button
								ImageGrid.editImageShowlistBtt.hide();
								//Hide move source video button
								ImageGrid.moveImageToShowlistBtt.hide();
								//Set empty source images
								ImageGrid.sourceImages.html('');
								//Uncheck selected category item
								ImageGrid.contents.categories.find('a.jtree-selected').removeClass('jtree-selected');
								//Add sync button to active
								syncButton.addClass('btn-success');
								syncButton.html(JSNISLang.translate('SYNC_UPPERCASE') + ': ' + JSNISLang.translate('ON_UPPERCASE'))
								//Add notice
								//ImageGrid.showlistImages.html('<div class="jsn-bglabel">Drag and drop images here</div>');
								ImageGrid.showlistImages.html('<div class="showlist-sync-image-notice jsn-bglabel"><span class="jsn-icon64 icon-refresh"></span>Showlist is in Sync mode</div>');
								// Resize content
								ImageGrid.contentResize();
								//Save showlist
								ImageGrid.saveShowlist();
								//Add sync
								ImageGrid.jsnjTree.sync();
								// remove class catselected
								//imageGrid.removecatSelected();	
								ImageGrid.contents.categories.find('ul li').each(function(){							
									$(this).addClass('catsyn');
								});									
							}
						}else{
							//Change mode to normal
							ImageGrid.setOption('selectMode', 'sync');
							//Hide delete showlist button
							ImageGrid.deleteImageShowlistBtt.hide();
							//Hide edit showlist button
							ImageGrid.editImageShowlistBtt.hide();
							//Hide move video from source video
							ImageGrid.moveImageToShowlistBtt.hide();
							//Set empty source images
							ImageGrid.sourceImages.html('');
							//Uncheck selected category item
							ImageGrid.contents.categories.find('a.jtree-selected').removeClass('jtree-selected');
							//Add sync button to active
							syncButton.addClass('btn-success');
							syncButton.html(JSNISLang.translate('SYNC_UPPERCASE') + ': ' + JSNISLang.translate('ON_UPPERCASE'))
							//Remove all video in the showlist
							//ImageGrid.showlistImages.html('<div class="jsn-bglabel">Drag and drop images here</div>');							
							ImageGrid.showlistImages.html('<div class="showlist-sync-image-notice jsn-bglabel"><span class="jsn-icon64 icon-refresh"></span>Showlist is in Sync mode</div>');
							//ImageGrid.showlistImages.html('<div class="showlist-sync-image-notice">Showlist is in Sync mode</div>');
							// Resize content
							ImageGrid.contentResize();
							//Save showlist
							ImageGrid.saveShowlist();
							//Add sync
							ImageGrid.jsnjTree.sync();
						}
					}
				}
			});
			
			//Restore source videos showtype
			if ( ImageGrid.cookie.exists('jsn-is-cookie-view-mode-image-source') && ImageGrid.cookie.get('jsn-is-cookie-view-mode-image-source') == 'true' ){
				ImageGrid.sourceImages.removeClass('showgrid').addClass('showlist');
			}
			ImageGrid.RestoreSourceImagesShowType();
			//Restore showlist videos showtype
			if ( ImageGrid.cookie.exists('jsn-is-cookie-view-mode-imageshow-showlist') && ImageGrid.cookie.get('jsn-is-cookie-view-mode-imageshow-showlist')  == 'true' ){
				ImageGrid.showlistImages.removeClass('showgrid').addClass('showlist');
			}
			ImageGrid.RestoreShowlistShowType();
			/**
			 * Resize content
			 */
			ImageGrid.contentResize();
		};
		/**
		 * Restore Showlist Show Type
		 */
		ImageGrid.RestoreShowlistShowType = function() {
			var countShowlistImages = $('.video-item', ImageGrid.showlistImages).length;
			if (!countShowlistImages)
			{
				ImageGrid.showlistPanelHeader.children('a.image-show-grid').removeClass('active').addClass('disabled').unbind("click");	
				ImageGrid.showlistPanelHeader.children('a.image-show-list').removeClass('active').addClass('disabled').unbind("click");	
			}
			else
			{
				ImageGrid.showlistPanelHeader.children('a.image-show-grid').removeClass('disabled').bind("click");						
				ImageGrid.showlistPanelHeader.children('a.image-show-list').removeClass('disabled').bind("click");
				if (ImageGrid.cookie.exists('jsn-is-cookie-view-mode-imageshow-showlist') && ImageGrid.cookie.get('jsn-is-cookie-view-mode-imageshow-showlist')  == 'true'){
					ImageGrid.showlistPanelHeader.children('a.image-show-grid').removeClass('active');
					ImageGrid.showlistPanelHeader.children('a.image-show-list').addClass('active');
				}	
				else
				{
					ImageGrid.showlistPanelHeader.children('a.image-show-grid').addClass('active');
					ImageGrid.showlistPanelHeader.children('a.image-show-list').removeClass('active');
				}			
			}			
		};
		/**
		 * Bind click even to showtype button of showlist
		 */
		ImageGrid.bindClickToShowlistShowTypeButton = function() {
			$('a', ImageGrid.showlistPanelHeader ).unbind("click").click(function(){
				if ( $(this).hasClass('image-show-grid') && !$(this).hasClass('active') ){
					$(this).addClass('active');
					$(this).next().removeClass('active');
					ImageGrid.showlistImages.fadeOut(300, function(){
						$(this).removeClass('showlist');
						ImageGrid.contents.showlistimages.find('div.video-item').removeAttr('style');
						$(this).addClass('showgrid').fadeIn(300, function(){
							//Set status to cookie store
							ImageGrid.cookie.set('jsn-is-cookie-view-mode-imageshow-showlist', false);
							ImageGrid.contentResize();
						});
					});
				}else if($(this).hasClass('image-show-list') && !$(this).hasClass('active')){
					$(this).addClass('active');
					$(this).prev().removeClass('active');
					ImageGrid.showlistImages.fadeOut(300, function(){
						$(this).removeClass('showgrid').addClass('showlist').delay(300).fadeIn(300, function(){
							//Set status to cookie store
							ImageGrid.cookie.set('jsn-is-cookie-view-mode-imageshow-showlist', true);
							ImageGrid.contentResize();
						});
					});
				}
			});			
		};
		/**
		 * Restore Source Show Type
		 */
		ImageGrid.RestoreSourceImagesShowType = function() {
			var countSourceImages = $('.video-item, .image-item-is-selected', ImageGrid.sourceImages).length;
			if (!countSourceImages)
			{
				ImageGrid.sourcePanelHeader.children('a.image-show-grid').removeClass('active').addClass('disabled').unbind("click");	
				ImageGrid.sourcePanelHeader.children('a.image-show-list').removeClass('active').addClass('disabled').unbind("click");	
			}
			else
			{
				ImageGrid.sourcePanelHeader.children('a.image-show-grid').removeClass('disabled').bind("click");						
				ImageGrid.sourcePanelHeader.children('a.image-show-list').removeClass('disabled').bind("click");
				if (ImageGrid.cookie.exists('jsn-is-cookie-view-mode-image-source') && ImageGrid.cookie.get('jsn-is-cookie-view-mode-image-source') == 'true' ){
					ImageGrid.sourcePanelHeader.children('a.image-show-grid').removeClass('active');
					ImageGrid.sourcePanelHeader.children('a.image-show-list').addClass('active');
				}	
				else
				{
					ImageGrid.sourcePanelHeader.children('a.image-show-grid').addClass('active');
					ImageGrid.sourcePanelHeader.children('a.image-show-list').removeClass('active');
				}			
			}			
		};		
		/**
		 * Active buttons action
		 */
		ImageGrid.activeButtonsAction = function(){
			/**
			 * Edit and Delete video showlist
			 */			
			if ( ImageGrid.multipleselect.hasChildSelected(ImageGrid.showlistImages) ){								
				ImageGrid.editImageShowlistBtt.removeClass('disabled');	
				//ImageGrid.editImageShowlistBtt.parent().removeClass('disabled');				
				ImageGrid.deleteImageShowlistBtt.removeClass('disabled');	
				//ImageGrid.deleteImageShowlistBtt.parent().removeClass('disabled');				
				ImageGrid.editImageShowlistBtt.unbind("click").click(function(){					
					ImageGrid.editImage( $(ImageGrid.multipleselect.getAll(ImageGrid.showlistImages)) );
				});
				
				//ImageGrid.deleteVideoShowlistBtt.unbind("click").click(function(){
                ImageGrid.deleteImageShowlistBtt.unbind("click").click(function(){                	
					var videosMultipleSelected = ImageGrid.multipleselect.getAll(ImageGrid.showlistImages);				
					var confirmBox = confirm('Are you sure you want to remove selected images?');		
					if (confirmBox == true)
					{
						//Delete images confirmation
                        ImageGrid.showlistImages.find('div.image-item-multiple-select').each(function(){  
                                var id = $(this).attr('id');
								$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=deleteimageshowlist',{
									showListID : ImageGrid.options.showListID,
									sourceName : ImageGrid.options.sourceName,
									sourceType : ImageGrid.options.sourceType,
									imageID    : id											
								}).success( function( responce ){												
									// remove class "catselected" of each album menu when haven't any image in showlist image											
								});	
								$(this).fadeOut(1500,function(){											
									ImageGrid.indexImages();		
									ImageGrid.contentResize();
									ImageGrid.multipleselect.init();			
									$('div[id="'+id+'"]', ImageGrid.sourceImages).each(function(){
										$(this).removeClass('image-item-is-selected').addClass('video-item');
									});
									$(this).remove();
									ImageGrid.removeselectedAlbum();
									ImageGrid.RestoreShowlistShowType();
								}); 																			
						});                                
                        //Close dialog
						showLoading({removeall:false});
						setTimeout('showLoading({removeall:true})',2000);
					}
				});
				
			}else{				
				ImageGrid.editImageShowlistBtt.addClass('disabled').unbind('click');
				//ImageGrid.editImageShowlistBtt.parent().addClass('disabled');
				ImageGrid.deleteImageShowlistBtt.addClass('disabled').unbind('click');	
				//ImageGrid.deleteImageShowlistBtt.parent().addClass('disabled');				
			}
			
			/**
			 * Move selected video source
			 */			
			if ( ImageGrid.multipleselect.hasChildSelected( ImageGrid.sourceImages ) ){				
				ImageGrid.moveImageToShowlistBtt.removeClass('disabled');
				//ImageGrid.moveImageToShowlistBtt.parent().removeClass('disabled');
				ImageGrid.moveImageToShowlistBtt.unbind("click").click(function(){
					totalShowedImage = ImageGrid.showlistImages.find('div.video-item').length;
					totalSourceSelectedImage = ImageGrid.sourceImages.find('div.image-item-multiple-select').length;
					if( ( totalShowedImage + totalSourceSelectedImage) > freeImageLimit ){												
						verNotice(VERSION_EDITION_NOTICE);
						return false;
					}					
					ImageGrid.contents.categories.find('a.jtree-selected').parent().addClass('catselected');
					showLoading({removeall:false});
					var i = 1;
					var _append;
					var videosMultipleSelected = ImageGrid.multipleselect.getAll(ImageGrid.sourceImages);
					var totalVideo =  videosMultipleSelected.length;
					ImageGrid.showlistImages.removeClass('jsn-section-empty');
					ImageGrid.queueExecute(videosMultipleSelected, 0, function(obj){					   		
						
						ImageGrid.moveVideoToShowlist(obj, _append, totalVideo, i);
						
						if (i == totalVideo)
						{
							i = 0;
						}						
						i++;						
					});
				});
			}else{
				ImageGrid.moveImageToShowlistBtt.addClass('disabled').unbind('click');
				//ImageGrid.moveImageToShowlistBtt.parent().addClass('disabled');
			}
		};
		
		index = new Array();		
		/**
		 * Multiple select
		 */
		ImageGrid.multipleselect  = {			
			/**
			 * Init multiple select element
			 */
			init : function(){			
				ImageGrid.multipleselect.multiselectable();
				/**
				 * Deselect all selected video from source
				 */
				ImageGrid.contents.sourceimages.unbind("click").click(function(e){
					
					if ( ImageGrid.multipleselect.hasChildSelected(ImageGrid.sourceImages) && !$(e.target).parents('div.video-item').length > 0 && !$(e.target).parents('div.image-item-is-selected').length > 0 ){
						$('#showlist-images div').each(function(){
							$(this).removeAttr('start');
							$('#start_image_showlist').val('');
							$('#stop_image_showlist').val('');
						});
						$('#source-images div').each(function(){
							$(this).removeAttr('start');
							$('#start').val('');
							$('#stop').val('');
						});
						ImageGrid.multipleselect.deSelectAll(ImageGrid.sourceImages);
						ImageGrid.activeButtonsAction();
					}
				});
				/**
				 * Deselecte all selected video from showlist
				 */
				ImageGrid.contents.showlistimages.unbind("click").click(function(e){
					if ( ImageGrid.multipleselect.hasChildSelected(ImageGrid.showlistImages) && !$(e.target).parents('div.video-item').length > 0 ){
						$('#showlist-images div').each(function(){
							$(this).removeAttr('start');
							$('#start_image_showlist').val('');
							$('#stop_image_showlist').val('');
						});
						$('#source-images div').each(function(){
							$(this).removeAttr('start');
							$('#start').val('');
							$('#stop').val('');
						});
						ImageGrid.multipleselect.deSelectAll(ImageGrid.showlistImages);
						ImageGrid.activeButtonsAction();
					}
				});
			},
			
			multiselectable: function()
			{
				ImageGrid.sourceImages.find('div.video-item').unbind("click").click(function(e) {
					var item = $(this),
						parent = item.parent(),
						myIndex = parent.children().index(item),
						prevIndex = parent.children().index(parent.find('.multiselectable-previous'));
					
					if(item.hasClass('image-item-multiple-select')){		//deselect item if it selected currently					
						item.removeClass('image-item-multiple-select');
					}else{
						if (!e.ctrlKey && !e.metaKey)
						{	
							parent.find('.image-item-multiple-select').removeClass('image-item-multiple-select')							
						}
						else {
							if (item.not('.child').length) {
								if (item.hasClass('image-item-multiple-select'))
									item.nextUntil(':not(.child)').removeClass('image-item-multiple-select')
								else
									item.nextUntil(':not(.child)').addClass('image-item-multiple-select')
							}
						}
						
						if (e.shiftKey && prevIndex >= 0) {							
							parent.find('.multiselectable-previous').toggleClass('image-item-multiple-select')
							if (prevIndex < myIndex)
								item.prevUntil('.multiselectable-previous').toggleClass('image-item-multiple-select')
							else if (prevIndex > myIndex)
								item.nextUntil('.multiselectable-previous').toggleClass('image-item-multiple-select')

							$('.image-item-is-selected', ImageGrid.sourceImages).removeClass('image-item-multiple-select');	
						}
						
						item.toggleClass('image-item-multiple-select')
						parent.find('.multiselectable-previous').removeClass('multiselectable-previous')
						item.addClass('multiselectable-previous')
						ImageGrid.multipleselect.select($(this));	
						ImageGrid.activeButtonsAction();
					}
					
				}).disableSelection()
				
				ImageGrid.showlistImages.find('div.video-item').unbind("click").click(function(e) {						
					var item = $(this),
						parent = item.parent(),
						myIndex = parent.children().index(item),
						prevIndex = parent.children().index(parent.find('.multiselectable-previous'));
					
					if(item.hasClass('image-item-multiple-select')){			//deselect item if it selected currently				
						item.removeClass('image-item-multiple-select');
					}else{
						if (!e.ctrlKey && !e.metaKey)
						{	
							parent.find('.image-item-multiple-select').removeClass('image-item-multiple-select')							
						}
						else {
							if (item.not('.child').length) {
								if (item.hasClass('image-item-multiple-select'))
									item.nextUntil(':not(.child)').removeClass('image-item-multiple-select')
								else
									item.nextUntil(':not(.child)').addClass('image-item-multiple-select')
							}
						}
						
						if (e.shiftKey && prevIndex >= 0) {
							parent.find('.multiselectable-previous').toggleClass('image-item-multiple-select')
							if (prevIndex < myIndex)
								item.prevUntil('.multiselectable-previous').toggleClass('image-item-multiple-select')
							else if (prevIndex > myIndex)
								item.nextUntil('.multiselectable-previous').toggleClass('image-item-multiple-select')
						}
						
						item.toggleClass('image-item-multiple-select')
						parent.find('.multiselectable-previous').removeClass('multiselectable-previous')
						item.addClass('multiselectable-previous')
						ImageGrid.multipleselect.select($(this));	
						ImageGrid.activeButtonsAction();
					}
				}).disableSelection()									
			},			
			/**
			 * Destroy 
			 */
			destroy : function(){
				ImageGrid.sourceImages.find('div.video-item').unbind("click");
				ImageGrid.showlistImages.find('div.video-item').unbind("click");
				ImageGrid.contents.sourceimages.unbind("click");
				ImageGrid.contents.showlistimages.unbind("click");
			},
			/**
			 * Get all elements was selected for multiple
			 */
			getAll : function(obj){
				return $(ImageGrid.classMultiple, obj);
			},
			/**
			 * Count multiple element
			 */
			getTotal : function(obj){
				return $(ImageGrid.classMultiple, obj).length;
			},
			/**
			 * Select element
			 */
			select : function(obj){
				obj.addClass(ImageGrid.classMultiple.replace('.', ''));
			},
			/**
			 * Deselect element
			 */
			deSelect : function(obj){
				obj.removeClass('.multiselectable-previous'.replace('.', ''));
				obj.removeClass(ImageGrid.classMultiple.replace('.', ''));
			},
			/**
			 * Deselect all elements
			 */
			deSelectAll : function(obj){ 
				ImageGrid.multipleselect.getAll(obj).removeClass('.multiselectable-previous'.replace('.', ''));
				ImageGrid.multipleselect.getAll(obj).removeClass(ImageGrid.classMultiple.replace('.', ''));
			},
			/**
			 * Check element multiple
			 */
			hasSelected : function(obj){
				return obj.hasClass(ImageGrid.classMultiple.replace('.', ''));
			},
			/**
			 * Check parent have child element are multiple
			 */
			hasChildSelected : function(obj){
				return ( $(ImageGrid.classMultiple, obj ).length > 0 ? true : false );
			}
		};
		/**
		* 
		* Init function to set events and data
		* 
		* @param: (array) (objs) is arrray elements
		* @param: (int) (i) is index item need init
		* @return: Init 
		*/
		ImageGrid.sortable        = function(){
			ImageGrid.sourceImages.sortable({
				connectWith: 'div.showlist-images',
				items:'div.video-item',
				opacity: 0.6,
				scroll: true,
				dropOnEmpty: false,
				forceHelperSize: true,				
				cancel: 'div.image-item-is-selected, div.video-no-found, div.showlist-drag-drop-video-notice, div.sync',
				scrollSensitivity: 50,
				helper: function(e, item){	
				
					if ( ImageGrid.multipleselect.hasSelected( $(item) ) && ImageGrid.multipleselect.getTotal( ImageGrid.sourceImages ) > 1 ){
						var container = $('<div />', {
							'class' : 'jsn-video-item-multiple-select-container',
							'id'    : 'jsn-video-item-multiple-select-container'
						});
						var sumHeight = 0, i = 0;						
						ImageGrid.multipleselect.getAll(ImageGrid.sourceImages).each(function(){
							sumHeight += $(this).height() + 9;														
							var dragElement = $(this).clone(true);
							container.append( dragElement );
							$(this).data('i', i)
						});
						
						container.css({
							'height': sumHeight,																					
						});
					}else{
						container = $(item).clone(true);
					}
					$(item).show();
					
					return container;
				},
				start: function(event, ui) {					
					var parent = ui.item.parent()		
					if (parent.attr('id') == 'source-images')
					{
						var copy = $('.ui-sortable-placeholder').prev().clone(true);					
						$('.ui-sortable-placeholder').after(copy);
						copy.show();
					}
					ImageGrid.receive = true;
				},
				over : function(event, ui){
					if ( $(this).hasClass('showlist-images') && ImageGrid.showlistImages.find('div.video-item').length == 1 && ImageGrid.showlistImages.find('div.ui-sortable-placeholder').length ){
						ImageGrid.showlistImages.find('div.showlist-drag-drop-video-notice').remove();
					}
					
				},
				out : function(event, ui){	
					
					if ( ImageGrid.showlistImages.find('div.video-item').length == 0 && ImageGrid.showlistImages.find('div.showlist-drag-drop-video-notice').length == 0){
						ImageGrid.showlistImages.html('<div class="jsn-bglabel showlist-drag-drop-video-notice"><span class="jsn-icon64 icon-pointer"></span>Drag and drop images here</div>');
					}
				},				
				update : function(event, ui){					
					if ($(this).attr('id') == ImageGrid.showlistImages.attr('id'))
					{	
						ImageGrid.activeButtonsAction();
						ImageGrid.contentResize();
					}
				},
				stop: function(event, ui){					
					var parent = ui.item.parent();					
				//	ImageGrid.saveShowlist();
					ImageGrid.showlistImages.find('div.video-no-found').remove();
					ImageGrid.showlistImages.find('div.video-no-found').remove();	    
					var elementID = ui.item.attr('id');				
					if ( $('div[id="'+elementID+'"]', ImageGrid.sourceImages).length > 1){
						var index = 0;
						$('div[id="'+elementID+'"]', ImageGrid.sourceImages).each(function(){
							if (index > 0){
								$(this).remove();
							}
							index++;
						});
					}
					
					if ( $('div[id="'+elementID+'"]', ImageGrid.showlistImages).length > 1){
						var index = 0;
						$('div[id="'+elementID+'"]', ImageGrid.showlistImages).each(function(){
							if (index > 0){
								$(this).remove();
							}
							index++;
						});
					}
					ImageGrid.sourceImages.find('div.image-item-is-selected').each(function(){
						$(this).removeAttr('start');
					});
					ImageGrid.showlistImages.find('div.video-item').each(function(){
						$(this).removeAttr('start');
					});
					ImageGrid.indexImages();
					ImageGrid.multipleselect.init();
					ImageGrid.editImageShowlistBtt.addClass('disabled');
					//ImageGrid.editImageShowlistBtt.parent().addClass('disabled');					
					ImageGrid.deleteImageShowlistBtt.addClass('disabled');	
					//ImageGrid.deleteImageShowlistBtt.parent().addClass('disabled');
					if (parent.attr('id') != undefined && parent.attr('id') == 'showlist-images')
					{
						ImageGrid.contents.categories.find('a.jtree-selected').parent().addClass('catselected');
					}
					if ((parent.attr('id') != undefined && parent.attr('id') == 'showlist-images') && (!ImageGrid.receive))
					{
						ImageGrid.saveShowlist();
						setTimeout('showLoading({removeall:true})',1000);
					}
					
					ImageGrid.receive = false;
				}
			}).disableSelection();			
	 		
			ImageGrid.showlistImages.sortable({				
				opacity: 0.6,
				scroll: true,
				dropOnEmpty: false,
				forceHelperSize: true,				
				cancel: 'div.image-item-is-selected, div.video-no-found, div.showlist-drag-drop-video-notice, div.sync',
				scrollSensitivity: 50,
				helper: function(e, item){
					if ( ImageGrid.multipleselect.hasSelected( $(item) ) && ImageGrid.multipleselect.getTotal( ImageGrid.showlistImages ) > 1 ){
						var container = $('<div />', {
							'class' : 'jsn-video-item-multiple-select-container',
							'id'    : 'jsn-video-item-multiple-select-container'
						});
						var sumHeight = 0, i = 0;						
						ImageGrid.multipleselect.getAll(ImageGrid.showlistImages).each(function(){							
							sumHeight += $(this).height() + 9;														
							var dragElement = $(this).clone(true);
							$(this).hide();
							container.append( dragElement );
							$(this).data('i', i);
							i++;
						});
						
						container.css({
							'height': sumHeight																					
						});
					}else{
						container = $(item).clone(true);
					}
					$(item).show();
					
					return container;
				},
				receive: function(event, ui){		
					showLoading({removeall:false});
					ImageGrid.receive = true;										
				},
				
				update : function(event, ui){					
					var elementID = ui.item.attr('id');	
					if(ImageGrid.receive){
						totalShowedImage = ImageGrid.showlistImages.find('div.video-item').length;
						totalSourceSelectedImage = ImageGrid.sourceImages.find('div.image-item-multiple-select').length-1;
						if( (totalShowedImage > freeImageLimit && totalSourceSelectedImage<=1) || (totalSourceSelectedImage>1 && (totalSourceSelectedImage + totalShowedImage) > freeImageLimit) ){							
							showLoading({removeall:true});
							ui.item.remove();
							verNotice(VERSION_EDITION_NOTICE);
							return false;
						}
						var isMultipleVideo = ImageGrid.multipleselect.getTotal(ImageGrid.sourceImages);
						
						//showLoading({removeall:false});
						$('div[id="'+elementID+'"]', ImageGrid.sourceImages).removeClass('video-item').addClass('image-item-is-selected');
						if (isMultipleVideo)
						{
							var i = 1;
							ui.item.children('div.move-to-showlist').remove();
							ImageGrid.multipleselect.deSelect( $('div[id="'+elementID+'"]', ImageGrid.sourceImages));
							var totalVideoMoving = ImageGrid.multipleselect.getAll(ImageGrid.sourceImages);
							
							var _append = function(obj){
								ui.item.before(obj);
							};
							var totalVideo =  totalVideoMoving.length;							
							ImageGrid.queueExecute(totalVideoMoving, 0, function(obj){								
								ImageGrid.moveVideoToShowlist(obj, _append, totalVideo, i);
								_append = function(obj){
									ui.item.before(obj);
								};								
								if (i == totalVideo)
								{
									i = 0;
								}								
								i++;								
							});
							if (totalVideoMoving.length == 0)
							{
								ImageGrid.saveOneImage();						
							}							
						}else{
							ImageGrid.saveOneImage();
						}
						
						
					}else{			
						var isMultipleVideo = ImageGrid.multipleselect.hasSelected(ui.item);
						showLoading({removeall:false});
						
						ui.item.children('div.move-to-showlist').remove();	
						//ImageGrid.showlistImages.children('div.clr').remove();
						var totalVideoMoving = ImageGrid.multipleselect.getAll(ImageGrid.showlistImages);						
						$('#showlist-images .image-item-multiple-select').removeClass('image-item-multiple-select');
						var _append = function(obj){
							ui.item.before(obj);
						};					
						
						totalVideoMoving.each( function (){	
							if(ui.item.attr('id') != $(this).attr('id')){
								$(this).removeAttr('style');								
								_append($(this));
							}																
						});
						
						ImageGrid.saveShowlist();							
						setTimeout('showLoading({removeall:true})',1000);					
					}
					
					ImageGrid.indexImages();
					ImageGrid.contentResize();
					ImageGrid.showlistImages.removeClass('jsn-section-empty');
					//ImageGrid.removeselectedAlbum();
					
				},
				stop: function (){			
					$('.image-item-multiple-select',ImageGrid.showlistImages).removeAttr('style');
					ImageGrid.receive = false;
				},
				over: function (){
					$('.showlist-drag-drop-video-notice').hide();
					ImageGrid.showlistImages.removeClass('jsn-section-empty');
				},
				out: function (){
					var countShowlistImages = $('.item-loaded', ImageGrid.showlistImages).length;
					ImageGrid.showlistImages.removeClass('jsn-section-empty');
				}
					
			}).disableSelection();
		};

		/**
		 * Index video items
		 */
		ImageGrid.indexImages     = function(){
//			//Index source
//			var totalVideos = $('.image-item-is-selected', ImageGrid.sourceImages).length + $('.video-item', ImageGrid.sourceImages).length;
//			i = 1;		
//			ImageGrid.sourceImages.children('div.video-item').each(function(){
//				
//				if ( $(this).hasClass('image-item-is-selected') || $(this).hasClass('video-item') ){
//					$(this).children('div.video-index').html( i++ + '/' + totalVideos );
//					if (ImageGrid.options.selectMode != 'sync'){
//						var moveVideoToShowlist = $('<button />',{
//							'class' : "move-to-showlist"
//						}).html('&nbsp;');
//						$(this).children('div.video-index').append(moveVideoToShowlist);
//						moveVideoToShowlist.unbind("click").click(function(){	
//							showLoading({removeall:false});
//							ImageGrid.contents.categories.find('a.jtree-selected').parent().addClass('catselected');
//							ImageGrid.moveVideoToShowlist( $(this).parents('div.video-item') );
//						});
//					}
//				}
//			});
			
			var totalVideos = $('.video-item', ImageGrid.showlistImages).length;			
							  i = 1;
			ImageGrid.showlistImages.children().each(function(){
				if ( $(this).hasClass('video-item') ){
					$(this).children('div.video-index').html( i++ + '/' + totalVideos );
					if (ImageGrid.options.selectMode != 'sync'){
						$(this).children('div.video-index').append('<button class="delete-video"></button><button class="edit-video"></button>');
						$(this).children('div.video-index').children('button.delete-video').click(function(){
							var video = $(this).parents('div.video-item');	
							var confirmBox = confirm('Are you sure you want to remove selected images?');		
							if (confirmBox == true)
							{
								ImageGrid.deleteImage(video);
								//jsnConfirm.dialog('close');
								showLoading({removeall:false});
							}
						});
						$(this).children('div.video-index').children('button.edit-video').click(function(){
							ImageGrid.editImage($(this).parents('div.video-item'));
						});
					}
				}
			});
			
			if ( ImageGrid.showlistImages.find('div.video-item').length == 0 ){
				if (ImageGrid.showlistImages.find('div.showlist-drag-drop-video-notice').length == 0 ){
					// remove duplicate noice in sync mode
					//ImageGrid.showlistImages.append('<div class="jsn-bglabel">Drag and drop images here</div>');
				}
			}else{
				ImageGrid.showlistImages.find('div.showlist-drag-drop-video-notice').remove();
			}
			ImageGrid.sourceImages.children('div.video-item').each(function(){
				$(this).dblclick(function(){
					// get current image click
					$(this).find('div.video-thumbnail img').each(function(){
						imgsrc  = $(this).attr('src');
					});
					//show popup with image detail					
					$("#dialogboxdetailimage").html('<div class="img-box" style="background-color:#F4F4F4;width:440px;height:400px;display:table-cell; vertical-align:middle;"><img style="max-width: 400px; max-height: 360px;margin: 5px;" src="'+imgsrc+'"></div>')
					.dialog({
								width: 460, 
								height: 620, 
								modal: true, 
								title: '<span style="font-size: 15px; font-weight:bold;">View Image</span>',
								buttons: [
								    {
								        text: "Close",
								        click: function() { $(this).dialog("close"); }
								    }
								] 
							});	
				});
			});	

			//show popup edit image when double click to image

			ImageGrid.showlistImages.children('div.video-item').each(function(){
				$(this).unbind("dblclick").dblclick(function(){	
					if ( ImageGrid.options.selectMode != 'sync'){
						ImageGrid.editImage($(this));	
					}
				});					
			});

		};
		/**
		 * Queue execute 
		 */
		ImageGrid.queueExecute = function(queueArr, n, _callFunc){			
			if ( n == queueArr.length ){
				return;
			}else{
				$(queueArr[n]).unbind("ImageGrid.execute.completed").bind("ImageGrid.execute.completed", function(){
					ImageGrid.queueExecute(queueArr, n+1, _callFunc);
				});
				if ( $.isFunction( _callFunc ) ){
					_callFunc( $(queueArr[n]) );
				}
			}
		};
		/**
		 * Move an element 
		 */
		ImageGrid.moveVideoToShowlist = function(obj, _callFunc, total, i){			
			$('.showlist-drag-drop-video-notice').remove();
			//$('#showlist-images .image-item-multiple-select').removeClass('image-item-multiple-select');
			//Deselect item
			ImageGrid.multipleselect.deSelect(obj);
			//Disable move button
			ImageGrid.activeButtonsAction();
			//Copy an video-item and append to showlist			
			var copy = obj.clone(true);			
			copy.removeAttr('style');
			//ImageGrid.showlistImages.children('div.clr').remove();
			//ImageGrid.showlistImages.children('div.clr').remove();
			if ( $.isFunction(_callFunc) ){
				_callFunc(copy);
			}else{
				copy.appendTo(ImageGrid.showlistImages);
			}
			obj.removeClass('video-item').addClass('image-item-is-selected');
			
			//Save showlist
			if ( $.isFunction(_callFunc) ){				
				ImageGrid.saveShowlist();
			}
			else
			{				
				ImageGrid.saveShowlistMovedAll(obj, total, i);
			}
			
			if(i <= total){
				//Re-index
				ImageGrid.indexImages();
				//Resize layout
				ImageGrid.contentResize();
				//Scroll to bottom
				if ( $.isFunction(_callFunc) ){
					ImageGrid.contents.showlistimages.animate({
						scrollTop : ImageGrid.contents.showlistimages.prop('scrollHeight')
					}, 1500, function(){
						if (i == total)
						{	
							//if (ImageGrid.options.sourceName == 'folder')
								//ImageGrid.checkThumb();
							//else
								showLoading({removeall:true});
						}else{
							obj.trigger("ImageGrid.execute.completed");
						}
						
					});
				}else{
					//if (ImageGrid.options.sourceName != 'folder')
					//{
					//	obj.trigger("ImageGrid.execute.completed");
						//showLoading({removeall:true});
					//}
				}
			}
		};
		
		//moving images is show list
		ImageGrid.moveImageInShowlist = function(obj, _callFunc, total, i){
			//Deselect item
			ImageGrid.multipleselect.deSelect(obj);
			//Disable move button
			ImageGrid.activeButtonsAction();
			//Copy an video-item and append to showlist			
			var copy = obj.clone(true);
			
			copy.removeAttr('style');
			//ImageGrid.showlistImages.children('div.clr').remove();
			if ( $.isFunction(_callFunc) ){
				_callFunc(copy);
			}else{
				copy.appendTo(ImageGrid.showlistImages);
			}			
			//ImageGrid.showlistImages.append('<div class="clr"></div>');	
			
			
			//Save showlist
			if ( $.isFunction(_callFunc) ){
				//ImageGrid.saveShowlist();
			}
			else
			{
//				if (ImageGrid.options.sourceName == 'folder')
//					//ImageGrid.saveShowlistMovedAll(obj, total, i);
//				else
//					//ImageGrid.saveShowlist();	
			}	
			
			if(i <= total){
				//Re-index
				ImageGrid.indexImages();
				//Resize layout
				ImageGrid.contentResize();
				//Scroll to bottom
				if ( $.isFunction(_callFunc) ){
					ImageGrid.contents.showlistimages.animate({
						scrollTop : ImageGrid.contents.showlistimages.prop('scrollHeight')
					}, 1500, function(){						
						obj.trigger("ImageGrid.execute.completed");
					});
				}else{
					//if (ImageGrid.options.sourceName != 'folder')
					//{
					//	obj.trigger("ImageGrid.execute.completed");
						//showLoading({removeall:true});
					//}
				}
			}
		};
		/**
		 * Convert array to JSON data
		 */
		ImageGrid.toJSON          = function( arr ){
			var json = new Array();
			var i = 0;
			for(k in arr){
				if (typeof arr[k] != 'function'){
					if (typeof arr[k] == 'Array' || typeof arr[k] == 'object'){
						json[i] = '"'+k+'":'+ImageGrid.toJSON(arr[k]);
					}else{
						json[i] = '"'+k+'":"'+arr[k]+'"'; 
					}
					i++;
				}
			}
			return '{'+json.join(',')+'}';
		};
		/**
		 * Check session ajax-responce 
		 */
		ImageGrid.checkResponse   = function(res){
			$('input[type="hidden"]', res).each(function(i){
				if ($(this).attr('name') == 'task' && $(this).val() == 'login'){				    
					window.location.reload(true);
				}
	        });
		};
		
		ImageGrid.editImage = function(vEl){
			var params ='&showListID='+ImageGrid.options.showListID+'&imageID=';
			if(vEl.length>1){
				vEl.each(function(){
					params += $(this).attr('id')+"|";				
				});
			}
			else
				params += vEl.attr('id');
	 		url = 'index.php?option=com_imageshow&controller=image&task=editimage&sourceName='+ImageGrid.options.sourceName+'&sourceType='+ImageGrid.options.sourceType+'&tmpl=component'+params;		
			showLoading({removeall:false});
			$("#jsn-is-dialogbox").load(url, function(response, status, xhr) {
				if(status == "success"){
					showLoading({removeall:true});
					$("#jsn-is-dialogbox").dialog({
						width: 600, 
						height: 550,
						modal: true,
						resizable: false,
						draggable: false,						
						title: 'Edit Image Details',
						close: function (){
							$('#image_detail div').html('');
						},
						buttons: {
							'Save': function (){
								$('#jsn-is-link-image-form').submit();
							},
							'Cancel': function (){
								$(this).dialog('close');
							}
						}
					});
				}
			})
		}	
		/**
		 * Delete video
		 */
		ImageGrid.deleteImage    = function(vEl){		

			$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=deleteimageshowlist',{
				showListID : ImageGrid.options.showListID,
				sourceName : ImageGrid.options.sourceName,
				sourceType : ImageGrid.options.sourceType,
				imageID    : vEl.attr('id')
			}).success( function( responce ){
				ImageGrid.checkResponse(responce);
				var vID = vEl.attr('id');
				//vEl.trigger("ImageGrid.execute.completed");
				vEl.fadeOut(300, function(){										
					$(this).remove();
					$('div[id="'+vID+'"]', ImageGrid.sourceImages).each(function(){
						$(this).removeClass('image-item-is-selected').addClass('video-item');
					});		
					ImageGrid.indexImages();		
					ImageGrid.contentResize();
					ImageGrid.multipleselect.init();
					ImageGrid.RestoreShowlistShowType();					
					//ImageGrid.removeselectedAlbum();
				});				
				showLoading({removeall:true});
			});
		};
		
		/**
		 * Check Thumb
		 */
		ImageGrid.checkThumb = function(){		

			$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=checkthumb',{
				showListID : ImageGrid.options.showListID,
				sourceName : ImageGrid.options.sourceName,
				sourceType : ImageGrid.options.sourceType
			}).success( function(responce){
				//JSON.parse(responce).each(function(obj){
					//ImageGrid.createThumb(obj);
				//});	
				var i = 1;
				var total = JSON.parse(responce).length;
				if(!total){
					showLoading({removeall:true});
				}
				ImageGrid.queueExecute(JSON.parse(responce), 0, function(obj){
					ImageGrid.createThumb(obj, total, i);
					if (i == total)
					{
						i = 0;
					}						
					i++;
				});				
			});
		};
		/**
		 * Create Thumb
		 */
		ImageGrid.createThumb = function(obj, total, i){			 		
			var cloneobj={
					image_big:obj[0].image_big,
					image_extid:obj[0].image_extid,
					album_extid:obj[0].album_extid
			}
			$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=createthumb',{
				showListID : ImageGrid.options.showListID,
				sourceName : ImageGrid.options.sourceName,
				sourceType : ImageGrid.options.sourceType,
				image	   : cloneobj
			}).success(function(responce){	
				obj.trigger("ImageGrid.execute.completed");
				if (i == total)
				{
					showLoading({removeall:true});
				}
			});
		};
		/**
		 * Create Thumb for preview
		 */
		ImageGrid.createThumbForPreview = function(elementID, inputID ,folderName, imageName, imagePath){
			$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=createthumbforpreview&rand='+Math.random(),{
				folderName: folderName,
				imageName: imageName,
				imagePath: imagePath
			}).success(function(response){
				var data = JSON.parse(response);
				$('#'+elementID, ImageGrid.sourceImages).attr('src', data.image_path);//.parent().removeClass('isloading');
				$('#'+inputID, ImageGrid.sourceImages).val(data.encode_image_path);
			});
		};		
		/**
		 *
		 * Save your drag and drop modulesList
		 *		 
		 * @return: Save to the database
		 * if (not success){
		 *	undo drag
		 *}
		 */		
		ImageGrid.saveOneImage    = function(){								
			var images = new Array(), i = 0;	
			ImageGrid.showlistImages.find('div.video-item').each(function(){
				images[i] = new Array();
				images[i]['source_type']   = ImageGrid.options.sourceType;
				images[i]['source_name']   = ImageGrid.options.sourceName;
				images[i]['showlist_id']   = ImageGrid.options.showListID;
				images[i]['imgid']   = $(this).attr('id');
				images[i]['order'] = $(this).index();
				images[i]['albumid'] = $(this).find('input.img_extid').val();
				images[i]['img_detail'] = JSON.parse($(this).find('input.img_detail').val());
				images[i]['img_thumb'] = $(this).find('input.img_thumb').val();					
				i++;
			});
			
			if( i > freeImageLimit ){
				showLoading({removeall:true});	
				verNotice(VERSION_EDITION_NOTICE);;
				return false;
			}
			
			$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=saveshowlist',{
				showListID : ImageGrid.options.showListID,
				sourceName : ImageGrid.options.sourceName,
				sourceType : ImageGrid.options.sourceType,
				syncMode   : ImageGrid.options.selectMode,
				images     : ImageGrid.toJSON(images)
			}).success( function( responce ){
				//if (ImageGrid.options.sourceName == 'folder')
					//ImageGrid.checkThumb();
				//else
					showLoading({removeall:true});
				
				ImageGrid.checkResponse(responce);
				ImageGrid.multipleselect.deSelectAll(ImageGrid.showlistImages);	
				ImageGrid.moveImageToShowlistBtt.addClass('disabled').unbind("click");
				ImageGrid.RestoreShowlistShowType();
				ImageGrid.bindClickToShowlistShowTypeButton();				
				//ImageGrid.moveImageToShowlistBtt.parent().addClass('disabled');
			});
		};
		
		//reload source images
		ImageGrid.reloadImageSource = function (cateName){
			var countImages = 0;
			showLoading({height:ImageGrid.options.layoutHeight,removeall:false,element:ImageGrid.panels.panelFull});
			if(ImageGrid.options.sourceType=='external' || ImageGrid.options.sourceName == 'folder'){
				if(!ImageGrid.clickTreeIcon){
					countImages = $('.video-item, .image-item-is-selected', ImageGrid.sourceImages).length;
				}
				$('#showMoreImagesBtn').attr("disabled", true);
				$('#showMoreImagesBtn').html('...'+JSNISLang.translate('SHOWLIST_IMAGE_LOADING'));
				$('#cateNameInShowlist').val(cateName);
			}
			$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=loadSourceImages', {
				showListID : ImageGrid.options.showListID,
				sourceType : ImageGrid.options.sourceType,
				sourceName : ImageGrid.options.sourceName,
				selectMode : ImageGrid.options.selectMode,
				offset	   : countImages,
				cateName   : cateName
			}).success( function(res){
				if(ImageGrid.options.sourceType=='external' || ImageGrid.options.sourceName == 'folder'){
					if(ImageGrid.clickTreeIcon){
						$('.video-item, .image-item-is-selected, .video-no-found', ImageGrid.sourceImages).remove();
						ImageGrid.clickTreeIcon = false;
					}
					$('#showMoreImages').before(res);
				}else{
					ImageGrid.sourceImages.html(res);
				}
				//Add image thumbnail
				//ImageGrid.imageLoading(ImageGrid.sourceImages.find('img[alt="video thumbnail"]'));
				/**
				 * Init events
				 */							 
				ImageGrid.initEvents();
				ImageGrid.sourceImages.find('div.video-item').addClass('item-loaded');
				countImages = $('.video-item, .image-item-is-selected', ImageGrid.sourceImages).length;
				if(ImageGrid.options.sourceType=='external' || ImageGrid.options.sourceName == 'folder'){
					countImages = $('.video-item, .image-item-is-selected', ImageGrid.sourceImages).length;
					ImageGrid.reindexImageSource();
					$('#showMoreImagesBtn').attr("disabled", false);
					$('#showMoreImagesBtn').html(JSNISLang.translate('SHOWLIST_IMAGE_LOAD_MORE_IMAGES'));
					if(countImages >= ImageGrid.imageTotal || ImageGrid.imageTotal==-1){
						$('#showMoreImages').hide();
					}else{
						$('#showMoreImages').show();
					}
				}
				if (!countImages)
				{
					ImageGrid.sourceImages.addClass("jsn-section-empty");
				}
				else
				{
					ImageGrid.sourceImages.removeClass("jsn-section-empty");
				}	
				ImageGrid.RestoreSourceImagesShowType();
				showLoading({removeall:true,element:ImageGrid.panels.panelFull});
			})
		};
		/**
		 * Reindex image source
		 */
		ImageGrid.reindexImageSource = function(){
			var i = 1;
			ImageGrid.sourceImages.children().each(function(){
				$(this).children('div.video-index').html( i++ + '/' + ImageGrid.imageTotal + '<button class="move-to-showlist">&nbsp;</button>');
			});
			ImageGrid.sourceImages.find('button.move-to-showlist').unbind("click").click(function(){
				totalShowedImage = ImageGrid.showlistImages.find('div.video-item').length;
				if( totalShowedImage >= freeImageLimit ){
					showLoading({removeall:true});					
					verNotice(VERSION_EDITION_NOTICE);
					return false;
				}
				var _append;
				showLoading({removeall:false});
				ImageGrid.contents.categories.find('a.jtree-selected').parent().addClass('catselected');
				ImageGrid.moveVideoToShowlist( $(this).parents('div.video-item'),_append,1,1 );
				ImageGrid.showlistImages.removeClass('jsn-section-empty');
			});
		};
		/**
		 *
		 * Save your drag and drop modulesList
		 *		 
		 * @return: Save to the database
		 * if (not success){
		 *	undo drag
		 *}
		 */
		ImageGrid.saveShowlist    = function(){								
				var images = new Array(), i = 0;	
				ImageGrid.showlistImages.find('div.video-item').each(function(){
					images[i] = new Array();
					images[i]['source_type']   = ImageGrid.options.sourceType;
					images[i]['source_name']   = ImageGrid.options.sourceName;
					images[i]['showlist_id']   = ImageGrid.options.showListID;
					images[i]['imgid']   = $(this).attr('id');
					images[i]['order'] = $(this).index();
					images[i]['albumid'] = $(this).find('input.img_extid').val();
					images[i]['img_detail'] = JSON.parse($(this).find('input.img_detail').val());
					images[i]['img_thumb'] = $(this).find('input.img_thumb').val();	
					i++;
				});
				
				if( i > freeImageLimit ){
					showLoading({removeall:true});	
					verNotice(VERSION_EDITION_NOTICE);;
					return false;
				}
			$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=saveshowlist',{
				showListID : ImageGrid.options.showListID,
				sourceName : ImageGrid.options.sourceName,
				sourceType : ImageGrid.options.sourceType,
				syncMode   : ImageGrid.options.selectMode,
				images     : ImageGrid.toJSON(images)
			}).success( function( responce ){				
				ImageGrid.moveImageToShowlistBtt.addClass('disabled').unbind('click');
				//ImageGrid.moveImageToShowlistBtt.parent().addClass('disabled');
				ImageGrid.checkResponse(responce);
				ImageGrid.multipleselect.deSelectAll(ImageGrid.showlistImages);
				ImageGrid.multipleselect.deSelectAll(ImageGrid.sourceImages);	
				ImageGrid.RestoreShowlistShowType();
				ImageGrid.RestoreSourceImagesShowType();
				ImageGrid.bindClickToShowlistShowTypeButton();
			});
		};

		ImageGrid.saveShowlistMovedAll    = function(obj, total, j){								
			var images = new Array(), i = 0;	
			ImageGrid.showlistImages.find('div.video-item').each(function(){
				images[i] = new Array();
				images[i]['source_type']   = ImageGrid.options.sourceType;
				images[i]['source_name']   = ImageGrid.options.sourceName;
				images[i]['showlist_id']   = ImageGrid.options.showListID;
				images[i]['imgid']   = $(this).attr('id');
				images[i]['order'] = $(this).index();
				images[i]['albumid'] = $(this).find('input.img_extid').val();
				images[i]['img_detail'] = JSON.parse($(this).find('input.img_detail').val());
				images[i]['img_thumb'] = $(this).find('input.img_thumb').val();					
				i++;
			});
			
		$.post(baseUrl + 'administrator/index.php?option=com_imageshow&controller=images&task=saveshowlist',{
			showListID : ImageGrid.options.showListID,
			sourceName : ImageGrid.options.sourceName,
			sourceType : ImageGrid.options.sourceType,
			syncMode   : ImageGrid.options.selectMode,
			images     : ImageGrid.toJSON(images)
		}).success( function( responce ){
			obj.trigger("ImageGrid.execute.completed");
			if (j == total)
			{
				//if (ImageGrid.options.sourceName == 'folder')
					//ImageGrid.checkThumb();
				//else
					showLoading({removeall:true});
			}
			ImageGrid.checkResponse(responce);
			ImageGrid.multipleselect.deSelectAll(ImageGrid.showlistImages);
			ImageGrid.moveImageToShowlistBtt.addClass('disabled').unbind("click");
			ImageGrid.RestoreShowlistShowType();
			ImageGrid.bindClickToShowlistShowTypeButton();
			//ImageGrid.moveImageToShowlistBtt.parent().addClass('disabled');
		});
	};
		
		ImageGrid.selectlinkBtt.click(function(){					
			$('#dialogbox2').bPopup({
		            closeClass:'close2',
		            content:'iframe',
		            follow:[false, false],
		            loadUrl:baseUrl+'administrator/index.php?option=com_imageshow&controller=image&view=image&task=showlinkpopup&layout=showlinkpopup&tmpl=component'						           
	        	});			
		});
		
		ImageGrid.removeSync = function(){
			$.post( baseUrl+'administrator/index.php?option=com_imageshow&controller=images&task=removeallSync', {
				showListID : ImageGrid.options.showListID,
				sourceType : ImageGrid.options.sourceType,
				sourceName : ImageGrid.options.sourceName,
				syncMode   : ImageGrid.options.selectMode					
			}).success(function(res){
				$('li', ImageGrid.contents.categories).removeClass("catselected");
			});
		}
		
		// remove prototies selected of a album if that album haven't any image in showlist after delete. 
		ImageGrid.removeselectedAlbum = function(){
			var id_showlistarr = new Array();
			ImageGrid.showlistImages.find('div.image_extid').each(function(e){
				var id = $(this).attr('id').replace('cat_','');												
				id_showlistarr[e] = id;												
			});				
			ImageGrid.contents.categories.find('li.catselected').each(function(){												
				if($.inArray($(this).attr('id'),id_showlistarr ) > -1){
					// doesn't work anything :).
				}else{
					// remove cat don't have any images on showlist images
					$(this).removeClass('catselected');	
				}
			});
		}
		
		return ImageGrid;
	};
	
	showLoading     = function(ops){
		//Option and overwrite option. jQuery extend 
		var _ops = $.extend
		(
			{
				left           : 0,
				top            : 0,
				width          : $(document).width(),
				height         : $(document).height(),
				zIndex         : $.topZIndex(),
				showImgLoading : true,
				removeall      : false,
				element		   : 'body'
			}, 
			ops
		);		
		if ( _ops.removeall ){
			$(_ops.element).find('div.ui-widget-overlay').remove();
			return;
		}
		
		var widgetOverlay = $(_ops.element).children('div.ui-widget-overlay');
		if ( widgetOverlay.length > 0 ){
			return;
		} 
		if ( widgetOverlay.length == 0 ){
		   	var widgetOverlay = $('<div />', {
				'class' : 'ui-widget-overlay'
           	}).css({
           		'top'    : _ops.top,
           		'left'   : _ops.left,
           		'width'  : _ops.width,
           		'height' : _ops.height,
           		'z-index': _ops.zIndex	
           	}).appendTo($(_ops.element));
			//Add image loading
			if ( _ops.showImgLoading ){

				if ( widgetOverlay.find('.img-box-loading').length ){
					widgetOverlay.find('.img-box-loading').remove();
				}
				if(_ops.element == 'body'){
					var top = $(window).scrollTop() + $(window).height()/2-12+'px';
					var left = $(window).scrollLeft() + $(window).width()/2-12+'px';
				}else{
					var height = ($(document).height()!=_ops.height)?_ops.height:$(_ops.element).height();
					var top =  height/2-12+'px';
					var left = $(_ops.element).width()/2-12+'px'
				}
				var imgBoxLoading = $('<div />', {
					                   'class' : 'img-box-loading'
				                    })
				                    .appendTo(widgetOverlay)
	                                .css({
	                                	'position': 'relative',
	                                	'top'     : top,
	                                	'left'    : left
	                                });

				$('<img />', {
					'src' : baseUrl+'administrator/components/com_imageshow/assets/images/ajax-loader.gif'
				})
				.appendTo(imgBoxLoading)
	            .css({
            		'position': 'relative',
            		'left'    : '12px',
            		'top'     : '12px'
	            });
			}
		}
	}
	
	/**
	 * Manager 
	 */
	var Instances = new Array();
	$.JSNISImageGridGetInstaces = function(options){
		if (Instances['JSNISImageGrid'] != undefined ){
			Instances['JSNISImageGrid'].setOption(options);
		}else{
			Instances['JSNISImageGrid'] = new $.JSNISImageGrid(options);
			var obj = Instances['JSNISImageGrid'];
		}
		return Instances['JSNISImageGrid'];
	};
})(jQuery);
