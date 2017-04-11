BindBootBox();
function CheckElement(){
    console.log('check element');
    $.each( $( '#require_element_mapping' ).children(), function( idx, elem ) {
        var testId = elem.id;
        if(testId.match('require_element_([0-9]+)')){
            var element_mapping_no = testId.split('_')[2];
            //check if $('#default #element_mapping_no_x') exist , turn elem color to blue
            if($('#default_element #element_mapping_no_'+element_mapping_no).length>0){
                $(elem).attr('class','blue');
            }else if($('#target #element_mapping_no_'+element_mapping_no).length>0){
                //else if $('#target #element_mapping_no_x') exist , turn elem color to blue
                $(elem).attr('class','blue');
            }else if($('#doc_element #element_mapping_no_'+element_mapping_no).length>0){
                //else if $('#target #element_mapping_no_x') exist , turn elem color to blue
                $(elem).attr('class','blue');
            }else{
                $(elem).attr('class','red');
            }
        } 
    });
    $.each( $( '#script_file_element' ).children(), function( idx, elem ) {
        var elemVal = $(elem).attr('value');
        $(elem).removeClass('blue');
        $(elem).addClass('red');
        //console.log(elemVal);
        $.each( $( '#file_docs input'), function( idx2, elem2 ) {
            var elem2Id = elem2.id;
            if(elem2Id.match('file_docs_([0-9]+)_element')){
                console.log(elem2);
                console.log($(elem2).val());
                if($(elem2).val() == elemVal)
                    $(elem).addClass('blue');
                return;
            }
        });
    });
}

function BindScriptChange(site_server,session_id){
    $('#script_selector').change(function(){
        ScriptChange(site_server,session_id);
    });
}

function BindBootBox(){
    $(document).on('click','.boot_box_button',function(){
        //先取此button是屬於哪個file_doc
        var buttonId = this.id;
        var file_doc_no = buttonId.split('_')[3];
        var html = '<div><label>選擇對應欄位</label></div>'+
                   '<div>'+
                        '<select id="script_element_no">';
        $('.script_file_element').each(function( idx, element) {
            html += '<option value="'+element.id.split('_')[3]+'">'+element.innerHTML+'</option>';
        });
                
        html += '<select>'+
                   '</div>';
        bootbox.dialog({
                message: html,
                    buttons: {
                            success:{
                                    label: "確定",
                                    className: "btn-small btn-success",
                                    callback: function() {
                                            $('#file_docs_'+file_doc_no+'_element').val($('#script_element_no').val());
                                            $('#file_docs_'+file_doc_no+'_element_name').html($('#script_element_no').find("option:selected").text());
                                            CheckElement();
                                    }
                            },
                            cancel:{
                                    label: "取消",
                                    className:"btn-small btn-danger",
                                    "callback": function() {
                                            //Example.show("uh oh, look out!");
                                    }        
                            }
                    }
            }) ;     
    });
}

function ScriptChange(site_server,session_id){
    var script_no ='';
    $('#script_selector').each(function( idx, selector) {
        script_no = selector.options[selector.selectedIndex].value;
    });
    
    $('#require_element_mapping').html('');
    $('#script_file_element').html('');
    
    $.each($('#file_docs .file_element_no'),function(idx, elem){
        $(elem).val('');
    });
    $.each($('#file_docs .file_element_name'),function(idx, elem){
        $(elem).html('');
    });
    
    $.ajax({
        url: 'http://'+site_server+'/api/api.CScriptElement.php?action=get_element&PHPSESSID='+session_id,
        type: 'post',
        data: {"text": script_no},
        dataType: 'json',
        success: function(json) {
            console.log(json);
            //require_nonfile_element
            var str = "";
            if(json.require){
                for(var i =0; i < json.require.length; i++) {
                    str +='&nbsp<span class="red" id="require_element_'+json['require'][i]['element_mapping_no']+'">'+json['require'][i]['element_mapping_name']+'</span>&nbsp';
                }
            }
            $('#require_element_mapping').html(str);
            CheckElement();
            
            //require_file_element
            str = "";
            if(json.file){
                for(var i =0; i < json.file.length; i++) {
                    str +='&nbsp<span class="red script_file_element" id="script_file_element_'+json['file'][i]['script_element_no']+'" value="'+json['file'][i]['script_element_no']+'">'+json['file'][i]['element_mapping_name']+'</span>&nbsp';
                }
            }
            $('#script_file_element').html(str);
            
        }
    });
}

