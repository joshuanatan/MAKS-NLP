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
    <table class = "table table-striped table-hover table-bordered" id = "table_driver" data-plugin = "dataTable">
        <thead>
            <th style = "width:5%">#</th>
            <th style = "width:10%">Intents</th>
            <th style = "width:10%">Date Entity Add</th>
            <th style = "width:5%">Status Entity</th>
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
                    <button type = "button" class = "btn btn-primary btn-sm col-lg-12">REGISTERED</button>
                    <?php else:?>
                    <a href = "<?php echo base_url();?>function/intent/reupload_intent/<?php echo $intent[$a]["id_submit_intent"];?>" class = "btn btn-danger btn-sm col-lg-12">NOT REGISTERED</a>
                    <?php endif;?>
                </td>
                <td>
                    <?php if($intent[$a]["status_aktif_intent"] == 1):?>
                    <button type = "button" data-toggle = "modal" data-target = "#editExpression" class = "btn btn-primary btn-sm col-lg-12" onclick = "loadIntent(<?php echo $intent[$a]['id_submit_intent'];?>);loadExpression(<?php echo $intent[$a]['id_submit_intent'];?>)">EDIT INTENT</button>
                    <a href = "<?php echo base_url();?>function/intent/delete_intent/<?php echo $intent[$a]["id_submit_intent"];?>" class = "btn btn-danger btn-sm col-lg-12">REMOVE INTENT</a>
                    <?php else:?>
                    <a href = "<?php echo base_url();?>function/intent/reupload_intent/<?php echo $intent[$a]["id_submit_intent"];?>" class = "btn btn-light btn-sm col-lg-12">REUPLOAD INTENT</a>
                    <?php endif;?>
                </td>
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
    <a href = "<?php echo base_url();?>function/intent" class = "btn btn-primary btn-sm">BACK</a>
</div>