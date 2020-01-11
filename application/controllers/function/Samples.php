<?php
class Samples extends CI_Controller{
    private $id_wit_acc;
    public function __construct(){
        parent::__construct();
        $this->load->library('wit');
        $where = array(
            "status_aktif_wit_ai_acc" => 1
        );
        $this->id_wit_acc = get1Value("tbl_wit_ai_acc","id_submit_wit_ai_acc",$where);
    }
    public function index(){
        $this->check_session();
        $where = array(
            "entity_name" => "intent",
            "status_aktif_entity_value" => 1,
            "id_wit_ai_acc" => $this->id_wit_acc
        );
        $field = array(
            "entity_value"
        );
        $result = selectRow("detail_intent",$where,$field,"","","","","id_submit_entity_value");
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
            "id_submit_samples","samples","entity_value","tgl_samples_add","tgl_samples_edit","tgl_samples_delete","status_aktif_samples"
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
            "id_submit_samples","samples","entity_value","tgl_samples_add","tgl_samples_edit","tgl_samples_delete","status_aktif_samples"
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

            $b = 0;
            if($checks != ""){
                $b++;
                foreach($checks as $a){
                    $entity[$b]["entity"] = $this->input->post("entity_name".$a);
                    $entity[$b]["value"] = $this->input->post("entityValue".$a);
                    $entity[$b]["start"] = $this->input->post("startIndex".$a);
                    $entity[$b]["end"] = $this->input->post("endIndex".$a);
                }
            }
            $entities = $entity;

            $respond = $this->wit->post_samples($text,$entities);
            $id_intent = get1Value("tbl_entity_value","id_submit_entity_value",array("entity_value" => $this->input->post("intent")));
            if($respond){
                if($respond["err"]){
                    $msg = $respond["err"];
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                    $data = array(
                        "id_wit_ai_acc" => get1Value("tbl_wit_ai_acc","id_submit_wit_ai_acc",array("status_aktif_wit_ai_acc" => 1)),
                        "samples" => $text,
                        "status_aktif_samples" => 0,
                        "id_intent" => $id_intent,
                        "tgl_samples_add" => date("Y-m-d H:i:s"),
                        "id_user_samples_add" => $this->session->id_user
                    );
                    $id_samples = insertRow("tbl_samples",$data);
                    $checks = $this->input->post("analytics_check");
                    if($checks != ""){
                        foreach($checks as $a){
                            $id_intent = get1Value("tbl_entity_value","id_submit_entity_value",array("entity_value" => $this->input->post("entityValue".$a)));
                            $data = array(
                                "id_samples" => $id_samples,
                                "start_index" => $this->input->post("startIndex".$a),
                                "end_index" => $this->input->post("endIndex".$a),
                                "id_entity_value" => $id_intent
                            );
                            insertRow("tbl_samples_entity",$data);
                        }
                    }
                    $msg = "Samples is added to database, Not uploaded to Wit.ai";
                    $this->session->set_flashdata("status_samples","error");
                    $this->session->set_flashdata("msg_samples",$msg);
                }
                else{
                    $respond = json_decode($respond["response"]);
                    if(!array_key_exists("error",$respond)){
                        $msg = "Samples is added to Wit.ai";
                        $this->session->set_flashdata("status_wit","success");
                        $this->session->set_flashdata("msg_wit",$msg);
                        $data = array(
                            "id_wit_ai_acc" => $this->id_wit_acc,
                            "samples" => $this->input->post("sample_sentence"),
                            "status_aktif_samples" => 1,
                            "id_intent" => $id_intent,
                            "tgl_samples_add" => date("Y-m-d H:i:s"),
                            "id_user_samples_add" => $this->session->id_user
                        );
                        $id_samples = insertRow("tbl_samples",$data);
                        $checks = $this->input->post("analytics_check");
                        if($checks != ""){
                            foreach($checks as $a){
                                $id_intent = get1Value("tbl_entity_value","id_submit_entity_value",array("entity_value" => $this->input->post("entityValue".$a)));
                                $data = array(
                                    "id_samples" => $id_samples,
                                    "start_index" => $this->input->post("startIndex".$a),
                                    "end_index" => $this->input->post("endIndex".$a),
                                    "id_entity_value" => $id_intent
                                );
                                insertRow("tbl_samples_entity",$data);
                            }
                        }
                        $msg = "Samples is added to database";
                        $this->session->set_flashdata("status_samples","success");
                        $this->session->set_flashdata("msg_samples",$msg);
                    }
                    else{
                        $msg = $respond["error"];
                        $this->session->set_flashdata("status_wit","error");
                        $this->session->set_flashdata("msg_wit",$msg);
                        $data = array(
                            "id_wit_ai_acc" => $this->id_wit_acc,
                            "samples" => $text,
                            "status_aktif_samples" => 0,
                            "id_intent" => $id_intent,
                            "tgl_samples_add" => date("Y-m-d H:i:s"),
                            "id_user_samples_add" => $this->session->id_user
                        );
                        $id_samples = insertRow("tbl_samples",$data);
                        $checks = $this->input->post("analytics_check");
                        if($checks != ""){
                            foreach($checks as $a){
                                $id_intent = get1Value("tbl_entity_value","id_submit_entity_value",array("entity_value" => $this->input->post("entityValue".$a)));
                                $data = array(
                                    "id_samples" => $id_samples,
                                    "start_index" => $this->input->post("startIndex".$a),
                                    "end_index" => $this->input->post("endIndex".$a),
                                    "id_entity_value" => $id_intent
                                );
                                insertRow("tbl_samples_entity",$data);
                            }
                        }
                        $msg = "Samples is added to database, Not uploaded to Wit.ai";
                        $this->session->set_flashdata("status_samples","error");
                        $this->session->set_flashdata("msg_samples",$msg);
                    }
                }
                $this->redirect();
            }
        }
        $this->session->set_flashdata("status_samples","error");
        $this->session->set_flashdata("msg_samples",validation_errors());
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
                $data = array(
                    "status_aktif_samples" => 2
                );
                $where = array(
                    "id_submit_samples" => $id_submit_samples
                );
                updateRow("tbl_samples",$data,$where);
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
                $data = array(
                    "status_aktif_samples" => 0
                );
                $where = array(
                    "id_submit_samples" => $id_submit_samples
                );
                updateRow("tbl_samples",$data,$where);
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
                }
            }
        }
        $this->redirect();
    }
    public function edit($id_submit_samples){
        $this->check_session();
        $where = array(
            "id_submit_samples" => $id_submit_samples
        );
        $field = array(
            "id_submit_samples","samples","intent_entity_value"
        );
        $result = selectRow("detail_samples",$where,$field,"","","","","id_submit_samples");
        $data["primary"] = $result->result_array();

        $where = array(
            "id_samples" => $id_submit_samples
        );
        $field = array(
            "start_index","end_index","entity_value","entity_name","id_submit_samples_detail"
        );
        $result = selectRow("detail_samples",$where,$field,"","","","","id_submit_samples_detail");
        $data["extract"] = $result->result_array();

        $where = array(
            "status_aktif_entity_value" => 1,
        );
        $field = array(
            "entity_value"
        );
        $result = selectRow("v_intent",$where,$field,"","","","","entity_value");
        $data["intent_list"] = $result->result_array();

        $where = array(
            "status_aktif_entity" => 1,
            "entity_name != " => "intent"
        );
        $field = array(
            "entity_name"
        );
        $result = selectRow("detail_entity",$where,$field,"","","","","entity_name");
        $data["entity_list"] = $result->result_array();
        $this->page_generator->req();
        $this->load->view("plugin/form/form-css");
        $this->page_generator->head_close();
        $this->page_generator->navbar();
        $this->page_generator->content_open();
        $this->load->view("function/v_samples_edit",$data);
        $this->page_generator->close();
        $this->load->view("plugin/form/form-js");
        $this->load->view("function/v_samples_edit_js",$data);
    }
    public function update(){
        $this->check_session();
        $text = $this->input->post("sample_sentence");
        $intent_name = $this->input->post("intent");
        $id_intent = get1Value("tbl_entity_value","id_submit_entity_value",array("entity_value" => $intent_name));

        $new_checks = $this->input->post("new");
        $change_checks = $this->input->post("changes");
        $delete_checks = $this->input->post("remove");

        $entity[0]["entity"] = "intent";
        $entity[0]["value"] = $intent_name;

        
        if($new_checks != ""){
            foreach($new_checks as $a){
                $data = array(
                    "id_samples" => $this->input->post("id_submit_samples"),
                    "start_index" => $this->input->post("start_index".$a),
                    "end_index" => $this->input->post("end_index".$a),
                    "id_entity_value" => get1Value("tbl_entity_value","id_submit_entity_value",array("entity_value" => $this->input->post("entityValue".$a)))
                );
                insertRow("tbl_samples_entity",$data);
            }
        }
        if($change_checks != ""){
            foreach($change_checks as $a){
                $where = array(
                    "id_submit_samples_detail" => $this->input->post("id_submit_samples_detail".$a)
                );
                $data = array(
                    "id_samples" => $this->input->post("id_submit_samples"),
                    "id_entity_value" => get1Value("tbl_entity_value","id_submit_entity_value",array("entity_value" => $this->input->post("entityValue".$a)))
                );
                updateRow("tbl_samples_entity",$data,$where);
            }
        }
        if($delete_checks != ""){
            foreach($delete_checks as $a){
                $where = array(
                    "id_submit_samples_detail" => $this->input->post("id_submit_samples_detail".$a)
                );
                deleteRow("tbl_samples_entity",$where);
            }
        }
        $where = array(
            "id_samples" => $this->input->post("id_submit_samples")
        );
        $field = array(
            "start_index","end_index","entity_value","entity_name"
        );
        $result = selectRow("detail_samples",$where,$field,"","","","","id_submit_entity_value");
        $result_array = $result->result();
        $b = 0;
        foreach($result_array as $a){
            $b++;
            $entity[$b]["entity"] = $a->entity_name;
            $entity[$b]["start"] = $a->start_index;
            $entity[$b]["end"] = $a->end_index;
            $entity[$b]["value"] = $a->entity_value;
        }
        $entities = $entity;

        $this->wit->delete_samples($text);
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
                    $msg = "Samples is updated to Wit.ai";
                    $this->session->set_flashdata("status_wit","success");
                    $this->session->set_flashdata("msg_wit",$msg);

                    $where = array(
                        "id_submit_samples" => $this->input->post("id_submit_samples")
                    );
                    $data = array(
                        "samples" => $text,
                        "id_intent" => $id_intent,
                        "tgl_samples_edit" => date("Y-m-d H:i:s"),
                        "id_user_samples_edit" => $this->session->id_user
                    );
                    updateRow("tbl_samples",$data,$where);
                    $msg = "Samples is updated to database";
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
        $msg = validation_errors();
        $this->session->set_flashdata("status_wit","error");
        $this->session->set_flashdata("msg_wit",$msg);
        $this->redirect();

    }
    public function reupload($id_submit_samples){
        $this->check_session();
        $where = array(
            "id_submit_samples" => $id_submit_samples
        );
        $field = array(
            "samples","intent_entity_value"
        );
        $result = selectRow("detail_samples",$where,$field,"","","","","id_submit_samples");
        $result_array = $result->result_array();

        $entity_array[0]["entity"] = "intent";
        $entity_array[0]["value"] = $result_array[0]["intent_entity_value"];

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
            $data = array(
                "status_aktif_samples" => 1
            );
            $where = array(
                "id_submit_samples" => $id_submit_samples
            );
            updateRow("tbl_samples",$data,$where);
            $response = json_decode($respond["response"],TRUE);
            if(array_key_exists("error",$response)){
                $msg = $response["error"];
                $this->session->set_flashdata("status_samples","error");
                $this->session->set_flashdata("msg_samples",$msg);
            }
            else{
                $msg = "Data is successfully reuploaded";
                $this->session->set_flashdata("status_samples","success");
                $this->session->set_flashdata("msg_samples",$msg);
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
}
?>