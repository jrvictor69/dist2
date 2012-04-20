/**
 * Javascript for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

var com = com || {};
com.em = com.em ||{};
	com.em.NewsUpdate = function () {
		// Alert to show a message to client
		this.alert;
		// urls
		this.url = {};
		this.validator;
		
		this.initAlert();
		this.initEvents();
		this.setValidatorForm('#formId');
	};
com.em.NewsUpdate.prototype = {
	
	/**
	 * 
	 * Initializes all the events for items on page
	 */
	initAlert: function() {
		this.alert = new com.em.Alert();
	},
		
	/**
	 * 
	 * Shows alert if it exists, if not create a new instance of alert and show it
	 * @param message to show
	 * @param header of the message
	 */
	showAlert: function(message, header) {with (this) {
		if (this.alert == undefined) {
			this.alert = new com.em.Alert();
		}
		alert.show(message, header);
	}},
	
	/**
	 * 
	 * Initializes all the events for items on page
	 */
	initEvents: function() {with(this) {		
//		$("#title").bind('keyup', function(e) {
//			var titleVal = $('#title').val();
//			if (titleVal != '') {
//				$('#title').parent().find('.error').remove();
//			}
//		});
	}},
	
	/**
	 * Add an event to save changes on left or right form
	 *   
	 * @param string selector of save button
	 */
	clickToUpdate: function(selector) { with (this){
		$(selector).bind('click',function(event) {
//			var tinyObj = tinyMCE.get('contain');
//	        var htmlContent = tinyObj.getContent();
//	        
//	        $("#contain").val(htmlContent);
////	        console.log($("#contain").val());
////	        console.log(htmlContent);
//	        $('#update').click();


	        
//	        console.log('dsd');
//	        $.ajax({
//				url: url.toUpdate,
//				type: "POST",
//				dataType: 'json',
//				data: $('#formId').serialize(),
//				beforeSend: function(XMLHttpRequest) {
//					//validate form
//					validator.form();
//					if (!validator.valid() ) {
//						return false;
//					}
//					
//					return true;
//				},
//				
//				success: function(data, textStatus, XMLHttpRequest) {
//					if (textStatus == 'success') {
//						if (data.success) {
//							alert.show(data.message, {header : com.em.Alert.SUCCESS});
//							setTimeout(function() {
//								window.location.href = "http://dist2/admin/news/index/type/information";
//							}, 2000);
//						} else {
//							if (data.duplicate_title) {
//								alert.show(data.message, {header : com.em.Alert.ERROR});
//								$('#title').parent().find('.error').remove();
//								$('#title').parent().append('<label class="error" for="name" generated="true">'+data.message+'</label>');
//							} else {
//								alert.show(data.message, {header : com.em.Alert.ERROR});
//							}
//						}
//					}
//				},
//				
//				complete: function(jqXHR, textStatus) {
//					
//				},
//				
//				error: function(jqXHR, textStatus, errorThrown) {
//					alert.show(errorThrown, {header: com.em.Alert.ERROR});
//				}
//			});
		}); 
	}},
	
	/**
	 * Add an event to save changes on left or right form
	 *   
	 * @param string selector of save button
	 */
