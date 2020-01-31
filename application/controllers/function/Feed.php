<?php
set_time_limit(0);
class Feed extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }
    public function entity_value(){
        $entity_value = array(
            "hari ini"
        );
        $this->session->id_submit_entity = 39;
        $entity = "hariIni";
        for($a = 0; $a<count($entity_value); $a++){
            $where = array(
                "entity_value" => $entity_value[$a],
                "id_entity" => $this->session->id_submit_entity,
            );
            $field = array(
                "entity_value"
            );
            $check = selectRow("tbl_entity_value",$where,$field);
            /*check double value*/
            if(!$check->num_rows() > 0){
                $value = $entity_value[$a];
                $expression = "";
                $expression_list = explode(",",$expression);
                $data = array(
                    "id_entity" => $this->session->id_submit_entity,
                    "entity_value" => $value,
                    "tgl_entity_value_last_modified" => date("Y-m-d H:i:s"),
                    "status_aktif_entity_value" => 0,
                    "id_user_entity_value_last_modified" => 1
                );
                $id_submit_entity_value = insertRow("tbl_entity_value",$data);
                $response = $this->wit->post_entities_value($entity,$value,$expression_list);
                if ($response["err"]) {
                    echo $response["err"];
                } 
                else {
                    $respond = json_decode($response["response"],true);
                    print_r($respond);
                    if(!array_key_exists("error",$respond)){
                        $where = array(
                            "id_submit_entity_value" => $id_submit_entity_value   
                        );
                        $data = array(
                            "status_aktif_entity_value" => 1
                        );
                        updateRow("tbl_entity_value",$data,$where);
                    }
                }
            }
        }
    }
    public function samples(){
        $text = $this->input->post("sample_sentence");
        $checks = $this->input->post("analytics_check");


        $entity[0]["entity"] = "intent";
        $entity[0]["value"] = $this->input->post("intent");
        $id_intent = get1Value("tbl_intent","id_submit_intent",array("intent" => $this->input->post("intent")));
        $data = array(
            "id_wit_ai_acc" => get1Value("tbl_wit_ai_acc","id_submit_wit_ai_acc",array("status_aktif_wit_ai_acc" => 1)),
            "samples" => $text,
            "status_aktif_samples" => 0,
            "id_intent" => $id_intent,
            "tgl_samples_last_modified" => date("Y-m-d H:i:s"),
            "id_user_samples_last_modified" => $this->session->id_user
        );
        $id_samples = insertRow("tbl_samples",$data);

        if($checks != ""){
            foreach($checks as $a){
                if($this->input->post("entityValue".$a) == "0"){
                    //masukin entity value baru
                    //output entity value baru
                    $where = array(
                        "entity_name" => $this->input->post("entity_name".$a)
                    );
                    $field = array(
                        "id_submit_entity"
                    );
                    $result = selectRow("tbl_entity",$where,$field);
                    $result = $result->row();
                    $id_entity = $result->id_submit_entity;
                    $entity_value = $this->input->post("highlight".$a);
                    $url = base_url()."function/entity/insert_single_entity_value";
                    $body = array(
                        "entity_value" => $entity_value,
                        "entity_name" => $this->input->post("entity_name".$a),
                        "id_entity" => $id_entity
                    );
                    $response = $this->curl->post($url,array(),$body);
                    $respond = json_decode($response["response"],true);
                    $id_entity_value = $respond["id_entity_value"];
                    print_r($respond);
                }
                else{
                    $where = array(
                        "entity_value" => $this->input->post("entityValue".$a)
                    );
                    $field = array(
                        "id_submit_entity_value"
                    );
                    $result = selectRow("tbl_entity_value",$where,$field);
                    $result = $result->row();
                    $id_entity_value = $result->id_submit_entity_value;

                    if($this->input->post("entityValue".$a) != $this->input->post("highlight".$a)){
                        $expression = $this->input->post("highlight".$a);
                        $url = base_url()."function/entity/insert_single_expression";
                        $body = array(
                            "id_entity_value" => $id_entity_value,
                            "expression" => $expression,
                        );
                        $this->curl->post($url,array(),$body);
                    }
                }
                
                $data = array(
                    "id_samples" => $id_samples,
                    "start_index" => $this->input->post("startIndex".$a),
                    "end_index" => $this->input->post("endIndex".$a),
                    "id_entity_value" => $id_entity_value
                );
                insertRow("tbl_samples_entity",$data);
            }
        }

        $b = 0;
        if($checks != ""){
            $b++;
            foreach($checks as $a){
                $entity[$b]["entity"] = $this->input->post("entity_name".$a);
                if($this->input->post("entityValue".$a) == "0"){
                    $entity[$b]["value"] = $this->input->post("highlight".$a);
                }
                else{
                    $entity[$b]["value"] = $this->input->post("entityValue".$a);
                }
                $entity[$b]["start"] = $this->input->post("startIndex".$a);
                $entity[$b]["end"] = $this->input->post("endIndex".$a);
            }
        }
        $entities = $entity;
        $respond = $this->wit->post_samples($text,$entities);
        if($respond){
            if($respond["err"]){
                echo $respond["err"];
            }
            else{
                $respond = json_decode($respond["response"]);
                if(!array_key_exists("error",$respond)){
                    $where = array(
                        "id_submit_samples" => $id_samples
                    );
                    $data = array(
                        "status_aktif_samples" => 1,
                    );
                    updateRow("tbl_samples",$data,$where);
                    
                }
                else{
                    echo $respond["error"];
                }
            }
        }
    }
}