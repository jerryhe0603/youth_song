function projectTypeAhead() {
    var mapped = [];
    $('#project_name').typeahead({
            source: function(text, process) {   
                    if(!text) 
                        return;
                    var data = [];  
                    return $.ajax({
                            url: '../ajax/ajax.CBeauryProject.php?action=typeahead',	//find project_no by name
                            type: 'post',
                            data: {"text": text},
                            dataType: 'json',
                            success: function(json) {   
                                if(json.length) {   
                                    for(var i = 0; i < json.length; i++) {
                                            mapped[json[i].project_name] = json[i].project_no;
                                            data.push(json[i].project_name);
                                    }
                                    return typeof process(data);
                                }
                            }
                    });
            },
            updater: function(item) {
                var str = '<label class="control-label"for="project_board">目標版面</label>';
			    str += '<div class="controls">'+
			                '<label id="add_selector"class="badge badge-primary bigger-110">增加版面</label>'+
			          '</div>';

	          	var project_no = mapped[item];
                $.ajax({
			        url: '../ajax/ajax.CBeautyProject.php?action=board',
			        type: 'post',
			        data: {"text": project_no},
			        dataType: 'json',
			        success: function(json) {
			            for(var i =0; i < json.length; i++) {
			                str += '<div class="controls board_row" id="board_input_'+(i+1)+'" style="margin-bottom: 5px;">'+
			                        '<input type="text" class="board width-40" name="board[]" data-provide="typeahead" placeholder="enter some name..." autocomplete="off" value="'+json[i]['board_title']+'" disabled> '+
			                        '<i class="icon-minus removeBoard"></i>'+
			                        '<input type="hidden" id="galaxy_board_no" name="galaxy_board_no[]" value="'+json[i]['board_no']+'" />'+
			                        '</div>';
			            }
			            $('#board_area').html(str);
			            addSelector();
			        }
			    });

                return item;
            }
    });
}