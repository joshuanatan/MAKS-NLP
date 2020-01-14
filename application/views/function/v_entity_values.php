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


<div class="page-body col-lg-10 offset-lg-1 col-sm-12">
    <button type = "button" class = "btn btn-primary btn-sm" data-toggle = "modal" data-target = "#tambahEntity">+ ADD VALUES</button>
    <a href = "<?php echo base_url();?>function/entity/values_recycle_bin/<?php echo $this->session->id_submit_entity;?>" class = "btn btn-light btn-sm"><i class = "icon wb-trash"></i></a>
    <br/><br/>
    <table class = "table table-striped table-hover table-bordered" id = "table_driver" data-plugin = "dataTable">
        <thead>
            <th style = "width:5%">#</th>
            <th style = "width:10%">Value</th>
            <th style = "width:10%">Expression</th>
            <th style = "width:10%">Last Modified</th>
            <th style = "width:5%">Status Entity Value</th>
            <th style = "width:5%">Action</th>
        </thead>
        <tbody>
            <?php for($a = 0; $a<count($entity_values); $a++):?>
            <tr>
                <td><?php echo $a+1;?></td>
                <td><?php echo $entity_values[$a]["entity_value"];?></td>
                <td><button type = "button" class = "btn btn-primary btn-sm col-lg-12" data-toggle = "modal" data-target = "#expression" onclick = "loadExpression(<?php echo $entity_values[$a]['id_submit_entity_value'];?>)">EXPRESSION</button></td>
                <td><?php echo $entity_values[$a]["tgl_entity_value_last_modified"];?></td>
                <td>
                    <?php if($entity_values[$a]["status_aktif_entity_value"] == 1):?>
                    <button type = "button" class = "btn btn-primary btn-sm col-lg-12">UPLOADED</button>
                    <?php else:?>
                    <a href = "<?php echo base_url();?>function/entity/reupload_entity_value/<?php echo $entity_values[$a]["id_submit_entity_value"];?>" class = "btn btn-danger btn-sm col-lg-12">NOT UPLOADED</a>
                    <?php endif;?>
                </td>
                <td>
                    <?php if($entity_values[$a]["status_aktif_entity_value"] == 1):?>
                    <button type = "button" data-toggle = "modal" data-target = "#editExpression<?php echo $a;?>" class = "btn btn-primary btn-sm col-lg-12" onclick = "loadIntent(<?php echo $entity_values[$a]['id_submit_entity_value'];?>);loadExpression(<?php echo $entity_values[$a]['id_submit_entity_value'];?>)">EDIT VALUE</button>
                    <a href = "<?php echo base_url();?>function/entity/delete_entity_value/<?php echo $entity_values[$a]["id_submit_entity_value"];?>" class = "btn btn-danger btn-sm col-lg-12">DEACTIVE VALUE</a>
                    <a href = "<?php echo base_url();?>function/entity/remove_entity_value/<?php echo $entity_values[$a]["id_submit_entity_value"];?>" class = "btn btn-dark btn-sm col-lg-12">DELETE VALUE</a>
                    <?php else:?>
                    <a href = "<?php echo base_url();?>function/entity/reupload_entity_value/<?php echo $entity_values[$a]["id_submit_entity_value"];?>" class = "btn btn-primary btn-sm col-lg-12">REUPLOAD VALUE</a>
                    <?php endif;?>
                </td>
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
    <a href = "<?php echo base_url();?>function/entity" class = "btn btn-primary btn-sm">BACK</a>
</div>
<div class = "modal fade" id = "tambahEntity">
    <div class = "modal-dialog modal-center">
        <div class = "modal-content">
            <div class = "modal-header">
                <h4>Add Values</h4>
            </div>
            <div class = "modal-body" style = "max-height: calc(100vh - 210px);overflow-y: auto">
                <form action = "<?php echo base_url();?>function/entity/insert_entity_value" method = "POST">
                    <input type = "hidden" name = "id_entity" value = "<?php echo $id_submit_entity;?>">
                    <table class = "table table-stripped table-hover table-bordered">
                        <thead>
                            <th>Entity Value</th>
                            <th>Entity Expression</th>
                        </thead>
                        <tbody>
                        <?php for($a = 0; $a<12; $a++):?>
                        <tr>
                            <input type = "hidden" name = "add_values[]" value = "<?php echo $a;?>">
                            <td>
                                <input type = "text" class = "form-control" name = "entity_value<?php echo $a;?>">
                            </td>
                            <td>
                                <textarea class = "form-control" name = "expression<?php echo $a;?>"></textarea>
                            </td>
                        </tr>
                        <?php endfor;?>
                        </tbody>
                    </table>
                    <div class = "form-group">
                        <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php for($a = 0; $a<count($entity_values); $a++):?>
<div class = "modal fade" id = "editExpression<?php echo $a;?>">
    <div class = "modal-dialog modal-center">
        <div class = "modal-content">
            <div class = "modal-header">
                <h4>Value Expression</h4>
            </div>
            <div class = "modal-body">  
                <form action = "<?php echo base_url();?>function/entity/update_entity_value" method = "POST"> 
                    <input type = "hidden" id = "id_entity_value" name = "id_submit_entity_value" value = "<?php echo $entity_values[$a]["id_submit_entity_value"];?>">
                    <div class = "form-group">
                        <h5>Value</h5>
                        <input required type = "text" class = "form-control" name = "entity_value" id = "new_intent" value = "<?php echo $entity_values[$a]["entity_value"];?>">
                    </div>
                    <div class = "form-group">
                        <h5>Entity Expression <i>Separate every expression with comma </i><strong>[ , ]</strong></h5>
                        <textarea class = "form-control expression_list" name = "expression" id = "expression_textarea<?php echo $entity_values[$a]["id_submit_entity_value"];?>">Please wait, data is loading...</textarea>
                    </div>
                    <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endfor;?>
<div class = "modal fade" id = "expression">
    <div class = "modal-dialog modal-center">
        <div class = "modal-content">
            <div class = "modal-header">
                <h4>Entity Value Expression</h4>
            </div>
            <div class = "modal-body">
                <div class = "form-group">
                    <h5>Entity Value Expression</h5>
                    <textarea readonly class = "form-control expression_list" name = "expression" id = "expression_list">Please wait, data is loading...</textarea>
                </div>
            </div>
        </div>
    </div>
</div>