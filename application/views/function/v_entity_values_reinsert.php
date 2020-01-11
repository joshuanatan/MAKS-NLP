<div class="page-header">
    <h1 class="page-title">VALUES FOR ENTITY</h1>
    <br/>
    <ol class="breadcrumb breadcrumb-arrow">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Function</a></li>
        <li class="breadcrumb-item active">Entity</li>
    </ol>
</div>
<h4>Notes</h4>
<h5><i>Wit.ai</i> Uses Intent - Entity</h5> 
<p>There are 3 levels of <i>Entity Mapping</i> in <i>Wit.ai</i>. There are <i>Entity</i>,<i>Values</i>,<i>Expressions</i>
<br/><strong>Entity</strong>: What all these values about. Ex: Favourite City.
<br/><strong>Values</strong>: Examples of <i>Entity</i> . Ex: Jakarta, Surabaya.
<br/><strong>Expression</strong>: How to describe each examples. Ex: Jakarta = 'metropolitan city, capital city of Indonesia'.
<br/>
<p>Thus, Every phrase that matches 'metropolitan city' will be recognized as 'Jakarta' and 'Jakarta' will be recognized as 'Favourite City' therefore, The system understand that you are talking about your favourite city, Jakarta, the metropolitan city</p>
</p>
<br/>

<div class = "alert alert-danger alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo validation_errors();?>
</div>

<div class="page-body">
    <form action = "<?php echo base_url();?>function/entity/insert_entity_value" method = "POST">    
        <input type = "hidden" name = "id_entity" value = "<?php echo set_value("id_entity");?>">
        <div class = "form-group">
            <h5>Values</h5>
            <input type = "text" class = "form-control" value = "<?php echo set_value("entity_value");?>" name = "entity_value">
        </div>
        <div class = "form-group">
            <h5>Entity Expression <i>Separate every expression with comma </i><strong>[ , ]</strong></h5>
            <textarea class = "form-control" value = "<?php echo set_value("expression");?>" name = "expression"><?php echo set_value("expression");?></textarea>
        </div>
        <div class = "form-group">
            <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
            <a href = "<?php echo base_url();?>function/entity" class = "btn btn-primary btn-sm">BACK</a>
        </div>
    </form>
</div>