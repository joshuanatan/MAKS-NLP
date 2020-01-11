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
<?php if($this->session->status_entity == "success"):?>
<div class = "alert alert-success alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_entity;?>
</div>
<?php elseif($this->session->status_entity == "error"):?>
<div class = "alert alert-danger alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_entity;?>
</div>
<?php endif;?>
<div class="page-body col-lg-10 offset-lg-1 col-sm-12">
    <button type = "button" class = "btn btn-primary btn-sm" data-toggle = "modal" data-target = "#tambahEntity">+ ADD INTENT</button>
    <a href = "<?php echo base_url();?>function/intent/recycle_bin" class = "btn btn-light btn-sm"><i class = "icon wb-trash"></i></a>
    <br/><br/>
    <table class = "table table-striped table-hover table-bordered" id = "table_driver" data-plugin = "dataTable">
        <thead>
            <th style = "width:5%">#</th>
            <th style = "width:10%">Intents</th>
            <th style = "width:10%">Last Modified</th>
            <th style = "width:5%">Status Intent</th>
            <th style = "width:5%">Action</th>
        </thead>
        <tbody>
            <?php for($a = 0; $a<count($intent); $a++):?>
            <tr>
                <td><?php echo $a+1;?></td>
                <td><?php echo $intent[$a]["intent"];?></td>
                <td><?php echo $intent[$a]["tgl_intent_last_modified"];?></td>
                <td>
                    <?php if($intent[$a]["status_aktif_intent"] == 1):?>
                    <button type = "button" class = "btn btn-primary btn-sm col-lg-12">UPLOADED</button>
                    <?php else:?>
                    <a href = "<?php echo base_url();?>function/intent/reupload_intent/<?php echo $intent[$a]["id_submit_intent"];?>" class = "btn btn-danger btn-sm col-lg-12">NOT UPLOADED</a>
                    <?php endif;?>
                </td>
                <td>
                    <?php if($intent[$a]["status_aktif_intent"] == 1):?>
                    <button type = "button" data-toggle = "modal" data-target = "#editExpression" class = "btn btn-primary btn-sm col-lg-12" onclick = "loadIntent(<?php echo $intent[$a]['id_submit_intent'];?>);loadExpression(<?php echo $intent[$a]['id_submit_intent'];?>)">EDIT INTENT</button>
                    <a href = "<?php echo base_url();?>function/intent/delete_intent/<?php echo $intent[$a]["id_submit_intent"];?>" class = "btn btn-danger btn-sm col-lg-12">DEACTIVE INTENT</a>
                    <?php else:?>
                    <a href = "<?php echo base_url();?>function/intent/reupload_intent/<?php echo $intent[$a]["id_submit_intent"];?>" class = "btn btn-primary btn-sm col-lg-12">REUPLOAD INTENT</a>
                    <?php endif;?>
                    <a href = "<?php echo base_url();?>function/intent/remove_intent/<?php echo $intent[$a]["id_submit_intent"];?>" class = "btn btn-dark btn-sm col-lg-12">REMOVE INTENT</a>
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
                <h4>Add Intent</h4>
            </div>
            <div class = "modal-body">
                <form action = "<?php echo base_url();?>function/intent/insert_intent" method = "POST">
                    <table class = "table table-stripped table-hover table-stripped table-bordered">
                        <thead>
                            <th>Intent</th>
                        </thead>
                        <tbody>
                        <?php for($a = 0; $a<10; $a++):?>
                        <tr>
                            <input type = "hidden" name = "add_check[]" value = "<?php echo $a;?>">
                            <td><input type = "text" class = "form-control" name = "intent<?php echo $a;?>"></td>
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
<?php for($a = 0; $a<count($intent); $a++):?>
<div class = "modal fade" id = "editExpression">
    <div class = "modal-dialog modal-center">
        <div class = "modal-content">
            <div class = "modal-header">
                <h4>Edit Intent</h4>
            </div>
            <div class = "modal-body">  
                <form action = "<?php echo base_url();?>function/intent/update_intent" method = "POST"> 
                    <input type = "hidden" id = "id_intent" name = "id_submit_intent" value = "<?php echo $intent[$a]["id_submit_intent"];?>">
                    <div class = "form-group">
                        <h5>Intent</h5>
                        <input required type = "text" class = "form-control" name = "intent" value = "<?php echo $intent[$a]["intent"];?>">
                    </div>
                    <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endfor;?>