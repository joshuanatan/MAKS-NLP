<?php
class Endpoint extends CI_Controller{

     public function get_text_meaning(){
        #mengambil data pada request header
        $headers = getallheaders();

         #memeriksa apakah variable client-token disertakan dalam request header
         if(array_key_exists("client-token",$headers)){

            #[begin] validasi token dengan endpoint (memeriksa hak akses endpoint)
            $where = array(
                "status_aktif_token" => 1,
                "token" => $headers["client-token"]
            );  
            $result = selectRow("tbl_token",$where);
            #[end] validasi token dengan endpoint

             #memeriksa status hak akses
             if($result->num_rows() > 0){
                #status hak akses diterima
                
                #[begin] komunikasi dengan Wit.ai
                $this->load->library("Wit");
                $msg = $this->input->post("search_text");
                $encoded = rawurlencode($msg);
                $response = $this->wit->get_message($encoded);
                #[end] komunikasi dengan Wit.ai

                #memeriksa status respon dari komunikasi
                if($response){

                     #memeriksa keberhasilan komunikasi
                     if ($response["err"]) {
                        #komunikasi gagal, tidak terjadi komunikasi keluar sistem
                        $data = array(
                            "error" => "true",
                            "status" => "error",
                            "msg" => strtoupper("Request is not sent"),
                            "text_entity_list" => "-"
                        );
                     } 
                     else {
                        #komunikasi berhasil, terjadi komunikasi dengan Wit.ai

                        #mengekstrak hasil komunikasi
                        $respond = json_decode($response["response"],true);
                        $response = array();
                        
                         #melihat array key "msg_id" sebagai tanda teks berhasil di ekstrak
                         if(array_key_exists("msg_id",$respond)){

                            #[begin] reform respon dari Wit.ai menjadi format yang sesuai dengan WSDL
                            foreach($respond["entities"] as $key => $value){
                                for($a = 0; $a<count($respond["entities"][$key]); $a++){
                                    if(strtoupper($key) == "INTENT"){
                                        $response["intent"] = $respond["entities"][$key][$a]["value"];
                                    }
                                    else{
                                        //bisa multiple value seperti minta yang satuannya kilogram & meter
                                        $response["entity"][$key][$a] = $respond["entities"][$key][$a]["value"];
                                    }
                                }
                            }
                            $data = array(
                                "status" => "success",
                                "msg" => "EXTRACTION SUCCESS",
                                "text_entity_list" => $response
                            );
                            #[end] reform respon
                         }
                         else{
                            #respon error dari Wit.ai
                            $data = array(
                                "error" => "true",
                                "status" => "error",
                                "msg" => strtoupper($respond["error"]),
                                "text_entity_list" => "-"
                            );
                         }
                     }
                }
             }

            else{
                #respon error apabila token tidak ditemukan / tidak memiliki hak akses
                $data = array(
                    "error" => "true",
                    "status" => "error",
                    "msg" => "TOKEN IS NOT RECOGNIZED",
                    "text_entity_list" => NULL
                );
            }
         }

        else{
            #respon error apabila tidak ada key client-token dalam header
            $response = array(
                "error" => "true",
                "status" => "error",
                "msg" => "TOKEN IS NOT PROVIDED",
                "result" => "-"
            );
        }
        #mengirim respon
        header("Content-type:application/json");
        echo json_encode($data);
     }

