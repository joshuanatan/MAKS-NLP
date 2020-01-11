<?php
class Samples extends CI_Controller{
    public function get_samples_entity(){
        $where = array(
            "id_samples" => $this->input->post("id_samples")
        );
        $field = array(
            "start_index","end_index","entity_value","entity_name"
        );
        $result = selectRow("detail_samples",$where,$field,"","","","","id_submit_samples_detail");
        $result_array = $result->result_array();
        echo json_encode($result_array);
    }
    public function get_samples_primary(){
        $where = array(
            "id_submit_samples" => $this->input->post("id_submit_samples")
        );
        $field = array(
            "samples","intent_entity_value"
        );
        $result = selectRow("detail_samples",$where,$field,"","","","","id_submit_samples");
        $result_array = $result->result_array();
        echo json_encode($result_array);
    }
}
?>