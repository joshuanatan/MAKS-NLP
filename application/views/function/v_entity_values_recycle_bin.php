<div class="page-header">
    <h1 class="page-title">VALUES FOR ENTITY <i><?php echo strtoupper($entity);?></i></h1>
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

<?php if($this->session->status_value == "success"):?>
<div class = "alert alert-success alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_value;?>
</div>
<?php elseif($this->session->status_value == "error"):?>
<div class = "alert alert-danger alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_value;?>
</div>
<?php endif;?>

<?php if($this->session->status_expression == "success"):?>
<div class = "alert alert-success alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_expression;?>
</div>
<?php elseif($this->session->status_expression == "error"):?>
<div class = "alert alert-danger alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_expression;?>
</div>
<?php endif;?>

<?php if($this->session->status_wit == "success"):?>
<div class = "alert alert-success alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_wit;?>
</div>
<?php elseif($this->session->status_wit == "error"):?>
<div class = "alert alert-danger alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_wit;?>
</div>
<?php endif;?>


<div class="page-body col-lg-10 offset-lg-1">
    <table class = "table table-striped table-hover table-bordered" id = "table_driver" data-plugin = "dataTable">
        <thead>
            <th style = "width:5%">#</th>
            <th>Value</th>
            <th>Expression</th>
            <th>Last Modified</th>
            <th style = "width:20%">Action</th>
        </thead>
        <tbody>
            <?php for($a = 0; $a<count($entity_values); $a++):?>
            <tr>
                <td><?php echo $a+1;?></td>
                <td><?php echo $entity_values[$a]["entity_value"];?></td>
                <td><button type = "button" class = "btn btn-primary btn-sm col-lg-12" data-toggle = "modal" data-target = "#expression" onclick = "loadExpression(<?php echo $entity_values[$a]['id_submit_entity_value'];?>)">EXPRESSION</button></td>
                <td><?php echo $entity_values[$a]["tgl_entity_value_last_modified"];?></td>
                
                <td>
                    <a href = "<?php echo base_url();?>function/entity/reupload_entity_value/<?php echo $entity_values[$a]["id_submit_entity_value"];?>" class = "btn btn-primary btn-sm col-lg-12">REUPLOAD VALUE</a>
                </td>
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
    <a href = "<?php echo base_url();?>function/entity/values/<?php echo $this->session->id_submit_entity;?>" class = "btn btn-primary btn-sm">BACK</a>
</div>

<div class = "modal fade" id = "expression">
    <div class = "modal-dialog modal-center">
        <div class = "modal-content">
            <div class = "modal-header">
                <h4>Intent Expression</h4>
            </div>
            <div class = "modal-body">
                <div class = "form-group">
                    <h5>Entity Expression</h5>
                    <textarea readonly class = "form-control" name = "expression" id = "expression_list"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>