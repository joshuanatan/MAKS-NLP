
<script>
function getSelectedText(){
    var entity_option = "<option>Choose Entity</option>";
    <?php for($a = 0; $a<count($entity_list); $a++):?>
    entity_option += "<option value = '<?php echo $entity_list[$a]["entity_name"];?>'><?php echo $entity_list[$a]["entity_name"];?></option>";
    <?php endfor;?>

    var text = "";
    if (window.getSelection) {
        text = window.getSelection().toString();
    } 
    else if (document.selection && document.selection.type != "Control") {
        text = document.selection.createRange().text;
    }
    if(text !== ""){
        var startIndex = document.getElementById("sample_sentence").selectionStart;   
        var endIndex = document.getElementById("sample_sentence").selectionEnd;  
        var row = $(".sample_editor_row").length;
        html = "<tr class = 'sample_editor_row'><td>"+(row+1)+"</td><td><div class = 'checkbox-custom checkbox-primary'><input type = 'checkbox' checked name = 'new[]' value = '"+(row+1)+"'><label></label></div></td><td></td><td><td><input type = 'number' class = 'form-control' name = 'start_index"+(row+1)+"' value = '"+startIndex+"'></td><td><input type = 'text' class = 'form-control' name = 'end_index"+(row+1)+"' value = '"+endIndex+"'></td><td>"+text+"</td><td><select data-plugin = 'select2' name = '' style = 'width:100%' onchange = 'loadEntityValue("+row+")' id = 'entityOption"+(row)+"' class = 'form-control'>"+entity_option+"</select><hr/><select data-plugin = 'select2' style = 'width:100%' id = 'entityValue"+row+"' name = 'entityValue"+(row+1)+"' class = 'form-control'><option></option></select></td></tr>"; 
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