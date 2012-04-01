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
	com.em.Department = function () {
		// For create or update register
		this.dialogForm;
		// For show message to client
		this.alert;
		// For data table
		this.table;
		// urls
		this.url = {};
		this.validator;
		
		this.initAlert();
		this.initEvents();
		
		this.dtHeaders = undefined;
		this.actionSort = undefined;
	};
com.em.Department.prototype = {
	
	/**
	 * 
	 * Initializes JQuery alert component
	 */	
	initAlert: function() {
		this.alert = new com.em.Alert();
	},
	
	/**
	 * 
	 * Initializes all the events for items on page
	 */
	initEvents: function() {with(this) {
		$("#nameFilter").bind('keydown', function(e) {
			if (e.type == 'keydown' && e.which == '13') {
				initDisplayStart();
				table.oApi._fnAjaxUpdate(table.fnSettings());
			}
		});
		
		$("#searchButton").bind('click', function() {
			initDisplayStart();
			table.oApi._fnAjaxUpdate(table.fnSettings());
		});
		
		$("#resetButton").bind('click', function() {
			//set value to all and refresh the input for select
//			$('#countryFilter').attr('value', 'All');
//			$('#countryFilter').change();
			$("#countryFilter option[value="+-1+"]").attr("selected", true);
			$('#nameFilter').attr('value', '');
			
			initDisplayStart();
			table.oApi._fnAjaxUpdate(table.fnSettings());
		});
		
		$("#countryFilter").change(function() {
			var oSettings = table.fnSettings();
	   		oSettings._iDisplayStart = 0;
	   		table.oApi._fnAjaxUpdate(oSettings);
		});
	}},
	
	/**
	 * 
	 * Initializes Display start of datatable
	 */
	initDisplayStart: function() {with(this) {
		var oSettings = table.fnSettings();
		oSettings._iDisplayStart = 0;
		//rows by page
//		oSettings._iDisplayLength = 3;
	}},
	
	/**
	 * 
	 * Sets headers of datatable
	 * @param pheaders
	 */
	setHeaders: function(pheaders){with(this) {
		pheaders = typeof pheaders !== 'undefined' ? pheaders : dtHeaders;
			
		if (typeof dtHeaders === 'undefined') {
			dtHeaders = pheaders;
		}
		
		var headers = pheaders['headerArray'];
		
		$("#datatable-headers").empty();
		
		for ( var i = 0; i < headers.length; i++) {
			$("#datatable-headers").append('<th>'+headers[i]+'</th>');
		}
		
		$("#datatable-headers").append('<th><input type="checkbox" id="check-master" value="0"></th>');
		$("#datatable-headers").prepend('<th >Id</th>');
		
		//Adding the event to check-master because it was removed
		$("#check-master").bind('click', function() {
			var checked = $("#check-master").attr('checked');
			if (checked) {
				$('#tblDepartment input[id^=check-slave-]').attr('checked', true);
			} else {
				$('#tblDepartment input[id^=check-slave-]').attr('checked', false);
			}
		});		
	}},
	
	/**
	 * 
	 * Configures the table and elements
	 * @param selector
	 */
	configureTable: function(selector, pdestroy) { with (this) {
		table = $(selector).dataTable({
			"bProcessing"   : true,
			"bFilter"       : false,
			"bSort"         : true,
			"bInfo"         : true, 
			"bLengthChange" : false,
			"bServerSide"   : true,
			"sAjaxSource"   : url.toTable,
			"aoColumns"     : getColumns(),
		    "sPaginationType" : "full_numbers",
			"oLanguage": {
				"sEmptyTable": "No Catagory found."
			},
			"fnDrawCallback": function() {
				clickToUpdate('#tblDepartment a[id^=update-department-]');
			},
			
			"fnServerData": function (sSource, aoData, fnCallback ) { 
				//applying filter_name
				var position = getPosition(aoData, 'filter_name');
				
				if (position == -1)
					aoData.push({"name": "filter_name", "value": $('#nameFilter').attr('value')});				
				else
					aoData[position].value=$('#nameFilter').attr('value');
				
				//applying filter_maingroup
				position = getPosition(aoData,'filter_maingroup');
				
				if(position == -1)
					aoData.push({"name": "filter_country", "value": $('#countryFilter').attr('value')});				
				else
					aoData[position].value = $('#countryFilter').attr('value');
				
	            $.getJSON(sSource, aoData, function (json) {
	                fnCallback(json);       
	            } );
			}
		});
		$(selector).width("100%");
	}},
	
	/**
	 * 
	 * Gets columns configuration for datatable
	 * @return Array
	 */
	getColumns: function() {with (this) {
		var columns = new Array;
		//Sets every element of the table headers
		columns.push({bVisible:false});
		columns.push({
			"sWidth": "20%",
			"bSercheable": "true",
			fnRender : function (oObj){
				return '<a id="update-department-'+oObj.aData[0]+'" href="'+url.toUpdate+'/id/'+oObj.aData[0]+'">'+oObj.aData[1]+'</a>';
				}
			});
		columns.push({"sWidth": "38%"});
		columns.push({"sWidth": "20%"});
		columns.push({"sWidth": "10%"});
		columns.push({"sWidth": "10%"});
		columns.push({
			"bSortable": false,
			"sWidth": "2%",
			"sClass": "checkColumn",
			fnRender : function (oObj){
			   return '<input type="checkbox" name="itemIds[]" id="check-slave-'+oObj.aData[0]+'" value="'+oObj.aData[0]+'">';
			}
		});
		
		return columns;
	}},
	
	/**
	 * Shows proccessing display for data table
	 * @param bShow boolean
	 */
	processingDisplay: function(bShow) {
		var settings = table.fnSettings();
		settings.oApi._fnProcessingDisplay(settings, bShow);
	},
	
//	processingDisplay : function(bShow) {
//		if(bShow)
//			$.blockUI({ css:{ 
//				border					: 'none', 
//				padding					: '15px', 
//				backgroundColor			: '#000', 
//				'-webkit-border-radius'	: '10px', 
//				'-moz-border-radius'	: '10px', 
//				opacity					: .5, 
//				color					: '#fff' 
//			} }); 
//		else
//			$.unblockUI();
//	},
	
	/**
	 * 
	 * Configures the form
	 * @param selector (dialog of form)	 
	 * */
	configureDialogForm: function(selector) {with (this) {
		dialogForm = $(selector).dialog({
			autoOpen: false,
			height: 200,
			width: 350,
			modal: true,
			close: function(event, ui) {
				$(this).remove();
			}
		});				
		
//		$('#formId').submit(function() {
//			return false;
//		});
		
		// Configs font-size for header dialog and buttons
		$(selector).parent().css('font-size','0.7em');
	}},
	
	/**
	 * 
	 * Opens dialog and manages the creation of new register
	 * @param selector
	 */
	clickToAdd: function(selector) {with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			// Begins to get data
			var action = $(this).attr('href');
			// Sends request by ajax
			$.ajax({
				url: action ,
				type: "POST",
				beforeSend : function(XMLHttpRequest) {
					processingDisplay(true);
				},
				
				success: function(data, textStatus, XMLHttpRequest) {
					if (textStatus == 'success') {
						var contentType = XMLHttpRequest.getResponseHeader('Content-Type');
						if (contentType == 'application/json') {
							alert.show(data.message, {header : com.em.Alert.FAILURE});
						} else {
							// Getting html dialog
							$('#dialog').html(data);
							// Configs dialog
							configureDialogForm('#dialog-form', 'insert');
							// Sets validator
							setValidatorForm("#formId");
							// Opens dialog
							dialogForm.dialog('open');
							// Loads buttons for dialog. dialogButtons is defined by ajax
							dialogForm.dialog( "option" , 'buttons', dialogButtons);
						}
					} 
				},
				
				complete: function(jqXHR, textStatus) {
					processingDisplay(false);
				},
				
				error: function(jqXHR, textStatus, errorThrown) {
					dialogForm.dialog('close');
					alert.show(errorThrown,{header : com.em.Alert.ERROR});
				}
			});
		});
	}},
	
	/**
	 * 
	 * Opens dialog and manages the update of register
	 * @param selector
	 */
	clickToUpdate: function(selector) {with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			// Begins to get data
			var action = $(this).attr('href');
			// Sends request by ajax
			$.ajax({
				url: action,
				type: "POST",
				beforeSend: function(XMLHttpRequest) {
					processingDisplay(true);
				},
				
				success: function(data, textStatus, XMLHttpRequest) {
					if (textStatus == 'success') {
						var contentType = XMLHttpRequest.getResponseHeader('Content-Type');
						if (contentType == 'application/json') {
							alert.show(data.message, {header : com.em.Alert.FAILURE});
						} else {
							// Getting html dialog
							$('#dialog').html(data);
							// Configs dialog
							configureDialogForm('#dialog-form', 'update');
							// Sets validator
							setValidatorForm("#formId");
							// open dialog
							dialogForm.dialog('open');
							// Loads buttons for dialog. dialogButtons is defined by ajax
							dialogForm.dialog( "option" , 'buttons' , dialogButtons);
						}
					} 
				},
				
				complete: function(jqXHR, textStatus) {
					processingDisplay(false);
				},
				
				error : function(jqXHR, textStatus, errorThrown) {
					dialogForm.dialog('close');
					alert.show(errorThrown,{header : com.em.Alert.ERROR});
				}
			});
		});
	}},
	
	/**
	 * 
	 * Deletes n items
	 * @param selector
	 */
	clickToDelete: function(selector) {with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			// Serializes items checked
			var items = $('#tblDepartment :checked');
			var itemsChecked = items.serialize();
			if (itemsChecked == '') {
				alert.show('There is no item selected', {header:com.em.Alert.SUCCESS});
				return;
			}
			var action = $(this).attr('href');
			
			jConfirm('Are you sure to delete?', 'Delete Department', function(r) {			    
			    if (r) {
			    	$.ajax({
						dataType: 'json', 
						type: "POST", 
						url: action,
						// Gets element checkbox checked
						data: itemsChecked,
						beforeSend : function(XMLHttpRequest) {
							processingDisplay(true);
						},
						
						success : function(data, textStatus, XMLHttpRequest) {
							if (textStatus == 'success') {
								if (data.success) {
									table.fnDraw();
									alert.show(data.message);
								} else {
									alert.show(data.message, {header : com.em.Alert.SUCCESS});
								}
							}
						},
						
						complete : function(jqXHR, textStatus) {
							processingDisplay(false);
						},
						
						error : function(jqXHR, textStatus, errorThrown) {
							alert.show(errorThrown,{header : com.em.Alert.ERROR});
						}
					});
				} else {
					return;
				}
			});
		});
	}},
	
	/**
	 * 
	 * Validates department form
	 * @param selector
	 */
	setValidatorForm : function(selector) {
		validator = $(selector).validate({
	        rules:{
//	        	"name":{
////					required: true,
////					minlength: 3,
//					maxlength: 100
//				},
				"description":{
					required: true,
					minlength: 3
				}
	        }
	    });
	},
	
	/**
	 * 
	 * Sets url for action side server
	 * @param url json
	 */
	setUrl: function(url) {
		this.url = url;
	},
	
	/**
	 * 
	 * Gets number position of name in array data
	 * @param array containing sub-arrays with the structure name->valname, value->valvalue
	 * @param name is the string we are looking for and must match with valname
	 */
	getPosition: function(data, name) {
		var pos = -1;
		for ( var i = 0; i < data.length; i++) {
			if (data[i].name == name) {
				pos = i;
			}
		}
		return pos;
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
	}}
};