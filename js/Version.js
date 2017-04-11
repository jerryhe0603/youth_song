version = function(version, download_server) 
{
	this.version = version || {};
	this.download_server = download_server;

	this.listenDownloadFinish();

	this.checkJsVersion();

	this.checkBumbelbeeVersion();
}
																
version.prototype = {
	checkJsVersion: function() {
		if(!this.version || !this.version.js) 
			this.showMsg('無法得到版本資訊');

		var NowJsVersion = bumblebee_setting.getBumblebeeJsVersion();
		
		if(NowJsVersion !== this.version.js.sVersion) {

	
		

				bumblebee_setting.fileSaveAs('http://'+this.download_server+'/bumblebee_js.zip','data/bumblebee_js.zip');
				
		}

	},
	checkBumbelbeeVersion: function() {
		var mainVersion = bumblebee_setting.version();

		if(mainVersion != this.version.bumblebee.sVersion) {
			//alert("目前版本: "+version);
			if(bumblebee_setting.getOS == "unix") {
				bumblebee_setting.fileSaveAs('http://'+this.download_server+'/bumblebee_install_ubuntu_x86_64.run','data/bumblebee_install_ubuntu_x86_64.run');	
			} else {
				bumblebee_setting.fileSaveAs('http://'+this.download_server+'/bumblebee_install_windows.exe','data/bumblebee_install_windows.exe');
			}				
		}

	},
	showMsg: function(msg) {
		if ($("#bumble_version_msg_area").length == 0){
			var html = 	'<div id="bumble_version_msg_area" class="span2 offset6" style="position: fixed; ">'+
							'<div id="bumble_version_msg_content"class="alert alert-block alert-warnig">'+
								'<i class="icon-ok yellow"></i>'+
							'</div>'+
						'</div>';		
			$('body').append(html);
		}

		$('#bumble_version_msg_content').text(msg);
		$("#bumble_version_msg_area").fadeIn('fast', function(){
			$(this).fadeOut(3000);
		});
	},
	listenDownloadFinish :function() {
		function updateFinished() {
			if(this.version.js.sVersion) {
				bumblebee_setting.setBumblebeeJsVersion(this.version.js.sVersion);
				bumblebee_setting.onUpdateFinished.removeAllListener();
				this.showMsg('JS更新成功');
			}
		}

		bumblebee_setting.onUpdateFinished.removeAllListener();
		bumblebee_setting.onUpdateFinished.addListener(updateFinished.bind(this));

	},
}