//	clickToUpdate: function(selector) { with (this){
//		$(selector).bind('click',function(event) {
//			var tinyObj = tinyMCE.get('contain');
//	        var htmlContent = tinyObj.getContent();
//	        
//	        $.ajax({
//				url: url.toUpdate,
//				type: "POST",
//				dataType: 'json',
//				data: $('#formId').serialize(),
//				beforeSend: function(XMLHttpRequest) {
//					//validate form
//					validator.form();
//					if (!validator.valid() ) {
//						return false;
//					}
//					
//					return true;
//				},
//				
//				success: function(data, textStatus, XMLHttpRequest) {
//					if (textStatus == 'success') {
//						if (data.success) {
//							alert.show(data.message, {header : com.em.Alert.SUCCESS});
//							setTimeout(function() {
//								window.location.href = "http://dist2/admin/news/index/type/information";
//							}, 2000);
//						} else {
//							if (data.duplicate_title) {
//								alert.show(data.message, {header : com.em.Alert.ERROR});
//								$('#title').parent().find('.error').remove();
//								$('#title').parent().append('<label class="error" for="name" generated="true">'+data.message+'</label>');
//							} else {
//								alert.show(data.message, {header : com.em.Alert.ERROR});
//							}
//						}
//					}
//				},
//				
//				complete: function(jqXHR, textStatus) {
//					
//				},
//				
//				error: function(jqXHR, textStatus, errorThrown) {
//					alert.show(errorThrown, {header: com.em.Alert.ERROR});
//				}
//			});
//		}); 
//	}},
//	
	/**
	 * 
	 */
	clickToLoad: function(selector) { with (this){
		$(selector).bind('click',function(event) {
			$.ajax({
				dataType: 'json',
				url: url.toLoad,
				type: "GET",
				beforeSend: function(XMLHttpRequest) {
					
				},
							
				success: function(data, textStatus, XMLHttpRequest) {
					var tinyObj = tinyMCE.get('contain');
					tinyObj.setContent(data.htmlContent);
				},
	
				complete: function(jqXHR, textStatus) {
					
				},
				
				error: function (jqXHR, textStatus, errorThrown) {
									
				}
			});
		});
	}},
	
	/**
	 * set url for action side server
	 * @param array url
	 */
	setUrl : function(url) {
		this.url = url;
	},
	
	/**
	 * Configure tinymce textarea and elements
	 *  
	 * @param selector
	 */
	configureTinymce : function(selector) { with (this) {
//			$(selector).tinymce({
//				relative_urls : false,
//				// Location of TinyMCE script
//				document_base_url : '/var/www/html/metasitenew/public/',
//				//script_url : url.toBase + '/tinymce/jscripts/tiny_mce/tiny_mce.js',
//				// General options
//				theme : "advanced",
//				
//
//				plugins : "autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,imagemanager",
//				
//				// Theme options
//				theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
//				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,|,forecolor,backcolor",
//				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
//				theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,insertimage,preview",
//				theme_advanced_toolbar_location : "top",
//				theme_advanced_toolbar_align : "left",
//				theme_advanced_statusbar_location : "bottom",
//				theme_advanced_resizing : false,
//
//				// Example content CSS (should be your site CSS)
//				content_css : "../../../../../css/screen.css",
//
//				// Drop lists for link/image/media/template dialogs
//				template_external_list_url : "lists/template_list.js",
//				external_link_list_url : "lists/link_list.js",
//				external_image_list_url : "lists/image_list.js",
//				media_external_list_url : "lists/media_list.js",
//				imagemanager_rootpath : '{0}/' + tableHeaderId
//				
//			});
			
			
		$(selector).tinymce({
		// Location of TinyMCE script
		script_url : '../jscripts/tiny_mce/tiny_mce.js',

		// General options
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
//		plugins : "autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,imagemanager",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		//
		// Example content CSS (should be your site CSS)
		content_css : "css/content.css"
		//
//			// Drop lists for link/image/media/template dialogs
//			template_external_list_url : "lists/template_list.js",
//			external_link_list_url : "lists/link_list.js",
//			external_image_list_url : "lists/image_list.js",
//			media_external_list_url : "lists/media_list.js",
		//
//			// Replace values for the template plugin
//			template_replace_values : {
//				username : "Some User",
//				staffid : "991234"
//			}
			});
	}},
	
	initFile: function(selector) { with (this){
		$(selector).bind('click',function(event) {
			$.ajax({
				dataType: 'json',
				url: url.toLoad,
				type: "GET",
				beforeSend: function(XMLHttpRequest) {
					
				},
							
				success: function(data, textStatus, XMLHttpRequest) {
					var tinyObj = tinyMCE.get('contain');
					tinyObj.setContent(data.htmlContent);
				},
	
				complete: function(jqXHR, textStatus) {
					
				},
				
				error: function (jqXHR, textStatus, errorThrown) {
									
				}
			});
		});
	}},
	
	/**
	 * 
	 * Validates privilege form
	 * @param selector
	 */
	setValidatorForm : function(selector) {
		validator = $(selector).validate({
	        rules:{
	        	'title':{
					required: true,
					maxlength: 255
				},
				'summary':{
					required: true
				},
				'fount':{
					required: true,
					maxlength: 255
				}
	        },
	        messages:{
				'title':{
					required: "No puede ser vacio inserte un valor",
					maxlength: "El limite es de 255"
				}
			}
	    });
	}
};