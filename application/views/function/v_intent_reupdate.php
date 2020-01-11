<div class="page-header">
    <h1 class="page-title">INTENT Wit.ai RESPOSITORY</h1>
    <br/>
    <ol class="breadcrumb breadcrumb-arrow">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Function</a></li>
        <li class="breadcrumb-item active">Intent</li>
    </ol>
</div>
<h4>Notes</h4>
<p><i>Intent</i>: Intent means <strong>intention</strong>. Every spoken sentence has intention to know the the user's needs</p>
<br/>

<div class = "alert alert-danger alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo validation_errors();?>
</div>
<div class="page-body">
    <form action = "<?php echo base_url();?>function/intent/update_entity_value" method = "POST">    
        <input type = "hidden" name = "id_submit_entity_value" value = "<?php echo set_value("id_submit_entity_value");?>">
        <div class = "form-group">
            <h5>Intention</h5>
            <input type = "text" class = "form-control" name = "entity_value" value = "<?php echo set_value("entity_value");?>">
        </div>
        <div class = "form-group">
            <h5>Entity Expression <i>Separate every expression with comma </i><strong>[ , ]</strong></h5>
            <textarea class = "form-control" name = "expression"><?php echo set_value("expression");?></textarea>
        </div>
        <div class = "form-group">
            <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
            <a href = "<?php echo base_url();?>function/intent" class = "btn btn-primary btn-sm">BACK</a>
        </div>
    </form>
</div>