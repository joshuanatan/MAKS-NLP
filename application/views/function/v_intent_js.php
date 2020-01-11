<script>
function loadIntent(id_submit_entity_value){
    $("#id_entity_value").val(id_submit_entity_value);
    $.ajax({
        url:"<?php echo base_url();?>interface/intent/get_intent",
        type:"POST",
        dataType:"JSON",
        data:{id_entity_value:id_submit_entity_value},
        success:function(respond){
            $("#new_intent").val(respond);
        }
    });
}
</script>