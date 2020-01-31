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
    public function get_intent_list(){
        $where = array(
            "status_aktif_intent" => 1
        );
        $field = array(
            "intent"
        );
        $result = selectRow("tbl_intent",$where,$field);
        $response = array();
        if($result->num_rows() > 0){
            $response = array(
                "status" => "success",
                "msg" => "Intent Found",
                "data" => $result->result_array()
            );
        }
        else{
            $response = array(
                "error" => "true",
                "status" => "error",
                "msg" => "Intent Not Found"
            );
        }
        echo json_encode($response);
    }
    public function get_entity_list(){
        $where = array(
            "status_aktif_entity" => 1
        );
        $field = array(
            "entity_name"
        );
        $result = selectRow("tbl_entity",$where,$field);
        $response = array();
        if($result->num_rows() > 0){
            $response = array(
                "status" => "success",
                "msg" => "Entity Found",
                "data" => $result->result_array()
            );
        }
        else{
            $response = array(
                "error" => "true",
                "status" => "error",
                "msg" => "Entity Not Found"
            );
        }
        echo json_encode($response);
    }
}
?>