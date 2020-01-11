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
<div class = "alert alert-danger alert-dismissible">
    <button type = "button" class = "close" data-dismiss = "alert">&times;</button>
    <?php echo validation_errors();?>
</div>
<div class="page-body">
    <form action = "<?php echo base_url();?>function/samples/insert" method = "POST">
        <div class = "form-group">
            <h5>Text</h5>
            <input id = "sample_sentence" type = "text" class = "form-control" name = "sample_sentence">
        </div>
        <div class = "form-group">
            <h5>Intent</h5>
            <select class = "form-control" data-plugin = "select2" name = "intent">
                <?php for($a = 0; $a<count($intent); $a++):?>
                <option value = "<?php echo $intent[$a]["entity_value"];?>" <?php if($intent[$a]["entity_value"] == set_value("intent")) echo "selected";?>><?php echo $intent[$a]["entity_value"];?></option>
                <?php endfor;?>
            </select>
        </div>
        <div class = "form-group">
            <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
        </div>
    </form>
</div>
