<div class="page-header">
    <h1 class="page-title">ENTITY Wit.ai RESPOSITORY</h1>
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
<?php if($this->session->status_entity == "success"):?>
<div class = "alert alert-success">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_entity;?>
</div>
<?php elseif($this->session->status_entity == "error"):?>
<div class = "alert alert-danger">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_entity;?>
</div>
<?php endif;?>

<?php if($this->session->status_wit == "success"):?>
<div class = "alert alert-success">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_wit;?>
</div>
<?php elseif($this->session->status_wit == "error"):?>
<div class = "alert alert-danger">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_wit;?>
</div>
<?php endif;?>
<div class="page-body col-lg-10 offset-lg-1">
    <table class = "table table-striped table-hover table-bordered" id = "table_driver" data-plugin = "dataTable">
        <thead>
            <th style = "width:5%">#</th>
            <th style = "width:10%">Entity</th>
            <th style = "width:10%">Description</th>
            <th style = "width:5%">Status Entity</th>
            <th style = "width:5%">Action</th>
        </thead>
        <tbody>
            <?php for($a = 0; $a<count($entity); $a++):?>
            <tr>
                <td><?php echo $a+1;?></td>
                <td><?php echo $entity[$a]["entity_name"];?></td>
                <td><?php echo $entity[$a]["entity_desc"];?></td>
                <td>
                    <a href = "<?php echo base_url();?>function/entity/reupload_entity/<?php echo $entity[$a]["id_submit_entity"];?>" class = "btn btn-danger btn-sm col-lg-12">NOT REGISTERED</a>
                </td>
                <td>
                    <a href = "<?php echo base_url();?>function/entity/reupload_entity/<?php echo $entity[$a]["id_submit_entity"];?>" class = "btn btn-primary btn-sm col-lg-12">ACTIVATE</a>
                </td>
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
    <a href = "<?php echo base_url();?>function/entity" class = "btn btn-primary btn-sm">BACK</a>
</div>