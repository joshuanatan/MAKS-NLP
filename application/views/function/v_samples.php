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
<h5>For better experience, do samples training directly to <a target = "_blank" href = "https://wit.ai">Wit.ai</a></h5>
<h5>WARNING. Please mark the keyword carefully. </h5>
<div class="page-body">
    <button type = "button" class = "btn btn-primary btn-sm" data-toggle = "modal" data-target = "#tambahSamples">+ ADD SAMPLES</button>
    <a href = "<?php echo base_url();?>function/samples/recycle_bin" class = "btn btn-light btn-sm"><i class = "icon wb-trash"></i></a>
    <br/><br/>
    <table class = "table table-striped table-hover table-bordered" id = "table_driver" data-plugin = "dataTable">
        <thead>
            <th style = "width:5%">#</th>
            <th style = "width:10%">Samples</th>
            <th style = "width:10%">Intent</th>
            <th style = "width:10%">Entity</th>
            <th style = "width:10%">Last Modified</th>
            <th style = "width:10%">Status Samples</th>
            <th style = "width:5%">Action</th>
        </thead>
        <tbody>
            <?php for($a = 0; $a<count($samples); $a++):?>
            <tr>
                <td><?php echo $a+1;?></td>
                <td id = "samples<?php echo $samples[$a]['id_submit_samples'];?>"><?php echo $samples[$a]["samples"];?></td>
                <td><?php echo $samples[$a]["intent"];?></td>
                <td><button data-toggle = "modal" data-target = "#entityDetail" onclick = "getSamplesEntity(<?php echo $samples[$a]['id_submit_samples'];?>)" type = 'button' class = 'btn btn-primary btn-sm col-lg-12'>ENTITY LIST</button></td>
                <td><?php echo $samples[$a]["tgl_samples_last_modified"];?></td>
                <td>
                    <?php if($samples[$a]["status_aktif_samples"] == 1):?>
                    <button type = "button" class = "btn btn-primary btn-sm col-lg-12">UPLOADED</button>
                    <?php else:?>
                    <button type = "button" class = "btn btn-danger btn-sm col-lg-12">NOT UPLOADED</button>
                    <?php endif;?>
                </td>
                <td>
                    <?php if($samples[$a]["status_aktif_samples"] == 1):?>
                    <a href = "<?php echo base_url();?>function/samples/delete/<?php echo $samples[$a]["id_submit_samples"];?>" class = "btn btn-danger btn-sm col-lg-12">REMOVE SAMPLES</a>
                    <?php else:?>
                    <a href = "<?php echo base_url();?>function/samples/reupload/<?php echo $samples[$a]["id_submit_samples"];?>" class = "btn btn-primary btn-sm col-lg-12">REUPLOAD SAMPLES</a>
                    <?php endif;?>
                    <?php if(false):?>
                    <a href = "<?php echo base_url();?>function/samples/edit/<?php echo $samples[$a]["id_submit_samples"];?>" class = "btn btn-primary btn-sm col-lg-12">EDIT SAMPLES</button>
                    <?php endif;?>
                    <a href = "<?php echo base_url();?>function/samples/remove/<?php echo $samples[$a]["id_submit_samples"];?>" class = "btn btn-dark btn-sm col-lg-12">DELETE SAMPLES</a>
                </td>
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
</div>
<div class = "modal fade" id = "tambahSamples">
    <div class = "modal-dialog modal-center modal-lg">
        <div class = "modal-content">
            <div class = "modal-header">
                <h4>Add Samples</h4>
            </div>
            <div class = "modal-body" style = "max-height: calc(100vh - 210px);overflow-y: auto;">
                <form action = "<?php echo base_url();?>function/samples/insert" method = "POST">
                    <div class = "form-group">
                        <h5>Text</h5>
                        <input required onmouseup = "getSelectedText()" id = "sample_sentence" type = "text" class = "form-control" name = "sample_sentence">
                    </div>
                    <div class = "form-group">
                        <h5>Intent</h5>
                        <select class = "form-control" data-plugin = "select2" name = "intent">
                            <?php for($a = 0; $a<count($intent); $a++):?>
                            <option value = "<?php echo $intent[$a]["intent"];?>"><?php echo $intent[$a]["intent"];?></option>
                            <?php endfor;?>
                        </select>
                    </div>
                    <div class = "form-group">
                        <h5>Entities</h5>
                        <table class = "table table-bordered">
                            <thead>
                                <th style = "width:5%">#</th>
                                <th style = "width:10%">Start</th>
                                <th style = "width:10%">End</th>
                                <th>Text</th>
                                <th>Entity</th>
                                <th>Action</th>
                            </thead>
                            <tbody id = "entityList">
                                
                            </tbody>
                        </table>
                    </div>
                    <div class = "form-group">
                        <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
