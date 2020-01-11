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
    <table class = "table table-striped table-hover table-bordered" id = "table_driver" data-plugin = "dataTable">
        <thead>
            <th style = "width:5%">#</th>
            <th>User Registered</th>
            <th>Email Registered</th>
            <th>Application ID</th>
            <th>Application Name</th>
            <th>Token</th>
            <th>Last Modified</th>
            <th style = "width:15%">Action</th>
        </thead>
        <tbody>
            <?php for($a = 0; $a<count($acc); $a++):?>
            <tr>
                <td><?php echo $a+1;?></td>
                <td><?php echo $acc[$a]["registered_email"];?></td>
                <td><?php echo $acc[$a]["registered_name"];?></td>
                <td><?php echo $acc[$a]["application_id"];?></td>
                <td><?php echo $acc[$a]["application_name"];?></td>
                <td><?php echo $acc[$a]["server_access_token"];?></td>
                <td><?php echo $acc[$a]["date_wit_ai_acc_last_modified"];?></td>
                <td>
                    
                    <a href = "<?php echo base_url();?>setup/activate/<?php echo $acc[$a]["id_submit_wit_ai_acc"];?>"class = "btn btn-primary btn-sm col-lg-12">ACTIVATE ACCOUNT</a>
                </td>
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
    <a href = "<?php echo base_url();?>setup" class = "btn btn-primary btn-sm">BACK</a>
</div>