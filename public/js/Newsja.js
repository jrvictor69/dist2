/**
 * Javascript for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

var com = com || {};
com.em = com.em ||{};
	com.em.Newsja = function () {
		// For create or update register
		this.dialogForm;
		// For show message to client
		this.alert;
		// For data table
		this.table;
		// urls
		this.url = {};
		this.validator;
		
		this.initFlashMessage();
		this.initEvents();
		
		this.dtHeaders = undefined;
		this.actionSort = undefined;
	};
com.em.Newsja.prototype = {
	
	/**
	 * 
	 * Initializes JQuery flash message component
	 */	
	initFlashMessage: function() {
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
			$('#nameFilter').attr('value', '');
			initDisplayStart();
			table.oApi._fnAjaxUpdate(table.fnSettings());
		});
	}},
	
	/**
	 * 
	 * Initializes Display start of datatable
	 */
	initDisplayStart: function() {with(this) {
		var oSettings = table.fnSettings();
		oSettings._iDisplayStart = 0;
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
		
		$("#datatable-headers").prepend('<th >Id</th>');		
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
			"bSort"         : false,
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
//				clickToUpdate('#tblNews a[id^=update-news-]');
			},
			
			"fnServerData": function (sSource, aoData, fnCallback ) { 
				//applying filter_title
				var position = getPosition(aoData, 'filter_name');
				
				if (position == -1)
					aoData.push({"name": "filter_name","value": $('#nameFilter').attr('value')});				
				else
					aoData[position].value=$('#nameFilter').attr('value');
				
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
			"sWidth": "30%",
			"bSercheable": "true",
			fnRender : function (oObj){
				return '<a id="update-news-'+oObj.aData[0]+'" href="'+url.toUpdate+'/id/'+oObj.aData[0]+'">'+oObj.aData[1]+'</a><div><img src="/image/upload/news/'+oObj.aData[3]+'" height="90" width="100" title="Lone Tree Yellowstone" data-description="A solitary tree surviving another harsh winter in Yellowstone National Park. Yellowstone National Park, Wyoming. (Photo and caption by Anita Erdmann/Nature/National Geographic Photo Contest) " /></div>';
				}
			});
		columns.push({"sWidth": "70%"});
		
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
	
	/**
	 * 
	 * Configures the name autocomplete of the filter
	 * @param selector
	 */
	configureAuto: function(selector) { with (this) {
		$(selector).autocomplete({
			source: function(request, response) {
				$.ajax({
					url: url.toAutocomplete,
					dataType: 'json',
					data: {name_auto: request.term},
					success: function(data, textStatus, XMLHttpRequest) {
						response($.map(data.items, function(item) {
							return {
								label: item
							};
						}));
					}
				});
			}
		});
	}},
	
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