    public function get_entity($last_pull){
        $headers = getallheaders();
        if(array_key_exists("client-token",$headers)){
            $where = array(
                "status_aktif_token" => 1,
                "token" => $headers["client-token"]
            );  
            $result = selectRow("tbl_token",$where);
            if($result->num_rows() > 0){
                /** 
                 * Ini ngecek liat apakah entitynya pernah di update / valuenya pernah di update / di add baru setelah pengambilan pertama, metodenya itu harus ngambil entitynya dlu baru bisa dapetin valuenya, oleh karena itu, ngambilnya di detail_entity untuk ngecheck either entity / valuenya ada yang baru
                */
                $where = "status_aktif_entity = 1 and (tgl_entity_add > '".urldecode($last_pull)."' or tgl_entity_edit > '".urldecode($last_pull)."') and entity_name != 'intent'";
                
                $field = array(
                    "id_submit_entity","entity_name"
                );
                $result = selectRow("tbl_entity",$where,$field);
                
                if($result->num_rows() > 0){
                    $entity_array = $result->result_array();
                    $response = array(
                        "status" => "success",
                        "msg" => "DATA FOUND",
                        "result" => $entity_array
                    );
                }
                else{
                    $response = array(
                        "error" => "true",
                        "status" => "error",
                        "msg" => "DATA NOT FOUND",
                        "result" => "-"
                    );
                }
            }
            else{
                $response = array(
                    "error" => "true",
                    "status" => "error",
                    "msg" => "TOKEN IS NOT RECOGNIZED",
                    "result" => "-"
                );
            }
        }
        else{
            $response = array(
                "error" => "true",
                "status" => "error",
                "msg" => "TOKEN IS NOT PROVIDED",
                "result" => "-"
            );
        }
        header("Content-Type:application/json");
        echo json_encode($response);
    }
    public function get_intent($last_pull){
        $headers = getallheaders();
        if(array_key_exists("client-token",$headers)){
            $where = array(
                "endpoint_name" => "get_intent",
                "client_token" => $headers["client-token"]
            );  
            $result = selectRow("detail_endpoint_auth",$where);
            if($result->num_rows() > 0){
                $where = "status_aktif_entity_value = 1 and (tgl_entity_value_add > '".urldecode($last_pull)."' or tgl_entity_value_edit > '".urldecode($last_pull)."')";
                $field = array(
                    "id_submit_entity_value","entity_value"
                );
                $result = selectRow("detail_intent",$where,$field);
                if($result->num_rows() > 0){
                    $response = array(
                        "status" => "success",
                        "msg" => "DATA FOUND",
                        "result" => $result->result_array()
                    );
                }
                else{
                    $response = array(
                        "error" => "true",
                        "status" => "error",
                        "msg" => "DATA NOT FOUND",
                        "result" => "-"
                    );
                }
            }
            else{
                $response = array(
                    "error" => "true",
                    "status" => "error",
                    "msg" => "TOKEN IS NOT RECOGNIZED",
                    "result" => "-"
                );
            } 
        }  
        else{
            $response = array(
                "error" => "true",
                "status" => "error",
                "msg" => "TOKEN IS NOT PROVIDED",
                "result" => "-"
            );
        }
        header("Content-Type:application/json");
        echo json_encode($response);
    }
    public function get_active_entity_list(){
        $headers = getallheaders();
        if(array_key_exists("client-token",$headers)){
           
            $where = array(
                "status_aktif_entity" => 1,
                "entity_name !=" => "intent" 
            );
            $field = array(
                "id_submit_entity","entity_name"
            );
            $result = selectRow("tbl_entity",$where,$field);
            
            if($result->num_rows() > 0){
                $entity_array = $result->result_array();
                $response = array(
                    "status" => "success",
                    "msg" => "DATA FOUND",
                    "result" => $entity_array
                );
            }
            else{
                $response = array(
                    "error" => "true",
                    "status" => "error",
                    "msg" => "DATA NOT FOUND",
                    "result" => "-"
                );
            }
        }
        else{
            $response = array(
                "error" => "true",
                "status" => "error",
                "msg" => "TOKEN IS NOT PROVIDED",
                "result" => "-"
            );
        }
        header("Content-Type:application/json");
        echo json_encode($response);
    }
    public function get_active_intent_list(){
        $headers = getallheaders();
        if(array_key_exists("client-token",$headers)){
           
            $where = array(
                "status_aktif_entity_value" => 1,
                "entity_name" => "intent" 
            );
            $field = array(
                "id_submit_entity_value","entity_value"
            );
            $result = selectRow("detail_intent",$where,$field);
            
            if($result->num_rows() > 0){
                $entity_array = $result->result_array();
                $response = array(
                    "status" => "success",
                    "msg" => "DATA FOUND",
                    "result" => $entity_array
                );
            }
            else{
                $response = array(
                    "error" => "true",
                    "status" => "error",
                    "msg" => "DATA NOT FOUND",
                    "result" => "-"
                );
            }
        }
        else{
            $response = array(
                "error" => "true",
                "status" => "error",
                "msg" => "TOKEN IS NOT PROVIDED",
                "result" => "-"
            );
        }
        header("Content-Type:application/json");
        echo json_encode($response);
    }
}
?>