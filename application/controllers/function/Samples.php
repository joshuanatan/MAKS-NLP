<?php
date_default_timezone_set("Asia/Bangkok");
class Samples extends CI_Controller{
    private $id_wit_acc;
    private $get_dataset_trial_url = "http://127.0.0.1:8888/project/maks/maks_nlp/ws/endpoint/get_text_meaning";
    private $get_dataset_trial_token = "c1852fb45a10a4c7945e805f348b09c1";
    public function __construct(){
        parent::__construct();
        $this->load->library("curl");
        $this->load->library('wit');
        $where = array(
            "status_aktif_wit_ai_acc" => 1
        );
        $this->id_wit_acc = get1Value("tbl_wit_ai_acc","id_submit_wit_ai_acc",$where);
    }
    public function index(){
        $this->check_session();
        $where = array(
            "status_aktif_intent" => 1,
            "id_wit_ai_acc" => $this->id_wit_acc
        );
        $field = array(
            "intent"
        );
        $result = selectRow("tbl_intent",$where,$field);
        $data["intent"] = $result->result_array();

        $where = array(
            "status_aktif_entity" => 1,
            "id_wit_ai_acc" => $this->id_wit_acc
        );
        $field = array(
            "id_submit_entity","entity_name"
        );
        $result = selectRow("tbl_entity",$where,$field);
        $data["entity"] = $result->result_array();
        $where = array(
            "status_aktif_samples <" => 2,
            "id_wit_ai_acc" => $this->id_wit_acc
        );
        $field = array(
            "id_submit_samples","samples","intent","tgl_samples_last_modified","status_aktif_samples"
        );
        $result = selectRow("v_samples_list",$where,$field,"","","","","id_submit_samples");
        $data["samples"] = $result->result_array();
        for($a = 0; $a<count($data["samples"]); $a++){
            $where = array(
                "id_samples" => $data["samples"][$a]["id_submit_samples"]
            );
            $field = array(
                "start_index","end_index","entity_value","entity_name"
            );
            $result = selectRow("detail_samples",$where,$field,"","","","","id_submit_samples_detail");
            $data["samples"][$a]["entity"] = $result->result_array();
        }
        $this->page_generator->req();
        $this->load->view("plugin/datatable/datatable-css");
        $this->load->view("plugin/form/form-css");
        $this->page_generator->head_close();
        $this->page_generator->navbar();
        $this->page_generator->content_open();
        $this->load->view("function/v_samples",$data);
        $this->page_generator->close();
        $this->load->view("plugin/datatable/datatable-js");
        $this->load->view("plugin/form/form-js");
        $this->load->view("function/v_samples_js",$data);
    }
    public function recycle_bin(){
        $this->check_session();
        
        $where = array(
            "status_aktif_samples" => 2,
            "id_wit_ai_acc" => $this->id_wit_acc
        );
        $field = array(
            "id_submit_samples","samples","intent","tgl_samples_last_modified","status_aktif_samples"
        );
        $result = selectRow("v_samples_list",$where,$field,"","","","","id_submit_samples");
        $data["samples"] = $result->result_array();
        for($a = 0; $a<count($data["samples"]); $a++){
            $where = array(
                "id_samples" => $data["samples"][$a]["id_submit_samples"]
            );
            $field = array(
                "start_index","end_index","entity_value","entity_name"
            );
            $result = selectRow("detail_samples",$where,$field,"","","","","id_submit_samples_detail");
            $data["samples"][$a]["entity"] = $result->result_array();
        }
        $this->page_generator->req();
        $this->load->view("plugin/datatable/datatable-css");
        $this->load->view("plugin/form/form-css");
        $this->page_generator->head_close();
        $this->page_generator->navbar();
        $this->page_generator->content_open();
        $this->load->view("function/v_samples_recycle_bin",$data);
        $this->page_generator->close();
        $this->load->view("plugin/datatable/datatable-js");
        $this->load->view("plugin/form/form-js");
        $this->load->view("function/v_samples_js",$data);
    }
    public function insert(){
        $this->check_session();
        $config = array(
            array(
                "field" => "sample_sentence",
                "label" => "Samples",
                "rules" => "required"
            ),
            array(
                "field" => "intent",
                "label" => "Intent",
                "rules" => "required"
            )
        );
        $this->form_validation->set_rules($config);
        if($this->form_validation->run()){
            
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
                            "entity_value" => $this->input->post("entityValue".$a),
                            "entity_name" => $this->input->post("entity_name".$a),
                            "status_aktif_entity_value" => 1,
                            "status_aktif_entity" => 1
                        );
                        $field = array(
                            "id_submit_entity_value"
                        );
                        $result = selectRow("detail_entity",$where,$field);
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

            $b = 1;
            if($checks != ""){
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
                    $b++;
                }
            }
            $entities = $entity;
            
