var PostTool = function(doc_no, genesis_server, file_save_path) 
{
	bumblebee_setting.setPageInspectorEnabled(true, '', 300, bumblebee_setting.getWinGUID());

	//this.crackOff();
	this.bindCustomPhoto();
	this.bindScreenPhoto();
	
	//id="photo_area" class="delete_catch_img"
	this.bindDeleteCatchItem();

	this.prefix_file_path = 'post_tool/'+file_save_path;

	this.ImgData = {};

	this.doc_no = doc_no;

	this.genesis_server = genesis_server;
}
																
PostTool.prototype = {
	bindCustomPhoto: function() {
		var self = this;
		$('#customPhoto').click(function() {
			bumblebee_setting.screenShotPage(bumblebee_setting.getWinGUID());	
			bumblebee_setting.onFinishScreenShot.removeAllListener();	
			bumblebee_setting.onFinishScreenShot.addListener(self.photoTool.bind(self));			
		})
	},
	bindScreenPhoto: function() {
		var self = this;
		$('#screenPhoto').click(function() {
			bumblebee_setting.renderElement(bumblebee_setting.getWinGUID(), ['body'], function(data) {
				img_count = 0;

				//var data = bumblebee_setting.renderElement(bumblebee_setting.getWinGUID(), abs_paths);
				var html = 
					'<li style="width:100%;" img_count='+img_count+'>'+
						'<div style="height:100%;">'+
							'<img src="'+data+'">'+
							'<div class="text">'+
								'<div class="inner">'+
									// '<span>預覽 刪除</span>'+
									// '<br>'+
									// '<a data-rel="colorbox" class="cboxElement">'+
									// 	'<i class="icon-zoom-in"></i>'+
									// '</a>'+

									// '<a class="delete_catch_img" img_count='+img_count+' href="#">'+
									// 	'<i class="icon-ban-circle"></i>'+
									// '</a>'+
									'<a class="delete_catch_img" img_count='+img_count+' href="#">'+
										'<i class="icon-trash icon-5x icon-only"></i>'+
									'</a>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</li>';
			
				//$('#photo_area').append(html);
				$('#photo_area').html(html);

				this.ImgData = data;
			});			
		})
	},
	catchTool: function(guid, uuid) {
		if(guid != bumblebee_setting.getWinGUID()) return;
	
		var labels = bumblebee_setting.getPageLabel(guid, uuid);
		bumblebee_setting.hidePageLabel(guid, uuid);

		var abs_paths = [];

		for(var i=0;i<labels[0].selectors.length;i++){
			abs_paths.push(labels[0].selectors[i]['abs_path']);
		}

		content = [];
		var content = bumblebee_setting.evaluateJavaScript('this.textContent', abs_paths, bumblebee_setting.getWinGUID());
		
		$('#catch_display').data('content', content);
		$('#catch_display').data('path', abs_paths);

		$('#catch_display').html(content);
	},
	photoTool: function(guid, map) {
		if(guid != bumblebee_setting.getWinGUID()) return;
	
		//var labels = bumblebee_setting.getPageLabel(guid, uuid);
		//bumblebee_setting.hidePageLabel(guid, uuid);

		//var abs_paths = [];

		//for(var i=0;i<labels[0].selectors.length;i++){
			//abs_paths.push(labels[0].selectors[i]['abs_path']);
		//}

		//var img_count = $('#photo_area').find('li').size()+1;
		img_count = 0;

		//var data = bumblebee_setting.renderElement(bumblebee_setting.getWinGUID(), abs_paths);
		var html = 
			'<li style="width:100%;" img_count='+img_count+'>'+
				'<div style="height:100%;">'+
					'<img src="'+map.data+'">'+
					'<div class="text">'+
						'<div class="inner">'+
							// '<span>預覽 刪除</span>'+
							// '<br>'+
							// '<a data-rel="colorbox" class="cboxElement">'+
							// 	'<i class="icon-zoom-in"></i>'+
							// '</a>'+

							// '<a class="delete_catch_img" img_count='+img_count+' href="#">'+
							// 	'<i class="icon-ban-circle"></i>'+
							// '</a>'+
							'<a class="delete_catch_img" img_count='+img_count+' href="#">'+
								'<i class="icon-trash icon-5x icon-only"></i>'+
							'</a>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</li>';
	
		//$('#photo_area').append(html);
		$('#photo_area').html(html);

		this.ImgData = map.data;
	},
	bindDeleteCatchItem: function () {
		var self = this;

		$('#photo_area').on('click', '.delete_catch_img', function() {
			var img_conut = $(this).attr('img_count');
			$('#photo_area').find('li[img_count='+img_conut+']').remove();
			delete self.ImgData[img_conut];
		})
	},
	save: function() {
		var self = this;

		//upload picture
		// var i = 0;
		// this.ImgData.forEach(function(img) {
		// 	var file_path = self.prefix_file_path+'img_'+i+'.png';
		// 	bumblebee_setting.saveFile('post/'+file_path, img);  
		// 	i++;
		// });

		//upload photo
		if(self.ImgData) {
			var file_path = self.prefix_file_path+'image_0';
			bumblebee_setting.saveFile('post/'+file_path, self.ImgData);  
			bumblebee_setting.upload('http://'+this.genesis_server+'/api/api.upload_decode_file.php?docs_no='+this.doc_no, self.prefix_file_path, '');		
		}

		//upload now url 
		var url = bumblebee_setting.getWinUrl(bumblebee_setting.getWinGUID());	
		$('#url').val(url);
	},
}