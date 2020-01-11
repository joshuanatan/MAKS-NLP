<script>
function getSelectedText(){
    var entity_option = "<option>Choose Entity</option>";
    <?php for($a = 0; $a<count($entity); $a++):?>
    entity_option += "<option value = '<?php echo $entity[$a]["entity_name"];?>'><?php echo $entity[$a]["entity_name"];?></option>";
    <?php endfor;?>
    var text = "";
    if (window.getSelection) {
        text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != "Control") {
        text = document.selection.createRange().text;
    }
    if(text !== ""){
        var startIndex = document.getElementById("sample_sentence").selectionStart;   
        var endIndex = document.getElementById("sample_sentence").selectionEnd;  
        var row = $(".sample_editor_row").length;
        html = "<tr class = 'sample_editor_row'><td><div class = 'checkbox-custom checkbox-primary'><input checked type ='checkbox' name = 'analytics_check[]' value = '"+row+"'><label></label></div></td><td><input id = 'startIndex"+row+"' name = 'startIndex"+row+"' value = '"+startIndex+"' type = 'text' class = 'form-control'></td><td><input id = 'endIndex"+row+"' name = 'endIndex"+row+"' value = '"+endIndex+"' type = 'text' class = 'form-control'></td><td><input id = 'selectedText"+row+"' value = '"+text+"' type = 'text' class = 'form-control' readonly></td><td><select name = 'entity_name"+row+"' style = 'width:100%' onchange = 'loadEntityValue("+row+")' id = 'entityOption"+row+"' class = 'form-control'>"+entity_option+"</select><hr/><select style = 'width:100%' id = 'entityValue"+row+"' name = 'entityValue"+row+"' class = 'form-control'></select></td><td><button type = 'button' class = 'btn btn-primary btn-sm col-lg-12' onclick = 'showHighlightedText("+row+")'>SHOW SELECTED TEXT</button></td></tr>"; 
        $("#entityList").append(html);
        $("#entityOption"+row).select2();
        $("#entityValue"+row).select2();
        if (window.getSelection) {
            if (window.getSelection().empty) {  // Chrome
                window.getSelection().empty();
            } 
            else if (window.getSelection().removeAllRanges) {  // Firefox
                window.getSelection().removeAllRanges();
            }
        } 
        else if (document.selection) {  // IE?
            document.selection.empty();
        }
    }
}
</script>
<script>
function loadEntityValue(row){
    var id_entity = $("#entityOption"+row).val();
    $.ajax({
        url:"<?php echo base_url();?>interface/entity/load_values",
        type:"POST",
        dataType:"JSON",
        data:{id_entity:id_entity},
        success:function(respond){
            var html = "<option>Choose Value</option>";
            for(var a = 0; a<respond.length; a++){
                html += "<option value = '"+respond[a]["entity_value"]+"'>"+respond[a]["entity_value"]+"</option>";
            }
            $("#entityValue"+row).html(html);
        }
    });
}
</script>
<script>
function showHighlightedText(row){
    var start = $("#startIndex"+row).val();
    var end = $("#endIndex"+row).val(); 
    document.getElementById("sample_sentence").focus();
    document.getElementById("sample_sentence").setSelectionRange(start,end);
}
</script>
<script>
function getSamplesEntity(id_submit_samples){
    var text = $("#samples"+id_submit_samples).html();
    
    $.ajax({
        url:"<?php echo base_url();?>interface/samples/get_samples_entity",
        data:{id_samples:id_submit_samples},
        dataType:"JSON",
        type:"POST",
        success:function(respond){
            var html = "";
            for(var a = 0; a<respond.length; a++){
                html += "<tr><td>"+(a+1)+"</td><td>"+respond[a]["entity_value"]+"</td><td>"+respond[a]["entity_name"]+"</td><td>"+respond[a]["start_index"]+"</td><td>"+respond[a]["end_index"]+"</td><td>"+text.substr(respond[a]["start_index"],(respond[a]["end_index"]-respond[a]["start_index"]))+"</td></tr>";
            }
            $("#sample_entity_detail_container").html(html);
        }
    });
}
</script>