<?php

class Wit{
    private $server_access_token;
    protected $CI;
    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->helper('standardquery_helper');
        $this->get_server_token();
    }
    private function get_server_token(){
        $where = array(
            "status_aktif_wit_ai_acc" => 1
        );
        $field = array(
            "server_access_token"
        );
        $result = selectRow("tbl_wit_ai_acc",$where,$field);
        $result_array = $result->result_array();
        $this->server_access_token = $result_array[0]["server_access_token"];
    }
    public function check_load_library(){
        return $this->server_access_token;
    }
     public function get_message($message){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.wit.ai/message?q=".$message,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$this->server_access_token
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $respond["err"] = $err;
        $respond["response"] = $response;
        curl_close($curl);
        return $respond;
     }
     
    /*entities*/
    public function get_entities(){}
    public function get_entites_detail(){}
    
    public function post_entities($id = "", $doc = ""){
        if($id != ""){
            $curl_field["id"] = $id;
        }
        else{
            return false;
        }
        if($doc != ""){
            $curl_field["doc"] = $doc;
        } 

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.wit.ai/entities",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($curl_field),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "Authorization: Bearer ".$this->server_access_token
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $respond["err"] = $err;
        $respond["response"] = $response;
        curl_close($curl);
        return $respond;
    }
    public function put_entities($entity_name = "",$id = "",$doc = "",$values = "", $lookups = ""){
        if($entity_name == ""){
            return false;
        }
        if($id != ""){
            $curl_field["id"] = $id;
        }
        if($doc != ""){
            $curl_field["doc"] = $doc;
        }
        if($values != ""){
            $curl_field["values"] = $values;
        }
        if($lookups != ""){
            $curl_field["lookups"] = $lookups;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.wit.ai/entities/".$entity_name,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($curl_field),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "Authorization: Bearer ".$this->server_access_token
            ),
        ));
        $respond["response"] = curl_exec($curl);
        $respond["err"] = curl_error($curl);

        curl_close($curl);
        return $respond;
    }
    public function delete_entities($entity_name = ""){
        if($entity_name == ""){
            return false;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.wit.ai/entities/".$entity_name,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "Authorization: Bearer ".$this->server_access_token
            ),
        ));
        $respond["response"] = curl_exec($curl);
        $respond["err"] = curl_error($curl);
        curl_close($curl);
        return $respond;
    }

    /*entity value*/
    public function post_entities_value($entity_name = "",$value = "",$expressions = "",$metadata = ""){
        if($entity_name == ""){
            return false;
        }
        if($value == ""){
            return false;
        }
        if($value != ""){
            $curl_field["value"] = $value;
        }
        if($expressions != ""){
            $curl_field["expressions"] = $expressions;
        }
        if($metadata != ""){
            $curl_field["metadata"] = $metadata;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.wit.ai/entities/".$entity_name."/values",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($curl_field),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "Authorization: Bearer ".$this->server_access_token
            ),
        ));
        $respond["response"] = curl_exec($curl);
        $respond["err"] = curl_error($curl);
        curl_close($curl);
        return $respond;
    }
    public function delete_entities_value($entity_name = "",$entity_value = ""){
        if($entity_name == ""){
            return false;
        }
        if($entity_value == ""){
            return false;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.wit.ai/entities/".rawurlencode($entity_name)."/values/".rawurlencode($entity_value),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "Authorization: Bearer ".$this->server_access_token
            ),
        ));
        $respond["response"] = curl_exec($curl);
        $respond["err"] = curl_error($curl);
        curl_close($curl);
        return $respond;
    }

    /*expressions*/
    public function post_expressions($entity_name = "",$entity_value = "",$expressions = ""){
        if($entity_name == ""){
            return false;
        }
        if($entity_value == ""){
            return false;
        }
        if($expressions == ""){
            return false;
        }
        else{
            $curl_field["expressions"] = $expressions;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.wit.ai/entities/".$entity_name."/values/".$entity_value,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($curl_field),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "Authorization: Bearer ".$this->server_access_token
            ),
        ));
        $respond["response"] = curl_exec($curl);
        $respond["err"] = curl_error($curl);
        curl_close($curl);
        return $respond;
    }
    public function delete_expressions($entity_name = "",$entity_value = "",$expressions = ""){
        if($entity_name == ""){
            return false;
        }
        if($entity_value == ""){
            return false;
        }
        if($expressions == ""){
            return false;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.wit.ai/entities/".$entity_name."/values/".$entity_value."/expressions/".$expressions,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "Authorization: Bearer ".$this->server_access_token
            ),
        ));
        $respond["response"] = curl_exec($curl);
        $respond["err"] = curl_error($curl);
        curl_close($curl);
        return $respond;
    }

    /*samples*/
    public function get_samples(){}

    public function post_samples($text,$entities){
        if($text == ""){
            return false;
        }
        else{
            $curl_field[0]["text"] = $text;
        }
        if($entities == "" || !is_array($entities)){
            return false;
        }
        else{
            $curl_field[0]["entities"] = $entities;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.wit.ai/samples",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($curl_field),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "Authorization: Bearer ".$this->server_access_token
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $respond["err"] = $err;
        $respond["response"] = $response;
        curl_close($curl);
        return $respond;
    }
    public function delete_samples($text){
        if($text == ""){
            return false;
        }
        else{
            $curl_field[0]["text"] = $text;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.wit.ai/samples",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_POSTFIELDS => json_encode($curl_field),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
                "Authorization: Bearer ".$this->server_access_token
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $respond["err"] = $err;
        $respond["response"] = $response;
        curl_close($curl);
        return $respond;
    }
    
    /*apps*/
    public function get_apps(){}
    public function get_apps_detail(){}
    public function post_apps(){}
    public function put_apps(){}
    public function delete_apps(){}

}