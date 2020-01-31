<?php
date_default_timezone_set("Asia/Bangkok");
class Entity extends CI_Controller{
    private $id_wit_acc;
    public function __construct(){ 
        parent::__construct();
        $this->load->library('wit');
        $this->load->library('curl');
        $where = array(
            "status_aktif_wit_ai_acc" => 1
        );
        $this->id_wit_acc = get1Value("tbl_wit_ai_acc","id_submit_wit_ai_acc",$where);
    }
    public function index(){
        $this->check_session();
        unset($this->session->id_submit_entity);
        $where = array(
            "entity_name != " => "intent",
            "status_aktif_entity <" => 2,
            "id_wit_ai_acc" => $this->id_wit_acc
        );
        $field = array(
            "id_submit_entity","entity_name","status_aktif_entity","tgl_entity_last_modified","entity_desc"
        );
        $result = selectRow("tbl_entity",$where,$field);
        $data["entity"] = $result->result_array();
        $this->page_generator->req();
        $this->load->view("plugin/datatable/datatable-css");
        $this->page_generator->head_close();
        $this->page_generator->navbar();
        $this->page_generator->content_open();
        $this->load->view("function/v_entity",$data);
        $this->page_generator->close();
        $this->load->view("plugin/datatable/datatable-js");
    }
    public function recycle_bin(){
        $this->check_session();
        unset($this->session->id_submit_entity);
        $where = array(
            "entity_name != " => "intent",
            "status_aktif_entity" => 2,
            "id_wit_ai_acc" => $this->id_wit_acc
        );
        $field = array(
            "id_submit_entity","entity_name","status_aktif_entity","tgl_entity_last_modified","entity_desc"
        );
        $result = selectRow("tbl_entity",$where,$field);
        $data["entity"] = $result->result_array();
        $this->page_generator->req();
        $this->load->view("plugin/datatable/datatable-css");
        $this->page_generator->head_close();
        $this->page_generator->navbar();
        $this->page_generator->content_open();
        $this->load->view("function/v_entity_recycle_bin",$data);
        $this->page_generator->close();
        $this->load->view("plugin/datatable/datatable-js");
    }
    public function insert_entity(){
        $this->check_session();
        $checks = $this->input->post("add_check");
        if($checks != ""){
            foreach($checks as $a){
                $config = array(
                    array(
                        "field" => "entity_name".$a,
                        "label" => "Entity Name",
                        "rules" => "required"
                    ),
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run()){
                    $where = array(
                        "entity_name" => $this->input->post("entity_name".$a),
                        "id_wit_ai_acc" => $this->id_wit_acc
                    );
                    $field = array(
                        "id_wit_ai_acc"
                    );
                    $result = selectRow("tbl_entity",$where,$field);
                    if($result->num_rows() > 0){
                        $this->session->set_flashdata("status_entity","error");
                        $this->session->set_flashdata("msg_entity","Data exists");
                    }
                    else{
                        
                        
                        $data = array(
                            "id_wit_ai_acc" => $this->id_wit_acc,
                            "entity_name" => $this->input->post("entity_name".$a),
                            "entity_desc" => $this->input->post("entity_desc".$a),
                            "tgl_entity_last_modified" => date("Y-m-d H:i:s"),
                            "status_aktif_entity" => 0, //diset fix tidak aktif
                            "id_user_entity_last_modified" => $this->session->id_user
                        );
                        $id_submit_entity = insertRow("tbl_entity",$data);
                        
                        $this->session->set_flashdata("status_entity","success");
                        $this->session->set_flashdata("msg_entity","Data is successfully added");
            
                        $id = $this->input->post("entity_name".$a);
                        $doc = $this->input->post("entity_desc".$a);
                        $response = $this->wit->post_entities($id,$doc);
            
                        if($response){
                            if ($response["err"]) {
                                //curl error
                                $this->session->set_flashdata("status_wit","error");
                                $this->session->set_flashdata("msg_wit",$response["err"]);
                            } 
                            else {
                                $respond = json_decode($response["response"],true);
                                if(!array_key_exists("error",$respond)){
                                    //integrasi berhasil, ubah jadi aktif
                                    $msg = "Data is successfully added to Wit.ai";
                                    $this->session->set_flashdata("status_wit","success");
                                    $this->session->set_flashdata("msg_wit",$msg);
                                    $where = array(
                                        "id_submit_entity" => $id_submit_entity   
                                    );
                                    $data = array(
                                        "entity_wit_id" => $respond["id"],
                                        "status_aktif_entity" => 1
                                    );
                                    updateRow("tbl_entity",$data,$where);
                                    $this->session->set_flashdata("status_entity","success");
                                    $this->session->set_flashdata("msg_entity","Entity is successfully added");
                                }
                                else{
                                    //integrasi error
                                    $this->session->set_flashdata("status_wit","error");
                                    $this->session->set_flashdata("msg_wit",$respond["error"]);
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->redirect();
    }
    public function update_entity(){
        $this->check_session();
        
        $where = array(
            "id_submit_entity" => $this->input->post("id_submit_entity")
        );
        $field = array(
            "entity_wit_id","entity_name"
        );
        $result = selectRow("tbl_entity",$where,$field);
        $entity_detail = $result->result_array();
        $doc = $this->input->post("entity_desc");
        $id = $this->input->post("entity_name");
        $config = array(
            array(
                "field" => "entity_name",
                "label" => "Entity Name",
                "rules" => "required"
            )
        );
        $this->form_validation->set_rules($config);
        if($this->form_validation->run()){
            /*check double entity in 1 id_wit_acc*/
            $where = array(
                "entity_name" => $this->input->post("entity_name"),
                "id_wit_ai_acc" => $this->id_wit_acc
            );
            $field = array(
                "id_wit_ai_acc"
            );
            $check = selectRow("tbl_entity",$where,$field);
            /*check if data exists*/
            if($check->num_rows() > 0){
                //kalau ada
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit","Data Exists");
            }
            else{
                $response = $this->wit->put_entities($entity_detail[0]["entity_name"],$id,$doc);
                if($response){
                    if ($response["err"]) {
                        $this->session->set_flashdata("status_wit","error");
                        $this->session->set_flashdata("msg_wit",$response["err"]);
                    } 
                    else {
                        $respond = json_decode($response["response"],true);
                        if(!array_key_exists("error",$respond)){
                            $msg = "Entity is successfully updated to Wit.ai";
                            $this->session->set_flashdata("status_wit","success");
                            $this->session->set_flashdata("msg_wit",$msg);
    
                            $where = array(
                                "id_submit_entity" => $this->input->post("id_submit_entity")
                            );
                            $data = array(
                                "entity_wit_id" => $respond["id"],
                                "entity_name" => $respond["name"],
                                "entity_desc" => $respond["doc"],
                                "status_aktif_entity" => 1,
                                "tgl_entity_last_modified" => date("Y-m-d H:i:s"),
                                "id_user_entity_last_modified" => $this->session->id_user
                            );
                            updateRow("tbl_entity",$data,$where);
                            $this->session->set_flashdata("status_entity","success");
                            $this->session->set_flashdata("msg_entity","Entity is successfully updated");
                        }
                        else{
                            $this->session->set_flashdata("status_wit","error");
                            $this->session->set_flashdata("msg_wit",$respond["error"]);
                        }
                    }
                }
            }
            $this->redirect();
        }
        else{
            $this->page_generator->req();
            $this->page_generator->head_close();
            $this->page_generator->content_open();
            $this->load->view("function/v_entity_reupdate");
            $this->page_generator->close();
        }
    }
    public function delete_entity($id_submit_entity){
        $this->check_session();
        $where = array(
            "id_submit_entity" => $id_submit_entity
        );
        $field = array(
            "entity_name"
        );
        $result = selectRow("tbl_entity",$where,$field);
        $entity_detail = $result->result_array();

        $response = $this->wit->delete_entities($entity_detail[0]["entity_name"]);
        if($response){
            if ($response["err"]) {
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit",$response["err"]);
            } 
            else {
                $respond = json_decode($response["response"],true);
                if(!array_key_exists("error",$respond)){
                    
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit","Data is successfully removed from Wit.ai");
                    $where = array(
                        "id_submit_entity" => $id_submit_entity
                    );
                    $data = array(
                        "status_aktif_entity" => 0,
                        "tgl_entity_last_modified" => date("Y-m-d H:i:s"),
                        "id_user_entity_last_modified" => $this->session->id_user
                    );
                    updateRow("tbl_entity",$data,$where);
                    $this->session->set_flashdata("status_entity","error");
                    $this->session->set_flashdata("msg_entity","Data is successfully deleted");
                }
                else{
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$respond["error"]);
                }
            }
        }
        $this->redirect();
    }
    public function remove_entity($id_submit_entity){
        $this->check_session();
        $where = array(
            "id_submit_entity" => $id_submit_entity
        );
        $field = array(
            "entity_name"
        );
        $result = selectRow("tbl_entity",$where,$field);
        $entity_detail = $result->result_array();

        $response = $this->wit->delete_entities($entity_detail[0]["entity_name"]);
        if($response){
            if ($response["err"]) {
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit",$response["err"]);
            } 
            else {
                $respond = json_decode($response["response"],true);
                if(!array_key_exists("error",$respond)){
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit","Data is successfully removed from Wit.ai");
                    $where = array(
                        "id_submit_entity" => $id_submit_entity
                    );
                    $data = array(
                        "status_aktif_entity" => 2,
                        "tgl_entity_last_modified" => date("Y-m-d H:i:s"),
                        "id_user_entity_last_modified" => $this->session->id_user
                    );
                    updateRow("tbl_entity",$data,$where);
                    $this->session->set_flashdata("status_entity","error");
                    $this->session->set_flashdata("msg_entity","Data is successfully deleted");
                }
                else{
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$respond["error"]);
                }
            }
        }
        $this->redirect();
    }
    public function reupload_entity($id_submit_entity){
        $this->check_session();
        $where = array(
            "id_submit_entity" => $id_submit_entity
        );
        $field = array(
            "entity_name","entity_desc"
        );
        $result = selectRow("tbl_entity",$where,$field);
        $entity_detail = $result->result_array();

        $id = $entity_detail[0]["entity_name"];
        $doc = $entity_detail[0]["entity_desc"];

        $response = $this->wit->post_entities($entity_detail[0]["entity_name"],$id,$doc);
        if($response){
            if ($response["err"]) {
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit",$response["err"]);
            } 
            else {
                $respond = json_decode($response["response"],true);
                if(!array_key_exists("error",$respond)){
                    $this->session->set_flashdata("status_wit","success");
                    $this->session->set_flashdata("msg_wit","Data is successfully added to Wit.ai");
                    $where = array(
                        "id_submit_entity" => $id_submit_entity
                    );
                    $data = array(
                        "entity_wit_id" => $respond["id"],
                        "entity_name" => $respond["name"],
                        "entity_desc" => $respond["doc"],
                        "status_aktif_entity" => 1,
                        "tgl_entity_last_modified" => date("Y-m-d H:i:s"),
                        "id_user_entity_last_modified" => $this->session->id_user
                    );
                    updateRow("tbl_entity",$data,$where);
                    $this->session->set_flashdata("status_entity","success");
                    $this->session->set_flashdata("msg_entity","Data is successfully activated");
                }
                else{
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$respond["error"]);
                }
            }
        }
        $this->redirect();
    }
    public function values($id_submit_entity){
        $this->check_session();
        $this->session->id_submit_entity = $id_submit_entity;
        $where = array(
            "id_entity" => $id_submit_entity,
            "status_aktif_entity_value <" => 2
        );
        $field = array(
            "id_submit_entity_value","entity_value","status_aktif_entity_value","tgl_entity_value_last_modified"
        );
        $result = selectRow("tbl_entity_value",$where,$field);
        $data["entity_values"] = $result->result_array();
        $data["entity"] = get1Value("tbl_entity","entity_name",array("id_submit_entity" => $id_submit_entity));
        $data["id_submit_entity"] = $id_submit_entity;
        $this->page_generator->req();
        $this->load->view("plugin/form/form-css");
        $this->load->view("plugin/datatable/datatable-css");
        $this->page_generator->head_close();
        $this->page_generator->navbar();
        $this->page_generator->content_open();
        $this->load->view("function/v_entity_values",$data);
        $this->page_generator->close();
        $this->load->view("plugin/form/form-js");
        $this->load->view("function/v_entity_js");
        $this->load->view("plugin/datatable/datatable-js");

        $this->session->id_submit_entity = $id_submit_entity;
        $this->session->entity = $data["entity"];
    }
    public function values_recycle_bin($id_submit_entity){
        $this->check_session();
        $where = array(
            "id_entity" => $id_submit_entity,
            "status_aktif_entity_value" => 2
        );
        $field = array(
            "id_submit_entity_value","entity_value","status_aktif_entity_value","tgl_entity_value_last_modified"
        );
        $result = selectRow("tbl_entity_value",$where,$field);
        $data["entity_values"] = $result->result_array();
        $data["entity"] = get1Value("tbl_entity","entity_name",array("id_submit_entity" => $id_submit_entity));
        $data["id_submit_entity"] = $id_submit_entity;
        $this->page_generator->req();
        $this->load->view("plugin/form/form-css");
        $this->load->view("plugin/datatable/datatable-css");
        $this->page_generator->head_close();
        $this->page_generator->navbar();
        $this->page_generator->content_open();
        $this->load->view("function/v_entity_values_recycle_bin",$data);
        $this->page_generator->close();
        $this->load->view("plugin/form/form-js");
        $this->load->view("function/v_entity_js");
        $this->load->view("plugin/datatable/datatable-js");

        $this->session->id_submit_entity = $id_submit_entity;
        $this->session->entity = $data["entity"];
    }
    public function insert_entity_value(){
        $this->check_session();
        $checks = $this->input->post("add_values");
        if($checks != ""){
            foreach($checks as $a){
                $config = array(
                    array(
                        "field" => "id_entity",
                        "label" => "ID Entity",
                        "rules" => "required"
                    ),
                    array(
                        "field" => "entity_value".$a,
                        "label" => "Value",
                        "rules" => "required"
                    )
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run()){
                    /*check double valud*/
                    $where = array(
                        "entity_value" => $this->input->post("entity_value".$a),
                        "id_entity" => $this->session->id_submit_entity,
                    );
                    $field = array(
                        "entity_value"
                    );
                    $check = selectRow("tbl_entity_value",$where,$field);
                    /*check double value*/
                    if($check->num_rows() > 0){
                        $this->session->set_flashdata("status_wit","error");
                        $this->session->set_flashdata("msg_wit","Data Exists");
                    }
                    else{
                        $value = $this->input->post("entity_value".$a);
                        $expression = $this->input->post("expression".$a);
                        $expression_list = explode(",",$expression);
                        $data = array(
                            "id_entity" => $this->input->post("id_entity"),
                            "entity_value" => $value,
                            "tgl_entity_value_last_modified" => date("Y-m-d H:i:s"),
                            "status_aktif_entity_value" => 0,
                            "id_user_entity_value_last_modified" => $this->session->id_user
                        );
                        $id_submit_entity_value = insertRow("tbl_entity_value",$data);
                        $this->session->set_flashdata("status_value","success");
                        $this->session->set_flashdata("msg_value","Value is successfully added, not activated");
    
                        for($a = 0; $a<count($expression_list); $a++){
                            $data = array(
                                "id_entity_value" => $id_submit_entity_value,
                                "entity_value_expression" => $expression_list[$a],
                                "status_aktif_entity_value_expression" => 1,
                                "tgl_entity_value_expression_last_modified" => date("Y-m-d H:i:s"),
                                "id_user_entity_value_expression_last_modified" => $this->session->id_user
                            );
                            insertRow("tbl_entity_value_expression",$data);
                        }
                        $this->session->set_flashdata("status_expression","success");
                        $this->session->set_flashdata("msg_expression","Expressions are successfully added");
                        $response = $this->wit->post_entities_value($this->session->entity,$value,$expression_list);
                        if ($response["err"]) {
                            $this->session->set_flashdata("status_wit","error");
                            $this->session->set_flashdata("msg_wit",$response["err"]);
                        } 
                        else {
                            $respond = json_decode($response["response"],true);
                            if(!array_key_exists("error",$respond)){
                                $this->session->set_flashdata("status_wit","success");
                                $this->session->set_flashdata("msg_wit","Value is successfully uploaded to Wit.ai");
                                $where = array(
                                    "id_submit_entity_value" => $id_submit_entity_value   
                                );
                                $data = array(
                                    "status_aktif_entity_value" => 1
                                );
                                updateRow("tbl_entity_value",$data,$where);
                                $this->session->set_flashdata("status_value","success");
                                $this->session->set_flashdata("msg_value","Value is successfully added, activated");
                            }
                            else{
                                $this->session->set_flashdata("status_wit","error");
                                $this->session->set_flashdata("msg_wit",$respond["error"]);
                            }
                        }
                    }
                }
            }
        }
        //$this->redirect_values();
    
    }
    public function insert_single_entity_value(){ //dipake di samples, buat insert value baru dari smaples yang ga ada

        $entity_value = $this->input->post("entity_value");
        $entity_name = $this->input->post("entity_name");
        $id_entity = $this->input->post("id_entity");
        $response = $this->wit->post_entities_value($entity_name,$entity_value,"");
        $respond = json_decode($response["response"],true);
        if(!array_key_exists("error",$respond)){
            $data = array(
                "id_entity" => $id_entity,
                "entity_value" => $entity_value,
                "tgl_entity_value_last_modified" => date("Y-m-d H:i:s"),
                "status_aktif_entity_value" => 1,
                "id_user_entity_value_last_modified" => $this->session->id_user
            );
            $id_entity_value = insertRow("tbl_entity_value",$data);
            $response = array(
                "id_entity_value" => $id_entity_value
            );
        }
        echo json_encode($response);
    }
    public function insert_single_expression(){ //dipake di samples, buat insert value baru dari smaples yang ga ada

        $id_entity_value = $this->input->post("id_entity_value");
        $expression = $this->input->post("expression");
        $data = array(
            "id_entity_value" => $id_entity_value,
            "entity_value_expression" => $expression,
            "status_aktif_entity_value_expression" => 1,
            "tgl_entity_value_expression_last_modified" => date("Y-m-d H:i:s"),
            "id_user_entity_value_expression_last_modified" => $this->session->id_user
        );
        insertRow("tbl_entity_value_expression",$data);
    }
    public function update_entity_value(){
        $this->check_session();
        $config = array(
            array(
                "field" => "entity_value",
                "label" => "Value",
                "rules" => "required"
            ),
            array(
                "field" => "id_submit_entity_value",
                "label" => "ID Value",
                "rules" => "required"
            )
        );
        $this->form_validation->set_rules($config);
        if($this->form_validation->run()){
            //echo $this->input->post("id_submit_entity_value");
            $where = array(
                "entity_value" => $this->input->post("entity_value"),
                "id_submit_entity_value !=" => $this->input->post("id_submit_entity_value"),
                "id_entity" => $this->session->id_submit_entity
            );
            $field = array(
                "entity_value"
            );
            $check = selectRow("tbl_entity_value",$where,$field);
            if($check->num_rows() > 0){
                
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit","Local Data Exists");
            }
            else{
                
                $where = array(
                    "id_submit_entity_value" => $this->input->post("id_submit_entity_value")
                );
                $entity_value = get1Value("tbl_entity_value","entity_value",$where);

                //memastikan diwit.ai gak ada
                $respond = $this->wit->delete_entities_value($this->session->entity,$entity_value);
                $msg = "";
                if($respond){
                    if($respond["err"]){
                        $msg .= $respond["err"].". ";
                        $this->session->set_flashdata("status_wit","error");
                        $this->session->set_flashdata("msg_wit","Wit".$msg);
                    }
                    else{
                        $respond = json_decode($respond["response"],true);
                        if(!array_key_exists("error",$respond)){
                            $msg .= "Value is successfully deleted from Wit.ai".". ";
                            $this->session->set_flashdata("status_wit","success");
                            $this->session->set_flashdata("msg_wit",$msg);
                        }
                        else{
                            $msg .= $respond["error"].". ";
                            $this->session->set_flashdata("status_wit","error");
                            $this->session->set_flashdata("msg_wit",$msg);
                        }
                    }
                }
                //mengupload entity kembali
                $entity_value = $this->input->post("entity_value");
                $expression = $this->input->post("expression");
                $expression_list = explode(",",$expression);
                $respond = $this->wit->post_entities_value($this->session->entity,$entity_value,$expression_list);
                if($respond){
                    if($respond["err"]){
                        $msg .= $respond["err"].". ";
                        $this->session->set_flashdata("status_wit","error");
                        $this->session->set_flashdata("msg_wit","Wit".$msg);
                    }
                    else{
                        $respond = json_decode($respond["response"],true);
                        if(!array_key_exists("error",$respond)){
                            $msg .= "Value is successfully added to Wit.ai".". ";
                            $this->session->set_flashdata("status_wit","success");
                            $this->session->set_flashdata("msg_wit",$msg);
                            
                            $where = array(
                                "id_submit_entity_value" => $this->input->post("id_submit_entity_value")
                            );
                            $data = array(
                                "entity_value" => $entity_value,
                                "tgl_entity_value_last_modified" => date("Y-m-d H:i:s"),
                                "id_user_entity_value_last_modified" => $this->session->id_user
                            );
                            updateRow("tbl_entity_value",$data,$where);
                            $this->session->set_flashdata("status_value","success");
                            $this->session->set_flashdata("msg_value","Value is successfully added, not activated");

                            $where = array(
                                "id_entity_value" => $this->input->post("id_submit_entity_value")
                            );
                            $data = array(
                                //deactive semua expressionnya
                                "status_aktif_entity_value_expression" => 0
                            );
                            updateRow("tbl_entity_value_expression",$data,$where);
                
                            for($a = 0; $a<count($expression_list); $a++){
                                $data = array(
                                    "id_entity_value" => $this->input->post("id_submit_entity_value"),
                                    "entity_value_expression" => $expression_list[$a],
                                    "status_aktif_entity_value_expression" => 1,
                                    "tgl_entity_value_expression_last_modified" => date("Y-m-d H:i:s"),
                                    "id_user_entity_value_expression_last_modified" => $this->session->id_user
                                );
                                insertRow("tbl_entity_value_expression",$data);
                            }
                            $this->session->set_flashdata("status_expression","success");
                            $this->session->set_flashdata("msg_expression","Expressions are successfully added");
                
                        }
                        else{
                            $msg .= $respond["error"].". ";
                            $this->session->set_flashdata("status_wit","error");
                            $this->session->set_flashdata("msg_wit",$msg);
                        }
                    }
                }
            }
            $this->redirect_values();
        }
        else{
            $this->page_generator->req();
            $this->page_generator->head_close();
            $this->page_generator->content_open();
            $this->load->view("function/v_entity_values_reupdate");
            $this->page_generator->close();
        }
    }
    public function delete_entity_value($id_submit_entity_value){
        $this->check_session();

        $where = array(
            "id_submit_entity_value" => $id_submit_entity_value
        );
        $field = array(
            "entity_value"
        );
        $result = selectRow("tbl_entity_value",$where,$field);
        $result_array = $result->result_array();
        $entity_value = $result_array[0]["entity_value"];
        $respond = $this->wit->delete_entities_value($this->session->entity,$entity_value);
    
        if($respond){
            if($respond["err"]){
                $msg .= $respond["err"].". ";
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit",$msg);
            }
            else{
                $respond = json_decode($respond["response"],true);
                
                if(!array_key_exists("error",$respond)){
                    $msg .= "Value is successfully deleted from Wit.ai".". ";
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                    $where = array(
                        "id_submit_entity_value" => $id_submit_entity_value
                    );
                    $data = array(
                        "status_aktif_entity_value" => 0,
                        "tgl_entity_value_last_modified" => date("Y-m-d H:i:s"),
                        "id_user_entity_value_last_modified" => $this->session->id_user
                    );
                    updateRow("tbl_entity_value",$data,$where);
                    $this->session->set_flashdata("status_value","error");
                    $this->session->set_flashdata("msg_value","Entity is successfully deactivated");
                }
                else{
                    $msg .= $respond["error"].". ";
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                }
            }
        }
        $this->redirect_values();
    }
    public function remove_entity_value($id_submit_entity_value){
        $this->check_session();
        

        $where = array(
            "id_submit_entity_value" => $id_submit_entity_value
        );
        $field = array(
            "entity_value"
        );
        $result = selectRow("tbl_entity_value",$where,$field);
        $result_array = $result->result_array();
        $entity_value = $result_array[0]["entity_value"];

        $respond = $this->wit->delete_entities_value($this->session->entity,$entity_value);
        $msg = "";
        if($respond){
            if($respond["err"]){
                $msg .= $respond["err"].". ";
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit",$msg);
            }
            else{
                $respond = json_decode($respond["response"],true);
                if(!array_key_exists("error",$respond)){
                    $msg .= "Value is successfully deleted from Wit.ai".". ";
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                    $where = array(
                        "id_submit_entity_value" => $id_submit_entity_value
                    );
                    $data = array(
                        "status_aktif_entity_value" => 2,
                        "tgl_entity_value_last_modified" => date("Y-m-d H:i:s"),
                        "id_user_entity_value_last_modified" => $this->session->id_user
                    );
                    updateRow("tbl_entity_value",$data,$where);
                    $this->session->set_flashdata("status_value","error");
                    $this->session->set_flashdata("msg_value","Entity is successfully deleted");
                }
                else{
                    $msg .= $respond["error"].". ";
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                }
            }
        }
        $this->redirect_values();
    }
    public function reupload_entity_value($id_submit_entity_value){
        $this->check_session();
        
        $where = array(
            "id_submit_entity_value" => $id_submit_entity_value
        );
        $field = array(
            "entity_value"
        );
        $result = selectRow("tbl_entity_value",$where,$field);
        $result_array = $result->result_array();
        $entity_value = $result_array[0]["entity_value"];

        $where = array(
            "id_entity_value" => $id_submit_entity_value,
            "status_aktif_entity_value_expression" => 1
        );
        $field = array(
            "entity_value_expression"
        );
        $result = selectRow("tbl_entity_value_expression",$where,$field);
        $result_array = $result->result_array();
        $expression_list = array();
        for($a = 0; $a<count($result_array); $a++){
            $expression_list[$a] = $result_array[$a]["entity_value_expression"];
        }
        $respond = $this->wit->post_entities_value($this->session->entity,$entity_value,$expression_list);
        $msg = "";
        if($respond){
            if($respond["err"]){
                $msg .= $respond["err"].". ";
                $this->session->set_flashdata("status_wit","error");
                $this->session->set_flashdata("msg_wit",$msg);
            }
            else{
                $respond = json_decode($respond["response"],true);
                if(!array_key_exists("error",$respond)){
                    $msg .= "Value is successfully added to Wit.ai".". ";
                    $this->session->set_flashdata("status_wit","success");
                    $this->session->set_flashdata("msg_wit",$msg);
                    $where = array(
                        "id_submit_entity_value" => $id_submit_entity_value
                    );
                    $data = array(
                        "status_aktif_entity_value" => 1,
                        "tgl_entity_value_last_modified" => date("Y-m-d H:i:S")
                    );
                    updateRow("tbl_entity_value",$data,$where);
                    $this->session->set_flashdata("status_value","success");
                    $this->session->set_flashdata("msg_value","Entity is successfully activated");
            
                }
                else{
                    $msg .= $respond["error"].". ";
                    $this->session->set_flashdata("status_wit","error");
                    $this->session->set_flashdata("msg_wit",$msg);
                }
            }
        }
        $this->redirect_values();
    }
    public function redirect(){
        $this->check_session();
        redirect("function/entity");
    }
    public function redirect_values(){
        $this->check_session();
        redirect("function/entity/values/".$this->session->id_submit_entity);
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