            $msg = "Samples is added to database, Not uploaded to Wit.ai";
            $this->session->set_flashdata("status_samples","success");
            $this->session->set_flashdata("msg_samples",$msg);
            $respond = $this->wit->post_samples($text,$entities);
            if($respond){
                if($respond["err"]){
                    $msg = $respond["err"];
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                }
                else{
                    $respond = json_decode($respond["response"]);
                    if(!array_key_exists("error",$respond)){
                        $msg = "Samples is added to Wit.ai";
                        $this->session->set_flashdata("status_wit","success");
                        $this->session->set_flashdata("msg_wit",$msg);
                        $where = array(
                            "id_submit_samples" => $id_samples
                        );
                        $data = array(
                            "status_aktif_samples" => 1,
                        );
                        updateRow("tbl_samples",$data,$where);
                        $msg = "Samples is activated";
                        $this->session->set_flashdata("status_samples","success");
                        $this->session->set_flashdata("msg_samples",$msg);
                        
                    }
                    else{
                        $msg = $respond["error"];
                        $this->session->set_flashdata("status_wit","error");
                        $this->session->set_flashdata("msg_wit",$msg);
                    }
                }
            }
        }
        else{
            $this->session->set_flashdata("status_samples","error");
            $this->session->set_flashdata("msg_samples",validation_errors());
        }
        $this->redirect();
    }
    public function remove($id_submit_samples){
        $this->check_session();
        $where = array(
            "id_submit_samples" => $id_submit_samples
        );
        $field = array(
            "samples"
        );
        $result = selectRow("tbl_samples",$where,$field);
        $result_array = $result->result_array();

        $text = $result_array[0]["samples"];
        
        $respond = $this->wit->delete_samples($text);
        if($respond){
            
            if($respond["err"]){
                $msg = $respond["err"];
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit",$msg);
            }
            else{
                $response = json_decode($respond["response"],TRUE);
                if(array_key_exists("error",$response)){
                    $msg = $response["error"];
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                }
                else{
                    $msg = "Data is successfuly removed";
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                    
                    $data = array(
                        "status_aktif_samples" => 2
                    );
                    $where = array(
                        "id_submit_samples" => $id_submit_samples
                    );
                    updateRow("tbl_samples",$data,$where);
                }
            }
        }
        $this->redirect();
    }
    public function delete($id_submit_samples){
        $this->check_session();
        $where = array(
            "id_submit_samples" => $id_submit_samples
        );
        $field = array(
            "samples"
        );
        $result = selectRow("tbl_samples",$where,$field);
        $result_array = $result->result_array();

        $text = $result_array[0]["samples"];
        
        $respond = $this->wit->delete_samples($text);
        if($respond){
            
            if($respond["err"]){
                $msg = $respond["err"];
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit",$msg);
            }
            else{
                $response = json_decode($respond["response"],TRUE);
                if(array_key_exists("error",$response)){
                    $msg = $response["error"];
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                }
                else{
                    $msg = "Data is successfuly removed";
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                    
                    $data = array(
                        "status_aktif_samples" => 0
                    );
                    $where = array(
                        "id_submit_samples" => $id_submit_samples
                    );
                    updateRow("tbl_samples",$data,$where);
                }
            }
        }
        $this->redirect();
    }
    public function reupload($id_submit_samples){
        $this->check_session();
        $where = array(
            "id_submit_samples" => $id_submit_samples
        );
        $field = array(
            "samples","intent"
        );
        $result = selectRow("detail_samples",$where,$field);
        $result_array = $result->result_array();

        $entity_array[0]["entity"] = "intent";
        $entity_array[0]["value"] = $result_array[0]["intent"];

        $where = array(
            "id_samples" => $id_submit_samples
        );
        $field = array(
            "entity_name","start_index","end_index","entity_value"
        );
        $result = selectRow("detail_samples",$where,$field,"","","","","id_submit_entity_value");
        $entity_result_array = $result->result();
        $b = 0 ;
        foreach($entity_result_array as $a){
            $b++;
            $entity_array[$b]["entity"] = $a->entity_name;
            $entity_array[$b]["start"] = $a->start_index;
            $entity_array[$b]["end"] = $a->end_index;
            $entity_array[$b]["value"] = $a->entity_value;
            $b++;
        }
        $text = $result_array[0]["samples"];
        $entities = $entity_array;

        $respond = $this->wit->post_samples($text,$entities);
        if($respond["err"]){
            $msg = $respond["err"];
            $this->session->set_flashdata("status_samples","error");
            $this->session->set_flashdata("msg_samples",$msg);
        }
        else{
            $response = json_decode($respond["response"],TRUE);
            print_r($response);
            if(array_key_exists("error",$response)){
                $msg = $response["error"]." Samples is not uploaded. Consider re-add the sample";
                $this->session->set_flashdata("status_samples","error");
                $this->session->set_flashdata("msg_samples",$msg);
            }
            else{
                $msg = "Data is successfully reuploaded";
                $this->session->set_flashdata("status_samples","success");
                $this->session->set_flashdata("msg_samples",$msg);
                
                $data = array(
                    "status_aktif_samples" => 1
                );
                $where = array(
                    "id_submit_samples" => $id_submit_samples
                );
                updateRow("tbl_samples",$data,$where);
            }
            
        }
        $this->redirect();
    }
    public function redirect(){
        $this->check_session();
        redirect("function/samples");
    }
    private function check_session(){
		if($this->session->id_user == ""){
			$msg = "Session Expired";	
			$this->session->set_flashdata("status_login","error");
			$this->session->set_flashdata("msg_login",$msg);
			redirect("welcome");
		}
    }
    public function trial(){
        $request = $this->input->post("request");
        $url = $this->get_dataset_trial_url;
        $header = array(
            "client-token:".$this->get_dataset_trial_token
        );
        $body = array(
            "search_text" => $request
        );
        $response = $this->curl->post($url,$header,$body);
        header("content-type:application/json");
        //echo $encode;
        echo $response["response"];
    }
}
?>