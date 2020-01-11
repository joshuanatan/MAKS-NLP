<div class="page-header">
    <h1 class="page-title">SETUP QUERY BUILDER ADAPTER CONNECTION</h1>
    <br/>
    <ol class="breadcrumb breadcrumb-arrow">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item active">Database Connection</li>
    </ol>
</div>
<div class = "alert alert-danger">
    <button type = "button" class = "close">&times;</button>
<?php echo validation_errors(); ?>
</div>
<br/>
<div class="page-body">
    <form action = "<?php echo base_url();?>setup/insert" method = "POST">
        <div class = "form-group">
            <h5>Registration Email</h5>
            <input type = "text" class = "form-control" value = "<?php echo set_value("registered_email");?>" name = "registered_email">
        </div>
        <div class = "form-group">
            <h5>Registration Name</h5>
            <input type = "text" class = "form-control" value = "<?php echo set_value("registered_name");?>" name = "registered_name">
        </div>
        <div class = "form-group">
            <h5>Application ID</h5>
            <input type = "text" class = "form-control" value = "<?php echo set_value("application_id");?>" name = "application_id">
        </div>
        <div class = "form-group">
            <h5>Application Name</h5>
            <input type = "text" class = "form-control" value = "<?php echo set_value("application_name");?>" name = "application_name">
        </div>
        <div class = "form-group">
            <h5>Server Access Token</h5>
            <input type = "text" class = "form-control" value = "<?php echo set_value("server_access_token");?>" name = "server_access_token">
        </div>
        <div class = "form-group">
            <h5>Date Registered</h5>
            <input type = "date" class = "form-control" value = "<?php echo set_value("date_wit_ai_acc_add");?>" name = "date_wit_ai_acc_add">
        </div>
        <a href = "<?php echo base_url();?>setup" class = "btn btn-primary btn-sm">BACK</a>
        <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
    </form>
</div>
