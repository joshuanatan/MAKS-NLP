<?php
class Entity extends CI_Controller{
    public function load_values(){
        $where = array(
            "entity_name" => $this->input->post("id_entity"),
            
        );
        $field = array(
            "id_submit_entity_value","entity_value"
        );
        $result = selectRow("detail_entity",$where,$field,"","","","","entity_value");
        $result_array = $result->result_array();
        echo json_encode($result_array);
    }
    public function get_entity_value(){
        $where = array(
            "id_submit_entity_value" => $this->input->post("id_entity_value")
        );
        $field = array(
            "entity_value"
        );
        $result = selectRow("tbl_entity_value",$where,$field);
        $result_array = $result->result_array();
        echo json_encode($result_array[0]["entity_value"]);
    }
}
?>