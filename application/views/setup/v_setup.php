    <div class="page-header">
    <h1 class="page-title">Wit.ai Configuration List</h1>
    <br/>
    <ol class="breadcrumb breadcrumb-arrow">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item active">Wit.ai Configuration</li>
    </ol>
</div>
<br/>
<?php if($this->session->status == "success"):?>
<div class = "alert alert-success alert-dismissible">
    <button type = "button" class = "close">&times;</button>
    <?php echo $this->session->msg;?>
</div>
<?php elseif($this->session->status == "error"):?>
<div class = "alert alert-danger alert-dismissible">
    <button type = "button" class = "close">&times;</button>
    <?php echo $this->session->msg;?>
</div>
<?php endif;?>
<div class="page-body">
    <button type = "button" class = "btn btn-primary btn-sm" data-toggle = "modal" data-target = "#addNewAccount">+ ADD NEW ACCOUNT</button>
    <a href = "<?php echo base_url();?>setup/recycle_bin" class = "btn btn-light btn-sm"><i class = "icon wb-trash"></i></a>
    <br/><br/>
    <table class = "table table-striped table-hover table-bordered" id = "table_driver" data-plugin = "dataTable" style = "table-layout:fixed">
        <thead>
            <th style = "width:5%">#</th>
            <th>Email Registered</th>
            <th>User Registered</th>
            <th>Application Name</th>
            <th>Token</th>
            <th>Last Modified</th>
            <th style = "width:15%">Status Account</th>
            <th style = "width:15%">Action</th>
        </thead>
        <tbody>
            <?php for($a = 0; $a<count($acc); $a++):?>
            <tr>
                <td style = "overflow-wrap:break-word"><?php echo $a+1;?></td>
                <td style = "overflow-wrap:break-word"><?php echo $acc[$a]["registered_email"];?></td>
                <td style = "overflow-wrap:break-word"><?php echo $acc[$a]["registered_name"];?></td>
                <td style = "overflow-wrap:break-word"><?php echo $acc[$a]["application_name"];?></td>
                <td style = "overflow-wrap:break-word"><?php echo $acc[$a]["server_access_token"];?></td>
                <td style = "overflow-wrap:break-word"><?php echo $acc[$a]["date_wit_ai_acc_last_modified"];?></td>
                <td style = "overflow-wrap:break-word">
                    <?php if($acc[$a]["status_aktif_wit_ai_acc"] ==0):?>
                    <button type = "button" class = "btn btn-danger btn-sm col-lg-12">NOT IN USE</button>
                    <?php else:?>
                    <button type = "button" class = "btn btn-primary btn-sm col-lg-12">IN USE</button>
                    <?php endif;?>
                </td>
                <td style = "overflow-wrap:break-word">
                    <?php if($acc[$a]["status_aktif_wit_ai_acc"] == 1):?>
                    <a href = "<?php echo base_url();?>setup/deactive/<?php echo $acc[$a]["id_submit_wit_ai_acc"];?>"class = "btn btn-danger btn-sm col-lg-12">DEACTIVE ACCOUNT</a>
                    <?php else:?>
                    <a href = "<?php echo base_url();?>setup/activate/<?php echo $acc[$a]["id_submit_wit_ai_acc"];?>"class = "btn btn-light btn-sm col-lg-12">ACTIVATE ACCOUNT</a>
                    <?php endif;?>
                    <a href = "<?php echo base_url();?>setup/delete/<?php echo $acc[$a]["id_submit_wit_ai_acc"];?>"class = "btn btn-dark btn-sm col-lg-12">DELETE ACCOUNT</a>
                    <button type = "button" class = "btn btn-primary btn-sm col-lg-12" data-toggle = "modal" data-target = "#editAccount<?php echo $acc[$a]["id_submit_wit_ai_acc"];?>">EDIT ACCOUNT</button>
                </td>
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
</div>
<div class = "modal fade" id = "addNewAccount">
    <div class = "modal-dialog modal-center">
        <div class = "modal-content">
            <div class = "modal-header">
                <h4>ADD Wit.ai ACCOUNT</h4>
            </div>
            <div class = "modal-body">
                <form action = "<?php echo base_url();?>setup/insert" method = "POST">
                    <div class = "form-group">
                        <h5>Registration Email</h5>
                        <input required type = "text" class = "form-control" name = "registered_email">
                    </div>
                    <div class = "form-group">
                        <h5>Registration Name</h5>
                        <input required type = "text" class = "form-control" name = "registered_name">
                    </div>
                    <div class = "form-group">
                        <h5>Application ID</h5>
                        <input required type = "text" class = "form-control" name = "application_id">
                    </div>
                    <div class = "form-group">
                        <h5>Application Name</h5>
                        <input required type = "text" class = "form-control" name = "application_name">
                    </div>
                    <div class = "form-group">
                        <h5>Server Access Token</h5>
                        <input required type = "text" class = "form-control" name = "server_access_token">
                    </div>
                    
                    <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php for($a = 0; $a<count($acc); $a++):?>
<div class = "modal fade" id = "editAccount<?php echo $acc[$a]["id_submit_wit_ai_acc"];?>">
    <div class = "modal-dialog modal-center">
        <div class = "modal-content">
            <div class = "modal-header">
                <h4>EDIT Wit.ai ACCOUNT</h4>
            </div>
            <div class = "modal-body">
                <form action = "<?php echo base_url();?>setup/update" method = "POST">
                <input type = "hidden" value = "<?php echo $acc[$a]["id_submit_wit_ai_acc"];?>" name = "id_submit_wit_ai_acc">
                    <div class = "form-group">
                        <h5>Registration Email</h5>
                        <input required type = "text" class = "form-control" name = "registered_email" value = "<?php echo $acc[$a]["registered_email"];?>">
                    </div>
                    <div class = "form-group">
                        <h5>Registration Name</h5>
                        <input required type = "text" class = "form-control" name = "registered_name" value = "<?php echo $acc[$a]["registered_name"];?>">
                    </div>
                    <div class = "form-group">
                        <h5>Application ID</h5>
                        <input required type = "text" class = "form-control" name = "application_id" value = "<?php echo $acc[$a]["application_id"];?>">
                    </div>
                    <div class = "form-group">
                        <h5>Application Name</h5>
                        <input required type = "text" class = "form-control" name = "application_name" value = "<?php echo $acc[$a]["application_name"];?>">
                    </div>
                    <div class = "form-group">
                        <h5>Server Access Token</h5>
                        <input required type = "text" class = "form-control" name = "server_access_token" value = "<?php echo $acc[$a]["server_access_token"];?>">
                    </div>
                    <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endfor;?>