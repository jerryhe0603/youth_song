var CatchTool = function(fileSavePath, serverName) 
{
	bumblebee_setting.setPageInspectorEnabled(true, '', 300, bumblebee_setting.getWinGUID());

	this.fileSavePath = fileSavePath;	
	this.serverName  = serverName;
	//連續性資料元素的暫存array
	this.siblingPosList = [];
	//元素path的暫存array
	this.pathAbs = [];

	this.catchData = [];

	this.textContent = {};

	this.catchItem = {};

	this.totalItem = [];

	this.imgData = [];

	this.content = [];

	this.crackOff();
	this.bindCatch();
	this.bindCatchElementMapping();
	this.bindContinuityRangeChange();
	this.bindDeleteCatchItem();
	this.bindDeleteTextContent();
	this.bindSave();
}
																
CatchTool.prototype = {
	crackOn: function () {
		// hide all
		bumblebee_setting.hidePageLabel(bumblebee_setting.getWinGUID(), '');

		bumblebee_setting.setPageViewClickEnabled(false, bumblebee_setting.getWinGUID());	/* 滑鼠點擊事件開關 */
		bumblebee_setting.setPageLabelAddEnabled(true, bumblebee_setting.getWinGUID());		/* 新增標記功能 */
		bumblebee_setting.setPageLabelMoveEnabled(true, bumblebee_setting.getWinGUID());	/* 標記移動功能 */
		bumblebee_setting.setPageRectLabelEnabled(true, bumblebee_setting.getWinGUID());	/* 藍色底框功能 */
		//bumblebee_setting.onEnteredLabel.removeAllListener();				/* 先移除監聽關注標記 */
		//bumblebee_setting.onEnteredLabel.addListener(this.enteredLabel.bind(this));	/* 監聽關注標記 */
	},
	crackOff: function () {
		bumblebee_setting.hidePageLabel(bumblebee_setting.getWinGUID(), '');

		bumblebee_setting.setPageViewClickEnabled(true, bumblebee_setting.getWinGUID());	/* 滑鼠點擊事件開關 */
		bumblebee_setting.setPageLabelAddEnabled(false, bumblebee_setting.getWinGUID());		/* 新增標記功能 */
		bumblebee_setting.setPageLabelMoveEnabled(false, bumblebee_setting.getWinGUID());	/* 標記移動功能 */
		bumblebee_setting.setPageRectLabelEnabled(false, bumblebee_setting.getWinGUID());	/* 藍色底框功能 */
	},
	bindCatch: function() {
		var self = this;

		$('#myTab1 li a').click(function() {
			var func = $(this).attr('href') || '#total';

			switch(func) {
				case '#catch':
					self.crackOn();
					bumblebee_setting.onAddedLabel.removeAllListener();				
					bumblebee_setting.onAddedLabel.addListener(self.catchTool.bind(self));		
		
					break;

				case '#photo':
					self.crackOn();
					bumblebee_setting.onAddedLabel.removeAllListener();				
					bumblebee_setting.onAddedLabel.addListener(self.addPhoto.bind(self));	
					break;

				case '#total':
					self.crackOff();
					self.displayTotal();
					break;
			}		
		})
	},
	catchTool: function(guid, uuid) {

		if(guid != bumblebee_setting.getWinGUID()) return;

		var labels = bumblebee_setting.getPageLabel(guid, uuid);
		bumblebee_setting.hidePageLabel(guid, uuid);

		this.pathAbs = [];

		for(var i=0;i<labels[0].selectors.length;i++){
			this.pathAbs.push(labels[0].selectors[i]['abs_path']);
		}

		//sibling_pos_list
		this.siblingPosList = [];
		this.siblingPosList = labels[0].selectors[labels[0].selectors.length-1].sibling_pos_list;

		var elementContent = bumblebee_setting.elementContent(true, this.pathAbs, bumblebee_setting.getWinGUID()) || '';

		var num = 0;

		var continuity = parseInt(this.siblingPosList[num]);

		var elementMappingNo = $('#catch_element_mapping_no').val();
		var result = '';
		var script_element_no = bumblebee_setting.makeUUID();

		function getParentPath(path, continuity) {
			if(path.length == 1)
				return [path[0].split(" > ").slice(0, parseInt(continuity)).join(" > ")];
			else if(path.length > 1) {
				return [path[path.length-1].split(" > ").slice(0, parseInt(continuity)).join(" > ")];
			}
		}

		var data = {
			'parent_path' : getParentPath(this.pathAbs, continuity),
			'element_mapping_no' : elementMappingNo,
			'script_element_no' : script_element_no,
			'path' : this.pathAbs,
			'text' : elementContent,
		};
console.log(data);
		this.catchData = [];
		this.catchData.push(data);
		result += '<p><i class="icon-circle green"></i> '+elementContent+'<p>';

		this.catchItem = {
					"element_mapping_name":$('#catch_element_mapping_no option:selected').text(),
				    "text":this.catchData[0].text,
				    "script_element_no": script_element_no,
				};

		//$('#continuity_range').attr('max', this.siblingPosList.length-1);
		var option = '';
		for(var k = 0; k < this.siblingPosList.length; k++) {
			option += '<option value="'+k+'"> '+k+' </option>';
		}

		$('#continuity_range').html(option);

		//$('#catch_display').data('content', '');
		//$('#catch_display').data('path', abs_paths);

		$('#catch_display').html(result);

		//$('#catch_display').html('');
	},
	bindCatchElementMapping: function() {
		var self = this;
		
		$('#catch_element_mapping_no').change(function() {
			self.catchData = [];
			$('#catch_display').html('');
			$('#continuity_range').val(0);
		});
	},
	bindContinuityRangeChange: function() {
		var self = this;

		function getParentPath(path, continuity) {
			if(path.length == 1)
				return [path[0].split(" > ").slice(0, parseInt(continuity)).join(" > ")];
			else if(path.length > 1) {
				return [path[path.length-1].split(" > ").slice(0, parseInt(continuity)).join(" > ")];
			}
		}

		$('#continuity_range').change(function() {
			var num = $(this).val();
			var continuity = parseInt(self.siblingPosList[num]);

			var siblingElement = bumblebee_setting.siblingElementContent(bumblebee_setting.getWinGUID(), self.pathAbs, true, continuity);
			var elementMappingNo = $('#catch_element_mapping_no').val();
			var result = '';
			var script_element_no = bumblebee_setting.makeUUID();

			self.catchData = [];

			siblingElement.forEach(function(e) {
				if(e.content && e.path) {	
					var data = {
						'parent_path' : getParentPath(e.path, continuity),
						'element_mapping_no' : elementMappingNo,
						'script_element_no' : script_element_no,
						'path' : e.path,
						'text' : e.content,
						'href' : e.href,
						//'element_mapping_name' : $('#catch_element_mapping_no option:selected').text(),
					};

					self.catchData.push(data);
					result += '<p><i class="icon-circle green"></i> '+e.content+'<p>';
				}				
			});

			self.catchItem = {
								"element_mapping_name":$('#catch_element_mapping_no option:selected').text(),
							    "text":self.catchData[0].text,
							    "script_element_no": script_element_no,
							};

			$('#catch_display').html(result);
		});
	},
	addPhoto: function(guid, uuid) {
		if(guid != bumblebee_setting.getWinGUID()) return;

			var labels = bumblebee_setting.getPageLabel(guid, uuid);
			bumblebee_setting.hidePageLabel(guid, uuid);

			var abs_paths = [];

			for(var i=0;i<labels[0].selectors.length;i++){
				abs_paths.push(labels[0].selectors[i]['abs_path']);
			}

			var img_count = $('#photo_area').find('li').size();
			//img_count = 0;

			var data = bumblebee_setting.renderElement(bumblebee_setting.getWinGUID(), abs_paths);
			var html = 
				'<li style="width:30%;" img_count='+img_count+'>'+
					'<div style="height:80px;">'+
						'<img src="'+data+'">'+
						'<div class="text">'+
							'<div class="inner">'+
								'<a class="delete_catch_img" img_count='+img_count+' href="#">'+
									'<i class="icon-trash icon-2x icon-only"></i>'+
								'</a>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</li>';

			$('#photo_area').append(html);
			//$('#photo_area').html(html);

			this.imgData.push(data);		
	},
	bindDeleteCatchItem: function () {
		var self = this;

		$('#photo_area').on('click', '.delete_catch_img', function() {
			var img_conut = $(this).attr('img_count');
			$('#photo_area').find('li[img_count='+img_conut+']').remove();
			self.imgData.splice(img_conut, 1)
		})
	},
	bindDeleteTextContent: function() {
		var self = this;

		$('#total').on('click', '.delete_text_content', function() {
			var script_element_no = $(this).attr('script_element_no');
			$('#'+script_element_no).remove();
			self.removetotalItem(script_element_no);
		});
	},
	removetotalItem: function (script_element_no) {
		for(var i = 0; i < this.totalItem.length; i++) {
			if(this.totalItem[i].script_element_no == script_element_no) {
				this.totalItem.splice(i,1);
				break;
			}
		}	

		for(var k = 0; k < this.content.length; k++){
			if(this.content[k].script_element_no == script_element_no) {
				this.content.splice(k,1);
			}
		}	
	},
	displayTotal: function() {

		var str = '<dl class="dl-horizontal">';

		this.totalItem.forEach(function(item) {
			str += '<div id='+item.script_element_no+' >';
			str += '<dt style="width:80px;">'+item.element_mapping_name+'</dt>';
 			str += '<dd style="margin-left: 90px;">'+item.text;
 			str += 		'<span class="delete_text_content pull-right" script_element_no="'+item.script_element_no+'">';
 			str += 			'<i class="icon-trash icon-2x icon-only"></i>';
 			str += 		'<span>';
 			str += '</dd>';
 			str += '<hr>';	
 			str += '</div>';
		})
		//$.date('')

		// for(var parent_path in this.textContent) {
		// 	for(var element_mapping_no in this.textContent[parent_path]) {
		// 		console.log(this.textContent[parent_path][element_mapping_no]);
		// 		//for(var script_element_uuid in this.textContent[parent_path][element_mapping_no]) {
		// 			str += '<dt style="width:80px;">'+this.textContent[parent_path][element_mapping_no]['element_mapping_name']+'</dt>';
		// 			str += '<dd style="margin-left: 90px;">'+this.textContent[parent_path][element_mapping_no]['text']+'</dd>';
		// 			str += '<hr>';
		// 		//}
		
		// 	}
		// };

		str += '</dl>';

		$('#total').html(str);
	},
	setParentPath: function(data) {	
		if(!(data.parent_path[0] in this.textContent)) {
			this.textContent[data.parent_path[0]] = {};
		}
	}, 
	setElementMapping: function(data) {
		if(!(data.element_mapping_no in this.textContent[data.parent_path[0]])) {
			this.textContent[data.parent_path[0]][data.element_mapping_no] = {};
		}
	}, 
	setScriptElementNo: function(data) {
		if(!(data.script_element_no in this.textContent[data.parent_path[0]][data.element_mapping_no])) {
			this.textContent[data.parent_path[0]][data.element_mapping_no][data.script_element_no] = [];	
		}	

		this.textContent[data.parent_path[0]][data.element_mapping_no][data.script_element_no].push(data);
	}, 
	bindSave: function() {
		var self = this;

		$('#save_content').click(function() {

			if(self.catchData.length == 0) return;
			// for(var i = 0; i < self.catchData.length; i++) {
			// 	self.setParentPath(self.catchData[i]);
			// 	self.setElementMapping(self.catchData[i]);
			// 	self.setScriptElementNo(self.catchData[i]);
			// }

			for(var i = 0; i < self.catchData.length; i++) {
				self.content.push(self.catchData[i]);
			}

			self.catchData = [];
			$('#catch_display').html('');
			$('#continuity_range').val(0);

			//顯示用
			self.totalItem.push(self.catchItem);
		});	
	},
	upload: function() {
		for(var i = 0; i < this.content.length; i++) {
			this.setParentPath(this.content[i]);
			this.setElementMapping(this.content[i]);
			this.setScriptElementNo(this.content[i]);
		}
//console.log(JSON.stringify(this.textContent));
		bumblebee_setting.saveFile('post/'+this.fileSavePath+'/catch_0_0.json', JSON.stringify(this.textContent));

		this.content = [];

		for(var k = 0; k < this.imgData.length; k++) {
			bumblebee_setting.saveFile('post/'+this.fileSavePath+'/image_'+k, this.imgData[k]);
		}

		this.imgData = [];
		var description = '{"project_no":"'+$('#project_no').val()+'", "principle_no":"'+$('#principle_no').val()+'", "board_no":"'+$('#board_no').val()+'", "parent_doc_no": "'+$('#parent_doc_no').val()+'", "url":"'+$('#url').val()+'"}';

		bumblebee_setting.upload('http://'+this.serverName+'/api/api.BeautyCatchReturn.php', this.fileSavePath, description);
		this.textContent = {};
	}
	// bindAddCatchItem: function() {
	// 	// var self = this;

	// 	// $('#catch_element_mapping_no').on('change', function() {

	// 	// 	var text = $('#catch_display').text().trim();	
	// 	// 	var element_mapping_no = $('#catch_element_mapping_no').val();	
	// 	// 	var path = $('#catch_display').data('path');

	// 	// 	var element_mapping_name = $('#catch_element_mapping_no option:selected').text();

	// 	// 	var item = {
	// 	// 		"element_no": null,
	// 	// 		"element_mapping_no": element_mapping_no,
	// 	// 		"path_parent": null,
	// 	// 		"content": [
	// 	// 			{
	// 	// 				"path": path,
	// 	// 				"text": text,
	// 	// 			}
	// 	// 		],
	// 	// 		"type":"text",
	// 	// 		"element_mapping_name": element_mapping_name,
	// 	// 	};

	// 	// 	console.log(item);

	// 	// 	self.CatchData.push(item);
	// 	// 	$('#catch_display').text('');
	// 	// })
	// }
}