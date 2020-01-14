<div class="page-header">
    <h1 class="page-title">SAMPLES Wit.ai RESPOSITORY</h1>
    <br/>
    <ol class="breadcrumb breadcrumb-arrow">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Function</a></li>
        <li class="breadcrumb-item active">Samples</li>
    </ol>
</div>
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

<?php if($this->session->status_samples == "success"):?>
<div class = "alert alert-success alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_samples;?>
</div>
<?php elseif($this->session->status_samples == "error"):?>
<div class = "alert alert-danger alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo $this->session->msg_samples;?>
</div>
<?php endif;?>
<div class="page-body">
    <table class = "table table-striped table-hover table-bordered" id = "table_driver" data-plugin = "dataTable">
        <thead>
            <th style = "width:5%">#</th>
            <th>Samples</th>
            <th>Intent</th>
            <th>Last Modified</th>
            <th style = "width:15%">Entity</th>
            <th style = "width:15%">Action</th>
        </thead>
        <tbody>
            <?php for($a = 0; $a<count($samples); $a++):?>
            <tr>
                <td><?php echo $a+1;?></td>
                <td id = "samples<?php echo $samples[$a]['id_submit_samples'];?>"><?php echo $samples[$a]["samples"];?></td>
                <td><?php echo $samples[$a]["intent"];?></td>
                <td><?php echo $samples[$a]["tgl_samples_last_modified"];?></td>
                <td><button data-toggle = "modal" data-target = "#entityDetail" onclick = "getSamplesEntity(<?php echo $samples[$a]['id_submit_samples'];?>)" type = 'button' class = 'btn btn-primary btn-sm col-lg-12'>ENTITY LIST</button></td>
                <td>
                    <a href = "<?php echo base_url();?>function/samples/reupload/<?php echo $samples[$a]["id_submit_samples"];?>" class = "btn btn-primary btn-sm col-lg-12">REUPLOAD SAMPLES</a>
                </td>
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
    <a href = "<?php echo base_url();?>function/samples" class = "btn btn-primary btn-sm">BACK</a>
</div>
<div class = "modal fade" id = "entityDetail">
    <div class = "modal-dialog modal-center modal-lg">
        <div class = "modal-content">
            <div class = "modal-header">
                <h4>Sample's Entity Detail</h4>
            </div>
            <div class = "modal-body">
                <table class = "table table-striped table-hover table-bordered">
                    <thead>
                        <th>#</th>
                        <th>Entity</th>
                        <th>Value</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Text</th>
                    </thead>
                    <tbody id = "sample_entity_detail_container">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
