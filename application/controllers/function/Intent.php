<?php
date_default_timezone_set("Asia/Bangkok");
class Intent extends CI_Controller{
    private $id_wit_acc;
    public function __construct(){
        parent::__construct();
        $this->load->library("wit");
        $where = array(
            "status_aktif_wit_ai_acc" => 1
        );
        $this->id_wit_acc = get1Value("tbl_wit_ai_acc","id_submit_wit_ai_acc",$where);
    }
    public function index(){
        $this->check_session();
        $where = array(
            "id_wit_ai_acc" => $this->id_wit_acc,
            "status_aktif_intent <" => 2,
        );
        $field = array(
            "id_submit_intent","intent","status_aktif_intent","tgl_intent_last_modified"
        );
        $result = selectRow("tbl_intent",$where,$field);
        $data["intent"] = $result->result_array();

        $this->page_generator->req();
        $this->load->view("plugin/datatable/datatable-css");
        $this->page_generator->head_close();
        $this->page_generator->navbar();
        $this->page_generator->content_open();
        $this->load->view("function/v_intent",$data);
        $this->page_generator->close();
        $this->load->view("plugin/datatable/datatable-js");
        $this->load->view("function/v_intent_js");
    }
    public function recycle_bin(){
        $this->check_session();
        $where = array(
            "id_wit_ai_acc" => $this->id_wit_acc,
            "status_aktif_intent" => 2
        );
        $field = array(
            "id_submit_intent","intent","status_aktif_intent","tgl_intent_last_modified"
        );
        $result = selectRow("tbl_intent",$where,$field);
        $data["intent"] = $result->result_array();
        
        $this->page_generator->req();
        $this->load->view("plugin/datatable/datatable-css");
        $this->page_generator->head_close();
        $this->page_generator->navbar();
        $this->page_generator->content_open();
        $this->load->view("function/v_intent_recycle_bin",$data);
        $this->page_generator->close();
        $this->load->view("plugin/datatable/datatable-js");
        $this->load->view("function/v_intent_js");
    }
    public function insert_intent(){
        $this->check_session();
        $checks = $this->input->post("add_check");
        if($checks != ""){
            foreach($checks as $a){
                $config = array(
                    array(
                        "field" => "intent".$a,
                        "label" => "Intention",
                        "rules" => "required"
                    )
                );
                $this->form_validation->set_rules($config);
                $value = $this->input->post("intent".$a);
                if($this->form_validation->run()){
                    $where = array(
                        "intent" => $this->input->post("intent".$a),
                        "id_wit_ai_acc" => $this->id_wit_acc,
                    );
                    $field = array(
                        "intent"
                    );
                    $check = selectRow("tbl_intent",$where,$field);
                    if($check->num_rows() > 0){
                        
                        $this->session->set_flashdata("status_wit","error");
                        $this->session->set_flashdata("msg_wit","Data Exists");
                    }
                    else{
                        $data = array(
                            "id_wit_ai_acc" => $this->id_wit_acc,
                            "intent" => $value,
                            "tgl_intent_last_modified" => date("Y-m-d H:i:s"),
                            "status_aktif_intent" => 0,
                            "id_user_intent_last_modified" => $this->session->id_user
                        );
                        $id_submit_intent = insertRow("tbl_intent",$data);
                        $response = $this->wit->post_entities_value("intent", $value);
                        if ($response["err"]) {
                            $msg = $response["err"];
                            $this->session->set_flashdata("status_wit","error");
                            $this->session->set_flashdata("msg_wit",$msg);
                        } 
                        else {
                            $respond = json_decode($response["response"],true);
                            if(!array_key_exists("error",$respond)){
                                $msg = "Intent is successfully added to Wit.ai";
                                $this->session->set_flashdata("status_wit","success");
                                $this->session->set_flashdata("msg_wit",$msg);
    
                                $where = array(
                                    "id_submit_intent" => $id_submit_intent   
                                );
                                $data = array(
                                    "status_aktif_intent" => 1
                                );
                                updateRow("tbl_intent",$data,$where);
                                $msg = "Intent is successfully added to the system";
                                $this->session->set_flashdata("status_entity","success");
                                $this->session->set_flashdata("msg_entity",$msg);
                            }
                            else{
                                $msg = $respond["error"];
                                $this->session->set_flashdata("status_wit","error");
                                $this->session->set_flashdata("msg_wit",$msg);
                            }
                        }
                    }
                }
            }
        }
        $this->redirect();

    }
    public function update_intent(){
        $this->check_session();
        $config = array(
            array(
                "field" => "intent",
                "label" => "Intent",
                "rules" => "required"
            )
        );
        $this->form_validation->set_rules($config);
        if($this->form_validation->run()){
            $where = array(
                "intent" => $this->input->post("intent"),
                "id_submit_intent !=" => $this->input->post("id_submit_intent")
            );
            $field = array(
                "intent"
            );
            $check = selectRow("tbl_intent",$where,$field);
            if($check->num_rows() > 0){
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit","Data Exists");
            }
            else{
                $where = array(
                    "id_submit_intent" => $this->input->post("id_submit_intent")
                );
                $intent = get1Value("tbl_intent","intent",$where);
                $respond = $this->wit->delete_entities_value("intent",$intent);
                $msg = "";
                if($respond){
                    if($respond["err"]){
                        $msg .= $respond["err"].". ";
                        $this->session->set_flashdata("status_wit","error");
                        $this->session->set_flashdata("msg_wit",$msg);
                    }
                    else{
                        $msg .= "Current intent is removed from Wit.ai".". ";
                        $this->session->set_flashdata("status_wit","success");
                        $this->session->set_flashdata("msg_wit",$msg);
                    }
                }
                
                $intent = $this->input->post("intent");
                $respond = $this->wit->post_entities_value("intent",$intent);
                if($respond){
                    if($respond["err"]){ 
                        $msg .= $respond["err"].". ";
                        $this->session->set_flashdata("status_wit","error");
                        $this->session->set_flashdata("msg_wit",$msg);
    
                    }
                    else{
                        $msg .= "New intent is added to Wit.ai".". ";
                        $this->session->set_flashdata("status_wit","success");
                        $this->session->set_flashdata("msg_wit",$msg);
                        
                        $where = array(
                            "id_submit_intent" => $this->input->post("id_submit_intent")
                        );
                        $data = array(
                            "intent" => $this->input->post("intent"),
                            "tgl_intent_last_modified" => date("Y-m-d H:i:s"),
                            "id_user_intent_last_modified" => $this->session->id_user
                        );
                        updateRow("tbl_intent",$data,$where);
    
                        $msg .= "New intent is added to database".". ";
                        $this->session->set_flashdata("status_entity","success");
                        $this->session->set_flashdata("msg_entity",$msg);
                    }
                }
            }
            $this->redirect();
        }
        else{
            $this->page_generator->req();
            $this->page_generator->head_close();
            $this->page_generator->content_open();
            $this->load->view("function/v_intent_reupdate");
            $this->page_generator->close();
        }
    }
    public function remove_intent($id_submit_intent){
        $this->check_session();
        $where = array(
            "id_submit_intent" => $id_submit_intent
        );
        $field = array(
            "intent"
        );
        $result = selectRow("tbl_intent",$where,$field);
        $result_array = $result->result_array();
        $intent = $result_array[0]["intent"];
        $respond = $this->wit->delete_entities_value("intent",$intent);
        if($respond){
            if($respond["err"]){
                $msg = $respond["err"];
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit",$msg);
            }
            else{
                $respond = json_decode($respond["response"],true);
                if(!array_key_exists("error",$respond)){
                    
                    $msg = "Intent is successfully removed from Wit.ai";
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                    $where = array(
                        "id_submit_intent" => $id_submit_intent
                    );
                    
                    $data = array(
                        "status_aktif_intent" => 2,
                        "tgl_intent_last_modified" => date("Y-m-d H:i:s"),
                        "id_user_intent_last_modified" => $this->session->id_user
                    );
                    updateRow("tbl_intent",$data,$where);
                    $msg = "Intent is successfully removed from database";
                    $this->session->set_flashdata("status_entity","error");
                    $this->session->set_flashdata("msg_entity",$msg);
                }
                else{
                    $msg = $respond["error"];
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                }
            }
            $this->redirect();
        }
    }
    public function delete_intent($id_submit_intent){
        $this->check_session();
        $where = array(
            "id_submit_intent" => $id_submit_intent
        );
        $field = array(
            "intent"
        );
        $result = selectRow("tbl_intent",$where,$field);
        $result_array = $result->result_array();
        $intent = $result_array[0]["intent"];
        $respond = $this->wit->delete_entities_value("intent",$intent);
        if($respond){
            if($respond["err"]){
                $msg = $respond["err"];
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit",$msg);
            }
            else{
                $respond = json_decode($respond["response"],true);
                if(!array_key_exists("error",$respond)){
                    
                    $msg = "Intent is successfully removed from Wit.ai";
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                    $where = array(
                        "id_submit_intent" => $id_submit_intent
                    );
                    
                    $data = array(
                        "status_aktif_intent" => 0,
                        "tgl_intent_last_modified" => date("Y-m-d H:i:s"),
                        "id_user_intent_last_modified" => $this->session->id_user
                    );
                    updateRow("tbl_intent",$data,$where);
                    $msg = "Intent is successfully deactivated";
                    $this->session->set_flashdata("status_entity","error");
                    $this->session->set_flashdata("msg_entity",$msg);
                }
                else{
                    $msg = $respond["error"];
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                }
            }
            $this->redirect();
        }
    }
    public function reupload_intent($id_submit_intent){
        $this->check_session();
        
        $where = array(
            "id_submit_intent" => $id_submit_intent
        );
        $field = array(
            "intent"
        );
        $result = selectRow("tbl_intent",$where,$field);
        $result_array = $result->result_array();
        $intent = $result_array[0]["intent"];

        $where = array(
            "id_submit_intent" => $id_submit_intent,
            "status_aktif_intent_expression" => 1
        );
        $respond = $this->wit->post_entities_value("intent",$intent);
        if($respond){
            if($respond["err"]){
                $msg = $respond["err"];
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit",$msg);
            }
            else{
                $respond = json_decode($respond["response"],true);
                if(!array_key_exists("error",$respond)){
                    $msg = "Intent is successfully added to Wit.ai";
                    $this->session->set_flashdata("status_wit","success");
                    $this->session->set_flashdata("msg_wit",$msg);
                    $where = array(
                        "id_submit_intent" => $id_submit_intent
                    );
                    $data = array(
                        "status_aktif_intent" => 1,
                        "tgl_intent_last_modified" => date("Y-m-d H:i:s")
                    );
                    updateRow("tbl_intent",$data,$where);
                    $msg = "Intent is successfully added to database";
                    $this->session->set_flashdata("status_entity","success");
                    $this->session->set_flashdata("msg_entity",$msg);
                }
                else{
                    $msg = $respond["error"];
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                }
            }
            $this->redirect();
        }
    }
    public function redirect(){
        $this->check_session();
        redirect("function/intent");
    }
    private function check_session(){
		if($this->session->id_user == ""){
			$msg = "Session Expired";	
			$this->session->set_flashdata("status_login","error");
			$this->session->set_flashdata("msg_login",$msg);
			redirect("welcome");
		}
    }
    public function check_intent(){
        $respond = $this->wit->get_entity_detail("intent");
        if($respond["err"]){
            $msg = $respond["err"];
            echo $msg;
        }
        else{
            $respond = json_decode($respond["response"],true);
            if(!array_key_exists("error",$respond)){
                echo "Entity Name Intent is exists.. continue";
            }
            else{
                $msg = $respond["error"];
                echo $msg;
                
                $id = "intent";
                $doc = "";
                $response = $this->wit->post_entities($id,$doc);
                if($response){
                    if ($response["err"]) {
                        echo "fail making request intent";
                    }
                    else{
                        $respond = json_decode($response["response"],true);
                        if(!array_key_exists("error",$respond)){
                            echo "Intent is uploaded";
                        }
                        else{
                            echo "fail uploading entity name intent";
                        }
                    }
                }
            }
        }
    }
}
?>