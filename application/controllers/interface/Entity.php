<?php
class Entity extends CI_Controller{
    public function load_values(){
        $where = array(
            "entity_name" => $this->input->post("id_entity"),
            "status_aktif_entity_value" => 1
        );
        $field = array(
            "id_submit_entity_value","entity_value"
        );
        $result = selectRow("detail_entity",$where,$field,"","","","","entity_value");
        $result_array = $result->result_array();
        echo json_encode($result_array);
    }
    public function get_expression(){
        $where = array(
            "id_entity_value" => $this->input->post("id_entity_value"),
            "status_aktif_entity_value_expression" => 1
        );
        $field = array(
            "entity_value_expression"
        );
        $result = selectRow("tbl_entity_value_expression",$where,$field);
        $result_array = $result->result_array();
        $string = "";
        for($a = 0; $a<count($result_array); $a++){
            $string .= $result_array[$a]["entity_value_expression"];
            if($a+1 != count($result_array)){
                $string .= ",";
            }
        }
        echo json_encode($string);
    }
    public function get_entity_value(){
        $where = array(
            "id_submit_entity_value" => $this->input->post("id_entity_value"),
            "status_aktif_entity_value" => 1
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