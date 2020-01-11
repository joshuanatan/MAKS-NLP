<script>
function loadIntent(id_submit_entity_value){
    $("#id_entity_value").val(id_submit_entity_value);
    $.ajax({
        url:"<?php echo base_url();?>interface/entity/get_entity_value",
        type:"POST",
        dataType:"JSON",
        data:{id_entity_value:id_submit_entity_value},
        success:function(respond){
            $("#new_intent").val(respond);
        }
    });
}
function loadExpression(id_submit_entity_value){
    $("#id_entity_value").val(id_submit_entity_value);
    $.ajax({
        url:"<?php echo base_url();?>interface/entity/get_expression",
        type:"POST",
        dataType:"JSON",
        data:{id_entity_value:id_submit_entity_value},
        success:function(respond){
            $("#expression_textarea").val(respond);
            $("#expression_list").val(respond);
        }
    });
}
</script>