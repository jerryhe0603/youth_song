function reloadAntiSpam(){
	var url="./antispam.php?time="+Math.floor(Math.random()*10000);
	document.getElementById("antispam").src=url;
}	

function selectNewMusicTool(){
	var url = "selectNewMusicTool.php";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#music_tool_data').html(html);
		  }
	});
}

function addNewMusicTool(){
	var url = "selectNewMusicTool.php?func=add";
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#music_tool_data').html(html);
		  }
	});
}

function editNewMusicTool(id,music_tool,ability){
	var url = "selectNewMusicTool.php?func=edit&id="+id+"&music_tool="+music_tool+"&ability="+ability;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#music_tool_data').html(html);
		  }
	});
}

function delNewMusicTool(id){
	var url = "selectNewMusicTool.php?func=del&id="+id;
	$.ajax({
		  url: url,
		  cache: false,
		  success: function(html){
			$('#music_tool_data').html(html);
		  }
	});
}