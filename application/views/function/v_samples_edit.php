<div class="page-header">
    <h1 class="page-title">EDIT SAMPLES</h1>
    <br/>
    <ol class="breadcrumb breadcrumb-arrow">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Samples</a></li>
        <li class="breadcrumb-item">Edit</li>
    </ol>
</div>
<br/>
<div class="page-body col-lg-12">
    <div class="row row-lg">
        <div class="col-xl-12">
            <!-- Example Tabs Left -->
            <div class="example-wrap">
                <div class="nav-tabs-vertical" data-plugin="tabs">
                    <ul class="nav nav-tabs mr-25" role="tablist">
                        <li class="nav-item" role="presentation"><a class="nav-link active" data-toggle="tab" href="#primaryData" aria-controls="primaryData" role="tab">Primary Data</a></li>
                    </ul>
                    <form action = "<?php echo base_url();?>function/samples/update" method = "post">
                        <input type = "hidden" name = "id_submit_samples" value = "<?php echo $primary[0]["id_submit_samples"];?>">    
                        <div class="tab-content">
                            <div class="tab-pane active" id="primaryData" role="tabpanel">
                                <div class = "form-group">
                                    <h5>Text</h5>
                                    <input onmouseup = "getSelectedText()" id = "sample_sentence" type = "text" class = "form-control" name = "sample_sentence" value = "<?php echo $primary[0]["samples"];?>" readonly>
                                </div>
                                <div class = "form-group">
                                    <h5>Intent</h5>
                                    <select class = "form-control" data-plugin = "select2" name = "intent" id = "intent_container">
                                        <?php for($a = 0; $a<count($intent_list); $a++):?>
                                        <option value = "<?php echo $intent_list[$a]["entity_value"];?>" <?php if($intent_list[$a]["entity_value"] == $primary[0]["intent_entity_value"]) echo "selected";?>><?php echo $intent_list[$a]["entity_value"];?></option>
                                        <?php endfor;?>
                                    </select>
                                </div>
                                <div class = "form-group">
                                    <h5>Entities</h5>
                                    <table class = "table table-bordered">
                                        <thead>
                                            <th style = "width:5%">#</th>
                                            <th style = "width:5%">New</th>
                                            <th style = "width:5%">Changes</th>
                                            <th style = "width:5%">Remove</th>
                                            <th style = "width:10%">Start</th>
                                            <th style = "width:10%">End</th>
                                            <th>Text</th>
                                            <th>Entity</th>
                                        </thead>
                                        <tbody id = "entityList">
                                            <?php for($a = 0; $a<count($extract); $a++):?>
                                            <tr class = "sample_editor_row">
                                                <input type = "hidden" value = "<?php echo $extract[$a]["id_submit_samples_detail"];?>" name = "id_submit_samples_detail<?php echo $a;?>">
                                                <td><?php echo ($a+1);?></td>
                                                <td></td>
                                                <td>
                                                    <div class = "checkbox-custom checkbox-primary">
                                                        <input type = "checkbox" name = "changes[]" value = "<?php echo $a;?>">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class = "checkbox-custom checkbox-primary">
                                                        <input type = "checkbox" name = "remove[]" value = "<?php echo $a;?>">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type = "hidden" class = "form-control" name = "start_index<?php echo $a;?>" value = "<?php echo $extract[$a]["start_index"];?>"><?php echo $extract[$a]["start_index"];?>
                                                </td>
                                                <td>
                                                    <input type = "hidden" class = "form-control" name = "end_index<?php echo $a;?>" value = "<?php echo $extract[$a]["end_index"];?>"><?php echo $extract[$a]["end_index"];?>
                                                </td>
                                                <td>
                                                    <?php echo substr($primary[0]["samples"],$extract[$a]["start_index"],($extract[$a]["end_index"]-$extract[$a]["start_index"]));?>
                                                </td>
                                                <td>
                                                    <select data-plugin = "select2" name = '' style = 'width:100%' onchange = 'loadEntityValue(<?php echo $a;?>)' id = 'entityOption<?php echo $a;?>' class = 'form-control'>
                                                        <?php for($b = 0; $b<count($entity_list); $b++):?>
                                                        <option value = "<?php echo $entity_list[$b]["entity_name"];?>" <?php if($entity_list[$b]["entity_name"] == $extract[$a]["entity_name"]) echo "selected"; ?>><?php echo $entity_list[$b]["entity_name"];?></option>
                                                        <?php endfor;?>
                                                    </select>
                                                    <hr/>
                                                    <select data-plugin = "select2" style = 'width:100%' id = 'entityValue<?php echo $a;?>' name = 'entityValue<?php echo $a;?>' class = 'form-control'>
                                                        <option value = "<?php echo $extract[$a]["entity_value"];?>"><?php echo $extract[$a]["entity_value"];?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php endfor;?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class = "form-group">
                                    <button type = "submit" class = "btn btn-primary btn-sm">SUBMIT</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class = "form-group">
                <a href = "<?php echo base_url();?>function/samples" class = "btn btn-outline btn-primary btn-sm">BACK</a>
            </div>
            <!-- End Example Tabs Left -->
        </div>
    </div>
</div>