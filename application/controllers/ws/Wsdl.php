<?php
class Wsdl extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        $where = array(
            "status_aktif_endpoint" => 1
        );
        $field = array(
            "endpoint_name"
        );
        $result = selectRow("endpoint_documentation",$where,$field);
        $data["endpoint"] = $result->result_array();
        $this->page_generator->req();
        $this->page_generator->head_close();
        $this->page_generator->content_open();
        $this->load->view("ws/wsdl_index",$data);
        $this->page_generator->close();
    }
    public function get_text_meaning(){
        $where = array(
            "status_aktif_endpoint" => 1,
            "endpoint_name" => "get_text_meaning"
        );
        $field = array(
            "endpoint_name","endpoint_http_method","endpoint_uri","endpoint_input","endpoint_output"
        );
        $result = selectRow("endpoint_documentation",$where,$field)->row();
        $array = array(
            "endpoint_name" => $result->endpoint_name,
            "endpoint_method" => $result->endpoint_http_method,
            "endpoint_uri" => $result->endpoint_uri,
            "endpoint_input" => $result->endpoint_input,
            "endpoint_output" => $result->endpoint_output,
            "request" => array(
                "header" => array(
                    "client_token" => "client_token_here"
                ),
                "body" => array(
                    "search_text" => "search_text"
                )
            ),
            "response" => array(
                "status" => "success",
                "msg" => "EXTRACTION SUCCESS",
                "text_entity_list" => array(
                    "intent" => "Request Intent",
                    "entity" => array(
                        "entity1" => array(
                            "value1","value2"
                        ),
                        "entity2" => array(
                            "value1"
                        )
                    )
                )
            ),
            "response_error" => array(
                "error" => "true",
                "status" => "error",
                "msg" => "ERROR MESSAGE",
                "text_entity_list" => "-"
            )

        );
        header("Content-type:application/json");
        echo json_encode($array);
    }
    public function get_entity(){
        $where = array(
            "status_aktif_endpoint" => 1,
            "endpoint_name" => "get_entity"
        );
        $field = array(
            "endpoint_name","endpoint_http_method","endpoint_uri","endpoint_input","endpoint_output"
        );
        $result = selectRow("endpoint_documentation",$where,$field)->row();
        $array = array(
            "endpoint_name" => $result->endpoint_name,
            "endpoint_method" => $result->endpoint_http_method,
            "endpoint_uri" => $result->endpoint_uri,
            "endpoint_input" => $result->endpoint_input,
            "endpoint_output" => $result->endpoint_output,
            "request" => array(
                "header" => array(
                    "client-token" => "client_token_here"
                )
            ),
            "response" => array(
                "status" => "success",
                "msg" => "DATA FOUND",
                "result" => array(
                    array(
                        "id_submit_entity" => 1,
                        "entity_name" => "Entity Name"
                    ),
                    array(
                        "id_submit_entity" => 2,
                        "entity_name" => "Entity Name 2"
                    )
                )
            ),
            "response_error" => array(
                "error" => "true",
                "status" => "error",
                "msg" => "DATA NOT FOUND",
                "result" => "-"
            )
        );
        header("Content-type:application/json");
        echo json_encode($array);
    }
    public function get_intent(){
        $where = array(
            "status_aktif_endpoint" => 1,
            "endpoint_name" => "get_intent"
        );
        $field = array(
            "endpoint_name","endpoint_http_method","endpoint_uri","endpoint_input","endpoint_output"
        );
        $result = selectRow("endpoint_documentation",$where,$field)->row();
        $array = array(
            "endpoint_name" => $result->endpoint_name,
            "endpoint_method" => $result->endpoint_http_method,
            "endpoint_uri" => $result->endpoint_uri,
            "endpoint_input" => $result->endpoint_input,
            "endpoint_output" => $result->endpoint_output,
            "request" => array(
                "header" => array(
                    "client_token" => "client_token_here"
                )
            ),
            "response" => array(
                "status" => "success",
                "msg" => "DATA FOUND",
                "result" => array(
                    array(
                        "id_submit_entity_value" => 1,
                        "entity_value" => "Intent 1"
                    ),
                    array(
                        "id_submit_entity_value" => 2,
                        "entity_value" => "Intent 2"
                    )
                )
            ),
            "response_error" => array(
                "error" => "true",
                "status" => "error",
                "msg" => "DATA NOT FOUND",
                "result" => "-"
            )

        );
        header("Content-type:application/json");
        echo json_encode($array);
    }
}
?>