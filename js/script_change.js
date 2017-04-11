function BindScriptChange(site_server,session_id,default_title_value){
    $('#script_selector').change(function(){
        console.log('a script selected');
        ScriptChange(site_server,session_id,default_title_value);
    });
}

function ScriptChange(site_server,session_id,default_title_value){
    $('#script_selector').each(function( idx, selector) {
        script_no = selector.options[selector.selectedIndex].value;
    });
    //console.log(script_no);
    GetScriptElement(site_server,session_id,script_no,default_title_value);
}
function GetScriptElement(site_server,session_id,script_no,default_title_value){
        $('#default_element').html('');
    //$('#default_element').html('');
    $.ajax({
        url:'http://'+site_server+'/api/api.CScriptElement.php?action=get_element&PHPSESSID='+session_id,
        type: 'post',
        cache:'false',
        data: {"text": script_no},
        dataType: 'json',
        success: function(json) {
            $('#default_element .from_script').remove();
            var str = "";
            var fields = "";

            for(var i =0; i < json.require.length; i++) {
                var tagType =json.require[i]['element_mapping_tag_type'];
                var no = json.require[i]['element_mapping_no'];
                var name = json.require[i]['element_mapping_name'];
                //var value = json.require[i]['element_mapping_value'];

                if(no==3)   //element_mapping_no of title
                    var value = default_title_value;
                else
                    var value = '';

                var status =  json.require[i]['element_status'];
                
                if($('#default_element [name="element_mapping_no_'+no+'"]').length !=0){
                    console.log('element_mapping_no_' + no + ':exist');
                    continue;
                }else{
                    console.log('element_mapping_no_' + no + ':not exist');
                }

                str +='&nbsp<span class="red" id="require_element_'+no+'">'+name+'</span>&nbsp';
                
                if(tagType=='text'||tagType=='password'||tagType=='other'){
                    fields  +=' <div class="control-group from_script"  title="'+name+'">';
                    fields +='<!-- Text input-->';
                    fields +=' <label class="control-label" for="element_mapping_no_'+no+'" >'+name+'</label>';
                    fields +='<div class="controls">';
                    fields +='<input class="span9" type="text" name="element_mapping_no_'+no+'" id="element_mapping_no_'+no+'" class=" span2" value="'+value+'">';
                    fields +='<input type="checkbox" name="fields_status_'+no+'" id="fields_status_'+no+'" class="ace-switch ace-switch-6 default-field-status" value="'+status+'"';
                    if(status)
                        fields+=' checked />';
                    fields +='<span class="lbl"></span></div></div> ';
                }
                else if(tagType=='textarea'){
                    fields +=' <div class="control-group from_script"  title="'+name+'">';
                    fields +='<!-- TextArea -->';
                    fields +=' <label class="control-label" for="element_mapping_no_'+no+'" >'+name+'</label>';
                    fields +=' <div class="controls">';
                    fields +='<textarea class="span9" type="text" name="element_mapping_no_'+no+'" id="element_mapping_no_'+no+'" rows="5" style="height:100px">'+value+'</textarea>';
                    fields +='<input type="checkbox" name="fields_status_'+no+'" id="fields_status_'+no+'" class="ace-switch ace-switch-6 default-field-status" value="'+status+'"';
                    if(status)
                        fields+=' checked />';
                    fields +='<span class="lbl" style="vertical-align:top;"></span></div></div>';
                }
                else if(tagType=='select'){
                    fields +=' <div class="control-group from_script"  title="'+name+'">';
                    fields +='<!-- Select Basic -->';
                    fields +=' <label class="control-label" for="element_mapping_no_'+no+'" >'+name+'</label>';
                    fields +=' <div class="controls">';
                    fields +=' <select class=" span9 " name="element_mapping_no_'+no+'" id="element_mapping_no_'+no+'">';
                    //fields +='<{foreach item=oOption name=options from=$oEleMap->aOption}>';
                    var options =json.require[i]['option'];
                    for(key in options){
                        fields +='<option value="'+options[key]['element_mapping_option_no']+'"';
                        if(value==options[key]['element_mapping_option_no']){
                            fields+=' selected ';    
                        }
                        fields +='>'+options[key]['sName']+'</option>';
                    }
                    fields +='</select>';
                    fields +='<input type="checkbox" name="fields_status_'+no+'" id="fields_status_'+no+'" class="ace-switch ace-switch-6 default-field-status" value="'+status+'"';
                    if(status)
                        fields+=' checked />';
                    fields +='<span class="lbl"></span></div></div>';
                }
                //checkbox is missing
                else if(tagType=='radio'){
                    fields +=' <div class="control-group from_script"  title="'+name+'">';
                    fields +=' <label class="control-label" for="element_mapping_no_'+no+'" >'+name+'</label>';
                    fields +='<!-- Multiple Radios -->';
                    fields +=' <div class="controls" id="element_mapping_no_'+no+'">';
                    var options =json.require[i]['option'];
                    for(key in options){
                        fields +='<label >';
                        fields +='<input class="ace" type="radio" value="'+key+'" name="element_mapping_no_'+no+'" id="element_mapping_no_'+options[key]['element_mapping_option_name']+'"';
                        if(status){ fields +='checked'; }
                        fields +='>';
                        fields +='<span class="lbl">'+options[key]['element_mapping_option_name']+'</span></label>';
                    }                        
                    fields +='<input type="checkbox" name="fields_status_'+no+'" id="fields_status_'+no+'" class="ace-switch ace-switch-6 default-field-status" value="'+status+'"';
                    if(status)
                        fields+=' checked />';
                    fields +='<span class="lbl"></span></div></div>';
                }
            }
            //$('#require_element_mapping').html(str);
            $('#default_element').append(fields);
            $('textarea').autosize();
            //CheckElement();
        }
    });
}