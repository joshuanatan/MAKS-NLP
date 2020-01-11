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
<div class="page-body col-lg-10 offset-lg-1 col-sm-12">
    <button type = "button" class = "btn btn-primary btn-sm" data-toggle = "modal" data-target = "#tambahEntity">+ ADD ENTITY</button>
    <a href = "<?php echo base_url();?>function/entity/recycle_bin" class = "btn btn-light btn-sm"><i class = "icon wb-trash"></i></a>
    <br/><br/>
    <table class = "table table-striped table-hover table-bordered" id = "table_driver" data-plugin = "dataTable" style = "table-layout:fixed">
        <thead>
            <th style = "width:5%">#</th>
            <th>Entity</th>
            <th>Description</th>
            <th>Date Entity Add</th>
            <th style = "width:15%">Status Entity</th>
            <th style = "width:15%">Action</th>
        </thead>
        <tbody>
            <?php for($a = 0; $a<count($entity); $a++):?>
            <tr>
                <td style = "overflow-wrap:break-word"><?php echo $a+1;?></td>
                <td style = "overflow-wrap:break-word"><?php echo $entity[$a]["entity_name"];?></td>
                <td style = "overflow-wrap:break-word"><?php echo $entity[$a]["entity_desc"];?></td>
                <td style = "overflow-wrap:break-word"><?php echo $entity[$a]["tgl_entity_last_modified"];?></td>
                <td style = "overflow-wrap:break-word">
                    <?php if($entity[$a]["status_aktif_entity"] == 1):?>
                    <button type = "button" class = "btn btn-primary btn-sm col-lg-12">UPLOADED</button>
                    <?php else:?>
                    <a href = "<?php echo base_url();?>function/entity/reupload_entity/<?php echo $entity[$a]["id_submit_entity"];?>" class = "btn btn-danger btn-sm col-lg-12">NOT UPLOADED</a>
                    <?php endif;?>
                </td>
                <td style = "overflow-wrap:break-word">
                    <?php if($entity[$a]["status_aktif_entity"] == 1):?>
                    <button type = "button" data-toggle = "modal" data-target = "#editEntity<?php echo $a;?>" class = "btn btn-primary btn-sm col-lg-12">EDIT ENTITY</button>
                    <a href = "<?php echo base_url();?>function/entity/delete_entity/<?php echo $entity[$a]["id_submit_entity"];?>" class = "btn btn-danger btn-sm col-lg-12">DEACTIVE ENTITY</a>
                    <a href = "<?php echo base_url();?>function/entity/values/<?php echo $entity[$a]["id_submit_entity"];?>" class = "btn btn-primary btn-sm col-lg-12">MANAGE VALUES</a>
                    <?php elseif($entity[$a]["status_aktif_entity"] == 0):?>
                    <a href = "<?php echo base_url();?>function/entity/reupload_entity/<?php echo $entity[$a]["id_submit_entity"];?>" class = "btn btn-light btn-sm col-lg-12">ACTIVATE ENTITY</a>
                    <?php endif;?>
                    <a href = "<?php echo base_url();?>function/entity/remove_entity/<?php echo $entity[$a]["id_submit_entity"];?>" class = "btn btn-dark btn-sm col-lg-12">DELETE ENTITY</a>
                </td>
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
</div>
<div class = "modal fade" id = "tambahEntity">
    <div class = "modal-dialog modal-center">
        <div class = "modal-content">
            <div class = "modal-header">
                <h4>Add Entity</h4>
            </div>
            <div class = "modal-body" style = "max-height: calc(100vh - 210px);overflow-y: auto">
                <form action = "<?php echo base_url();?>function/entity/insert_entity" method = "POST">
                    <table class = "table table-stripped table-bordered table-hover">
                        <thead>
                            <th>Entity Name</th>
                            <th>Entity Description</th>
                        </thead>
                        <tbody>
                        <?php for($a = 0; $a<10; $a++):?>
                            <tr>
                                <input type = "hidden" name = "add_check[]" value = "<?php echo $a;?>">
                                <td>
                                    <input type = "text" class = "form-control" name = "entity_name<?php echo $a;?>">
                                </td>
                                <td>
                                    <textarea class = "form-control" name = "entity_desc<?php echo $a;?>"></textarea>
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
<?php for($a = 0; $a<count($entity); $a++):?>
<div class = "modal fade" id = "editEntity<?php echo $a;?>">
    <div class = "modal-dialog modal-center">
        <div class = "modal-content">
            <div class = "modal-header">
                <h4>Edit Entity</h4>
            </div>
            <div class = "modal-body">
                <form action = "<?php echo base_url();?>function/entity/update_entity" method = "POST">
                    <input type = "hidden" name = "id_submit_entity" value = "<?php echo $entity[$a]["id_submit_entity"];?>">
                    <div class = "form-group">
                        <h5>Entity Name</h5>
                        <input required type = "text" class = "form-control" name = "entity_name" value = "<?php echo $entity[$a]["entity_name"];?>">
                    </div>
                    <div class = "form-group">
                        <h5>Entity Description</h5>
                        <textarea class = "form-control" name = "entity_desc"><?php echo $entity[$a]["entity_desc"];?></textarea>
                    </div>
                    <div class = "form-group">
                        <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endfor;?>